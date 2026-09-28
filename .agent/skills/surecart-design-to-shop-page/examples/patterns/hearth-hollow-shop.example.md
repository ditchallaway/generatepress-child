# `hearth-hollow-shop` — Sidebar shop with hero banner + footer (v0.3.0 first gold reference)

**Source:** Hearth & Hollow Shop design (Claude Design export, 2026-05-19 — Path A zip with `shop-page.jsx` + `shop.css` + `product-art.jsx` + `design-canvas.jsx`). Warm cream / olive / terracotta palette, Cormorant Garamond display serif, sticky 225px filter sidebar + 3-up product grid, hero `core/cover` banner above the grid, 4-column footer with newsletter signup below.

**Block type:** `surecart/product-list` (outer wrapper) + sibling `core/cover` (banner above) + sibling `core/group` (footer below)
**Pattern name:** `hearth-hollow-shop`
**Output file:** [`hearth-hollow-shop.example.html`](./hearth-hollow-shop.example.html) (~23 KB, 158 lines, ~52 blocks)
**Paste-test status:** clean (zero "Attempt block recovery" prompts, zero "Start Basic" picker UIs, zero `Block validation failed` errors), 2026-05-19, after round-2 corrections.

## Why this exemplar exists

This is the **first paste-tested gold reference for the shop-page skill** — analogous to `aurora-lamp.example.html` for the sibling product-page skill. The H&H paste-test cycle (v0.1.0 → v0.2.0 → v0.3.0) surfaced three production failure modes that retroactively corrected the v0.1.0 scaffolding:

