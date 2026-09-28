# `surecart/product-list` Start-Basic Canonical Template

**Source:** Paste-verified against `packages/blocks-next/src/blocks/product-list/template.js` (lines 3-87) AND the production block-pattern at `app/src/BlockLibrary/ProductListMigrationService.php:99-115`. Same shape as the canonical `surecart/product-list-related` Start-Basic template documented in `../../surecart-design-to-blocks/reference/product-page-blocks.md:455-487`.

## Why this template is mandatory

Emitting `<!-- wp:surecart/product-list /-->` self-closed (or with a partial inner template) renders a **"Start Basic / Select template"** placeholder picker in the WordPress block editor — NOT the products grid. The placeholder is a setup widget the merchant must manually click "Start Basic" on. The failure is silent: no `Block validation failed` error, no recovery prompt. The merchant just sees an unstyled chooser where the products should be.

The same failure mode applies to `surecart/product-review-list` (A-27 in the sibling skill) and `surecart/product-list-related` (A-28). All three share an editor-side `Placeholder` component that activates when the block's inner blocks don't match the expected template shape.

**Always emit the full Start-Basic template.** Customize the values inside; do not omit the structure.

## The canonical template (paste-ready, customize the marked spots)

**Important:** the template below shows the structure with descriptive section labels (`<!-- Header row: ... -->`) for human readability. **DO NOT copy those descriptive comments into emitted markup** — see SHC#10 in SKILL.md (any HTML comment that isn't a block delimiter `<!-- wp:.../-->` or `<!-- /wp:... -->` corrupts inner-block parsing for `apiVersion:3` blocks and silently drops the inner blocks during save()). The descriptive labels in the template below are LEARNING aids ONLY. Strip them before emit.

```html
<!-- wp:surecart/product-list {"limit":null,"query":{"perPage":9,"pages":0,"offset":0,"postType":"sc_product","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"include":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"metadata":{"categories":["surecart_shop"],"patternName":"<your-slug>","name":"<Your Title>"},"align":"wide","style":{"spacing":{"blockGap":"10px","margin":{"left":"0px","right":"0px"}}}} -->
<!-- [SECTION-LABEL — STRIP BEFORE EMIT] Header row: sort + filter on the left, search on the right -->
<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"10px"},"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="margin-bottom:10px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-list-sort /-->
<!-- wp:surecart/product-list-filter /--></div>
<!-- /wp:group -->

<!-- wp:surecart/product-list-search {"style":{"layout":{"selfStretch":"fixed","flexSize":"250px"}}} /--></div>
<!-- /wp:group -->

<!-- [SECTION-LABEL — STRIP BEFORE EMIT] Applied filter chips row -->
<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"10px"},"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:10px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-list-filter-tags {"layout":{"type":"flex","orientation":"vertical"}} -->
<!-- wp:surecart/product-list-filter-tags-template -->
<!-- wp:surecart/product-list-filter-tag /-->
<!-- /wp:surecart/product-list-filter-tags-template -->
<!-- /wp:surecart/product-list-filter-tags --></div>
<!-- /wp:group -->

<!-- [SECTION-LABEL — STRIP BEFORE EMIT] Product grid: customize columnCount or use minimumColumnWidth for responsive -->
<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"grid","columnCount":null,"minimumColumnWidth":"225px"}} -->
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"color":{"background":"#0000000d"},"border":{"radius":"10px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;background-color:#0000000d;margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top center","style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover has-custom-content-position is-position-top-center" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:surecart/product-quick-view-button {"className":"is-style-show-on-hover","style":{"spacing":{"padding":{"top":"10px","bottom":"10px","left":"10px","right":"10px"}},"typography":{"fontSize":"12px","lineHeight":"1"},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"border":{"radius":"100px"}},"backgroundColor":"white","textColor":"black"} /-->
<!-- wp:surecart/product-sale-badge {"style":{"typography":{"fontSize":"12px"},"border":{"radius":"100px"},"layout":{"selfStretch":"fit","flexSize":null}}} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"bottom":"5px","top":"0px"}}}} /-->

<!-- wp:surecart/product-review-average-rating-stars {"style":{"spacing":{"margin":{"top":"10px","bottom":"8px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"}},"margin":{"top":"0px","bottom":"0px"},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;line-height:1"><!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /-->
<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-template -->

<!-- [SECTION-LABEL — STRIP BEFORE EMIT] Pagination triad -->
<!-- wp:surecart/product-pagination {"style":{"elements":{"link":{"color":{"text":"#000000"}}},"spacing":{"padding":{"top":"15px","bottom":"15px"}}},"textColor":"black"} -->
<!-- wp:surecart/product-pagination-previous /-->
<!-- wp:surecart/product-pagination-numbers /-->
<!-- wp:surecart/product-pagination-next /-->
<!-- /wp:surecart/product-pagination -->

<!-- [SECTION-LABEL — STRIP BEFORE EMIT] Empty-state fallback: ALWAYS pair with a core/paragraph -->
<!-- wp:surecart/product-list-no-products -->
<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when a query returns no products."} -->
<p class="has-text-align-center">No products found.</p>
<!-- /wp:paragraph -->
<!-- /wp:surecart/product-list-no-products -->
<!-- /wp:surecart/product-list -->
```

> **Reminder before emit:** strip every `<!-- [SECTION-LABEL — STRIP BEFORE EMIT] ... -->` line from the template above. Leaving any of them in the emitted markup will silently drop the immediately-following block's children during parse (HC#10 / SHC#10).

