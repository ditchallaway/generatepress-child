# Design Patterns — paste-safe primitives + section archetypes

Lazy-loaded from SKILL.md. **The primary content of this file is the PRIMITIVES library (Part 1 below)** — small paste-safe block-tree fragments the skill composes to express whatever design the merchant provides. Section archetypes (Part 2) are reference compositions, not templates to copy.

**Priority rule:** for every section, prefer **SureCart block > WP core block > custom HTML block**, in that order, when semantics match. SureCart blocks are server-rendered (`apiVersion:3`) and read product/cart/customer state automatically; core blocks are stable Gutenberg primitives; custom HTML is the last resort and triggers no recovery only when wrapped in `core/html`.

Cross-reference `reference/core-blocks-cheatsheet.md` for paired-wrapper shapes, `reference/style-conversion.md` for attrs, `reference/full-inventory.json` for SureCart block attrs.

---

# Part 1 — PASTE-SAFE PRIMITIVES (v7.4 — the actual building blocks)

Each primitive is a paste-validated block-tree fragment that expresses ONE structural unit. The skill **composes** primitives to match the design's actual layout. Production patterns (`examples/patterns/*.example.md`) are HOW examples — they teach the conventions used here. They are NOT WHAT templates — never copy a pattern verbatim and expect it to match every design.

**The skill's job is to decompose the design into structural units, then emit the matching primitive for each unit.** A design with 3 cards uses the bordered-card primitive 3x; a design with 6 cards uses it 6x; a design with 2 hero columns uses the hero-flex-split primitive once. Any design composes from these primitives.

Every primitive obeys the v7.4 paste-safety rules from `reference/style-conversion.md`:
- Every CSS rule in wrapper inline `style=""` has a 1:1 JSON `style.*` correlate (set-equality)
- No slug attr + literal inline-style mixing on the same property
- JSON string values ≤ 80 chars

---

## P1 — Body-bg shim (`core/group` literal-bg, layout default)

**When:** the design's `body { background: ... }` or page-level CSS var is something other than white. Wraps the entire page interior.

**Block tree:**
```
core/group {style:{color:{background:"#hex"},spacing:{padding:{top,right,bottom,left}}},layout:{type:"default"}}
└── (all sections nested inside)
```

**Wrapper class:** `wp-block-group has-background`
**Wrapper inline style:** `background-color:#hex;padding-top:Npx;padding-right:Npx;padding-bottom:Npx;padding-left:Npx`

**Reference:** `examples/patterns/product-physical.example.md:25-26`.

---

## P2 — Hero flex split (`core/group` flex with two child groups)

**When:** the design's hero has media on one side, info column on the other. Two-column horizontal layout.

**Block tree:**
```
core/group {style:{spacing:{blockGap:0,padding:0,margin:0}},layout:{type:"flex",flexWrap:"nowrap",verticalAlignment:"top",justifyContent:"center"}}
├── core/group {style:{layout:{selfStretch:"fixed",flexSize:"800px"}},layout:{type:"default"}}
│   └── (media — surecart/product-media or core/image)
└── core/group {style:{layout:{selfStretch:"fixed",flexSize:"424px"},spacing:{margin:{left:"96px"}}},layout:{type:"constrained"}}
    └── (info column — title, price, description, variant-pills, etc.)
```

**Width math:** total fixed widths + gap = container max-width. Adjust `flexSize` per design (e.g. 50/50 split = `"600px","600px"` for 1240 container with 40px gap).

**Reference:** `examples/patterns/product-physical.example.md:26-31`.

---

## P3 — Vertical icon-text card (`core/group` with vertical flex + SVG icon + paragraphs)

**When:** the design has a benefit/feature card with an icon at top, heading, and body text vertically stacked. Used for highlights, benefits, trust strips.

**Block tree:**
```
core/group {style:{spacing:{blockGap:0,padding:0,margin:{bottom:0}},layout:{selfStretch:"fixed",flexSize:"258px"}},layout:{type:"flex",orientation:"vertical"}}
├── core/html (inline SVG, stroke=#brand, width=24, height=24, viewBox="0 0 24 24")
└── core/group {style:{spacing:{blockGap:0,padding:0,margin:{top:"16px",bottom:0}}},layout:{type:"flex",orientation:"vertical"}}
    ├── core/paragraph (heading text — fontSize:16, fontWeight:500, color:#text-primary, has-link-color)
    └── core/paragraph (body text — fontSize:16, color:#text-secondary, margin-top:4px, has-link-color)
```

**Reference:** `examples/patterns/product-physical.example.md:136-152`.

**Composition:** for an N-card row, place N copies of P3 inside a parent `core/group` flex (P5 — multi-card row primitive).

---

## P4 — Bordered card chrome (`core/group` literal-bg + literal-border + padding)

**When:** the design has a card with a colored background, visible border, rounded corners, padding. Used for pricing tiers, info panels, callouts.

**Block tree:**
```
core/group {style:{spacing:{padding:{top,right,bottom,left}},border:{color:"#hex",width:"1px",style:"solid",radius:"Npx"},color:{background:"#hex"}},layout:{type:"default"}}
└── (card content)
```

**Wrapper class:** `wp-block-group has-border-color has-background`
**Wrapper inline style:** `border-color:#hex;border-style:solid;border-width:1px;border-radius:Npx;background-color:#hex;padding-top:Npx;padding-right:Npx;padding-bottom:Npx;padding-left:Npx`

**Reference:** `examples/patterns/product-physical.example.md:55` (bordered price-choice-template).

---

## P5 — Multi-card row (`core/group` flex with N child cards)

**When:** the design has 2+ horizontal sibling cards. Use this to wrap any number of P3 / P4 primitives.

