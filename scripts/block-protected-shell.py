#!/usr/bin/env python3
"""Ask or deny shell commands that target Magento protected paths or look destructive.

Reads beforeShellExecution JSON from stdin.
- Deny: clear writes into vendor/, generated/, pub/static/, env.php, auth.json
- Ask: force-push, drop/truncate database-ish commands
- Allow: everything else (including reading vendor via cat/rg)

Fail-open on parse errors (exit 0 + allow) so normal shell work is not bricked;
the write-tool hook is failClosed and is the primary vendor guard.
"""
from __future__ import annotations

import json
import re
import sys

WRITE_INTO_PROTECTED = re.compile(
    r"(?:^|[;&|]\s*|\s)"
    r"(?:"
    r"(?:rm|mv|cp|tee|install|install\s+-m|dd|truncate|chmod|chown)\b[^;&|\n]*"
    r"(?:vendor/|generated/|pub/static|app/etc/env\.php|auth\.json)"
    r"|"
    r"(?:sed\s+-i|perl\s+-i|ruby\s+-i)\b[^;&|\n]*"
    r"(?:vendor/|generated/|pub/static|app/etc/env\.php|auth\.json)"
    r"|"
    r">\s*(?:\.\/)?(?:vendor/|generated/|pub/static/|app/etc/env\.php|auth\.json)"
    r"|"
    r"composer\s+(?:update|require|remove)\b[^;&|\n]*\b--working-dir[= ]*[^\s]*vendor"
    r")",
    re.IGNORECASE,
)

FORCE_PUSH = re.compile(r"\bgit\s+push\b[^;&|\n]*(\s--force\b|\s-f\b)", re.IGNORECASE)
DROP_OR_TRUNCATE = re.compile(
    r"\b(DROP\s+(TABLE|DATABASE)|TRUNCATE\s+TABLE)\b"
    r"|\bmysql\b[^;&|\n]*\b-e\b[^;&|\n]*(DROP|TRUNCATE)"
    r"|\bdd\s+if=.*\bof=.*",
    re.IGNORECASE,
)


def main() -> int:
    try:
        data = json.loads(sys.stdin.read() or "{}")
    except Exception:
        print(json.dumps({"permission": "allow"}))
        return 0

    command = data.get("command") or data.get("tool_input", {}).get("command") or ""
    if not isinstance(command, str):
        print(json.dumps({"permission": "allow"}))
        return 0

    if WRITE_INTO_PROTECTED.search(command):
        msg = (
            "Blocked shell write into a protected Magento path "
            "(vendor/, generated/, pub/static/, app/etc/env.php, auth.json). "
            "Use Composer patches for vendor changes."
        )
        print(json.dumps({
            "permission": "deny",
            "user_message": msg,
            "agent_message": msg,
        }))
        return 0

    if FORCE_PUSH.search(command) or DROP_OR_TRUNCATE.search(command):
        msg = (
            "This shell command looks destructive (force-push or drop/truncate). "
            "Confirm with the developer before running."
        )
        print(json.dumps({
            "permission": "ask",
            "user_message": msg,
            "agent_message": msg,
        }))
        return 0

    print(json.dumps({"permission": "allow"}))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
