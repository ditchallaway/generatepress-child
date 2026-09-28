# `surecart-list-staggered` — Staggered Product List

**Source:** `~/Desktop/patterns/list-staggered.php` — SureCart plugin block-patterns library
**Block type:** `surecart/product-list`
**Category:** `surecart_shop`
**Priority:** `2`

## Use case

2-column staggered / asymmetric product layout. `core/spacer` blocks offset alternating items vertically so the grid reads as a magazine-style staggered cascade rather than a uniform grid.

## Conventions demonstrated

- 2-column `core/columns` instead of `surecart/product-template` grid layout — each column manually lists products.
- `core/spacer` blocks at the top of one column (e.g., `60px`–`100px`) shift its items down so the two columns read offset / staggered.
- Each product card is a `core/group` with `core/cover` (featured image) + sale badge overlay + title + price stacked below.
- Card aspect ratios may vary between columns to enhance the staggered reading rhythm.
- Designed for editorial / lookbook-style product showcases — typically 4–6 products per pattern instance.

## Markup (paste-ready)

```html
<!-- wp:columns {"metadata":{"categories":["surecart_shop"],"patternName":"surecart-list-staggered","name":"Staggered Product List"},"style":{"spacing":{"blockGap":{"left":"80px"},"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}}} -->
<div class="wp-block-columns" style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"position":{"type":""},"spacing":{"padding":{"right":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:0;padding-left:0"><!-- wp:surecart/product-list {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"include":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","style":{"spacing":{"blockGap":"10px"}}} -->
<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"default","columnCount":"1"}} -->
<!-- wp:group {"style":{"spacing":{"padding":{"right":"0","left":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-right:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"}},"color":{"background":"#0000000d"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#0000000d;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top left","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"},"padding":{"right":"0px","left":"0px","top":"30px","bottom":"30px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-left" style="margin-bottom:15px;padding-top:30px;padding-right:0px;padding-bottom:30px;padding-left:0px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:surecart/product-sale-badge {"style":{"typography":{"fontSize":"12px","textTransform":"uppercase","letterSpacing":"3px"},"border":{"radius":"0px"},"spacing":{"padding":{"top":"18px","bottom":"18px","left":"15px","right":"15px"}}}} /--></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-collection-tags {"style":{"spacing":{"margin":{"bottom":"5px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:surecart/product-collection-tag {"isLink":false,"style":{"color":{"background":"#ffffff00","text":"#0000009e"},"elements":{"link":{"color":{"text":"#0000009e"}}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase","fontSize":"12px","letterSpacing":"1.5px"}}} /-->
<!-- /wp:surecart/product-collection-tags -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"900","textAlign":"center","textTransform":"uppercase","letterSpacing":"1px"},"spacing":{"margin":{"bottom":"5px","top":"0px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"},"padding":{"right":"0","left":"0"}},"margin":{"top":"0px","bottom":"0px"},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-right:0;padding-left:0;line-height:1"><!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"top":"5px","bottom":"5px"}},"color":{"text":"#0000009e"},"elements":{"link":{"color":{"text":"#0000009e"}}}}} /-->

<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"80px","width":"0px","style":{"layout":[]}} -->
<div style="height:80px;width:0px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- /wp:surecart/product-template -->
<!-- wp:surecart/product-list-no-products -->
	<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when a query returns no products."} -->
		<p class="has-text-align-center">No products found.</p>
	<!-- /wp:paragraph -->
<!-- /wp:surecart/product-list-no-products -->
<!-- /wp:surecart/product-list --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"position":{"type":""},"spacing":{"padding":{"right":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:0;padding-left:0"><!-- wp:surecart/product-list {"query":{"perPage":3,"pages":0,"offset":3,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"include":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","style":{"spacing":{"blockGap":"10px"}}} -->
<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"default","columnCount":"1"}} -->
<!-- wp:spacer {"height":"80px","width":"0px","style":{"layout":[]}} -->
<div style="height:80px;width:0px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"color":{"background":"#0000000d"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#0000000d;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top left","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"},"padding":{"right":"0px","left":"0px","top":"30px","bottom":"30px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-left" style="margin-bottom:15px;padding-top:30px;padding-right:0px;padding-bottom:30px;padding-left:0px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:surecart/product-sale-badge {"style":{"typography":{"fontSize":"12px","textTransform":"uppercase","letterSpacing":"3px"},"border":{"radius":"0px"},"spacing":{"padding":{"top":"18px","bottom":"18px","left":"15px","right":"15px"}}}} /--></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-collection-tags {"style":{"spacing":{"margin":{"bottom":"5px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:surecart/product-collection-tag {"style":{"color":{"background":"#ffffff00","text":"#0000009e"},"elements":{"link":{"color":{"text":"#0000009e"}}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase","fontSize":"12px","letterSpacing":"1.5px"}}} /-->
<!-- /wp:surecart/product-collection-tags -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"900","textAlign":"center","textTransform":"uppercase","letterSpacing":"1px"},"spacing":{"margin":{"bottom":"5px","top":"0px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"}},"margin":{"top":"0px","bottom":"0px"},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;line-height:1"><!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"top":"5px","bottom":"5px"}},"color":{"text":"#0000009e"},"elements":{"link":{"color":{"text":"#0000009e"}}}}} /-->

<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-template -->
<!-- /wp:surecart/product-list --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
```
