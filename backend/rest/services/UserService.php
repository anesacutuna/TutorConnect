<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
class UserService extends BaseService
{
    private BookingDao $bookings;
    public function __construct(?UserDao $dao = null, ?BookingDao $bookings = null)
    {
        $dao ??= new UserDao(); parent::__construct($dao); $this->bookings = $bookings ?? new BookingDao();
    }
    protected function output(array $row): array { unset($row['password_hash']); return $row; }
    protected function validate(array $data, ?array $current = null, bool $replace = false): array
    {
        Validator::only($data, ['full_name', 'email', 'password']);
        if (!$current) Validator::required($data, ['full_name', 'email', 'password']);
        if ($replace) Validator::required($data, ['full_name', 'email']);
        $out = [];
        if (array_key_exists('full_name', $data)) $out['full_name'] = Validator::text($data['full_name'], 'full_name', 2, 120);
        if (array_key_exists('email', $data)) {
            $email = Validator::email($data['email']);
            $existing = $this->dao->get_by_email($email);
            if ($existing && (!$current || (int) $existing['id'] !== (int) $current['id'])) throw new ValidationException('Email is already registered.');
            $out['email'] = $email;
        }
        if (array_key_exists('password', $data)) $out['password_hash'] = password_hash(Validator::password($data['password']), PASSWORD_BCRYPT);
        if (!$current) {
            $out['role'] = 'student';
        }
        return $out;
    }
    protected function before_delete(int $id): void
    {
        $count = $this->bookings->count_by('student_id', $id);
        if ($count > 0) throw new ValidationException("This user still has {$count} booking(s). Delete them first.");
    }
}
