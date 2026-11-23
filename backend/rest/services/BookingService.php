<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/TutorProfileDao.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
class BookingService extends BaseService
{
    private UserDao $users;
    private TutorProfileDao $tutors;
    private ReviewDao $reviews;
    public function __construct(?BookingDao $dao = null, ?UserDao $users = null, ?TutorProfileDao $tutors = null, ?ReviewDao $reviews = null)
    {
        $dao ??= new BookingDao(); parent::__construct($dao);
        $this->users = $users ?? new UserDao(); $this->tutors = $tutors ?? new TutorProfileDao(); $this->reviews = $reviews ?? new ReviewDao();
    }
    protected function validate(array $data, ?array $current = null, bool $replace = false): array
    {
        $allowed = ['student_id', 'tutor_profile_id', 'starts_at', 'duration_minutes', 'status', 'notes'];
        Validator::only($data, $allowed);
        if (!$current || $replace) Validator::required($data, ['student_id', 'tutor_profile_id', 'starts_at', 'duration_minutes']);
        $candidate = array_merge($current ?? [], $data); $out = [];
        foreach (['student_id', 'tutor_profile_id'] as $field) {
            if (array_key_exists($field, $data)) $out[$field] = Validator::integer($data[$field], $field);
        }
        $studentId = (int) ($candidate['student_id'] ?? 0);
        $student = $this->users->get_by_id($studentId);
        if (!$student || $student['role'] !== 'student') throw new ValidationException('student_id must identify a student.');
        $tutorId = (int) ($candidate['tutor_profile_id'] ?? 0);
        if (!$this->tutors->get_by_id($tutorId)) throw new ValidationException('Tutor profile does not exist.');

        if (array_key_exists('starts_at', $data)) {
            $start = $this->normalize_start($data['starts_at']);
            if (!$current || $start !== $current['starts_at']) {
                if (new DateTimeImmutable($start, new DateTimeZone('UTC')) <= new DateTimeImmutable('now', new DateTimeZone('UTC'))) {
                    throw new ValidationException('New booking must be in the future.');
                }
            }
            $out['starts_at'] = $start;
        }
        if (array_key_exists('duration_minutes', $data)) {
            $duration = Validator::integer($data['duration_minutes'], 'duration_minutes', 30, 180);
            if ($duration % 15 !== 0) throw new ValidationException('Duration must be in 15-minute steps.');
            $out['duration_minutes'] = $duration;
        }
        if (array_key_exists('status', $data)) {
            $status = $data['status'];
            if (!in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) throw new ValidationException('Invalid booking status.');
            if (!$current && !in_array($status, ['pending', 'confirmed'], true)) throw new ValidationException('New bookings must be pending or confirmed.');
            $transitions = [
                'pending' => ['pending', 'confirmed', 'cancelled'],
                'confirmed' => ['confirmed', 'completed', 'cancelled'],
                'completed' => ['completed'],
                'cancelled' => ['cancelled'],
            ];
            if ($current && !in_array($status, $transitions[$current['status']], true)) throw new ValidationException('Invalid booking status transition.');
            $out['status'] = $status;
        } elseif (!$current) {
            $out['status'] = 'pending';
        }
        if (array_key_exists('notes', $data)) {
            if ($data['notes'] === null) {
                $out['notes'] = null;
            } else {
                $notes = Validator::text($data['notes'], 'notes', 0, 1000);
                Validator::no_html($notes, 'notes');
                $out['notes'] = $notes === '' ? null : $notes;
            }
        } elseif (!$current) {
            $out['notes'] = null;
        }

        $candidate = array_merge($current ?? [], $out);
        if ($current && in_array($current['status'], ['completed', 'cancelled'], true)) {
            foreach (['student_id', 'tutor_profile_id', 'starts_at', 'duration_minutes'] as $field) {
                if (array_key_exists($field, $out) && (string) $out[$field] !== (string) $current[$field]) throw new ValidationException('Completed or cancelled bookings cannot be rescheduled.');
            }
        }
        if (($candidate['status'] ?? '') === 'completed') {
            $end = (new DateTimeImmutable($candidate['starts_at'], new DateTimeZone('UTC')))->modify('+' . (int) $candidate['duration_minutes'] . ' minutes');
            if ($end > new DateTimeImmutable('now', new DateTimeZone('UTC'))) throw new ValidationException('Booking can be completed only after the lesson ends.');
        }
        if (in_array($candidate['status'] ?? '', ['pending', 'confirmed'], true) && $this->dao->has_overlap($candidate, $current ? (int) $current['id'] : null)) {
            throw new ValidationException('Student or tutor already has an overlapping active booking.');
        }
        return $out;
    }
    private function normalize_start(mixed $value): string
    {
        if (!is_string($value) || trim($value) === '') throw new ValidationException("Field 'starts_at' is required.");
        $sarajevo = new DateTimeZone('Europe/Sarajevo');
        $formats = ['!Y-m-d\TH:i', '!Y-m-d H:i:s'];
        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat($format, $value, $sarajevo);
            $errors = DateTimeImmutable::getLastErrors();
            if ($date && (!$errors || (!$errors['warning_count'] && !$errors['error_count']))) {
                return $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            }
        }
        try {
            $date = new DateTimeImmutable($value);
            return $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        } catch (Throwable) {
            throw new ValidationException('Invalid booking date and time.');
        }
    }
    public function add(array $data): array
    {
        return $this->dao->transaction(function () use ($data) {
            $clean = $this->validate($data);
            return $this->output($this->dao->add($clean));
        });
    }
    public function update(array $data, int $id, bool $replace = false): array
    {
        return $this->dao->transaction(fn() => parent::update($data, $id, $replace));
    }
    protected function before_delete(int $id): void
    {
        if ($this->reviews->count_by('booking_id', $id) > 0) throw new ValidationException('This booking has a review. Delete it first.');
    }
}
