# `morning-glow-serum` — Skincare Product Page (v7.7 gold reference)

**Source:** Morning Glow Vitamin C Serum design (Claude Design export, 2026-05-11). Designed to stress-test subscription pricing, variant chips, before/after gradient halves, sticky add-bar, and footer newsletter form — surfacing v7.7 paste-test corrections.

**Block type:** `surecart/product-page`
**Pattern name:** `surecart-morning-glow-serum`
**Output file:** [`morning-glow-serum.example.html`](./morning-glow-serum.example.html) (84 KB, ~112 blocks)
**Paste-test status:** clean (zero "Attempt Block Recovery" prompts) after 3 v7.7 corrections applied, 2026-05-11.

## Why this exemplar exists

Aurora Lamp (v7.6) exercised compositional layout primitives across 8 sections. Morning Glow Serum extends that with 12 sections covering subscription pricing (radio price-chooser), variant chips with active-state chrome, before/after side-by-side decoration, breakdown bar chart via `core/html`, dedicated sticky add-bar via `surecart/sticky-purchase`, and a 3-column footer with newsletter form. It surfaced three new `save()` behaviors that retroactively corrected the v7.0 cheatsheet and v7.6 hard constraints:

1. **`core/group` doesn't inline-mirror `style.dimensions.aspectRatio`** — A-24 / HC25. The CSS-var routing means `aspect-ratio:1` in the inline mirror breaks set-equality. Use explicit padding instead.
2. **Zero-value spacing properties REQUIRE JSON↔inline parity** — A-25 / HC26. `style.spacing.margin:{top:"0",bottom:"0"}` in JSON without `style="margin-top:0;margin-bottom:0"` on the wrapper triggers recovery.
3. **`core/cover` gradient class set is `wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient`** — A-26 / HC27. The v7.0 cheatsheet had `wp-block-cover__gradient-background has-background-gradient` which was wrong. Recommended fallback: use `core/group` solid-bg for decorative gradient panels.

## Section ledger

| Section | Composition | SureCart blocks used | Primitives |
|---|---|---|---|
| 1. Header | Sticky-position-DROPPED 3-col flex: brand text + nav links + cart icon | `surecart/cart-icon` | Custom flex |
| 2. Hero | 2-col split (52%/48%): media + thumb gallery + info column with subscription radio | `surecart/product-media` + `surecart/product-review-average-rating-stars` + `surecart/product-review-total-rating` + `surecart/product-price-chooser` + `surecart/product-price-choice-template` + `surecart/price-name` + `surecart/price-amount` + `surecart/product-variant-pills` + `surecart/product-variant-pill` + `surecart/product-quantity` + `surecart/product-buy-buttons` + `surecart/product-buy-button` | P2 hero flex split + P10 info column + P8 inline review summary |
| 3. Benefits strip | 4-up flex with right-border dividers, no card chrome, pastel-bg icon circles via `core/html` | none | P5 multi-card row |
| 4. Before & After | 2-col split with solid pastel-bg halves (gradient dropped + logged) | none | P5 row with P4-style colored panels |
| 5. Ingredients | 2-up × 3 flex grid (6 pastel-bg cards) | none | P5 + P3 vertical icon-text cards |
| 6. How to Use | 4-up flex with numbered circles (no card chrome) | none | P5 |
| 7. Reviews | Big number + stars + breakdown bar chart (via `core/html` L1) + reviews list | `surecart/product-review-average-rating-value` + `surecart/product-review-average-rating-stars` + `surecart/product-review-total-rating` + `surecart/product-review-list` | Custom summary + L1 chart |
| 8. FAQ | 6 `core/details` items, max-width 880px | none | P9 FAQ details list |
| 9. Subscribe & save | Sage-bg CTA banner with rounded 32px, centered headline + body + button + bullets | `surecart/product-buy-buttons` + `surecart/product-buy-button` (start subscription) | P6 CTA band |
| 10. Related products | section-head + `surecart/product-list-related` self-closed | `surecart/product-list-related` | Server-rendered |
| 11. Footer | 3-col `core/columns` (brand+social via `core/html` L1, nav links, newsletter form via `core/html` L1) + copyright row | none | Custom |
| 12. Sticky add-bar | `surecart/sticky-purchase` paired with selected-variant-image + title + price + buy-button | `surecart/sticky-purchase` + `surecart/product-selected-variant-image` + `surecart/product-title` + `surecart/product-selected-price-amount` + `surecart/product-buy-buttons` + `surecart/product-buy-button` | Production exemplar |

