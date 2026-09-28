# Product-Page Blocks — Curated Vocabulary

Use ONLY these blocks (or `core/*`) when emitting product-page markup. **Do not invent block names.** If the design needs something not in this list, search `reference/full-inventory.json` for an exact match, or fall back to `core/group` + `core/heading` + `core/paragraph` and log under "fallback" in the drift report.

Every `surecart/*` block listed below is `apiVersion: 3` and server-rendered — they MAY self-close (`<!-- wp:surecart/product-title /-->` is correct).

---

## Outer wrapper (mandatory, exactly one per page)

### `surecart/product-page`
The outer wrapper. Provides `surecart/product_id` context to all children. **Without it, every `surecart/product-*` child renders empty.** Wrap the entire pattern in this block. Carry the pattern `metadata` here and **only** here.

⚠️ **`apiVersion: 3` server-rendered. NO inner `<div>` wrapper.** Children sit between the comment markers as siblings. Hand-writing `<div class="wp-block-surecart-product-page">` becomes freeform HTML and breaks every child.

```html
<!-- wp:surecart/product-page {"metadata":{"name":"<title>","patternName":"<slug>"}} -->
  …all sections…
<!-- /wp:surecart/product-page -->
```

Attributes: `metadata.name` (string, the human title), `metadata.patternName` (string, the slug).

### Body-bg shim (v7.3 — canonical)

When the design CSS declares `body { background-color: #hex }` or has a body-level CSS var (e.g., `--bg-page: #f6f0e6`), emit it on the FIRST inner `core/group` of `surecart/product-page` — NOT on the SureCart product-page block (apiVersion 3, no save()), AND NOT under `dropped_features`. The body bg ALWAYS has a host: the inner `core/group`.

Pattern reference: `examples/patterns/product-physical.example.md:25-26`:

```html
<!-- wp:surecart/product-page {"metadata":...,"align":"full","layout":{"type":"constrained","contentSize":"1320px"}} -->
<!-- wp:group {"style":{"spacing":{"padding":{"left":"75px","top":"60px","bottom":"0","right":"75px"}},"color":{"background":"#f3f0ec"}},"layout":{"type":"default"}} -->
<div class="wp-block-group has-background" style="background-color:#f3f0ec;padding-top:60px;padding-right:75px;padding-bottom:0;padding-left:75px">
  …all sections…
</div>
<!-- /wp:group -->
<!-- /wp:surecart/product-page -->
```

