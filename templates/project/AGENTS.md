# Project agent overlay

Fill this in once per repo. Keep it under 60 lines. Do not repeat the adobe-commerce-core rule.

## Platform

- Model: [Cloud/PaaS | on-premises | ACCS | ACO | App Builder | Edge Delivery storefront]
- Commerce package + version: [from composer.lock]
- PHP version: [actual runtime version]
- Search / cache / queue: [Elasticsearch or OpenSearch version, Redis/Valkey, RabbitMQ]

## Local stack and command prefix

- Stack: [DDEV | native | other]
- Command prefix: [`ddev exec` | none]

Every command below is used verbatim by the agent. Prefix them with the line above.

## Commands

- Lint changed files: [`vendor/bin/phpcs --standard=Magento2 <paths>` | NOT SET UP]
- Static analysis: [command | NOT SET UP]
- Unit tests: [`vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist <path>` | NOT SET UP]
- Integration tests: [command | NOT SET UP]
- Front-end / E2E: [command | NOT SET UP]
- Rebuild after DI/XML change: `bin/magento setup:upgrade && bin/magento setup:di:compile`
- Cache: `bin/magento cache:flush`

Write `NOT SET UP` rather than guessing. The agent will then do manual validation and say so, instead of claiming a test run that cannot happen.

## Environments

- Shared non-prod: [names]
- Off-limits: production hosts, production database, live payment credentials

## Exemplars (copy patterns from these)

- Backend module: `app/code/[Vendor]/[Module]`
- Integration module: `app/code/[Vendor]/[Module]`
- Frontend: [Luma | Hyva | headless | EDS] at `[path]`

## Conventions that differ from Magento defaults

- [list only real deviations]

## Do not touch

- [paths or flows needing a specific owner's approval]

## Durable facts

`docs/ai/project-facts.md` — human-reviewed, each fact carries an evidence path and verified date.
