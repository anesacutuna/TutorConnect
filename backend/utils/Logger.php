<?php
class Logger
{
    private static function write(string $file, string $level, string $message, array $context = []): void
    {
        $directory = __DIR__ . '/../logs';
        if (!is_dir($directory)) mkdir($directory, 0775, true);
        $line = '[' . gmdate('Y-m-d H:i:s') . ' UTC] ' . $level . ' ' . $message;
        if ($context) $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents($directory . '/' . $file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    public static function info(string $message, array $context = []): void { self::write('info.log', 'INFO', $message, $context); }
    public static function error(string $message, array $context = []): void { self::write('error.log', 'ERROR', $message, $context); }
}
