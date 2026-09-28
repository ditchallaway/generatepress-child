# Typography presets — Q5 visual mood → exemplar handle + font pairing

Lookup table used by `intake/questions.md` to resolve Q5 "Visual mood" + Q6 "Typography" answers into the canonical exemplar handle (cited in the emitted prompt's §5) and the font-family slugs used in §4 style tokens.

## Q5 → exemplar mapping

| Q5 answer | Q1 product type | Exemplar handle | Why |
| --- | --- | --- | --- |
| Light & airy | (any) | `atlas-greens (R2)` | Cream + sage palette, generous whitespace, soft serif. Paste-tested R2 gold. |
| Bold & modern | (any) | `lumen-saas (R3)` | High-contrast, sans-display, color-blocked CTAs. Paste-tested R3 gold. |
| Dark & premium | (any) | `loom-ash-throw` | Charcoal + accent, dense type, restrained spacing. Paste-tested. |
| Earthy & organic | Physical product / Consumable | `halcyon-field-jacket (R4)` | Olive + ochre, photography-forward, condensed sans. **First-try clean** in R4 — the lowest-friction reference for physical earthy products. Lookbook gallery + size guide + materials sections. |
| Earthy & organic | Digital download / SaaS / Online course | `hearth-hollow-almanac (R5)` | Cream + warm orange + oak palette, soft serif (Fraunces) + sans body (Inter). **First gold sourced from intake-mode flow** (v7.22.2). Multi-tier price-chooser + 1-axis format variants + L/R reviews layout (HC#53) + 3-step how-it-works + FAQ accordion. Use for any digital/course/SaaS product with editorial earthy brand voice. |
| Tech-SaaS / dashboard | (any) | `northwind-kettle` | Grid + iconography, condensed metrics. Paste-tested. |
| Clean default | (any) | `aurora-lamp` | Neutral palette, no brand commitment. Paste-tested. |

The exemplar handle is cited in §5 of the emitted prompt to give Claude Design a concrete visual anchor: *"Use this structural shape as your starting point (paste-tested `halcyon-field-jacket (R4)`)..."*. The full exemplar file lives in `examples/patterns/<exemplar>.example.md` and is NOT inlined in the prompt (token budget).

## Q5 + Q6 → font-family slugs

The emitted prompt's §4 style tokens use named font-family preset slugs. Each Q5 mood has a default pairing; Q6 can override.

### Default pairings per Q5 (used when Q6 = "Match theme")

| Q5 answer | Display slug | Body slug | Notes |
| --- | --- | --- | --- |
| Light & airy | `sc-serif-display` | `sc-body` | Soft serif headlines, sans body. Atlas-greens convention. |
| Bold & modern | `sc-display` | `sc-body` | Sans display, sans body. Lumen convention. |
| Dark & premium | `sc-condensed` | `sc-body` | Condensed sans display, sans body. Loom-ash convention. |
| Earthy & organic | `sc-condensed` | `sc-body` | Condensed sans display, sans body. Halcyon convention. |
| Tech-SaaS / dashboard | `sc-display` | `sc-body` | Sans display, sans body. Northwind convention. |
| Clean default | `surecart-display` | `surecart-body` | The base SureCart slugs — no brand commitment. |

### Q6 override mapping (when Q6 ≠ "Match theme")

| Q6 answer | Display slug | Body slug | Mono accent? |
| --- | --- | --- | --- |
| Serif display + sans body | `sc-serif-display` | `sc-body` | No |
| Sans-only | `sc-display` | `sc-body` | No |
| Mixed (display sans + body sans + serif accents) | `sc-display` | `sc-body` + `sc-serif-accent` for blockquotes | No |
| Mono accents (price, badges, code) | `sc-display` | `sc-body` | Yes — emit `sc-mono` for `<ProductPrice/>` typography overrides |
| Match theme | (use Q5 default — see above table) | (use Q5 default) | No |

## Resolver pseudocode

```
exemplar = Q5 → exemplar_table[Q5_answer]
if Q6 == "Match theme":
    display_slug, body_slug = Q5_default_pairing[Q5_answer]
else:
    display_slug, body_slug = Q6_override[Q6_answer]
mono_accent = (Q6 == "Mono accents")
```

## Future expansion

When new exemplars land in `examples/patterns/` (paste-tested with a fresh visual identity), add Q5 options + new rows above. The current 6 moods cover the production exemplar set; PR4 polish may surface gaps.
