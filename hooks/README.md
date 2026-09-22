# Guardrail hooks

## Purpose

Tool-level enforcement of rules that prompts alone cannot guarantee:

| Guard | Hook | Behaviour |
|-------|------|-----------|
| No writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json` | `preToolUse` → `scripts/block-protected-writes.py` | **deny** (`failClosed: true`) |
| Shell writes into those paths | `beforeShellExecution` → `scripts/block-protected-shell.py` | **deny** |
| Force-push / DROP / TRUNCATE | same shell hook | **ask** (developer confirms) |

Reading `vendor/` (cat, rg, detect-stack) remains allowed — R4 needs it.

## Plugin vs project

| Install path | Config | Scripts |
|--------------|--------|---------|
| Team marketplace / plugin | `hooks/hooks.json` | `scripts/*.py` (plugin root) |
| `./install.sh` into a Magento repo | `.cursor/hooks.json` | `.cursor/hooks/*.py` |

## Local test (no Cursor needed)

```bash
echo '{"tool_input":{"path":"vendor/magento/framework/X.php"}}' \
  | python3 scripts/block-protected-writes.py
# expect permission: deny

echo '{"tool_input":{"path":"app/code/Brainvire/Foo/Plugin/X.php"}}' \
  | python3 scripts/block-protected-writes.py
# expect permission: allow

echo '{"command":"rm -rf vendor/magento"}' \
  | python3 scripts/block-protected-shell.py
# expect permission: deny
```

## Limits

- Hooks are not a substitute for CI or code review.
- `failClosed` applies to the write-tool hook only; shell hook fails open on crash so day-to-day shell is not bricked.
- If a Magento project already has `.cursor/hooks.json`, `install.sh` will not overwrite it — merge manually from `templates/project/.cursor/hooks.json`.
