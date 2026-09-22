# Log and state map

Local/dev only. Confirm commands exist for the installed version (`bin/magento list`) before relying on them. Never target production.

## Logs (typical 2.4.x paths)

| Path | Contains |
|------|----------|
| `var/log/exception.log` | Uncaught exceptions with stack traces |
| `var/log/system.log` | Warnings, cron and indexer notices |
| `var/log/debug.log` | Debug output when developer mode / logging enabled |
| `var/report/<id>` | Frontend error report referenced by the error page id |
| `var/log/` (project-specific) | Custom module channels — check the project's logger DI |

Web server and PHP-FPM error logs sit outside the repo; ask the developer for the relevant excerpt rather than guessing.

## Symptom to first checks

| Symptom | First checks |
|---------|--------------|
| 500 / blank page | `var/report/<id>`, `exception.log`, deployment mode, generated code freshness |
| Change has no effect | Cache status, correct area (`frontend` vs `adminhtml`), module enabled, DI compiled, theme fallback |
| Admin field missing | `system.xml`, ACL resource, cache, config scope |
| Stale prices/stock/listing | Indexer status, indexer mode, cron running, FPC invalidation |
| Data not syncing outbound | Queue consumers running, cron schedule rows, publisher call site reached, HTTP client errors in logs |
| Wrong implementation executing | Plugin sort order, preferences, area-specific `di.xml`, third-party module overriding |
| Works for one store/website only | Config scope values, store-scoped config, website-specific overrides |
| Intermittent failures | Timeouts and retries on external calls, race with cron/consumer, cache stampede |

## Read-only commands worth running locally

```
bin/magento list                       # confirm available commands for this version
bin/magento module:status
bin/magento indexer:status
bin/magento cron:run --help            # inspect before running anything
bin/magento cache:status
bin/magento config:show <path>
bin/magento dev:di:info <type>         # resolve preferences/plugins on a type
bin/magento queue:consumers:list
```

Anything that writes (cache flush, reindex, setup:upgrade, consumer start) is the developer's call in their environment — propose it, explain the effect, and let them run it unless they asked you to.

## Database

Use a read-only connection on a local or sanitized database. Never query production. Prefer repositories and CLI output over raw SQL when reproducing logic.
