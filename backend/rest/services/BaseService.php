<?php
require_once __DIR__ . '/../dao/BaseDao.php';
require_once __DIR__ . '/../../utils/exceptions.php';
abstract class BaseService
{
    public function __construct(protected BaseDao $dao) {}
    protected function output(array $row): array { return $row; }
    public function get_all(): array { return array_map(fn($row) => $this->output($row), $this->dao->get_all()); }
    public function get_by_id(int $id): array
    {
        if ($id < 1) throw new ValidationException('Id must be a positive integer.');
        $row = $this->dao->get_by_id($id);
        if (!$row) throw new NotFoundException('Record not found.');
        return $this->output($row);
    }
    public function add(array $data): array
    {
        return $this->output($this->dao->add($this->validate($data)));
    }
    public function update(array $data, int $id, bool $replace = false): array
    {
        $current = $this->dao->get_by_id($id);
        if (!$current) throw new NotFoundException('Record not found.');
        if (!$data) throw new ValidationException('Provide at least one field.');
        $clean = $this->validate($data, $current, $replace);
        $this->dao->update($clean, $id);
        return $this->get_by_id($id);
    }
    public function delete(int $id): array
    {
        $this->get_by_id($id);
        $this->before_delete($id);
        if (!$this->dao->delete($id)) throw new NotFoundException('Record not found.');
        return ['message' => 'Record deleted.'];
    }
    protected function before_delete(int $id): void {}
    abstract protected function validate(array $data, ?array $current = null, bool $replace = false): array;
}
