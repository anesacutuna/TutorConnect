<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TutorProfileDao.php';
require_once __DIR__ . '/../dao/SubjectDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
class TutorProfileService extends BaseService
{
    private SubjectDao $subjects;
    private BookingDao $bookings;
    public function __construct(?TutorProfileDao $dao = null, ?SubjectDao $subjects = null, ?BookingDao $bookings = null)
    {
        $dao ??= new TutorProfileDao(); parent::__construct($dao);
        $this->subjects = $subjects ?? new SubjectDao(); $this->bookings = $bookings ?? new BookingDao();
    }
    protected function validate(array $data, ?array $current = null, bool $replace = false): array
    {
        Validator::only($data, ['subject_id', 'full_name', 'bio', 'hourly_rate']);
        $required = ['subject_id', 'full_name', 'bio', 'hourly_rate'];
        if (!$current || $replace) Validator::required($data, $required);
        $candidate = array_merge($current ?? [], $data); $out = [];
        if (array_key_exists('subject_id', $data)) $out['subject_id'] = Validator::integer($data['subject_id'], 'subject_id');
        $subjectId = (int) ($candidate['subject_id'] ?? 0);
        if (!$this->subjects->get_by_id($subjectId)) throw new ValidationException('Subject does not exist.');
        if (array_key_exists('full_name', $data)) $out['full_name'] = Validator::text($data['full_name'], 'full_name', 2, 120);
        if (array_key_exists('bio', $data)) {
            $bio = Validator::text($data['bio'], 'bio', 10, 3000); Validator::no_html($bio, 'bio'); $out['bio'] = $bio;
        }
        if (array_key_exists('hourly_rate', $data)) $out['hourly_rate'] = Validator::positive_price($data['hourly_rate']);
        return $out;
    }
    protected function before_delete(int $id): void
    {
        $count = $this->bookings->count_by('tutor_profile_id', $id);
        if ($count > 0) throw new ValidationException("This tutor still has {$count} booking(s). Delete them first.");
    }
}
