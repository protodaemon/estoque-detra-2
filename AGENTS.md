# Agent Instructions for Estoque Detra

## Commands

**Backend (Laravel 10 - PHP 8.1+):**
- Test: `cd BackEnd && php artisan test` or `./vendor/bin/phpunit`
- Test single file: `cd BackEnd && php artisan test tests/Feature/ExampleTest.php`
- Code format: `cd BackEnd && ./vendor/bin/pint`
- Serve: `cd BackEnd && php artisan serve`

**Frontend (Vue 3 + Vite):**
- Dev: `cd FrontEnd && npm run dev`
- Build: `cd FrontEnd && npm run build`

## Architecture

- **BackEnd/**: Laravel 10 API with Controllers, Models, Services, routes (api.php)
- **FrontEnd/**: Vue 3 SPA with Vue Router, Tailwind CSS
- **Database**: MySQL with tables like `produtos_consumivel`, `produtos_patrimonio`, `categoria_patrimonio`
- **API**: RESTful endpoints in `routes/api.php` consumed by frontend via axios

## Code Style

- **Indentation**: 4 spaces (backend), match existing (frontend)
- **Naming**: Controllers use `PascalCase`, models use `PascalCase`, variables/methods use `camelCase`, database tables use `snake_case`
- **Models**: Use `$fillable`, define custom `$table` and `$primaryKey` as needed
- **Controllers**: Use validation, Eloquent models, return JSON responses with proper status codes
- **Comments**: Use Portuguese comments sparingly (e.g., `#relacionamento de pertence a`)
- **Images**: Compress uploads using Intervention Image to JPEG (quality 60, max width 800px)
