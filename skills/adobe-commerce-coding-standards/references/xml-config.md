# XML configuration

## Common files

- `etc/module.xml`, `registration.php`
- Area `di.xml`, `events.xml`, `webapi.xml`, `acl.xml`, `crontab.xml`
- `db_schema.xml` + `db_schema_whitelist.json`
- Layout under `view/*/layout/`; UI components under `view/*/ui_component/`

## Rules

1. Match existing module XML style (schema locations, attribute formatting).
2. Prefer additive config; do not remove unrelated nodes.
3. Plugins: declare `type`, `name`, `sortOrder`; set `disabled` only when intentional.
4. ACL: every new admin route/menu/config section needs a resource; wire menus to ACL.
5. After schema changes, keep whitelist in sync.
