---
name: adobe-commerce-upgrade-and-patching
description: >
  Plans and validates Adobe Commerce version upgrades and security or quality
  patch adoption: system-requirement changes, extension and custom-code
  compatibility via the Upgrade Compatibility Tool where applicable, and
  rollout. Use only when explicitly invoked with
  /adobe-commerce-upgrade-and-patching for an upgrade or patch task. Do not use
  for feature work, and do not auto-trigger.
disable-model-invocation: true
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "UCT overview S16; Adobe 2.4.9 release notes S14; 2026-09-22"
---

# Upgrade and patching

Slash-only. Written plan approved before any upgrade command. Never production.

## When to use / skip

Use: `/adobe-commerce-upgrade-and-patching` for version upgrades or Adobe quality/security patches.
Skip: feature work; ACCS/ACO versionless upgrades (say so and stop UCT path).

## Procedure

1. Detect platform with `detect-platform.sh` / `detect-stack.sh`. Read **current** commerce package version from `composer.lock` via the terminal.
2. Fetch the **target** version's official Adobe release notes and system requirements at run time. Never state PHP/DB/search/queue requirements from memory.
3. **UCT applicability** (Adobe docs): On-premises and Cloud/PaaS — yes. ACCS and ACO — **no**. If ACCS/ACO, say UCT does not apply; upgrades are versionless / SaaS-managed; do not invent a UCT run.
4. For on-prem/Cloud: plan Upgrade Compatibility Tool against the target version (per-module paths on large repos, memory-limit workarounds as needed). Source: Adobe UCT docs.
5. Review backward-incompatible changes and third-party extension compatibility for this project's `composer.lock` packages.
6. Produce a written plan: current→target, system requirement deltas, UCT scope, staging validation with realistic data, rollback. **Stop for developer approval before any upgrade or patch command.**
7. Never run against production. Propose commands only for local/staging the developer names.

## Must not

- Guess supported PHP versions from training knowledge.
- Run UCT or composer upgrade on ACCS/ACO as if it were PaaS.
- Execute production deploy/upgrade commands.
- Start coding or upgrading before plan approval.

## Output

```
Platform: ...
Current (composer.lock): ...
Target (official notes URL): ...
UCT applies: yes|no — <why>
Plan: ...
Approval needed: yes
Production: not in scope
```
