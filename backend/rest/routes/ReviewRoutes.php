<?php
// Lab 05 pattern: routes only read the request, call a service, and return JSON.
Flight::route('GET /api/v1/reviews', function () {
    Flight::json(Flight::review_service()->get_all());
});
Flight::route('GET /api/v1/reviews/@id:[0-9]+', function ($id) {
    Flight::json(Flight::review_service()->get_by_id((int) $id));
});
Flight::route('POST /api/v1/reviews', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::review_service()->add($data), 201);
});
Flight::route('PUT /api/v1/reviews/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::review_service()->update($data, (int) $id, true));
});
Flight::route('PATCH /api/v1/reviews/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::review_service()->update($data, (int) $id));
});
Flight::route('DELETE /api/v1/reviews/@id:[0-9]+', function ($id) {
    Flight::json(Flight::review_service()->delete((int) $id));
});