## Customization points

| What to change per design | Where |
|---|---|
| Page title (pattern name) | `metadata.name` on `surecart/product-list` |
| Pattern slug | `metadata.patternName` on `surecart/product-list` |
| Products per page | `query.perPage` on `surecart/product-list` |
| Sort order | `query.order` (`asc`/`desc`) and `query.orderBy` (`date`/`title`/`menu_order`/`price`) |
| Container width | `align:"wide"` / `align:"full"` / omit for constrained |
| Grid columns (fixed) | `product-template.layout.columnCount` (e.g., `3`, `4`) |
| Grid columns (responsive) | `product-template.layout.columnCount:null,minimumColumnWidth:"Npx"` |
| Card gap | `product-template.style.spacing.blockGap` |
| Card background | inner `core/group` chrome — `style.color.background` |
| Card border | inner `core/group` chrome — `style.border.radius` / `style.border.width` / `style.border.color` |
| Cover aspect ratio | `core/cover.style.dimensions.aspectRatio` (e.g., `"3/4"`, `"1"`, `"4/3"`) |
| Cover focal point | `core/cover.focalPoint` (e.g., `{"x":0.5,"y":0.3}` for top-biased) |
| Quick-view button colors | `product-quick-view-button.backgroundColor`/`textColor` slugs OR literal in `style.elements.link.color` |
| Sale badge colors | `product-sale-badge.style.color.*` |
| Title size/weight | `product-title.style.typography.*` |
| Price size/weight | `product-list-price.style.typography.*` and `product-scratch-price.style.typography.*` |
| Pagination color | `product-pagination.textColor` slug + `style.elements.link.color.text` |
| Pagination padding | `product-pagination.style.spacing.padding` |
| Empty-state message | `<p class="has-text-align-center">…</p>` text inside `product-list-no-products` |

## Variants

### Variant A — Sidebar layout (sticky filter column + scrolling grid)

Wrap the entire template in a top-level `core/group` flex split. Place filter blocks inside `surecart/product-list-sidebar` (sticky, fixed width). The `surecart/product-template` and pagination go in a sibling group with `selfStretch:"fill"`.

```html
<!-- wp:surecart/product-list {"limit":null,"query":{...},"align":"wide"} -->
  <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
  <div class="wp-block-group">
    <!-- [SECTION-LABEL — STRIP BEFORE EMIT] Sidebar with filters -->
    <!-- wp:surecart/product-list-sidebar {"label":"Filters","style":{"layout":{"selfStretch":"fixed","flexSize":"225px"},"position":{"type":"sticky","top":"0px"},"spacing":{"blockGap":"30px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
      <!-- wp:surecart/product-list-filter-checkboxes -->
      <!-- wp:surecart/product-list-filter-checkboxes-label /-->
      <!-- wp:surecart/product-list-filter-checkboxes-template -->
      <!-- wp:surecart/product-list-filter-checkbox /-->
      <!-- /wp:surecart/product-list-filter-checkboxes-template -->
      <!-- /wp:surecart/product-list-filter-checkboxes -->
    <!-- /wp:surecart/product-list-sidebar -->

    <!-- [SECTION-LABEL — STRIP BEFORE EMIT] Main column: grid + pagination -->
    <!-- wp:group {"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group">
      <!-- [SECTION-LABEL — STRIP BEFORE EMIT] header row with sort + search -->
      <!-- [SECTION-LABEL — STRIP BEFORE EMIT] product-template grid -->
      <!-- [SECTION-LABEL — STRIP BEFORE EMIT] product-pagination -->
      <!-- [SECTION-LABEL — STRIP BEFORE EMIT] product-list-no-products -->
    </div>
    <!-- /wp:group -->
  </div>
  <!-- /wp:group -->
<!-- /wp:surecart/product-list -->
```

### Variant B — Load-more pagination

Replace the pagination triad with a single `-next` child wrapped in a flex-centered parent:

```html
<!-- wp:surecart/product-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"padding":{"top":"30px","bottom":"30px"}}}} -->
<!-- wp:surecart/product-pagination-next {"label":"Load more"} /-->
<!-- /wp:surecart/product-pagination -->
```

### Variant C — Radio-button sort

Replace `<!-- wp:surecart/product-list-sort /-->` with the radio-group template:

```html
<!-- wp:surecart/product-list-sort-radio-group -->
<!-- wp:surecart/product-list-sort-radio-group-label /-->
<!-- wp:surecart/product-list-sort-radio-group-template -->
<!-- wp:surecart/product-list-sort-radio /-->
<!-- /wp:surecart/product-list-sort-radio-group-template -->
<!-- /wp:surecart/product-list-sort-radio-group -->
```

## Source verification

Compare your customized template against:

1. `packages/blocks-next/src/blocks/product-list/template.js` — the editor's TEMPLATE constant. This is what the "Start Basic" picker inserts when the merchant clicks it manually. Your emitted markup must produce the same block-tree shape.
2. `app/src/BlockLibrary/ProductListMigrationService.php:99-115` — the production migration output (legacy `product-collection-page` → next-gen `product-list`). Real, paste-verified shop-page markup.
3. `examples/patterns/list-standard.example.md` — the corresponding `~/Desktop/patterns/list-standard.php` exemplar (the SureCart plugin's own pattern library), which expresses this template with paste-verified attribute defaults.

Any divergence from these three is a bug — the editor will show the "Start Basic" picker.
