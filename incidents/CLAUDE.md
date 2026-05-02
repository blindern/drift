# CLAUDE.md

Reports of production outages and significant incidents.

## Why we write them

So a future investigator (often the same person, six months later) can recognize a pattern from a past failure and skip to the answer. Reports get re-read; design documents don't. Optimize for being skimmable and trustworthy: someone seeing a similar symptom should find the right report quickly and trust what it says.

## What a good report contains

- **Title and filename**: `YYYY-MM-DD-<service>-<short-summary>.md`. The title and filename should describe the visible symptom — that's what a future reader will be searching for.
- **Timeline**: UTC timestamps for trigger, first symptom, detection, mitigation, resolution. Quote relevant log lines verbatim — paraphrased logs are not as searchable or trustworthy.
- **Root cause**: the mechanism, with the trigger (what set it off this time) separated from the underlying condition (what made it possible). The condition is usually the more reusable lesson.
- **Resolution**: the exact commands or code change. Future-you wants to copy-paste, not interpret.
- **Anything else worth recording**: incidental findings, follow-up work. Group these neutrally; don't mix them into the root cause.

## Style

- Prefer facts over framing. If you don't know, say "unknown" or omit.
- Skip speculation ("would have caught this", "probably means…") and value words ("serious", "fortunately").
- Reference past reports by filename, e.g. `2026-04-26-uka-billett-down.md`.
- Short is fine. A 30-line report that gets read beats a 200-line one that doesn't.
