# `aurora-smart-desk-lamp` — Compositional Product Page (v7.6 gold reference)

**Source:** Aurora Smart Desk Lamp design (Claude Design export, 2026-05-11). Designed to exercise every primitive (P1–P10) and surface every paste-recovery edge case so future generations of the skill match this output's class/style emission exactly.

**Block type:** `surecart/product-page`
**Pattern name:** `surecart-aurora-lamp`
**Output file:** [`aurora-lamp.example.html`](./aurora-lamp.example.html) (72 KB, 612 lines, 88 blocks)
**Paste-test status:** clean (zero "Attempt Block Recovery" prompts), 2026-05-11.

## Why this exemplar exists

The earlier 12 exemplars (product-standard, product-physical, etc.) are derived from the SureCart plugin's own pattern library — each isolated and focused on one archetype. Aurora is the first **composed** exemplar: a single page that strings 8 distinct section archetypes through one off-white body theme, alternating with two white-bg sections and ending with a dark CTA band. It's the realistic shape of a Claude Design export.

It also captures four v7.6 paste-test discoveries that retroactively corrected the v7.3 docs:

1. **`has-border-color` always-emit policy** (A-20, B-14 v7.6 reversal): every bordered card carries `has-border-color` regardless of slug-vs-literal. Reverses v7.3's "OMIT for literal-hex" rule.
2. **No HTML comments inside `surecart/product-page`** (A-21): annotations like `<!-- 1. HERO === -->` get rejected as freeform content by the apiVersion-3 parser.
3. **`core/image` is strict-schema** (A-22): no freehand inline styles on `<figure>` or `<img>`. Use a parent `core/group` for flex-width.
4. **`core/list` requires `wp-block-list` class** + no inline-styled `<strong>/<span>` children inside `<li>` (A-23). For 2-col tabular data, use P7 detail-row primitive instead.

## Primitives used

| Section | Primitives | Notes |
|---|---|---|
| 1. Hero | P2 (flex split, `core/columns` form) + P10 (info column) + P8 (inline review) + 3× P3 (trust-row icons) | Uses `core/columns` instead of P2's `core/group` flex variant because the design's media is on the LEFT and `core/columns` handles `width:"52%"`/`"48%"` cleaner. Both are valid P2 expressions. |
| 2. Features | P5 (multi-card row) + 6× P4 (bordered card chrome) + P3 (vertical icon-text card per cell) | 3-up × 2 rows = `flexSize:"31%"` with `flexWrap:"wrap"`. Every card has `has-border-color has-background` (A-20). |
| 3. Gallery | P5 (multi-card row) + 4× `core/group` wrapping `core/image` | 2×2 grid = `flexSize:"48%"`. Each image is schema-clean (A-22): only `border-radius` inline. |
| 4. Details (tabs→accordion) | Bordered `core/group` shell + 3× `core/details` | Tabs convert to vertical accordion (Gutenberg has no tabs block). Specs use P7 detail-row primitive (10 rows × label/value pair) instead of `core/list` (A-23). |
| 5. Reviews | Custom summary row (atomic SureCart review blocks) + `surecart/product-review-list /` | The summary is composed atom-by-atom: `product-review-average-rating-value` + `product-review-average-rating-stars` + `product-review-total-rating` + `product-review-add-button`. Avoids the gray default summary card. |
| 6. FAQ | P9 (FAQ details list, centered) + 5× `core/details` | Section uses `contentSize:"880px"` for the 880px max-width centered FAQ list. Each details has bordered chrome with `has-border-color has-background`. |
| 7. Related | section-head + `surecart/product-list-related /` | Server renders related products. |
| 8. FinalCTA | P6 (CTA band, dark variant) | `align:"full"` outer with literal `background-color:#1A1714`. Inner constrained at `contentSize:"760px"`. Buy-buttons with `justifyContent:"center"` for the centered CTA pair. |

## Conventions demonstrated (use as reference for paste-safety questions)

