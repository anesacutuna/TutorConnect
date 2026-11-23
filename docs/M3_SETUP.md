# Milestone 3 local setup

1. Keep the existing database and local `backend/config/config.php` from M2. Add the three Open Library timeout/User-Agent values shown in `config.example.php` if the local file predates M3.
2. In Terminal, from the project root run `cd backend && composer install`.
3. Return to the project root and run `php -S localhost:8000 server.php`.
4. Open the REST API, Swagger UI, and frontend Books view using the URLs in the README.
5. Use Swagger `Try it out` for the GET routes and CRUD operations. Clean up any records you add, especially records linked by foreign keys.
6. Submit a book search from Learning books. Confirm a result card appears. If Open Library is unavailable, confirm the page displays a friendly message and check `backend/logs/error.log`.

Open Library's Search API is public and does not require an API key. Its base URL, timeout, and connection timeout are configurable. Do not add a fabricated API key.
