# Pixel Checklist — the Design Extraction Pass

Single-page checklist the skill walks **before emitting any markup**. Every category here must be either captured into block attrs or logged in `dropped_features`. Silent drops are forbidden.

Load on demand from SKILL.md Step 1.5.

---

## How to use

1. For every section the design contains, walk this checklist top-to-bottom.
2. If a category is captured: ✅ note the block + attr key.
3. If a category cannot be expressed in Gutenberg attrs: drop and add a `dropped_features` entry **with a `merchant_action` CSS-snippet escape hatch** when restoration is feasible via theme CSS.
4. Cross-reference `reference/style-conversion.md` for the exact JSX → attr mapping per category.

---

## Category 1 — Typography (per-element, never global)

For every text-bearing element (heading, paragraph, list item, button label, eyebrow, quote, table cell):

| Property | Capture | Block attr | Notes |
|---|---|---|---|
| `font-family` | ✅ | `fontFamily:"surecart-{display\|body\|mono}"` + `has-{slug}-font-family` class | Mandatory when source class declares font-family. See SKILL.md hard constraint #10. |
| `font-size` | ✅ | slug `fontSize:"X"` if matched, else `style.typography.fontSize:"Npx"` + `has-custom-font-size` | Slug-first; literal px fallback. |
| `font-weight` | ✅ | `style.typography.fontWeight:"700"` (string, not number) | Always when ≠ 400. |
| `line-height` | ✅ | `style.typography.lineHeight:"1.6"` (string) | Always when source has it. |
| `letter-spacing` | ✅ | `style.typography.letterSpacing:"-0.03em"` | Em units, not px. Preserve 1:1. |
| `text-transform` | ✅ | `style.typography.textTransform:"uppercase"` | Always when source has it. |
| `text-align` | ✅ | `textAlign:"center"` (top-level attr) + `has-text-align-{value}` class | Top-level, not nested. |
| `text-decoration` | ✅ | `style.typography.textDecoration:"underline"` | Preserve. |
| `font-style` | ✅ | `style.typography.fontStyle:"italic"` | Preserve. |
| `font-feature-settings` | ❌ | — | Drop + log `{type:"font-feature-settings"}` with merchant CSS snippet. |

**Per-element rule**: every leaf emits its full B-18 typography contract — never inherited from a parent. Container blocks (`core/group`, `core/columns`, `surecart/product-buy-buttons`, `surecart/product-quantity`, etc.) own LAYOUT only.

---

## Category 2 — Color (text, background, borders, gradients)

| Property | Capture | Output | Notes |
|---|---|---|---|
| `color` (slug match in `theme-partial.json` within ΔE 6) | ✅ | `textColor:"slug"` + `has-{slug}-color has-text-color` class. **Leaf elements get D7 dual-emit:** also include `style.color.text:"#hex"`. | ΔE = perceptual color distance. |
| `color` (no slug match) | ✅ | `style.color.text:"#hex"` + `has-text-color` class | Literal hex; logged in `color_snap_misses`. |
| `background-color` (slug match) | ✅ | `backgroundColor:"slug"` + `has-{slug}-background-color has-background` class. **Paired wrappers stay slug-only (Rule 0).** | No-source-no-emit: only emit if source has explicit background. |
| `background-color` (literal hex) | ✅ | `style.color.background:"#hex"` + `has-background` class. **Leaf elements get D7 dual-emit; paired wrappers get the Rule 0 carve-out.** | |
| `border-color` (slug or literal) | ✅ | `borderColor:"slug"` + `style.border.color:"#hex"` literal | Always when source has it. |
| **Linear gradient** (`linear-gradient(...)`) | ⚠ partial | `core/cover` only: `gradient` attr if matches a theme gradient slug, else `style.color.gradient:"<full string>"`. **All other blocks: drop + log + CSS snippet.** | See `reference/style-conversion.md` Gradients section. |
| **Radial gradient** | ❌ | — | Drop + log `{type:"gradient",mode:"radial",detail:"..."}` with merchant CSS snippet. |
| **Color-mix / color-contrast / oklch / lab/lch** | ❌ | — | Compute the resolved hex if possible (capture intermediate), else drop + log. |
| **CSS filter** (invert/saturate/hue-rotate) | ❌ | — | Drop + log with CSS snippet. |