## v7.7 paste-test corrections demonstrated

### Correction 1 — No `aspect-ratio` on `core/group` (A-24, HC25)

The hero gallery has a main image area + 3 thumbnail squares. Initial emission tried `core/group` with `style.dimensions.aspectRatio:"1"` + `aspect-ratio:1` in inline mirror — `save()` rejected it. Fix: drop the aspect-ratio entirely and use explicit `padding-top:60px;padding-bottom:60px` on the 3 thumbnails to approximate square shape at `flexSize:"32%"`. The main hero media uses `padding:48px` all around since it contains `surecart/product-media` which renders the actual product image.

### Correction 2 — Zero-margin parity (A-25, HC26)

Initial hero `surecart/product-buy-buttons` emission had `style.spacing.margin:{top:"0",bottom:"0"}` in JSON but the rendered `<div>` had no `style="..."`. Fix: drop zero margins from JSON since they're no-op anyway. The `surecart/product-buy-buttons` opener is now `{"style":{"spacing":{"blockGap":"0"},"layout":{"selfStretch":"fill"}}}` — JSON only has properties that need to be there.

### Correction 3 — `core/cover` gradient fallback to `core/group` solid-bg (A-26, HC27)

The Before/After section originally used 2× `core/cover` with `customGradient`. The class-set drift triggered recovery. Fix: replace with 2× `core/group` solid-bg halves using the gradient's first stop color (`#EDE4DA` for Before, `#FCE8DC` for After). Drop+log the gradient with `merchant_action` recommending CSS via theme stylesheet.

## Conventions demonstrated (read as HOW reference)

- **Outer wrapper:** `surecart/product-page` with `align:"full"` and `layout:{type:"constrained",contentSize:"1240px"}` — wider than Aurora's 1200px because the design's container is 1240px.
- **No HTML comments inside the wrapper.** (A-21)
- **Each section wraps an `align:"full"` `core/group` with literal bg + section padding + nested constrained inner group with `contentSize:"1240px"`** — except FAQ (880px) and Subscribe banner (640px). The pattern is uniform across 12 sections.
- **Subscription price chooser (hero):** the `surecart/product-price-chooser` with a single `product-price-choice-template` child iterates server-side over the product's prices. Configure both a one-time price ($48) and a subscription price ($42/month) on the SureCart product admin; the chooser renders both as radio options automatically. The hardcoded $48 / $42 / "Save 15%" in the design becomes a `dropped_features.hardcoded_data` entry — the server handles it.
- **Variant chips (hero):** `surecart/product-variant-pills` paired with one `surecart/product-variant-pill` carrying `highlight_text/highlight_background/highlight_border` chrome attrs for the active state. Configure a "Strength" variant on the product with 3 options.
- **Benefits strip with right-border dividers:** 4× `core/group` flex with `border:{right:{...}}` per-side (no `has-border-color` class for per-side borders — A-20 only applies to top-level `style.border.color`).
- **Numbered step circles (How to Use):** rendered via `core/html` L1 inline `<div style="...">N</div>` — pastel-bg circular elements with serif numerals. L1-safe (no `data-wp-*` directives).
- **Breakdown bar chart (Reviews):** rendered as one `core/html` L1 block with grid layout + 5 rows of (label / track / fill / pct). Drop+log under `raw_html_static`.
- **Footer newsletter form:** `<form onsubmit="return false;">` inside `core/html` L1 — visual only, no submit handler. To capture real emails, merchant adds Brevo / Mailchimp / WP Forms block.
- **Social icons row (Footer):** 4 circular `<a>` elements with SVG icons inside one `core/html` L1 block.
- **`surecart/sticky-purchase`:** uses the production sticky-purchase exemplar verbatim — single `surecart/product-buy-button` (no Buy Now pair). The "appears on scroll" behavior in the design is dropped+logged with merchant_action recommending CSS for scroll-triggered reveal.

## Cross-references

- Hard Constraint #25 (no `aspect-ratio` on `core/group`) — SKILL.md
- Hard Constraint #26 (zero-value spacing JSON↔inline parity) — SKILL.md
- Hard Constraint #27 (`core/cover` gradient class set + `core/group` fallback) — SKILL.md
- Tier A items A-24, A-25, A-26 — `rubric/self-validate.md`
- Exemplar 8 Variant B (core/cover gradient) — `reference/core-blocks-cheatsheet.md`
- Aurora Lamp gold exemplar (v7.6) — `examples/patterns/aurora-lamp.example.md`
