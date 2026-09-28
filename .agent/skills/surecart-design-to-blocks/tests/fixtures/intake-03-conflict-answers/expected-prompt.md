# Expected emitted prompt (golden) — slot substitution summary

Full prompt body matches `prompt-template.md` verbatim except for the substituted slots below. See `intake-01-minimal-answers/expected-prompt.md` for the verbatim shape; this fixture documents only the diff.

## Slot substitution table (for this fixture)

| Slot | Resolved value |
| --- | --- |
| `Q1_PRODUCT_TYPE` | Online course |
| `Q2_ACCENT_DESCRIPTION` | Warm — orange / coral / amber |
| `ACCENT_PRESET_SLUG` | `surecart-orange-500` |
| `ACCENT_RESOLVED_HEX` | `#E8643A` |
| `Q3A_VARIANTS` | None |
| `VARIANT_BLOCK` | (empty — Q3a=None) |
| `Q3B_PRICING` | One-time |
| `Q4_SECTIONS` | Press / quotes band, Comparison table, How it works (steps) |
| `STATIC_SECTIONS` | `<PressBand/>\n<ComparisonTable/>\n<HowItWorks/>` |
| `Q5_VISUAL_MOOD` | Earthy & organic |
| `EXEMPLAR_HANDLE` | halcyon-field-jacket (R4) |
| `Q6_TYPOGRAPHY` | Serif display + sans body |
| `DISPLAY_FONT_SLUG` | `sc-serif-display` (Q6 override of Q5's default `sc-condensed`) |
| `BODY_FONT_SLUG` | `sc-body` |
| `HERO_PILL_TEXT` | ENROLL NOW (derived from Q1=Course + Q3b=One-time) |
| `HERO_TRAILING_SECTIONS` | (empty — Q4 does not include "Sticky bar"; Course branch filtered it out) |

## Branching coverage

- **Q3b option filter exercised** — "Pay-what-you-want" removed from Q3b options before AskUserQuestion, because Q1=Course triggered the filter (per `questions.md` branching rule)
- **Q4 option filter exercised** — "Sticky bar" removed from Q4 options, same reason (Course is non-shipping)
- **Q6 explicit override** — merchant chose "Serif display + sans body" which overrides Q5's default `sc-condensed` display slug (Q5=Earthy default is condensed sans); the resolver per `typography-presets.md` returns `sc-serif-display` + `sc-body`
- **Exemplar mapping** — Q5=Earthy → `halcyon-field-jacket (R4)` (first-try-clean paste-tested gold)

## §5 reference shape (notable — uses Q5-mapped exemplar)

The §5 compressed exemplar block cites `halcyon-field-jacket (R4)` as the visual anchor — different from the `aurora-lamp` default used in intake-01. The card-template structure inside `<Hero>` is identical across exemplars; only the named reference changes.

## §6 merchant brief lines

```
- Brand mood: Earthy & organic (visual reference: `halcyon-field-jacket (R4)`).
- Product type: Online course.
- Pricing model: One-time.
- Variants: None.
- Sections to include: Press / quotes band, Comparison table, How it works (steps).
- Typography: Serif display + sans body.
- Accent color: Warm — orange / coral / amber (preset: `surecart-orange-500`).
```
