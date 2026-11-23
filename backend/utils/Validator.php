<?php
require_once __DIR__ . '/exceptions.php';
class Validator
{
    public static function only(array $data, array $allowed): void
    {
        $unknown = array_diff(array_keys($data), $allowed);
        if ($unknown) throw new ValidationException('Unknown field: ' . implode(', ', $unknown) . '.');
    }
    public static function required(array $data, array $fields): void
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                throw new ValidationException("Field '{$field}' is required.");
            }
        }
    }
    public static function text(mixed $value, string $field, int $min, int $max): string
    {
        if (!is_string($value)) throw new ValidationException("Field '{$field}' must be text.");
        $value = trim($value);
        $length = mb_strlen($value, 'UTF-8');
        if ($length < $min || $length > $max) throw new ValidationException("Field '{$field}' must be between {$min} and {$max} characters long.");
        return $value;
    }
    public static function integer(mixed $value, string $field, int $min = 1, int $max = PHP_INT_MAX): int
    {
        $validated = filter_var($value, FILTER_VALIDATE_INT);
        if ($validated === false || $validated < $min || $validated > $max) throw new ValidationException("Field '{$field}' must be an integer between {$min} and {$max}.");
        return $validated;
    }
    public static function no_html(string $value, string $field): void
    {
        if (preg_match('/[<>]/', $value)) throw new ValidationException("Field '{$field}' must not contain HTML.");
    }
    public static function password(mixed $password): string
    {
        if (!is_string($password) || strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            throw new ValidationException('Password must have at least 8 characters, including a letter and a number.');
        }
        return $password;
    }
    public static function email(mixed $email): string
    {
        $email = strtolower(self::text($email, 'email', 3, 190));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new ValidationException('Field email must be a valid email address.');
        return $email;
    }
    public static function positive_price(mixed $value): string
    {
        if (!is_numeric($value) || (float) $value <= 0 || (float) $value > 100000) throw new ValidationException('Field hourly_rate must be greater than 0 and no more than 100000.');
        return number_format((float) $value, 2, '.', '');
    }
}
