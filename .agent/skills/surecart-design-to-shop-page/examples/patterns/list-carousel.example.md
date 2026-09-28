# `surecart-list-carousel` — Classic Product Carousel

**Source:** `~/Desktop/patterns/list-carousel.php` — SureCart plugin block-patterns library
**Block type:** `surecart/product-list`
**Category:** `surecart_shop`
**Priority:** `1`

## Use case

Simple 3-column product list with title heading + pagination prev / next buttons positioned in the header. Card has cover (featured image) + sale badge + title + price row. Suitable for "Featured products" or "Latest arrivals" carousel-style sections.

## Conventions demonstrated

- `surecart/product-template` with `layout:{type:'grid',columnCount:'3'}` — fixed 3-column grid (not auto-fit).
- Header row holds heading + pagination prev / next as inline buttons (carousel-style nav controls).
- Card chrome: `core/cover` with `useFeaturedImage:true`, rounded corners, sale-badge overlay; title + price stacked below in a centered group.
- Light-bg card with subtle background tint (`#0000000d`) and `10px` border radius.
- Pagination uses `surecart/product-pagination-previous` and `surecart/product-pagination-next` only (no numbers) — keeps the header compact and carousel-feeling.

## Markup (paste-ready)

```html
<!-- wp:surecart/product-list {"query":{"perPage":3,"pages":0,"offset":0,"postType":"sc_product","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"include":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"metadata":{"categories":["surecart_shop"],"patternName":"surecart-list-carousel","name":"Classic Product Carousel"},"align":"wide","style":{"spacing":{"blockGap":"24px"}}} -->
<!-- wp:group {"style":{"position":{"type":""},"spacing":{"margin":{"bottom":"24px"},"padding":{"right":"0","left":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","orientation":"horizontal","verticalAlignment":"bottom"}} -->
<div class="wp-block-group" style="margin-bottom:24px;padding-right:0;padding-left:0"><!-- wp:heading {"style":{"typography":{"fontSize":"36px"}}} -->
<h2 class="wp-block-heading" style="font-size:36px">Shop Bestsellers</h2>
<!-- /wp:heading -->

<!-- wp:surecart/product-pagination {"showLabel":false,"style":{"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"blockGap":"18px"}},"layout":{"type":"flex","justifyContent":"space-between","verticalAlignment":"center","flexWrap":"nowrap"}} -->
<!-- wp:surecart/product-pagination-previous {"style":{"spacing":{"padding":{"top":"18px","bottom":"18px","left":"18px","right":"18px"}},"border":{"radius":"100px","color":"#4c4c4cd6","width":"1px"},"typography":{"lineHeight":"1"},"color":{"text":"#4c4c4cd6"},"elements":{"link":{"color":{"text":"#4c4c4cd6"}}}}} /-->

<!-- wp:surecart/product-pagination-next {"style":{"spacing":{"padding":{"top":"18px","bottom":"18px","left":"18px","right":"18px"}},"border":{"radius":"100px","color":"#4c4c4cd6","width":"1px"},"typography":{"lineHeight":"1"},"color":{"text":"#4c4c4cd6"},"elements":{"link":{"color":{"text":"#4c4c4cd6"}}}}} /-->
<!-- /wp:surecart/product-pagination --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"grid","columnCount":"3"}} -->
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"color":{"background":"#0000000d"},"spacing":{"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"}},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;background-color:#0000000d;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top right","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-right" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:surecart/product-sale-badge {"style":{"typography":{"fontSize":"12px"},"border":{"radius":"100px"}}} /--></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"bottom":"5px","top":"0px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"},"padding":{"right":"0","left":"0"}},"margin":{"top":"0px","bottom":"0px"},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-right:0;padding-left:0;line-height:1"><!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /-->

<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-template -->
<!-- wp:surecart/product-list-no-products -->
	<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when a query returns no products."} -->
		<p class="has-text-align-center">No products found.</p>
	<!-- /wp:paragraph -->
<!-- /wp:surecart/product-list-no-products -->
<!-- /wp:surecart/product-list -->
```