**Block tree:**
```
core/group {style:{spacing:{blockGap:0,padding:{top,right,bottom,left},margin:{top:0,bottom:0}},border:{top:[],bottom:{color:"#hex",width:"1px"},left:[]}},layout:{type:"flex",flexWrap:"nowrap",justifyContent:"space-between"}}
├── (card 1 — P3 or P4 with selfStretch:"fixed",flexSize:"Npx")
├── (card 2 — same shape, with margin:{left:"24px"} for inter-card gap)
├── ...
└── (card N — same)
```

**Inter-card gap:** put `margin:{left:"24px"}` (or design's gap) on cards 2..N. The outer flex has `blockGap:0` since per-child margins handle spacing.

**Reference:** `examples/patterns/product-physical.example.md:135-218` (4-card benefits row).

---

## P6 — CTA band (`core/group` literal-bg `align:"full"`)

**When:** the design has a full-bleed colored section with centered headline + body + CTA button. Typically at the bottom or between sections.

**Block tree:**
```
core/group {style:{spacing:{padding:{top:"96px",right:"32px",bottom:"96px",left:"32px"},margin:{top:0,bottom:0}},color:{background:"#brand"}},layout:{type:"default"}}
├── core/paragraph (headline — large, white text, align:center)
├── core/paragraph (body — muted-white, align:center)
└── surecart/product-buy-buttons (CTA — flex justifyContent:center, single button with text attr + style.color.{text,background})
```

**Wrapper class:** `wp-block-group has-background`
**Wrapper inline style:** `background-color:#brand;margin-top:0;margin-bottom:0;padding-top:96px;padding-right:32px;padding-bottom:96px;padding-left:32px`

**Reference:** mirrors P1 structure with brand bg.

---

## P7 — Detail-row (`core/group` with border-bottom split flex)

**When:** the design has eyebrow-label + display-value rows separated by horizontal dividers. Used for specs tables, feature lists.

**Block tree:**
```
core/group {style:{spacing:{padding:{top:"80px",bottom:"80px"}},border:{top:[],bottom:{color:"#hex",width:"1px"},left:[]}},layout:{type:"flex",flexWrap:"nowrap",justifyContent:"space-between"}}
├── core/group {style:{layout:{selfStretch:"fixed",flexSize:"Npx"}}}
│   ├── core/paragraph (eyebrow — uppercase, small, muted)
│   └── core/paragraph (value — large, primary text)
├── (more cells, with margin-left for gap)
```

**Reference:** `examples/patterns/product-physical.example.md:135-218`.

---

## P8 — Inline review summary (`core/group` flex with stars + total-rating)

**When:** the design's hero has a single-line review chrome (★★★★★ 4.9 · 1,284 reviews). NOT the expanded summary card.

**Block tree:**
```
core/group {style:{spacing:{blockGap:"10px",padding:0}},layout:{type:"flex",flexWrap:"nowrap"}}
├── surecart/product-review-average-rating-stars /
└── surecart/product-review-total-rating {style:{spacing:{blockGap:"4px"}}} /                              ← OMIT className per HC#19 v7.15 (plus-sign is isDefault:true)
```

**Reference:** `examples/patterns/product-standard.example.md:35-41`.

---

## P9 — FAQ details list (`core/details` paired blocks)

**When:** the design has a Q&A section with collapsible answers.

**Block tree:**
```
core/group (FAQ section wrapper, vertical flex)
├── core/paragraph (heading — "Frequently asked", large)
├── core/details {summary:"Question 1?"} (paired)
│   └── core/paragraph (answer 1 — has-text-color has-link-color)
├── core/details {summary:"Question 2?"} (paired)
│   └── core/paragraph (answer 2)
├── ...
```

**Constraints:**
- Every `core/details` opener carries `summary` attr matching inner `<summary>` text exactly
- `<summary>` element is plain — no class, no style attr (per A-4)
- Keep summary text ≤ 80 chars (JSON string-value cap)

---

## P10 — Hero info column primitives (composed inside P2's info-column child)

The info column inside the hero flex split typically contains a vertical sequence of these sub-primitives:

a. **Inline review** (P8)
b. **Title** — `surecart/product-title` self-closed with style.typography + style.color.text
c. **Selected-price line** — `core/group` flex containing scratch-amount + amount + interval + sale-badge children
d. **Description** — `surecart/product-description /-->` self-closed
e. **Variant pills** — `surecart/product-variant-pills` paired with one `surecart/product-variant-pill` carrying `highlight_text/background/border` chrome attrs
f. **Price chooser** — `surecart/product-price-chooser` paired with `surecart/product-price-choice-template` (emit ONCE, server iterates)
g. **Quantity** — `surecart/product-quantity /-->` self-closed (default form) OR paired with explicit -control + 3 input children for custom chrome
h. **Buy buttons** — `surecart/product-buy-buttons` paired wrapper with `text` attr on every child button. Outline style via `className:"is-style-outline"` for secondary; explicit colors for inverse-styled CTAs

**Reference:** `examples/patterns/product-physical.example.md:31-100`.

---

## P11 — Filled Pill (badge / "BESTSELLER" / "NEW" / "LIMITED")

Small filled capsules used as inline badges (hero info columns, related-product card corners, above section eyebrows). Distinct from an eyebrow — pill has filled bg + padding + radius; eyebrow is plain colored text.

```html
<!-- wp:paragraph {"fontFamily":"surecart-body","style":{"typography":{"fontSize":"11px","fontWeight":"600","letterSpacing":"0.16em","textTransform":"uppercase","fontFamily":"Inter, sans-serif"},"color":{"text":"#C8694A","background":"#F0DDD2"},"spacing":{"padding":{"top":"7px","right":"14px","bottom":"7px","left":"14px"},"margin":{"top":"0","bottom":"22px"}},"border":{"radius":"9999px"}}} -->
<p class="has-text-color has-background has-surecart-body-font-family" style="border-radius:9999px;color:#C8694A;background-color:#F0DDD2;margin-top:0;margin-bottom:22px;padding-top:7px;padding-right:14px;padding-bottom:7px;padding-left:14px;font-family:Inter, sans-serif;font-size:11px;font-weight:600;letter-spacing:0.16em;text-transform:uppercase">BESTSELLER</p>
<!-- /wp:paragraph -->
```

Keys: `color.background` (tinted pill bg), `spacing.padding` (7px vertical + 14px horizontal), `border.radius:"9999px"` (full capsule), `has-background` class.

## P12 — Icon Bubble (round colored circle wrapping an SVG icon)

Round-bubble icon wrappers used in trust strips, spec icons, step number circles, social-icon rows, benefit strips. CSS pattern: `width/height: Npx; border-radius: 50%; background: tint; color: accent; display: flex; align-items: center; justify-content: center`. Emit via `core/html` (most reliable — bypasses `core/group` dimensions reliability issues per HC#25):

```html
<!-- wp:html -->
<div style="width:44px;height:44px;border-radius:9999px;background:#F0DDD2;color:#C8694A;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">
  <svg viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">…icon paths…</svg>
</div>
<!-- /wp:html -->
```

Variants: tinted-bg + accent stroke (trust strip), white-bg + bordered (specs), step-number circle (replace `<svg>` with the digit).

When the glyph matches a `core/icon` built-in slug (HC#32), prefer `core/icon` inside a wrapping `core/html` styled div. The built-in slugs handle color via Stencil and integrate with theme.json.

## P13 — Mixed-Row-Spanning Columns (story / about / what's-inside layouts)

For sections with a CSS-grid layout where one child spans both columns (lead paragraph, h2 own-row) before the remaining content drops into 2-col flow. Compose with nested groups instead of true CSS-grid:

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"36px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
  <!-- h2 on its own row, full width -->
  <!-- wp:heading {"level":2,"fontFamily":"surecart-display",...} -->
  <h2>Made by hand in a small Kyoto workshop.</h2>
  <!-- /wp:heading -->

  <!-- First (lead) paragraph: full-width, larger -->
  <!-- wp:paragraph {"fontFamily":"surecart-body",...} -->
  <p>…lead paragraph…</p>
  <!-- /wp:paragraph -->

  <!-- Remaining paragraphs: 2-col grid -->
  <!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"28px","left":"48px"}}}} -->
  <div class="wp-block-columns are-vertically-aligned-top">
    <!-- wp:column {"verticalAlignment":"top","width":"50%"} --><div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%">…</div><!-- /wp:column -->
    <!-- wp:column {"verticalAlignment":"top","width":"50%"} --><div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%">…</div><!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</div>
