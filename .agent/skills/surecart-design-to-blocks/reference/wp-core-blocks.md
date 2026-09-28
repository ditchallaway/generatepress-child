# WordPress core blocks — design-to-blocks scope

Lazy-loaded. Open this file when the skill needs to look up a WP core block's attribute schema, supports tree, or wrapper-class shape. Scoped to the blocks `/surecart-design-to-blocks` actually emits — layout primitives, typography, media, interactive elements, and structural blocks. Site/post/query blocks (`core/post-*`, `core/query`, `core/site-*`, `core/template-*`) are not emitted by this skill.

For paired-block exemplars and Rules 0–11, see `reference/core-blocks-cheatsheet.md` — that file shows the canonical markup. **This file is the attribute index.**

---

## Layout primitives

### `core/group`
Generic container. The most-emitted block in design-to-blocks output. Three layout types — pick exactly one per emission.

| Attribute | Type | Notes |
|---|---|---|
| `tagName` | enum | `"div"` (default) / `"section"` / `"header"` / `"footer"` / `"nav"` / `"aside"` / `"article"` / `"main"` |
| `align` | enum | `"wide"` / `"full"` (omit for default constrained) |
| `backgroundColor` / `textColor` / `gradient` | slug | Slug attrs (per Rule 0, paired wrappers stay slug-only — no inline style mirror) |
| `style.color.{text,background,gradient}` | hex | Literal-bg variant only — Rule 0 carve-out (Exemplar 3.5) |
| `style.spacing.{padding,margin,blockGap}` | px | Literal px (v5.9 hybrid policy) |
| `style.border.{width,color,radius}` | mixed | NEVER emit `style:"solid"` (Rule 4 reversal) |
| `style.position` | object | `{type:"sticky", top:"Npx"}` for sticky headers. **Does NOT inline-mirror** (v7.13/A-33). save() emits `is-position-sticky` class only — `position:*` / `top:*` / `z-index:*` MUST NOT appear in the wrapper's inline `style=""`. Same routing family as `style.dimensions.aspectRatio` (HC#25). See Exemplar 9 (corrected). |
| `style.dimensions.{minHeight,aspectRatio}` | string | |
| `layout` | object | See below |
| `className` | string | Custom CSS hooks |

**`layout` shapes (mutually exclusive):**
- `{type:"constrained", contentSize:"1200px", wideSize:"1400px"}` — center-with-max-width. Most common for page sections.
- `{type:"flex", orientation:"horizontal", justifyContent:"center", flexWrap:"wrap"}` — flex row.
- `{type:"flex", orientation:"vertical"}` — flex column.
- `{type:"flow"}` — default flow (block siblings stack with no gap container).

**Wrapper class shape (per `save()`):** `<div class="wp-block-group {alignwide|alignfull?} {has-{slug}-background-color has-background?} {has-{slug}-color has-text-color?} {layout-double-class?}">`

**Layout double-class** (Rule 1): when `layout.type` is `"constrained"` or `"flow"`, also add `is-layout-{type}` AND `wp-block-group-is-layout-{type}` classes. NOT for `"flex"`.

---

### `core/columns`

N-column container.

| Attribute | Type | Notes |
|---|---|---|
| `columns` | number | Total columns (informational; actual N comes from `core/column` siblings) |
| `isStackedOnMobile` | boolean | Default `true`. Default may be omitted; explicit `false` flips it. |
| `verticalAlignment` | enum | `"top"` / `"center"` / `"bottom"` — required on parent + each `core/column` child for centering |
| `align` | enum | `"wide"` / `"full"` — only on outermost columns block, NOT on inner columns |
| `style.spacing.blockGap` | px | Gap between columns (single axis or `{top,left}` object for asymmetric) |
| `backgroundColor` / `textColor` | slug | Slug-only per Rule 0 |
| `className` | string | |

**Wrapper class shape:** `<div class="wp-block-columns {alignwide|alignfull?} {are-vertically-aligned-{value}?} {is-stacked-on-mobile?}">`

When `verticalAlignment` is set: parent gets `are-vertically-aligned-{value}` AND each child `core/column` gets `is-vertically-aligned-{value}` on its own wrapper.

---

### `core/column`

