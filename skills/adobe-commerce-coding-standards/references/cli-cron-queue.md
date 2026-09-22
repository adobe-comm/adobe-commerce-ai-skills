# CLI, cron, and queue

1. Mirror an existing command/cron/consumer in the same codebase.
2. CLI: clear options, no secrets in output, idempotent where possible.
3. Cron: correct group/schedule; avoid unbounded work without batching if the project batches elsewhere.
4. Queue: confirm topology/consumer XML names against installed Magento version files in the repo.
5. Logging: use project logger patterns; never log credentials or full payment payloads.