1. **`is-position-<v>-<h>` class on `core/cover` with `contentPosition`** (v0.2.0, SHC#8 / A-8). When `contentPosition:"center left"` or `"bottom right"` is set in attrs, the rendered `<div class="wp-block-cover ...">` MUST include the matching `is-position-center-left` / `is-position-bottom-right` class plus `has-custom-content-position`. Missing the class → cascade-fail through every ancestor block.

2. **`has-border-color` class for top-level `style.border.color` literal** (v0.2.0, SHC#9 / A-9). When `style.border.color:"#RRGGBB"` is set top-level on `core/cover` / `core/group`, the rendered `<div>` MUST include `has-border-color`. Per-side colors (`border.top.color`, etc.) do NOT — only inline `border-<side>-color:hex;` style.

3. **NO descriptive HTML comments anywhere inside a block content area** (v0.3.0, SHC#10 / A-10). Raw section labels (`<!-- Header row -->`, `<!-- Card chrome -->`, etc.) interleaved between block delimiters treat as freeform content during parse and **silently drop the inner blocks** for `apiVersion:3` strict-schema blocks. Same family as sibling skill's HC#22.

## Structure (3 top-level blocks)

| # | Block | Role | Notes |
|---|---|---|---|
| 1 | `core/cover` `align:"full"` | Collection banner above the grid | 360px min-height, custom-gradient `linear-gradient(120deg,#6B4A36 0%,...,#C77E55 100%)` with `dimRatio:40` black overlay, `contentPosition:"center left"` (→ `is-position-center-left` class required). Inner: constrained `core/group` with eyebrow paragraph + h1 (Cormorant) + sub paragraph. |
| 2 | `surecart/product-list` `align:"wide"` | The shop pattern itself | `metadata.categories:["surecart_shop"]`, `query.perPage:9`. Sidebar variant via `surecart/product-list-content` flex split. |
| 3 | `core/group` `align:"full"` | Site footer (page-level chrome below the product-list) | 4-col `core/columns` (brand / Shop / About / newsletter) + bottom row with copyright. Newsletter form via L1 `core/html` (no native SureCart newsletter block). |

## Inside `surecart/product-list` (top-down)

1. **Header row** (`core/group` flex space-between, bottom border per-side):
   - Left flex group: `surecart/product-list-sort` + `surecart/product-list-filter` (both self-closing).
   - Right: `surecart/product-list-search` with `flexSize:"250px"`.

2. **Applied chips row** (`core/group` flex wrap):
   - `surecart/product-list-filter-tags` paired with `-filter-tags-template` + `-filter-tag` + `-filter-tags-clear-all`.

3. **`surecart/product-list-content`** (flex split, `flexWrap:"nowrap"`, `verticalAlignment:"top"`):
   - **`surecart/product-list-sidebar`** (sticky, `flexSize:"225px"`, `top:0px`):
     - `surecart/product-list-filter-checkboxes` paired with `-checkboxes-label` (text "Material") + `-checkboxes-template` + `-filter-checkbox`.
     - Decorative serif quote: `core/group` with `has-border-color has-background` + Cormorant italic paragraph.
   - **`surecart/product-template-container`** (`selfStretch:"fill"`):
     - **`surecart/product-template`** (`layout.type:"grid",columnCount:3`, `blockGap:"32px"`):
       - Outer card: `core/group` `layout.type:"default"`.
       - Card chrome: `core/group` `layout.type:"constrained"` with `border.radius:"10px"`.
       - **`core/cover`** with `useFeaturedImage:true`, `aspectRatio:"1"` (square), `contentPosition:"bottom right"` (→ `is-position-bottom-right`), top-level `border.color:"#E6DFD2"` (→ `has-border-color`), `color.background:"#FBF7F0"` (→ `has-background`).
       - Inside cover's `__inner-container`: flex `core/group` with `surecart/product-sale-badge` (left, olive bg + cream text) + `surecart/product-quick-view-button` (right, cream pill with olive 1px border) — **emit with `className:"is-style-show-on-hover"` per SHC#12**: inside `product-template` cards, the quick-view button MUST hover-reveal, not be always-visible.
       - **`surecart/product-title`** with `level:2`, Cormorant 20px, olive text.
       - `surecart/product-review-average-rating-stars` with terracotta `#C76A4A`.
       - Price row: flex `core/group` with `surecart/product-list-price` (terracotta when on sale) + `surecart/product-scratch-price` (warm-grey strike-through).
     - **`surecart/product-list-no-products`** paired with constrained `core/group` containing h2 (Cormorant) + paragraph fallback.

4. **`surecart/product-pagination`** (full triad — `-previous` + `-numbers` + `-next`).

## Conventions demonstrated

- **Outer wrapper:** `surecart/product-list` (NOT `surecart/product-page` — that's the sibling skill's single-product wrapper). Sits BETWEEN a hero `core/cover` banner above and a `core/group` footer below; both above-and-below blocks are page-level siblings of the product-list, NOT inner children.
- **Sidebar variant:** all filter chrome that doesn't fit in the top header row goes inside `surecart/product-list-sidebar` (sticky, fixed flex width). The grid is wrapped in a sibling `surecart/product-template-container` with `selfStretch:"fill"`. Both sit inside `surecart/product-list-content` (the parent flex split).
- **Per-side asymmetric borders (header row, footer top):** `style.border.bottom.color` or `style.border.top.color`. Emits only inline `border-<side>-color:hex;border-<side>-width:Npx;` — NO `has-border-color` class. Distinct from top-level border.color.
- **Top-level border.color (sidebar quote group, per-card cover):** `style.border.color` literal hex. Emits `has-border-color` class on the wrapper `<div>` + inline `border-color:hex;border-width:Npx;border-radius:Npx;`.
- **Cover with `contentPosition`:** banner has `"center left"` → `is-position-center-left`; per-card cover has `"bottom right"` → `is-position-bottom-right`. Both also need `has-custom-content-position`.
- **NO descriptive HTML comments anywhere inside any block.** Only block delimiters (`<!-- wp:foo -->` / `<!-- /wp:foo -->` / `<!-- wp:foo /-->`).
- **D7 dual-emit / warm palette:** the warm cream/olive/terracotta palette does NOT overlap SureCart's base palette (`surecart-brand` green). All H&H colors are emitted as literal hex (`#F7F2EA`, `#2F3A24`, `#C76A4A`, `#E6DFD2`, etc.) — they do not snap to merchant palette swatches in the editor sidebar.
- **Cormorant Garamond display serif:** all serif text (banner h1, card titles, footer brand, sidebar quote, empty-state h2) uses literal `style.typography.fontFamily:"Cormorant Garamond, serif"` (25 chars, HC#41 Path B — no `fontFamily:""` slug attr alongside). Sans-serif body inherits theme default (no `fontFamily` attr).
- **Newsletter form via `core/html` L1:** the footer's email signup is a hand-coded `<form>` with inline `style="..."` — no native SureCart subscribe block exists. L1 fallback documented in `custom-html-fallback.md`.
- **Card grid uses `surecart/product-template` (NOT `core/columns`):** `layout.type:"grid",columnCount:3`. WP_Query iteration happens server-side inside the template; substituting `core/columns` drops all products.
- **Per-card `surecart/product-title` is `level:2`:** card has many titles per page, not one h1. The page h1 is on the banner above the product-list.
- **Quick-view button overrides:** Start-Basic default (`backgroundColor:"white",textColor:"black"`) replaced with literal hex (`color.background:"#F7F2EA",color.text:"#2F3A24"`, `elements.link.color.text:"#2F3A24"`, 1px olive border, 999px radius — matches the design's bottom-right pill shape).
- **Sale badge overrides:** Start-Basic default (`fontSize:"12px",border.radius:"100px"`) replaced with literal hex (`color.background:"#2F3A24",color.text:"#F7F2EA"`, `border.radius:"4px"` — sharper corners matching the design's small olive chip).
- **Pagination color overrides:** all 3 triad children carry `color.text:"#4A5538"` (warm olive-soft) instead of the default black, and `border.radius:"6px"` for hover-state rounded chip.

## Dropped features (logged in v0.3.0 drift report)

| Type | What | Merchant action |
|---|---|---|
| `site_chrome` | Site header (Shop/Collections/Journal/About nav + logo + cart icons) | Theme-controlled — add via the active theme's header template |
| `decoration` | Banner "37 Pieces · In stock" meta in bottom-right of hero | Add as positioned `core/paragraph` inside the cover if needed |
| `interaction_state` | Filter popover shown in expanded state (Category + Price checkboxes) | Interaction state only — the live block opens the popover on click, can't be "pre-opened" in markup |
| `no_filter_match` | Filter result-count text "Showing 1–9 of 37" | No SureCart block for query count display — requires `/surecart-new-block` |
| `no_filter_match` | Sidebar color-swatch filter (12 color circles) | `product-list-filter-checkboxes` is taxonomy-driven only; color-swatch UI needs `/surecart-new-block` |
| `no_filter_match` | Sidebar "In stock only" + "Ready to ship" toggle | No availability-toggle filter block — needs `/surecart-new-block` |
| `no_per_card_block` | Per-card category eyebrow tag ("CANDLES", "CERAMICS") | No per-card collection-tag block — drop or add via custom block |
| `no_per_card_block` | Per-card heart/favourite button (top-right of cover, hover-reveal) | No per-card wishlist block — drop or add via `/surecart-new-block` |
| `no_per_card_block` | Per-card variant color chips (3-4 dots + "+N" overflow) | `surecart/product-variant-pills` is single-product context only; per-card chips need `/surecart-new-block` |
| `no_per_card_block` | Per-card hover state (lift + shadow + button reveals) | Theme/CSS concern — add CSS via Customizer or custom block style |
| `unsupported_style` | Custom border + background on `product-list-sort`/`-filter`/`-search` controls | Those blocks' `supports` declare typography + spacing + color.text only — chrome inherits theme defaults |
| `custom_chrome` | Empty-state suggestion tags ("Bestsellers / Under $50 / New this week") | No SureCart quick-filter-suggestion block — emit as `core/buttons` inside `product-list-no-products` if desired |

## How to use

When the merchant's design is a sidebar shop with a hero banner above and a 4-col footer below, this exemplar is the closest compositional reference. Read it as a HOW reference for class/style emission, NOT a WHAT template — compose your own primitives that match the actual design.

When in doubt about a specific paste-recovery error message, grep this file for the relevant class or attribute pattern. The file is byte-equivalent to `save()` output for every block and passes all 10 Tier A rubric items + all 4 Tier B items.

## Cross-references

- **Hard Constraint SHC#8** (`is-position-<v>-<h>` for cover with contentPosition) — `SKILL.md`
- **Hard Constraint SHC#9** (`has-border-color` for top-level border.color literal) — `SKILL.md`
- **Hard Constraint SHC#10** (no descriptive HTML comments inside block content areas) — `SKILL.md`
- **Tier A items A-8, A-9, A-10** — `rubric/self-validate.md`
- **Sibling exemplar `list-sidebar.example.md`** — the production sidebar pattern; H&H builds on its structure and adds the hero-above + footer-below pattern.
- **Sibling skill HC#22** (no HTML comments in apiVersion-3 blocks, sibling skill v7.6.0) — the original discovery; SHC#10 restates it for shop-page emission.