Single column inside `core/columns`. **Required parent: `core/columns`.**

| Attribute | Type | Notes |
|---|---|---|
| `width` | string | `"50%"` / `"33.33%"` / `"320px"` etc. Becomes inline `style="flex-basis:50%"` |
| `verticalAlignment` | enum | Per-column override of parent's value |
| `style.spacing.padding` | px | Per-column padding |
| `backgroundColor` / `textColor` | slug | Slug-only |
| `className` | string | |

**Wrapper class shape:** `<div class="wp-block-column {is-vertically-aligned-{value}?}" style="flex-basis:{width};">`.

---

### `core/cover`

Background-image / video / gradient + overlay + content. The **only** core block with native gradient + aspect-ratio attrs. See Exemplar 8 for canonical markup.

| Attribute | Type | Notes |
|---|---|---|
| `url` | string | Background image URL |
| `backgroundType` | enum | `"image"` (default) / `"video"` |
| `gradient` | slug | Theme gradient slug |
| `customGradient` | string | Literal `linear-gradient(...)` / `radial-gradient(...)` |
| `dimRatio` | number | 0–100. **Default: 100.** Overlay opacity. Class is `has-background-dim-{dimRatio}`. When `useFeaturedImage:true` and no image is set, the cover renders with this dim. When `customGradient` is set, default-100 is applied per HC#27. |
| `overlayColor` | slug | Overlay color slug |
| `customOverlayColor` | hex | Literal overlay color |
| `focalPoint` | object | `{x:0.5, y:0.5}` — image focal point |
| `contentPosition` | string | `"top left"` / `"top center"` / `"top right"` / `"center left"` / `"center center"` (default) / `"center right"` / `"bottom left"` / `"bottom center"` / `"bottom right"`. **Emits `is-position-{v}-{h}` class** on the wrapper `<div>` (space replaced with hyphen). Also emits `has-custom-content-position` class when NOT the default `"center center"`. **MUST emit both classes** — omission triggers `Block validation failed for core/cover` cascading to every ancestor block. |
| `isDark` | boolean | Default true. When false, emits `is-light` class on wrapper instead of `is-dark`. Affects text contrast styling. |
| `useFeaturedImage` | boolean | When true, cover renders the product's featured image instead of `url`/bg color. See HC#30 + HC#36 for aspect-ratio routing and SVG-placeholder fallback. |
| `isUserOverlayColor` | boolean | Default false. When true, the overlay color is explicitly set by user (not auto-detected). Pair with `dimRatio:0` for "no overlay tint" semantics. |
| `hasParallax` | boolean | Default false. When true, emits `has-parallax` class. Background scrolls slower than content. |
| `isRepeated` | boolean | Default false. When true, emits `is-repeated` class on wrapper. Background tiles instead of cover. |
| `tagName` | enum | `"div"` (default) / `"section"` / `"header"` / `"footer"` / `"main"`. Wrapper tag for the cover. |
| `id` | number | WordPress attachment ID of the background image. |
| `alt` | string | Default `""`. Image alt text — required when `backgroundType:"image"` for accessibility. |
| `sizeSlug` | string | `"thumbnail"` / `"medium"` / `"large"` / `"full"`. Image size variant. |
| `poster` | string | Poster image URL when `backgroundType:"video"`. |
| `minHeight` | number | |
| `minHeightUnit` | string | `"px"` (default) / `"vh"` / `"%"` |
| `aspectRatio` | string | `"1"` / `"4/3"` / `"3/4"` / `"3/2"` / `"2/3"` / `"16/9"` / `"9/16"` / `"21/9"` (or arbitrary `"5/4"` literal) |
| `align` | enum | `"wide"` / `"full"` |
| `layout` | object | `{type:"constrained", contentSize:"1200px"}` for content layout |

**Wrapper class shape:** `<div class="wp-block-cover {is-light?} {has-custom-content-position?} {is-position-{v}-{h}?} {has-parallax?} {is-repeated?} {has-background-gradient?} {has-{slug}-overlay-color?} {has-{slug}-background-color has-background?}" style="...">`