<!-- /wp:group -->
```

Detection: CSS grid `grid-template-columns:1fr 1fr` with at least one child carrying `grid-column:1/-1`.

---

## Section-bg tint + dark-section archetypes

**Section-bg tints.** Designs frequently apply a slightly-deeper background to specific sections (Specs, FAQ, Related) to create visual rhythm. CSS pattern: `background: color-mix(in oklch, var(--bg-deep) 70%, var(--bg))` produces a subtle warmer cream distinct from the body bg. Emit each such section as a `core/group align:"full"` with `style.color.background` set to the design's section bg hex — NOT just relying on the body-bg shim. Same as the v7.3 body-bg shim doctrine, applied at the section level.

**Dark-section archetype.** Many designs have a fully dark footer (or dark CTA band) with light text and dark-tinted social icon bubbles. Detection: section's CSS uses an ink-family color as `background` AND inner body has light text colors.

- Emit the section as `core/group align:"full"` with `style.color.background` set to the dark hex (e.g., `#2B2520`).
- Switch all text leaves inside this section to light colors: `style.color.text:"#F5EFE6"` (or whatever the design uses).
- For social-icon bubbles inside a dark section, use a translucent-on-dark bg: `background:rgba(255,255,255,0.06)` (via `core/html` styled div — `core/group` can't reliably emit rgba via standard color attrs).
- For newsletter inputs inside a dark section, same: `rgba(255,255,255,0.04)` bg + light placeholder color.
- The dark section is structurally INSIDE the body-bg shim — it sits at the bottom of the page as a final sibling of all the other sections.

Reference: Kobachi Ramen Bowl footer (dark `#2B2520` ink-family on otherwise-cream page).

---

## P-NN extension policy

When a design needs a structural unit not covered by P1–P13, add it as a new P-NN here. Source from the closest production pattern, paste-validate the resulting block tree, then document the JSON attrs + wrapper class set + inline-style set side-by-side. Future emissions reuse the new primitive instead of re-deriving.

---

# Part 2 — Section archetypes (legacy reference)

The archetypes below predate the primitives library. They show full-section compositions that combine multiple primitives. Useful as worked examples but **NOT as templates to copy verbatim** — the actual design's structure drives composition, not archetype fit.

---

## 1. Hero

**When:** Page-dominant section above the fold. Title + subheading + primary CTA + optional media. May have full-bleed image or gradient background.

**Decision tree:**
- Background is image OR gradient OR has aspect-ratio constraint → `core/cover` (only block that natively emits gradient + aspect-ratio).
- Background is solid color OR no background → `core/group` with `layout.type:"constrained"`.
- 2-column hero (text left, media right) → `core/columns` with `verticalAlignment:"center"`.

**Canonical tree (2-column product hero):**
```
core/group               (section padding, layout.type:"constrained")
└── core/columns         (gap=blockGap, verticalAlignment:"center")
    ├── core/column      (verticalAlignment:"center", width:"50%")
    │   ├── core/paragraph         (.sc-eyebrow — small uppercase tag)
    │   ├── core/heading           (level:1, .sc-display-1)
    │   ├── core/paragraph         (.sc-lead — subheading)
    │   ├── surecart/product-price-chooser  (if product page) OR core/paragraph (price string)
    │   └── core/buttons
    │       ├── surecart/product-buy-button (add_to_cart:true)   ← inside surecart/product-buy-buttons if both present
    │       └── core/button                                       ← "Learn more" or "Watch demo"
    └── core/column      (verticalAlignment:"center", width:"50%")
        └── surecart/product-media   (if product page) OR core/image
```

**Canonical tree (full-bleed cover hero):**
```
core/cover               (url OR customGradient, dimRatio, minHeight OR aspectRatio, layout.type:"constrained")
└── (inner-container)
    ├── core/heading     (level:1, white textColor + D7 dual-emit since cover background varies)
    ├── core/paragraph   (.sc-lead)
    └── core/buttons
        └── core/button  (style:fill, contrasting textColor for legibility)
```

**Drop+log:** parallax effects, hero animations, hover-triggered transforms, video autoplay (browser policies), particle backgrounds.

---

## 2. Feature grid (3–4 cards with icon + heading + text) — v7.3 group flex

**When:** Highlights / benefits / "what you get" section. 3–6 cards, equal width, often with icon at top.

**v7.3 — ALWAYS use `core/group` flex, NEVER `core/columns`.** Card grids in production patterns (`product-physical.example.md:26-31`, `product-course-dark.example.md:94-134`) exclusively use nested `core/group` flex with each card carrying `style.layout:{selfStretch:"fixed",flexSize:"NN%"}`. `core/columns` is wrong here because:
1. Theme conflict on borders (column wrappers inherit theme `border-color` overrides → visible black borders even with literal-hex inline mirror).
2. No per-card width control via inline style (must use `width:"33%"` attr which doesn't always honor literal px).
3. Harder to tighten gaps (column gutter uses `blockGap` on parent which interacts with stack-on-mobile behavior).

**Decision tree:**
- Cards have card chrome (background, border, padding, radius) → each child `core/group` carries literal-bg + border + padding per Exemplar 3.5 (with `border-style:"solid"` per Rule 4 v7.3 reversal, NO `has-border-color` class for literal-hex borders).
- Cards are flat (no chrome) → plain `core/group` per card with no background.
- Icon → `core/html` with inline SVG (L1-safe per A-15) OR `core/image` with placeholder URL.

**Canonical tree (v7.3):**
```
core/group               (section padding, layout.type:"constrained")
├── core/heading         (level:2, optional aligncenter)
├── core/paragraph       (lead intro, optional)
└── core/group           (OUTER GRID — layout.type:"flex", flexWrap:"wrap", justifyContent:"space-between", blockGap:"24px")
    ├── core/group       (CARD 1 — selfStretch:"fixed", flexSize:"23%" for 4-up; "31%" for 3-up; "48%" for 2-up)
    │   │                 + literal-bg + border + padding per Exemplar 3.5 + border-style:"solid"
    │   ├── core/html      (inline SVG icon, stroke=#brand)
    │   ├── core/heading   (level:3)
    │   └── core/paragraph (body)
    ├── core/group       (CARD 2 — same shape)
    └── core/group       (CARD 3, 4 …)
```

**Width math:**
- 4-up grid: `flexSize:"23%"` × 4 cards + 3 × 24px gap = ~92% + ~72px gap → fits in 1200px container with breathing room
- 3-up: `flexSize:"31%"` × 3 + 2 × 24px gap
- 2-up: `flexSize:"48%"` × 2 + 24px gap

**.map() rule:** if source JSX iterates `HIGHLIGHTS.map(h => …)`, **fully expand to N concrete `core/group` siblings**. Bake data inline; never leave `{h.title}` in output.

**Drop+log:** hover-tilt effects, animated counter on stats, gradient borders.

---

## 3. Pricing table (2–3 plans with recommended-plan emphasis)

**When:** "Choose your plan" with feature-comparison rows. Usually 2–3 columns, one column visually highlighted (border accent + "Most popular" badge).

**Decision tree:**
- Pricing is **product-driven** (each plan = a SureCart product price option) → `surecart/product-price-chooser` with `surecart/product-price-choice-template` per plan. Highlight via `highlight_border:true` on the recommended template.
- Pricing is **static / marketing** (not yet linked to SureCart products) → `core/group` flex with one child `core/group` per plan (`selfStretch:"fixed",flexSize:"31%"` for 3 plans). v7.3: card grids use `core/group` flex, never `core/columns`.

**Canonical tree (product-driven):**
```
surecart/product-page                (outer wrapper, mandatory)
└── surecart/product-price-chooser   (label, columns:3)
    ├── surecart/product-price-choice-template  (highlight_border:false)
    │   ├── surecart/price-name
    │   ├── surecart/price-amount
    │   ├── surecart/price-interval
    │   ├── core/list                  (feature bullets — each <li> with check-mark unicode/icon)
    │   └── surecart/product-buy-button (add_to_cart:false, text:"Choose Starter")
    ├── surecart/product-price-choice-template  (highlight_border:true)   ← recommended
    │   └── … same shape
    └── surecart/product-price-choice-template  (highlight_border:false)
        └── … same shape
```

**Canonical tree (static / marketing):**
```
core/group                  (section padding, layout.type:"constrained", contentSize:"1200px")
├── core/heading            (level:2, .sc-h2, aligncenter)
├── core/paragraph          (.sc-lead, aligncenter)
└── core/columns            (3 columns, equal width, gap=blockGap)
    ├── core/column         (width:"33.33%")
    │   └── core/group      (CARD: literal-bg-white + border + padding:32px + radius:16px)
    │       ├── core/heading   (level:3, plan name)
    │       ├── core/paragraph (large price + interval)
    │       ├── core/list      (features — bulleted)
    │       └── core/buttons
    │           └── core/button (text:"Choose Starter", url:"#checkout")
    ├── core/column         (width:"33.33%", className:"is-style-recommended")
    │   └── core/group      (CARD with thicker border + accent borderColor + "Most popular" badge above)
    └── core/column
```

**Recommended-plan emphasis options (in priority order):**
1. Border accent: `style.border.color:"surecart-brand"` + `style.border.width:"2px"` on the recommended card (vs `1px` on others).
2. Background tint: `backgroundColor:"surecart-brand-soft"` on the recommended card (slug-bg only, per Rule 0).
3. "Most popular" badge above: a small `core/group` with `core/paragraph` (.sc-eyebrow uppercase) absolutely positioned via inline style on a literal-bg wrapper.
4. `transform:scale(1.05)` — drop+log; restoration via theme CSS.

**Drop+log:** column-hover effects, animated price counters, conditional plan-highlight tied to user state.

---

## 4. Testimonial (single quote + avatar, OR carousel)

**When:** Customer story, 5-star quote with attribution + photo + company name. Sometimes a multi-quote carousel.

**Decision tree:**
- Single testimonial → `core/quote` (semantic HTML `<blockquote>` with cite).
- 2–3 testimonials in a row → `core/columns` with one `core/quote` per `core/column`.
- Carousel (>3 quotes, only one visible at a time) → no native Gutenberg carousel block. Emit all quotes in a `core/columns` with `core/group` parent + `className:"is-style-carousel"`; drop+log the carousel behavior with a merchant action ("install slick-slider plugin or add carousel CSS to theme").

**Canonical tree (single):**
```
core/group               (section padding, layout.type:"constrained", optional .sc-bg-soft background)
└── core/columns         (verticalAlignment:"center", gap=blockGap)
    ├── core/column      (width:"30%", verticalAlignment:"center")
    │   └── core/image   (placeholder URL, width=120, height=120, style.border.radius:"60px" — circular avatar)
    └── core/column      (width:"70%", verticalAlignment:"center")
        ├── core/quote
        │   └── core/paragraph    (the quote text, .sc-quote class)
        └── core/group              (citation row: name + company + role, .sc-small)
            ├── core/paragraph     (name, .sc-h4)
            └── core/paragraph     (role + company, .sc-small + textColor:"surecart-gray-700")
```

**Canonical tree (3-up grid):**
```
core/group               (section padding, layout.type:"constrained")
├── core/heading         (level:2, .sc-h2, aligncenter)
└── core/columns         (3 columns, gap=blockGap)
    ├── core/column      (width:"33.33%")
    │   └── core/group   (CARD: literal-bg-white + border + padding:32px + radius:16px)
    │       ├── core/paragraph    (5-star unicode "★★★★★", textColor:"surecart-yellow")
    │       ├── core/quote
    │       │   └── core/paragraph (quote text)
    │       └── core/group         (avatar + name row — flex layout)
    │           ├── core/image    (avatar, circular)
    │           └── core/group
    │               ├── core/paragraph (name)
    │               └── core/paragraph (role + company)
    ├── core/column     (… repeat)
    └── core/column
```

**Drop+log:** carousel auto-rotate, animated quote-fade transitions.

---

## 5. FAQ (collapsible Q&A)

**When:** Long-form question/answer list near bottom of page. Each question expands inline.

**Decision tree:**
- Always `core/details` per Q&A — semantic `<details>/<summary>` HTML, zero JS, zero recovery cascade.

**Canonical tree:**
```
core/group               (section padding, layout.type:"constrained", optional contentSize:"800px" to keep readable)
├── core/heading         (level:2, .sc-h2, aligncenter)
└── core/group           (vertical stack, blockGap:"16px")
    ├── core/details     (summary:"What does the warranty cover?")
    │   └── core/paragraph  (answer body, .sc-body)
    ├── core/details     (summary:"How do I return a product?")
    │   └── core/paragraph
    ├── core/details     (summary:"…")
    │   └── core/paragraph
    └── … per FAQ item
```

**Mandatory:** every `core/details` opener carries `{"summary":"<question text>"}` matching the inner `<summary>` HTML. Mismatch → recovery prompt on every FAQ item (rubric A-4 + Rule 5).

**Drop+log:** custom expand/collapse animations, accordion (only-one-open-at-a-time) behavior — `core/details` is independent by default; merchant adds JS for accordion behavior.

---

## 6. Product card (single product with image, price, buy button)

**When:** A standalone product display embedded in a non-product page (e.g., a featured-product callout in a landing page).

**Decision tree:**
- Card is on a **product detail page** (with `surecart_current_product` query var set) → use `surecart/product-*` blocks directly, they read context automatically.
- Card is on a **catalog / generic page** referencing a specific product → wrap in `surecart/product-list` with `ids:["<product_id>"]` and `limit:1`, then use `surecart/product-template` per card.
- Card is on a **landing page** with no product context → static `core/image` + `core/heading` + `core/button` linking to the product page (no SureCart block; merchant fills in URLs).

**Canonical tree (product detail context):**
```
core/group               (CARD: literal-bg + border + padding + radius)
├── surecart/product-image   (sizing:"cover", aspectRatio:"1/1")
├── surecart/product-title   (level:3, .sc-h3)
├── core/paragraph           (truncated description — hand-write, no truncate block)
├── surecart/product-scratch-price   (if on sale)
├── surecart/product-selected-price-amount
└── surecart/product-buy-buttons
    └── surecart/product-buy-button   (add_to_cart:true, text:"Add to Cart")
```

**Canonical tree (catalog / generic page with product list):**
```
surecart/product-list    (ids:["abc"], limit:1, type:"specific")
└── surecart/product-template
    └── core/group
        ├── surecart/product-image
        ├── surecart/product-title
        ├── surecart/product-list-price
        └── surecart/product-buy-button
```

**Drop+log:** quick-view modal on hover, "added to cart" toast, wishlist heart icon (no Gutenberg attr; theme CSS or JS).

---

## 7. CTA band (full-width section with heading + button)

**When:** Mid-page or end-of-page conversion-focused section. Big heading + supporting paragraph + 1–2 CTAs. Often on a contrasting background.

**Decision tree:**
- Background is gradient OR image → `core/cover`.
- Background is solid color (slug match) → `core/group` with `backgroundColor:"<slug>"`, `align:"full"`.
- Background is white / no background → `core/group` with `layout.type:"constrained"`.

**Canonical tree (slug-bg full-width):**
```
core/group               (align:"full", backgroundColor:"surecart-brand", textColor:"surecart-white", padding:96px 24px, layout.type:"constrained", contentSize:"800px")
├── core/heading         (level:2, .sc-h1, aligncenter, white text + D7 dual-emit)
├── core/paragraph       (.sc-lead, aligncenter, white text + D7)
└── core/buttons         (layout.justifyContent:"center", flex)
    ├── core/button      (text:"Get started", style:"fill", inverted color for contrast on dark bg)
    └── core/button      (text:"Watch demo", style:"outline")
```

**Canonical tree (cover-image full-width):**
```
core/cover               (url, dimRatio:50, minHeight:480, align:"full", layout.type:"constrained", contentSize:"800px")
└── (inner-container)
    ├── core/heading     (level:2, white)
    ├── core/paragraph
    └── core/buttons
        └── core/button
```

**Drop+log:** scroll-triggered animations, parallax background, urgency-countdown timers.

---

## 8. Footer (multi-column links + brand)

**When:** Bottom-of-page navigation + legal + brand section. 3–5 columns of links + a brand column.

**Decision tree:**
- Footer is **part of the product page markup** (rare; usually it's a theme template part) → emit as `core/group` with `align:"full"`.
- Footer is **theme-managed** (typical) → drop the design's footer entirely; log under `dropped_features` with `merchant_action:"Use the theme's site footer template part; copy your column layout from the design into theme.json or the Site Editor."`

**Canonical tree (product-page-embedded footer):**
```
core/group               (align:"full", backgroundColor:"surecart-bg-dark", textColor:"surecart-gray-400", padding:80px 24px, layout.type:"constrained")
├── core/columns         (4 columns, gap:64px, isStackedOnMobile)
│   ├── core/column      (width:"30%")    ← Brand column
│   │   ├── surecart/icon          (icon_name:"logo", size:32)  OR core/image
│   │   ├── core/paragraph         (tagline)
│   │   └── core/group             (social icons row — flex layout)
│   │       ├── surecart/icon       (icon_name:"twitter", link_url:"...")
│   │       ├── surecart/icon       (icon_name:"linkedin", link_url:"...")
│   │       └── surecart/icon       (icon_name:"github", link_url:"...")
│   ├── core/column      (width:"23%")    ← Product column
│   │   ├── core/heading           (level:4, "Product")
│   │   └── core/list
│   │       ├── core/list-item     (links — use href in <li><a> directly)
│   │       └── core/list-item
│   ├── core/column      (width:"23%")    ← Company column
│   │   └── … same shape
│   └── core/column      (width:"23%")    ← Support column
│       └── … same shape
├── core/separator       (style:"solid", borderColor:"surecart-gray-200", margin:48px 0 32px)
└── core/group           (legal row: copyright + small links — flex justifyContent:"space-between")
    ├── core/paragraph   (copyright, .sc-small)
    └── core/group       (links: privacy, terms — flex)
        ├── core/paragraph (link)
        └── core/paragraph (link)
```

**Drop+log:** newsletter signup form (use theme's form block or drop), language switcher (Polylang / WPML own), country/currency switcher (`surecart/currency-switcher` if applicable), live chat widget.

---

## Cross-archetype tips

- **Section padding:** standard product-page sections use `style.spacing.padding:"96px 24px"` desktop / drop the horizontal in favor of `layout.contentSize` for inner constraint. See `examples/group-section.example.md`.
- **`align:"full"` only on outermost section wrappers**, never on inner `core/columns`. Full-bleed columns inside a constrained section corrupts layout.
- **Section gap:** vertical rhythm between sections comes from the outer `core/group` `padding.top/bottom`, not from `marginBottom` on the previous section. Keep margins out of section authoring.
- **Heading hierarchy:** `core/heading.level:1` exists at most once per page (the hero title). Section headings are `level:2`. Card titles inside a section are `level:3`. FAQ summaries are inside `<summary>`, not headings.
- **Button styles:** prefer `surecart/product-buy-button` for any product purchase action. `core/button` is for navigation, learn-more, secondary CTAs. Never use `core/button` for "Add to Cart" — it doesn't connect to cart state.

---

## When the design has no archetype match

If a section doesn't match any of the 8 patterns above (e.g., a custom comparison table, a stats banner with animated counters, an interactive product configurator):

1. **Decompose into the smallest WP-core primitives that fit** (group + columns + headings + paragraphs + images).
2. **Search SureCart's full block catalog** — `reference/surecart-blocks.md` and `reference/full-inventory.json`. The 8 archetypes above are a curated set; SureCart has ~150 next-gen + ~84 legacy blocks. Reviews → `surecart/product-reviews`, cart drawer → `surecart/slide-out-cart`, order bumps → `surecart/cart-order-bumps`, donations → `surecart/donation` (legacy), checkout forms → `surecart/form` (legacy), etc.
3. **Open `reference/custom-html-fallback.md`** for the v7.1 3-tier `core/html` ladder:
   - **L1 — static `core/html`** for decorative content with no behavior. Log `dropped_features.type:"raw_html_static"`.
   - **L2 — `core/html` piggybacking on a SureCart Interactivity store** (cart toggle, lightbox, gallery navigation, quantity, variant). Use only the 7-namespace allowlist. Verify a sibling SureCart block on the same emit triggers the namespace's module load.
   - **L3 — drop + recommend `/surecart-new-block`** when no allowlist namespace fits. Emit a best-effort static visual fallback so the page isn't empty.

Animated/transitive behaviors (count-up, parallax, hover-tilt) are still dropped with a `merchant_action` CSS or JS escape hatch — the Interactivity API ladder is for state-driven interactions (toggles, modals, filtering), not for declarative animations.

---

# v7.2.0 — section archetypes from production patterns

The 8 archetypes below were extracted by auditing the 20 production block-pattern files in the SureCart plugin's own pattern library. Each archetype names the block tree (depth-first) and the distinguishing layout configs. When a Claude Design section matches an archetype, **prefer reusing the canonical structure verbatim** over inventing a new tree.

For full markup, see the corresponding `examples/patterns/<name>.example.md`.

## Archetype A — Product hero (2-col media + info)

**Source patterns:** `product-standard`, `product-alternate`, `product-physical`, `product-course`, `product-course-dark`, `product-quick-view`

**Tree:**
```
core/columns (align:wide, blockGap:{top:30px,left:60px})
  core/column (width:"" — media side)
    surecart/product-media /
  core/column (width:"36%" — info side, blockGap:0.75rem)
    surecart/product-collection-tags (paired)
      surecart/product-collection-tag /
    core/group (flex nowrap — review row)
      surecart/product-review-average-rating-stars /
      surecart/product-review-total-rating /
    surecart/product-title /
    core/group (price block)
      core/group (flex wrap — scratch + amount + interval + sale-badge)
      core/group (flex nowrap — trial + fees)
    surecart/product-description /
    surecart/product-variant-pills (paired)
      surecart/product-variant-pill /
    surecart/product-price-chooser (paired)
      surecart/product-price-choice-template (paired, flex horizontal)
        surecart/price-name (50%)
        core/group (50%, flex column)
          core/group (flex row — scratch + amount + interval)
          surecart/price-trial /
          surecart/price-setup-fee /
    surecart/product-quantity /
    surecart/product-selected-price-ad-hoc-amount /
    surecart/product-buy-buttons (paired, explicit wrapper div)
      surecart/product-buy-button (add_to_cart:true) /
      surecart/product-buy-button (Buy Now, is-style-outline) /
```

**Distinguishing markers:**
- Media:info ratio is 64:36 (info column `width:"36%"`).
- `blockGap:{top:"30px",left:"60px"}` on outer `core/columns` (column gutter).
- Variants → pills. Multi-tier price → chooser. Single hero price → selected-price-* family.
- Buy-buttons wrapper class set is **always** `wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex` (4 classes, all required).

## Archetype B — Themed product hero (background-tinted, full-width)

**Source patterns:** `product-physical`, `product-course`, `product-course-dark`

**Tree:**
```
surecart/product-page (align:full, layout:{type:"constrained",contentSize:"1320px"})
  core/group (full-bg, large padding 60–80px)  // page-tinted band
    [Archetype A nested here, but inner structure may use core/group instead of core/columns
     when the design wants asymmetric flex sizes (800px media + 424px info)]
```

**Distinguishing markers:**
- Outer `surecart/product-page` carries `align:"full"` and a literal `contentSize` (e.g. `"1320px"`).
- A `core/group` with explicit background fills the band.
- Asymmetric layout uses **flex with `selfStretch:"fixed",flexSize:"800px"`** on media and `flexSize:"424px"` on info, rather than `core/columns`. See `product-physical.example.md`.
- Theming: brown family (`product-physical`), light-gray (`product-course`), dark/emerald (`product-course-dark`). Use the design's hex values; don't snap.

## Archetype C — Product card grid (in `product-template`)

**Source patterns:** `list-standard`, `list-bento`, `list-row`, `list-staggered`, `list-carousel`, `related-carousel*`

**Tree:**
```
surecart/product-list (align:wide, blockGap)
  core/group (header — title + sort + search + pagination)
  core/group (filter-tags row, optional)
  surecart/product-template (paired, layout:{type:"grid",columnCount:3 OR minimumColumnWidth:"225px"})
    core/group (card container, optional border-radius:10px)
      core/group (image wrapper, bg:#0000000d, border-radius:10px)
        core/cover (aspectRatio:"3/4", useFeaturedImage:true, dimRatio:0)
          surecart/product-quick-view-button /
          surecart/product-sale-badge /
      surecart/product-title (h2, fontSize 15–18px) /
      core/group (flex nowrap — price row)
        surecart/product-list-price /
        surecart/product-scratch-price /
  surecart/product-list-no-products (paired)
    core/paragraph (fallback) /
  surecart/product-pagination (paired)  // optional, if not in header
    surecart/product-pagination-previous /
    surecart/product-pagination-numbers /
    surecart/product-pagination-next /
```

**Distinguishing markers:**
- Grid uses **`minimumColumnWidth:"225px"`** for responsive (recommended) OR explicit `columnCount:3|4` for fixed.
- Card aspect: `3/4` (portrait product), `1/1` (square), `16/9` (banner).
- `core/cover` MUST carry `dimRatio:0` and `isUserOverlayColor:true` (no overlay).
- Sale-badge is a child of `core/cover` via `wp-block-cover__inner-container`.
- Always include `surecart/product-list-no-products` as a sibling fallback.

## Archetype D — Sidebar + grid (sticky filter)

**Source pattern:** `list-sidebar`

**Tree:**
```
surecart/product-list
  core/group (controls: sidebar-toggle + search)
  core/group (filter-tags row, optional)
  surecart/product-list-content (flex row, orientation:horizontal)
    surecart/product-list-sidebar (sticky, top:0, flexSize:225px)
      surecart/product-list-filter-tags (paired)
      surecart/product-list-sort-radio-group (paired)
      surecart/product-list-filter-checkboxes (paired)
    surecart/product-template-container (flex:fill)
      surecart/product-template (grid, minimumColumnWidth:"225px")
        // Archetype C card structure
  surecart/product-pagination (bottom)
```

**Distinguishing markers:**
- Sidebar is `position:sticky, top:0` with `flexSize:"225px"` (fixed pixel width).
- Sidebar uses LARGER `blockGap` (~30px) between filter groups.
- Main pane uses `flex:fill` to absorb remaining width.
- Grid uses `minimumColumnWidth` (responsive) so cards reflow as sidebar collapses.

## Archetype E — Sticky purchase bar (horizontal)

**Source pattern:** `sticky-purchase`

**Tree:**
```
surecart/sticky-purchase (layout:{type:"flex",orientation:"horizontal",justifyContent:"space-between",verticalAlignment:"top",flexWrap:"nowrap",wideSize:"full"})
  core/group (left zone, selfStretch:"fit")
    surecart/product-selected-variant-image (fixed 80–120px) /
    core/group (flex column, blockGap:4px)
      surecart/product-title (level:4, fontSize:16px, fontWeight:700) /
      surecart/product-selected-variant /
      core/group (flex wrap — price row)
        surecart/product-selected-price-scratch-amount /
        surecart/product-selected-price-amount /
        surecart/product-selected-price-interval /
  core/group (right zone, selfStretch:"fill")
    surecart/product-buy-buttons (paired)
      surecart/product-buy-button (Add) /
```

**Distinguishing markers:**
- Two-zone flex: `selfStretch:"fit"` left + `selfStretch:"fill"` right pushes CTA right.
- `className:"is-vertically-aligned-center"` on inner groups; wrapper div MUST also include `is-layout-flex is-vertically-aligned-center`.
- Heading level 4 (not 1 or 2) for compact display.
- Single CTA (no Buy-Now pair).

## Archetype F — Slide-out cart line item

**Source pattern:** `cart-new`

**Tree:**
```
surecart/slide-out-cart-line-items (paired, metadata.ignoredHookedBlocks)
  core/group (item, flex column)
    core/group (flex row, vertical-stretch)
      surecart/cart-line-item-image (80px fixed, border-radius:4px) /
      core/group (flex column, fill)
        core/group (flex row, space-between)
          core/group (flex column, fill — title block)
            surecart/cart-line-item-title /
            core/group (flex column — variant + note)
              surecart/cart-line-item-price-name /
              surecart/cart-line-item-variant /
              surecart/cart-line-item-note /
            surecart/cart-line-item-status /
          core/group (flex column, fit — amount block)
            core/group (flex row)
              surecart/cart-line-item-scratch-amount /
              surecart/cart-line-item-amount /
              surecart/cart-line-item-interval /
            core/group (flex column)
              surecart/cart-line-item-trial /
              surecart/cart-line-item-fees /
        core/group (flex row, space-between — bottom row)
          core/group (flex fill)
            surecart/cart-line-item-quantity /
          core/group (flex right)
            surecart/cart-line-item-remove /
```

**Distinguishing markers:**
- Image: 80px fixed square.
- Title block: flex-fill (absorbs space).
- Amount block: flex-fit (sizes to content).
- Quantity stepper: flex-fill (takes remaining width).
- Remove button: flex-right alignment.
- ONLY block in the library with `metadata.ignoredHookedBlocks:["surecart/cart-line-item-divider"]`.

## Archetype G — Course feature card row (icon + label + body)

**Source patterns:** `product-physical-content`, `product-course`, `product-course-dark`, `product-course-dark-content`

**Tree:**
```
core/group (flex row, blockGap 24–32px, marginBottom 40–80px)
  core/group (flex column, flexSize:258–327px — feature card)
    core/html (inline SVG, 24×24, stroke-only) /
    core/group (flex column, blockGap 0, marginTop 16px)
      core/paragraph (bold label: "High quality") /
      core/paragraph (body description) /
  // ... 3-6 sibling cards
```

**Distinguishing markers:**
- Cards are fixed-width via `flexSize:"258px"` (or similar).
- SVG: viewBox-based, `fill="none"`, `stroke="<palette-color>"`, `stroke-width="1.25"`, `stroke-linecap="round"`, `stroke-linejoin="round"`.
- Label: bold paragraph (NOT a heading — keeps semantic weight low for marketing copy).
- 3 cards in physical, 6 cards in course (Video Classes / Mobile / Bonus / Downloads / Lifetime / Community).

## Archetype H — Buy-button row (always paired)

**Source patterns:** all `product-*` and `sticky-purchase` (20 instances)

**Tree:**
```
surecart/product-buy-buttons (paired, blockGap:5px)
  <div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
    surecart/product-buy-button (add_to_cart:true, text:"Add To Cart" [, width:50]) /
    surecart/product-buy-button (text:"Buy Now", className:"is-style-outline" [, width:50]) /
  </div>
```

**Distinguishing markers:**
- Wrapper div ALWAYS has 4 classes (no fewer): `wp-block-surecart-product-buy-buttons`, `wp-block-buttons`, `sc-block-buttons`, `is-layout-flex`.
- Buttons can carry `width:50` (50% each) for equal split, or no width (auto-fit).
- Per-corner `border.radius` for pill buttons (e.g. `999px` on all 4 corners) appears as object form, not single literal.
- Skill must NEVER emit `surecart/product-buy-button` outside this wrapper.

## Theming archetypes (palettes observed)

| Palette | Use case | Patterns | Hex values |
|---|---|---|---|
| Default neutral (light) | Standard products, lists | `product-standard`, `product-alternate`, `list-*` | `#111827`/`#4b5563` text, `#686868`/`#8a8a8a` muted, `#f9fafb` bg, `#dc2626` sale red |
| Brown / earth | Lifestyle / physical goods | `product-physical*` | `#28201b` text, `#5b5048` secondary, `#8b4513` action, `#8b451340` muted-border, `#f3f0ec` bg, `#dc2626` sale red |
| Gray / dark + emerald | Course / education (dark mode) | `product-course-dark*` | `#1a1b26` outer bg, `#212231` cards, `#2b2d3b` borders, `#9ca3af` muted text, `#34d399` action emerald, `#fff`/`var:preset|color|white` text, `#e5e7eb` muted-light |

When mapping a Claude Design palette: snap to the closest archetype within ΔE 6 (CIE Lab); if no match, emit literal hex and log under `color_snap_misses` per Tier C C-19.
