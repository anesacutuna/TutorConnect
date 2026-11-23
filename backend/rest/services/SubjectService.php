<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/SubjectDao.php';
require_once __DIR__ . '/../dao/TutorProfileDao.php';
require_once __DIR__ . '/../../utils/Validator.php';
class SubjectService extends BaseService
{
    private TutorProfileDao $tutors;
    public function __construct(?SubjectDao $dao = null, ?TutorProfileDao $tutors = null)
    {
        $dao ??= new SubjectDao(); parent::__construct($dao); $this->tutors = $tutors ?? new TutorProfileDao();
    }
    protected function validate(array $data, ?array $current = null, bool $replace = false): array
    {
        Validator::only($data, ['name', 'description']);
        if (!$current || $replace) Validator::required($data, ['name']);
        $out = [];
        if (array_key_exists('name', $data)) {
            $name = Validator::text($data['name'], 'name', 2, 100);
            $existing = $this->dao->get_by_name($name);
            if ($existing && (!$current || (int) $existing['id'] !== (int) $current['id'])) throw new ValidationException("Subject '{$name}' already exists.");
            $out['name'] = $name;
        }
        if (array_key_exists('description', $data)) {
            $description = Validator::text($data['description'] ?? '', 'description', 0, 2000);
            Validator::no_html($description, 'description');
            $out['description'] = $description === '' ? null : $description;
        }
        return $out;
    }
    protected function before_delete(int $id): void
    {
        $count = $this->tutors->count_by('subject_id', $id);
        if ($count > 0) throw new ValidationException("This subject still has {$count} tutor profile(s). Delete or reassign them first.");
    }
}
