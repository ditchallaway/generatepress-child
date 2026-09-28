# `surecart-sticky-purchase` — Sticky Purchase

**Source:** `~/Desktop/patterns/sticky-purchase.php` — SureCart plugin block-patterns library
**Block type:** `surecart/sticky-purchase`
**Category:** `surecart_sticky_purchase`
**Priority:** `3`

> **Upstream JSON fix applied.** The source `.php` had a malformed outer attribute (`{""metadata":...,layout":...`) on line 10 — extra quote after `{` and missing quote before `layout`. This exemplar emits the corrected JSON. File the upstream bug separately if not already tracked.

## Use case

Persistent horizontal purchase bar that stays visible at the bottom (or top) of the product page as the shopper scrolls. Shows variant image, title, current variant, price stack, and a single Add-to-Cart button on the right.

## Conventions demonstrated

- **Outer wrapper:** `surecart/sticky-purchase` carries the layout config directly (flex / horizontal / nowrap / space-between, full wide). No `<div>` wrapper (server-rendered).
- **Two-zone flex:** Left zone = product summary (image + stacked title/variant/price); right zone = buy buttons. Uses `flex-grow` via `selfStretch:"fit"` (left) and `selfStretch:"fill"` (right) to push the CTA right.
- **Vertical-center alignment via className:** `className:"is-vertically-aligned-center"` on the inner groups (this is the explicit class name; the wrapper's class string MUST include `is-layout-flex is-vertically-aligned-center`).
- **`product-title` level 4:** Heading level 4 (smaller than 32px hero h2). Carried in JSON as `level:4`.
- **Single buy button:** Only one `product-buy-button` (text: "Add"). No "Buy Now" pair. Wrapper class set still required: `wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex`.

## Markup (paste-ready)

```html
<!-- wp:surecart/sticky-purchase {"metadata":{"categories":["surecart_sticky_purchase"],"patternName":"surecart-sticky-purchase","name":"Sticky Purchase"},"layout":{"type":"flex","orientation":"horizontal","verticalAlignment":"top","flexWrap":"nowrap","wideSize":"full","justifyContent":"space-between"}} -->
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group">
	<!-- wp:group {"className":"is-vertically-aligned-center","style":{"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
	<div class="wp-block-group is-layout-flex is-vertically-aligned-center">
		<!-- wp:surecart/product-selected-variant-image {"style":{"layout":{"selfStretch":"fit","flexSize":null}}} /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"4px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:surecart/product-title {"level":4,"style":{"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"700"},"layout":{"selfStretch":"fill","flexSize":null}}} /-->

			<!-- wp:surecart/product-selected-variant {"style":{"typography":{"fontSize":"16px"}}} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
			<div class="wp-block-group">
				<!-- wp:surecart/product-selected-price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontSize":"16px","lineHeight":"1.5"}}} /-->

				<!-- wp:surecart/product-selected-price-amount {"style":{"typography":{"fontSize":"16px","lineHeight":"1.5"}}} /-->

				<!-- wp:surecart/product-selected-price-interval {"style":{"typography":{"lineHeight":"1.5","fontSize":"16px"}}} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"is-layout-flex is-vertically-aligned-center","style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
	<div class="wp-block-group is-layout-flex is-vertically-aligned-center">
		<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"5px"}}} -->
		<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
			<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add"} /-->
		</div>
		<!-- /wp:surecart/product-buy-buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- /wp:surecart/sticky-purchase -->
```
