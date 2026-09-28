# `surecart-product-quick-view` — Product Quick View

**Source:** `~/Desktop/patterns/product-quick-view.php` — SureCart plugin block-patterns library
**Block type:** `surecart/product-quick-view`
**Category:** `surecart_shop`
**Priority:** `3`

## Use case

Modal/overlay product card shown when a shopper clicks a "Quick View" button on a product list. Compact 500px-wide layout with selected-variant image, title, price stack, variant pills, price chooser, and buy buttons. Closed via `surecart/product-quick-view-close`.

## Conventions demonstrated

- **Outer wrapper:** `surecart/product-quick-view` with `alignment:"center center"`, `width:"500px"`, and inherited `fontSize:"16px"` (typography on the wrapper sets the base for em-relative children).
- **`em` typography:** All inner text sizes use `em` (e.g. `1.2em`, `0.88em`, `0.75em`) relative to the wrapper's 16px base. This is the only pattern that uses em-scaling extensively.
- **Selected-variant image:** `surecart/product-selected-variant-image` with explicit `width:"120px"`, `height:"120px"`, `aspectRatio:"1"`, plus `hide_on_mobile:true`.
- **Inline link color via slug:** `textColor:"contrast"` on `product-title` paired with `elements.link.color.text:"var:preset|color|contrast"` (slug-only, no inline hex on this paired wrapper context).
- **`accent-4` slug:** Used for `product-selected-price-interval` text color — demonstrates how non-default theme palette slugs flow through.
- **Buy-button widths:** Each `product-buy-button` carries `width:50` (50%) so the two buttons split the wrapper evenly.

## Markup (paste-ready)

```html
<!-- wp:surecart/product-quick-view {"alignment":"center center","width":"500px","style":{"typography":{"fontSize":"16px"}}} -->
<!-- wp:group {"style":{"typography":{"fontSize":"1em"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="font-size:1em">
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
    <div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group">
            <!-- wp:surecart/product-selected-variant-image {"aspectRatio":"1","width":"120px","height":"120px","hide_on_mobile":true,"style":{"layout":{"selfStretch":"fixed","flexSize":"120px"}}} /-->

            <!-- wp:group {"style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}}}} -->
            <div class="wp-block-group" style="margin-top:0;margin-bottom:0">
                <!-- wp:surecart/product-title {"isLink":true,"style":{"spacing":{"margin":{"top":"0","bottom":"0px"}},"typography":{"fontSize":"1.2em","fontStyle":"normal","fontWeight":"600","lineHeight":"1.2"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"textColor":"contrast"} /-->

                <!-- wp:surecart/product-selected-variant {"style":{"typography":{"fontSize":"1em"}}} /-->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0.4em"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:500">
                    <!-- wp:surecart/product-selected-price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","lineHeight":"1.5","fontSize":"1em"},"color":{"text":"#686868"},"elements":{"link":{"color":{"text":"#686868"}}}}} /-->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.25em"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group">
                        <!-- wp:surecart/product-selected-price-amount {"style":{"typography":{"lineHeight":"1.5","fontSize":"1em"}}} /-->

                        <!-- wp:surecart/product-selected-price-interval {"style":{"typography":{"fontSize":"0.88em","lineHeight":"1.5"},"elements":{"link":{"color":{"text":"var:preset|color|accent-4"}}}},"textColor":"accent-4"} /-->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:surecart/product-sale-badge {"style":{"border":{"radius":"15px"},"typography":{"fontSize":"12px","lineHeight":"2.1"},"layout":{"selfStretch":"fit","flexSize":null},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"8px","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
                <div class="wp-block-group" style="margin-top:0;margin-bottom:0">
                    <!-- wp:surecart/product-selected-price-trial {"style":{"typography":{"fontSize":"1em"}}} /-->

                    <!-- wp:surecart/product-selected-price-fees {"style":{"typography":{"fontSize":"1em"}}} /-->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:surecart/product-quick-view-close /-->
    </div>
    <!-- /wp:group -->

    <!-- wp:surecart/product-variant-pills -->
    <!-- wp:surecart/product-variant-pill {"style":{"typography":{"fontSize":"0.88em"}}} /-->
    <!-- /wp:surecart/product-variant-pills -->

    <!-- wp:surecart/product-price-chooser -->
    <!-- wp:surecart/product-price-choice-template {"layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap","orientation":"horizontal"}} -->
    <!-- wp:surecart/price-name {"style":{"layout":{"selfStretch":"fixed","flexSize":"50%"},"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"0.88em"}}} /-->

    <!-- wp:group {"style":{"spacing":{"blockGap":"0px"},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"right"}} -->
    <div class="wp-block-group">
        <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
        <div class="wp-block-group">
            <!-- wp:surecart/price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontStyle":"normal","fontWeight":"500","fontSize":"0.88em"},"color":{"text":"#686868"}}} /-->

            <!-- wp:surecart/price-amount {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"0.88em"}}} /-->

            <!-- wp:surecart/price-interval {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"0.75em"}}} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:surecart/price-trial {"style":{"color":{"text":"#8a8a8a"},"elements":{"link":{"color":{"text":"#8a8a8a"}}},"typography":{"fontSize":"0.75rem"}}} /-->

        <!-- wp:surecart/price-setup-fee {"style":{"color":{"text":"#8a8a8a"},"elements":{"link":{"color":{"text":"#8a8a8a"}}},"typography":{"fontSize":"0.75rem"}}} /-->
    </div>
    <!-- /wp:group -->
    <!-- /wp:surecart/product-price-choice-template -->
    <!-- /wp:surecart/product-price-chooser -->

    <!-- wp:surecart/product-selected-price-ad-hoc-amount {"lock":{"move":false,"remove":false}} /-->

    <!-- wp:surecart/product-buy-buttons -->
    <div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
        <!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add To Cart","width":50} /-->

        <!-- wp:surecart/product-buy-button {"text":"Buy Now","width":50,"className":"is-style-outline"} /-->
    </div>
    <!-- /wp:surecart/product-buy-buttons -->
</div>
<!-- /wp:group -->
<!-- /wp:surecart/product-quick-view -->
```
