# Tracing recipes

Short recipes by entry-point type. Confirm against this project's installed version.

## HTTP frontend / admin route

1. Find route id in `etc/frontend/routes.xml` or `etc/adminhtml/routes.xml`.
2. Match controller under `Controller/`.
3. Check layout handles `view/*/layout/<route>_<controller>_<action>.xml`.
4. Follow blocks → templates (`.phtml`) and UI components if present.
5. Check plugins/preferences on the controller or involved services in `etc/*/di.xml`.

## GraphQL

1. Schema in `etc/schema.graphqls` (or module equivalent).
2. Resolver class from schema or `etc/schema.graphqls` + DI.
3. Follow resolver → service / repository; note cache identity classes if any.
4. Check webapi/graphql area `di.xml` plugins.

## REST / SOAP webapi

1. `etc/webapi.xml` route + service interface method.
2. Implementation preference in DI.
3. ACL resource in `etc/acl.xml` linked from webapi.

## Event / observer

1. `etc/events.xml` (and area-specific) event name → observer instance.
2. Observer execute path; plugins on the observer or observed type.
3. Confirm module enabled (`app/etc/config.php` or `module:status` in local only).

## Plugin on shared type

1. `di.xml` (area) plugin declaration: sortOrder, disabled flag.
2. Target type — public methods only for interception.
3. List all plugins on that type; determine order.
4. Prefer before/after over around when sufficient (project + Magento guidance).

## Cron

1. `etc/crontab.xml` job name → instance/method.
2. Schedule group; related `cron_groups.xml` if present.
3. Side effects: DB, queue publish, HTTP — note failure handling.

## Queue / consumer

1. `etc/queue_topology.xml`, `queue_consumer.xml`, `communication.xml` (names vary by version — confirm in repo).
2. Publisher call sites; consumer handler class.
3. Retry / poison handling if configured in project.

## CLI command

1. `etc/di.xml` `Magento\Framework\Console\CommandList` or command class preference.
2. Command `configure`/`execute`; injected services.

## Data / schema change path

1. `etc/db_schema.xml` + `db_schema_whitelist.json`.
2. Data/schema patches under `Setup/Patch/`.
3. Avoid legacy Install/Upgrade scripts unless the project still uses them (CONFIRMED only).
