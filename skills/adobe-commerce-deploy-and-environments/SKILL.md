---
name: adobe-commerce-deploy-and-environments
description: >
  Assists with Adobe Commerce environment configuration and delivery: Cloud
  PaaS configuration and deploy hooks, CI/CD quality gates, App Builder
  incremental deployment for out-of-process extensions, environment variables,
  and release checklists. Use only when explicitly invoked with
  /adobe-commerce-deploy-and-environments for deploy, pipeline, or environment
  tasks. Do not use for application feature code, and do not auto-trigger.
disable-model-invocation: true
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Adobe AI agent best practices S11b; Cloud/PaaS vs SaaS S19; 2026-09-22"
---

# Deploy and environments

Slash-only. Propose commands; never deploy to production without explicit human confirmation.

## When to use / skip

Use: `/adobe-commerce-deploy-and-environments` for Cloud config, deploy hooks, App Builder deploy, env/pipeline questions.
Skip: ordinary application PHP/feature work.

## Procedure

1. Detect platform first (`detect-platform.sh`). Paths differ for Cloud PaaS vs App Builder vs ACCS.
2. **Cloud PaaS:** work from evidence in `.magento.app.yaml`, `.magento/`, project deploy docs. Propose changes as diffs; do not invent Cloud-only features for on-prem.
3. **App Builder:** test locally first (`aio-app-dev` / MCP equivalents when available), deploy only changed actions incrementally, clean up orphaned actions after major changes. Source: Adobe developer-agent best practices.
4. **ACCS/SaaS:** no classic Magento Cloud deploy of in-process PHP; customization is out-of-process. Redirect App Builder / API Mesh / webhooks accordingly.
5. No secrets in the repo. Reference env var **names** only; values stay in the project's secret store / Cloud variables UI.
6. Run (or name) the project's quality gates before deploy: lint/unit/static as listed in `AGENTS.md`.
7. **Production:** never deploy or promote to production unless the developer explicitly confirms the target environment name and approves in this session. Default target is local or named non-prod only.

## Must not

- Deploy to production without explicit confirmation of the environment name.
- Commit `env.php`, `auth.json`, API keys, or `.env` values.
- Run shared-environment commands unless asked.
- Treat ACCS like Cloud PaaS PHP deploy.

## Output

```
Platform: ...
Target environment: <named non-prod | production ONLY if confirmed>
Plan: ...
Commands (proposed, not run unless asked): ...
Secrets check: no secrets in diff
Production confirmation: required|n/a
```
