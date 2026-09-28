# Self-Validation Rubric — Run BEFORE Emitting

Before you finalize the markup and respond to the merchant, mentally walk through the rubric below against your candidate output. **If any item fails, fix the markup and re-run.** Do not skip items — each one corresponds to a real production failure mode.

State the rubric result at the start of your response, then emit the markup. Format:

> Self-validation: Tier A 10/10, Tier B 5/5, Tier C skipped (input: zip).

The rubric below is shop-page-specific. All paste-safety rules from the sibling skill (`../../surecart-design-to-blocks/SKILL.md` Hard Constraints HC#1–HC#38) apply VERBATIM here — the `save()` and `validateBlock()` behavior is identical across all SureCart and WP core blocks. This rubric covers what's UNIQUE to shop pages on top of the sibling skill's foundation.

## Rubric tiers

The rubric is organized by **failure blast radius**:

- **Tier A — Blocking.** Paste-safety / silent-failure prevention. A failure here renders the page broken or empty on first paste. Fix and re-emit before responding. Items A-1 through A-10.
- **Tier B — Fidelity.** Page loads correctly but visually drifts from source design. Logged in drift report but does not block emission. Items B-1 through B-5.
- **Tier C — Screenshot-only.** Conditional checks that run ONLY when `input_type:"screenshot"`. Skipped on `zip`, `html_css`, `live_url`. Items C-1 through C-2.

---

## Tier A — Blocking

### A-1. Outer wrapper is `surecart/product-list`

- [ ] Output starts with `<!-- wp:surecart/product-list` and ends with `<!-- /wp:surecart/product-list -->`.
- [ ] Output does NOT wrap in `surecart/product-page` — that's the single-product context wrapper from the sibling skill.
- [ ] The wrapper carries `metadata.name`, `metadata.patternName`, and `metadata.categories` (typically `["surecart_shop"]`).
- [ ] Top-level `align:"wide"` or `align:"full"` is set when the design spans wider than the theme's default content width (most shop pages do).

**Failure if missing:** all `surecart/product-list-*` children fail their `ancestor:["surecart/product-list"]` constraint and don't register. Grid renders empty.

### A-2. `surecart/product-list` is paired with full Start-Basic inner template (mirrors A-28 in sibling skill)

- [ ] The block is NOT self-closed (`<!-- wp:surecart/product-list /-->` is FORBIDDEN).
- [ ] Inner blocks include ALL of: at least one filter/sort/search control in the header, exactly one `surecart/product-template` for the grid, one `surecart/product-pagination` with all 3 children, one `surecart/product-list-no-products` with a `core/paragraph` fallback.
- [ ] The structure follows `reference/start-basic-template.md` — variants allowed (sidebar / load-more / radio-sort), but the required sections are non-negotiable.

**Failure signature:** silent — editor shows "Start Basic / Select template" picker UI instead of the products grid. NO recovery prompt, NO block-validation error. Empirically verified: same failure mode as `surecart/product-review-list` and `surecart/product-list-related` (sibling skill A-27, A-28).

### A-3. `surecart/product-template` grid uses block layout, not `core/columns` / `core/group` flex

- [ ] The product grid is rendered by `surecart/product-template` with `layout.type:"grid"`.
- [ ] No `core/columns` or `core/group` flex is used as a substitute for the product-template grid.
- [ ] `columnCount` is set to a fixed N (when the design has fixed columns) OR `columnCount:null,minimumColumnWidth:"Npx"` (when the design wants responsive auto-fit).
- [ ] `style.spacing.blockGap` matches the design's card gap (literal px, not slug).

**Failure if missing:** WP_Query iteration happens in `product-template` server-side; substituting a static layout drops all products from the grid.

### A-4. Per-card `surecart/product-title` uses `level:2`

- [ ] Every `surecart/product-title` inside `surecart/product-template` has `level:2` in its JSON attrs.
- [ ] `level:1` is reserved for a page-level h1 OUTSIDE the `surecart/product-list` block (the collection's overall title).
- [ ] No card has multiple `surecart/product-title` siblings — exactly one per card.

**Why:** semantically a page has one h1, many h2s. A shop page with 12 cards × `level:1` produces 12 h1s — fails WCAG heading-structure checks AND breaks SEO heading hierarchy.

### A-5. `surecart/product-pagination` paired with 3 children (or load-more variant)

- [ ] Full-pagination variant: parent contains `surecart/product-pagination-previous`, `-numbers`, AND `-next` as inner blocks (all 3, all self-closed).
- [ ] Load-more variant: parent contains ONLY `surecart/product-pagination-next` with a `label` attr (typically `"Load more"`) and a `layout.justifyContent:"center"` on the parent.
- [ ] Parent is NEVER self-closed.

**Failure if self-closed:** "Start Basic" picker UI appears in place of the pagination control.

### A-6. `surecart/product-list-no-products` paired with `core/paragraph` fallback

- [ ] Inner blocks contain at least one `core/paragraph` (or other content block) with merchant-readable empty-state copy (e.g., "No products found.").
- [ ] Parent is NEVER self-closed.

**Failure if self-closed:** editor renders empty void when the query returns zero products; merchant has nothing to customize.

### A-7. Filter/sort/search blocks are inside `surecart/product-list` (ancestor constraint)

- [ ] Every `surecart/product-list-sort`, `surecart/product-list-sort-radio-group`, `surecart/product-list-filter`, `surecart/product-list-filter-checkboxes`, `surecart/product-list-filter-tags`, `surecart/product-list-search`, `surecart/product-list-sidebar` block is nested inside the top-level `surecart/product-list` parent.
- [ ] All `*-template` repeater children (e.g., `product-list-filter-tags-template`) are inside their respective parent block.
- [ ] All ancestor-locked children (`product-list-filter-checkbox`, `product-list-filter-tag`, `product-list-sort-radio`) are inside their respective `*-template` parent.

**Failure signature:** child blocks with `ancestor:[...]` constraints don't register at editor load when placed outside the constraint. They render as `block not registered` placeholders.

### A-8. `core/cover` with `contentPosition` includes `is-position-<v>-<h>` class on the wrapper `<div>`

(See SHC#8 in SKILL.md.) Walk every `<!-- wp:cover {...,"contentPosition":"X Y",...} -->` in the output:

- [ ] The wrapper `<div class="wp-block-cover ...">` includes the class `is-position-X-Y` (space replaced with hyphen).
- [ ] The wrapper `<div>` also includes `has-custom-content-position` (always required when `contentPosition` is anything other than the default `"center center"`).
- [ ] If `contentPosition` is the default or omitted, neither `is-position-*` nor `has-custom-content-position` is emitted.

**Failure signature:** `Block validation failed for 'core/cover'` with the diff showing the missing `is-position-X-Y`. Cascades to every ancestor block. Common values:

| `contentPosition` | Required class |
|---|---|
| `"top left"` | `is-position-top-left` |
| `"top center"` | `is-position-top-center` |
| `"top right"` | `is-position-top-right` |
| `"center left"` | `is-position-center-left` |
| `"center right"` | `is-position-center-right` |
| `"bottom left"` | `is-position-bottom-left` |
| `"bottom center"` | `is-position-bottom-center` |
| `"bottom right"` | `is-position-bottom-right` |

### A-9. Top-level `border.color` literal requires `has-border-color` class on the `<div>` (core/cover, core/group, similar)

(See SHC#9 in SKILL.md.) Walk every block with a `style.border.color` (literal hex):

- [ ] If `"border":{"color":"#RRGGBB",...}` is set TOP-LEVEL, the rendered `<div>` includes `has-border-color`.
- [ ] If `"border":{"<side>":{"color":"#RRGGBB",...}}` is set PER-SIDE (top/right/bottom/left), NO `has-border-color` class is emitted — only the inline `border-<side>-color` style.
- [ ] If `"border":{"radius":"Npx"}` is set WITHOUT a `color` key, NO `has-border-color` class is emitted.
- [ ] Same rule applies to companion classes: `has-background` (with literal `color.background`), `has-text-color` (with literal `color.text`), `has-link-color` (with `style.elements.link.color.text`).

**Failure signature:** `Block validation failed for 'core/group'` or `'core/cover'` with the diff showing the missing `has-border-color`. Cascades to every ancestor block (a single inner core/group failure can cascade through 5+ surecart/* wrappers and break the entire shop page).

**Quick rule:** if you wrote `border-color:#RRGGBB;` inline, you also need `has-border-color` in the class list.

### A-10. NO descriptive HTML comments inside any block content area

(See SHC#10 in SKILL.md.) Walk the emitted markup and check that EVERY HTML comment matches one of these three exact patterns:

- `<!-- wp:<name> -->` (block opener)
- `<!-- wp:<name> {<json attrs>} -->` (block opener with attrs)
- `<!-- wp:<name> {<json attrs>} /-->` (self-closing block)
- `<!-- wp:<name> /-->` (self-closing block, no attrs)
- `<!-- /wp:<name> -->` (block closer)

Any HTML comment that does NOT fit one of those patterns is forbidden:

- [ ] No section labels (`<!-- Header row -->`, `<!-- Pagination triad -->`).
- [ ] No editorial comments (`<!-- ONE card (server repeats per product) -->`, `<!-- Customize per design -->`).
- [ ] No TODO markers (`<!-- TODO: revisit -->`).
- [ ] No copy-paste hints from the reference templates (`<!-- Card chrome: ... -->`).

**Failure signature:** `Block validation failed` on the parent of the comment, with the diff showing save() outputs an EMPTY content area while the retrieved HTML contains the comment AND the original inner blocks. Cascades up the ancestor chain — one descriptive comment inside a per-card cover can brick the entire `surecart/product-list`.

**Quick rule:** before emit, search the markup for any HTML comment that doesn't start with `<!-- wp:` or `<!-- /wp:` — strip every match.

---

## Tier B — Fidelity

### B-1. Grid column count matches design

- [ ] If the design shows N fixed columns at desktop width, `product-template.layout.columnCount` is exactly N.
- [ ] If the design wants responsive reflow, `columnCount:null,minimumColumnWidth:"Npx"` is set where Npx is the smallest acceptable card width (typically 200–280px).
- [ ] At 1440px viewport, the rendered grid has the same column count as the design.

### B-2. Filter UI choice matches design

- [ ] Single "Filter ▾" dropdown in design → `surecart/product-list-filter` emitted (the default).
- [ ] Checkbox group in design → `surecart/product-list-filter-checkboxes` + `-checkbox` template emitted (no dropdown).
- [ ] Pill-style applied-filters chips → `surecart/product-list-filter-tags` + `-filter-tag` template emitted.
- [ ] Sticky filter sidebar → all filter blocks wrapped in `surecart/product-list-sidebar` with `position:sticky,top:0px`.
- [ ] Radio-button sort in design → `surecart/product-list-sort-radio-group` (not the dropdown).

### B-3. Per-card content matches design

Card content visible in the design has a corresponding inner block in the per-card template:

- [ ] Featured image → `core/cover` with `useFeaturedImage:true`. Aspect ratio matches (`3/4` for portrait product cards, `1` for square, `16/9` for landscape course cards, etc.).
- [ ] Sale badge → `surecart/product-sale-badge` inside cover's `__inner-container`.
- [ ] Quick-view button → `surecart/product-quick-view-button` inside cover's `__inner-container`.
- [ ] Product title → `surecart/product-title {"level":2}`.
- [ ] Star rating → `surecart/product-review-average-rating-stars`.
- [ ] Price → `surecart/product-list-price` + `surecart/product-scratch-price` in a flex `core/group` (the price+strikethrough pair, even when the design only shows one).
- [ ] If the design lacks one of these (e.g., no rating per card), omit the corresponding block AND log under `dropped_features` if the omission is intentional.

### B-4. Container width matches design

- [ ] `align:"full"` if the design spans 100% viewport width.
- [ ] `align:"wide"` if the design spans the theme's wide content width (typical for shop pages).
- [ ] Omit `align` if the design constrains to the theme's default content width.
- [ ] The wrapping `core/group` (if any, between page wrapper and `surecart/product-list`) has matching `layout.contentSize` when the design specifies a max-width.

### B-5. Prefer `core/icon` over inline-SVG `core/html` for built-in icon slugs (v0.4 — mirrors sibling B-28)

Mirrors B-28 in the sibling skill rubric. For every `core/html` block in the candidate output containing `<svg>` markup, check whether the glyph matches one of the 88 built-in `core/icon` slugs (full catalog at `../surecart-design-to-blocks/reference/wp-core-blocks.md § core/icon`).

- [ ] If a built-in slug matches → swap the `core/html` for `<!-- wp:icon {"icon":"core/{slug}"} /-->` (self-closing).
- [ ] If no slug matches but the glyph exists as a file URL → prefer `core/image` over `core/html`.
- [ ] `core/html` L1 inline SVG is acceptable ONLY when neither alternative applies AND the merchant has `unfiltered_html` capability.

**Most-common shop-page matches** to check:

| Glyph in design | Slug to emit |
|---|---|
| Search input prefix | `core/search` |
| Sort caret | `core/chevron-down` |
| Cart icon in nav | `core/cart` |
| Rating star | `core/star-filled` / `core/star-empty` / `core/star-half` |
| "Load more" / continue arrow | `core/arrow-right` |
| Filter-checkbox tick | `core/check` |
| Info tooltip | `core/info` |
| Menu / hamburger | `core/menu` |

**Greppable check:**

```
# Every <svg> inside core/html for a built-in-glyph match is a B-5 violation
grep -A5 "<!-- wp:html" emit.html | grep -E "(arrow|chevron|cart|check|plus|star|info|search|menu|shield|share)" | wc -l
# Expected: 0
```

**Note:** Tier B (fidelity), not Tier A (blocking) — the inline-SVG form still pastes successfully for users with `unfiltered_html` capability. The issue is loss of theme-override capability and downstream maintainability. Flag in drift report; don't block emission.

---

## Tier C — Screenshot-only (Mode B)

Active ONLY when `input_type:"screenshot"`. Skipped on zip / html_css / live_url.

### C-1. Card count plausibility

- [ ] Detected cards per row at desktop ≥ 2 (single-column shop pages exist but are unusual at desktop — confirm with the merchant if you detect 1-up).
- [ ] Detected cards on page 1 ≥ 4 (most shop-page designs show at least one full row plus partial second row at desktop).
- [ ] If card count seems implausibly low (< 4 visible), check for a hero band consuming the screenshot — the actual grid may be below the fold. Ask merchant for a taller screenshot.

### C-2. Filter UI detection confidence

- [ ] The filter UI shape (dropdown vs checkbox vs sidebar vs tags) is rated `high`/`medium`/`low` in the drift report's `confidence.filter_ui` field.
- [ ] If confidence is `low`, the drift report's `merchant_verify[]` includes "Verify filter UI: I chose <X> based on the screenshot; if the design intends <Y>, swap to the <Y> variant in `reference/start-basic-template.md`".
- [ ] Designs with no visible filter chrome (just sort + search) are emitted with the default `surecart/product-list-filter` (dropdown) — log under `dropped_features.type:"no_filter_visible"` if the merchant might want no filter at all.

---

## Drift report — shop-page-specific fields

In addition to the standard fields (`input_type`, `sections_detected`, `blocks_emitted`, `dropped_features`, `color_snap_misses`, `assets`, etc.) from the sibling skill, the shop-page drift report MUST include:

```json
{
  "outer_wrapper": "surecart/product-list",
  "grid_layout": {"type":"grid","columnCount":null,"minimumColumnWidth":"225px"},
  "filter_ui_chosen": "dropdown_plus_tags",
  "sort_ui_chosen": "dropdown",
  "sidebar_emitted": false,
  "pagination_variant": "full_triad",
  "per_card_blocks": [
    "core/cover (useFeaturedImage)",
    "surecart/product-quick-view-button",
    "surecart/product-sale-badge",
    "surecart/product-title (level:2)",
    "surecart/product-review-average-rating-stars",
    "surecart/product-list-price",
    "surecart/product-scratch-price"
  ],
  "query_per_page": 12,
  "above_grid_blocks": [
    "core/heading (level:1, page title)",
    "core/paragraph (collection description)"
  ]
}
```

`above_grid_blocks` lists any blocks emitted BEFORE the `surecart/product-list` block (page title, hero, collection description, breadcrumb chrome). These are NOT children of `surecart/product-list` — they're top-level siblings in the page.
