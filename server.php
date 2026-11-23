<?php
$requested_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$requested_file = realpath(__DIR__ . rawurldecode($requested_path));
$project_root = realpath(__DIR__);
if ($requested_file && $project_root && str_starts_with($requested_file, $project_root . DIRECTORY_SEPARATOR)) {
    if (is_file($requested_file)) return false;
    if (is_dir($requested_file) && (is_file($requested_file . '/index.html') || is_file($requested_file . '/index.php'))) return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/backend/index.php';
