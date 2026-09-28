# `surecart-product-course-dark` — Product Course Dark

**Source:** `~/Desktop/patterns/product-course-dark.php` — SureCart plugin block-patterns library
**Block type:** `surecart/product-page`
**Category:** `surecart_product_page`
**Priority:** `1`

## Use case

Dark-themed variant of the course product page. Outer bg `#1a1b26`, card bg `#212231`. Emerald (`#34d399`) accents on price / CTA, white text throughout. Uses `core/post-featured-image` (16:9, 20px rounded) for the hero, with variant pills using `bg:contrast` highlight.

## Conventions demonstrated

- Dark palette: `#1a1b26` (page bg), `#212231` (card bg), `#34d399` (emerald action), `#ffffff` (heading), `#9ca3af` (muted body), `#374151` (divider).
- Hero image uses `core/post-featured-image` with `aspectRatio:'16/9'` and `border.radius:'20px'` — an alternative to `surecart/product-media` when a single fixed-ratio image is preferred.
- Variant pills configured with `highlight` style backed by `bg:contrast` so the selected pill uses the inverse of the surrounding bg — keeps the design theme-token-aware.
- Feature-card section repeats the light variant's 3-up SVG icon grid but with dark card backgrounds and white SVG strokes.
- Per-corner `border.radius:'999px'` pills on buy-buttons; emerald fill + dark text for high contrast.
- Course-info bar (Level / Duration / Language) uses `#374151` vertical dividers instead of light gray.

## Content-only variant (v7.18.3 — corrected)

For dark pure-presentation pages without buy interactions, still use the `surecart/product-page` outer wrapper but omit purchase blocks from the inner template. The earlier doctrine referenced a `surecart/product-page-content` block — **that block does NOT exist in the inventory** and the reference was removed in v7.18.3. Use the same dark palette tokens (`#1a1b26` page bg, `#212231` card bg, `#34d399` emerald accent, white headings on dark) + same SVG-stroke-currentColor icon convention.

## Markup (paste-ready)

