# Core Blocks Cheatsheet — Paired-Block Contract

WordPress core blocks fall into two families: **self-closing** (their server-side render decides everything) and **paired** (their `save()` JS function emits an HTML wrapper that MUST be in the markup, or Gutenberg's `validateBlock` flags every instance with "Attempt block recovery").

Getting this wrong is the #1 cause of recovery prompts in v1/v2/v4 output. The fix is mechanical — just match the exemplars below.

---

## Paired blocks (NEVER self-close — wrap in a `<div class="wp-block-{name}">…</div>`)

```
core/columns          core/column           core/group
core/cover            core/media-text       core/buttons
core/list             core/list-item        core/quote
core/details
```

Wrong:
```html
<!-- wp:core/columns /-->
```

Right:
```html
<!-- wp:core/columns -->
<div class="wp-block-columns">…children…</div>
<!-- /wp:core/columns -->
```

---

## Exemplar 1 — `core/columns` (2-column hero)

JSX source:
```jsx
<div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "60px" }}>
  <div><BlockTag blocks={["surecart/product-media"]} />…</div>
  <div><BlockTag blocks={["surecart/product-title"]} />…</div>
</div>
```

Output:
```html
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"60px","left":"60px"}}}} -->
<div class="wp-block-columns alignwide">
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:surecart/product-media /-->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:surecart/product-title /-->
  </div>
  <!-- /wp:column -->
</div>
<!-- /wp:columns -->
```

**Note:** `gap` on a flex/grid container becomes `style.spacing.blockGap` on the parent `core/columns`, NOT on each `core/column`.

---

## Exemplar 2 — `core/columns` (4-column highlights grid)

JSX:
```jsx
<div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: 24 }}>
  {HIGHLIGHTS.map(h => <Card icon={h.icon} title={h.title} body={h.body} />)}
</div>
```

`repeat(4, 1fr)` → 4 columns. `.map()` over a literal array → expand to N siblings (emit each card as a `core/column` with the data baked in).

```html
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"24px","left":"24px"}}}} -->
<div class="wp-block-columns">
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} --><h3 class="wp-block-heading">A19 Pro Bionic</h3><!-- /wp:heading -->
    <!-- wp:paragraph --><p>The fastest chip ever in a smartphone…</p><!-- /wp:paragraph -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} --><h3 class="wp-block-heading">48MP Pro Fusion</h3><!-- /wp:heading -->
    <!-- wp:paragraph --><p>A redesigned triple-lens system…</p><!-- /wp:paragraph -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} --><h3 class="wp-block-heading">29-hour battery</h3><!-- /wp:heading -->
    <!-- wp:paragraph --><p>The longest battery life ever…</p><!-- /wp:paragraph -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Aerospace titanium</h3><!-- /wp:heading -->
    <!-- wp:paragraph --><p>Grade-5 titanium frame…</p><!-- /wp:paragraph -->
  </div>
  <!-- /wp:column -->
</div>
<!-- /wp:columns -->
```

---

## Exemplar 3 — `core/group` (section container with padding + slug background) — **Variant A: slug-bg only**

> Pair with **Exemplar 3.5** (literal-bg variant) below. The two together cover the Rule 0 / Item 10 hierarchy:
> - **Variant A (this exemplar)**: slug background → wrapper has slug class + NO inline `style=""` (Rule 0).
> - **Variant B (Exemplar 3.5)**: literal-hex background → wrapper has full inline mirrors of bg + border + padding (Rule 0 carve-out per Item 10).

JSX: `<section style={{ padding: 96, background: "var(--bg-dark)" }}>…</section>`

Output (Rule 0 — paired-block wrapper, NO inline `style=""`):
```html
<!-- wp:group {"style":{"spacing":{"padding":{"top":"96px","right":"96px","bottom":"96px","left":"96px"}}},"backgroundColor":"surecart-bg-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-bg-dark-background-color has-background">
  …children…
</div>
<!-- /wp:group -->
```

**Note:** when you use `backgroundColor` slug, also add `has-{slug}-background-color has-background` to the wrapper `<div>`'s class. Gutenberg's `save()` does this automatically when it serializes — your hand-written wrapper must match. The padding lives in the comment-marker JSON only — Rule 0 strips it from inline `style=""`.

---

## Exemplar 3.5 — `core/group` card (literal-bg variant: bg + border + padding)

A "card" — a `core/group` child of `core/column` with literal-hex background + border + padding. Common in highlights grids, feature cards, info panels. Two variants depending on whether the card uses a slug-bg or literal-bg.

### Variant A — Literal-bg (theme-independent rendering)

Use this when the card needs to render visibly on ANY theme, including classic themes that don't enqueue `wp-block-library` global styles. Per Rule 0's literal-bg carve-out, a `core/group` wrapper with `style.color.background` literal hex MAY have inline `style=""` mirroring all of `border-color`, `border-width`, `border-radius`, `background-color`, and `padding-*`.

JSX source: `<div style={{ background: "#FFFFFF", border: "1px solid #E5E7EB", borderRadius: 16, padding: 28 }}>…</div>`

Output:
```html
<!-- wp:group {"style":{"color":{"background":"#FFFFFF"},"border":{"color":"#E5E7EB","width":"1px","style":"solid","radius":"16px"},"spacing":{"padding":{"top":"28px","right":"28px","bottom":"28px","left":"28px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-background" style="border-color:#E5E7EB;border-style:solid;border-width:1px;border-radius:16px;background-color:#FFFFFF;padding-top:28px;padding-right:28px;padding-bottom:28px;padding-left:28px">
  …card content (heading + paragraph etc.)…
</div>
<!-- /wp:group -->
```

**Critical details (v7.3 — production-pattern-aligned):**

1. **Class order:** for SLUG borderColor: `wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-border-color has-{slug}-border-color has-background`. For LITERAL hex border: `wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-background` (NO `has-border-color` — v7.3 has-border-color emission policy: literal-hex border omits the class to avoid theme `currentColor` override → black borders).
2. **Inline-style declaration order:** `border-color; border-style; border-width; border-radius; background-color; padding-top; padding-right; padding-bottom; padding-left`. Save() emits in this exact sequence (production-pattern-verified at `examples/patterns/product-physical.example.md:42,87,94`).
3. **DO emit `border-style:solid`** in inline style AND `style.border.style:"solid"` in JSON. v7.3 reversed the v5.6 reversal. Production patterns ALL include `border-style:solid` in literal-hex card chrome — verified across 30+ occurrences.
4. **`style.border.style:"solid"` MUST be in JSON** when `border.color` or `border.width` is set, so the round-trip parser doesn't strip it.
5. **Applies at any nesting depth.** Rule 0's literal-bg carve-out is per-wrapper, not per-tree-position.

### Variant B — Slug-bg (theme-overrideable, but theme-dependent)

Use this when the card's background should adapt to theme overrides. Output emits slug class only, NO inline style on wrapper.

```html
<!-- wp:group {"backgroundColor":"surecart-white","style":{"border":{"radius":"16px","width":"1px","color":"#E5E7EB"},"spacing":{"padding":{"top":"28px","right":"28px","bottom":"28px","left":"28px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-white-background-color has-border-color has-background">
  …card content…
</div>
<!-- /wp:group -->
```

Trade-off: on classic themes that don't enqueue global styles, `--wp--preset--color--surecart-white` resolves to empty and the card renders **transparent / chromeless**. Variant A renders correctly on ANY theme; Variant B requires theme-side preset support.

**When to pick which:** prefer Variant A for cards in fixtures and merchant-facing output where theme-independence matters. Variant B for designs that explicitly want theme color recoloring (rare for product page templates).

**Resolves the apparent Rule 0 / A-10 tension:** Rule 0 says "no inline style on paired wrappers". Item A-10 says "literal style.* attrs MUST mirror to inline". These conflict for slug-bg cards but align for literal-bg cards. Hierarchy: Rule 0 is a slug-only-on-wrapper rule (slug bg → no inline, anywhere); A-10 takes over on literal-bg and ALL inline mirrors are required.

---

## Exemplar 4 — `core/media-text` (image left + text right)

```html
<!-- wp:media-text {"mediaPosition":"left","mediaWidth":50,"verticalAlignment":"center"} -->
<div class="wp-block-media-text has-media-on-the-left is-stacked-on-mobile is-vertically-aligned-center">
  <figure class="wp-block-media-text__media">
    <!-- wp:image {"id":123} -->
    <figure class="wp-block-image"><img src="https://merchant.com/path.jpg" alt="" /></figure>
    <!-- /wp:image -->
  </figure>
  <div class="wp-block-media-text__content">
    <!-- wp:heading -->
    <h2 class="wp-block-heading">…</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph --><p>…</p><!-- /wp:paragraph -->
  </div>
</div>
<!-- /wp:media-text -->
```

`mediaPosition`: `"left"` or `"right"`. `mediaWidth`: percentage 0–100. `verticalAlignment`: `"top"`, `"center"`, `"bottom"`.

---

## Exemplar 5 — `core/details` (FAQ accordion item)

`.map()` over `FAQ_ITEMS` → one `<!-- wp:details -->` per item.

**Critical: every `<!-- wp:details -->` opener MUST carry a `summary` attribute matching the inner `<summary>` text.** Gutenberg's deserializer parses summary from the HTML and re-serializes the block with the attr filled in — if the saved attrs are empty, the round-trip fails and you get "Attempt block recovery" on every FAQ item.

```html
<!-- wp:details {"summary":"Is the iPhone 17 Pro unlocked?"} -->
<details class="wp-block-details">
  <summary>Is the iPhone 17 Pro unlocked?</summary>
  <!-- wp:paragraph -->
  <p>Yes. Every iPhone 17 Pro purchased here ships SIM-free and works with any carrier.</p>
  <!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
```

For multiple FAQ items, repeat the whole block N times — each is a separate `core/details` with its own `summary` attr.

---

## Exemplar 6 — `core/buttons` (CTA pair, NOT for product buy buttons)

```html
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
  <!-- wp:button {"backgroundColor":"surecart-brand","textColor":"surecart-white"} -->
  <div class="wp-block-button">
    <a class="wp-block-button__link has-surecart-white-color has-surecart-brand-background-color has-text-color has-background wp-element-button" href="#">Get started</a>
  </div>
  <!-- /wp:button -->
  <!-- wp:button {"className":"is-style-outline"} -->
  <div class="wp-block-button is-style-outline">
    <a class="wp-block-button__link wp-element-button" href="#">Learn more</a>
  </div>
  <!-- /wp:button -->
</div>
<!-- /wp:buttons -->
```

⚠️ **For "Add to Cart" / "Buy Now" buttons on a product page, use `surecart/product-buy-buttons`, NOT `core/buttons`.** See `reference/product-page-blocks.md`.

### Variant — button with literal-px fontSize + slug fontFamily (v7.13)

When the design specifies a literal pixel `fontSize` on the button (e.g., `16px`), save() emits the `has-custom-font-size` class on the inner `<a>`. The canonical class order is:

```
wp-block-button__link has-text-color has-background has-{slug}-font-family has-custom-font-size wp-element-button
```

**`wp-element-button` is the FINAL class (trailing indicator).** Same trailing pattern as `has-text-color` / `has-background` in B-22.

```html
<!-- wp:button {"style":{"color":{"background":"#2A2620","text":"#FBF7F2"},"spacing":{"padding":{"top":"16px","right":"32px","bottom":"16px","left":"32px"}},"border":{"radius":"9999px"},"typography":{"fontSize":"16px","fontWeight":"500"}},"fontFamily":"surecart-body"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background has-surecart-body-font-family has-custom-font-size wp-element-button" href="#subscribe" style="border-radius:9999px;color:#FBF7F2;background-color:#2A2620;padding-top:16px;padding-right:32px;padding-bottom:16px;padding-left:32px;font-size:16px;font-weight:500">Start subscription — $42 / month</a></div>
<!-- /wp:button -->
```

**Class set summary (per A-34):**

| Condition | Class to add |
|---|---|
| Always | `wp-block-button__link` |
| `style.color.text` set | `has-text-color` |
| `style.color.background` set | `has-background` |
| `fontFamily:"<slug>"` set | `has-{slug}-font-family` |
| `style.typography.fontSize:"Npx"` (literal) set | `has-custom-font-size` |
| `fontSize:"<slug>"` (preset) set | `has-{slug}-font-size` (NOT `has-custom-font-size`) |
| Always (TRAILING) | `wp-element-button` |

**Failure signature (A-34):** `Expected class "...has-{slug}-font-family has-custom-font-size wp-element-button", saw "...wp-element-button has-{slug}-font-family"` — recovery on the button. Empirically verified Morning Glow v2 paste-test (2026-05-26).

---

## Exemplar 6.5 — `core/table` (data table — figure wraps table, classes split)

`core/table` has a tricky structure: classes split between `<figure>` and `<table>`. Get this wrong and recovery triggers on every table.

```html
<!-- wp:table {"className":"is-style-stripes"} -->
<figure class="wp-block-table is-style-stripes">
  <table class="has-fixed-layout">
    <tbody>
      <tr>
        <td class="has-text-align-left" data-align="left">Display</td>
        <td>6.3″ Super Retina XDR · ProMotion 1–120Hz</td>
      </tr>
      <tr>
        <td class="has-text-align-left" data-align="left">Chip</td>
        <td>Apple A19 Pro · 6-core CPU · 16-core Neural Engine</td>
      </tr>
    </tbody>
  </table>
  <figcaption class="wp-element-caption">Optional caption text</figcaption>
</figure>
<!-- /wp:table -->
```

**Mandatory rules:**
- `wp-block-table` → on `<figure>` (NOT `<table>`)
- `has-fixed-layout` → on `<table>` (default `hasFixedLayout:true` is **always** emitted; missing this is the #1 table recovery trigger)
- `is-style-stripes`/`is-style-regular` → on `<figure>` via `className` attr
- Color/border classes (when set) → on `<table>` (table uses `__experimentalSkipSerialization` for color/border; manually re-applied)
- Cell alignment: BOTH `class="has-text-align-{value}"` AND `data-align="{value}"` on each `<td>`/`<th>` (parser reads `data-align` to round-trip the attr)
- Caption: `<figcaption class="wp-element-caption">` INSIDE `<figure>` (NEVER `<caption>` inside `<table>`)

If you don't need actual tabular data with headers, prefer `core/list` (Exemplar 7 below) — simpler markup, fewer recovery surfaces.

## Exemplar 7 — `core/list` (plain bulleted list ONLY)

**v7.6 paste-test correction.** `core/list`'s `save()` emits `<ul class="wp-block-list …">` — the `wp-block-list` class is MANDATORY. Omitting it triggers `"Expected attribute class of value 'wp-block-list …', saw '…'"` recovery on every paste.

```html
<!-- wp:list -->
<ul class="wp-block-list">
  <!-- wp:list-item -->
  <li>USB-C power adapter (6 ft)</li>
  <!-- /wp:list-item -->
  <!-- wp:list-item -->
  <li>Quick-start guide</li>
  <!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
```

For ordered: `<!-- wp:list {"ordered":true} -->` and `<ol class="wp-block-list">`.

### ⚠️ DO NOT use `core/list` for 2-column key:value tabular data (v7.6)

`<li>` content with inline-styled `<strong>/<span>` siblings (`<li><strong style="…">Display:</strong><span style="…">6.3″ XDR</span></li>`) triggers the deprecated `wp.blocks.children.matcher` path (deprecated since WP 6.1, slated for 6.3 removal). The deprecated matcher mis-serializes the content → set-equality fails → recovery cascades.

**For 2-column tabular data (specs tables, comparison tables), use the P7 detail-row primitive instead** — parent `core/group` (vertical flex) wrapping N detail-row `core/group` (`layout.type:"flex",flexWrap:"nowrap",justifyContent:"space-between"`), each containing 2 `core/paragraph` siblings (label muted + value primary):

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">

<!-- wp:group {"style":{"spacing":{"padding":{"top":"16px","bottom":"16px"}}},"border":{"bottom":{"color":"#F0EBE0","width":"1px"},"top":[],"left":[],"right":[]}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-bottom-color:#F0EBE0;border-bottom-width:1px;padding-top:16px;padding-bottom:16px">
<!-- wp:paragraph {"style":{"color":{"text":"#8A8278"}}} --><p style="color:#8A8278">Display</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"color":{"text":"#1A1714"}}} --><p style="color:#1A1714">6.3″ Super Retina XDR</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- repeat per row -->

</div>
<!-- /wp:group -->
```

Plain bulleted lists (no inline styling, plain text `<li>` content like "USB-C power adapter") remain fine with `core/list`.

---

## Exemplar 7.5 — `core/image` (strict-schema; wrap in `core/group` for flex-width)

**v7.6 paste-test correction (refined by v7.10).** `core/image`'s `save()` emits a tightly controlled set of inline styles on `<img>`: `border-radius:Npx` (when `style.border.radius` is set), and `width:Npx`/`height:Npx` (when top-level `width`/`height` block attrs are set, NOT `style.dimensions.*`). Adding ANY OTHER freehand inline style to `<figure>` or `<img>` (`flex-basis`, `aspect-ratio`, `object-fit`, etc.) triggers set-equality drift: `"Expected attributes [Array(3)], instead saw (2) [Array(3), Array(3)]"`. The block has NO `aspectRatio` attr in its schema (unlike `core/cover`); `aspect-ratio` cannot be expressed through `core/image` JSON.

**v7.10 correction (Northwind paste-test, 2026-05-13):** when `width:"Npx"` and `height:"Npx"` are set, they emit as **inline-style** on `<img>` — NOT as `width="N" height="N"` HTML attrs. Hand-writing `<img ... width="32" height="32"/>` is set-drift even though the rendered visual looks identical. Combined inline order with border-radius: `border-radius:Npx;width:Npx;height:Npx`.

### Canonical schema-clean form

Without size attrs:

```html
<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"16px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="https://cdn.example.com/hero.jpg" alt="Aurora lamp on a desk" style="border-radius:16px"/></figure>
<!-- /wp:image -->
```

With width + height set (fixed-size icons):

```html
<!-- wp:image {"sizeSlug":"thumbnail","width":"32px","height":"32px"} -->
<figure class="wp-block-image size-thumbnail is-resized"><img src="https://cdn.example.com/icon.svg" alt="" style="width:32px;height:32px"/></figure>
<!-- /wp:image -->
```

With width + height + border-radius (avatars, circular icons):

```html
<!-- wp:image {"sizeSlug":"thumbnail","width":"36px","height":"36px","style":{"border":{"radius":"36px"}}} -->
<figure class="wp-block-image size-thumbnail is-resized has-custom-border"><img src="https://cdn.example.com/avatar.jpg" alt="" style="border-radius:36px;width:36px;height:36px"/></figure>
<!-- /wp:image -->
```

Class set rules:
- `wp-block-image size-{slug}` — always
- `is-resized` — when `width` OR `height` is set on the block (any value)
- `has-custom-border` — when ANY `style.border.*` is set

The `has-custom-border` class is emitted by save() whenever ANY `style.border.*` is present.

### For flex-width control inside a flex-row parent (P5 multi-card row, gallery grids)

DON'T put `flex-basis:48%` on the figure. Wrap each image in a `core/group` carrying the width:

```html
<!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"48%"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"16px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="https://cdn.example.com/aurora-bedroom.jpg" alt="Aurora lamp on a bedside table" style="border-radius:16px"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
```

For a 2×2 gallery, repeat 4× inside a parent `core/group` flex with `flexWrap:"wrap"`. For 4-up, use `flexSize:"23%"`; 3-up `"31%"`; 2-up `"48%"`.

### Aspect-ratio, object-fit, width restoration

These are paste-recovery triggers when on `core/image`. To restore the design's `aspect-ratio: 4/3` or `object-fit: cover`, drop+log under `dropped_features` with a CSS-snippet `merchant_action`:

```json
{"type":"asset_style","section":"Gallery","detail":"aspect-ratio 4/3 + object-fit:cover on gallery tiles","merchant_action":"add `.wp-block-image img { aspect-ratio: 4/3; object-fit: cover; width: 100% }` to theme stylesheet"}
```

---

## Exemplar 8 — `core/cover` (background image / video / gradient with overlay)

`core/cover` is the **only** WP core block that supports `gradient`, `aspectRatio`, and `customGradient` attrs. Hero sections with full-bleed backgrounds belong here, not on `core/group`.

### Variant A — image background with dim overlay

Source:
```jsx
<section style={{
  position: "relative",
  background: `url('/hero.jpg') center/cover`,
  minHeight: 480,
  color: "#FFFFFF",
}}>
  …content…
</section>
```

Output:
```html
<!-- wp:cover {"url":"https://cdn.example.com/hero.jpg","dimRatio":50,"minHeight":480,"minHeightUnit":"px","aspectRatio":"16/9","layout":{"type":"constrained"}} -->
<div class="wp-block-cover" style="min-height:480px;aspect-ratio:16/9">
  <span aria-hidden="true" class="wp-block-cover__background has-background-dim-50 has-background-dim"></span>
  <img class="wp-block-cover__image-background" alt="" src="https://cdn.example.com/hero.jpg" data-object-fit="cover"/>
  <div class="wp-block-cover__inner-container">
    …content…
  </div>
</div>
<!-- /wp:cover -->
```

### Variant B — gradient background (no image) — **v7.7 CORRECTION**

Source:
```jsx
<section style={{
  background: "linear-gradient(135deg, #01824C 0%, #042F2E 100%)",
  minHeight: 480,
}}>
```

**Canonical output (v7.7 — corrects v7.0 class set):**
```html
<!-- wp:cover {"customGradient":"linear-gradient(135deg,#01824C 0%,#042F2E 100%)","minHeight":480,"minHeightUnit":"px"} -->
<div class="wp-block-cover" style="min-height:480px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(135deg,#01824C 0%,#042F2E 100%)"></span><div class="wp-block-cover__inner-container">
  …content…
</div></div>
<!-- /wp:cover -->
```

**Key empirical findings (v7.7, Morning Glow paste-test 2026-05-11):**

1. **Inner `<span>` class set is `wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient`** — NOT `wp-block-cover__gradient-background has-background-gradient` (the v7.0 docs were wrong).
2. **`dimRatio` defaults to `100`** when `customGradient` is set without explicit `dimRatio` JSON attr. The class `has-background-dim-100` reflects this default.
3. **The outer `<div class="wp-block-cover">` does NOT carry `has-background-gradient`** — that class lives on the inner span only.
4. **The outer inline style is `style="min-height:480px"` only** — no `background:` rule on the outer div (the gradient lives on the inner span).
5. **JSON shape:** the gradient string format is `"linear-gradient(135deg,#A 0%,#B 100%)"` — no space after the comma between the angle/stops (matches `style-engine` normalization).

**⚠️ Recommended fallback:** for decorative side-by-side colored panels (Before/After comparison, dual-tone CTAs, etc.), prefer `core/group` with `style.color.background:"#hex"` literal (using one of the gradient end-stops) over `core/cover` gradient. The `core/group` solid-bg emit:
- has fewer class-set drift risks (no `__background` overlay span shape to get wrong)
- has no inner-container wrapping
- composes cleanly with `core/group` flex layout
- the visual difference vs. a subtle gradient is minor; merchant can add the gradient via theme CSS

Drop+log the gradient under `dropped_features.type:"gradient", mode:"linear"` with a CSS-snippet `merchant_action`.

Example fallback:
```html
<!-- wp:group {"style":{"color":{"background":"#EDE4DA"},"spacing":{"padding":{"top":"160px","right":"24px","bottom":"24px","left":"24px"}},"layout":{"selfStretch":"fixed","flexSize":"50%"}},"layout":{"type":"default"}} -->
<div class="wp-block-group has-background" style="background-color:#EDE4DA;padding-top:160px;padding-right:24px;padding-bottom:24px;padding-left:24px">
  …content…
</div>
<!-- /wp:group -->
```

### Variant C — fixed aspect ratio (no minHeight)

Use when the design specifies `aspect-ratio` instead of `min-height` (common for hero cards in feature grids). **There are TWO JSON paths for aspect-ratio on `core/cover` — and they behave DIFFERENTLY in save() (v7.11 correction):**

| JSON path | Inline `style=""` mirror? | Typical use |
|---|---|---|
| Top-level `aspectRatio:"N/M"` | ✅ YES — emits `aspect-ratio:N/M` inline | Cover with `url` (background-image hero) |
| `style.dimensions.aspectRatio:"N/M"` | ❌ NO — applied via CSS class/var | Cover with `useFeaturedImage:true` (related-products card, template-driven covers) |

#### Variant C1 — top-level `aspectRatio` (with `url`)

```html
<!-- wp:cover {"url":"https://cdn.example.com/feature.jpg","dimRatio":40,"aspectRatio":"4/3"} -->
<div class="wp-block-cover" style="aspect-ratio:4/3">
  …
</div>
<!-- /wp:cover -->
```

Save() inline-mirrors `aspect-ratio:4/3` because the attr is at the top level.

#### Variant C2 — `style.dimensions.aspectRatio` (with `useFeaturedImage:true`)

```html
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top center","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-center" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
  …content…
</div></div>
<!-- /wp:cover -->
```

Save() does NOT inline-mirror `aspect-ratio:3/4` here — only `border-radius` and `margin-bottom`. The aspect-ratio is applied via internal CSS class/variable system at render time. Same routing behavior as `core/group` per HC#25.

**Failure mode:** hand-writing `aspect-ratio:1` in the inline mirror when JSON path is `style.dimensions.aspectRatio` triggers `Block validation failed for core/cover. Generated: style="border-radius:4px;margin-bottom:0", retrieved: style="border-radius:4px;aspect-ratio:1"`.

The canonical Start-Basic template at `reference/product-page-blocks.md:427` uses Variant C2 (the `useFeaturedImage:true` + `style.dimensions.aspectRatio` form). When emitting `surecart/product-list-related` or any `surecart/product-template` inner cover, follow Variant C2 — no aspect-ratio inline.

Standard `aspectRatio` token values Gutenberg accepts: `"1"`, `"4/3"`, `"3/4"`, `"3/2"`, `"2/3"`, `"16/9"`, `"9/16"`, `"21/9"`. Other ratios pass through as literal CSS strings (e.g., `"5/4"`).

Empirically verified Northwind paste-test (2026-05-13, second round).

### Cover rules

- `core/cover` IS the exception to Rule 0 — its outer div DOES mirror inline `style="min-height:Npx;aspect-ratio:N/M"` (Rule 0c).
- Overlay dim class is `has-background-dim-{dimRatio}` (NOT hardcoded 100). When `dimRatio:50`, emit `has-background-dim-50`. When 0, emit `has-background-dim-0` (still required for the inner span).
- `<img>` MUST have `data-object-fit="cover"` attr — Gutenberg's CSS targets it.
- Inner content sits in `<div class="wp-block-cover__inner-container">` — block siblings go inside that, not as direct children of `wp-block-cover`.
- For background videos: same shape, swap `<img>` for `<video class="wp-block-cover__video-background" autoplay muted loop>` + `backgroundType:"video"` attr.

---

## Exemplar 9 — `position:sticky` on `core/group` (sticky headers, sticky sidebars)

> **v7.13 CORRECTION — REVERSES v7.0 doctrine.** Earlier versions of this exemplar said "Inline `style=""` IS allowed here (Rule 0 carve-out for sticky positioning)". Empirical paste-test (Morning Glow v2, 2026-05-26) proved this WRONG: save() does NOT emit `position:sticky;top:Npx;z-index:N` inline on `core/group`. The position routes through the `is-position-sticky` class only — same routing family as HC#25 (`aspectRatio` on group → CSS class/var, not inline) and HC#30 (`style.dimensions.aspectRatio` on cover). See HC#33 / A-33 in `rubric/self-validate.md`.

Source:
```jsx
<header style={{
  position: "sticky",
  top: 0,
  zIndex: 40,
  background: "rgba(255,255,255,0.92)",
}}>
```

Output (v7.13 — corrected):
```html
<!-- wp:group {"style":{"position":{"type":"sticky","top":"0px"},"color":{"background":"#FFFFFF"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-position-sticky has-background" style="background-color:#FFFFFF">
  …header content…
</div>
<!-- /wp:group -->
```

**Key changes from v7.0:**
- NO `position:sticky` in the inline `style=""`.
- NO `top:0px` in the inline `style=""`.
- NO `z-index:40` in the inline `style=""`.
- `is-position-sticky` class is mandatory — it carries ALL the position behavior.
- `style.position.{type,top}` in JSON is preserved; save() applies via class-based CSS.

Rules:
- `is-position-sticky` class is mandatory whenever `style.position.type:"sticky"` is in JSON.
- Inline `style=""` is **NOT** allowed for position/top/z-index on `core/group` (REVERSES v7.0 carve-out). Only `color.background` / `spacing.*` / `border.*` mirror to inline per the standard Rule 0 carve-out for literal-bg.
- `z-index` has no native attr in `core/group`'s block schema. Designs requiring specific z-index → drop+log under `dropped_features.type:"z_index"` with `merchant_action` recommending theme CSS targeting `.is-position-sticky`.
- For sticky add-to-bag bars, prefer the dedicated `surecart/sticky-purchase` block over manual `core/group` (see `examples/sticky-buy-bar.example.md`).

**Failure signature if the v7.0 form is emitted:** `Generated: style="background-color:#FFFFFF", retrieved: style="background-color:#FFFFFF;position:sticky;top:0px;z-index:40"` — recovery cascade on the wrapper. Empirically reproduced Morning Glow v2 paste-test (2026-05-26).

---

## Exemplar 10 — `core/icon` (WP 7.0+ native icon block)

**Canonical doctrine, attribute schema, 88-slug catalog, and full emission rules: `reference/wp-core-blocks.md § core/icon`.** This exemplar shows paste-form recipes only.

Output (always self-closing — `apiVersion: 3`):
```html
<!-- wp:icon {"icon":"core/check"} /-->
```

With color + size:
```html
<!-- wp:icon {"icon":"core/check","style":{"dimensions":{"width":"24px"},"color":{"text":"#01824C"}}} /-->
```

With slug color + ariaLabel:
```html
<!-- wp:icon {"icon":"core/cart","textColor":"surecart-brand","ariaLabel":"Open cart"} /-->
```

Inside a P3 vertical icon-text card (replaces inline-SVG `core/html` L1):
```html
<!-- wp:group {"style":{"spacing":{"padding":"24px","blockGap":"12px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="padding:24px">
  <!-- wp:icon {"icon":"core/shield","style":{"dimensions":{"width":"32px"},"color":{"text":"#01824C"}}} /-->
  <!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Lifetime warranty</h3><!-- /wp:heading -->
  <!-- wp:paragraph --><p>Every unit is covered for life.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
```

**Quick reminders (full rules in wp-core-blocks.md):** comment marker is unprefixed `wp:icon` (HC#12); slug MUST include `core/` prefix; ALWAYS self-close (no save()); align is `left`/`center`/`right` only.

**Precedence:** built-in slug → `core/icon`; custom file → `core/image`; novel inline SVG → `core/html` L1 (last resort).

---

## Self-closing core blocks (these MAY self-close)

```
core/heading       — self-closing if no inner blocks; usually has innerHTML <h{level}>…</h{level}>
core/paragraph     — same
core/image         — has inner <figure>
core/spacer        — truly empty
core/separator     — truly empty
core/site-logo     — server-rendered (block.json `apiVersion: 3`)
core/site-title    — same
core/post-title    — same
core/site-tagline  — same
core/icon          — server-rendered (apiVersion 3) — ALWAYS self-close, no save()
```

**`core/heading` and `core/paragraph` look self-closing but actually have inner HTML** — they're "block with `innerHTML`" pattern, not "self-closing":

```html
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">…</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>…</p><!-- /wp:paragraph -->
```

**`core/spacer` and `core/separator` need their inner element** — do NOT self-close. Their save() emits a wrapper that must be present in the markup:

```html
<!-- wp:spacer {"height":"60px"} -->
<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->
```

---

## Quick scan — paired vs not

| Block | Pattern | Wrapper |
|---|---|---|
| `core/columns` | paired | `<div class="wp-block-columns">` |
| `core/column` | paired | `<div class="wp-block-column">` |
| `core/group` | paired | `<div class="wp-block-group">` |
| `core/cover` | paired | `<div class="wp-block-cover">` |
| `core/media-text` | paired | `<div class="wp-block-media-text">` |
| `core/buttons` | paired | `<div class="wp-block-buttons">` |
| `core/button` | paired | `<div class="wp-block-button">` |
| `core/list` | paired | `<ul class="wp-block-list">` (or `<ol>`) |
| `core/list-item` | inline | `<li>` (direct content; **no wrapper class** — do NOT add `class="wp-block-list-item"`) |
| `core/quote` | paired | `<blockquote class="wp-block-quote">` |
| `core/details` | paired | `<details class="wp-block-details">` |
| `core/heading` | inline | `<h{level} class="wp-block-heading">` |
| `core/paragraph` | inline | `<p>` |
| `core/image` | inline | `<figure class="wp-block-image">` with `<img>` |
| `core/spacer` | empty | `<div class="wp-block-spacer">` |
| `core/separator` | empty | `<hr class="wp-block-separator">` |
| `core/icon` | self-close (apiVersion 3) | `<div class="wp-block-icon">` with `<svg>` (server-rendered, no source wrapper) |
| `surecart/product-*` | self-close OK | server-rendered, no save() output |
