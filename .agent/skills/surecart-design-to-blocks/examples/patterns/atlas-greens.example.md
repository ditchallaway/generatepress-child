# `atlas-greens` — Wellness Supplement Product Page (v7.15 gold reference)

**Source:** Atlas Greens Daily Essentials Powder design (Claude Design export, 2026-05-27). Designed to stress-test the **5 patterns with no dedicated primitive** (press strip, stat counter strip, comparison table, testimonial carousel, quantity-discount tier strip) plus validate cumulative v7.15 doctrine across a NEW palette family (sage + cream) the skill had never trained on.

**Block type:** `surecart/product-page`
**Pattern name:** `atlas-greens-pdp`
**Output file:** [`atlas-greens.example.html`](./atlas-greens.example.html) (113 KB, 294 block opens, 14 sections)
**Paste-test status:** clean on first try (zero "Attempt Block Recovery" prompts), 2026-05-27 — **best paste-test result of the entire training run**.

## Why this exemplar exists

After 12 of 18 audit items shipped to v7.15 (W1.1 block-style variations catalog, W1.2 cover.contentPosition, W2.5 core/icon rich attribute schema, W2.6 7 wp-core default fixes, W2.9 surecart special attrs catalog, W3.14 SKILL.md changelog extraction, plus block-name corrections), this fixture validated all of them empirically against a NEW archetype the skill had never seen:

