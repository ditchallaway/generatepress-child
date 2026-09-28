# `halcyon-field-jacket` — Heritage Fashion Apparel Page (v7.18 gold reference — first-try clean)

**Source:** Halcyon Field Jacket design (Claude Design export, 2026-05-27). Tests `surecart/product-variant-pills` block (first paste-test of this block), warm-charcoal dark palette, fashion/apparel archetype with size + color variants.

**Block type:** `surecart/product-page`
**Pattern name:** `halcyon-field-jacket`
**Output file:** [`halcyon-field-jacket.example.html`](./halcyon-field-jacket.example.html) (90 KB, 14 sections)
**Paste-test status:** **FIRST-TRY CLEAN** — `invalid_count: 0` post-paste, zero recovery prompts, zero console validation errors. **2/3 streak** toward production-ready certification, 2026-05-27.

> **First gold to pass on first paste** — no comment-strip needed (HC#45 grep returned 0), no class-set conflict, no hallucinations. The v7.18 mandatory pre-emit grep gauntlet caught all violations before paste.

## Why this exemplar exists

R3 Lumen taught the skill that the existing A-21 "no comments inside wrapper" doctrine needed a grep-checkable enforcement (now HC#45). R4 Halcyon was the first paste-test with that enforcement applied from the start. Plus it covers untested dimensions:

1. **`surecart/product-variant-pills` block** — first ever paste-test of this paired block (with `surecart/product-variant-pill` child). Both color + size variant axes emitted as separate `product-variant-pills` blocks.
2. **Warm-charcoal dark palette** — `#1A1816` body bg with slight brown undertone (vs R3 Lumen's cool navy `#0A1124`). Demonstrates that the dark-section archetype handles palette family variations cleanly.
3. **Fashion/apparel archetype** — physical clothing with size + color variants, lookbook gallery (4-up covers), size guide measurement table, materials/care/longevity sections, brand-story band, dark 4-col footer.
4. **Mixed serif + sans + monospace** — EB Garamond display headings, Inter body, JetBrains Mono for monospace numerics (sizes, measurements, dates). All emitted with HC#41 Path B (literal-only).

## Section ledger (14 sections, all dark warm-charcoal)

| # | Section | Composition | Skill primitives |
|---|---|---|---|
| 1 | Sticky header | HC#33 sticky group, no inline position-mirror. Wordmark (serif + burnt-orange dot) + 4-link nav + cart-with-badge icon. | HC#33 |
| 2 | Hero (2-col 56/44) | LEFT: gallery cover with custom jacket SVG via `core/html` L1 + 4-thumb strip (Pine highlighted with `#C8694A` border). RIGHT: P11 "JUST RESTOCKED" pill + `surecart/product-title` + tagline + rating row + price block + 2× `surecart/product-variant-pills` (color row + size row) + stock badge + quantity stepper (HC#38 `is-style-orbit`) + buy button + 3-icon trust strip with P12 icon-bubbles | P2 hero split + P10 info column + P11 pill + P12 icon bubbles + HC#33 + HC#38 + HC#41 + HC#42 |
| 3 | Lookbook gallery | 4-up grid (`flexSize:48%`) of `core/cover` blocks with monospace caption "Acadia, ME — March 2026" etc. | P5 multi-card + HC#42 (covers with dark bg, no isDark:false) |
| 4 | Materials & construction | `#232220` band + 4-up flex of charcoal cards with sage-tinted icon bubbles + h3 + body | P5 + P3 vertical icon-text card |
| 5 (skipped) | Hotspot callouts | Not emitted — drop+log under merchant_action (decorative SVG schematic with numbered annotations requires absolute-positioning; no clean compositional path) | — |
| 6 | Size guide | Constrained 1000px band + section-head + measurement table via `core/html` L1 (6-column XS-XXL × 5-row Chest/Length/Sleeve/Shoulder/Waist) + monospace cells | `core/html` L1 |
| 7 | Care & longevity | 3-up flex (`flexSize:32%`) of charcoal cards with re-wax / lifetime-mends / buy-back services | P5 multi-card + P3 |
| 8 | Press strip | `#232220` band + 5 publication names in monospace uppercase muted-cream | No primitive — compositional flex |
| 9 | Reviews | Full `surecart/product-review-list` with summary + sidebar + template + pagination + no-reviews branch (Start-Basic A-27) | Production exemplar |
| 10 | FAQ | `#232220` band, max-width 880px + 6 `core/details` (first OPEN via `showContent:true`) | P9 FAQ details list |
| 11 | Related products | `surecart/product-list-related` Start-Basic template (A-28) with 4-col grid + quick-view `is-style-show-on-hover` (SHC#12) + sale-badge + title + price | Production exemplar |
| 12 | Brand story band | 120px padded `#232220` band + centered eyebrow + h2 + body + "Read our story →" link | P6 CTA band variant |
| 13 | Dark footer | `#0F0E0C` deeper bg + 4-col layout (28%/20%/20%/28%) + wordmark+tagline+social bubbles / Shop nav / Service nav / Newsletter form (`core/html` L1) | Dark-section archetype, 4-col extension |
| 14 | Sticky purchase | HC#44 canonical anatomy: `surecart/product-selected-variant-image` + title + selected-variant (mono) + selected-price-amount + single buy-button. Two-zone flex with `is-vertically-aligned-center`. | HC#44 |

## v7.18 doctrine demonstrated (all PASS on first try)

### HC#33 — Sticky header (no inline position-mirror)

Class `is-position-sticky` carries the behavior; JSON has `style.position.{type,top}`; inline `style=""` mirrors only color/border/spacing — NOT position.

### HC#41 — Path B literal font-family (no `surecart-display` class)

Every heading and paragraph uses ONLY `style.typography.fontFamily:"EB Garamond, serif"` (or `"Inter, sans-serif"` or `"JetBrains Mono, monospace"`). Zero `fontFamily:"surecart-*"` class attrs. Renders consistently across themes regardless of `--wp--preset--font-family--surecart-display` theme-var resolution.

### HC#42 — Cover `isDark` omitted on dark backgrounds

5 covers in the gold (1 hero + 4 lookbook) all have dark bg (`#232220`, `#2E2C29`). Zero `isDark:false` attrs anywhere. `save()` computes `is-dark` from luminance; no class-set mismatch.

### HC#44 — Sticky-purchase canonical inner anatomy

Uses `surecart/product-selected-variant-image` (NOT product-media). Two-zone flex with `selfStretch:"fit"`/`"fill"`. Single buy-button labeled "Add to cart". Title at `level:4`.

### HC#45 — Zero descriptive HTML comments (pre-emit grep PASS)

Pre-emit `grep -nE '^<!-- [^/w]' <file>` returned ZERO matches. The skill never emitted section labels or tier annotations — clean from the start.

### `surecart/product-variant-pills` × 2 (first paste-test of this block)

Two separate `<!-- wp:surecart/product-variant-pills -->` blocks emitted (one for Color row, one for Size row). Each wraps a single `<!-- wp:surecart/product-variant-pill /-->` child template with `highlight_text`/`highlight_background`/`highlight_border` chrome attrs. Server iterates per-variant on the SureCart product's configured options.

## Read this exemplar as canonical for

- **Fashion / apparel** product pages (clothing, footwear, accessories) with size + color variants
- **Warm-charcoal dark palette** (different from R3 Lumen's cool-navy dark)
- **`surecart/product-variant-pills` ×2** (multi-axis variants)
- **Heritage outfitter voice** — serif display + monospace numerics
- **Lookbook gallery** (4-up `core/cover` with monospace location/date captions)
- **Size guide measurement table** via `core/html` L1
- **Brand story band** — 120px padded centered section with serif h2 + body + link
- **First-try clean paste-test** — proof that v7.18 grep gauntlet catches violations BEFORE paste

## Paste-test methodology (for future R5+)

The v7.18 protocol that made R4 pass first-try:

1. Compose markup with strict adherence to HC#1-45.
2. **Pre-emit grep gauntlet** (Tier A blocking):
   ```bash
   FILE=<output.html>
   grep -nE '^<!-- [^/w]' "$FILE"     # HC#45 — descriptive comments (0)
   grep -c 'isDark":false' "$FILE"    # HC#42 — dark-cover conflict (0)
   grep -c 'product-review-average-rating-breakdown' "$FILE"  # HC#43 (0)
   grep -c 'fontFamily":"surecart-' "$FILE"  # HC#41 Path A purity (0 if using Path B)
   grep -B5 'surecart/product-media' "$FILE" | grep -c 'sticky-purchase'  # HC#44 (0)
   ```
3. Only paste if ALL checks return 0.
4. After paste: read `wp.data.select('core/block-editor').getBlocks()` for `invalid_count`.
5. After save: `read_console_messages(pattern:"validation|recovery|does not support")` for fresh validation errors.
