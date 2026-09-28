# `surecart-product-standard` — Classic Product

**Source:** `~/Desktop/patterns/product-standard.php` — SureCart plugin block-patterns library
**Block type:** `surecart/product-page`
**Category:** `surecart_product_page`
**Priority:** `1`

## Use case

Canonical 2-column product page: media on the left, product info on the right (36% width). The default starting point for most physical/digital products.

## Conventions demonstrated

- **Outer wrapper:** `surecart/product-page` carries `metadata`, `align:"wide"`, and `layout:{type:"constrained"}`. No `<div>` wrapper (apiVersion 3, server-rendered).
- **Layout:** `core/columns` with `blockGap:{top:"30px",left:"60px"}` for column gutter. Right column has `width:"36%"` (carries `style="flex-basis:36%"`).
- **Price stack pattern:** scratch-amount → amount → interval → sale-badge in a flex-wrap row, with trial + fees on a separate flex-nowrap row below.
- **Price chooser pattern:** `product-price-choice-template` is the repeating template (emit ONCE; server iterates). Inner layout: 50/50 split — name left, price column right.
- **Buy-buttons wrapper:** `surecart/product-buy-buttons` has the explicit wrapper `<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">` — all 4 classes required.
- **Buy-button aliases:** `add_to_cart:true` for "Add To Cart", `add_to_cart` omitted (default false) + `className:"is-style-outline"` for "Buy Now".

## Mirror variant (v7.16 — folds in former `product-alternate.example.md`)

When the design has the info column on the LEFT and media on the RIGHT (reverse of the canonical), no separate exemplar is needed. Apply this single structural change to the markup below:

- Swap the order of the two `<!-- wp:column -->` blocks in the source.
- Keep widths and inner content identical. The 36% `width` attr moves with the INFO column wherever it sits in source order.
- Use the `metadata.patternName:"surecart-product-alternate"` and `name:"Classic Product (Alternate)"` to attribute the pattern correctly.

The same buy-button aliases, price-chooser, and price-stack conventions apply identically. The `product-alternate` exemplar was deprecated in v7.16 since it was a pure column-order mirror (per Expert #2 gold-audit recommendation).

## Markup (paste-ready)

```html
<!-- wp:surecart/product-page {"metadata":{"categories":["surecart_product_page"],"patternName":"surecart-product-standard","name":"Classic Product"},"align":"wide","layout":{"type":"constrained"}} -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"30px","left":"60px"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":""} -->
	<div class="wp-block-column"><!-- wp:surecart/product-media /--></div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"36%","style":{"spacing":{"blockGap":"0.75rem"}}} -->
	<div class="wp-block-column" style="flex-basis:36%"><!-- wp:surecart/product-collection-tags -->
		<!-- wp:surecart/product-collection-tag /-->
		<!-- /wp:surecart/product-collection-tags -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"10px","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
			<!-- wp:surecart/product-review-average-rating-stars /-->

			<!-- wp:surecart/product-review-total-rating {"style":{"spacing":{"blockGap":"4px"}}} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:surecart/product-title {"style":{"typography":{"fontSize":"32px"}}} /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"right":"0px","left":"0px"}}}} -->
		<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
			<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
				<!-- wp:surecart/product-selected-price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontSize":"24px","lineHeight":"1.5"},"color":{"text":"#686868"},"elements":{"link":{"color":{"text":"#686868"}}}}} /-->

				<!-- wp:surecart/product-selected-price-amount {"style":{"typography":{"fontSize":"24px","lineHeight":"1.5"}}} /-->

				<!-- wp:surecart/product-selected-price-interval {"style":{"typography":{"lineHeight":"2"}}} /-->

				<!-- wp:surecart/product-sale-badge {"style":{"border":{"radius":"15px"},"typography":{"fontSize":"12px","lineHeight":"2.1"},"layout":{"selfStretch":"fit","flexSize":null},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
			<div class="wp-block-group" style="padding-right:0px;padding-left:0px"><!-- wp:surecart/product-selected-price-trial /-->

				<!-- wp:surecart/product-selected-price-fees /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:surecart/product-description /-->

		<!-- wp:surecart/product-variant-pills -->
		<!-- wp:surecart/product-variant-pill /-->
		<!-- /wp:surecart/product-variant-pills -->

		<!-- wp:surecart/product-price-chooser -->
		<!-- wp:surecart/product-price-choice-template {"layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap","orientation":"horizontal"}} -->
		<!-- wp:surecart/price-name {"style":{"layout":{"selfStretch":"fixed","flexSize":"50%"},"typography":{"fontStyle":"normal","fontWeight":"600"}}} /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"0px","padding":{"right":"0px","left":"0px"}},"layout":{"selfStretch":"fixed","flexSize":"50%"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"right"}} -->
		<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
			<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
			<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
				<!-- wp:surecart/price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontStyle":"normal","fontWeight":"500"},"color":{"text":"#686868"}}} /-->

				<!-- wp:surecart/price-amount {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} /-->

				<!-- wp:surecart/price-interval {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:surecart/price-trial {"style":{"color":{"text":"#8a8a8a"},"elements":{"link":{"color":{"text":"#8a8a8a"}}}},"fontSize":"small"} /-->

			<!-- wp:surecart/price-setup-fee {"style":{"color":{"text":"#8a8a8a"},"elements":{"link":{"color":{"text":"#8a8a8a"}}}},"fontSize":"small"} /-->
		</div>
		<!-- /wp:group -->
		<!-- /wp:surecart/product-price-choice-template -->
		<!-- /wp:surecart/product-price-chooser -->

		<!-- wp:surecart/product-quantity {"hidden_label":true} /-->

		<!-- wp:surecart/product-selected-price-ad-hoc-amount /-->

		<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"5px"}}} -->
		<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
			<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add To Cart"} /-->

			<!-- wp:surecart/product-buy-button {"text":"Buy Now","className":"is-style-outline"} /-->
		</div>
		<!-- /wp:surecart/product-buy-buttons -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- /wp:surecart/product-page -->
```
