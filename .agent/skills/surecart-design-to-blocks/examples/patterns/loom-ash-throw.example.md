# Loom & Ash Handwoven Throw Blanket — v7.8 gold reference

Paste-tested 2026-05-11 against a fresh `surecart/product-page` Pattern in WordPress. Clean paste with zero "Attempt block recovery" prompts and zero "Start Basic / Select template" placeholder pickers.

## Why this exemplar exists

Third paste-tested gold reference after `aurora-lamp.example.{html,md}` (v7.6) and `morning-glow-serum.example.{html,md}` (v7.7). Surfaces a third silent failure mode previously hidden in both prior exemplars: **`surecart/product-review-list` and `surecart/product-list-related` shipped self-closed in v7.6 and v7.7**, which silently routed the editor into a *"Start Basic / Select a template"* placeholder UI instead of rendering the reviews/products. The merchant had to manually click "Start Basic" on every paste; no block-recovery error fired, so prior paste-tests missed it.

The Loom & Ash paste-test stressed both blocks simultaneously (the design has a full reviews section with histogram + breakdown, plus a 4-column related-products grid) and the manual setup was repetitive enough to surface as a real ergonomic complaint. Both blocks are now mandated paired with the canonical Start-Basic inner template — see rubric A-27 and A-28 in `rubric/self-validate.md`.

## Sections covered (18)