- **Outer wrapper:** `surecart/product-page` with `align:"full"` and `layout:{type:"constrained",contentSize:"1200px"}`. No `<div>` wrapper (apiVersion 3).
- **No HTML comments inside the wrapper.** Sections are bare siblings.
- **Section pattern:** each section is `core/group align:"full" layout:"default"` with bg + vertical padding (80px or 96px) + horizontal padding (24px or 32px) + bottom border (`#F0EBE0`, omitted on the last section). Inside, a nested `core/group layout:"constrained"` carries `contentSize` only (no chrome, no inline style).
- **Body-bg pattern:** off-white sections use literal `background-color:#FBF9F5`. White sections (Features, FAQ) use the `surecart-white` slug. Dark section (FinalCTA) uses literal `#1A1714`. No body-bg shim because sections alternate bg colors.
- **Bordered cards:** every P4 wrapper emits `has-border-color has-background` class + inline `border-color:#F0EBE0;border-style:solid;border-width:1px;border-radius:12px;background-color:#FBF9F5;padding-*`. Set-equality preserved (B-27).
- **Per-side asymmetric borders:** section dividers use `border:{bottom:{color,width},top:[],left:[],right:[]}` (no top-level color). These do NOT emit `has-border-color`. Only `border-bottom-color:#F0EBE0;border-bottom-width:1px` in inline.
- **Buy-buttons:** primary `{add_to_cart:true,text:"Add to Cart",style:{border:{radius:"9999px"}}}`. Secondary `{text:"Buy Now",className:"is-style-outline",...}`. Inverse-styled CTA (on dark): explicit `color.background:"#01824C",color.text:"#FFFFFF"` for primary, transparent `#1A171400` for outline.
- **Inline review summary (P8):** `core/group` flex `flexWrap:"nowrap"` containing `surecart/product-review-average-rating-stars /` + `surecart/product-review-total-rating /` (no className needed — `plus-sign` is `isDefault:true` per HC#19 v7.15; omitting the className is the canonical form). Replaces the default expanded-card form.
- **Card grids (P5):** `core/group` flex `flexWrap:"wrap",justifyContent:"space-between"` with each child `core/group` carrying `style.layout:{selfStretch:"fixed",flexSize:"31%"}` (3-up). Never `core/columns`.
- **Specs table (P7):** parent `core/group` (vertical flex) wrapping N detail-row `core/group` (horizontal flex `justifyContent:"space-between"`) with 2 `core/paragraph` siblings each. Replaces single `core/list` with inline-styled `<strong>/<span>` children (A-23).
- **Icons via `core/html`:** L1-safe inline SVG with explicit `stroke="#01824C"` (or `stroke="currentColor"` inside a container that sets `color`). Never `data-wp-*` directives at L1.
- **Image strict-schema (A-22):** `<figure class="wp-block-image size-large has-custom-border"><img src="..." alt="..." style="border-radius:16px"/></figure>`. No `flex-basis`, `aspect-ratio`, `object-fit`, `width` anywhere.
- **`core/list` plain bulleted only:** `<ul class="wp-block-list ...">` with plain-text `<li>` content. Used in "What's in the Box" — never for spec tables.

## How to use

When the merchant's design composes 8+ sections alternating background colors with a dark CTA band, this exemplar is the closest compositional reference. Read it as a HOW reference for class/style emission, NOT a WHAT template — compose your own primitives that match the actual design.

When in doubt about a specific paste-recovery error message, grep the `aurora-lamp.example.html` file for the relevant class or attribute pattern; the file is byte-equivalent to `save()` output and passes all 36 rubric items.

## Cross-references

- Hard Constraint #21 (`has-border-color` always-emit) — SKILL.md
- Hard Constraint #22 (no HTML comments inside apiVersion-3 blocks) — SKILL.md
- Hard Constraint #23 (`core/image` strict-schema) — SKILL.md
- Hard Constraint #24 (`core/list` + `wp-block-list` class) — SKILL.md
- Tier A items A-20, A-21, A-22, A-23 — `rubric/self-validate.md`
- Border-color emission policy (v7.6 reversal) — `reference/style-conversion.md` Table B Rule 4
- Exemplar 7 (`core/list`) + 7.5 (`core/image`) — `reference/core-blocks-cheatsheet.md`