```html
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"black","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-black-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-page {"metadata":{"categories":["surecart_product_page"],"patternName":"surecart-product-course-dark","name":"Product Course - Dark"},"align":"full","layout":{"type":"constrained","wideSize":"1440px"},"style":{"spacing":{"padding":{"right":"75px","left":"75px","top":"60px","bottom":"0px"},"margin":{"right":"0","left":"0","top":"0"}}}} -->
<!-- wp:columns {"style":{"spacing":{"padding":{"right":"0px","left":"0px"}}}} -->
<div class="wp-block-columns" style="padding-right:0px;padding-left:0px"><!-- wp:column {"width":"864px","style":{"spacing":{"margin":{"top":"0"}}}} -->
<div class="wp-block-column" style="margin-top:0;flex-basis:864px"><!-- wp:post-featured-image {"aspectRatio":"16/9","width":"","height":"","style":{"border":{"radius":{"topLeft":"20px","topRight":"20px","bottomLeft":"20px","bottomRight":"20px"}}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"376px","style":{"spacing":{"margin":{"left":"80px","top":"0"}}}} -->
<div class="wp-block-column" style="margin-top:0;margin-left:80px;flex-basis:376px"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"bottom":"0"}},"border":{"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"}}}} -->
<div class="wp-block-group" style="border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:surecart/product-collection-tags -->
<!-- wp:surecart/product-collection-tag {"style":{"color":{"background":"#212231","text":"#9ca3af"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"}},"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"500","lineHeight":"1.3","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"padding":{"top":"4px","bottom":"4px","left":"4px","right":"4px"}}}} /-->
<!-- /wp:surecart/product-collection-tags -->

<!-- wp:surecart/product-title {"style":{"typography":{"fontSize":"32px","fontStyle":"normal","fontWeight":"500","textAlign":"left"},"spacing":{"margin":{"top":"24px","bottom":"10px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->

<!-- wp:surecart/product-description {"style":{"typography":{"fontSize":"16px","textAlign":"left"},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"margin":{"top":"24px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-selected-price-amount {"style":{"typography":{"fontSize":"32px","lineHeight":"1.3"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->

<!-- wp:surecart/product-selected-price-interval {"style":{"typography":{"lineHeight":"1.5"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"margin":{"left":"0.5em"}}},"textColor":"white"} /-->

<!-- wp:surecart/product-selected-price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontSize":"20px","lineHeight":"1.5"},"color":{"text":"#dc2626"},"elements":{"link":{"color":{"text":"#dc2626"}}},"spacing":{"margin":{"left":"0.5em"}}}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-selected-price-trial {"style":{"elements":{"link":{"color":{"text":"#9ca3af"}}},"color":{"text":"#9ca3af"}}} /-->

<!-- wp:surecart/product-selected-price-fees {"style":{"elements":{"link":{"color":{"text":"#9ca3af"}}},"color":{"text":"#9ca3af"},"spacing":{"margin":{"left":"0.5em"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-price-chooser {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"margin":{"top":"24px"}}},"textColor":"white"} -->
<!-- wp:surecart/product-price-choice-template {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"blockGap":"0"}},"backgroundColor":"black","textColor":"white","layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:surecart/price-name /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","margin":{"bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"right"}} -->
<div class="wp-block-group" style="margin-bottom:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:surecart/price-scratch-amount {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textDecoration":"line-through"}},"textColor":"white"} /-->

<!-- wp:surecart/price-amount {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"left":"0.5rem"}}}} /-->

<!-- wp:surecart/price-interval {"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"left":"0.5rem"}}}} /--></div>
<!-- /wp:group -->

<!-- wp:surecart/price-trial {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"margin":{"top":"0"}}},"fontSize":"small"} /-->

<!-- wp:surecart/price-setup-fee {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"margin":{"top":"0"}}},"fontSize":"small"} /--></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-price-choice-template -->
<!-- /wp:surecart/product-price-chooser -->

<!-- wp:surecart/product-variant-pills {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"spacing":{"margin":{"top":"24px"}}},"textColor":"white"} -->
<!-- wp:surecart/product-variant-pill {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"contrast","textColor":"white"} /-->
<!-- /wp:surecart/product-variant-pills -->

<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"0","margin":{"top":"24px"}}}} -->
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex" style="margin-top:24px"><!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add To Cart","style":{"border":{"radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"},"color":"#34d39940","width":"1px"},"spacing":{"padding":{"left":"16px","right":"16px","top":"16px","bottom":"16px"}},"color":{"background":"#13201f","text":"#34d399"},"elements":{"link":{"color":{"text":"#34d399"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"600","textTransform":"uppercase"}}} /--></div>
<!-- /wp:surecart/product-buy-buttons -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"0","margin":{"top":"24px"}}}} -->
<div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"textColor":"white","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"},"spacing":{"padding":{"left":"16px","right":"16px","top":"16px","bottom":"16px"}},"border":{"radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"},"color":"#2b2d3b","width":"1px"},"color":{"background":"#1a1b26"}},"line_items":[]} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-custom-font-size wp-element-button" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-left-radius:4px;border-bottom-right-radius:4px;background-color:#1a1b26;padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px;font-size:14px;font-style:normal;font-weight:500;text-transform:uppercase">Or Get All Courses for Just $399</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"style":{"color":{"background":"#1a1b26","text":"#9ca3af"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"}},"typography":{"fontSize":"12px","fontStyle":"normal","fontWeight":"500","lineHeight":"1.3","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"blockGap":"0","padding":{"top":"28px","bottom":"28px","left":"32px","right":"32px"},"margin":{"top":"40px","bottom":"40px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group has-border-color has-text-color has-background has-link-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-left-radius:4px;border-bottom-right-radius:4px;color:#9ca3af;background-color:#1a1b26;margin-top:40px;margin-bottom:40px;padding-top:28px;padding-right:32px;padding-bottom:28px;padding-left:32px;font-size:12px;font-style:normal;font-weight:500;line-height:1.3;text-transform:uppercase"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"0","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"295px"},"border":{"top":{"style":"none","width":"0px"},"right":{"style":"none","width":"0px"},"bottom":{"style":"none","width":"0px"},"left":{"color":"#2b2d3b","width":"0px","style":"none"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="border-top-style:none;border-top-width:0px;border-right-style:none;border-right-width:0px;border-bottom-style:none;border-bottom-width:0px;border-left-color:#2b2d3b;border-left-style:none;border-left-width:0px;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;text-transform:none">Level</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600;text-transform:none">All levels</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"left":"32px","top":"0","right":"0","bottom":"0"},"margin":{"left":"0","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"327px"},"border":{"left":{"color":"#2b2d3b","width":"1px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="border-left-color:#2b2d3b;border-left-width:1px;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:32px"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;text-transform:none">Duration</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600;text-transform:none">4 Hours 52 m</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"left":"32px","top":"0","right":"0","bottom":"0"},"margin":{"left":"0","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"327px"},"border":{"left":{"color":"#2b2d3b","width":"1px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="border-left-color:#2b2d3b;border-left-width:1px;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:32px"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;text-transform:none">Instructor</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600;text-transform:none">Esther Howard</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"left":"32px","top":"0","right":"0","bottom":"0"},"margin":{"left":"0","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"327px"},"border":{"left":{"color":"#2b2d3b","width":"1px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="border-left-color:#2b2d3b;border-left-width:1px;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:32px"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;text-transform:none">Language</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600","textTransform":"none"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600;text-transform:none">English with subtitle</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"80px","bottom":"80px","right":"224px","left":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:80px;padding-right:224px;padding-bottom:80px;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"36px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:36px">Dive into the exciting world of web development! Discover the basics of crafting interactive websites from the ground up with HTML, CSS, and JavaScript. Let your creativity flow!</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"48px","bottom":"48px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:48px;padding-right:0;padding-bottom:48px;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"784px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Description</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#9ca3af"}}},"color":{"text":"#9ca3af"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"12px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:12px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">In this course, you'll learn how to set up your own boilerplate from scratch with all the technologies, tools, and libraries you need to get up to speed.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#9ca3af"}}},"color":{"text":"#9ca3af"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"12px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:12px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Well teach you the right approach to using plain JavaScript in a website: we’ll cover Fetch API and History API, then integrate it with ECMAScript 2015+ classes that will use Promises to animate in and animate out your views in a seamless way.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"32px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-top:32px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Requirements</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#9ca3af"}}},"color":{"text":"#9ca3af"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"12px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:12px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">A computer (Windows, Mac or Linux) with the latest version of Chrome installed and a broadband internet connection. That’s it!</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"424px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"112px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left","flexWrap":"wrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:112px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","left":"0","bottom":"12px"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:12px;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Skills you'll gain</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"12px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
<path d="M10 3L4.5 8.5L2 6" stroke="#34D399" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#e5e7eb"}}},"color":{"text":"#e5e7eb"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"12px"}}}} -->
<p class="has-text-color has-link-color" style="color:#e5e7eb;margin-top:0;margin-right:0;margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Read and write basic HTML, CSS, and JS</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"12px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
<path d="M10 3L4.5 8.5L2 6" stroke="#34D399" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#e5e7eb"}}},"color":{"text":"#e5e7eb"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"12px"}}}} -->
<p class="has-text-color has-link-color" style="color:#e5e7eb;margin-top:0;margin-right:0;margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Implement web development principles</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"12px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
<path d="M10 3L4.5 8.5L2 6" stroke="#34D399" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#e5e7eb"}}},"color":{"text":"#e5e7eb"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"12px"}}}} -->
<p class="has-text-color has-link-color" style="color:#e5e7eb;margin-top:0;margin-right:0;margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Build a static website</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
<path d="M10 3L4.5 8.5L2 6" stroke="#34D399" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#e5e7eb"}}},"color":{"text":"#e5e7eb"},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"12px"}}}} -->
<p class="has-text-color has-link-color" style="color:#e5e7eb;margin-top:0;margin-right:0;margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Launch a website with GitHub</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"48px","bottom":"48px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}},"border":{"top":{"color":"#2b2d3b","width":"1px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="border-top-color:#2b2d3b;border-top-width:1px;margin-top:0;margin-bottom:0;padding-top:48px;padding-right:0;padding-bottom:48px;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M14.5 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V7.5L14.5 2Z" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14 2V8H20" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 11L15 14L10 17V11Z" stroke="#34D399" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"16px","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"424px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:16px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Pro video course</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"15px"},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:15px">Expert-led practical modules</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"24px","bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:24px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 18L22 12L16 6" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M8 6L2 12L8 18" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"16px","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"424px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:16px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Practical cde exercises</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"15px"},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:15px">Learning by doing with practical exercises</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"24px","bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:24px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3.85019 8.6201C3.70423 7.96262 3.72665 7.27894 3.91535 6.63244C4.10405 5.98593 4.45294 5.39754 4.92966 4.92182C5.40638 4.4461 5.9955 4.09844 6.6424 3.91109C7.2893 3.72374 7.97303 3.70276 8.63019 3.8501C8.9919 3.2844 9.4902 2.81886 10.0791 2.49638C10.6681 2.17391 11.3287 2.00488 12.0002 2.00488C12.6716 2.00488 13.3323 2.17391 13.9212 2.49638C14.5102 2.81886 15.0085 3.2844 15.3702 3.8501C16.0284 3.70212 16.7133 3.72301 17.3612 3.91081C18.0091 4.09862 18.599 4.44724 19.076 4.92425C19.5531 5.40126 19.9017 5.99117 20.0895 6.6391C20.2773 7.28703 20.2982 7.97193 20.1502 8.6301C20.7159 8.99181 21.1814 9.4901 21.5039 10.079C21.8264 10.668 21.9954 11.3286 21.9954 12.0001C21.9954 12.6715 21.8264 13.3322 21.5039 13.9211C21.1814 14.5101 20.7159 15.0084 20.1502 15.3701C20.2975 16.0273 20.2765 16.711 20.0892 17.3579C19.9018 18.0048 19.5542 18.5939 19.0785 19.0706C18.6027 19.5473 18.0144 19.8962 17.3679 20.0849C16.7213 20.2736 16.0377 20.2961 15.3802 20.1501C15.019 20.718 14.5203 21.1855 13.9303 21.5094C13.3404 21.8333 12.6782 22.0032 12.0052 22.0032C11.3322 22.0032 10.67 21.8333 10.0801 21.5094C9.49011 21.1855 8.99143 20.718 8.63019 20.1501C7.97303 20.2974 7.2893 20.2765 6.6424 20.0891C5.9955 19.9018 5.40638 19.5541 4.92966 19.0784C4.45294 18.6027 4.10405 18.0143 3.91535 17.3678C3.72665 16.7213 3.70423 16.0376 3.85019 15.3801C3.28015 15.0193 2.81061 14.5203 2.48524 13.9293C2.15988 13.3384 1.98926 12.6747 1.98926 12.0001C1.98926 11.3255 2.15988 10.6618 2.48524 10.0709C2.81061 9.47992 3.28015 8.98085 3.85019 8.6201Z" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 12L11 14L15 10" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"16px","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"424px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:16px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:600">Certificate of completion</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"15px"},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"4px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:4px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:15px">Build skills, and earn a certificate</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"872px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:80px;padding-right:0;padding-bottom:80px;padding-left:0"><!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:500">Course curriculum</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"48px","bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:48px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Introduction</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"},"margin":{"top":"12px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Brief Introduction to Web Development</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"},"margin":{"top":"12px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Working In HTML and CSS</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"},"margin":{"top":"12px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Working In Javascript and Publishing</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"},"margin":{"top":"12px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Build Tools</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"},"margin":{"top":"12px"}},"color":{"background":"#1a1b26"},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}}} -->
<div role="group" class="wp-block-accordion has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px;font-size:18px;font-style:normal;font-weight:500"><!-- wp:accordion-item {"style":{"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<div class="wp-block-accordion-item has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"500"},"color":{"background":"#1a1b26"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h3 class="wp-block-accordion-heading has-white-color has-text-color has-background has-link-color" style="background-color:#1a1b26;font-size:18px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Final project</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:80px;padding-right:0;padding-bottom:80px;padding-left:0"><!-- wp:group {"style":{"color":{"background":"#1a1b26"},"spacing":{"blockGap":"0","padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"},"margin":{"top":"0","bottom":"0"}},"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group has-border-color has-background" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background-color:#1a1b26;margin-top:0;margin-bottom:0;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"424px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"id":130,"sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fixed","flexSize":"424px"},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<figure class="wp-block-image size-full" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><img src="http://localhost:10003/wp-content/uploads/2025/11/Rectangle-36.png" alt="" class="wp-image-130"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"48px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:48px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"768px"},"dimensions":{"minHeight":"336px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="min-height:336px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"40px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:40px;font-style:normal;font-weight:500">Esther Howard</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"8px","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:8px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:500">Creative Director at UclaStudio</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"16px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:16px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Esther is a creative developer from Amsterdam! She kicked off her journey studying multimedia design but soon found her passion for coding. Now, she blends both skills to run her own web design and development gig. She really enjoys mixing logic with aesthetics, ensuring her projects are not just functional but also eye-catching.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"border":{"top":{"color":"#2b2d3b","width":"1px"}},"spacing":{"padding":{"top":"32px","right":"0","bottom":"0","left":"0"},"margin":{"top":"32px","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="border-top-color:#2b2d3b;border-top-width:1px;margin-top:32px;margin-bottom:0;padding-top:32px;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:social-links {"customIconColor":"#6b7280","iconColorValue":"#6b7280","customIconBackgroundColor":"#ffffff00","iconBackgroundColorValue":"#ffffff00","size":"has-small-icon-size"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color has-icon-background-color"><!-- wp:social-link {"url":"https://surecart.com","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://surecart.com","service":"x"} /-->

<!-- wp:social-link {"url":"https://surecart.com","service":"linkedin"} /-->

<!-- wp:social-link {"url":"https://surecart.com","service":"github"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"80px","bottom":"80px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:80px;padding-right:0;padding-bottom:80px;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:500">Hear from our students</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"24px","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"bottom":"0"}},"border":{"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"},"color":"#2b2d3b","width":"1px"},"layout":{"selfStretch":"fixed","flexSize":"312px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:html -->
<svg width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.23915 2.34164C9.47864 1.60459 10.5214 1.60459 10.7608 2.34164L12.0655 6.35704C12.1726 6.68666 12.4798 6.90983 12.8264 6.90983H17.0484C17.8234 6.90983 18.1456 7.90152 17.5186 8.35704L14.1029 10.8387C13.8226 11.0424 13.7052 11.4035 13.8123 11.7331L15.117 15.7485C15.3565 16.4856 14.5129 17.0985 13.8859 16.643L10.4702 14.1613C10.1898 13.9576 9.81016 13.9576 9.52977 14.1613L6.11407 16.643C5.48709 17.0985 4.64351 16.4856 4.88299 15.7485L6.18768 11.7331C6.29478 11.4035 6.17745 11.0424 5.89706 10.8387L2.48135 8.35704C1.85438 7.90152 2.1766 6.90983 2.95158 6.90983H7.17363C7.52021 6.90983 7.82737 6.68666 7.93447 6.35704L9.23915 2.34164Z" fill="#FBBF24"/>
<path d="M29.2392 2.34164C29.4786 1.60459 30.5214 1.60459 30.7608 2.34164L32.0655 6.35704C32.1726 6.68666 32.4798 6.90983 32.8264 6.90983H37.0484C37.8234 6.90983 38.1456 7.90152 37.5186 8.35704L34.1029 10.8387C33.8226 11.0424 33.7052 11.4035 33.8123 11.7331L35.117 15.7485C35.3565 16.4856 34.5129 17.0985 33.8859 16.643L30.4702 14.1613C30.1898 13.9576 29.8102 13.9576 29.5298 14.1613L26.1141 16.643C25.4871 17.0985 24.6435 16.4856 24.883 15.7485L26.1877 11.7331C26.2948 11.4035 26.1774 11.0424 25.8971 10.8387L22.4814 8.35704C21.8544 7.90152 22.1766 6.90983 22.9516 6.90983H27.1736C27.5202 6.90983 27.8274 6.68666 27.9345 6.35704L29.2392 2.34164Z" fill="#FBBF24"/>
<path d="M49.2392 2.34164C49.4786 1.60459 50.5214 1.60459 50.7608 2.34164L52.0655 6.35704C52.1726 6.68666 52.4798 6.90983 52.8264 6.90983H57.0484C57.8234 6.90983 58.1456 7.90152 57.5186 8.35704L54.1029 10.8387C53.8226 11.0424 53.7052 11.4035 53.8123 11.7331L55.117 15.7485C55.3565 16.4856 54.5129 17.0985 53.8859 16.643L50.4702 14.1613C50.1898 13.9576 49.8102 13.9576 49.5298 14.1613L46.1141 16.643C45.4871 17.0985 44.6435 16.4856 44.883 15.7485L46.1877 11.7331C46.2948 11.4035 46.1774 11.0424 45.8971 10.8387L42.4814 8.35704C41.8544 7.90152 42.1766 6.90983 42.9516 6.90983H47.1736C47.5202 6.90983 47.8274 6.68666 47.9345 6.35704L49.2392 2.34164Z" fill="#FBBF24"/>
<path d="M69.2392 2.34164C69.4786 1.60459 70.5214 1.60459 70.7608 2.34164L72.0655 6.35704C72.1726 6.68666 72.4798 6.90983 72.8264 6.90983H77.0484C77.8234 6.90983 78.1456 7.90152 77.5186 8.35704L74.1029 10.8387C73.8226 11.0424 73.7052 11.4035 73.8123 11.7331L75.117 15.7485C75.3565 16.4856 74.5129 17.0985 73.8859 16.643L70.4702 14.1613C70.1898 13.9576 69.8102 13.9576 69.5298 14.1613L66.1141 16.643C65.4871 17.0985 64.6435 16.4856 64.883 15.7485L66.1877 11.7331C66.2948 11.4035 66.1774 11.0424 65.8971 10.8387L62.4814 8.35704C61.8544 7.90152 62.1766 6.90983 62.9516 6.90983H67.1736C67.5202 6.90983 67.8274 6.68666 67.9345 6.35704L69.2392 2.34164Z" fill="#FBBF24"/>
<path d="M89.2392 2.34164C89.4786 1.60459 90.5214 1.60459 90.7608 2.34164L92.0655 6.35704C92.1726 6.68666 92.4798 6.90983 92.8264 6.90983H97.0484C97.8234 6.90983 98.1456 7.90152 97.5186 8.35704L94.1029 10.8387C93.8226 11.0424 93.7052 11.4035 93.8123 11.7331L95.117 15.7485C95.3565 16.4856 94.5129 17.0985 93.8859 16.643L90.4702 14.1613C90.1898 13.9576 89.8102 13.9576 89.5298 14.1613L86.1141 16.643C85.4871 17.0985 84.6435 16.4856 84.883 15.7485L86.1877 11.7331C86.2948 11.4035 86.1774 11.0424 85.8971 10.8387L82.4814 8.35704C81.8544 7.90152 82.1766 6.90983 82.9516 6.90983H87.1736C87.5202 6.90983 87.8274 6.68666 87.9345 6.35704L89.2392 2.34164Z" fill="#FBBF24"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"dimensions":{"minHeight":"140px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="min-height:140px;margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:500">“Always challenge the status quo; innovation comes from bold thinking.”</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"id":154,"aspectRatio":"1","scale":"cover","sizeSlug":"medium","linkDestination":"none","className":"is-style-rounded","style":{"layout":{"selfStretch":"fixed","flexSize":"49px"},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<figure class="wp-block-image size-medium is-style-rounded" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><img src="http://localhost:10003/wp-content/uploads/2025/11/6aeadee1637104e33e46ae778e2dc26fe2d80033-300x300.jpg" alt="" class="wp-image-154" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"12px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px;font-style:normal;font-weight:600">Ava</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"2px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:2px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">@ava_davis</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"bottom":"0"}},"border":{"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"},"color":"#2b2d3b","width":"1px"},"layout":{"selfStretch":"fixed","flexSize":"312px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:html -->
<svg width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.23915 2.34164C9.47864 1.60459 10.5214 1.60459 10.7608 2.34164L12.0655 6.35704C12.1726 6.68666 12.4798 6.90983 12.8264 6.90983H17.0484C17.8234 6.90983 18.1456 7.90152 17.5186 8.35704L14.1029 10.8387C13.8226 11.0424 13.7052 11.4035 13.8123 11.7331L15.117 15.7485C15.3565 16.4856 14.5129 17.0985 13.8859 16.643L10.4702 14.1613C10.1898 13.9576 9.81016 13.9576 9.52977 14.1613L6.11407 16.643C5.48709 17.0985 4.64351 16.4856 4.88299 15.7485L6.18768 11.7331C6.29478 11.4035 6.17745 11.0424 5.89706 10.8387L2.48135 8.35704C1.85438 7.90152 2.1766 6.90983 2.95158 6.90983H7.17363C7.52021 6.90983 7.82737 6.68666 7.93447 6.35704L9.23915 2.34164Z" fill="#FBBF24"/>
<path d="M29.2392 2.34164C29.4786 1.60459 30.5214 1.60459 30.7608 2.34164L32.0655 6.35704C32.1726 6.68666 32.4798 6.90983 32.8264 6.90983H37.0484C37.8234 6.90983 38.1456 7.90152 37.5186 8.35704L34.1029 10.8387C33.8226 11.0424 33.7052 11.4035 33.8123 11.7331L35.117 15.7485C35.3565 16.4856 34.5129 17.0985 33.8859 16.643L30.4702 14.1613C30.1898 13.9576 29.8102 13.9576 29.5298 14.1613L26.1141 16.643C25.4871 17.0985 24.6435 16.4856 24.883 15.7485L26.1877 11.7331C26.2948 11.4035 26.1774 11.0424 25.8971 10.8387L22.4814 8.35704C21.8544 7.90152 22.1766 6.90983 22.9516 6.90983H27.1736C27.5202 6.90983 27.8274 6.68666 27.9345 6.35704L29.2392 2.34164Z" fill="#FBBF24"/>
<path d="M49.2392 2.34164C49.4786 1.60459 50.5214 1.60459 50.7608 2.34164L52.0655 6.35704C52.1726 6.68666 52.4798 6.90983 52.8264 6.90983H57.0484C57.8234 6.90983 58.1456 7.90152 57.5186 8.35704L54.1029 10.8387C53.8226 11.0424 53.7052 11.4035 53.8123 11.7331L55.117 15.7485C55.3565 16.4856 54.5129 17.0985 53.8859 16.643L50.4702 14.1613C50.1898 13.9576 49.8102 13.9576 49.5298 14.1613L46.1141 16.643C45.4871 17.0985 44.6435 16.4856 44.883 15.7485L46.1877 11.7331C46.2948 11.4035 46.1774 11.0424 45.8971 10.8387L42.4814 8.35704C41.8544 7.90152 42.1766 6.90983 42.9516 6.90983H47.1736C47.5202 6.90983 47.8274 6.68666 47.9345 6.35704L49.2392 2.34164Z" fill="#FBBF24"/>
<path d="M69.2392 2.34164C69.4786 1.60459 70.5214 1.60459 70.7608 2.34164L72.0655 6.35704C72.1726 6.68666 72.4798 6.90983 72.8264 6.90983H77.0484C77.8234 6.90983 78.1456 7.90152 77.5186 8.35704L74.1029 10.8387C73.8226 11.0424 73.7052 11.4035 73.8123 11.7331L75.117 15.7485C75.3565 16.4856 74.5129 17.0985 73.8859 16.643L70.4702 14.1613C70.1898 13.9576 69.8102 13.9576 69.5298 14.1613L66.1141 16.643C65.4871 17.0985 64.6435 16.4856 64.883 15.7485L66.1877 11.7331C66.2948 11.4035 66.1774 11.0424 65.8971 10.8387L62.4814 8.35704C61.8544 7.90152 62.1766 6.90983 62.9516 6.90983H67.1736C67.5202 6.90983 67.8274 6.68666 67.9345 6.35704L69.2392 2.34164Z" fill="#FBBF24"/>
<path d="M89.2392 2.34164C89.4786 1.60459 90.5214 1.60459 90.7608 2.34164L92.0655 6.35704C92.1726 6.68666 92.4798 6.90983 92.8264 6.90983H97.0484C97.8234 6.90983 98.1456 7.90152 97.5186 8.35704L94.1029 10.8387C93.8226 11.0424 93.7052 11.4035 93.8123 11.7331L95.117 15.7485C95.3565 16.4856 94.5129 17.0985 93.8859 16.643L90.4702 14.1613C90.1898 13.9576 89.8102 13.9576 89.5298 14.1613L86.1141 16.643C85.4871 17.0985 84.6435 16.4856 84.883 15.7485L86.1877 11.7331C86.2948 11.4035 86.1774 11.0424 85.8971 10.8387L82.4814 8.35704C81.8544 7.90152 82.1766 6.90983 82.9516 6.90983H87.1736C87.5202 6.90983 87.8274 6.68666 87.9345 6.35704L89.2392 2.34164Z" fill="#FBBF24"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"dimensions":{"minHeight":"140px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="min-height:140px;margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:500">“This is a game-changer for my career! The hands-on projects really helped me apply what I learned.”</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"id":156,"aspectRatio":"1","scale":"cover","sizeSlug":"medium","linkDestination":"none","className":"is-style-rounded","style":{"layout":{"selfStretch":"fixed","flexSize":"49px"},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<figure class="wp-block-image size-medium is-style-rounded" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><img src="http://localhost:10003/wp-content/uploads/2025/11/de8387cc3e29646e010d102c4c7c8ff37fd93e8d-300x300.jpg" alt="" class="wp-image-156" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"12px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px;font-style:normal;font-weight:600">Sophia</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"2px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:2px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">@sophia_lee</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"bottom":"0"}},"border":{"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"},"color":"#2b2d3b","width":"1px"},"layout":{"selfStretch":"fixed","flexSize":"312px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:html -->
<svg width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.23915 2.34164C9.47864 1.60459 10.5214 1.60459 10.7608 2.34164L12.0655 6.35704C12.1726 6.68666 12.4798 6.90983 12.8264 6.90983H17.0484C17.8234 6.90983 18.1456 7.90152 17.5186 8.35704L14.1029 10.8387C13.8226 11.0424 13.7052 11.4035 13.8123 11.7331L15.117 15.7485C15.3565 16.4856 14.5129 17.0985 13.8859 16.643L10.4702 14.1613C10.1898 13.9576 9.81016 13.9576 9.52977 14.1613L6.11407 16.643C5.48709 17.0985 4.64351 16.4856 4.88299 15.7485L6.18768 11.7331C6.29478 11.4035 6.17745 11.0424 5.89706 10.8387L2.48135 8.35704C1.85438 7.90152 2.1766 6.90983 2.95158 6.90983H7.17363C7.52021 6.90983 7.82737 6.68666 7.93447 6.35704L9.23915 2.34164Z" fill="#FBBF24"/>
<path d="M29.2392 2.34164C29.4786 1.60459 30.5214 1.60459 30.7608 2.34164L32.0655 6.35704C32.1726 6.68666 32.4798 6.90983 32.8264 6.90983H37.0484C37.8234 6.90983 38.1456 7.90152 37.5186 8.35704L34.1029 10.8387C33.8226 11.0424 33.7052 11.4035 33.8123 11.7331L35.117 15.7485C35.3565 16.4856 34.5129 17.0985 33.8859 16.643L30.4702 14.1613C30.1898 13.9576 29.8102 13.9576 29.5298 14.1613L26.1141 16.643C25.4871 17.0985 24.6435 16.4856 24.883 15.7485L26.1877 11.7331C26.2948 11.4035 26.1774 11.0424 25.8971 10.8387L22.4814 8.35704C21.8544 7.90152 22.1766 6.90983 22.9516 6.90983H27.1736C27.5202 6.90983 27.8274 6.68666 27.9345 6.35704L29.2392 2.34164Z" fill="#FBBF24"/>
<path d="M49.2392 2.34164C49.4786 1.60459 50.5214 1.60459 50.7608 2.34164L52.0655 6.35704C52.1726 6.68666 52.4798 6.90983 52.8264 6.90983H57.0484C57.8234 6.90983 58.1456 7.90152 57.5186 8.35704L54.1029 10.8387C53.8226 11.0424 53.7052 11.4035 53.8123 11.7331L55.117 15.7485C55.3565 16.4856 54.5129 17.0985 53.8859 16.643L50.4702 14.1613C50.1898 13.9576 49.8102 13.9576 49.5298 14.1613L46.1141 16.643C45.4871 17.0985 44.6435 16.4856 44.883 15.7485L46.1877 11.7331C46.2948 11.4035 46.1774 11.0424 45.8971 10.8387L42.4814 8.35704C41.8544 7.90152 42.1766 6.90983 42.9516 6.90983H47.1736C47.5202 6.90983 47.8274 6.68666 47.9345 6.35704L49.2392 2.34164Z" fill="#FBBF24"/>
<path d="M69.2392 2.34164C69.4786 1.60459 70.5214 1.60459 70.7608 2.34164L72.0655 6.35704C72.1726 6.68666 72.4798 6.90983 72.8264 6.90983H77.0484C77.8234 6.90983 78.1456 7.90152 77.5186 8.35704L74.1029 10.8387C73.8226 11.0424 73.7052 11.4035 73.8123 11.7331L75.117 15.7485C75.3565 16.4856 74.5129 17.0985 73.8859 16.643L70.4702 14.1613C70.1898 13.9576 69.8102 13.9576 69.5298 14.1613L66.1141 16.643C65.4871 17.0985 64.6435 16.4856 64.883 15.7485L66.1877 11.7331C66.2948 11.4035 66.1774 11.0424 65.8971 10.8387L62.4814 8.35704C61.8544 7.90152 62.1766 6.90983 62.9516 6.90983H67.1736C67.5202 6.90983 67.8274 6.68666 67.9345 6.35704L69.2392 2.34164Z" fill="#FBBF24"/>
<path d="M89.2392 2.34164C89.4786 1.60459 90.5214 1.60459 90.7608 2.34164L92.0655 6.35704C92.1726 6.68666 92.4798 6.90983 92.8264 6.90983H97.0484C97.8234 6.90983 98.1456 7.90152 97.5186 8.35704L94.1029 10.8387C93.8226 11.0424 93.7052 11.4035 93.8123 11.7331L95.117 15.7485C95.3565 16.4856 94.5129 17.0985 93.8859 16.643L90.4702 14.1613C90.1898 13.9576 89.8102 13.9576 89.5298 14.1613L86.1141 16.643C85.4871 17.0985 84.6435 16.4856 84.883 15.7485L86.1877 11.7331C86.2948 11.4035 86.1774 11.0424 85.8971 10.8387L82.4814 8.35704C81.8544 7.90152 82.1766 6.90983 82.9516 6.90983H87.1736C87.5202 6.90983 87.8274 6.68666 87.9345 6.35704L89.2392 2.34164Z" fill="#FBBF24"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"dimensions":{"minHeight":"140px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="min-height:140px;margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:500">“An incredible experience! The mentorship provided valuable insights that I couldn't find in textbooks.”</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"id":155,"aspectRatio":"1","scale":"cover","sizeSlug":"medium","linkDestination":"none","className":"is-style-rounded","style":{"layout":{"selfStretch":"fixed","flexSize":"49px"},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<figure class="wp-block-image size-medium is-style-rounded" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><img src="http://localhost:10003/wp-content/uploads/2025/11/05d923c0da78ce29f68aa5ada7e433d50c99e0b9-300x300.jpg" alt="" class="wp-image-155" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"12px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px;font-style:normal;font-weight:600">Emma</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"2px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:2px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">@emma_clark</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"bottom":"0"}},"border":{"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"},"color":"#2b2d3b","width":"1px"},"layout":{"selfStretch":"fixed","flexSize":"312px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:html -->
<svg width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.23915 2.34164C9.47864 1.60459 10.5214 1.60459 10.7608 2.34164L12.0655 6.35704C12.1726 6.68666 12.4798 6.90983 12.8264 6.90983H17.0484C17.8234 6.90983 18.1456 7.90152 17.5186 8.35704L14.1029 10.8387C13.8226 11.0424 13.7052 11.4035 13.8123 11.7331L15.117 15.7485C15.3565 16.4856 14.5129 17.0985 13.8859 16.643L10.4702 14.1613C10.1898 13.9576 9.81016 13.9576 9.52977 14.1613L6.11407 16.643C5.48709 17.0985 4.64351 16.4856 4.88299 15.7485L6.18768 11.7331C6.29478 11.4035 6.17745 11.0424 5.89706 10.8387L2.48135 8.35704C1.85438 7.90152 2.1766 6.90983 2.95158 6.90983H7.17363C7.52021 6.90983 7.82737 6.68666 7.93447 6.35704L9.23915 2.34164Z" fill="#FBBF24"/>
<path d="M29.2392 2.34164C29.4786 1.60459 30.5214 1.60459 30.7608 2.34164L32.0655 6.35704C32.1726 6.68666 32.4798 6.90983 32.8264 6.90983H37.0484C37.8234 6.90983 38.1456 7.90152 37.5186 8.35704L34.1029 10.8387C33.8226 11.0424 33.7052 11.4035 33.8123 11.7331L35.117 15.7485C35.3565 16.4856 34.5129 17.0985 33.8859 16.643L30.4702 14.1613C30.1898 13.9576 29.8102 13.9576 29.5298 14.1613L26.1141 16.643C25.4871 17.0985 24.6435 16.4856 24.883 15.7485L26.1877 11.7331C26.2948 11.4035 26.1774 11.0424 25.8971 10.8387L22.4814 8.35704C21.8544 7.90152 22.1766 6.90983 22.9516 6.90983H27.1736C27.5202 6.90983 27.8274 6.68666 27.9345 6.35704L29.2392 2.34164Z" fill="#FBBF24"/>
<path d="M49.2392 2.34164C49.4786 1.60459 50.5214 1.60459 50.7608 2.34164L52.0655 6.35704C52.1726 6.68666 52.4798 6.90983 52.8264 6.90983H57.0484C57.8234 6.90983 58.1456 7.90152 57.5186 8.35704L54.1029 10.8387C53.8226 11.0424 53.7052 11.4035 53.8123 11.7331L55.117 15.7485C55.3565 16.4856 54.5129 17.0985 53.8859 16.643L50.4702 14.1613C50.1898 13.9576 49.8102 13.9576 49.5298 14.1613L46.1141 16.643C45.4871 17.0985 44.6435 16.4856 44.883 15.7485L46.1877 11.7331C46.2948 11.4035 46.1774 11.0424 45.8971 10.8387L42.4814 8.35704C41.8544 7.90152 42.1766 6.90983 42.9516 6.90983H47.1736C47.5202 6.90983 47.8274 6.68666 47.9345 6.35704L49.2392 2.34164Z" fill="#FBBF24"/>
<path d="M69.2392 2.34164C69.4786 1.60459 70.5214 1.60459 70.7608 2.34164L72.0655 6.35704C72.1726 6.68666 72.4798 6.90983 72.8264 6.90983H77.0484C77.8234 6.90983 78.1456 7.90152 77.5186 8.35704L74.1029 10.8387C73.8226 11.0424 73.7052 11.4035 73.8123 11.7331L75.117 15.7485C75.3565 16.4856 74.5129 17.0985 73.8859 16.643L70.4702 14.1613C70.1898 13.9576 69.8102 13.9576 69.5298 14.1613L66.1141 16.643C65.4871 17.0985 64.6435 16.4856 64.883 15.7485L66.1877 11.7331C66.2948 11.4035 66.1774 11.0424 65.8971 10.8387L62.4814 8.35704C61.8544 7.90152 62.1766 6.90983 62.9516 6.90983H67.1736C67.5202 6.90983 67.8274 6.68666 67.9345 6.35704L69.2392 2.34164Z" fill="#FBBF24"/>
<path d="M89.2392 2.34164C89.4786 1.60459 90.5214 1.60459 90.7608 2.34164L92.0655 6.35704C92.1726 6.68666 92.4798 6.90983 92.8264 6.90983H97.0484C97.8234 6.90983 98.1456 7.90152 97.5186 8.35704L94.1029 10.8387C93.8226 11.0424 93.7052 11.4035 93.8123 11.7331L95.117 15.7485C95.3565 16.4856 94.5129 17.0985 93.8859 16.643L90.4702 14.1613C90.1898 13.9576 89.8102 13.9576 89.5298 14.1613L86.1141 16.643C85.4871 17.0985 84.6435 16.4856 84.883 15.7485L86.1877 11.7331C86.2948 11.4035 86.1774 11.0424 85.8971 10.8387L82.4814 8.35704C81.8544 7.90152 82.1766 6.90983 82.9516 6.90983H87.1736C87.5202 6.90983 87.8274 6.68666 87.9345 6.35704L89.2392 2.34164Z" fill="#FBBF24"/>
</svg>
<!-- /wp:html -->

<!-- wp:group {"style":{"dimensions":{"minHeight":"140px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="min-height:140px;margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:500">“I’ve never felt more engaged in an online course. The community support is fantastic!”</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"24px","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:24px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"id":157,"aspectRatio":"1","scale":"cover","sizeSlug":"medium","linkDestination":"none","className":"is-style-rounded","style":{"layout":{"selfStretch":"fixed","flexSize":"49px"},"spacing":{"margin":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<figure class="wp-block-image size-medium is-style-rounded" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><img src="http://localhost:10003/wp-content/uploads/2025/11/cc4f99adfb1e823665f43d54022f6790af91de56-300x300.jpg" alt="" class="wp-image-157" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"12px","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:12px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","fontStyle":"normal","fontWeight":"600"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px;font-style:normal;font-weight:600">Matthew</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"2px","right":"0","bottom":"0","left":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:2px;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">@matthew_jones</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"112px","bottom":"112px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:112px;padding-right:0;padding-bottom:112px;padding-left:0"><!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"48px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:500">Lifetime access</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"48px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:48px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"400px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"0","bottom":"0"}},"border":{"bottom":{"color":"#2b2d3b","width":"1px"},"top":[],"right":[],"left":[]}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="border-bottom-color:#2b2d3b;border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"16px","textTransform":"uppercase"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:700;text-transform:uppercase">PRO</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"20px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
<div class="wp-block-group" style="margin-top:20px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"700","lineHeight":"1"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:700;line-height:1">$39</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"4px"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:4px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">per month</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.9613 4.92773L7.7946 14.0944L3.62793 9.92773" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Lifetime access</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.9613 4.92773L7.7946 14.0944L3.62793 9.92773" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Certificate of completion</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.9613 4.92773L7.7946 14.0944L3.62793 9.92773" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Community access</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.9613 4.92773L7.7946 14.0944L3.62793 9.92773" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Join weekly live sessions</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.9613 4.92773L7.7946 14.0944L3.62793 9.92773" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Get feedback on your work</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"0","margin":{"top":"32px"}}}} -->
<div class="wp-block-buttons" style="margin-top:32px"><!-- wp:button {"textColor":"white","width":100,"style":{"spacing":{"padding":{"left":"16px","right":"16px","top":"16px","bottom":"16px"}},"color":{"background":"#1a1b26"},"border":{"radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"},"color":"#2b2d3b","width":"1px"},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"line_items":[]} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-custom-font-size wp-element-button" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-left-radius:4px;border-bottom-right-radius:4px;background-color:#1a1b26;padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px;font-size:16px;font-style:normal;font-weight:500;text-transform:uppercase">Get Access</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"border":{"color":"#2b2d3b","width":"1px","radius":{"topLeft":"12px","topRight":"12px","bottomLeft":"12px","bottomRight":"12px"}},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"24px","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"400px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:12px;border-top-right-radius:12px;border-bottom-left-radius:12px;border-bottom-right-radius:12px;margin-bottom:0;margin-left:24px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"0","bottom":"0"}},"border":{"bottom":{"color":"#2b2d3b","width":"1px"},"top":[],"right":[],"left":[]}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="border-bottom-color:#2b2d3b;border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"16px","textTransform":"uppercase"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-style:normal;font-weight:700;text-transform:uppercase">Team</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"20px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
<div class="wp-block-group" style="margin-top:20px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"700","lineHeight":"1"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:700;line-height:1">$279</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"4px"}}}} -->
<p class="has-text-color has-link-color" style="color:#9ca3af;margin-top:0;margin-right:0;margin-bottom:0;margin-left:4px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">per month</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.1665 10H15.8332" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 4.16602V15.8327" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Everything from PRO</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.1665 10H15.8332" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 4.16602V15.8327" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Multiple Licensing Options</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.1665 10H15.8332" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 4.16602V15.8327" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Book a Free Live Demo</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.1665 10H15.8332" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 4.16602V15.8327" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Flexible Schedules</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"8px","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:8px;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:html -->
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.1665 10H15.8332" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10 4.16602V15.8327" stroke="#34D399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"#ffffff"}}},"typography":{"fontSize":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"8px","right":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#ffffff;margin-top:0;margin-right:0;margin-bottom:0;margin-left:8px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px">Group Discounts</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"0","margin":{"top":"32px"}}}} -->
<div class="wp-block-buttons" style="margin-top:32px"><!-- wp:button {"textColor":"white","width":100,"style":{"spacing":{"padding":{"left":"16px","right":"16px","top":"16px","bottom":"16px"}},"color":{"background":"#1a1b26"},"border":{"radius":{"topLeft":"4px","topRight":"4px","bottomLeft":"4px","bottomRight":"4px"},"color":"#2b2d3b","width":"1px"},"typography":{"fontSize":"16px","fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"line_items":[]} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-custom-font-size wp-element-button" style="border-color:#2b2d3b;border-width:1px;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-left-radius:4px;border-bottom-right-radius:4px;background-color:#1a1b26;padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px;font-size:16px;font-style:normal;font-weight:500;text-transform:uppercase">Get Access</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"104px","bottom":"104px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:104px;padding-right:0;padding-bottom:104px;padding-left:0"><!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"424px"},"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color" style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:48px;font-style:normal;font-weight:500">Frequently asked questions</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"left":"32px","bottom":"0"}},"layout":{"selfStretch":"fixed","flexSize":"644px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-bottom:0;margin-left:32px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:accordion {"style":{"spacing":{"margin":{"top":"0"}}}} -->
<div role="group" class="wp-block-accordion" style="margin-top:0"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px"}},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"border":{"top":{"color":"#2b2d3b","width":"1px"},"right":[],"bottom":[],"left":[]}}} -->
<h3 class="wp-block-accordion-heading has-text-color has-link-color" style="border-top-color:#2b2d3b;border-top-width:1px;color:#9ca3af;font-size:24px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle" style="padding-top:24px;padding-bottom:24px"><span class="wp-block-accordion-heading__toggle-title">What is the duration of each course?</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"spacing":{"margin":{"top":"0"}}}} -->
<div role="group" class="wp-block-accordion" style="margin-top:0"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px"}},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"border":{"top":{"color":"#2b2d3b","width":"1px"},"right":[],"bottom":[],"left":[]}}} -->
<h3 class="wp-block-accordion-heading has-text-color has-link-color" style="border-top-color:#2b2d3b;border-top-width:1px;color:#9ca3af;font-size:24px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle" style="padding-top:24px;padding-bottom:24px"><span class="wp-block-accordion-heading__toggle-title">Are there any prerequisites?</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"spacing":{"margin":{"top":"0"}}}} -->
<div role="group" class="wp-block-accordion" style="margin-top:0"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px"}},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"border":{"top":{"color":"#2b2d3b","width":"1px"},"right":[],"bottom":[],"left":[]}}} -->
<h3 class="wp-block-accordion-heading has-text-color has-link-color" style="border-top-color:#2b2d3b;border-top-width:1px;color:#9ca3af;font-size:24px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle" style="padding-top:24px;padding-bottom:24px"><span class="wp-block-accordion-heading__toggle-title">Do you offer certificates?</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"spacing":{"margin":{"top":"0"}}}} -->
<div role="group" class="wp-block-accordion" style="margin-top:0"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px"}},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"border":{"top":{"color":"#2b2d3b","width":"1px"},"right":[],"bottom":[],"left":[]}}} -->
<h3 class="wp-block-accordion-heading has-text-color has-link-color" style="border-top-color:#2b2d3b;border-top-width:1px;color:#9ca3af;font-size:24px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle" style="padding-top:24px;padding-bottom:24px"><span class="wp-block-accordion-heading__toggle-title">Can I access the courses offline?</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

<!-- wp:accordion {"style":{"spacing":{"margin":{"top":"0"}}}} -->
<div role="group" class="wp-block-accordion" style="margin-top:0"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"style":{"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"top":"24px","bottom":"24px"}},"color":{"text":"#9ca3af"},"elements":{"link":{"color":{"text":"#9ca3af"}}},"border":{"top":{"color":"#2b2d3b","width":"1px"},"right":[],"bottom":[],"left":[]}}} -->
<h3 class="wp-block-accordion-heading has-text-color has-link-color" style="border-top-color:#2b2d3b;border-top-width:1px;color:#9ca3af;font-size:24px;font-style:normal;font-weight:500"><button type="button" class="wp-block-accordion-heading__toggle" style="padding-top:24px;padding-bottom:24px"><span class="wp-block-accordion-heading__toggle-title">Is there a refund policy?</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-page --></div>
<!-- /wp:group -->
```
