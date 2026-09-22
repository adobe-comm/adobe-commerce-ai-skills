# Backend PHP conventions

Load on demand. Verify against this project's installed Magento version and phpcs ruleset.

## Precedence reminder

Project exemplar first, then Magento coding standard, then PSR. Surface conflicts in one line.

## Pitfalls (sourced / keep)

| Pitfall | Guidance | Source |
|---------|----------|--------|
| Direct ObjectManager in business code | Prefer constructor DI; OM mainly in factories/proxies/tests | Magento PHP developer docs / coding standard |
| Deprecated core APIs | Check `@deprecated` on the **installed** vendor class before using | Installed `vendor/magento` |
| `around` plugins when before/after suffice | Prefer before/after; around only when required | Magento plugin best practices |
| Plugins on non-public methods | Interception applies to public methods | Magento plugin docs |
| Preferences where a plugin suffices | Prefer plugins/observers for extensibility | Magento DI docs |
| Legacy Install/UpgradeSchema scripts | Prefer declarative schema + data/schema patches on modern 2.4.x | Magento declarative schema docs |
| Stale `db_schema_whitelist.json` | Update whitelist when changing `db_schema.xml` | Magento declarative schema |
| Unescaped output in `.phtml` | Escape by context (`escapeHtml`, `escapeUrl`, etc.) | Magento template escaping docs |
| Business logic in controllers/templates | Move to services/domain classes | Magento service-contract guidance |
| Heavy constructors | Lazy dependencies via proxies where project does; avoid unnecessary work | Magento performance/DI guidance |
| Model `load`/`save` vs repositories | Prefer service contracts/repositories/resource models per project exemplar | Magento service contracts |
| Config without defaults / admin / ACL | New config needs `config.xml` default, system.xml field, ACL as applicable | Magento admin config docs |

## Commands

Discover project scripts first (`composer.json` scripts, Makefile, AGENTS.md). Example Magento standard install note: phpcs `installed_paths` must include `magento/magento-coding-standard`. Source: https://github.com/magento/magento-coding-standard
