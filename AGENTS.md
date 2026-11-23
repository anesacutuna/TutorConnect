# TutorConnect — project instructions

## Project
TutorConnect is an IT 2001 Web Programming project that helps students discover tutors, request lessons, manage bookings, review completed lessons, and find learning books.

## Required stack
- Frontend: HTML, CSS, JavaScript; Bootstrap 5 is used for responsive layout.
- Backend: pure PHP using FlightPHP for the REST API.
- Database: MySQL accessed through PDO.
- API documentation: OpenAPI attributes with swagger-php and Swagger UI.

## Architecture
- Keep frontend and backend code separate.
- Backend request flow: route -> service -> DAO -> database.
- Routes call services; only services call DAO classes; DAO classes contain PDO/database access.
- Frontend views must not contain backend/API calls. In the final frontend MVC design, frontend service files make AJAX requests.
- In Milestone 1, the frontend is a static SPA prototype. Do not claim that forms persist data or that authentication is real.

## Planned entities and roles
Entities: users, subjects, tutor_profiles, bookings, reviews. Roles: student and admin. Visitors can browse public pages without an account. Tutor profiles are catalog records in the initial scope; tutors do not have a separate login role.

## Naming and folders
- PHP class and file names use PascalCase, for example `BookingService.php` and `BookingDao.php`.
- Database tables and columns use snake_case.
- Keep route/service/DAO files under `backend/rest/`.
- Keep browser assets under `frontend/assets/`; HTML fragments go in `frontend/views/`.

## M3 implementation rules
- Each database entity has a service under `backend/rest/services/`; services own validation and call DAOs.
- FlightPHP routes under `backend/rest/routes/` only read HTTP data, call a service, and return JSON. Routes contain no SQL or business rules and do not catch exceptions individually.
- OpenAPI documentation uses PHP 8 attributes under `backend/public/v1/docs/`; `swagger.php` generates the JSON used by Swagger UI.
- Open Library calls happen only in `BookResourceService`; the browser calls the TutorConnect endpoint, not Open Library directly.

## Security and configuration
- Never commit `backend/config/config.php`, real credentials, JWT secrets, API keys, logs, or `backend/vendor/`.
- Commit only `backend/config/config.example.php` with placeholder values.
- Hash passwords on the backend; never return password hashes or log passwords/tokens.
- Enforce authentication and authorization in the backend, not only by hiding frontend controls.

## AI use
Update `docs/AI_LOG.md` for every milestone with actual AI use, prompts, and real mistakes and corrections. Do not invent tests, decisions, or student actions. The student must review and be able to explain all submitted code.