1. **Palette diversity** — sage + cream + warm gold accent (forest greens, pale-sage tints) — first design outside the warm-earth family that has dominated training (Aurora Lamp, Morning Glow, Loom & Ash, Northwind Kettle, Kobachi all warm-earth).
2. **5 patterns with no dedicated primitive** — press strip 5-up, stat counter 4-up, comparison table 4-col with highlighted column, testimonial carousel 3-up, quantity-discount tier strip 3-up. The skill emitted ALL of them cleanly using compositional flex groups (no L1 fallback to `core/html` needed for layout — only used `core/html` for the comparison-table cells where `core/table` couldn't express the highlighted-column chrome).
3. **First-try cleanliness** — unlike R1 Kobachi which required v2 corrections, R2 Atlas Greens parsed clean with zero recovery prompts on the first paste. Validates that cumulative v7.15 doctrine has reached generalization beyond fixture-overfitting.

## Section ledger

| Section | Composition | SureCart blocks used | Primitives |
|---|---|---|---|
| 1. Sticky header | `is-position-sticky` (HC#33: no inline position-mirror) + brand + 4-link nav + search icon + cart-icon-with-badge inline-SVG | none | Custom flex, HC#33 |
| 2. Hero | 2-col split (51%/49%): gallery (main `core/cover` with custom jar SVG + 4 thumbs) + info column (P11 pill, product-title, tagline, rating row, `surecart/product-price-chooser`, quantity-discount tier strip [no primitive — compositional P5 + P4 chrome], stepper [HC#38 `is-style-orbit`] + buy-button row, trust strip [P12 icon bubble row]) | `surecart/product-title` + `surecart/product-price-chooser` + `surecart/product-price-choice-template` + `surecart/price-name` + `surecart/price-interval` + `surecart/price-amount` + `surecart/product-quantity` + `surecart/product-buy-buttons` + `surecart/product-buy-button` | P2 hero split + P10 info column + P11 pill + P12 icon bubble + HC#33 + HC#38 |
| 3. Press strip | Full-width band with top+bottom borders, "AS SEEN IN" eyebrow + 5-up flex row of publication names with per-paragraph fontFamily/fontStyle differentiation (italic serif Vogue/The Cut, bold sans GQ/WIRED, regular Forbes) | none | NO primitive — compositional flex |
| 4. Stat counter strip | Sage-tint band (#E3EDDE) + 4-up flex (23% each) of large serif sage numbers (47% / 92% / 30B / 75+) + caption | none | NO primitive — compositional flex |
| 5. Science 3-step | Section-head + 3-up flex with sage numbered circles via `core/html` L1 + serif h3 + body | none | P5 multi-card |
| 6. Ingredients 3×2 grid | Section-head + 6-card 3-up flex (32% each, wraps to 3×2) with P12 sage icon-bubble (44×44) + custom SVG via `core/html` + h3 + body + muted "Sourced from" line | none | P5 + P12 + P3 vertical icon-text card |
| 7. Comparison table | Section-head + 4-col `core/html` table with highlighted Atlas Greens column (sage tint bg + 2px sage border + rounded top/bottom corners) — `core/table` `is-style-stripes` couldn't express column-highlight chrome, so dropped to `core/html` L1 with full inline styles | none | NO primitive — `core/html` L1 |
| 8. Testimonial carousel | Sage-tint full-width band (#E3EDDE) + section-head + 3-up flex (32% each) of white-bg testimonial cards with avatar circle [varied bg color] + name + role + 5 gold stars + serif headline + body + "— City, State" | none | P5 multi-card + P4 chrome + custom avatar via `core/html` L1 |
| 9. Reviews | Section-head + `surecart/product-review-list` full Start-Basic template (HC#19 + A-27 — summary + sidebar + template + pagination + no-reviews branch) | `surecart/product-review-list` + `-reviews` + `-review-summary` + `-review-average-rating-value` + `-stars` + `-total-rating` + `-breakdown` + `-list-sidebar` + `-list-filter-tags` + `-review-template` + `-review-title` + `-review-content` + `-review-reviewer-name` + `-review-verified-badge` + `-review-date` + `-review-pagination` + `-no-reviews` | Production exemplar |
| 10. FAQ | Section-head + 6 `core/details` with first one `showContent:true` (W2.6 verified) | none | P9 FAQ details list |
| 11. Subscribe & Save CTA | Sage-tint full-width band + centered serif h2 + body + sage-pill "Start subscription — $49/month" `core/button` + 3-check row | none | P6 CTA band |
| 12. Related products | Section-head + `surecart/product-list-related` with full Start-Basic template (A-28) + `surecart/product-template` (4-col grid) + cover with `useFeaturedImage:true` + quick-view-button `is-style-show-on-hover` + sale-badge + product-title + product-list-price | `surecart/product-list-related` + `-product-template` + `-product-quick-view-button` + `-product-sale-badge` + `-product-title` + `-product-list-price` + `-product-pagination` | Production exemplar |
| 13. Footer | FULL DARK section (bg #1F2D24) + `core/columns` 3-col (38% wordmark+tagline+social bubbles via `core/html` / 22% Shop nav links / 40% newsletter form via `core/html` L1) + hairline-separated bottom row | none | Dark-section archetype (v7.14) |
| 14. Sticky add-to-cart bar | `surecart/sticky-purchase` paired with product-media (44px) + product-title + subtitle + selected-price-amount + buy-button | `surecart/sticky-purchase` + `surecart/product-media` + `surecart/product-title` + `surecart/product-selected-price-amount` + `surecart/product-buy-buttons` + `surecart/product-buy-button` | Production exemplar |

## v7.15 doctrine demonstrated (empirically validated on frontend paste-test)

### HC#33 — Sticky no inline position-mirror

The header `core/group` has `style.position.type:"sticky"` + `top:"0px"` in JSON, but **NO** `position:sticky;top:0px;z-index:N` in inline `style`. Only `is-position-sticky` class. Verified: header sticks to top on scroll without recovery error.

### HC#38 — Quantity uses `is-style-orbit` block-style variation

```
<!-- wp:surecart/product-quantity {"label":"Quantity","hidden_label":true,"className":"is-style-orbit",...} /-->
```

The `is-style-orbit` registered block-style produces the round-pill chrome. `hidden_label:true` hides the visible "Quantity" label above the stepper while keeping the a11y `label` attr. Verified: stepper renders as `[− 1 +]` pill on frontend (NOT as the default square box).

### P11 filled pill (NEW FORMULA banner)

```
<!-- wp:paragraph {"fontFamily":"surecart-body","style":{...,"color":{"text":"#FCFAF6","background":"#4F8F5C"},...,"border":{"radius":"9999px"}}} -->
<p class="has-text-color has-background ..." style="border-radius:9999px;color:#FCFAF6;background-color:#4F8F5C;padding:7px 14px;...">NEW FORMULA</p>
<!-- /wp:paragraph -->
```

Sage filled pill with white uppercase letterspaced text. Verified: renders correctly as capsule on frontend (no recovery, color routing correct).

### P12 icon bubble (Trust strip + ingredients cards)

```
<div style="width:44px;height:44px;border-radius:9999px;background:#E3EDDE;color:#2E5C39;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">
  <svg viewBox="0 0 24 24" width="22" height="22" ...>...</svg>
</div>
```

Round (9999px radius) bubble with sage-tint background containing custom SVG glyph. Used in trust strip (3-up) and ingredients grid (6 cards). Verified: bubbles render at correct size/color/alignment, custom SVGs visible.

### Dark-section archetype (v7.14)

The footer wraps every text node with explicit `color.text` set to `#F7F5F0` (cream) or `#9FA89F` (muted cream). Background-set via `core/group` with `color.background:"#1F2D24"`. Form inputs/buttons use `rgba(255,255,255,0.06)` translucent backgrounds. Verified: footer inverts cleanly with no fallback-light leaks.

### W2.6 — `core/details.showContent:true` defaults to OPEN

```
<!-- wp:details {"summary":"How does it taste?","showContent":true,...} -->
<details ... open><summary>How does it taste?</summary>
  ...body...
</details>
<!-- /wp:details -->
```

Verified: first FAQ item renders OPEN on initial page load; the other 5 collapsed. The `showContent` default fix (W2.6 audit) verified empirically.

### W2.5 — `core/icon` for built-in slugs

Used `core/icon` with slugs `core/search` (header), `core/check` (3 places: trust strip + subscribe checks), `core/star-filled` (trust strip "Made in USA"). All resolved via `WP_Icons_Registry`. NO inline-SVG `core/html` fallback for these slugs.

### Comparison table — `core/html` L1 the right call

Considered `core/table` `is-style-stripes` (added in W2.5) but the design requires per-column highlighting (Atlas Greens column has sage tint + thicker border + rounded corners) which `core/table` cannot express. Dropped to `core/html` L1 with full inline styles on the `<table><thead>...<tbody>` structure. **This is the correct choice** — `core/table` is for plain-data tables; column-highlight chrome requires custom CSS routing.

### Press strip + stat counter + testimonial carousel — no primitive needed

The skill emitted all three cleanly using **compositional flex groups**:
- Press strip = `core/group` flex with `blockGap:"48px"`, `justifyContent:"center"`, 5 `core/paragraph` children with per-paragraph fontFamily/fontStyle.
- Stat counter = full-width sage band + `core/group` flex with `blockGap:"24px"`, 4 children (each a `core/group` with `flexSize:"23%"` containing `core/heading` h3 + `core/paragraph`).
- Testimonial carousel = full-width sage band + section-head + `core/group` flex with `blockGap:"24px"`, 3 children (each a white-bg `core/group` with `flexSize:"32%"` containing P4-chrome avatar + name + stars + headline + body + attribution).

The audit predicted these would need P14-P18 primitives. Paste-test proves they don't — pure flex composition reaches the target faithfully. **Architectural finding:** the W2.8 P14-P18 primitives are now lower priority than originally ranked.

## Cosmetic gaps surfaced (new HCs)

Round 2 surfaced 3 cosmetic gaps captured in v7.16 HC additions:

1. **HC#39** — `surecart/sticky-purchase` position bug (height/top wrong on frontend). Block renders `position:fixed` but content overflows. Future fix: explicit `bottom:0` style + content-height cap.
2. **HC#40** — `core/cover` thumbnails with `useFeaturedImage:true` override per-thumb `background-color`. For 4-up thumb-strip placeholders use plain `useFeaturedImage:false` instead.
3. **HC#41** — `fontFamily:"surecart-display"` attr + inline `font-family:EB Garamond, serif` conflict: theme's `--wp--preset--font-family--surecart-display` CSS var wins and falls through to sans-serif. When emitting literal font-family fallback (HC#27), do NOT also emit `fontFamily:"..."` — pick one path.

## Read this exemplar as canonical for

- Wellness / supplement / cosmetics products with subscription + quantity-tier UX
- Sage/cream palettes (or any non-warm-earth palette family)
- Press strips, stat counters, testimonial carousels, comparison tables, quantity-discount tiers without primitive support
- Full Start-Basic templates on both `surecart/product-review-list` AND `surecart/product-list-related`
- HC#33 + HC#38 working in concert
- Dark-section footer with newsletter form
