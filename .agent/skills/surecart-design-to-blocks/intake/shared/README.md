# Shared intake assets (master skill — borrowed by sibling)

Files in this directory are **canonical here** in `surecart-design-to-blocks/intake/shared/` and **referenced by relative path** from `surecart-design-to-shop-page/intake/`.

This mirrors the established precedent where the shop-page sibling references the master's `reference/*` files via `../surecart-design-to-blocks/reference/*` — 9 of 11 reference files are borrowed that way. The intake/shared assets follow the same lazy-reference convention.

## Files

- `anti-patterns.md` — banned design moves common to both surfaces (animations, parallax, fixed-position chrome, etc.), written as positive replacements
- `palette-presets.md` — accent color → theme.json preset slug lookup; both skills splice the same accent question into their prompts
- `tone-presets.md` — brand voice options (modern minimal, editorial, technical, etc.)
- `typography-presets.md` — per-exemplar font pairings keyed to Q5 visual-mood answers

## Symmetry contract

Both skills MUST use these assets without copying them. If a future PR finds the sibling needs a divergent version of any file here, the right move is to **fork that file into the sibling's own `intake/`**, not to duplicate it in `shared/`. The "shared" directory only contains files that are truly identical for both surfaces.

## PR1 status (v7.22.0-alpha.1)

Scaffold only. `anti-patterns.md` ships with the full positive-replacement content (it is content-stable). The preset/tone/typography files ship as stubs — PR2 populates them as part of the questionnaire wiring.