1. **Announce bar** — full-width `#EDE5D7` strip with shipping pitch; centered single paragraph; thin `#E5DCC9` bottom border.
2. **Header** — utility row (USD / EN / Find a store) on `#F8F3EB` with bottom border; logo row (`LOOM & ASH` Cormorant-Display 22px, 0.18em) + 5-link nav + 3 icon buttons + `surecart/cart-icon`.
3. **Breadcrumb** — small Inter 13px paragraph row; current page in ink.
4. **Hero** — `core/columns` 55/45 split. Left: oversized cushion frame around `surecart/product-media` plus 5 mini color swatches (flex-sized 19% each, NO margin to stay consistent). Right: `surecart/product-title` (52px Cormorant Garamond), subtitle, inline review summary (stars + total-rating + "Read all reviews ↓"), price row (`surecart/product-selected-price-amount` + scratch + `surecart/product-sale-badge` chip), bordered Klarna installment card, variant pills, size-guide link, quantity-control + buy-button row, free-shipping `core/details`, and 4-icon trust strip (free shipping / 30-day returns / Portugal / Oeko-Tex). **No `aspect-ratio` anywhere** (rubric A-24).
5. **Story (dark band)** — full-width `#1F1B17` cover with `core/columns` 50/50. Left: dark-tinted "video frame" card with play button + runtime in `core/html`. Right: "Our makers" eyebrow, "Slow craft, soft hands." 56px serif headline, body copy, "Watch the full film →" link. Below: 3-stat strip on a top-border dividing line (Cotton 70% / Linen 30% / Weave Handloom).
6. **Sizing** — heading block + `surecart/product-price-chooser` set to 3 columns, with `surecart/product-price-choice-template` carrying `price-name` + `price-amount` (Cormorant Garamond serif).
7. **Color story** — 6-tile grid wrapped in a single `core/group` flex (each tile is a flex-sized `core/group` at 32% width, 200px top-padding for tall card aspect, label + place under). All 6 tiles use IDENTICAL spacing-field cardinality (NO `margin` on any) — this is critical (see "Brace-counting bug" below).
8. **Care (light band)** — `core/columns` 40/60. Left: `core/group` with SVG illustration of a folded throw inside `core/html`. Right: 4-step ordered list expressed as 4 stacked detail-row `core/group` (NOT `core/list` — A-23 forbids `core/list` with inline-styled children).
9. **Reviews** — full `surecart/product-review-list` paired wrapper with the canonical Start-Basic template (rubric A-27). Heading "Customer Reviews", `surecart/product-reviews` wrapping `product-review-summary` (rating-value + stars + total-rating in columns, plus `product-review-breakdown`), filter sidebar with tags/checkboxes templates, `product-review-template` card (reviewer name, verified-badge, date, rating-stars, title, content), pagination triad, and `product-review-list-no-reviews` fallback.
10. **Press** — 5 magazine names (Kinfolk italic / Cereal / Dwell / Apartment Therapy italic / Domino) in a flex-wrap row inside a top/bottom-bordered band.
11. **Bundles** — heading block + flex row of 4 paired `core/group` cards. Each card has a tinted thumbnail tile inside, title (Cormorant), price row (current + line-through + Save-$ badge inline), and "Add bundle to cart →" link. All 4 cards have identical spacing-field cardinality.
12. **Hotspots (Details)** — heading block + custom `core/html` block with 5 absolute-positioned numbered buttons over a flat tinted background (the lifestyle illustration), followed by 5 `<strong>N.</strong> label` paragraphs in a flex-wrap row.
13. **FAQ** — heading block + 6 `core/details` with `showContent:true` on the first one (so it's expanded by default). Each has a bottom border; summary text in default font; answer paragraph in Inter 15px with `margin-top:10px`.
14. **Restock CTA** — `#EDE5D7` band with centered eyebrow + 56px headline + countdown timer + email form, all inside a single `core/html` block (the timer + form is a custom widget — see `reference/custom-html-fallback.md`).
15. **Related** — heading block + full `surecart/product-list-related` paired wrapper with the canonical Start-Basic template (rubric A-28). `product-template` at 4 columns, each card has a `core/cover` thumbnail with `useFeaturedImage:true`, `product-quick-view-button` + `product-sale-badge` overlay, then `product-title` (level 2), `product-list-price` + `product-scratch-price` row. Pagination triad below.
16. **Footer** — `core/columns` 32/22/22/24 split with logo column (LOOM & ASH + tagline + 4 social icon links + subscriber chip), Shop column (8 links), Help column (6 links), About column (5 links). Bottom strip: copyright + 5 payment-method badges (VISA/MC/AMEX/PP/PAY) + Privacy/Terms/Accessibility links.
17. **Sticky purchase** — `surecart/sticky-purchase` with selected-variant-image + title (level 4) + selected-variant text + selected-price-amount + a compact buy-button row.
18. **Section-band wrappers** — every full-width band uses a `core/group` shell carrying `align:"full"` + background hex + 88px vertical padding + an inner constrained-width `core/group` at `contentSize:"1280px"` (or `640px` for the FAQ/restock-CTA bands).

## Production-pattern primitives matched

- **P1 (body-bg shim):** the top-level `core/group` immediately inside `surecart/product-page` carries `#F8F3EB` to seed the page bg.
- **P2 (hero flex split):** the hero uses `core/columns` (55/45) NOT a flex-`core/group` — the design's vertical-top column alignment + nested layered content (cushion frame + mini-swatches) reads cleaner as columns.
- **P3 (vertical icon-text card):** the 4-icon trust strip in the hero (free shipping / 30-day returns / Portugal / Oeko-Tex) uses 4 flex-sized vertical `core/group` cards, each containing a `core/html` SVG + a 2-line caption paragraph.
- **P4 (bordered card chrome):** the Klarna installment card uses a paired `core/group` with `has-border-color` class + `border-color:#E5DCC9` + `border-style:solid` + 12px radius + `#FCFAF6` background (rubric A-20).
- **P5 (multi-card row):** the bundles row uses 4 flex-sized `core/group` cards at 23% width each.
- **P7 (detail-row):** the care 4-step list uses 4 stacked detail-row `core/group` (horizontal flex, gap 16px, number on left, label on right).
- **P8 (inline review summary):** the hero review chip uses `surecart/product-review-average-rating-stars` + `surecart/product-review-total-rating` flex pair (default `plus-sign` divider — no className needed per HC#19 v7.15) + "Read all reviews ↓" link, NOT the gray summary card (rubric B-25).
- **P9 (FAQ details list):** the FAQ uses 6 `core/details` blocks with bottom borders, no wrapper accordion (rubric: avoid custom JS accordions).
- **P10 (hero info column):** the right column of the hero stacks eyebrow → title → subtitle → review-chip → price-row → installment-card → variant-label → variant-pills → size-guide → quantity+buy → details → trust-strip.

## v7.8 hardened patterns demonstrated

| Pattern | Where | Why |
|---|---|---|
| `surecart/product-review-list` paired with Start-Basic | Section 9 | A-27 (this exemplar's headline lesson) |
| `surecart/product-list-related` paired with Start-Basic | Section 15 | A-28 (this exemplar's headline lesson) |
| Sibling consistency across 6 color tiles | Section 7 | A flex-sized child wrapper carrying BOTH `style.spacing.margin` AND `style.layout` (selfStretch/flexSize) is brace-fragile; siblings in the same grid must have identical spacing-field cardinality. The Loom & Ash first emit had `margin:{top:"0",bottom:"0"}` on ONE tile (Rust #A85037) and the brace miscounted, pushing `style.layout` outside `style` and creating a duplicate top-level `layout` key. `JSON.parse` kept the last `layout` (`type:"default"`), dropped the `selfStretch:"fixed"`/`flexSize:"32%"` from `style.layout`, and `save()` emitted a bare `<div class="wp-block-group has-background"></div>` while the post body had the full styled wrapper — set-equality failed. Fix: drop the `margin` field from the one outlier tile so brace counts match the other 5 siblings. |
| Custom HTML widgets | Sections 5, 12, 14, 16 | The video-frame play button, the hotspots' absolute-positioned numbered dots, the restock countdown + email form, and the social-icon row + payment-method badges all use `core/html`. Per `reference/custom-html-fallback.md`, custom widgets that don't map cleanly to Gutenberg primitives go in `core/html`. Always log them under `dropped_features` so the merchant knows their interactivity needs theme JS. |
| `plus-sign` variant on `surecart/product-review-total-rating` (DEFAULT — omit className per HC#19 v7.15) | Hero | When the review summary lives inline in the hero (P8), the count display picks up a "+" divider (e.g., "★★★★★ + 312 reviews"). This variant is `isDefault:true` in `block.json#styles`, so the canonical form OMITS any className. The old `is-style-plus-sign` literal is now an anti-pattern (round-trip rewrite). |
| Buy-button `text` attr mandatory | Hero + Sticky | `text:"Add to bag"` on every `surecart/product-buy-button` to prevent the default-text fallback (rubric A-19). |
| `metadata.name` + `metadata.patternName` on wrapper | Line 1 | Helps the merchant register this page as a SureCart Pattern; auto-stamped on save if missing, but explicit is better. |

## Where this exemplar applies

Use as a HOW reference (not a copy-paste template) when:

- The design has a customer-reviews section with histogram or breakdown chart — copy the `surecart/product-review-list` block verbatim, adjust typography to match.
- The design has a "you may also love" or "related products" grid — copy the `surecart/product-list-related` block verbatim, adjust `columnCount` and inner card typography.
- The design has a multi-tile color/swatch grid with uniform sizing — match all sibling tiles to the same spacing-field cardinality.
- The design has a 4–6 step care/instruction list — use stacked detail-row `core/group` (NOT `core/list`).
- The design has a custom-positioned hotspot or absolute-element layout — wrap in `core/html` and drop+log under `dropped_features`.

Do NOT copy this exemplar wholesale — decompose your design into primitives first, match primitives to sections, and only THEN consult this file for the canonical paste-safe emission per primitive.
