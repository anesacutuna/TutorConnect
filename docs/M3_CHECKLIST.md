# Milestone 3 Checklist

## Business logic
- [x] A service class is implemented for each of the five database entities.
- [x] Services contain reusable validation and business rules and are the only layer that calls the DAOs.
- [x] Validation covers unique email addresses and subject names, password strength and hashing, tutor prices, booking rules, and review rules.
- [x] Related records are checked before deletion.

## Presentation layer
- [x] FlightPHP routes provide GET collection, GET by ID, POST, PUT, PATCH, and DELETE endpoints for all five entities.
- [x] Routes call services and return JSON responses.
- [x] Successful reads, updates, and deletes return 200; successful creates return 201.
- [x] A shared error handler returns JSON for validation errors, missing records, and unexpected server errors.
- [x] Invalid subject input returned 400, and a request for an already-deleted subject returned 404.
- [x] Verify 401 and 403 responses after protected routes and JWT authorization are implemented.

## OpenAPI documentation
- [x] OpenAPI attributes document the API status endpoint, entity CRUD endpoints, and book-search endpoint.
- [x] Swagger UI loads and displays the API documentation.
- [x] The generated OpenAPI document loads in Swagger UI.

## External API
- [x] The backend calls the Open Library Search API through a dedicated service and endpoint.
- [x] The external request has connection and total timeouts.
- [x] External API calls and failures are written to the appropriate logs.
- [x] The frontend calls the TutorConnect endpoint and displays book results as cards.
- [x] A successful search for “algebra” returned status 200 and eight books; the results appeared in the frontend.
- [ x] Simulate an Open Library outage and verify the friendly error message and error log.

## Local verification
- [x] Ran `composer install` from `backend/`.
- [x] Added the OpenAPI documentation directory to Composer's classmap and ran `composer dump-autoload`.
- [x] Started the application with `php -S localhost:8000 server.php`.
- [x] Confirmed that `GET /api/v1` returns the API status message.
- [x] Confirmed that the Swagger UI loads.
- [x] Confirmed that `GET /api/v1/subjects` returns records from MySQL.
- [x] Tested subject creation (201), update (200), and validation failure (400).
- [x] Confirmed that the temporary subject was deleted; a repeated DELETE returned the expected 404.
- [x] Confirmed that the other entity collection GET endpoints return successfully.
- [x] Confirmed that book search works in Swagger and in the frontend.
- [x] Commit the Milestone 3 work and push the `milestone3` branch to GitHub.