The `core/group` shim carries `style.color.background` (literal hex matches the design's body bg) + section padding + `layout.type:"constrained"` (with `contentSize` if the design has a max-width). Sections inside this group inherit the cream background.

---

## Hero / Above-the-fold (use most of these)

### `surecart/product-title`
Renders the product's title. Self-closing.
- `level` (number, default 1) — heading level 1–6
- `isLink` (boolean, default false) — wrap in `<a>` to the product URL
- Supports: `color`, `spacing.padding/margin`, `typography.fontSize/fontFamily/fontWeight/textAlign`

```html
<!-- wp:surecart/product-title {"level":1,"style":{"typography":{"fontSize":"56px","fontWeight":"700","letterSpacing":"-0.02em"}}} /-->
```

### `surecart/product-description`
Renders the product description (HTML allowed). Self-closing. No attributes; supports `color`, `spacing`, `typography`.

```html
<!-- wp:surecart/product-description {"style":{"typography":{"fontSize":"18px","lineHeight":"1.6"}}} /-->
```

### `surecart/product-media`
Image gallery / slider for the product. Self-closing.
- `auto_height` (boolean, default true)
- `desktop_gallery` (boolean, default false) — false=slider, true=gallery grid
- `height` (string, default `"310px"`)
- `show_thumbnails` (boolean, default true)
- `thumbnails_per_page` (number, default 5)
- `lightbox` (boolean, default true)
- Variations: `slider` (default), `gallery`

```html
<!-- wp:surecart/product-media {"desktop_gallery":true,"lightbox":true} /-->
```

### `surecart/product-price-chooser`
Price selector — shows variant pricing. **Replaces `surecart/product-price` (which doesn't exist).** Self-closing.
- `label` (string, default `"Pricing"`)
- `columns` (number, default 1)

```html
<!-- wp:surecart/product-price-chooser {"label":"Choose your model","columns":1} /-->
```

### `surecart/product-variant-pills`
Displays product variants as pills (e.g., color/storage selector). **Replaces `surecart/variant-picker`.** Paired (with one `surecart/product-variant-pill` template child) OR self-closing for default chrome.

```html
<!-- wp:surecart/product-variant-pills -->
<!-- wp:surecart/product-variant-pill /-->
<!-- /wp:surecart/product-variant-pills -->
```

#### v7.3 — Highlight chrome via block attrs

When the design shows an active-pill state with custom border/text/background colors, configure them via the inner `surecart/product-variant-pill` block's own attrs (NO theme CSS needed):

- `highlight_text:"#hex"` — active label color
- `highlight_background:"#hex"` — active fill
- `highlight_border:"#hex"` — active border
- `style.border:{color:"#hex",width:"1px",style:"solid"}` — default-state border

Reference: `examples/patterns/product-physical.example.md:75`:

```html
<!-- wp:surecart/product-variant-pills {"style":{"color":{"text":"#28201b"},"elements":{"link":{"color":{"text":"#28201b"}}},"spacing":{"margin":{"top":"16px"}}}} -->
<!-- wp:surecart/product-variant-pill {"highlight_text":"#FFFFFF","highlight_background":"#8b4513","highlight_border":"#8b4513","style":{"elements":{"link":{"color":{"text":"#8b4513"}}},"color":{"text":"#8b4513"},"border":{"color":"#8b451340","width":"1px","style":"solid"}}} /-->
<!-- /wp:surecart/product-variant-pills -->
```

### `surecart/product-quantity`
Quantity selector. **Replaces `surecart/quantity`.** Self-closing.
- `label` (string, default `"Quantity"`)
- `hidden_label` (boolean, default false) — true to render the input only

```html
<!-- wp:surecart/product-quantity {"label":"Qty","hidden_label":false} /-->
```

### `surecart/product-buy-buttons` + `surecart/product-buy-button`
Buy buttons. **Always pair them — the inner button declares an `ancestor` constraint.** When the design has both Add to Cart and Buy Now, render TWO inner buttons IN THE SAME WRAPPER.

#### v7.3 — Canonical wrapper class set + mandatory `text` attr

The wrapper `<div>` MUST emit ALL FOUR classes (production patterns confirm — `examples/patterns/product-standard.example.md:103`, `product-physical.example.md:94`):
```
wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex
```

Every `surecart/product-buy-button` MUST carry a non-empty `text` attr. Self-closed `<!-- wp:surecart/product-buy-button /-->` (no attrs) is a Tier-A failure (rubric A-19) — recovery on paste strips attrs and the block re-renders with default labels.

When pairing Add-to-Cart + Buy-Now in the same wrapper:
- Primary: `{"add_to_cart":true,"text":"Add To Cart"}` — filled style, brand bg, white text (server-rendered).
- Secondary: `{"text":"Buy Now","className":"is-style-outline"}` — outline style, transparent bg, brand text + brand border. **NO inline border/radius/color on the secondary** — the registered `is-style-outline` block style handles all chrome.

Canonical exemplar (`product-standard.example.md:102-108`):
```html
<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"5px"}}} -->
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
    <!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add To Cart"} /-->
    <!-- wp:surecart/product-buy-button {"text":"Buy Now","className":"is-style-outline"} /-->
</div>
<!-- /wp:surecart/product-buy-buttons -->
```

#### v7.10 — Margin inline-mirror parity (Northwind paste-test correction)

When the wrapper JSON has `style.spacing.margin:{top:X,bottom:Y}` (e.g., to separate buy-buttons from sibling shipping/warranty microcopy below), the wrapper `<div>` MUST carry matching inline `style="margin-top:X;margin-bottom:Y"` — DESPITE this block being `apiVersion:3` server-rendered. Set-equality validation applies.

Omitting the inline mirror triggers:
```
Block validation failed for `surecart/product-buy-buttons`.
Generated: <div ... style="margin-top:0;margin-bottom:20px"></div>
Retrieved: <div ...></div>
```

**Scope of inline-mirror on the buy-buttons wrapper:**

| JSON path | Emit on wrapper inline? |
|---|---|
| `style.spacing.margin.{top|right|bottom|left}` | ✅ YES |
| `style.spacing.padding.{top|right|bottom|left}` | ✅ YES |
| `style.spacing.blockGap` | ❌ NO (CSS-var, never inline) |
| `style.color.*` | ❌ NO (skipSerialization — per buy-button only) |
| `style.border.*` | ❌ NO (skipSerialization — per buy-button only) |

Canonical exemplar with margin (v7.10):
```html
<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"10px","margin":{"top":"0","bottom":"20px"}}}} -->
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex" style="margin-top:0;margin-bottom:20px">
    <!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add to Cart"} /-->
    <!-- wp:surecart/product-buy-button {"text":"Buy Now","className":"is-style-outline"} /-->
</div>
<!-- /wp:surecart/product-buy-buttons -->
```

Wrapper class set (constant — server-determined): `wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex`. Only the inline `style=""` changes based on JSON `style.spacing.margin/padding`.

#### Inverse-styled buttons on dark/colored sections

For FinalCTA white-bg buttons on a brown/dark hero, etc., emit explicit `style.color.background:"#hex"` + `style.color.text:"#hex"` along with `text` — color skipSerialization means the colors land server-side via PHP render. Reference: `product-physical.example.md:99` (buy-button with `color.background:"#8b4513"` and `textColor:"white"`):

```html
<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add to Cart","style":{"color":{"text":"#9c6b3f","background":"#ffffff"},"border":{"radius":"8px"}}} /-->
```

`surecart/product-buy-button` attributes:
- `add_to_cart` (boolean, default false) — true=Add to Cart, false=Buy Now
- `text` (string)
- `out_of_stock_text` (string)
- `unavailable_text` (string)
- `width` (number, default 100)
- `show_sticky_purchase_button` (enum: `"never"|"in_stock"|"always"`, default `"never"`)

⚠️ **The button uses `__experimentalSkipSerialization` for spacing + color + border (only those three)** — write the attrs but **do NOT inject inline `style=""` or extra classnames on the inner `<a>`/`<button>` element for any of those three trees**. The block's server render handles spacing/color/border itself. If the design needs additional surrounding chrome, wrap children in a `core/group` that carries it.

✅ **Typography is NOT skipSerialization.** `block.json:54-60` declares `typography.fontSize`, `__experimentalFontFamily`, `__experimentalFontWeight`, `__experimentalFontStyle`, `__experimentalTextTransform` without skip — meaning the typography classes (`has-{slug}-font-family`, `has-custom-font-size`, etc.) DO land on the wrapper, and Gutenberg cascades them to the inner `<a>` via `__experimentalSelector: ".wp-block-button .wp-block-button__link"`. This is the correct path for setting button-label typography:

```html
<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add to Bag","fontFamily":"surecart-display","style":{"typography":{"fontSize":"16px","fontWeight":"700"}}} /-->
```

The Geist font (or whatever the design specifies) will land on button labels. Do NOT wrap in a `core/group` solely for typography — it's unnecessary and adds markup nesting. Per rubric **B-16**.

### Hero "selected price" vs price-chooser options — TWO DIFFERENT BLOCK FAMILIES

**Critical pattern (v6.0): when the design shows a BIG, PROMINENT price near the product title, that is `surecart/product-selected-price-*` blocks — NOT the `surecart/product-price-chooser`.**

The `surecart/product-price-chooser` is a chooser/picker (storage options grid, plan tiers, monthly/yearly toggle) — it shows the LIST of price options the merchant can offer. Its `price-*` children render labels for each individual option.

The `surecart/product-selected-price-*` family shows the CURRENTLY SELECTED price prominently (typically near product title or buy buttons). After a customer picks "256 GB" in the chooser, the big "$1,099" elsewhere on the page is `product-selected-price-amount`.

**The 6 selected-price blocks** (use these for the hero price display):

| Block | Purpose | Typical placement |
|---|---|---|
| `surecart/product-selected-price-amount` | THE BIG dollar number | hero, near `surecart/product-title` |
| `surecart/product-selected-price-interval` | "/mo", "/yr", "one-time" suffix | inline with selected-price-amount |
| `surecart/product-selected-price-scratch-amount` | strike-through "was" price | before selected-price-amount |
| `surecart/product-selected-price-trial` | "14-day trial" line | below price line |
| `surecart/product-selected-price-fees` | setup fees / processing fees | below price line, often beside trial |
| `surecart/product-selected-price-ad-hoc-amount` | "name your price" input — for pay-what-you-want pricing | only when design has it |

**Canonical hero price block** (extracted from `templates/parts/product-info.html`):
```html
<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"bottom"}} -->
<div class="wp-block-group">
  <!-- wp:surecart/product-selected-price-scratch-amount {"style":{"typography":{"textDecoration":"line-through","fontSize":"24px"},"color":{"text":"#686868"}}} /-->
  <!-- wp:surecart/product-selected-price-amount {"style":{"typography":{"fontSize":"44px","fontWeight":"700","letterSpacing":"-0.03em"},"color":{"text":"#111827"}},"fontFamily":"surecart-display"} /-->
  <!-- wp:surecart/product-selected-price-interval {"style":{"typography":{"fontSize":"16px","lineHeight":"2"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
  <!-- wp:surecart/product-selected-price-trial {"style":{"typography":{"fontSize":"14px"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
  <!-- wp:surecart/product-selected-price-fees {"style":{"typography":{"fontSize":"14px"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
</div>
<!-- /wp:group -->
```

**Decision tree (when emitting a price-related design element):**

1. **Is this an OPTIONS GRID** (multiple buyable price tiers/storage sizes/plans shown as siblings)?
   → Use `surecart/product-price-chooser` with its `price-*` children (small text per option)
2. **Is this a SINGLE prominent price** showing the user's current selection?
   → Use `surecart/product-selected-price-amount` + `selected-price-interval` (big hero text)
3. **Both?** (Most product templates have BOTH: hero selected-price near title AND chooser below)
   → Emit both. Hero goes near product-title; chooser goes after variant pills.

**iPhone fixture pattern (post-correction):**
- Near title: `product-selected-price-amount` (44px) + `product-selected-price-interval` (16px) — "$1,099 or $45.79/mo" hero line
- Below variant pills: `product-price-chooser` with 3 columns of `price-name` (storage label "256 GB") + `price-amount` (small) per option

The chooser's per-option `price-amount` children should be **smaller** (16-24px) than the hero `product-selected-price-amount` (44px). These are different visual roles serving different purposes.

### `surecart/product-price-chooser` is atomic — no merchant child blocks

Despite emitting markup that looks like a parent + children template (`product-price-choice-template` + `price-name` + `price-amount` etc.), `surecart/product-price-chooser`'s `block.json` declares NO `template`, NO `allowedBlocks`, and only 2 attrs (`label`, `columns`). The inner template is server-rendered. Same applies to `surecart/product-quantity` and `surecart/product-buy-buttons` (the latter does allow `surecart/product-buy-button` as a child via `ancestor` — that IS authored).

What this means for the skill: when the design shows typography on the product price (e.g., 44px Geist with -0.03em letter-spacing), set those attrs on `surecart/product-price-chooser` itself. The price-amount/price-name children inside the comment markers also support their own typography attrs and can carry per-element overrides if the design demands it — but the typical case is a single set of attrs on the chooser.

### `surecart/product-image`
Single product image (alternative to media gallery). Self-closing. Less commonly used than `product-media`.

---

## Trust / Detail signals

### `surecart/product-review-summary`
Summary of average rating + count. Container block — paired with an inner template, OR self-closing for the default expanded summary card chrome (gray bg, breakdown bars, "Write a review" CTA).

#### v7.3 — Inline-vs-card fork (read carefully)

There are TWO distinct review-summary visual archetypes in product-page designs. Choose the right one:

**Inline review chrome** — single horizontal line: `★★★★★ 4.9 · 1,284 reviews`. Used in 90% of hero designs (compact, near the product title). DO NOT emit `<!-- wp:surecart/product-review-summary /-->` — that renders the expanded card. Instead emit a `core/group` flex containing the two atomic siblings:

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"10px","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
    <!-- wp:surecart/product-review-average-rating-stars /-->
    <!-- wp:surecart/product-review-total-rating {"style":{"spacing":{"blockGap":"4px"}}} /-->
</div>
<!-- /wp:group -->
```

**HC#19 v7.15 correction:** OMIT `className` on `product-review-total-rating` — `plus-sign` is `isDefault:true` per `block.json#styles`, so emitting `className:"is-style-plus-sign"` triggers a round-trip class-set rewrite during save() and causes recovery. Bare emission produces the default plus-sign chrome correctly. Only emit `className:"is-style-default"` for the explicit dot-prefix variant. Reference: `examples/patterns/product-standard.example.md:35-41`.

**Expanded summary card** — the larger card with rating value + stars + breakdown bars (5★/4★/3★/2★/1★ progress bars) + "Write a review" CTA. Used in dedicated reviews sections below the hero. THIS is when you emit the paired `surecart/product-review-summary` block with its inner template.

**Decision tree:**
- Single horizontal line near product title → inline `core/group` flex pair
- Card with breakdown bars → paired `surecart/product-review-summary` with template

The bare self-closed `<!-- wp:surecart/product-review-summary /-->` should almost never appear in hero output. It's reserved for the rare design that wants the default card chrome unmodified.

### `surecart/product-review-average-rating-stars`
Just the star icons. Self-closing. Used in the inline review chrome pattern (v7.3) AND inside the expanded summary card.

### `surecart/product-review-total-rating`
Total review count display ("(1,284 reviews)"). Self-closing.
- (default, no `className` needed) — `plus-sign` is `isDefault:true` so the "+" / "·" divider chrome renders automatically; per HC#19 v7.15 do NOT emit `className:"is-style-plus-sign"` (round-trip rewrite triggers recovery)

### `surecart/product-review-average-rating-value`
Numeric rating value. Self-closing.

### `surecart/product-review-list`
Full list of reviews. **Paired container — MUST emit the full Start-Basic inner template** (v7.8 reversal of prior v7.x self-close doctrine).

**Why:** when emitted self-closed (`<!-- wp:surecart/product-review-list /-->`), the editor shows a *"Start Basic / Select a template"* placeholder picker instead of rendering the reviews. The merchant has to manually click "Start Basic" in the editor for each instance — exactly what the skill should automate. Empirically observed in Loom & Ash paste-test (2026-05-11). The placeholder is silent (no block-recovery error), so prior paste-tests missed it.

**Canonical Start-Basic template** (paste verbatim; tweak typography/spacing to match design):

```html
<!-- wp:surecart/product-review-list -->
<!-- wp:heading {"className":"wp-block-heading","style":{"spacing":{"margin":{"top":"32px","bottom":"32px"}},"typography":{"lineHeight":"1"}}} -->
<h2 class="wp-block-heading" style="margin-top:32px;margin-bottom:32px;line-height:1">Customer Reviews</h2>
<!-- /wp:heading -->

<!-- wp:surecart/product-reviews -->
<!-- wp:surecart/product-review-summary {"style":{"spacing":{"margin":{"bottom":"15px"}}}} -->
<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"20px"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"280px"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:280px"><!-- wp:group {"style":{"spacing":{"blockGap":"14px","padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:group {"style":{"spacing":{"blockGap":"5px","padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:surecart/product-review-average-rating-value {"className":"is-style-none","style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1","fontSize":"24px"}}} /-->

<!-- wp:paragraph {"metadata":{"name":"/ 5.0"},"style":{"typography":{"lineHeight":"1.5","fontSize":"14px"},"color":{"text":"#4b5563"},"elements":{"link":{"color":{"text":"#4b5563"}}},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}}} -->
<p class="has-text-color has-link-color" style="color:#4b5563;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px;line-height:1.5">/ 5.0</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-review-average-rating-stars /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"5px","padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:paragraph {"metadata":{"name":"Based on"},"style":{"typography":{"fontSize":"14px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}}} -->
<p style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:14px">Based on</p>
<!-- /wp:paragraph -->

<!-- wp:surecart/product-review-total-rating {"link_to_reviews":false,"className":"is-style-default","style":{"spacing":{"blockGap":"4px","margin":{"right":"0","left":"0"},"padding":{"right":"0","left":"0"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:surecart/product-review-breakdown {"columns":2,"className":"is-style-default","layout":{"type":"flex","justifyContent":"left","orientation":"horizontal","verticalAlignment":"center"}} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:surecart/product-review-summary -->

<!-- wp:group {"metadata":{"name":"Header"},"style":{"spacing":{"margin":{"bottom":"10px"},"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="margin-bottom:10px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:surecart/product-review-list-sidebar-toggle {"label":"Filters"} /-->

<!-- wp:surecart/product-review-add-button {"width":100,"className":"is-style-fill","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"surecart","textColor":"white"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:surecart/product-review-list-sidebar {"style":{"layout":{"selfStretch":"fixed","flexSize":"225px"},"position":{"type":"sticky","top":"0px"},"spacing":{"blockGap":"30px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<!-- wp:surecart/product-review-list-filter-tags {"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"top","flexWrap":"nowrap"}} -->
<!-- wp:surecart/product-review-list-filter-tags-label {"style":{"typography":{"fontWeight":"700","fontStyle":"normal","fontSize":"16px"}}} /-->

<!-- wp:surecart/product-review-list-filter-tags-template {"style":{"spacing":{"blockGap":"8px"},"typography":{"fontSize":"16px"}},"layout":{"type":"flex","orientation":"horizontal"}} -->
<!-- wp:surecart/product-review-list-filter-tag {"style":{"typography":{"fontSize":"14px"}}} /-->
<!-- /wp:surecart/product-review-list-filter-tags-template -->

<!-- wp:surecart/product-review-list-filter-tags-clear-all {"style":{"typography":{"textDecoration":"underline","fontWeight":"700","fontStyle":"normal"}},"fontSize":"small"} /-->
<!-- /wp:surecart/product-review-list-filter-tags -->

<!-- wp:surecart/product-review-list-filter-checkboxes {"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"top","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"8px"}}} -->
<!-- wp:surecart/product-review-list-filter-checkboxes-label {"style":{"typography":{"fontWeight":"700","fontStyle":"normal","fontSize":"16px"}}} /-->

<!-- wp:surecart/product-review-list-filter-checkboxes-template {"style":{"spacing":{"blockGap":"6px","margin":{"top":"0","bottom":"0"}},"typography":{"fontSize":"16px"}}} -->
<!-- wp:surecart/product-review-list-filter-checkbox {"style":{"typography":{"fontSize":"16px"}}} /-->
<!-- /wp:surecart/product-review-list-filter-checkboxes-template -->
<!-- /wp:surecart/product-review-list-filter-checkboxes -->
<!-- /wp:surecart/product-review-list-sidebar -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0px","padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:surecart/product-review-template {"style":{"spacing":{"blockGap":"0px","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"grid","columnCount":1}} -->
<!-- wp:group {"style":{"spacing":{"blockGap":"8px","padding":{"top":"24px","bottom":"24px","right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}},"border":{"bottom":{"color":"#e5e7eb","width":"1px"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="border-bottom-color:#e5e7eb;border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:24px;padding-right:0px;padding-bottom:24px;padding-left:0px"><!-- wp:group {"className":"sc-review-header-group","style":{"spacing":{"margin":{"top":"0","bottom":"16px"},"padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group sc-review-header-group" style="margin-top:0;margin-bottom:16px;padding-right:0px;padding-left:0px"><!-- wp:group {"style":{"spacing":{"blockGap":"8px","padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:surecart/product-review-reviewer-name {"style":{"spacing":{"padding":{"top":"0","bottom":"0"},"margin":{"right":"8px"}},"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"16px"}}} /-->

<!-- wp:surecart/product-review-verified-badge {"label":"Verified Buyer","style":{"typography":{"fontStyle":"normal","fontWeight":"400","fontSize":"16px"},"spacing":{"blockGap":"4px"},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","justifyContent":"center","verticalAlignment":"center","orientation":"horizontal"}} /--></div>
<!-- /wp:group -->

<!-- wp:surecart/product-review-date {"datetime":"2026-01-01T09:37:00.225Z","format":"human-diff","style":{"typography":{"fontSize":"14px"}}} /--></div>
<!-- /wp:group -->

<!-- wp:surecart/product-review-rating-stars {"style":{"spacing":{"margin":{"bottom":"16px"}}}} /-->

<!-- wp:surecart/product-review-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"18px"},"spacing":{"margin":{"bottom":"8px"}}}} /-->

<!-- wp:surecart/product-review-content {"style":{"typography":{"fontSize":"16px"}}} /--></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-review-template -->

<!-- wp:surecart/product-review-pagination {"style":{"spacing":{"margin":{"top":"30px","bottom":"30px"}}}} -->
<!-- wp:surecart/product-review-pagination-previous /-->

<!-- wp:surecart/product-review-pagination-numbers /-->

<!-- wp:surecart/product-review-pagination-next /-->
<!-- /wp:surecart/product-review-pagination --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-reviews -->

<!-- wp:surecart/product-review-list-no-reviews -->
<!-- wp:paragraph {"align":"left","placeholder":"Add text or blocks that will display when a query returns no reviews."} -->
<p class="has-text-align-left">No reviews yet.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"padding":{"right":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0px;padding-left:0px"><!-- wp:surecart/product-review-add-button {"width":100,"className":"is-style-fill","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"surecart","textColor":"white"} /--></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-review-list-no-reviews -->
<!-- /wp:surecart/product-review-list -->
```

The merchant can subsequently customize the `Customer Reviews` heading, the review-template card layout, or the no-reviews fallback. Do NOT self-close.

### `surecart/product-collection-tags` + `surecart/product-collection-tag`
Product tags (often shown as chips below the title). Both blocks are `apiVersion: 3` server-rendered — **no inner `<div>` wrapper**:

```html
<!-- wp:surecart/product-collection-tags -->
<!-- wp:surecart/product-collection-tag /-->
<!-- /wp:surecart/product-collection-tags -->
```

### `surecart/product-sale-badge`
"Sale" / discount badge. Self-closing.

### `surecart/product-scratch-price`
Strike-through original price (when on sale). Self-closing.

### `surecart/product-selected-variant`
Shows the currently-selected variant name. Self-closing.

---

## Related products section

### `surecart/product-list-related`
Renders related products. **Replaces `surecart/product-list` for related-context.** **Paired container — MUST emit the full Start-Basic inner template** (v7.8 reversal of prior v7.x self-close doctrine).

**Why:** when emitted self-closed (`<!-- wp:surecart/product-list-related /-->`), the editor shows a *"Start Basic / Select a template"* placeholder picker instead of rendering the products. The merchant has to manually click "Start Basic" in the editor for each instance. Empirically observed in Loom & Ash paste-test (2026-05-11). The placeholder is silent (no block-recovery error).

**Canonical Start-Basic template** (paste verbatim; tweak `columnCount`, typography, or quick-view styling to match design):

```html
<!-- wp:surecart/product-list-related {"limit":null} -->
<!-- wp:surecart/product-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"grid","columnCount":4}} -->
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"color":{"background":"#0000000d"},"border":{"radius":"10px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;background-color:#0000000d;margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top center","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-center" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:surecart/product-quick-view-button {"style":{"typography":{"fontSize":"12px","textAlign":"center","fontStyle":"normal","fontWeight":"500"},"spacing":{"padding":{"left":"10px","right":"10px","top":"10px","bottom":"10px"}},"border":{"radius":"100px"}}} /-->

<!-- wp:surecart/product-sale-badge {"style":{"typography":{"fontSize":"12px"},"border":{"radius":"100px"},"layout":{"selfStretch":"fit","flexSize":null}}} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"bottom":"5px","top":"0px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"}},"margin":{"top":"0px","bottom":"0px"},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;line-height:1"><!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /-->

<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:surecart/product-template -->

<!-- wp:surecart/product-pagination -->
<!-- wp:surecart/product-pagination-previous /-->

<!-- wp:surecart/product-pagination-numbers /-->

<!-- wp:surecart/product-pagination-next /-->
<!-- /wp:surecart/product-pagination -->
<!-- /wp:surecart/product-list-related -->
```

For a generic product grid (not "related"), use `surecart/product-list` directly with the same inner template shape — but on a single-product page, `product-list-related` is almost always what you want. Do NOT self-close.

---

## Cart elements (only if design has a cart drawer / mini-cart)

### `surecart/cart-icon`
Cart icon with count badge. Self-closing.

### `surecart/cart-count`
Just the item count number. Self-closing.

---

## Layout (always `core/*`, never `surecart/*`)

For all layout primitives, use WordPress core blocks. The skill's `reference/core-blocks-cheatsheet.md` has the paired-block exemplars. Quick reference:

| Need | Use |
|---|---|
| Section container with padding/background | `core/group` |
| 2/3/4-column grid | `core/columns` + N × `core/column` |
| Image left + text right | `core/media-text` |
| FAQ / accordion | `core/details` (one per item, repeated) |
| Heading | `core/heading` |
| Body text | `core/paragraph` |
| Image (non-product) | `core/image` |
| CTA button (non-product) | `core/buttons` + `core/button` |
| Bullet list | `core/list` + `core/list-item` |
| Vertical space | `core/spacer` |
| Horizontal divider | `core/separator` |

---

## Decision tree

```
Is the data product-driven (varies per product)?
├─ YES → use a surecart/product-* block from this list
│  └─ If no exact match exists → search reference/full-inventory.json,
│     else fall back to core/* + log "fallback"
└─ NO → use a core/* block
```

Common JSX → block mappings:

| JSX intent | Block |
|---|---|
| Product name (`<h1>` near top) | `surecart/product-title` |
| Long product description / specs blob | `surecart/product-description` |
| Image carousel of product photos | `surecart/product-media` |
| Color/storage variant picker | `surecart/product-variant-pills` |
| Price display | `surecart/product-price-chooser` |
| Quantity input | `surecart/product-quantity` |
| "Add to Cart" + "Buy Now" buttons | `surecart/product-buy-buttons` (paired wrapper) + 2 × `surecart/product-buy-button` |
| Star rating | `surecart/product-review-summary` |
| "Related products" rail | `surecart/product-list-related` |
| Hero section background | `core/group` with `style.spacing.padding` + `backgroundColor` |
| Highlights grid (4-col) | `core/columns` + 4 × `core/column` |
| FAQ section | repeated `core/details` |
| Specs table | `core/list` (`<dl>`-style) or `core/group` with `core/columns` per row |
| Final CTA banner | `core/group` wrapping `core/heading` + `core/paragraph` + `surecart/product-buy-buttons` |

---

## Cart chrome & order bumps (43 blocks)

When the design includes a slide-out cart drawer, sticky cart icon, or upsell-in-cart pattern (~80% of modern e-commerce designs), use these blocks. Without them, the skill falls back to `core/group` placeholders that don't function as a real cart.

### Slide-out cart drawer (12 blocks)

| Block | Purpose |
|---|---|
| `surecart/slide-out-cart` | Drawer wrapper (typically right-edge, ~400px wide) |
| `surecart/slide-out-cart-header` | Top bar with title + close button |
| `surecart/slide-out-cart-items` | Scroll region containing line items |
| `surecart/slide-out-cart-line-items` | Template loop for each cart line item |
| `surecart/slide-out-cart-bump-line-item` | Order bump display inside cart |
| `surecart/slide-out-cart-message` | Notice / promo message |
| `surecart/slide-out-cart-coupon` | Coupon code input |
| `surecart/slide-out-cart-subtotal` | Subtotal line (label + amount) |
| `surecart/slide-out-cart-items-subtotal` | Per-items subtotal |
| `surecart/slide-out-cart-submit` | "Checkout" button |
| `surecart/slide-out-cart-items-submit` | Per-items submit (for split carts) |
| `surecart/cart-close-button` | Close icon button |

### Cart line item subblocks (10)

Each cart line item is composed of these granular pieces. Use them inside `surecart/slide-out-cart-line-items` template loop.

| Block | Purpose |
|---|---|
| `surecart/cart-line-item-image` | Product thumbnail (typically 80×80px) |
| `surecart/cart-line-item-title` | Product name |
| `surecart/cart-line-item-variant` | Variant chip (e.g., "Black, 256 GB") |
| `surecart/cart-line-item-quantity` | Quantity stepper |
| `surecart/cart-line-item-amount` | Line price |
| `surecart/cart-line-item-scratch-amount` | Strike-through "was" line price |
| `surecart/cart-line-item-interval` | "/mo" suffix |
| `surecart/cart-line-item-trial` | Trial badge |
| `surecart/cart-line-item-fees` | Setup fees note |
| `surecart/cart-line-item-note` | Customer note input |
| `surecart/cart-line-item-status` | Stock / availability status |
| `surecart/cart-line-item-remove` | "Remove" link/button |

### Order bumps / cart upsells (13)

Show alternative or complementary products inside the cart drawer.

| Block | Purpose |
|---|---|
| `surecart/cart-order-bumps` | Container (carousel or stack) |
| `surecart/cart-order-bump-template` | Per-bump template loop |
| `surecart/cart-order-bump-title` | Bump title |
| `surecart/cart-order-bump-image` | Bump image |
| `surecart/cart-order-bump-description` | Short description |
| `surecart/cart-order-bump-amount` | Bump price |
| `surecart/cart-order-bump-scratch-amount` | Bump strike-through price |
| `surecart/cart-order-bump-cta` | "Add this" button |
| `surecart/cart-order-bump-add-button` | Inline add button |
| `surecart/cart-order-bump-discount-badge` | Discount chip |
| `surecart/cart-order-bump-pagination` | Pagination wrapper |
| `surecart/cart-order-bump-pagination-next` | Next button |
| `surecart/cart-order-bump-pagination-previous` | Previous button |

### Cart icon / menu (3)

| Block | Purpose |
|---|---|
| `surecart/cart-icon` | Standalone cart icon |
| `surecart/cart-icon-button` | Cart icon with click handler (opens drawer) |
| `surecart/cart-menu-icon-button` | Cart icon for menu bar (with badge count) |
| `surecart/cart-count` | Item count badge |

### Sticky purchase bar (1 + composition)

`surecart/sticky-purchase` is the dedicated block for the bottom-floating add-to-bag bar that appears when user scrolls past hero. Composes: image (left) + title/price (center) + add-to-bag button (right). Use `style.position.type:"sticky"` on the wrapping `core/group` per `style-conversion.md`.

---

## Reviews & social proof (15 blocks)

Real product pages typically include a full review section: rating summary at top, distribution chart, list of reviews with filters, and a submission form. The skill currently has only the rating summary; document the full set.

### Reviews container & summary (5)

| Block | Purpose |
|---|---|
| `surecart/product-reviews` | Outer container for the reviews section |
| `surecart/product-review-summary` | Top summary block (avg + total) |
| `surecart/product-review-average-rating-stars` | Just the visual stars |
| `surecart/product-review-average-rating-value` | Numeric value (e.g., "4.9") |
| `surecart/product-review-total-rating` | "(2,847 reviews)" count |
| `surecart/product-review-breakdown` | 5/4/3/2/1-star distribution chart |

### Review submission form (5)

| Block | Purpose |
|---|---|
| `surecart/product-review-add-button` | "Write a Review" CTA |
| `surecart/product-review-form` | Form wrapper (modal or inline) |
| `surecart/product-review-form-rating` | Star picker input |
| `surecart/product-review-form-title` | Review title input |
| `surecart/product-review-form-content` | Body input |
| `surecart/product-review-form-submit-button` | Submit button |

### Review list display (5)

| Block | Purpose |
|---|---|
| `surecart/product-review-list` | List/template container |
| `surecart/product-review-list-filter-checkboxes` | Star-rating filter |
| `surecart/product-review-list-filter-tags` | Tag-based filters with applied chips |
| `surecart/product-review-list-filter-tags-clear-all` | Clear-filters link |
| `surecart/product-review-list-sidebar` | Sticky filter sidebar (typically 280px) |
| `surecart/product-review-list-sidebar-toggle` | Mobile filter-toggle button |
| `surecart/product-review-pagination` (+ `-previous` + `-next` + `-numbers`) | Paged navigation |
| `surecart/product-review-list-no-reviews` | Empty state (paired — emit a `core/paragraph` fallback inside) |

### Per-review fields (4)

Inside the list template, each review composes:

| Block | Purpose |
|---|---|
| `surecart/product-review-rating-stars` | Per-review star count |
| `surecart/product-review-reviewer-name` | "Sarah M." |
| `surecart/product-review-verified-badge` | "Verified buyer" chip |
| (review title and content render via core/heading + core/paragraph children inside the list template) | |

---

## Product list / discovery (24 blocks)

When the design includes a catalog grid, search, sort, or filters beyond just "related products", use these blocks.

### Container (4)

| Block | Purpose |
|---|---|
| `surecart/product-list` | Main grid wrapper |
| `surecart/product-list-content` | Inner content area |
| `surecart/product-template` | Per-card template loop |
| `surecart/product-template-container` | Optional wrapper for template-level styling |

### Filters & sort (8)

| Block | Purpose |
|---|---|
| `surecart/product-list-search` | Search input |
| `surecart/product-list-sort` | Sort dropdown |
| `surecart/product-list-sort-radio-group` | Sort radio variant |
| `surecart/product-list-filter` | Generic filter wrapper |
| `surecart/product-list-filter-checkboxes` | Multi-select checkbox filter |
| `surecart/product-list-filter-tags` | Applied filter chips |
| `surecart/product-list-filter-tags-clear-all` | Clear-all link |
| `surecart/product-list-filter-tag` | Individual tag chip |
| `surecart/product-list-sidebar` | Sticky filter sidebar |
| `surecart/product-list-sidebar-toggle` | Mobile sidebar toggle |
| `surecart/product-list-no-products` | Empty state |

### Pagination (3) — canonical block names

| Block | Purpose |
|---|---|
| `surecart/product-pagination` (paired) | Pagination wrapper |
| `surecart/product-pagination-previous` | Prev arrow (self-close) |
| `surecart/product-pagination-numbers` | Page numbers (self-close) |
| `surecart/product-pagination-next` | Next arrow (self-close) |

> The names above are canonical (verified against `packages/blocks-next/src/blocks/product-pagination/block.json`). Earlier drafts of this doc used `surecart/product-list-pagination` — that name is INCORRECT and does not exist in the inventory.

### Per-card composition (in product-template)

Each card composes: cover image (often `core/cover` with `useFeaturedImage:true`) + product-quick-view-button + product-sale-badge + product-title + product-review-average-rating-stars + product-list-price + product-scratch-price.

---

## Quick view (3)

| Block | Purpose |
|---|---|
| `surecart/product-quick-view` | Modal wrapper (~500px centered) |
| `surecart/product-quick-view-button` | Trigger button (typically inside product card) |
| `surecart/product-quick-view-close` | Modal close button |

Inside the quick-view modal: product-selected-variant-image (120×120) + product-title + product-selected-variant + selected-price cluster + variant-pills + price-chooser + buy-buttons + close button.

---

## Variants & selected variant (3)

| Block | Purpose |
|---|---|
| `surecart/product-variant-pills` | Variant picker (storage, color, size) |
| `surecart/product-variant-pill` | Individual pill (template child) |
| `surecart/product-selected-variant` | Display "Selected: Black, 256 GB" string |
| `surecart/product-selected-variant-image` | Image that updates when user picks variant |

---

## Currency, icon, misc (3)

| Block | Purpose |
|---|---|
| `surecart/currency-switcher` | Multi-currency toggle (header) |
| `surecart/icon` | Generic icon block (use sparingly; prefer `core/image` for design-mock icons) |
| `surecart/product-line-item-note` | Customer note input on add-to-cart |

---

# v7.2.0 — vocabulary expansion from production patterns

The 25 blocks above are the curated **product-page** vocabulary. Auditing the 20 production block-pattern files in the SureCart plugin's own pattern library surfaced ~40 additional blocks the skill must recognize — even when they don't directly appear in product-page output, they appear in adjacent patterns (cart, reviews, lists, sidebars) that the skill may need to reference or extend.

## Product list / grid family (paired & template blocks)

| Block | Form | Use case |
|---|---|---|
| `surecart/product-list` | paired | Outer collection wrapper for a product grid page (`list-standard`, `list-bento`, `list-row`, `list-staggered`, `list-carousel`) |
| `surecart/product-list-related` | paired | Related-products grid below a product page (`related-carousel*`) |
| `surecart/product-list-content` | paired | Main results pane inside a sidebar layout (`list-sidebar`) |
| `surecart/product-list-sidebar` | paired | Sticky filter column inside a sidebar layout (`list-sidebar`) — `position:sticky, top:0` |
| `surecart/product-list-sidebar-toggle` | self-close | Show/hide-sidebar button |
| `surecart/product-template` | paired | Repeats once per product. Inner structure is the card. Layout `type:"grid"` with `columnCount` or `minimumColumnWidth` |
| `surecart/product-template-container` | paired | Container that wraps `product-template` in sidebar layouts |
| `surecart/product-list-no-products` | paired | Fallback content when query yields zero results |
| `surecart/product-list-search` | self-close | Search input |
| `surecart/product-list-sort` | self-close | Default dropdown sort |
| `surecart/product-list-sort-radio-group` | paired | Sort options as radios |
| `surecart/product-list-sort-radio-group-template` | paired | Template for each sort option |
| `surecart/product-list-sort-radio-group-label` | self-close | Sort group heading |
| `surecart/product-list-sort-radio` | self-close | Single sort option |
| `surecart/product-list-filter` | self-close | Generic filter button |
| `surecart/product-list-filter-tags` | paired | Tag-based filter group |
| `surecart/product-list-filter-tags-template` | paired | Template for each tag |
| `surecart/product-list-filter-tags-label` | self-close | Tag filter heading |
| `surecart/product-list-filter-tag` | self-close | Single tag pill |
| `surecart/product-list-filter-tags-clear-all` | self-close | "Clear all" button |
| `surecart/product-list-filter-checkboxes` | paired | Checkbox filter group (price ranges, attributes) |
| `surecart/product-list-filter-checkboxes-template` | paired | Template for each checkbox |
| `surecart/product-list-filter-checkboxes-label` | self-close | Checkbox group heading |
| `surecart/product-list-filter-checkbox` | self-close | Single checkbox |
| `surecart/product-pagination` | paired | Pagination wrapper |
| `surecart/product-pagination-previous` | self-close | Prev arrow |
| `surecart/product-pagination-next` | self-close | Next arrow |
| `surecart/product-pagination-numbers` | self-close | Page numbers |
| `surecart/product-list-price` | self-close | Price display in product cards |
| `surecart/product-scratch-price` | self-close | Strikethrough/sale price in cards |
| `surecart/product-quick-view-button` | self-close | "Quick View" trigger on product cards |

See `examples/patterns/list-standard.example.md`, `list-sidebar.example.md`, `list-bento.example.md`, `list-staggered.example.md` for canonical usage.

## Quick view family (modal product card)

| Block | Form | Use case |
|---|---|---|
| `surecart/product-quick-view` | paired | Modal/overlay product card wrapper. `alignment:"center center"`, `width:"500px"` |
| `surecart/product-quick-view-close` | self-close | Modal close button (X) |
| `surecart/product-selected-variant-image` | self-close | Image that swaps when user picks a variant — used in quick-view AND sticky-purchase. Carries `aspectRatio`, `width`, `height`, `hide_on_mobile` |
| `surecart/product-selected-variant` | self-close | Text label of currently-selected variant ("Black • Large") |

See `examples/patterns/product-quick-view.example.md`.

## Sticky purchase

| Block | Form | Use case |
|---|---|---|
| `surecart/sticky-purchase` | paired | Sticky horizontal purchase bar wrapper. Carries layout (flex, horizontal, nowrap, space-between, wideSize:full) |

See `examples/patterns/sticky-purchase.example.md`.

## Quantity stepper anatomy

| Block | Form | Use case |
|---|---|---|
| `surecart/product-quantity` | self-close OR paired | Default form is self-closed (renders SureCart's stock stepper). Paired form lets you override with custom children. |
| `surecart/product-quantity-control` | paired | Custom stepper container. Carries border styling. |
| `surecart/product-quantity-input-decrease` | self-close | Custom minus button |
| `surecart/product-quantity-input` | self-close | Number input |
| `surecart/product-quantity-input-increase` | self-close | Custom plus button |

See `examples/patterns/product-physical.example.md` (lines 69–76) for paired form.

## Review family (`product-review-list` and children — 28 blocks)

| Block | Form | Use case |
|---|---|---|
| `surecart/product-review-list` | paired | Outer reviews-page wrapper |
| `surecart/product-reviews` | paired | Reviews container with summary + filter + template |
| `surecart/product-review-summary` | paired | Top summary card (rating value + stars + total + breakdown) |
| `surecart/product-review-average-rating-value` | self-close | Numeric rating ("4.8") |
| `surecart/product-review-average-rating-stars` | self-close | Average star icons (read-only) |
| `surecart/product-review-total-rating` | self-close | "1,234 reviews" total count |
| `surecart/product-review-breakdown` | self-close | Bar-chart breakdown by star count |
| `surecart/product-review-add-button` | self-close | "Write a review" button (opens modal) |
| `surecart/product-review-list-sidebar` | paired | Sticky filter sidebar (rating-stars + tags + checkboxes) |
| `surecart/product-review-list-sidebar-toggle` | self-close | Show/hide-sidebar button |
| `surecart/product-review-list-filter-tags` | paired | Tag filter group |
| `surecart/product-review-list-filter-tags-template` | paired | Template for each tag |
| `surecart/product-review-list-filter-tags-label` | self-close | Tag filter heading |
| `surecart/product-review-list-filter-tag` | self-close | Single tag pill |
| `surecart/product-review-list-filter-tags-clear-all` | self-close | "Clear" button |
| `surecart/product-review-list-filter-checkboxes` | paired | Rating-stars-as-checkboxes filter group |
| `surecart/product-review-list-filter-checkboxes-template` | paired | Template for each checkbox |
| `surecart/product-review-list-filter-checkboxes-label` | self-close | Checkbox group heading |
| `surecart/product-review-list-filter-checkbox` | self-close | Single checkbox |
| `surecart/product-review-list-no-reviews` | paired | Fallback for empty results |
| `surecart/product-review-template` | paired | Repeats once per review. Inner = single review card |
| `surecart/product-review-reviewer-name` | self-close | Author name |
| `surecart/product-review-verified-badge` | self-close | "Verified buyer" badge |
| `surecart/product-review-rating-stars` | self-close | Per-review stars |
| `surecart/product-review-title` | self-close | Review headline |
| `surecart/product-review-content` | self-close | Review body |
| `surecart/product-review-date` | self-close | Posted date |
| `surecart/product-review-pagination` | paired | Pagination wrapper |
| `surecart/product-review-pagination-previous` | self-close | Prev |
| `surecart/product-review-pagination-numbers` | self-close | Page numbers |
| `surecart/product-review-pagination-next` | self-close | Next |

See `examples/patterns/product-review-standard.example.md`.

## Cart family (`slide-out-cart` and children — 30+ blocks)

| Block | Form | Use case |
|---|---|---|
| `surecart/slide-out-cart` | paired | Outer slide-out cart wrapper |
| `surecart/slide-out-cart-line-items` | paired | Scrollable line items section. **Only block in the library that carries `metadata.ignoredHookedBlocks:["surecart/cart-line-item-divider"]`** |
| `surecart/slide-out-cart-items-subtotal` | paired | Subtotal section |
| `surecart/slide-out-cart-items-submit` | self-close | "Checkout" button at the bottom |
| `surecart/cart-close-button` | self-close | X button to close the drawer |
| `surecart/cart-count` | self-close | "(3 items)" badge in header |
| `surecart/cart-line-item-image` | self-close | Product thumbnail |
| `surecart/cart-line-item-title` | self-close | Product name |
| `surecart/cart-line-item-price-name` | self-close | Price tier name |
| `surecart/cart-line-item-variant` | self-close | Variant label ("Black • Large") |
| `surecart/cart-line-item-note` | self-close | Customer note text |
| `surecart/cart-line-item-status` | self-close | Stock/availability status |
| `surecart/cart-line-item-amount` | self-close | Line total |
| `surecart/cart-line-item-scratch-amount` | self-close | Strikethrough/sale price |
| `surecart/cart-line-item-interval` | self-close | Subscription interval label |
| `surecart/cart-line-item-trial` | self-close | Trial label |
| `surecart/cart-line-item-fees` | self-close | Setup fee label |
| `surecart/cart-line-item-quantity` | self-close | Quantity stepper for the line item |
| `surecart/cart-line-item-remove` | self-close | "Remove" button |
| `surecart/cart-subtotal-amount` | self-close | Subtotal value |
| `surecart/cart-subtotal-scratch-amount` | self-close | Subtotal strikethrough |
| `surecart/cart-order-bumps` | paired | "Suggested for you" upsell section in cart |
| `surecart/cart-order-bump-template` | paired | Repeats once per bump. Inner = single bump card |
| `surecart/cart-order-bump-image` | self-close | Bump product thumbnail |
| `surecart/cart-order-bump-title` | self-close | Bump product name |
| `surecart/cart-order-bump-description` | self-close | Bump product description |
| `surecart/cart-order-bump-amount` | self-close | Bump price |
| `surecart/cart-order-bump-scratch-amount` | self-close | Bump strikethrough |
| `surecart/cart-order-bump-add-button` | self-close | "Add" button |
| `surecart/cart-order-bump-pagination` | paired | Bump carousel pagination |
| `surecart/cart-order-bump-pagination-previous` | self-close | Prev |
| `surecart/cart-order-bump-pagination-next` | self-close | Next |

See `examples/patterns/cart-new.example.md`.

## Upsell family (`surecart/upsell` flow)

| Block | Form | Use case |
|---|---|---|
| `surecart/upsell` | paired | Outer upsell offer wrapper |
| `surecart/upsell-countdown-timer` | self-close | Countdown timer ("Offer expires in 04:32") |
| `surecart/upsell-title` | self-close | Offer headline |
| `surecart/upsell-totals` | self-close | "You save $20" totals row |
| `surecart/upsell-submit` | self-close | "Yes, add to my order" button |
| `surecart/upsell-no-thanks` | self-close | "No thanks" button |
| `surecart/columns` | paired | **CUSTOM** SureCart columns block — only legal inside `surecart/upsell`. NOT an alias for `core/columns` |
| `surecart/column` | paired | **CUSTOM** SureCart column block — only legal inside `surecart/columns` |
| `surecart/product-variant-choices` | self-close | Radio/select variant chooser used in upsell context (different from `product-variant-pills`) |
| `surecart/product-price` | self-close | Generic price block — legitimate ONLY inside `surecart/upsell`. Otherwise alias to `product-selected-price-amount` (hero) or `product-price-chooser` (grid) |

See `examples/patterns/upsell-info.example.md`.

## Selected-price family (hero context — single price)

| Block | Form | Use case |
|---|---|---|
| `surecart/product-selected-price-amount` | self-close | Current price (large display) |
| `surecart/product-selected-price-scratch-amount` | self-close | Strikethrough original price |
| `surecart/product-selected-price-interval` | self-close | "/month", "/year" interval |
| `surecart/product-selected-price-trial` | self-close | "14-day free trial" |
| `surecart/product-selected-price-fees` | self-close | "+ $50 setup fee" |
| `surecart/product-selected-price-ad-hoc-amount` | self-close | "Name your price" / pay-what-you-want input |

## Price-chooser family (multi-tier price context)

| Block | Form | Use case |
|---|---|---|
| `surecart/product-price-chooser` | paired | Outer chooser (radio-list of price tiers) |
| `surecart/product-price-choice-template` | paired | Repeats once per tier. Inner = single tier card |
| `surecart/price-name` | self-close | Tier label ("Monthly", "Annual", "Lifetime") |
| `surecart/price-amount` | self-close | Tier amount |
| `surecart/price-scratch-amount` | self-close | Tier strikethrough |
| `surecart/price-interval` | self-close | Tier interval |
| `surecart/price-trial` | self-close | Tier trial |
| `surecart/price-setup-fee` | self-close | Tier setup fee |

## `*-template` summary (server iterates; emit ONCE)

13 template blocks across all families. Always paired, always emit ONE inner structure:

- `surecart/product-template` (loops over query products)
- `surecart/product-price-choice-template` (loops over price tiers)
- `surecart/product-list-sort-radio-group-template`
- `surecart/product-list-filter-tags-template`
- `surecart/product-list-filter-checkboxes-template`
- `surecart/product-review-template`
- `surecart/product-review-list-filter-tags-template`
- `surecart/product-review-list-filter-checkboxes-template`
- `surecart/cart-order-bump-template`

**Hard rule:** never expand `.map()` in JSX into N siblings of a `*-template` block. The server-side renderer expands. Emit one.