**`contentPosition` class doctrine (v7.15, ported from sibling SHC#8):** when JSON has `contentPosition:"X Y"` (anything other than the default `"center center"`), wrapper class MUST include BOTH `has-custom-content-position` AND `is-position-{X}-{Y}` (space-to-hyphen).

| `contentPosition` JSON | Required classes on wrapper `<div>` |
|---|---|
| omitted OR `"center center"` | (none — defaults are implicit) |
| `"top left"` | `has-custom-content-position is-position-top-left` |
| `"top center"` | `has-custom-content-position is-position-top-center` |
| `"top right"` | `has-custom-content-position is-position-top-right` |
| `"center left"` | `has-custom-content-position is-position-center-left` |
| `"center right"` | `has-custom-content-position is-position-center-right` |
| `"bottom left"` | `has-custom-content-position is-position-bottom-left` |
| `"bottom center"` | `has-custom-content-position is-position-bottom-center` |
| `"bottom right"` | `has-custom-content-position is-position-bottom-right` |

**Failure signature** if classes are missing: `Block validation failed for 'core/cover'` cascading to every ancestor (a single inner cover breaks the entire `surecart/product-page`).

**Outer div DOES mirror inline style** — Rule 0c carve-out. Cover is the exception to Rule 0.

**Inner shape (mandatory):**
```
<span aria-hidden="true" class="wp-block-cover__background has-background-dim-{dimRatio} has-background-dim"></span>
<img class="wp-block-cover__image-background" alt="" src="..." data-object-fit="cover"/>     ← only when backgroundType:image
<div class="wp-block-cover__inner-container">
  …block siblings inside this div, NOT direct children of wp-block-cover…
</div>
```

---

### `core/spacer`

Vertical or horizontal whitespace.

| Attribute | Type | Notes |
|---|---|---|
| `height` | string | `"60px"` / `"4rem"` / `"clamp(...)"` |
| `width` | string | When inside a flex container |

**Markup:** `<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>` — paired wrapper. Do NOT self-close.

---

### `core/separator`

Horizontal rule.

| Attribute | Type | Notes |
|---|---|---|
| `align` | enum | `"wide"` / `"full"` / `"center"` |
| `style.color.background` | hex | Literal hex for the line color |
| `backgroundColor` | slug | Slug variant |
| `opacity` | enum | `"alpha-channel"` (**default — DO NOT confuse with prior skill versions that said `"css"` was default; that was inverted**) / `"css"` (legacy). Always emits `has-alpha-channel-opacity` class on the `<hr>` per Rule 5. |
| `style.spacing.margin` | px | Vertical spacing around |

**Markup:** `<hr class="wp-block-separator has-alpha-channel-opacity {has-{slug}-background-color has-background?}"/>` — paired but minimal (no inner content). Do NOT self-close.

---

## Typography

### `core/heading`

`<h1>`–`<h6>` with full typography contract.

| Attribute | Type | Notes |
|---|---|---|
| `level` | number | 1–6. Defaults to 2. |
| `content` | string | Inline HTML allowed |
| `textAlign` | enum | `"left"` / `"center"` / `"right"` |
| `align` | enum | `"wide"` / `"full"` — usually omitted |
| `textColor` | slug | + `has-{slug}-color has-text-color` class |
| `fontFamily` | slug | + `has-{slug}-font-family` class — **mandatory** for text-bearing blocks (SKILL.md hard constraint #10) |
| `fontSize` | slug | + `has-{slug}-font-size` class |
| `style.color.text` | hex | D7 dual-emit on this leaf — pair with slug |
| `style.typography.{fontSize,fontWeight,lineHeight,letterSpacing,textTransform,textDecoration,fontStyle}` | string | All as strings, not numbers |
| `style.spacing.margin` | px | |
| `className` | string | |

**Wrapper class shape:** `<h{level} class="wp-block-heading {has-text-align-{value}?} {has-{slug}-color has-text-color?} {has-{slug}-font-family?} {has-custom-font-size?}">`

**`has-custom-font-size`** is added when `style.typography.fontSize` is a literal px value (Rule 5).

---

### `core/paragraph`

Body text.

| Attribute | Type | Notes |
|---|---|---|
| `content` | string | Inline HTML allowed |
| `align` | enum | `"left"` / `"center"` / `"right"` — top-level attr, NOT nested under style |
| `dropCap` | boolean | First-letter cap |
| Same color / typography / fontFamily / fontSize / spacing attrs as `core/heading` | | |

**Wrapper class shape:** `<p class="{has-text-align-{value}?} {has-{slug}-color has-text-color?} {has-{slug}-font-family?} {has-custom-font-size?}">…</p>`

Note: NO `wp-block-paragraph` class. Paragraph is the only inline-text block where the wrapper class is empty by default (only the modifier classes apply).

---

### `core/list` + `core/list-item`

`<ul>` / `<ol>` with `<li>` children.

| `core/list` attribute | Type | Notes |
|---|---|---|
| `ordered` | boolean | `false` → `<ul>`, `true` → `<ol>` |
| `start` | number | For ordered lists |
| `style.spacing.{margin,blockGap}` | px | Item gap |

**`core/list-item`:** has its own typography + color attrs (B-18 — explicit per item, not inherited). Wrapper is `<li>` with no `wp-block-list-item` class — just modifier classes (`has-text-color`, `has-{slug}-color`, `has-custom-font-size`, etc.).

---

### `core/quote`

Blockquote with citation.

| Attribute | Type | Notes |
|---|---|---|
| `value` | string | Inner HTML (use nested `core/paragraph` for body) |
| `citation` | string | Inline citation HTML |
| `align` | enum | |
| `className` | string | |

**Markup:** `<blockquote class="wp-block-quote">…<cite>{citation}</cite></blockquote>`. Children are `core/paragraph` (or other text blocks) for the quote body.

---

## Media

### `core/image`

Single image.

| Attribute | Type | Notes |
|---|---|---|
| `url` | string | Image src |
| `alt` | string | **Required for accessibility.** Empty string `""` allowed for purely decorative |
| `caption` | string | Inline HTML caption |
| `width` | number / string | Pixel value or `"100%"` |
| `height` | number / string | |
| `aspectRatio` | string | `"4/3"` / `"16/9"` etc. |
| `sizeSlug` | string | `"thumbnail"` / `"medium"` / `"large"` / `"full"` |
| `align` | enum | `"left"` / `"right"` / `"center"` / `"wide"` / `"full"` |
| `linkDestination` | enum | `"none"` / `"media"` / `"attachment"` / `"custom"` |
| `href` | string | When linkDestination is custom |
| `style.border.{width,color,radius}` | mixed | Border on `<img>`, NOT `<figure>` (Rule 9) |

**Markup:** `<figure class="wp-block-image size-{sizeSlug} {alignleft?} {has-custom-border?}"><img src="..." alt="..." width="..." height="..." {style.border?:"border-radius:..."}/>{caption?:"<figcaption>"}</figure>`

**Border-radius rule (Rule 9):** radius goes on the inner `<img>` style, not `<figure>`. `<figure>` carries the `has-custom-border` class to opt into the border styling layer.

---

### `core/gallery`

Multi-image grid.

| Attribute | Type | Notes |
|---|---|---|
| `columns` | number | 1–8 |
| `imageCrop` | boolean | Crop to fit |
| `linkTo` | enum | `"none"` / `"media"` / `"attachment"` |
| `sizeSlug` | string | |

Children are `core/image` blocks. Wrapper: `<figure class="wp-block-gallery has-nested-images columns-{N}">`.

---

### `core/video`

| Attribute | Type | Notes |
|---|---|---|
| `src` | string | |
| `poster` | string | Poster image URL |
| `controls` | boolean | Default `true` |
| `loop` | boolean | |
| `muted` | boolean | |
| `autoplay` | boolean | Browser-policy gated |
| `playsInline` | boolean | |
| `preload` | enum | `"none"` / `"metadata"` (**default**) / `"auto"` |
| `caption` | string | |

---

### `core/embed`

YouTube / Vimeo / Twitter / etc.

| Attribute | Type | Notes |
|---|---|---|
| `url` | string | Provider URL |
| `providerNameSlug` | string | `"youtube"` / `"vimeo"` / `"twitter"` etc. |
| `allowResponsive` | boolean | Default `true` |
| `caption` | string | |

---

### `core/icon` (WP 7.0+)

Native SVG icon block. Renders one of 88 built-in core icons by slug — **no inline SVG needed**. `apiVersion: 3`, server-rendered, **MUST self-close** in serialized markup (no `save()` function; the wrapper `<div>` is emitted at render time, not in the source).

| Attribute | Type | Notes |
|---|---|---|
| `icon` | string | **Mandatory.** Slug in `"{namespace}/{name}"` form — e.g., `"core/arrow-down-right"`, `"core/check"`, `"core/cart"`. Resolved against `WP_Icons_Registry` at render. Empty / unknown slug → block renders nothing. |
| `ariaLabel` | string | When set → SVG gets `role="img"` + `aria-label="…"`. Empty / omitted → SVG gets `aria-hidden="true"` + `focusable="false"` (decorative). |
| `align` | enum | `"left"` / `"center"` / `"right"` only. NOT `"wide"` / `"full"` — for full-width icon billboards, wrap in a `core/group` `align:"full"`. |
| `textColor` / `backgroundColor` / `borderColor` | slug | All slug color attrs supported. **`__experimentalSkipSerialization: true`** — colors apply to the inner `<svg>`, NOT the wrapper `<div>`. Do NOT inject `has-{slug}-color` / `has-text-color` / `has-background` classes on the wrapper. |
| `style.color.{text,background}` | hex | Literal colors. Same skipSerialization rule — applies to inner SVG only. |
| `style.border.{color,radius,style,width}` | mixed | All four border tools, skipSerialization → applied to SVG, not wrapper. |
| `style.spacing.margin` | px | **Serializes normally** on the wrapper `<div>` (per `selectors.spacing.margin`). |
| `style.spacing.padding` | px | **skipSerialization** — applied to inner SVG container. |
| `style.dimensions.width` | string | `"32px"` / `"48px"` / `"3em"` etc. **skipSerialization** — applied to inner SVG as `width`/`height`. Default size matches the SVG's intrinsic `viewBox`. |
| `anchor` | string | Standard HTML id anchor. |

**Markup form (paste output):** ALWAYS self-closing — `apiVersion: 3` server-rendered, same pattern as `surecart/product-*` blocks.

```html
<!-- wp:icon {"icon":"core/arrow-down-right"} /-->
```

With color + size:

```html
<!-- wp:icon {"icon":"core/check","style":{"dimensions":{"width":"24px"},"color":{"text":"#01824C"}}} /-->
```

With slug color + ariaLabel:

```html
<!-- wp:icon {"icon":"core/cart","textColor":"surecart-brand","ariaLabel":"Open cart"} /-->
```

**Server-rendered HTML shape** (for reference — do NOT hand-write this; the block renders it):

```html
<div class="wp-block-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="…color/border/width via style-engine…">…paths…</svg></div>
```

**Built-in core icon slugs (88 total, namespace `core/`):**

```
arrow-down, arrow-down-left, arrow-down-right, arrow-left, arrow-right,
arrow-up, arrow-up-left, arrow-up-right, at-symbol, audio, bell,
block-default, block-meta, block-table, calendar, capture-photo, capture-video,
cart, category, caution, chart-bar, check, chevron-down, chevron-down-small,
chevron-left, chevron-left-small, chevron-right, chevron-right-small,
chevron-up, chevron-up-down, chevron-up-small, comment, cover, create,
desktop, download, drawer-left, drawer-right, envelope, error, external,
file, gallery, group, heading, help, home, image, info, key, label,
language, map-marker, menu, mobile, more-horizontal, more-vertical, next,
paragraph, payment, pencil, people, plus, plus-circle, previous, published,
quote, receipt, rss, scheduled, search, settings, shadow, share, shield,
shuffle, star-empty, star-filled, star-half, store, styles, symbol,
symbol-filled, table, tablet, tag, tip, upload, verse
```

**When to emit `core/icon` vs other options:**

| Design element | Emit | Why |
|---|---|---|
| Decorative arrow / chevron / check / cart / star / etc. matching a built-in slug | `core/icon` | **First choice** — paste-safe, no `unfiltered_html` needed, theme-overridable color, schema-clean |
| Custom brand-specific icon (logo glyph, illustrative mark) | `core/image` with SVG/PNG URL | `core/icon` only resolves registered slugs; custom art needs a URL |
| Inline SVG that doesn't match any built-in slug but is decorative | `core/html` L1 fallback (existing) | Last resort — requires `unfiltered_html` capability to survive save |

`core/icon` does NOT support arbitrary `<svg>` markup. The `icon` attribute is a **slug only**, not raw SVG. If the design uses a non-built-in glyph, prefer `core/image` (PNG/SVG file) over `core/html`.

**Common gotchas:**

- Comment marker uses unprefixed `wp:icon`, NOT `wp:core/icon` (HC#12 — same as every core block).
- The icon attribute value MUST include the `core/` namespace prefix (e.g., `"core/check"`, not `"check"`). Omitting the namespace makes the registry lookup fail → block renders empty.
- Color / border / padding / width attrs apply to the SVG, NOT the wrapper. Do not add `has-{slug}-color` / `has-text-color` / `has-background` / `has-border-color` classes (no `save()` — the wrapper is emitted by `get_block_wrapper_attributes()` server-side).
- `align` is restricted to `left`/`center`/`right`. No `wide`/`full`. For wide layouts, wrap the icon in `core/group`.
- The block's `selectors.css` is `.wp-block-icon` (CSS hook); theme overrides for size/color target this selector.

---

## Interactive

### `core/buttons`

Container for `core/button` siblings. Required parent.

| Attribute | Type | Notes |
|---|---|---|
| `layout` | object | `{type:"flex", justifyContent:"...", flexWrap:"..."}` |
| `style.spacing.blockGap` | px | Gap between buttons |
| `align` | enum | |

**Wrapper:** `<div class="wp-block-buttons {is-content-justification-{value}?}">`. Required even for a single `core/button`.

---

### `core/button`

CTA button. Must be inside `core/buttons`.

| Attribute | Type | Notes |
|---|---|---|
| `text` | string | Button label |
| `url` | string | **Required.** Without `href`, parser falls back to `<button>` and breaks. |
| `linkTarget` | enum | `"_self"` / `"_blank"` |
| `rel` | string | |
| ~~`width`~~ | — | **NOT a block attribute** (was hallucinated in earlier skill versions). Button width is controlled via className: emit `className:"has-custom-width wp-block-button__width-{N}"` where N is 25 / 50 / 75 / 100. The block.json has NO `width` attribute. |
| `tagName` | enum | `"a"` (default — produces `<a href="...">`) / `"button"` (produces `<button>` for form-submit contexts). When `tagName:"button"`, the `href` attr becomes irrelevant. |
| `type` | enum | `"button"` (default) / `"submit"` / `"reset"`. Only meaningful when `tagName:"button"`. For form-submit buttons inside `<form>` blocks. |
| `title` | string | Tooltip text (HTML `title` attr on the `<a>` or `<button>`). |
| `backgroundColor` / `textColor` / `borderColor` | slug | Standard slug attrs |
| `gradient` | slug | Slug-only |
| `style.color.{text,background,gradient}` | hex | D7 dual-emit on this leaf |
| `style.border.{width,color,radius}` | mixed | |
| `style.spacing.padding` | px | |
| `style.typography.{fontSize,fontWeight,...}` | string | |
| `className` | string | `is-style-fill` / `is-style-outline` etc. |

**Wrapper:** `<div class="wp-block-button {has-custom-width?} {wp-block-button__width-{N}?} {is-style-{name}?}"><a class="wp-block-button__link {has-{slug}-color?} {has-text-color?} {has-{slug}-background-color?} {has-background?} {has-{slug}-font-family?} {has-{slug}-font-size?|has-custom-font-size?} wp-element-button" href="..." style="...">{text}</a></div>`

**Canonical class order on `<a>` (v7.13 — per A-34):**

1. `wp-block-button__link` (always)
2. `has-text-color` (when `style.color.text` or `textColor:"<slug>"` set)
3. `has-{slug}-color` (when `textColor:"<slug>"` set — BEFORE `has-text-color`? actually per save() empirics, `has-text-color` comes AFTER the specific slug class)
4. `has-background` (when `style.color.background` or `backgroundColor:"<slug>"` set)
5. `has-{slug}-background-color` (when `backgroundColor:"<slug>"` set)
6. `has-{slug}-font-family` (when `fontFamily:"<slug>"` set)
7. `has-{slug}-font-size` OR `has-custom-font-size` — slug variant when `fontSize:"<slug>"` set, custom variant when `style.typography.fontSize:"Npx"` (literal px) set
8. `wp-element-button` (always — TRAILING indicator, per A-34)

**`has-custom-font-size` is added when `style.typography.fontSize` is a literal px value** (e.g., `"16px"`) — same Rule 5 that applies to `core/heading`/`core/paragraph`. Empirically verified Morning Glow v2 paste-test (2026-05-26).

---

### `core/details`

Native `<details>/<summary>` collapsible. The FAQ-canonical block.

| Attribute | Type | Notes |
|---|---|---|
| `summary` | string | **Mandatory.** Must match the inner `<summary>` HTML. Mismatch → recovery (Rule 5 / rubric A-4). |
| `showContent` | boolean | **Default `false`.** When `true`, the `<details>` element emits an `open` attribute and the disclosure is expanded on load. Use for the first item in an FAQ list (the most-relevant question). |

**Wrapper:** `<details class="wp-block-details" {open?}><summary>{summary text}</summary>…inner blocks…</details>`. Inner blocks are typically `core/paragraph` or other text blocks; place between `<summary>` and `</details>`.

---

### `core/search`

Search form.

| Attribute | Type | Notes |
|---|---|---|
| `label` | string | |
| `placeholder` | string | |
| `buttonText` | string | |
| `buttonPosition` | enum | `"button-outside"` (**default**) / `"button-inside"` / `"no-button"` / `"button-only"` |
| `showLabel` | boolean | **Default `true`.** When `false`, the label is visually hidden (still present for a11y). Set to `false` when the design has a search input without a visible label. |

Rarely useful for design-to-blocks; SureCart usually has its own product search via `surecart/product-list-search`.

---

## Structural

### `core/table`

| Attribute | Type | Notes |
|---|---|---|
| `hasFixedLayout` | boolean | Adds `has-fixed-layout` class on `<table>`, NOT `<figure>` (Exemplar 6.5) |
| `caption` | string | Becomes `<figcaption>` inside `<figure>` |
| `head` / `body` / `foot` | array | Cells: `{cells: [{content, tag:"td"|"th", scope?, align?}]}` |

**Markup:** `<figure class="wp-block-table"><table class="has-fixed-layout?">…</table>{caption?:"<figcaption>"}</figure>` — class split between `<figure>` and `<table>` is intentional.

---

### `core/html`

Raw HTML passthrough. Use as escape hatch when nothing else fits.

| Attribute | Type | Notes |
|---|---|---|
| `content` | string | Raw HTML — Gutenberg passes through unmodified |

**Caution:** anything in `core/html` is invisible to the block editor's visual mode. Merchant edits via the code editor only. Use sparingly.

**v7.1 — three-tier ladder.** When falling back to `core/html`, classify the emit and log accordingly. See **`reference/custom-html-fallback.md`** for the full contract:

- **L1 — static** (decorative content, no behavior) → log `dropped_features.type:"raw_html_static"`.
- **L2 — Interactivity API piggyback** (`data-wp-interactive` + directives that call into one of the 7 documented `surecart/*` stores; requires a sibling SureCart block on the same emit to trigger the module load) → log `dropped_features.type:"raw_html_interactive", level:"L2"` with `namespace` + `dependency_block` fields.
- **L3 — custom interactivity** (no allowlist namespace fits) → emit best-effort static chrome, log `level:"L3"`, recommend `/surecart-new-block` (or `/surecart-new-integration` for third-party APIs).

**Capability gate.** `core/html` content survives the post-save round-trip only when the saving WP user has `unfiltered_html` (administrator and editor by default). Below that role, `wp_kses_post()` strips `data-wp-*` attributes and many other tags. SKILL.md's copy-paste instructions add a single-line warning when any L2 emit is present.

**Forbidden inside `core/html`** (regardless of tier): inline `onclick=`/`onchange=`/`onsubmit=` event handlers, `<script>` tags, `<style>` tags. Behavior goes through Interactivity directives at L2 or a registered block at L3; styling goes through theme CSS referencing class names.

---

## Registered block-style variations (v7.15) — WP core blocks

Many WP-core blocks have registered style variations via `block.json#styles`. These are paste-safe and theme-overridable. **Check this catalog BEFORE reaching for `core/html` L1 fallback** when the design has a stylized variant of a standard block.

| Block | Variations | `isDefault` | Visual effect |
|---|---|---|---|
| `core/image` | `default`, **`rounded`** | `default` | `is-style-rounded` → full circular border-radius on `<img>` (often used for avatars or testimonial portraits) |
| `core/separator` | `default`, `wide`, **`dots`** | `default` | `is-style-wide` → 100% width line; `is-style-dots` → 3-dot center mark instead of line. Design designs often use `dots` between sections. |
| `core/quote` | `default`, **`plain`** | `default` | `is-style-plain` → strips the `<blockquote>` left-bar styling, renders as larger body text |
| `core/table` | `regular`, **`stripes`** | `regular` | `is-style-stripes` → alternating-row striped background |
| `core/button` | **`fill`**, `outline` | `fill` | `is-style-fill` (default) = solid bg + text; `is-style-outline` = bordered + transparent bg. Designs commonly pair both as primary/secondary CTAs. |

**How to apply:** set the `className` attr in JSON. The class lands on the wrapper element:

```html
<!-- wp:image {"sizeSlug":"large","className":"is-style-rounded","style":{"border":{"radius":"9999px"}}} -->
<figure class="wp-block-image size-large is-style-rounded has-custom-border"><img src="..." alt="" style="border-radius:9999px"/></figure>
<!-- /wp:image -->
```

```html
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->
```

```html
<!-- wp:quote {"className":"is-style-plain"} -->
<blockquote class="wp-block-quote is-style-plain"><p>The quote text…</p><cite>— Author Name</cite></blockquote>
<!-- /wp:quote -->
```

```html
<!-- wp:table {"className":"is-style-stripes"} -->
<figure class="wp-block-table is-style-stripes"><table class="has-fixed-layout"><tbody>…</tbody></table></figure>
<!-- /wp:table -->
```

**Default variation handling:** when `isDefault:true` is the variant matching the design, OMIT `className` entirely. Emitting `className:"is-style-default"` causes a round-trip rewrite as save() strips the default-equivalent class on re-serialization.

**Discovery for other core blocks:** run `wp.data.select('core/blocks').getBlockStyles('core/<name>')` in the WP block editor console. Append findings to this table.

---

## Self-closing-allowed core blocks

These MAY use the self-closing comment form (`<!-- wp:NAME /-->`):

- `core/site-logo`, `core/site-title`, `core/site-tagline`, `core/post-title` (all server-rendered)
- `core/icon` (server-rendered, `apiVersion: 3`, no `save()` — ALWAYS self-close)

These look self-closing but actually have **inner HTML** — never self-close them:

- `core/heading` — inner `<h{level}>`
- `core/paragraph` — inner `<p>`
- `core/image` — inner `<figure>`
- `core/spacer` — inner `<div>`
- `core/separator` — inner `<hr>`

Everything else (`core/group`, `core/columns`, `core/column`, `core/cover`, `core/media-text`, `core/buttons`, `core/button`, `core/list`, `core/list-item`, `core/quote`, `core/details`, `core/table`) is a paired block — opener + wrapper div + closer.

---

## Validating a core block emit

For every `core/*` block:

1. **Block name is real** — listed in this file, in the cheatsheet, or the official Gutenberg block catalog. Don't invent.
2. **Attribute key is supported** — listed under that block's table here. No invented attrs.
3. **Wrapper class shape matches `save()`** — slug attrs require matching `has-{slug}-{kind}` classes; layout type requires double-class (Rule 1); `verticalAlignment` requires `are-vertically-aligned-*` on parent + `is-vertically-aligned-*` on each child.
4. **Paired blocks are paired** — opener + wrapper + closer. Self-closing only for the explicitly-allowed list.
5. **Rule 0 hierarchy applied** — paired wrappers stay slug-only (no inline style mirror), with the documented carve-outs (literal-bg variant, `core/cover`, sticky position).
