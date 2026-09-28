# Shop-Page Blocks — Curated Vocabulary

Use ONLY these blocks (or `core/*`) when emitting shop-page markup. **Do not invent block names.** If the design needs something not in this list, search `../../surecart-design-to-blocks/reference/full-inventory.json` for an exact match, or fall back to `core/group` + `core/heading` + `core/paragraph` and log under "fallback" in the drift report.

Every `surecart/*` block listed below is `apiVersion: 3` server-rendered — but **most of them must be paired** with their canonical inner template, not self-closed. Self-closing the wrong block produces a silent "Start Basic" template-picker UI in the editor (no recovery prompt, no error — just an unstyled setup widget where products should be).

---

## Outer wrapper (mandatory, exactly one per shop page)

### `surecart/product-list`

The outer wrapper. Provides the product-list query context to all children. **Paired container — MUST emit the full Start-Basic inner template** (see SHC#2 in SKILL.md and the canonical template in `start-basic-template.md`).

**Source:** `packages/blocks-next/src/blocks/product-list/block.json` + `template.js`.

**Attributes:**

| Attr | Type | Default | Purpose |
|---|---|---|---|
| `ids` | array | `[]` | Explicit product IDs to render. Empty = use `query` |
| `type` | string | `"all"` | Source type (legacy compat) |
| `limit` | number | (none) | Hard cap on products rendered (overrides `query.perPage`) |
| `collection_id` | string | `""` | Filter by collection taxonomy ID |
| `query` | object | (see below) | WP_Query args |

**Default `query`:**

```json
{
  "perPage": 15,
  "pages": 0,
  "offset": 0,
  "postType": "sc_product",
  "order": "desc",
  "orderBy": "date",
  "author": "",
  "search": "",
  "exclude": [],
  "include": [],
  "sticky": "",
  "inherit": true,
  "taxQuery": null,
  "parents": []
}
```

`perPage` is the main knob to customize per design — count visible cards on page 1.

**`providesContext`** (do NOT alter — server-controlled):
- `query`
- `surecart/product-list/limit`
- `surecart/product-list/offset`
- `surecart/product-list/type`
- `surecart/product-list/ids`

**Self-close behavior:** silent failure. Emits a "Start Basic / Select template" picker widget in the editor. Never self-close. See `start-basic-template.md` for the canonical paired form.

**Metadata convention:**

```json
{"metadata":{"categories":["surecart_shop"],"patternName":"<slug>","name":"<title>"}}
```

`categories:["surecart_shop"]` is the SureCart pattern-library taxonomy for shop patterns — keeps the pattern discoverable in the editor's pattern inserter.

---

## Header / Filter chrome children (inside `surecart/product-list`)

All blocks in this section have `ancestor: ["surecart/product-list"]` in their `block.json` — they only render correctly nested inside a `surecart/product-list` parent.

### `surecart/product-list-sort`

Default sort dropdown (Newest / Price / Title …). Self-closing — has no merchant-authored inner template.

```html
<!-- wp:surecart/product-list-sort /-->
```

### `surecart/product-list-sort-radio-group`

Radio-button sort (alternative to dropdown). Paired container — emit the template repeater.

```html
<!-- wp:surecart/product-list-sort-radio-group -->
<!-- wp:surecart/product-list-sort-radio-group-label /-->
<!-- wp:surecart/product-list-sort-radio-group-template -->
<!-- wp:surecart/product-list-sort-radio /-->
<!-- /wp:surecart/product-list-sort-radio-group-template -->
<!-- /wp:surecart/product-list-sort-radio-group -->
```

The `-radio` child uses context `surecart/radio/name` (`usesContext` declaration); it iterates per sort option server-side.

### `surecart/product-list-filter`

Taxonomy-driven dropdown filter (default: `taxonomy:"sc_collection"`). Self-closing.

```html
<!-- wp:surecart/product-list-filter /-->
```

Attribute: `taxonomy` (string, default `"sc_collection"`). Switch to e.g. `"sc_tag"` to filter by product tags instead.

### `surecart/product-list-filter-checkboxes`

Multi-select filter checkbox group (e.g., "Color: ☐ Red ☐ Blue ☐ Green"). Paired container.

```html
<!-- wp:surecart/product-list-filter-checkboxes -->
<!-- wp:surecart/product-list-filter-checkboxes-label /-->
<!-- wp:surecart/product-list-filter-checkboxes-template -->
<!-- wp:surecart/product-list-filter-checkbox /-->
<!-- /wp:surecart/product-list-filter-checkboxes-template -->
<!-- /wp:surecart/product-list-filter-checkboxes -->
```

The `-checkbox` child uses context `surecart/checkbox/name`; it iterates per taxonomy term server-side.

### `surecart/product-list-filter-tags`

Applied-filters chip display (shows the active filter selections as removable chips). Paired container.

```html
<!-- wp:surecart/product-list-filter-tags {"layout":{"type":"flex","orientation":"vertical"}} -->
<!-- wp:surecart/product-list-filter-tags-label /-->
<!-- wp:surecart/product-list-filter-tags-template -->
<!-- wp:surecart/product-list-filter-tag /-->
<!-- /wp:surecart/product-list-filter-tags-template -->
<!-- wp:surecart/product-list-filter-tags-clear-all /-->
<!-- /wp:surecart/product-list-filter-tags -->
```

The `-filter-tag` child uses context `surecart/filterTag/name`. `-clear-all` is the "Clear all" link/button.

### `surecart/product-list-search`

Search input. Self-closing.

```html
<!-- wp:surecart/product-list-search {"style":{"layout":{"selfStretch":"fixed","flexSize":"250px"}}} /-->
```

Default `flexSize:"250px"` fixes the input width inside flex parents — match this in custom designs.

### `surecart/product-list-sidebar`

Collapsible sidebar wrapper for filters. Paired container.

```html
<!-- wp:surecart/product-list-sidebar {"label":"Filters","open":true,"style":{"layout":{"selfStretch":"fixed","flexSize":"225px"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<!-- filter children (filter-checkboxes, filter-tags, etc.) -->
<!-- /wp:surecart/product-list-sidebar -->
```

Attributes: `label` (string), `open` (boolean, default `true`). The sticky positioning + `flexSize:"225px"` is the typical chrome — adjust the width per design.

### `surecart/product-list-sidebar-toggle`

Hamburger/toggle button for collapsing the sidebar on mobile. Self-closing.

```html
<!-- wp:surecart/product-list-sidebar-toggle /-->
```

---

## Grid (the heart of the shop page)

### `surecart/product-template`

The grid container. Iterates products via WP_Query server-side; the inner blocks describe ONE card and are repeated per product.

```html
<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"grid","columnCount":4}} -->
<!-- ONE card's block tree — repeated server-side per product -->
<!-- /wp:surecart/product-template -->
```

**Layout variants:**

| Pattern | Layout JSON | When to use |
|---|---|---|
| Fixed column count | `{"type":"grid","columnCount":4}` | Design has exactly N columns at desktop |
| Responsive auto-fit | `{"type":"grid","columnCount":null,"minimumColumnWidth":"225px"}` | Design wants grid to reflow by card width |

**Per-card inner blocks** — see `start-basic-template.md` for the canonical card structure. The card typically contains:
- `core/group` with `layout.type:"default"` — outer card wrapper
  - `core/group` with `style.color.background` + `style.border` — card chrome (background, border, radius, padding)
    - `core/cover` with `useFeaturedImage:true`, `dimensions.aspectRatio:"3/4"`, `focalPoint:{x:0.5,y:0.5}` — product image
      - inner: `core/group` flex with `surecart/product-quick-view-button` + `surecart/product-sale-badge`
  - `surecart/product-title` with `level:2`
  - `surecart/product-review-average-rating-stars` (optional, when design shows star rating per card)
  - `core/group` flex with `surecart/product-list-price` + `surecart/product-scratch-price`

**Always `level:2` on per-card `product-title`.** Page-h1 (`level:1`) is reserved for the collection's overall title (which lives in a separate `core/heading` outside the `product-list` block).

### Per-card SureCart blocks

These appear inside `surecart/product-template` as part of the per-card inner template:

- **`surecart/product-quick-view-button`** — opens the quick-view modal. Self-closing. Sits inside the `core/cover` `__inner-container` typically.
- **`surecart/product-sale-badge`** — "Sale" / discount chip. Self-closing. Sits inside the cover typically (overlay on the image).
- **`surecart/product-title`** — product title (level:2 for cards). Self-closing.
- **`surecart/product-list-price`** — the active price (variant-selected). Self-closing.
- **`surecart/product-scratch-price`** — strike-through original price (when on sale). Self-closing.
- **`surecart/product-review-average-rating-stars`** — star rating display. Self-closing.

---

## Pagination

### `surecart/product-pagination`

Paginate the grid. Paired container with 3 children — emit all 3.

```html
<!-- wp:surecart/product-pagination -->
<!-- wp:surecart/product-pagination-previous /-->
<!-- wp:surecart/product-pagination-numbers /-->
<!-- wp:surecart/product-pagination-next /-->
<!-- /wp:surecart/product-pagination -->
```

**"Load more" variant** — design shows a single button instead of full pagination:

```html
<!-- wp:surecart/product-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:surecart/product-pagination-next {"label":"Load more"} /-->
<!-- /wp:surecart/product-pagination -->
```

Omit `-previous` and `-numbers` for the load-more pattern. Add the `label` attr to override the default "Next" text.

**Infinite-scroll variant** — drop+log under `dropped_features.type:"infinite_scroll"` with `merchant_action:"requires custom block — invoke /surecart-new-block to scaffold"`.

---

## Empty-state fallback

### `surecart/product-list-no-products`

Renders when the query returns zero products. Paired — emit a `core/paragraph` fallback.

```html
<!-- wp:surecart/product-list-no-products -->
<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when a query returns no products."} -->
<p class="has-text-align-center">No products found.</p>
<!-- /wp:paragraph -->
<!-- /wp:surecart/product-list-no-products -->
```

Self-closing renders an empty void when zero results — always emit the paragraph fallback.

---

## Collection tags (above-the-grid chrome)

### `surecart/product-collection-tags` + `surecart/product-collection-tag`

Collection chip row (often shown below the page title above the grid). Both `apiVersion:3` server-rendered — no inner `<div>` wrapper.

```html
<!-- wp:surecart/product-collection-tags -->
<!-- wp:surecart/product-collection-tag /-->
<!-- /wp:surecart/product-collection-tags -->
```

Sits OUTSIDE the `surecart/product-list` block (above or below it, depending on design).

---

## Layout (always `core/*`, never `surecart/*`)

For all layout primitives inside the shop page (the grid header, the sidebar split, the page-level wrapper), use WordPress core blocks. See `../../surecart-design-to-blocks/reference/core-blocks-cheatsheet.md` for the paired-block exemplars.

Quick reference:

| Need | Use |
|---|---|
| Section container with padding/background | `core/group` |
| Sidebar + main split | `core/group` with `layout.type:"flex",flexWrap:"nowrap"` and two children with `selfStretch:"fixed"`/`"fill"` |
| Filter row (sort/filter/search above grid) | `core/group` with `layout.type:"flex",justifyContent:"space-between"` |
| Page title (above the grid) | `core/heading {"level":1}` |
| Collection description | `core/paragraph` |
| Hero band above the grid | `core/cover` or `core/group` with `align:"full"` |

---

## Out of scope for the shop-page skill

These blocks are documented in the sibling `surecart-design-to-blocks` skill — refer there if the design crosses into single-product / cart / checkout territory:

- `surecart/product-page` — single-product detail page wrapper. Different skill.
- `surecart/product-price-chooser`, `surecart/product-variant-pills`, `surecart/product-quantity`, `surecart/product-buy-buttons`, `surecart/product-buy-button` — single-product hero blocks.
- `surecart/product-review-list`, `surecart/product-reviews`, `surecart/product-review-summary` — single-product review section.
- `surecart/product-list-related` — related-products section on a single-product page (NOT the same as `surecart/product-list`).
- `surecart/cart-icon`, `surecart/cart-count`, `surecart/slide-out-cart` — cart drawer chrome.
- `surecart/upsell` — post-purchase upsell.
- `surecart/sticky-purchase` — sticky add-to-cart bar.

If the merchant's design combines a shop-page grid with single-product chrome (e.g., a featured product hero above the grid), emit two outputs: the shop-page block from this skill and a hero block from the sibling skill, with a note explaining the split.