---

## Category 3 — Spacing (padding, margin, gap)

**v5.9 Hybrid policy**: literal px only. NEVER emit `var:preset|spacing|*` strings — paste line-wrap corrupts them.

| Property | Capture | Output | Notes |
|---|---|---|---|
| `padding[*]` | ✅ | `style.spacing.padding.{side}:"Npx"` | Inline mirror on inner blocks; on paired wrappers only when wrapper also has literal-bg (Rule 0 hierarchy). |
| `margin[*]` | ✅ | `style.spacing.margin.{side}:"Npx"` | Preserve 1:1 (do NOT round 28→32). |
| `gap` (parent of grid/flex) | ✅ | parent's `style.spacing.blockGap:"Npx"` | On parent, never per-child. |
| Negative margin (`margin-top:-40px`) | ✅ | `style.spacing.margin.top:"-40px"` | Gutenberg accepts negative px. |
| `aspect-ratio: N/M` | ✅ | `style.dimensions.aspectRatio:"N/M"` (where supported) or per-block (e.g., `core/cover` `aspectRatio` attr) | See Category 6. |
| `row-gap` / `column-gap` (asymmetric) | ⚠ | Pick the dominant axis; emit single `blockGap`. Log asymmetry in drift report. | Gutenberg blockGap is single-axis. |
| `gap-x: 0; gap-y: Npx` | ⚠ | Same as above. | Drop the zero-axis silently if it's the natural CSS default. |

---

## Category 4 — Layout (flex, grid, columns)

| Source | Map to | Notes |
|---|---|---|
| `display:grid; grid-template-columns:repeat(N,1fr)` | `core/columns` with N `core/column` children | Stacks on mobile by default (`is-stacked-on-mobile`). |
| `display:grid; grid-template-columns:1fr 1fr` | `core/columns` with 2 `core/column` (50% each) | Width attrs explicit when not equal. |
| `display:flex; flex-direction:row` | parent's `layout.type:"flex"` + `layout.orientation:"horizontal"` | |
| `display:flex; flex-direction:column` | parent's `layout.type:"constrained"` (most common) or vertical `core/group` | |
| `flex-wrap:wrap` | `layout.flexWrap:"wrap"` | |
| `justify-content:center` | `layout.justifyContent:"center"` | |
| `align-items:center` | `verticalAlignment:"center"` (on `core/columns` parent + each `core/column` child) | |
| `grid-template-areas` | ❌ drop | No Gutenberg equivalent; restructure with nested groups/columns. |
| `position:absolute / fixed` | ⚠ partial | `core/cover` supports overlay positioning natively; otherwise drop + log. |
| `position:sticky` | ✅ | `style.position.type:"sticky"` + `style.position.top:"Npx"` + `is-position-sticky` class. See `style-conversion.md` Position section. |
| `z-index:N` | ✅ when paired with `position` | Inline `style="z-index:N"` mirror; not a standalone Gutenberg attr. |

---

## Category 5 — Box (border, radius, shadow, outline)

| Property | Capture | Output | Notes |
|---|---|---|---|
| `border-width` / `border-color` | ✅ | `style.border.width:"Npx"`, `borderColor:"slug"` or `style.border.color:"#hex"` | |
| `border-radius` | ✅ | `style.border.radius:"Npx"` (uniform) or `style.border.radius:{topLeft,topRight,bottomRight,bottomLeft}` (per-corner) | |
| `border-style` | ⚠ | **NEVER emit `border-style:solid`** — empirical Rule 4 (v5.6 reversal). save() does not auto-inject it; emitting it triggers recovery. | Other styles (dashed, dotted) — emit and accept the recovery risk. |
| `box-shadow` | ❌ | — | Drop + log `{type:"shadow",detail:"<full string>"}` with `merchant_action` CSS snippet to add to theme. |
| `inset` shadows / multi-layer shadows | ❌ | — | Same. Pick most prominent layer for description. |
| `outline` (focus ring) | ❌ | — | Themes typically own focus styles; drop silently. |
| `backdrop-filter:blur(...)` | ❌ | — | Drop + log + CSS snippet. |

