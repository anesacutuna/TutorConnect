<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
try {
    $openapi = (new OpenApi\Generator())->generate([__DIR__]);
    header('Content-Type: application/json; charset=utf-8');
    echo $openapi->toJson();
} catch (Throwable $error) {
    require_once __DIR__ . '/../../../utils/Logger.php';
    Logger::error('OpenAPI generation failed', ['detail' => $error->getMessage()]);
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 500, 'error' => 'Swagger documentation could not be generated. Check Composer installation and server logs.']);
}
