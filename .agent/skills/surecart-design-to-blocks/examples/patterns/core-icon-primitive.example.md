# `core/icon` Primitive — v7.12 Reference Mini-Exemplar

Canonical paste-form recipes for WordPress 7.0's native `core/icon` block. **This is the v7.12 reference** for any decorative icon glyph in a design that matches one of the 88 built-in slugs (`reference/wp-core-blocks.md § core/icon`). For custom illustrative art (sun rays, lightning bolts, sparkles, water droplets, looms, etc.), the gold references — Aurora Lamp v7.6, Morning Glow Serum v7.7, Loom & Ash Throw v7.8 — remain authoritative; their inline-SVG `core/html` L1 emissions are correct because the glyphs don't match any built-in slug.

**Precedence ladder (HC#32):**

1. **`core/icon`** — design's glyph matches a built-in slug → emit this. (See lookup table below.)
2. **`core/image`** — custom brand glyph (logo mark, illustrative art) that exists as an SVG/PNG file URL.
3. **`core/html` L1** — last resort for novel inline SVG that matches neither.

---

## Recipe 1 — Minimal decorative icon

For a single decorative arrow / chevron / check / etc. with no special styling:

```html
<!-- wp:icon {"icon":"core/check"} /-->
```

Renders at the SVG's intrinsic `viewBox` size (24×24px default), `currentColor` stroke, `aria-hidden="true"` + `focusable="false"` (decorative — screen readers skip it).

---

## Recipe 2 — Icon with literal-hex color + custom size

For brand-colored icons sized to fit a design (typical for highlight-card glyphs at 32px):

```html
<!-- wp:icon {"icon":"core/shield","style":{"dimensions":{"width":"32px"},"color":{"text":"#01824C"}}} /-->
```

`style.dimensions.width` and `style.color.*` are both `__experimentalSkipSerialization` → applied to the inner `<svg>` via style-engine, NOT mirrored on the wrapper `<div>`. Do NOT add `has-text-color` or `has-{slug}-color` classes — no `save()` exists; the wrapper is server-emitted.

---

## Recipe 3 — Icon with slug color + ariaLabel (interactive, semantic)

For icons that are part of a button or link (cart icon in nav, search prefix in a filter row):

```html
<!-- wp:icon {"icon":"core/cart","textColor":"surecart-brand","ariaLabel":"Open cart"} /-->
```

`ariaLabel` set → SVG gets `role="img"` + `aria-label="Open cart"` (announced by screen readers). Empty / omitted → decorative mode (recipe 1 / 2).

---

## Recipe 4 — P3 vertical icon-text card (v7.12 replacement for inline-SVG cards)

The single biggest emission win in v7.12 — when a design has feature cards with built-in-slug glyphs, the entire P3 primitive becomes paste-safer:

```html
<!-- wp:group {"style":{"spacing":{"padding":"24px","blockGap":"12px"},"border":{"radius":"16px","width":"1px","color":"#E6DCCB","style":"solid"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color" style="border-color:#E6DCCB;border-style:solid;border-width:1px;border-radius:16px;padding:24px">
  <!-- wp:icon {"icon":"core/shield","style":{"dimensions":{"width":"32px"},"color":{"text":"#01824C"}}} /-->
  <!-- wp:heading {"level":3,"fontFamily":"surecart-display","style":{"typography":{"fontSize":"18px","fontWeight":"600"}}} -->
  <h3 class="wp-block-heading has-surecart-display-font-family" style="font-size:18px;font-weight:600">Lifetime warranty</h3>
  <!-- /wp:heading -->
  <!-- wp:paragraph {"fontFamily":"surecart-body","style":{"typography":{"fontSize":"14px","lineHeight":"1.5"}}} -->
  <p class="has-surecart-body-font-family" style="font-size:14px;line-height:1.5">Every unit is covered for life — no questions asked.</p>
  <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
```

**Previous form (still valid for custom art glyphs — see gold refs):**

```html
<!-- wp:html -->
<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#01824C" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 4.5-3.5 8-8 9-4.5-1-8-4.5-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg>
<!-- /wp:html -->
```

The two forms RENDER the same shield-with-check at 32px in `#01824C`. The `core/icon` form:
- Doesn't require `unfiltered_html` capability (works for Contributor/Author roles too).
- Theme-overridable via `.wp-block-icon svg` CSS selector.
- Schema-clean (no set-equality drift risk; no soft-wrap line-break risk inside the SVG markup).

**Caveat:** `core/shield`'s glyph is a plain shield, NOT shield-with-check. If the design needs the composite glyph, stick with `core/html` L1 — the gold references are correct in doing so.

---

## Recipe 5 — Inline review summary with star icons (v7.12 — augments B-25)

P8 inline review summary used `surecart/product-review-average-rating-stars` (the SureCart auto-rendered star row). When a design wants STATIC stars (e.g., a marketing testimonial card not tied to a live review) at a known fixed rating, emit explicit `core/icon` stars:

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"2px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
  <!-- wp:icon {"icon":"core/star-filled","style":{"dimensions":{"width":"16px"},"color":{"text":"#F59E0B"}}} /-->
  <!-- wp:icon {"icon":"core/star-filled","style":{"dimensions":{"width":"16px"},"color":{"text":"#F59E0B"}}} /-->
  <!-- wp:icon {"icon":"core/star-filled","style":{"dimensions":{"width":"16px"},"color":{"text":"#F59E0B"}}} /-->
  <!-- wp:icon {"icon":"core/star-filled","style":{"dimensions":{"width":"16px"},"color":{"text":"#F59E0B"}}} /-->
  <!-- wp:icon {"icon":"core/star-half","style":{"dimensions":{"width":"16px"},"color":{"text":"#F59E0B"}}} /-->
</div>
<!-- /wp:group -->
```

For the live-rating case (rating value driven by actual reviews), use `surecart/product-review-average-rating-stars` per B-25 — `core/icon` is for static marketing chrome, NOT data-bound ratings.

---

## Recipe 6 — CTA with trailing arrow

For a "Learn more" / "Continue" / "View all" link with an arrow accent:

```html
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
  <!-- wp:button {"className":"is-style-outline","style":{"typography":{"fontWeight":"500"}}} -->
  <div class="wp-block-button is-style-outline">
    <a class="wp-block-button__link wp-element-button" href="#" style="font-weight:500">View all collections</a>
  </div>
  <!-- /wp:button -->
  <!-- wp:icon {"icon":"core/arrow-right","style":{"dimensions":{"width":"20px"}}} /-->
</div>
<!-- /wp:buttons -->
```

The icon lives as a sibling of the button (NOT inside the button anchor — `core/button` doesn't accept inner blocks, and an inline-decoration in the anchor's `text` would have to be a raw `›` character or a hand-coded `core/html` swap).

---

## Recipe 7 — Search input prefix icon (shop-page filter row)

For the magnifier inside a search input chrome (shop-page header row, sticky filter sidebar):

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"8px","padding":{"top":"10px","right":"14px","bottom":"10px","left":"14px"}},"border":{"radius":"8px","width":"1px","color":"#E6DCCB"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group has-border-color" style="border-color:#E6DCCB;border-width:1px;border-radius:8px;padding-top:10px;padding-right:14px;padding-bottom:10px;padding-left:14px">
  <!-- wp:icon {"icon":"core/search","style":{"dimensions":{"width":"18px"},"color":{"text":"#7A6D5C"}}} /-->
  <!-- wp:surecart/product-list-search /-->
</div>
<!-- /wp:group -->
```

Same pattern works for prefix glyphs in any input chrome (cart-quantity input, coupon code input, etc.).

---

## Lookup table — most-emitted slugs by design context

| Design intent | Slug |
|---|---|
| right arrow / "continue" / "next" / CTA accent | `core/arrow-right` |
| down chevron / accordion expander / dropdown caret | `core/chevron-down` |
| up chevron / collapse / scroll-to-top | `core/chevron-up` |
| check mark / feature tick / included badge | `core/check` |
| cart icon (mini-cart, nav cart) | `core/cart` |
| star (rating) — full / empty / half | `core/star-filled` / `core/star-empty` / `core/star-half` |
| plus / "add" / expand toggle | `core/plus` |
| search / magnifier (input prefix) | `core/search` |
| info circle / tooltip trigger | `core/info` |
| menu / hamburger | `core/menu` |
| shield (warranty, security badge) | `core/shield` |
| share / social-share trigger | `core/share` |
| home / breadcrumb root | `core/home` |
| envelope / mail / contact | `core/envelope` |
| external link indicator | `core/external` |
| bell / notifications | `core/bell` |
| caution / warning / alert | `core/caution` |
| help / question / "?" | `core/help` |
| settings / gear | `core/settings` |
| download | `core/download` |
| upload | `core/upload` |
| payment (generic card glyph) | `core/payment` |
| pencil / edit | `core/pencil` |
| trash / delete | (no `core/trash` — use `core/html` L1 with a custom path) |
| close / X / dismiss | (no `core/close` — use `core/html` L1, or `core/plus` rotated 45° via theme CSS) |

Full 88-slug catalog: `reference/wp-core-blocks.md § core/icon`.

---

## Rubric pointers

When emitting `core/icon`, the following rubric items apply:

- **HC#32** (SKILL.md) — overall precedence ladder + paste rules.
- **B-28** (rubric/self-validate.md) — Tier B fidelity item flagging inline-SVG `core/html` blocks that should be `core/icon`.
- **HC#12** (SKILL.md) — comment marker is unprefixed `wp:icon`, NOT `wp:core/icon`.

When in doubt about a specific glyph, run the design's SVG path against the 88-slug catalog visually. If it's a close-but-not-exact match (e.g., 4-pt sparkle vs. 5-pt `core/star-filled`), prefer `core/html` L1 — the slug substitution would lose visual fidelity.

---

## When NOT to retrofit existing exemplars

The three paste-tested gold references (Aurora Lamp v7.6, Morning Glow Serum v7.7, Loom & Ash Throw v7.8) emit inline-SVG `core/html` for their decorative glyphs. **Do not retrofit those exemplars** — their SVGs are custom illustrative art (sun rays, lightning bolts, sparkle stars, water droplets, looms, microphones, shield-with-check composites) that doesn't cleanly map to any built-in `core/icon` slug. Retrofitting would dilute the version-lock contract those gold refs encode (paste-tested AS-IS at a specific WP version) without gaining any fidelity.

Going forward, NEW emissions should follow this exemplar's recipes — emit `core/icon` for built-in slugs, fall back to `core/image` for custom-art file URLs, and reserve `core/html` L1 for novel inline SVG. Future paste-tested gold refs will naturally use `core/icon` where appropriate.