---

## Category 6 — Sizing (width, height, aspect-ratio)

| Property | Capture | Output | Notes |
|---|---|---|---|
| `width: Npx` (literal) | ✅ | `width:"Npx"` (block attr) or `style.dimensions.width:"Npx"` per block | |
| `width: N%` | ✅ | `width:"N%"` for `core/column` children; `align:"wide"` / `align:"full"` for sections | |
| `max-width: Npx` (containers) | ✅ | `layout.contentSize:"Npx"` on `core/group` with `layout.type:"constrained"` | |
| `min-height: Npx` | ✅ | `style.dimensions.minHeight:"Npx"` (where supported) or `core/cover.minHeight` | |
| `aspect-ratio: N/M` (`core/image`) | ✅ | `aspectRatio:"N/M"` block attr | |
| `aspect-ratio: N/M` (`core/cover`) | ✅ | `aspectRatio:"N/M"` block attr; `core/cover` also accepts the alias `aspect-ratio` value list `"1"`, `"4/3"`, `"16/9"`, `"21/9"` | If the value is one of the standard tokens, prefer the token form for editor UI consistency. |
| `aspect-ratio` (other blocks) | ⚠ | `style.dimensions.aspectRatio:"N/M"` if block supports it (check `full-inventory.json`); else wrap in `core/cover` or drop + log. | |
| `object-fit:cover/contain` (`core/image`) | ✅ | `style.dimensions.objectFit:"cover"` (where supported) | |

---

## Category 7 — Responsive (breakpoints, viewport-relative units)

Gutenberg's responsive support is partial. Emit what exists; drop + log the rest.

| Source pattern | Capture | Notes |
|---|---|---|
| `@media (max-width: 640px)` (mobile-only override) | ❌ | Drop + log `{type:"responsive",breakpoint:"sm",detail:"..."}`. Merchant edits per-breakpoint in WP UI. |
| `@media (max-width: 1024px)` (tablet) | ❌ | Same. |
| Stack-on-mobile (`flex-direction:column on small`) | ✅ | `core/columns` defaults to `is-stacked-on-mobile`; explicit attr `isStackedOnMobile:true` is the default and may be omitted. |
| Hide-on-mobile (`display:none below sm`) | ⚠ | `core/columns`: per-`core/column` `isStackedOnMobile` flag. Other blocks: drop + log. |
| `clamp()` / `min()` / `max()` font-size | ✅ | Pass through as literal CSS string in `style.typography.fontSize`. Gutenberg accepts arbitrary CSS in `style.*` values. |
| `vw` / `vh` units | ✅ | Pass through. |
| Container queries (`@container`) | ❌ | Drop + log. |

When a section has BOTH a desktop and a mobile variant in source: emit the desktop variant; log the mobile delta in `dropped_features` with a per-breakpoint patch the merchant can paste manually.

---

## Category 8 — Assets (images, video, fonts)

| Asset | Capture | Output | Notes |
|---|---|---|---|
| Image (in zip) | ✅ | `core/image` with placeholder URL `https://cdn.example.com/{filename}`. Preserve `width`, `height`, `alt` from source `<img>` attrs. List in drift report `assets[]`. | Merchant uploads to WP Media Library and replaces the placeholder URL. |
| Image (external CDN URL) | ✅ | `core/image` with the literal URL preserved. Preserve `width`, `height`, `alt`. | No upload action needed. |
| Image with intrinsic ratio | ✅ | Add `aspectRatio` attr (per Category 6). | |
| Image with `loading="lazy"` | ✅ | Default in Gutenberg; no explicit attr needed. | |
| Image with `srcset` | ⚠ | Emit `core/image` with the largest variant URL; drop the srcset (WP Media Library handles responsive). Log retained URL in drift report. | |
| SVG icon (inline `<svg>` in JSX) | ⚠ | If matches a known SureCart icon name → `surecart/icon` block. Else: drop + log + suggest `core/image` post-paste with uploaded SVG. | |
| Video (`<video src=...>`) | ✅ | `core/video` with placeholder URL. Preserve `poster`, `controls`, `loop`, `muted`. | |
| Video (YouTube/Vimeo embed URL) | ✅ | `core/embed` with `url` and `providerNameSlug`. | |
| Custom font (`@font-face` in CSS) | ❌ | Drop + log `{type:"custom-font",family:"<name>",detail:"..."}`. Merchant must add the font to the theme via theme.json or the WP fonts library. | Only the 3 SureCart fonts (display/body/mono) are emittable as slugs today. |
| Background image (`background-image: url(...)`) | ✅ | `core/cover` with `url` attr; preserve `dimRatio`, `overlayColor`, `focalPoint`. Drop bare CSS `background-image` on non-cover wrappers. | |
| Icon font / icon library reference (`<i className="lucide-arrow">`) | ❌ | Drop + log + suggest `surecart/icon` (if SureCart has it) or post-paste `core/image`. | |

