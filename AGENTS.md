# Repository Guidelines

## Project Structure & Module Organization
The plugin entry point is `wp-bcgov-corp-learn-portal.php`, which registers the `course` custom post type, associated taxonomies, and all sync helpers. Keep domain logic inside this file unless a feature deserves its own include; shared helpers (for example, markdown parsing) belong in `lib/` alongside `Parsedown.php`. Documentation lives in `readme.md`, and compliance context is tracked in `COMPLIANCE.yaml`.

## Build, Test, and Development Commands
Develop against a local WordPress instance and clone this repository into `wp-content/plugins/wp-learninghub-plugin`. Useful commands:
- `wp plugin activate wp-bcgov-corp-learn-portal` – enables the plugin after installing dependencies.
- `wp plugin deactivate wp-bcgov-corp-learn-portal` – quick rollback if changes misbehave.
- `wp option set learninghub_persistent_message "..."` – handy for validating Parsedown-rendered notices without touching the database directly.

## Coding Style & Naming Conventions
PHP code follows the WordPress Coding Standards: 4-space indentation, snake_case function names (`my_taxonomies_system`), and descriptive action/filter names. Use associative arrays with trailing commas for clarity, and prefer WordPress helpers (`register_post_type`, `wp_insert_post`) over raw SQL. When adding new hooks, prefix them with `learninghub_` or `bcgov_` to avoid collisions. Run `phpcs --standard=WordPress wp-bcgov-corp-learn-portal.php` before submitting.

## Testing Guidelines
There is no automated suite, so lean on targeted manual tests. Configure a handful of sample Courses, Groups, and Learning Partners in a sandbox site, then exercise create/update/delete flows plus the sync helpers. For regression checks, inspect custom fields (ELM codes, registration URLs) and confirm taxonomy permissions from a non-admin account.

## Commit & Pull Request Guidelines
Recent commits use short, imperative summaries (e.g., "Add post_name field to new and updated courses"). Follow that pattern, referencing tickets when available (`Add ... (#123)`). PRs should explain what changed, why, and how it was tested; link to related Learning Hub issues, and attach screenshots for UI tweaks or taxonomy admin updates. Mention any required content migrations or cache clears so deployers can rehearse the steps.

## Security & Configuration Tips
Sanitize every external input passed into sync functions, escape output destined for admin screens, and keep the bundled `Parsedown.php` patched to the latest upstream release. Secrets (API tokens, feed URLs) must live in WordPress configuration constants or environment variables—never hard-code them into the plugin.
