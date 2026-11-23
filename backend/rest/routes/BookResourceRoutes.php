<?php
Flight::route('GET /api/v1/books', function () {
    $query = Flight::request()->query['q'] ?? '';
    Flight::json(Flight::book_resource_service()->search($query));
});
