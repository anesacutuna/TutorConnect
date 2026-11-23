# AI Log

## Milestone 1

### AI tools and tasks
- ChatGPT/Codex was used to map the project requirements to the M1 scaffold, draft the static SPA, ERD, and project documentation.
- Main prompt: “Can you give me this structure as a ZIP with finished code that meets the Milestone 1 requirements?”

### AI errors noticed and corrected
1. AI initially advised cloning/opening the prior folder, while the student wanted a separate new folder connected to the existing GitHub repository. The student clarified the workflow; work continued in one local project folder.
2. AI advised adding `origin` after it had already been added. The terminal returned `remote origin already exists`; the existing remote was kept.

## Milestone 2

### AI tools and tasks
- ChatGPT/Codex was used to prepare the MySQL schema and sample data, PDO connection, BaseDao, five entity DAOs, and setup documentation.
- Main prompt: “Build Milestone 2 on the existing structure and provide the complete updated project to copy into the main folder.”

### AI errors noticed and corrected
1. The first M2 `UserDao::add()` implementation repeated a lookup after `BaseDao::add()` had already returned the created user. The DAO run showed `UserDao::add(): Return value must be of type array, null returned`. The method was corrected to return `parent::add($data)` directly.
2. AI initially inspected an older archive containing separate milestone snapshots, which did not match the agreed single project folder. The package was rebuilt as one project root for the same local folder.

### Local verification
- On 2026-11-22, ran `php rest/dao/my_tests.php` from `backend/`.
- The output confirmed the example's create/read/update/delete flow, related booking/review reads, and successful removal of temporary rows.
- The SQL was imported and local `config.php` was created from the example.

## Milestone 3

### AI tools and tasks
- ChatGPT/Codex was used to add entity services, FlightPHP CRUD routes, JSON error handling, PHP-attribute OpenAPI documentation, Swagger UI, and the Open Library book-search service and frontend view.
- Main prompt: “Upgrade the existing project structure for Milestone 3 and provide the whole updated structure for the same project folder.”

### AI errors noticed and corrected during review
1. An initial `UserDao::get_all()` override did not match the paginated method signature added to `BaseDao`. Code review found the PHP inheritance mismatch; `UserDao::get_all()` was updated to accept the same optional pagination parameters.
2. The first development-server router fallback handled files but not directories containing an `index.html`, which would prevent the Swagger UI directory URL from loading. The router was updated to pass such directories back to PHP's built-in server.

### Verification status
- Source files and routes are prepared, but local `composer install`, API requests, Swagger generation, and a live Open Library search still need to be run in the student's environment. Record actual output and any further corrections after those checks.
