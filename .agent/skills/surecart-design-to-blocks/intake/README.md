# Intake phase — upstream questionnaire + Claude Design prompt builder

This directory holds the **intake mode** assets for `surecart-design-to-blocks`. Intake mode activates when the merchant invokes the skill empty (no attached design, no JSX/HTML paste, no URL) — see Step 0 in the parent `SKILL.md`.

The intake flow:

1. Step 0 (in `SKILL.md`) detects empty invocation → routes here
2. `questions.md` runs a small batched questionnaire (max 3 `AskUserQuestion` calls) capturing merchant intent — product type, accent, variants, mood, pricing model, page sections, typography
3. `prompt-template.md` substitutes answers into a 7-section Claude Design prompt with constraint-aware framing (§0 "MIDI for a sampler" → §8 self-check)
4. `constraint-digest.md` (generated from `SKILL.md` HC table) is referenced from the prompt to communicate SureCart-block + WP-core constraints upstream
5. Merchant pastes the emitted prompt into design.claude.com → gets a constraint-compliant design back → re-invokes this skill with the design → Step 0 routes to **convert mode** (unchanged Steps 1–5)

## Files

- `questions.md` — the AskUserQuestion driver (2-batch script, branching, with defaults)
- `prompt-template.md` — the 7-section Claude Design prompt skeleton with `{{slot}}` substitutions
- `constraint-digest.md` — **GENERATED** from `SKILL.md` HC table by `scripts/regen-digest.mjs` (do not hand-edit)
- `shared/` — assets reusable by the `surecart-design-to-shop-page` sibling skill via relative path
  - `anti-patterns.md` — 10 positive-replacement rules common to both surfaces
  - `palette-presets.md` — Q2 accent → theme.json preset slug lookup
  - `tone-presets.md` — brand voice options for Q5 + Q6 brief building
  - `typography-presets.md` — Q5 mood → exemplar handle + font-pairing lookup

## Validation (Tier I rubric)

The intake mode adds a new "Tier I" section to `rubric/self-validate.md` (items I-1 through I-9). Every populated prompt must pass all 9 Tier I checks before being emitted to the merchant.

## Zero-regression contract

The intake/ directory is **purely additive**. Convert-mode users see no behavior change from v7.21 (Step 0's convert branch falls through to the existing Steps 1–5 byte-for-byte). The `intake-99-convert-contract.test.mjs` re-runs all 14 existing fixtures via `tests/diff.mjs` to enforce this — blocks merge on any drift.

## Test fixtures

Two intake-mode golden fixtures live under `tests/fixtures/`:

- `intake-01-minimal-answers/` — merchant accepts all defaults (Q5=Clean default → Q6 branch skipped; exemplar=aurora-lamp; accent=surecart-brand)
- `intake-02-full-answers/` — SaaS product with cool blue accent, 1-axis variants, monthly+yearly subscription toggle, Tech-SaaS mood (northwind-kettle exemplar), mono-accent typography

PR4 adds 2 more fixtures (intake-03 conflict-answers, intake-04 service-product) for branching coverage.
