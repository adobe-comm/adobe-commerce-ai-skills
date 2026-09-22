# Adobe Commerce AI Skills — Team User Guide

**Pack:** `adobe-commerce-ai-skills`  
**Version:** 0.4.0  
**Audience:** Brainvire Adobe Commerce CoE engineers  
**Owner:** Adobe Commerce CoE  

This guide tells you how to install the pack, when to use each skill, and how to work day to day in Cursor.

---

## 1. What this pack is

A Cursor plugin that makes the Agent behave like a senior Adobe Commerce engineer on an unfamiliar project:

- Discovers what exists in the repo (does not invent modules or integrations)
- Matches your project’s patterns (copies exemplars in `app/code`)
- Sizes work L0–L3 (small asks stay small)
- Reports tests honestly (`Ran: …` / `Not run: … because …`)
- Blocks unsafe writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json`

It covers **PaaS / on-premises PHP** Magento work. For App Builder, Edge Delivery drop-ins, and Adobe starter kits, use **Adobe’s official skills** when installed (`aio commerce extensibility tools-setup`).

---

## 2. Install (pick one)

### Option A — Team marketplace (preferred, when CoE publishes it)

1. Open Cursor → **Customize** (sidebar).
2. Find **adobe-commerce-ai-skills** under the team marketplace.
3. Click **Install**.
4. Reload Window: Command Palette → **Developer: Reload Window**.
5. Confirm under **Customize → Skills** that `adobe-commerce-*` skills appear.

Install modes (set by admin):

| Mode | Meaning |
|------|---------|
| Default Off | You install when you want it |
| Default On | Installed for you; you can opt out |
| Required | Always on; cannot uninstall |

### Option B — Install into one Magento project (pilot / no marketplace yet)

From the pack folder:

```bash
./install.sh /absolute/path/to/your-magento-project
```

This copies:

- Skills → `.cursor/skills/`
- Core rule → `.cursor/rules/adobe-commerce-core.mdc`
- Guardrail hooks → `.cursor/hooks.json` + `.cursor/hooks/*.py`
- Templates if missing → `AGENTS.md`, `.cursorignore`, `docs/ai/project-facts.md`

Then open **that Magento project** in Cursor and reload the window.

### Option C — Local plugin folder (maintainers)

Copy or place the pack under:

`~/.cursor/plugins/local/adobe-commerce-ai-skills`

Reload Cursor. (Teams admins may need to allow local plugin imports.)

---

## 3. Required setup on every Magento project

### Fill `AGENTS.md`

Skills use the commands in this file **verbatim**. At minimum set:

- Platform model and Commerce version
- **Command prefix:** `ddev exec` if you use DDEV, or `none`
- Lint / unit / integration / cache / `setup:di:compile` commands  
  (write `NOT SET UP` if you do not have them yet — do not invent)
- Exemplar modules to copy patterns from
- Off-limits (production, live payment credentials)

Template: `templates/project/AGENTS.md` in the pack (or the file created by `install.sh`).

### Optional: `docs/ai/project-facts.md`

Only for facts the code cannot show (e.g. “ERP is source of truth for stock”). Each line needs evidence path + verified date. No secrets.

---

## 4. How to use skills day to day

### Automatic

Type a normal Agent request. Cursor picks skills from their descriptions.

Examples:

```text
How is this project structured? Keep under 15 lines.
```

```text
Add a Magento plugin after Magento\Checkout\Model\Cart::addProduct.
Put it in an existing Brainvire module if that fits. Match our patterns.
```

```text
Orders stopped syncing to ERP. Find the root cause before changing code.
```

```text
Review my uncommitted changes as a senior Magento reviewer.
```

### Manual (force a skill)

In Agent chat, type `/` and choose a skill, e.g.:

```text
/adobe-commerce-debugging
```

```text
/adobe-commerce-module-scaffold
```

```text
/adobe-commerce-project-documentation
```

Use manual invoke when auto-pick feels wrong, or for slash-only skills (documentation).

### Always-on rule

`adobe-commerce-core` applies to every Agent chat. You do not need to invoke it.

### Guardrails (hooks)

You do not invoke these. They run automatically:

- **Blocked:** editing or deleting under `vendor/`, `generated/`, `pub/static/`, or writing `app/etc/env.php` / `auth.json` (including via shell `rm` / `sed -i` / `>` redirects)
- **Allowed:** reading `vendor/` via the **terminal** (needed to check real Adobe APIs). Prefer terminal over `@vendor/...` — see README.
- **Ask:** force-push or DROP/TRUNCATE-style commands

The shell guard blocks **obvious** destructive commands. It can be bypassed by obfuscated or scripted commands. Treat it as a **safety net**, not a substitute for code review or CI.

Vendor changes must go through your project’s **Composer patch** process, not direct edits.

---

## 5. Which skill for which job

| I need to… | Use |
|------------|-----|
| Understand a new repo | `adobe-commerce-project-understanding` |
| Turn an Azure DevOps ticket into a plan | `adobe-commerce-requirement-planning` |
| Trace how a feature works before changing it | `adobe-commerce-feature-analysis` |
| Create plugin / observer / patch / CLI / cron / consumer / GraphQL / admin config | **`adobe-commerce-module-scaffold`** |
| Write PHP/XML to project + Magento standards | `adobe-commerce-coding-standards` |
| Find why something is broken | **`adobe-commerce-debugging`** |
| Choose/run tests; honest validation | `adobe-commerce-testing` |
| Review a PR / diff | `adobe-commerce-code-review` |
| Security check (PII, checkout, admin, secrets) | `adobe-commerce-security-review` |
| Performance on hot paths | `adobe-commerce-performance-review` |
| Theme / storefront JS / GTM / GA4 | `adobe-commerce-frontend-and-tracking` |
| ERP/CRM/OMS sync or payload work | `adobe-commerce-integration-work` |
| Record an ADR or project fact | `/adobe-commerce-project-documentation` only |
| Version upgrade / UCT / patches | `/adobe-commerce-upgrade-and-patching` only |
| Cloud / App Builder deploy | `/adobe-commerce-deploy-and-environments` only |
| App Builder / EDS drop-ins | Adobe official skills (not this pack) |

---

## 6. Depth ladder (what “good” looks like)

| Level | Examples | Expect |
|-------|----------|--------|
| **L0** | Copy, CSS, known config key | Tiny change, 2–4 line summary |
| **L1** | One class or template | Exemplar + standards + targeted test if available |
| **L2** | Schema, API, plugin on shared class, queue/cron, integration | Trace + plan + tests + review surfaces |
| **L3** | Checkout, payment, pricing, auth, migration | Written plan **approved before code** |

If the Agent writes a long architecture report for a button margin change, stop it and say: **L0 only**.

---

## 7. Tickets (Azure DevOps)

- Prefer pasted work item text in the chat.
- If your team has an Azure DevOps MCP installed, you can point the Agent at a work item id/URL.
- Do **not** assume Jira.

---

## 8. Smoke test after install (15 minutes)

1. Reload Window; confirm skills in Customize.
2. `How is this project structured? Keep under 15 lines.`
3. Ask for a small plugin in an existing custom module (or `/adobe-commerce-module-scaffold`).
4. Ask to **edit a file under `vendor/`** — should be blocked.
5. Ask to run tests on a change — if none exist, expect `Not run: … because …`, not “all tests passed”.

---

## 9. Feedback to CoE

Please report:

- Wrong skill triggered (what you asked + what fired)
- Invented modules/integrations that are not in the repo
- Overly long answers on L0/L1 work
- Hook blocked something it should have allowed (false positive)
- Missing Magento pattern your projects always use

Send feedback to the Adobe Commerce CoE pack owner with: project name, Cursor version, pack version (`0.4.0`), and a short chat excerpt.

---

## 10. Related documents (deeper reading)

| Document | When to read |
|----------|----------------|
| `README.md` | Pack overview |
| `docs/ADOPTION.md` | CoE rollout / Teams marketplace |
| `CHANGELOG.md` | What changed per version |
| `hooks/README.md` | Guardrail technical detail |
| `evals/run.md` | Formal skill scoring (pilot QA) |
| `docs/VERIFICATION.md` | Design decisions / research log |

---

## 11. Quick don’ts

- Do not edit `vendor/` by hand or via Agent.
- Do not run Agent commands against production.
- Do not paste secrets into chat, `AGENTS.md`, or project-facts.
- Do not expect this pack to replace Adobe’s App Builder / drop-in skills.
- Do not skip filling `AGENTS.md` — wrong commands waste more time than the pack saves.
