# Palette presets — accent color → theme.json preset slug

Lookup table used by `intake/questions.md` to resolve Q2 "Accent color" answers into `theme.json` preset slugs. The emitted Claude Design prompt embeds the resolved slug as a CSS variable reference (`var(--wp--preset--color--<slug>`) so merchant theme overrides cascade automatically.

Source of truth for the preset slug list: `reference/theme-partial.json` (SureCart-shipped palette). When the merchant gives a free-text hex with no close preset match (ΔE > 6 from all slugs), the resolver registers the literal hex as `surecart-custom-accent` and the merchant edits theme.json post-paste if they want a permanent slug.

## Q2 answer → preset slug + hex

| Q2 answer label | Preset slug | Hex (visual ref) | Notes |
| --- | --- | --- | --- |
| SureCart default (sage green) | `surecart-brand` | `#01824C` | The canonical SureCart accent. Ships with every install. |
| Warm — orange / coral / amber | `surecart-orange-500` | `#E8643A` | Food, beverage, lifestyle. Warm-side neutral. |
| Bold — red / magenta / electric | `surecart-rose-500` | `#E11D48` | Sale-driven, high-energy. Use sparingly. |
| Cool — blue / teal / cyan | `surecart-blue-500` | `#0EA5E9` | Tech, SaaS, finance, healthcare. |
| Dark — charcoal / navy / black-on-cream | `surecart-slate-900` | `#0F172A` | Premium, editorial, restrained. Pair with cream `surecart-stone-50` body bg. |

## Free-text hex resolution (when merchant ignores the labels)

If the merchant types a custom hex via the AskUserQuestion "Other" option:

1. Normalize to lowercase 6-char hex (`#01824C`).
2. Compute ΔE (CIE76) against every preset hex in the table above.
3. If min ΔE ≤ 6 → use that preset's slug. Inform the merchant in the closing message: *"I matched your `#E84531` to `surecart-orange-500` — the same warm orange family."*
4. If min ΔE > 6 → use `surecart-custom-accent` as the slug, emit the literal hex into the `--wp--preset--color--surecart-custom-accent` declaration in the prompt's §4. Inform the merchant: *"Your color doesn't match a stock SureCart preset. The prompt registers it as `surecart-custom-accent` — edit `theme.json` after paste to rename the slug if you'd like."*

## Token-budget note for the prompt

The §4 style-tokens block in `prompt-template.md` only needs to reference the **one** resolved preset slug, not the full table. The full table here is intake-time only — never embedded in the emitted prompt.

## Future expansion

When a new SureCart palette ships (e.g. `surecart-emerald-500`, `surecart-violet-500`) and the questionnaire surfaces it as an option, add a new row above. The resolver auto-picks up new presets if they're added to `reference/theme-partial.json` and the Q2 options expand.
