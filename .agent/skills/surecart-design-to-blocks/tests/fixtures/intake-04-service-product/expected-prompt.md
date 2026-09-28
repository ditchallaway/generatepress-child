# Expected emitted prompt (golden) — slot substitution summary

Full prompt body matches `prompt-template.md` verbatim except for the substituted slots below. See `intake-01-minimal-answers/expected-prompt.md` for the verbatim shape; this fixture documents only the diff.

## Slot substitution table (for this fixture)

| Slot | Resolved value |
| --- | --- |
| `Q1_PRODUCT_TYPE` | Service / booking |
| `Q2_ACCENT_DESCRIPTION` | Cool — blue / teal / cyan |
| `ACCENT_PRESET_SLUG` | `surecart-blue-500` |
| `ACCENT_RESOLVED_HEX` | `#0EA5E9` |
| `Q3A_VARIANTS` | None |
| `VARIANT_BLOCK` | (empty — Q3a=None) |
| `Q3B_PRICING` | (not asked — Q1=Service branched it off; defaults to "Booking-driven" in §6) |
| `Q4_SECTIONS` | Reviews (full section + ratings histogram), How it works (steps) |
| `STATIC_SECTIONS` | `<ReviewSection/>\n<HowItWorks/>` |
| `Q5_VISUAL_MOOD` | Clean default |
| `EXEMPLAR_HANDLE` | aurora-lamp |
| `Q6_TYPOGRAPHY` | Match theme (default applied since Q6 was branched out) |
| `DISPLAY_FONT_SLUG` | `surecart-display` |
| `BODY_FONT_SLUG` | `surecart-body` |
| `HERO_PILL_TEXT` | BOOK A SESSION (derived from Q1=Service) |
| `HERO_TRAILING_SECTIONS` | (empty — Q4 does not include "Sticky bar"; Service branch filtered it out) |

## Branching coverage — heaviest-skip path

This fixture exercises the **minimum-question intake path**: Service products skip Q3b entirely, and Clean-default mood skips Q6. Batch B contains only Q4. Total questions answered: **5** (vs. 7 in `intake-02-full-answers`).

- **Q3b complete skip** — only Q1=Service triggers Q3b removal. The intake brief's "Pricing model:" line is filled with `Booking-driven (no recurring billing)` as the fallback descriptor.
- **Q6 complete skip** — Q5=Clean default removes Q6 from Batch B. Typography slugs default to `surecart-display` / `surecart-body`.
- **Q4 option filter** — Sticky bar removed because Service is non-shipping (no add-to-cart sticky pattern needed).
- **Batch B minimum case** — only Q4 asked in Batch B (Q3b skipped + Q6 skipped → 1 question batch).

## §6 merchant brief lines

```
- Brand mood: Clean default (visual reference: `aurora-lamp`).
- Product type: Service / booking.
- Pricing model: Booking-driven (no recurring billing).
- Variants: None.
- Sections to include: Reviews (full section + ratings histogram), How it works (steps).
- Typography: Match theme.
- Accent color: Cool — blue / teal / cyan (preset: `surecart-blue-500`).
```

## §5 reference shape

Uses `aurora-lamp` (neutral exemplar). The hero pill text is `BOOK A SESSION` instead of the default `JUST RESTOCKED` — `questions.md`'s `HERO_PILL_TEXT` derivation maps Q1=Service → that label.

## Notable: variant block + price chooser both absent

Because Q3a=None and Q3b is unset, the §5 hero shape elides both `<VariantPills/>` and `<PriceChooser/>` — the hero is simpler:

```jsx
<Hero cols="2">
  <ProductGallery is-style-orbit/>
  <Pill text="BOOK A SESSION"/>
  <ProductTitle/>
  <ReviewStars/>
  <ProductPrice/>
  <BuyButtons>
    <BuyButton primary addToCart text="Book Consultation"/>
  </BuyButtons>
</Hero>
```

Only ONE buy button (services typically have a single CTA, not Add-to-Cart + Buy-Now pair). The merchant edits post-paste if they want both.