---

## Drift report — required fields after Phase 2

Every emission must include these fields in the `dropped_features` array when applicable. **Empty array is acceptable; missing field is not.**

```json
"dropped_features": [
  { "type": "gradient", "mode": "linear|radial", "section": "Hero", "detail": "linear-gradient(135deg, #01824C 0%, #042F2E 100%)", "merchant_action": "Add `background: linear-gradient(135deg, #01824C 0%, #042F2E 100%)` to .wp-block-group.{section-class} via theme stylesheet" },
  { "type": "shadow", "section": "Highlights cards", "detail": "0 4px 16px rgba(0,0,0,0.1)", "merchant_action": "Add `box-shadow: 0 4px 16px rgba(0,0,0,0.1)` to the card class via theme stylesheet" },
  { "type": "backdrop-filter", "section": "Header", "detail": "saturate(180%) blur(8px)", "merchant_action": "Add `backdrop-filter: saturate(180%) blur(8px)` to the header wrapper class" },
  { "type": "transform", "section": "Decorative badge", "detail": "rotate(-3deg)", "merchant_action": "Add `transform: rotate(-3deg)` to the badge wrapper class" },
  { "type": "animation", "section": "Hero", "detail": "transition: transform 0.3s", "merchant_action": "Add `transition: transform 0.3s` to the relevant class; pair with hover state in theme CSS" },
  { "type": "responsive", "breakpoint": "sm", "section": "Hero", "detail": "mobile-only padding override", "merchant_action": "In WP block editor, switch device preview to Mobile and adjust padding on the Hero core/group" },
  { "type": "custom-font", "family": "DM Sans", "detail": "@font-face declaration in source CSS", "merchant_action": "Add DM Sans to the theme via theme.json or WP fonts library; then use slug `dm-sans` instead of `surecart-display`" }
]
```

Plus a top-level `assets[]` array for image/video extraction:

```json
"assets": [
  { "type": "image", "filename": "hero-iphone.jpg", "width": 1600, "height": 1200, "alt": "iPhone 17 Pro in titanium finish", "placeholder_url": "https://cdn.example.com/hero-iphone.jpg" },
  { "type": "image", "filename": "feature-camera.png", "width": 800, "height": 600, "alt": "Camera close-up", "placeholder_url": "https://cdn.example.com/feature-camera.png" }
]
```

Merchant action: upload each `filename` to WP Media Library, copy the resulting URL, search the markup for `placeholder_url`, replace.

---

## Quick triage — when in doubt

1. **Captureable in attrs?** Emit per Tables A/B in `style-conversion.md`.
2. **Not captureable but expressible in CSS?** Drop + log + `merchant_action` snippet.
3. **Not captureable and not CSS?** Drop + log + describe the loss in `dropped_features.detail`.
4. **Empty / zero / default value?** Per B-18, emit explicit zero (`"padding":"0px"`) only when the design specifies it. Do NOT emit zero defaults for properties the design doesn't mention.

---

## Done?

When every section has walked categories 1–8 and every drop has a logged escape hatch: proceed to Step 4 (emit) of SKILL.md.
