<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/exceptions.php';
require_once __DIR__ . '/utils/Logger.php';

Flight::set('flight.debug', false);
Flight::set('flight.log_errors', false);
Flight::set('flight.base_url', '');
$config = require __DIR__ . '/config/config.php';

Flight::map('error', function (Throwable $error) {
    if ($error instanceof HttpException) {
        $status = $error->get_status();
        $message = $error->getMessage();
        if ($status >= 500) Logger::error('API request failed', ['type' => get_class($error), 'detail' => $message]);
    } else {
        $status = 500;
        $message = 'Something went wrong on the server. Please try again later.';
        Logger::error('Unhandled API exception', ['type' => get_class($error), 'detail' => $error->getMessage()]);
    }
    Flight::response()->clearBody()->status($status)->header('Content-Type', 'application/json')
        ->write(json_encode(['status' => $status, 'error' => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
        ->send();
});

Flight::map('notFound', function () {
    $method = Flight::request()->method;
    $url = strtok(Flight::request()->url, '?');
    Flight::json(['status' => 404, 'error' => "Endpoint {$method} {$url} does not exist."], 404);
});

Flight::register('user_service', 'UserService');
Flight::register('subject_service', 'SubjectService');
Flight::register('tutor_profile_service', 'TutorProfileService');
Flight::register('booking_service', 'BookingService');
Flight::register('review_service', 'ReviewService');
Flight::register('book_resource_service', 'BookResourceService', [$config]);

Flight::route('GET /api/v1', function () {
    Flight::json(['message' => 'TutorConnect API is running.', 'version' => '1.0']);
});

foreach (['User', 'Subject', 'TutorProfile', 'Booking', 'Review'] as $entity) {
    require __DIR__ . '/rest/routes/' . $entity . 'Routes.php';
}
require __DIR__ . '/rest/routes/BookResourceRoutes.php';
Flight::start();
