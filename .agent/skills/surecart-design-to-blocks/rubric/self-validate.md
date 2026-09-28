# Self-Validation Rubric — Run BEFORE Emitting

Before you finalize the markup and respond to the merchant, mentally walk through the rubric below against your candidate output. **If any item fails, fix the markup and re-run.** Do not skip items — each one corresponds to a real production failure mode.

State the rubric result at the start of your response, then emit the markup. Format:

> Self-validation: Tier A 14/14, Tier B 7/7, Tier C skipped (input: zip).

If any item fails and you cannot fix without losing fidelity, state which item failed and why, and emit anyway with that specific issue called out — don't silently ship a broken page.

## Rubric tiers

The rubric is organized by **failure blast radius** AND **emission phase**:

- **Tier I — Intake prompt validation (NEW in v7.22).** Conditional checks that run ONLY when the skill is in **intake mode** (per Step 0 router) and is about to emit a Claude Design prompt to the merchant. Validates the prompt itself, not converter output. Skipped in convert mode. Items I-1 through I-9.
- **Tier A — Blocking.** Paste-safety / recovery-cascade prevention. A failure here renders the page broken on first paste. Fix and re-emit before responding. Items A-1 through A-14 (and additive A-20+).
- **Tier B — Fidelity.** Page loads correctly but visually drifts from source design. A failure here is logged in the drift report but does not block emission unless the merchant explicitly requested high-fidelity (e.g., "match exactly"). Items B-13 onward.
- **Tier C — Screenshot-only.** Conditional checks that run ONLY when `input_type:"screenshot"`. Skipped on `zip`, `html_css`, and `live_url` inputs. Items C-17 through C-22.

---

## Tier I — Intake prompt validation (v7.22)

Run these checks **before emitting the Claude Design prompt to the merchant**. All 9 items are blocking — fix the populated template and re-validate before sending. Tier I only applies in intake mode (per Step 0 router); skip entirely in convert mode.

### I-1. Output target declared

- [ ] The populated prompt's §1 explicitly names "Claude Design project" + the 4-file structure (`product-page.jsx`, `sections-1.jsx`, `sections-2.jsx`, `assets/colors_and_type.css`)
- [ ] No ambiguous targeting language like "build me a webpage" or "design a landing page"

**Failure signature:** Claude Design produces output in the wrong file structure (e.g. a single HTML file, a Next.js app). Recover by re-emitting with explicit §1 contract.

### I-2. Surface markers match the calling skill

- [ ] For `surecart-design-to-blocks` (product-detail): exactly the two markers `{/* SURFACE: PRODUCT-TEMPLATE */}` and `{/* SURFACE: STATIC-CONTENT */}` are named in §3
- [ ] For `surecart-design-to-shop-page` (sibling): exactly the three markers `{/* SURFACE: FILTER-CHROME */}`, `{/* SURFACE: PRODUCT-GRID */}`, `{/* SURFACE: PAGE-CHROME */}` are named in §3
- [ ] No surface markers from the other skill leaked in (cross-contamination check)

**Failure signature:** the downstream converter cannot route the design regions correctly because surfaces don't match its parser. Recover by regenerating from the correct `prompt-template.md`.

### I-3. Constraint digest sync verified

- [ ] `intake/constraint-digest.md` exists and its `digest-version:` matches `SKILL.md` `version:` in front matter
- [ ] `intake/constraint-digest.md` `source-hash:` matches a freshly-computed sha256 of `SKILL.md`'s `## Doctrine reversals` + `## Hard constraints` sections (run `node scripts/regen-digest.mjs --check`)
- [ ] `intake/constraint-digest.md` total size ≤ 8KB (cap accommodates 44+ HC one-line summaries + doctrine-reversals table + frontmatter; `scripts/regen-digest.mjs` errors out if exceeded)

**Failure signature:** the prompt embeds stale HC constraints that re-introduce reversed doctrine (e.g. v7.0 sticky position trap). Recover by running `node scripts/regen-digest.mjs` and re-emitting.

### I-4. Every questionnaire answer is reflected in §6

- [ ] §6 merchant brief contains a line per slot (`{{Q1_PRODUCT_TYPE}}`, `{{Q3A_VARIANTS}}`, `{{Q3B_PRICING}}`, `{{Q4_SECTIONS}}`, `{{Q5_VISUAL_MOOD}}`, `{{Q6_TYPOGRAPHY}}`, `{{Q2_ACCENT_DESCRIPTION}}`)
- [ ] No `{{SLOT}}` placeholder appears unsubstituted in the emitted prompt
- [ ] Skipped questions (per branching rules) use their default values from `questions.md`, not the literal placeholder

**Failure signature:** Claude Design sees a literal `{{Q5_VISUAL_MOOD}}` token and treats it as garbage. Recover by completing substitution and re-emitting.

### I-5. Prompt does not request anti-pattern content

- [ ] Cross-check the populated prompt against `intake/shared/anti-patterns.md` rules 1–10
- [ ] No language in §6 merchant brief implies parallax, scroll-jacking, hover-only content, or other forbidden patterns
- [ ] If the merchant's brand language (e.g. "really animated", "with motion graphics") would lead Claude Design toward forbidden patterns, soften the brief and add a note in §6 reminding "motion is added post-paste by the theme"

**Failure signature:** Claude Design produces an animation-heavy design that fails the converter's set-equality checks. Recover by softening §6 and re-emitting.

### I-6. Brand name + tone appear exactly once each

- [ ] The merchant's brand name (or "Untitled Brand") appears exactly once in §6
- [ ] The Q5 visual mood label appears exactly once in §6
- [ ] No duplicate brand/tone mentions in §0, §5, §7 (those sections are stylistically neutral)

**Failure signature:** Claude Design over-indexes on a repeated brand mention and produces a design that's all logo / all brand statement, no product. Recover by deduplicating.

### I-7. Word count within target range

- [ ] The populated prompt (everything inside the fenced ```` ```md ```` block) is between **600 and 1,700 words** (1,200–1,700 typical; floor is 600 for minimal-answer paths)
- [ ] If above 1,700: trim §5 exemplar skeleton first (largest variable cost); if still over, drop §2b table to its 3 most-used rows
- [ ] If below 600: the merchant's answers were too thin — populate `{{HERO_TRAILING_SECTIONS}}` and `{{STATIC_SECTIONS}}` with defaults from `questions.md`

**Failure signature:** design.claude.com truncates or ignores parts of an over-budget prompt; an under-budget prompt produces a vague design. Recover by adjusting per the rule above.

### I-8. Output is one fenced markdown block, copy-paste-ready

- [ ] The closing message (per `prompt-template.md` workflow step 7) appears once, above the fenced block
- [ ] The fenced block uses ```` ```md ```` opening and ```` ``` ```` closing
- [ ] No prose interrupts the fence — the entire prompt body is inside one fence
- [ ] The merchant can select-all + copy the fenced contents without picking up extra chrome

**Failure signature:** merchant copies the prompt with skill commentary mixed in; design.claude.com sees the commentary as part of the design brief. Recover by re-emitting with cleaner fencing.

### I-9. Surface-marker strip verification (defense-layer)

- [ ] No `<!-- SURFACE:` substring appears anywhere in any converter golden output under `tests/fixtures/<NN>/golden.html` (HTML-comment form of surface markers — would trigger HC#45 if leaked into apiVersion:3 regions)
- [ ] No `{/* SURFACE:` substring appears in any converter golden output (JSX-comment form — should have been stripped before Gutenberg emission)

**Why this check lives in Tier I despite being a converter-side property:** I-9 verifies the **strip stage** of the converter pipeline ran correctly on prior outputs. Surface markers are a routing-only contract between the emitted prompt and the converter; they must NEVER appear in final markup. Grep all goldens at intake-time as a sanity check that the strip contract holds.

**Greppable check:**

```bash
grep -lrE "<!-- SURFACE:|\\{/\\* SURFACE:" tests/fixtures/*/golden.html
```

Expected: zero matches. Any match is a strip-stage bug — fix the converter's surface-marker handling before shipping the next emission.

### I-10. Palette discipline paragraph present in §4

- [ ] §4 (Style tokens) contains the literal string `Palette discipline.` exactly once
- [ ] The discipline paragraph explicitly forbids companion-slug extrapolation (no `-100`/`-200`/`-700`/`-900` shades; no thematically-adjacent named slugs like `surecart-oak-900` / `surecart-olive-700` / `surecart-ochre-500` / `surecart-cream-50`)
- [ ] The discipline paragraph names the 6 neutral slugs guaranteed to resolve (`surecart-text`, `surecart-text-muted`, `surecart-text-inverse`, `surecart-bg`, `surecart-surface`, `surecart-border`)
- [ ] The discipline paragraph names raw `#RRGGBB` as the fallback for additional shades / earth tones

**Why this check exists:** HC#51 (v7.22.1) — Claude Design's prior is to extrapolate one named accent into a full Tailwind-style scale and invent thematically-adjacent palettes. Without the palette-discipline paragraph, the design returns referencing 10+ invented `surecart-*` slugs that don't exist in `theme.json`, and the converter falls back to literal-hex (which works but bypasses theme-overridability). The palette-discipline paragraph cuts this off at the source.

**Failure signature:** if I-10 fails (paragraph missing), the merchant's design returns with invented companion slugs; the converter's `color_snap_misses[]` array balloons to 10+ entries.

**Greppable check:**

```
grep -c "Palette discipline\." <emitted-prompt>
```

Expected output: exactly 1.

### Tier I tally

State at the start of your intake-mode response:

> Self-validation: Tier I 10/10. Emitting Claude Design prompt below.

If any item fails, fix the populated prompt and re-validate. Do not emit a Tier-I-failing prompt to the merchant.

---

## Tier A — Blocking (paste-safety)

### A-1. Outer wrapper

- [ ] Output starts with `<!-- wp:surecart/product-page` and ends with `<!-- /wp:surecart/product-page -->`
- [ ] The wrapper carries `metadata.name` and `metadata.patternName`
- [ ] **NO `<div class="wp-block-surecart-product-page">` wrapper** — `surecart/product-page` is `apiVersion:3` server-rendered. Children sit between the comment markers as siblings.
- [ ] Comment markers use the **unprefixed core form**: `<!-- wp:group -->`, NOT `<!-- wp:core/group -->`. Same for columns, column, heading, paragraph, list, list-item, image, details, etc.

### A-2. No hallucinated block names

- [ ] No `surecart/variant-picker` (use `surecart/product-variant-pills`)
- [ ] No `surecart/quantity` (use `surecart/product-quantity`)
- [ ] No `surecart/add-to-cart-button` or `surecart/buy-now-button` (use `surecart/product-buy-button` inside `surecart/product-buy-buttons`)
- [ ] No `surecart/product-price` (use `surecart/product-price-chooser`)
- [ ] No `surecart/product-list` for related context (use `surecart/product-list-related`)
- [ ] Every `surecart/*` block name appears in `reference/full-inventory.json` (next-gen) OR `reference/surecart-blocks.md § Legacy blocks` (legacy form/checkout/donation). Do NOT flag legacy block names as hallucinations purely because they're absent from full-inventory.json (which is next-gen-only — see `.dev/generate-block-inventory.js`).

**Namespace typo grep (v7.20 PR4)** — comment markers use SLASH between namespace and name, NOT dash. The most common namespace typo is `wp:surecart-product-title` instead of `wp:surecart/product-title` (dash instead of slash). The parser treats `wp:surecart-product-title` as a foreign namespace it doesn't recognize → block silently drops. Pre-emit check:

```
grep -nE '<!-- (/)?wp:surecart-' <output>
```

Zero matches expected. Same check for `core/`:

```
grep -nE '<!-- (/)?wp:core-' <output>
```

### A-3. No self-closed paired core blocks

- [ ] Zero occurrences of `<!-- wp:core/columns /-->`, `<!-- wp:core/column /-->`, `<!-- wp:core/group /-->`, `<!-- wp:core/cover /-->`, `<!-- wp:core/media-text /-->`, `<!-- wp:core/buttons /-->`, `<!-- wp:core/list /-->`, `<!-- wp:core/list-item /-->`, `<!-- wp:core/quote /-->`, `<!-- wp:core/details /-->`
- [ ] Each opening `<!-- wp:core/{name} -->` (paired) has a matching closing `<!-- /wp:core/{name} -->` AND a wrapper `<div class="wp-block-{name}">…</div>` in `innerHTML`

### A-4. Wrapper class match for paired blocks

- [ ] Every `core/columns` has `<div class="wp-block-columns">` (plus `alignwide`/`alignfull` if `align` attr set)
- [ ] If `core/columns` has `verticalAlignment` attr, parent wrapper has `are-vertically-aligned-{value}` AND each child `core/column` has `is-vertically-aligned-{value}` on its wrapper
- [ ] Every `core/column` has `<div class="wp-block-column">`
- [ ] Every `core/group` has `<div class="wp-block-group">` (plus background classes if `backgroundColor` attr set)
- [ ] **Layout double-class on `core/group` (v7.2 — empirically verified recovery trigger).** When `core/group` has `"layout":{"type":"constrained"}` in its JSON attrs, the wrapper class MUST include BOTH `is-layout-constrained` AND `wp-block-group-is-layout-constrained`. Same shape for `"flex"` / `"flow"` layouts: `is-layout-{type}` AND `wp-block-group-is-layout-{type}`. Empirical evidence: a 5-item FAQ pasted with the layout double-class missing recovered every single block on first paste; adding the classes alone fixed the cascade. Class order: layout double-class goes AFTER any `has-{slug}-*` and `has-text-color`/`has-background`/`has-border-color` modifier classes.
- [ ] Every `core/buttons` has `<div class="wp-block-buttons">`
- [ ] Every `core/button` has `<div class="wp-block-button">` containing `<a class="wp-block-button__link wp-element-button" href="…">…</a>` (the `href` is required — without it the parser falls back to `<button>` and breaks)
- [ ] Every `core/details` opener carries `{"summary":"<text>"}` matching the inner `<summary>` HTML
- [ ] **`core/details` `<summary>` is plain (v7.2 — empirically verified recovery trigger).** The `<summary>` element MUST be `<summary>{text}</summary>` — no `class`, no `style`, no inline modifiers. Gutenberg's `core/details` `save()` emits a bare `<summary>` and uses the JSON `summary` attr as the source of truth for the text. Adding classes/styles to `<summary>` makes the wrapper not match `save()`'s output → recovery → empty content rendered (block stripped to `summary` attr only, inner blocks lost). Empirical evidence: pasted FAQ with styled `<summary>` recovered with zero content; stripping the classes/styles fixed it. Style the summary text via theme CSS keyed off `.wp-block-details summary` instead.

### A-5. `core/heading` and `core/paragraph` have inner HTML

- [ ] Every `core/heading` has its `<h{level} class="wp-block-heading">…</h{level}>` (NOT self-closed)
- [ ] Every `core/paragraph` has its `<p>…</p>` (NOT self-closed)

### A-6. Buy-buttons ancestor

- [ ] Every `surecart/product-buy-button` is inside a `surecart/product-buy-buttons` parent
- [ ] If both Add-to-Cart AND Buy-Now exist, both are inside the SAME `surecart/product-buy-buttons` (not two wrappers)

### A-7. `metadata` attribute placement

- [ ] `metadata` appears ONLY on the outer `surecart/product-page` block
- [ ] No inner block carries a `metadata` attribute

### A-8. `__experimentalSkipSerialization` carve-out

- [ ] `surecart/product-buy-button` has its color/spacing/**border** attrs (where the design specifies them) but **NO** inline `style=""` and **NO** color/spacing/border classes on its wrapper element. The block skips serialization on **all three** support trees — but **typography is NOT skipped**, so `fontFamily` slug + `has-{slug}-font-family` class on the buy-button wrapper IS allowed and recommended for typography.
- [ ] If the design needs styling around the buy buttons, it's on a parent `core/group` wrapper, NOT on the button itself
- [ ] If a `surecart/*` placeholder block (e.g. `surecart/product-list-related`, `surecart/product-media`) is replacing 3+ distinct visual elements from JSX (badges, overlays, gradients, custom buttons), wrap it in a styled `core/group` AND log under `dropped_features` what fidelity is delegated to the block's default render

### A-9. Slug-attr classes match the wrapper

For every block where you used a slug attr (`textColor`, `backgroundColor`, `fontSize`, `fontFamily`, `borderColor`, `align`):
- [ ] The wrapper element's `class=""` includes the matching `has-{slug}-{kind}` class (see `reference/style-conversion.md` table)
- [ ] **Slug existence verified.** Every preset slug emitted (e.g., `surecart-brand`, `surecart-bg-dark`, `surecart-display`) is a literal `"slug": "<name>"` line in `reference/theme-partial.json` (which is auto-mirrored from production `app/data/surecart-theme-partial.json` via `yarn build:theme-partial-mirror`). Never invent slugs by pattern (`surecart-white` exists; `surecart-bg-1` does NOT — verify each slug against the mirror).
- [ ] **`fontFamily` is set on every text-bearing block.** Display headings → `surecart-display` + class `has-surecart-display-font-family`. Body copy → `surecart-body` + `has-surecart-body-font-family`. Mono → `surecart-mono` + `has-surecart-mono-font-family`. Without this, the page renders in the active theme's default font, not Geist.
- [ ] **No `var:preset|spacing|*` in `style.spacing.*` attrs** (covers `padding`, `margin`, AND `blockGap`). Spacing/border MUST be literal px (`"24px"`, `"48px"`, `"96px"`). Slug spacing produces long `var(--wp--preset--spacing--N)` strings inside `style=""` that line-wrap on paste, corrupting markup. Slug COLOR (`backgroundColor:"surecart-white"`) and slug FONT (`fontFamily:"surecart-display"`) stay — those emit short class names with no inline `var()`.
- [ ] `core/paragraph` and `core/heading` with `align` or `textAlign` attr have matching `has-text-align-{value}` class

### A-10. Inline style mirrors `style.*` attrs

For every block where you wrote `"style":{"color":{...}}` or `"style":{"spacing":{...}}` etc. as literals (not slugs):
- [ ] The wrapper element's `style="…"` includes the matching CSS property declarations
- (Exception: `__experimentalSkipSerialization` blocks — write attrs only, no inline style)
- (Rule 0 carve-out: paired-block wrappers strip inline style for slug-bg cases; literal-bg keeps ALL inline mirrors per Exemplar 3.5)

### A-11. `.map()` expansion

- [ ] Every JSX `.map(item => <X/>)` over a literal data array has been expanded to N concrete sibling blocks (not left as a placeholder)
- [ ] No template-language artifacts in the output (no `{{name}}`, no `${variable}`, no `<%= … %>`)

### A-9a. Save() byte-perfect tokens (THE recovery-prompt killers)

For every block, ensure the wrapper class set + inline style set exactly match what `save()` would produce. Specific check items:

- [ ] Every `core/group` with `style.border.width` has `border-style:solid` in inline style (Rule 1)
- [ ] Every `core/group` with `"layout":{"type":"constrained"}` has `is-layout-constrained is-layout-flow` in wrapper class (Rule 2)
- [ ] Every `core/group` with `"layout":{"type":"flex"}` has `is-layout-flex is-layout-flex-row` (or `-column`) (Rule 2)
- [ ] Every `core/group` with `"layout":{"type":"flow"}` has `is-layout-flow` (Rule 2)
- [ ] Every `core/media-text` has `grid-template-columns:{N}% auto` (left, default) or `auto {N}%` (right) inline style based on `mediaWidth` + `mediaPosition` (Rule 3)
- [ ] Every `core/media-text` has `is-stacked-on-mobile` class (default) — only omit if `isStackedOnMobile:false`
- [ ] Every `core/button` `<a>` (or `<button>`) has `wp-element-button` class (Rule 4)
- [ ] Every `core/button` `<a>` has `href="…"` attribute (Rule 4 — without href, parser falls back to `<button>`)
- [ ] Every `core/columns` with `verticalAlignment` set has `are-vertically-aligned-{value}` on wrapper AND each `core/column` child has `is-vertically-aligned-{value}` on its wrapper
- [ ] Every `core/columns` with `isStackedOnMobile:false` has `is-not-stacked-on-mobile` class (Rule 5)
- [ ] `style.border` with literal `color`/`width`/`radius` mirrors to inline style with ALL three properties + `border-style:solid`
- [ ] `core/heading`'s `<h{level}>` always has `class="wp-block-heading"` (plus `has-text-align-{x}` if `textAlign` set)
- [ ] Every `core/table`'s inner `<table>` has `class="has-fixed-layout"` (default `hasFixedLayout:true` is always emitted — this is the most-missed class on tables)
- [ ] `core/table`'s `<figure>` wraps the `<table>`; `wp-block-table` is on the `<figure>` only; caption uses `<figcaption class="wp-element-caption">` inside `<figure>` (never `<caption>` inside `<table>`)
- [ ] `core/separator` always has `has-alpha-channel-opacity` class (default `opacity:"alpha-channel"`)
- [ ] `core/spacer` always has `style="height:Npx"` + `aria-hidden="true"` (default `height:"100px"`)
- [ ] `core/image` always has `alt=""` attribute (even when empty)
- [ ] No whitespace inside single-block HTML tags (no newlines/indentation inside `<table>`, `<h{level}>`, `<p>`, `<img>`, `<hr>`)

### A-12a. Section completeness (no silent drops)

- [ ] Every named section component in the JSX (`Hero`, `Highlights`, `Specs`, `FAQ`, `Related`, `FinalCTA`, plus any `Header`, `Breadcrumbs`, `DescriptionBlocks`, `StickyBuyBar`, `Footer`) appears in the output **in source order**, OR each omission has a `dropped_features` entry with reason
- [ ] Eyebrows / kickers (any `<div className="sc-eyebrow">` or visually-distinct kicker text above an h2) are emitted as `core/paragraph` with `textColor:"surecart-brand"`, `fontSize:"14px"`, `fontWeight:"600"`, `letterSpacing:"0.04em"`, `textTransform:"uppercase"` — never silently stripped
- [ ] `marginBottom` / `marginTop` values from JSX are preserved 1:1 in `style.spacing.margin.{bottom,top}`. Do NOT round 28→32, 14→16, etc. **(Zip-input only — screenshot-input snap-to-scale rule applies on the screenshot path.)**
- [ ] `maxWidth: N` from JSX is emitted as `layout.contentSize:"{N}px"` literally. Do NOT snap to nearest 100px multiple.

### A-12. Round-trip safety

- [ ] No mismatched comment markers: every `<!-- wp:` has a matching close (either `/-->` or `<!-- /wp:{name} -->`)
- [ ] No HTML inside a block's attribute JSON that breaks parsing (escape `"` inside string attrs as `"`)
- [ ] No stray PHP, no `<script>` tags, no inline event handlers (`onclick=`/`onchange=`/`onsubmit=`/`onload=`/etc.) in the output. **v7.1 carve-out:** `data-wp-*` directives (`data-wp-on--click`, `data-wp-bind--*`, `data-wp-context`, `data-wp-text`, `data-wp-class--*`, `data-wp-init`, `data-wp-watch`, `data-wp-each`, `data-wp-interactive`) are allowed **inside `core/html` blocks at L2 only** (see A-15) — they're declarative directives the WordPress Interactivity runtime hydrates, not inline JS handlers.

### A-15. `core/html` fallback ladder (v7.1)

For every `core/html` block in the output, classify the emit and apply the matching contract:

- [ ] **L1 — static.** Block contains no `data-wp-*` attributes, no `data-wp-interactive` root. Drift report has a matching `dropped_features.type:"raw_html_static"` entry.
- [ ] **L2 — Interactivity API piggyback.** Outermost element inside the `core/html` block has `data-wp-interactive='{"namespace":"surecart/X"}'` where `surecart/X` is one of the **7 documented namespaces**: `surecart/checkout`, `surecart/cart`, `surecart/product-page`, `surecart/order-bumps`, `surecart/product-quick-view`, `surecart/lightbox`, `surecart/image-slider`. **Inventing a namespace fails silently** — directives never hydrate. All directives reference actions/state owned by SureCart's own next-gen blocks; never invent action names. Drift report has a matching `dropped_features.type:"raw_html_interactive", level:"L2"` entry with `namespace` AND `dependency_block` fields.
- [ ] **Cross-namespace correctness (v7.1.1).** For every `data-wp-text`, `data-wp-bind--*`, `data-wp-class--*`, `data-wp-on--*` directive, the referenced `state.X` / `actions.X` must be owned by the namespace of the **nearest ancestor `data-wp-interactive` root**. Cross-check against the namespace allowlist table in `reference/custom-html-fallback.md` — e.g., `state.itemsCount` is owned by `surecart/checkout`, `actions.toggle` is owned by `surecart/cart`. When a single widget references state/actions from two namespaces, either (a) wrap the cross-namespace element in a nested `data-wp-interactive` declaring the correct namespace, or (b) use the inline `namespace::path` prefix (e.g., `data-wp-on--click="surecart/cart::actions.toggle"`). **A mismatched reference fails silently** — the runtime returns `undefined` and the directive renders as if absent (the element shows its static fallback content). Verified empirically: `state.itemsCount` referenced inside a `surecart/cart` root never updates; the count never increments.
- [ ] **L3 — drop + recommend custom block.** Block contains best-effort static visual fallback (no behavior). Drift report has a matching `dropped_features.type:"raw_html_interactive", level:"L3"` entry with a `merchant_action` recommending `/surecart-new-block` or `/surecart-new-integration`.
- [ ] **No `<script>`, `<style>`, or inline event handlers** inside any `core/html` block, regardless of tier. Style goes in theme CSS; behavior goes through `data-wp-*` directives at L2 or a registered block at L3.
- [ ] **Balanced HTML** — every `<div>`, `<span>`, etc. inside a `core/html` block has its matching close. Unbalanced tags trigger paste recovery on save.

### A-20. `has-border-color` always-emit (v7.6 — REVERSES v7.3)

Every wrapper with `style.border.color` (top-level, NOT per-side `border:{top:{...},bottom:{...}}`) in JSON MUST emit `has-border-color` in the HTML `class=""` attribute.

- [ ] For every block in the output with JSON `style.border.color:"..."` (literal hex or slug), the wrapper element's `class="..."` includes `has-border-color`.
- [ ] When `borderColor:"<slug>"` is present, `class="..."` ALSO includes `has-{slug}-border-color`.
- [ ] Per-side asymmetric borders (`border:{bottom:{color,width},top:[],left:[],right:[]}` form, no top-level `color`) do NOT emit `has-border-color`. Confirm by checking that the JSON has no top-level `style.border.color` key.

**Failure signature:** `"Block validation: Expected attribute class of value 'wp-block-group has-border-color has-background', saw 'wp-block-group has-background'"` — recovery cascade on every bordered card. Empirically verified Aurora Lamp paste-test (2026-05-11).

### A-21. No HTML comments inside apiVersion-3 server-rendered blocks (v7.6)

Inside `surecart/product-page` (and any apiVersion-3 server-rendered block — those with no `save()` function), the post-content body is parsed as a flat sequence of block-comment-delimited children only.

- [ ] No plain HTML comments (`<!-- 1. HERO === -->`, `<!-- Section: Reviews -->`, `<!-- TODO -->`, etc.) appear between top-level children of `surecart/product-page`.
- [ ] Block-comment markers (`<!-- wp:NAME -->` ... `<!-- /wp:NAME -->`) are the ONLY comment-shaped content allowed between children.
- [ ] Use blank lines for visual separation between sibling sections; let the block names document structure.
- [ ] Annotation comments are also forbidden inside the inner `core/group` body-bg shim (it's a `core/group`, but the recovery cascade still triggers because comments aren't valid block content).

**Failure signature:** `"Block validation: Expected end of content, instead saw {type: 'Comment', chars: ' 1. HERO === '}"` — the outermost wrapper drops into recovery and emits empty content. Empirically verified Aurora Lamp paste-test (2026-05-11).

### A-22. `core/image` is strict-schema — no freehand inline styles (v7.6, amended v7.10)

`core/image`'s `save()` emits a tightly controlled set of inline styles on `<img>` — see **A-30** for the v7.10 amendment covering top-level `width`/`height` attrs. Anything OUTSIDE the controlled set on `<figure>` or `<img>` is a paste-recovery trigger.

- [ ] No `flex-basis`, `aspect-ratio`, `object-fit`, `margin`, or any other freehand inline style on `<figure>` or `<img>` elements inside `core/image` blocks.
- [ ] Allowed inline styles on `<img>` are limited to: `border-radius:Npx` (when `style.border.radius` is set) AND `width:Npx;height:Npx` (when top-level `width`/`height` attrs are set — see A-30). Combined order: `border-radius;width;height`.
- [ ] To control width inside a flex-row parent (P5 multi-card row, gallery grids), wrap each `core/image` in a `core/group` with `style.layout:{selfStretch:"fixed",flexSize:"NN%"}`. The group carries the width; the image stays schema-clean.
- [ ] To restore lost design properties (`aspect-ratio`, `object-fit`, `width:100%`), drop+log under `dropped_features.type:"asset_style"` with a CSS-snippet `merchant_action`.

**Failure signature:** `"Block validation: Expected attributes [Array(3)], instead saw (2) [Array(3), Array(3)]"` — set-equality fails on the figure or img element. Empirically verified Aurora Lamp paste-test (2026-05-11).

### A-23. `core/list` wrapper class + no inline-styled children (v7.6)

Two requirements for `core/list`:

- [ ] **`wp-block-list` class on wrapper.** The `<ul>` (or `<ol>` for ordered) MUST include `wp-block-list` as the first class. Slug typography classes (e.g., `has-surecart-body-font-family`) come AFTER.
- [ ] **No inline-styled `<strong>` or `<span>` siblings inside `<li>` content.** `<li><strong style="…">Display:</strong><span style="…">6.3″ XDR</span></li>` triggers the deprecated `wp.blocks.children.matcher` path (deprecated since WP 6.1, slated for 6.3 removal) — set-equality fails.
- [ ] Plain text `<li>` content (e.g., `<li>USB-C power adapter (6 ft)</li>`) is fine. Plain `<strong>` or `<em>` without inline styles is also fine.
- [ ] For 2-column key:value tabular data (specs tables, comparison tables), do NOT use `core/list` — emit a parent `core/group` (vertical flex) wrapping N detail-row `core/group` (horizontal flex, `justifyContent:"space-between"`), each with 2 `core/paragraph` siblings. This is P7 (detail-row primitive); see `reference/core-blocks-cheatsheet.md` Exemplar 7.

**Failure signatures:**
- `"Expected attribute class of value 'wp-block-list is-style-none …', saw 'is-style-none …'"` — wrapper class missing.
- `"wp.blocks.children.matcher is deprecated since version 6.1 and will be removed in version 6.3"` warning + set-equality drift on the list — inline-styled children path.

Empirically verified Aurora Lamp paste-test (2026-05-11).

### A-24. `core/group` `style.dimensions.aspectRatio` is FORBIDDEN (v7.7)

`core/group` does NOT emit `aspect-ratio:N` in inline style for `style.dimensions.aspectRatio` JSON. The value routes through a CSS-variable / class chain that save() applies separately. Including `aspect-ratio:N` in the inline mirror triggers set-equality drift.

- [ ] No `core/group` block in the output has `style.dimensions.aspectRatio` in JSON.
- [ ] No `core/group` `<div>` element has `aspect-ratio:N` in its inline `style="..."` attribute.
- [ ] For square / fixed-ratio placeholder boxes: use explicit `padding-top` / `padding-bottom` (e.g., 60px vertical inside a `flexSize:"32%"` column ≈ square at typical container widths) OR use `core/cover` with `aspectRatio` (top-level attr, NOT under `style.*`) OR use `core/image` with placeholder URL.
- [ ] Drop+log under `dropped_features.type:"aspect_ratio_on_group"` with a `merchant_action` CSS-snippet recommending `aspect-ratio: N` via theme stylesheet on the relevant class.

**Failure signature:** `"Block validation: Expected attribute style of value '<base>', saw '<base>;aspect-ratio:1'"` followed by `"Block validation failed for core/group"`. Recovery cascades on every group with the property.

Empirically verified Morning Glow Serum paste-test (2026-05-11).

### A-25. Zero-value spacing properties — JSON ↔ inline parity (v7.7)

The v7.4 set-equality rule applies to ALL `style.spacing.*` values including zeros. A JSON path like `style.spacing.margin:{top:"0",bottom:"0"}` MUST emit `style="margin-top:0;margin-bottom:0"` on the wrapper. JSON-only (no inline mirror) triggers recovery.

- [ ] For every `style.spacing.{margin|padding}.{side}:"0"` in JSON, the wrapper element's inline `style="..."` contains `margin-{side}:0` / `padding-{side}:0`.
- [ ] For every `style.spacing.blockGap:"0"` in JSON, the wrapper carries the matching `blockGap` mirror (note: blockGap doesn't always appear in inline — for paired wrappers it's emitted as a CSS rule via `:where()`; for `core/group flex` it may appear as `gap:0` inline. Verify against block type).
- [ ] If the wrapper doesn't need the explicit zero override (default spacing is already 0 or acceptable), DROP the property from JSON entirely rather than emit JSON-only.
- [ ] After any wrapper-style refactor, re-run B-27 (set-equality) to catch stale zero-value JSON properties that no longer have an inline mirror.

**Common failure scenario:** removing inline `margin-top:0;margin-bottom:0` from a `surecart/product-buy-buttons` div but forgetting to also remove `style.spacing.margin:{top:"0",bottom:"0"}` from the JSON. Set-equality fails.

**Failure signature:** `"Expected attribute style of value 'margin-top:0;margin-bottom:0', saw ''"` or vice versa — depending on which side has the property.

Empirically verified Morning Glow Serum paste-test on `surecart/product-buy-buttons` (2026-05-11).

### A-26. `core/cover` gradient-only emission class set canonical form (v7.7)

When `core/cover` is emitted with `customGradient` (or `gradient` slug) and NO `url`, the inner `<span>` overlay class set MUST be `wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient`. Other class shapes (notably `wp-block-cover__gradient-background has-background-gradient` from older v7.0 docs) trigger recovery.

- [ ] For every `core/cover` with `customGradient` (or `gradient` slug) and no `url` in JSON:
  - Outer `<div>` has `class="wp-block-cover"` only (NOT `has-background-gradient`)
  - Outer `<div>` inline style has `min-height:Npx` only (no `background:linear-gradient(...)`)
  - Inner `<span>` has `class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient"`
  - Inner `<span>` inline style has `background:linear-gradient(...)` with the gradient value
- [ ] Gradient string format: no space after commas between angle/stops. e.g., `linear-gradient(135deg,#A 0%,#B 100%)` not `linear-gradient(135deg, #A 0%, #B 100%)`.
- [ ] **Recommended fallback:** for purely decorative gradient backgrounds with no image content underneath, prefer `core/group` with `style.color.background:"#hex"` (one of the gradient end-stops). Simpler, fewer drift risks. Drop+log under `dropped_features.type:"gradient", mode:"linear"`.

**Failure signature:** `"Expected attribute class of value 'wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient', saw 'wp-block-cover__gradient-background has-background-gradient'"`.

Empirically verified Morning Glow Serum paste-test (2026-05-11).

---

### A-27. `surecart/product-review-list` MUST be paired with Start-Basic inner template (v7.8)

When the design includes a customer-reviews section, `surecart/product-review-list` MUST be emitted as a paired block with the full Start-Basic inner template populated. Self-closing it produces a silent failure mode: the editor renders a *"Start Basic / Select a template"* placeholder picker instead of the reviews, and the merchant has to click "Start Basic" manually on every fresh paste.

- [ ] For every `surecart/product-review-list` in your candidate output:
  - It is paired (`<!-- wp:surecart/product-review-list -->` … `<!-- /wp:surecart/product-review-list -->`), NOT self-closed
  - Inner template contains, in order: a `core/heading` ("Customer Reviews" or equivalent), a `surecart/product-reviews` paired wrapper (with `product-review-summary` columns/breakdown, `product-review-list-sidebar` filter group, `product-review-template` card with reviewer-name/verified-badge/date/rating-stars/title/content, and `product-review-pagination`), and a `surecart/product-review-list-no-reviews` empty-state fallback
  - The canonical Start-Basic template is in `reference/product-page-blocks.md § surecart/product-review-list` — paste verbatim, then adjust typography/colors/spacing to match design

**Failure signature:** no block-recovery error. The published page editor shows the template-picker placeholder UI ("Start Basic" button) for the reviews block — merchant must click it manually. The skill must NEVER leave that picker visible.

Empirically verified Loom & Ash Throw paste-test (2026-05-11).

---

### A-28. `surecart/product-list-related` MUST be paired with Start-Basic inner template (v7.8)

When the design includes a "you may also love" / "related products" section, `surecart/product-list-related` MUST be emitted as a paired block with the full Start-Basic inner template populated. Self-closing it produces the same silent failure mode as A-27 — a template-picker placeholder shows instead of the products.

- [ ] For every `surecart/product-list-related` in your candidate output:
  - It is paired (`<!-- wp:surecart/product-list-related ... -->` … `<!-- /wp:surecart/product-list-related -->`), NOT self-closed
  - Inner template contains, in order: a `surecart/product-template` paired wrapper (with grid layout and inner card structure — `core/cover` with `useFeaturedImage:true` for thumbnail, `surecart/product-quick-view-button`, `surecart/product-sale-badge`, `surecart/product-title`, `surecart/product-list-price`, `surecart/product-scratch-price`) and a `surecart/product-pagination` paired wrapper (with previous/numbers/next children)
  - The canonical Start-Basic template is in `reference/product-page-blocks.md § surecart/product-list-related` — paste verbatim, then adjust `columnCount` and inner typography to match design

**Failure signature:** no block-recovery error. The editor shows the template-picker placeholder UI ("Start Basic" button) for the related-products block.

**Related pattern:** the same Start-Basic-mandate applies to `surecart/product-list` (generic product grids on collection pages) — when the design includes a product grid outside a related-products context, use `surecart/product-list` with the same inner-template shape, never self-closed.

Empirically verified Loom & Ash Throw paste-test (2026-05-11).

### A-29. `surecart/product-buy-buttons` margin inline-mirror parity (v7.10)

For every `surecart/product-buy-buttons` block whose JSON has `style.spacing.margin.{top|right|bottom|left}` set (or `style.spacing.padding.*`), the wrapper `<div>` MUST carry the matching inline `style=""` mirror.

**Set-equality scope on this wrapper:**

| JSON path | Inline mirror required? |
|---|---|
| `style.spacing.margin.*` | ✅ YES — emit `margin-top:X;margin-bottom:Y;...` |
| `style.spacing.padding.*` | ✅ YES — emit `padding-top:X;padding-bottom:Y;...` |
| `style.spacing.blockGap` | ❌ NO (CSS-var only) |
| `style.color.*` | ❌ NO (skipSerialization on per-button) |
| `style.border.*` | ❌ NO (skipSerialization on per-button) |

**Despite the block being `apiVersion:3` server-rendered**, set-equality validation runs against the hand-written wrapper `<div>`. Omitting the inline margin mirror triggers `Block validation failed for surecart/product-buy-buttons. Generated has style="margin-top:0;margin-bottom:20px", retrieved has no style`. Wrapper class set is constant (server-determined): `wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex`.

**Canonical exemplar** (v7.10):

```html
<!-- wp:surecart/product-buy-buttons {"style":{"spacing":{"blockGap":"10px","margin":{"top":"0","bottom":"20px"}}}} -->
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex" style="margin-top:0;margin-bottom:20px">
    <!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add to Cart"} /-->
    <!-- wp:surecart/product-buy-button {"text":"Buy Now","className":"is-style-outline"} /-->
</div>
<!-- /wp:surecart/product-buy-buttons -->
```

**Failure signature:** `Block validation failed for surecart/product-buy-buttons` with generated content showing inline `style="margin-..."` and retrieved content showing no inline style on the wrapper `<div>`.

Empirically verified Northwind Pour-Over Kettle paste-test (2026-05-13).

### A-30. `core/image` width/height attrs emit as inline-style, NOT as HTML attrs (v7.10 — refines A-22)

For every `core/image` block whose JSON has top-level `width:"Npx"` and/or `height:"Npx"` attrs (these are top-level block attrs, NOT `style.dimensions.*`), the `<img>` element MUST carry inline `style="width:Npx;height:Npx"` — NOT `width="N" height="N"` HTML attrs.

**Canonical inline-style key order on `<img>`:**

| JSON attrs set | Inline `style=""` shape |
|---|---|
| `style.border.radius` only | `border-radius:Npx` |
| `width` + `height` only | `width:Npx;height:Npx` |
| `style.border.radius` + `width` + `height` | `border-radius:Npx;width:Npx;height:Npx` |

**Class set rules:**
- `wp-block-image size-{slug}` — always
- `is-resized` — emitted whenever `width` OR `height` is set on the block
- `has-custom-border` — emitted whenever ANY `style.border.*` is set

**Failure signature:** `Block validation failed for core/image. Generated img has style="width:32px;height:32px", retrieved has width="32" height="32"`.

The "strict-schema" doctrine from A-22 still holds — no `flex-basis`, `aspect-ratio`, `object-fit` on `<img>`. `width`/`height` ARE schema-driven and route to inline.

**Canonical exemplar** (v7.10) — avatar with circular border + size:

```html
<!-- wp:image {"sizeSlug":"thumbnail","width":"36px","height":"36px","style":{"border":{"radius":"36px"}}} -->
<figure class="wp-block-image size-thumbnail is-resized has-custom-border"><img src="https://cdn.example.com/avatar.jpg" alt="" style="border-radius:36px;width:36px;height:36px"/></figure>
<!-- /wp:image -->
```

Empirically verified Northwind paste-test (2026-05-13, 6 occurrences — 3 highlight icons + 3 review avatars).

### A-31. `font-style:normal` JSON ↔ inline parity (v7.10)

For every text-bearing block (`core/heading`, `core/paragraph`, etc.):

- If the inline `style=""` contains `font-style:normal`, the JSON `style.typography.fontStyle:"normal"` MUST also be present.
- If the JSON has NO `style.typography.fontStyle`, the inline `style=""` MUST NOT contain `font-style:normal`.

`font-style:normal` is the CSS default. Style-engine does NOT emit it to inline unless explicitly set in JSON. Hand-adding it to the inline mirror (defensive habit) triggers set-equality drift: `Generated has "font-size:22px;font-weight:400;line-height:1.2", retrieved has "font-size:22px;font-style:normal;font-weight:400;line-height:1.2"`.

**Two valid resolutions:**

1. **Drop from inline (compact, recommended):**
   ```html
   <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"22px","fontWeight":"400","lineHeight":"1.2"},"color":{"text":"#1a1a1a"}}} -->
   <h3 class="wp-block-heading has-text-color" style="color:#1a1a1a;font-size:22px;font-weight:400;line-height:1.2">Counter-balanced spout</h3>
   <!-- /wp:heading -->
   ```

2. **Explicit in JSON (verbose, but matches production exemplars that include it):**
   ```html
   <!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"22px","fontStyle":"normal","fontWeight":"400","lineHeight":"1.2"},"color":{"text":"#1a1a1a"}}} -->
   <h3 class="wp-block-heading has-text-color" style="color:#1a1a1a;font-size:22px;font-style:normal;font-weight:400;line-height:1.2">Counter-balanced spout</h3>
   <!-- /wp:heading -->
   ```

**Symmetric rule for `italic`:** when JSON has `fontStyle:"italic"`, inline MUST emit `font-style:italic`. This is what keeps attribution lines like "— Frank Crane, founder" valid (their JSON includes `fontStyle:"italic"`).

**Failure signature:** `Block validation failed for core/heading` (or `core/paragraph`) with generated content missing `font-style:` and retrieved content including `font-style:normal`.

Empirically verified Northwind paste-test (2026-05-13, 7 `core/heading` instances).

### A-32. `core/cover` aspect-ratio JSON path fork (v7.11)

For every `core/cover` block in your output, verify the aspect-ratio JSON path AND its inline-mirror presence:

| JSON path | Inline `style=""` mirror? | When to use |
|---|---|---|
| Top-level `aspectRatio:"N/M"` | ✅ YES — emit `aspect-ratio:N/M` inline | Cover with `url` (background-image hero) |
| `style.dimensions.aspectRatio:"N/M"` | ❌ NO — save() applies via CSS class/var | Cover with `useFeaturedImage:true` (related-products card) |

The dominant failure mode: hand-writing `aspect-ratio:1` in the inline mirror when JSON path is `style.dimensions.aspectRatio:"1"`. Save() does NOT mirror that path inline. Triggers `Block validation failed for core/cover. Generated: style="border-radius:4px;margin-bottom:0", retrieved: style="border-radius:4px;aspect-ratio:1"`.

**Resolution paths:**

1. **JSON path `style.dimensions.aspectRatio` (recommended for related-products / template covers with `useFeaturedImage:true`):** keep in JSON; DROP `aspect-ratio:X` from inline. Mirror `border.radius`, `spacing.margin.*`, `spacing.padding.*`, and `minHeight`/`minHeightUnit` as before.

2. **Top-level `aspectRatio` attr (for cover with `url`):** keep in JSON AND emit `aspect-ratio:N/M` inline. Follows the v7.0 Exemplar 8 Variant C pattern.

**Canonical exemplar** (v7.11 — `useFeaturedImage:true` form):

```html
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top center","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-top-center" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
  …content…
</div></div>
<!-- /wp:cover -->
```

Note: `style.dimensions.aspectRatio:"3/4"` in JSON, NO `aspect-ratio:3/4` in inline. Same routing as HC#25 on `core/group`.

**Failure signature:** `Block validation failed for core/cover` with generated content missing `aspect-ratio:` in inline and retrieved content including it (or vice-versa for the top-level path).

Empirically verified Northwind paste-test (2026-05-13, second round) — single `core/cover` instance inside `surecart/product-list-related` Start-Basic template.

---

## Tier B — Fidelity (page renders, but visually drifts)

### B-13. Background-honesty (no hallucinated literal backgrounds)

For every wrapper or leaf element carrying `style.color.background` literal hex OR `backgroundColor` slug attr in your candidate output:

- [ ] The source JSX inline `style={{...}}` for that element OR a CSS class declared on it explicitly sets `background`, `backgroundColor`, or `background-image`. If neither source has a background declaration → **DO NOT emit one.** Default-to-transparent is correct.
- [ ] When merging two-layer source padding into one `core/group` (per `style-conversion.md` "Section padding canonical pattern"), only emit a background if at least one of the merged sources had one.

**Greppable check:** list every emitted bg in your candidate output, cross-reference each against source. Any unanchored bg → fail and remove.

**Anti-pattern (from v5.9 iPhone fixture, fixed in v6.0 Phase 2):** review-summary `<div>` in source has flex layout + gap + marginBottom + paddingBottom + borderBottom only — NO `background` declaration anywhere. Output emitted `style.color.background:"#f7f9fb"`. Wrong. The fix is removal, not value substitution.

**Why this matters:** the skill's own `style-conversion.md` worked examples used to feature `#f7f9fb` as the canonical literal-hex demo, and the skill pattern-matched it into output. Both the rule (this check) AND the doc scrub (Phase 2 of v6.0) are required to prevent recurrence.

### B-14. Card-grid chrome preserved

For every `core/group` that is a child of `core/column` and whose source design has `padding`, `border` (width/color/radius), or both:

- [ ] The card emits `style.spacing.padding` AND `style.border.{radius,width,color,style:"solid"}` attrs reflecting source values (literal px). **v7.6:** also emit `has-border-color` class on the wrapper (v7.6 reversal of v7.3 OMIT rule — see A-20).
- [ ] When the card uses literal `style.color.background` hex (Rule 0 carve-out): the wrapper inline `style=""` mirrors `background-color`, `border-color`, **`border-style:solid` (v7.3 reinstatement)**, `border-width`, `border-radius`, `padding-*` — in **save()'s exact emission order**: `border-color; border-style; border-width; border-radius; background-color; padding-top; padding-right; padding-bottom; padding-left`. (v5.6's "DO NOT include border-style:solid" was reversed in v7.3 — production patterns at `product-physical.example.md:42,87,94,99` all emit it.)
- [ ] **Wrapper class set** for cards with literal-bg + border (v7.6 canonical): `wp-block-group has-border-color has-background`. **Class order is pure set-equality** (`validation/index.js:348-356` `isEqualAttributesOfName.class` splits + compares set-membership only — NEVER order-enforced). Emit in this order as a stable convention only. Both classes required (v7.6 reversal: `has-border-color` IS required for literal-hex borders, contradicts v7.3).
- [ ] When the card uses slug `backgroundColor` (e.g., `surecart-white`): the wrapper has the slug class only, NO inline style. Padding/border attrs stay in the JSON only. Browser-rendered chrome depends on global-styles being enqueued.
- [ ] If the card combines `align:"wide"` or `align:"full"` with padding, the wrapper carries layout classes (`is-layout-constrained wp-block-group-is-layout-constrained` etc.) per A-9a.

**Greppable check:** for every `core/column > core/group` in the output, the source for that card group has at least one of {padding, border, borderRadius}. Output should mirror.

**Anti-pattern:** a card emits as `<!-- wp:group {"className":"... has-surecart-white-background-color has-background has-border-color"} -->` with no `style.spacing.padding`, no `style.border.*` JSON, and no inline mirror. On a classic theme without SureCart's global styles enqueued, that card has zero chrome — invisible card boundaries.

**Anti-pattern (v7.6 catch):** literal-bg + literal-hex border card emits WITHOUT `has-border-color` class on the wrapper. save() always emits it when JSON has `style.border.color`. Omission triggers `"Expected `class` of value `has-border-color has-background`, saw `has-background`"` recovery on every card. **REVERSES the v7.3 OMIT rule.** Empirically verified Aurora Lamp paste-test (2026-05-11).

**Historical anti-pattern (now obsolete, v6.0 Phase 4):** literal-bg card emits with `border-style:solid` in inline style — was thought to trigger recovery. The v7.3 production-pattern audit (`product-physical.example.md:42` and ~30 more) confirmed save() DOES emit `border-style:solid` when `style.border.style:"solid"` is in JSON. The v5.6 catch was a non-representative single paste test; the v7.6 canonical is to always emit `border-style:solid` for any border with width/color.

### B-15. Typography extras emitted (heading + paragraph)

For every `core/heading` and non-eyebrow `core/paragraph` whose source has `font-weight ≠ 400`, explicit `line-height`, `letter-spacing`, or `text-transform`:

- [ ] The corresponding `style.typography.{fontWeight,lineHeight,letterSpacing,textTransform}` attr is present in JSON. Values are stored as **strings** (`"700"`, `"1.6"`, `"-0.03em"`).
- [ ] Inline `style=""` on the inner element (`<h{level}>`, `<p>`) mirrors each typography prop with matching CSS declarations.
- [ ] Letter-spacing emitted in **em** units, not px (Gutenberg's serializer normalizes to em on round-trip; px values get converted and trigger validation mismatches).
- [ ] Hero `letterSpacing:"-0.03em"` (the iPhone fixture's `.sc-display-2`) round-trips byte-perfect — never substituted with `-0.02em`.

**Scope of Rule 7 (className-only fallback):** applies ONLY to `core/paragraph` blocks combining `align:"center"` AND eyebrow-like styling (uppercase + letter-spacing 0.04em + brand color + small font-size). All other heading/paragraph emissions follow normal style.typography emission (B-15 above).

**Anti-pattern:** an `.sc-lead` paragraph (source CSS: `font-family:body, font-size:18px, line-height:28px, color:gray-700`) emits as `<p class="has-surecart-gray-700-color has-text-color has-surecart-body-font-family">…</p>` with NO `lineHeight` attr or inline. On any theme that doesn't define `--wp--preset--font-size--surecart-lead`, the line-height defaults to 1.5 instead of design's 28/18 = 1.555… The reader-comfort tuning is lost.

### B-16. Buy-button typography directly + media-text gap preserved

**Buy-button typography:** `surecart/product-buy-button` block.json declares `supports.typography` WITHOUT `__experimentalSkipSerialization` (only color/spacing/border skip). Therefore:

- [ ] When the source design has font-family/font-weight/font-size/letter-spacing/text-transform on the button label, set those directly on `surecart/product-buy-button`: e.g., `{"fontFamily":"surecart-display","style":{"typography":{"fontSize":"16px","fontWeight":"700"}}}`. The class lands on the wrapper (`__experimentalSelector: ".wp-block-button .wp-block-button__link"`) and CSS cascades to the inner `<a>` via Gutenberg's standard pattern.
- [ ] Do NOT wrap the buy-button in a `core/group` to apply typography via parent. The "group-wrap escape hatch" was a misreading of the block.json — typography is unrestricted.
- [ ] Do NOT emit color/spacing/border attrs on `surecart/product-buy-button` wrapper as inline `style=""` or `class="has-*"` (those three trees ARE skipSerialization). The block's render handles them server-side.

**Media-text gap:** `core/media-text` whose source has `gap: N` between media and text:

- [ ] Emit `style.spacing.blockGap:"Npx"` on the `core/media-text` wrapper. The `grid-column-gap` is derived from blockGap by Gutenberg's media-text save().

**Container child blocks (price-chooser, quantity, buy-buttons):** these are atomic at block.json level (no `template`/`allowedBlocks`). Their inner blocks (`surecart/price-name`, `surecart/price-amount`, `surecart/price-interval`, etc.) are emitted as siblings inside the parent comment markers, and each carries its OWN `fontFamily` slug attr. Do not assume cascading from the parent — the slug class on each child is what serves the font.

**Anti-pattern:** buy-button emitted as `<!-- wp:surecart/product-buy-button {"text":"Buy Now"} /-->` with no typography attrs, with no styled `core/group` wrapping it either. Result: button label renders in the host theme's default font (Manrope on a Twenty Twenty-Four/clone, system font on classic themes), not Geist.

### B-19. Section pattern parity (layout, contentSize, background match source)

For every top-level section in the output, verify that its **structural shape matches the source design**:

- [ ] **Layout type matches.** Source uses `display:grid; gridTemplateColumns:"repeat(4,1fr)"` → output is `core/columns` with 4 `core/column` siblings (NOT a single column with 4 stacked items). Source uses `display:flex; flexDirection:row` → output's parent has `layout.type:"flex"` (or is `core/columns` for hero-grid patterns). Source uses single-column flow → output is constrained `core/group`.
- [ ] **`layout.contentSize` matches source `maxWidth`** byte-perfect (NEVER round). Source `maxWidth: 1240` → output `"contentSize":"1240px"`. Source `maxWidth: 880` → output `"contentSize":"880px"`. If section's source has no maxWidth, omit `contentSize` and rely on parent.
- [ ] **Section background matches source.** If source has explicit `background: var(--bg-1)` → output has `backgroundColor:"surecart-white"` slug class (per the `--bg-1` → `surecart-white` substitution mapping documented in `reference/style-conversion.md` Table A). If source has literal hex → output has `style.color.background` literal hex. Per B-13, never invent a background where source has none.
- [ ] **Section vertical padding matches source.** Source `padding: "96px 0"` (vertical only) merged with inner wrapper `padding: "0 32px"` (horizontal gutter) → output emits `style.spacing.padding:{top:"96px",right:"32px",bottom:"96px",left:"32px"}` per the canonical two-layer merge pattern in style-conversion.md.
- [ ] **Section count matches source.** Every named JSX section component appears in the output as a top-level `core/group` child of `surecart/product-page` (or with reason logged in `dropped_features`).
- [ ] **Section ORDER matches source.** Don't reorder: hero before highlights before descriptions before specs before FAQ before related before final-CTA — the order in `product-page.jsx` is canonical.

**Greppable check (anti-patterns):**

```
section with no layout.contentSize when source maxWidth is set → ❌
section's blockGap doesn't match source's column gap → ❌
section bg slug doesn't match source --bg-* token → ❌
4-col grid in source emitted as single core/group with 4 stacked children → ❌
section emitted in different order than source product-page.jsx render() → ❌
```

**Anti-pattern (fixture deviation):**

```html
<!-- ❌ source: maxWidth: 1240 → emit literal -->
<!-- wp:group {"layout":{"type":"constrained","contentSize":"1200px"}} -->  <!-- snapped to 1200, wrong -->

<!-- ❌ source: 4-col grid → emitted as single column with stacked items -->
<!-- wp:group --> 
  <!-- wp:group --> Card 1 <!-- /wp:group -->
  <!-- wp:group --> Card 2 <!-- /wp:group -->  <!-- should be inside core/columns -->
```

**Why this rule exists:** Section-level fidelity drift produces visible "the design doesn't match the editor" complaints from merchants even when individual blocks are fine. B-18 covers within-block fidelity; B-19 covers between-section structural parity.

### B-18. Explicit-per-block visual contract (every property, every block, even 0)

For **every block** in the output (regardless of size or role), check that every visual property the design specifies is **explicitly declared** in that block's attrs — including 0 values.

**Property categories to verify (when design has them):**

- [ ] **Typography**: `style.typography.{fontSize, fontWeight, lineHeight, letterSpacing, textTransform, textDecoration, fontStyle}` + `fontFamily` slug — present on every text-rendering leaf (heading, paragraph, list, button, surecart text leaves like price-name/price-amount/price-interval/etc.)
- [ ] **Spacing**: `style.spacing.{padding, margin, blockGap}` — present on every block where design has spacing, including `"0px"` when design has 0
- [ ] **Dimensions**: `width`, `height`, `aspectRatio`, `layout.contentSize`, `layout.wideSize` — present when design fixes them
- [ ] **Color (text)**: D7 dual-emit (slug + literal hex inline) on leaves; slug-only on paired wrappers
- [ ] **Color (background)**: D7 dual-emit on leaves; slug-only on wrappers (Rule 0). Explicit when source has bg (B-13).
- [ ] **Border**: `style.border.{radius, width, color}` — emit even when radius is `"0px"`. NEVER include `style.border.style:"solid"` (Rule 4)
- [ ] **Layout**: `layout.{type, orientation, flexWrap, justifyContent}` + `verticalAlignment` — present on container blocks

**Container blocks own LAYOUT only (typography/color/font goes on each child):**

- [ ] `surecart/product-price-chooser` has NO `style.typography` attrs. Each of its 6 children (`price-name`, `price-amount`, `price-interval`, `price-trial`, `price-setup-fee`, `price-scratch-amount`) carries its own full typography + color + fontFamily.
- [ ] `surecart/product-quantity` has NO typography attrs at the wrapper level (typography on inner control children if visible).
- [ ] `surecart/product-buy-buttons` has NO typography attrs (typography on each `surecart/product-buy-button` child per B-16).
- [ ] `surecart/product-review-summary` has NO typography (typography on inner `product-review-average-rating-value`).
- [ ] `core/group` and `core/columns` paired wrappers have NO typography attrs (typography on each text-leaf child).

**Zero-value mandate:**

- [ ] Wherever the design has `padding-top: 0`, the block emits `"padding":{"top":"0px"}` — NOT omitted.
- [ ] Wherever design has `border-radius: 0`, emit `"border":{"radius":"0px"}` — NOT omitted.
- [ ] Wherever design has `margin-bottom: 0`, emit `"margin":{"bottom":"0px"}` — NOT omitted.
- [ ] Same for `blockGap: 0`, `letterSpacing: "0"`, `lineHeight: "0"` — explicit zero, never inherited.

**Greppable check (anti-patterns to flag):**

```
"surecart/product-price-chooser" .* "style":{"typography":  → ❌ (B-18 / B-16)
"surecart/product-buy-buttons" .* "style":{"typography":   → ❌ (B-16)
<!-- wp:surecart/price-name /-->                            → ❌ (no typography on price-name leaf)
<!-- wp:surecart/price-amount /-->                          → ❌ (no typography on price-amount leaf)
<!-- wp:surecart/price-interval /-->                        → ❌
<!-- wp:surecart/price-trial /-->                           → ❌
<!-- wp:surecart/price-setup-fee /-->                       → ❌
<!-- wp:surecart/price-scratch-amount /-->                  → ❌
core/heading without style.typography (when design has it)   → ❌ (B-15 + B-18)
core/paragraph without fontWeight (when design body has 400) → ❌
className-only paragraph/heading (legacy Rule 7 over-reach)  → ❌ (allowed ONLY for centered-eyebrow paragraphs per Rule 7 narrow scope; all other text blocks emit explicit style.typography per B-15/B-18)
```

**Anti-pattern (price-chooser inheritance):**

```html
<!-- ❌ WRONG -->
<!-- wp:surecart/product-price-chooser {"style":{"typography":{"fontSize":"44px","fontWeight":"700"}}} -->
  <!-- wp:surecart/price-name /-->        <!-- inherits 44px — design wanted 16px -->
  <!-- wp:surecart/price-amount /-->      <!-- inherits 44px — accidentally OK -->
  <!-- wp:surecart/price-interval /-->    <!-- inherits 44px — design wanted 16px -->

<!-- ✅ RIGHT — see style-conversion.md Explicit-per-block visual contract -->
```

**Why this rule exists:** the v6.0 fixture had typography on the price-chooser parent which cascaded to all children. price-name, price-interval, price-trial, price-setup-fee all rendered at 44px instead of their intended 14-16px sizes. The fix is per-child explicit emission. This pattern recurs anywhere a Gutenberg parent block has visible-text children — solve it once with B-18, prevent it everywhere.

### B-17. Classic-theme color resolvability (D7 dual-emit on leaf elements)

For every **leaf element** carrying a `textColor` slug attr OR `backgroundColor` slug attr — leaf elements are: `core/heading`, `core/paragraph`, `core/button`, and `surecart/*` text/CTA blocks (`product-title`, `product-description`, `product-buy-button`, `product-price-chooser`, `product-quantity`, `product-variant-pills`, `cart-count`):

- [ ] The leaf emits BOTH the slug attr (e.g., `textColor:"surecart-brand"`, class `has-surecart-brand-color has-text-color`) AND the literal hex inline (`style.color.text:"#01824C"` in JSON, `color:#01824C` in inline `style=""`). Hex values resolve from `reference/token-aliases.json` `color.{var}.hex`.
- [ ] Slug class wins on FSE themes / classic themes that enqueue `wp-block-library` global styles. Inline literal wins on classic themes that don't. Both render the same color when both layers are active — no conflict.
- [ ] **Paired wrapper blocks are EXCLUDED from D7 dual-emit.** Per Rule 0 / v5.7 paste-safety: `core/group`, `core/columns`, `core/column`, `core/cover`, `core/media-text`, `core/buttons`, `core/list`, `core/quote`, `core/details` carry slug-only when the slug-bg/-textColor variant is used. Never inline-mirror color slugs on these wrappers.

**Why D7 leaf-only:** the v5.7 paste-corruption diagnostic showed inline `style=""` on paired-block wrappers triggers recovery cascade because save() doesn't predict it. Leaf elements (heading inner `<h{level}>`, paragraph inner `<p>`, atomic surecart text blocks) are NOT paired wrappers — save() expects inline style on the inner element, so dual-emit is round-trip safe.

**Greppable check:** for every leaf carrying a `textColor`/`backgroundColor` slug, JSON contains `style.color.{text|background}` literal AND inner element's `style=""` includes the matching CSS declaration.

**Anti-pattern:** `<!-- wp:heading {"textColor":"surecart-gray-900","fontFamily":"surecart-display"} -->` `<h2 class="wp-block-heading has-surecart-gray-900-color has-text-color has-surecart-display-font-family">Make iPhone 17 Pro yours today.</h2>` — slug-only, no inline literal. On a classic theme without SureCart's theme partial loaded, the heading renders in the active theme's default text color (rgba 17,17,17 on Bundle Store), not the design's `#111827`. Same shape — but proves the issue empirically: if the partial DID load, both would be 17,17,17, and dual-emit costs nothing.

### B-29. Font-family literal fallback alongside slug (v7.14, backfilled v7.15 — mirrors HC#35)

For every text-bearing block (`core/heading`, `core/paragraph`, `core/details` summary, `core/button` link, `surecart/product-title`, `surecart/product-buy-button`, all `surecart/price-*` leaves):

- [ ] When emitting `fontFamily:"surecart-display"` (or any preset slug), ALSO emit `style.typography.fontFamily:"<face>, <generic-fallback>"` as a literal short-stack.
- [ ] The literal MUST be ≤30 chars (per HC#16 — primary face + ONE generic fallback). Example: `"Fraunces, serif"` (16 chars) ✓; `"Cormorant Garamond, Times New Roman, serif"` (43 chars) ✗ — pick a shorter form.
- [ ] The literal MUST appear in BOTH JSON `style.typography.fontFamily` AND in the inline `style="...;font-family:...;..."` mirror (per B-27 set-equality).
- [ ] If the design CSS declares `--font-display: 'Fraunces', serif;`, drop the inner quotes for JSON cleanliness — emit `Fraunces, serif`, NOT `'Fraunces', serif`.

**Why:** merchant theme.json often doesn't register the design's intended font face. Slug-only emission falls back to the active theme's default sans (typically Manrope). The literal provides the visual fallback when the slug isn't registered.

**Failure signature** (visual, not paste-recovery): rendered heading uses theme default font (e.g., Manrope) instead of design's intended face (e.g., Fraunces). No console error; manifests as font-family drift in browser inspector.

**Anti-pattern:** `<!-- wp:heading {"fontFamily":"surecart-display"} -->` (slug-only). On a theme that doesn't map `surecart-display` to Fraunces, the heading renders in the active sans. **Fix:** add `style.typography.fontFamily:"Fraunces, serif"` AND mirror in inline `style=""`.

Empirically verified via Kobachi Ramen Bowl deep visual diff (2026-05-27).

### B-30. Featured-image cover placeholder fallback (v7.14, backfilled v7.15 — mirrors HC#36)

For every `core/cover {useFeaturedImage:true}` block where the design has a custom SVG/illustration as the gallery / hero / card image:

- [ ] Emit ONE `core/html` block containing the design's illustration SVG INSIDE the cover's `<div class="wp-block-cover__inner-container">`.
- [ ] The SVG markup is verbatim from the design's source files (app.jsx, sections, etc.).
- [ ] The SVG `<div>` wrapper centers the illustration via flex (matching the design's intent: `display:flex;align-items:center;justify-content:center`).
- [ ] Log a `dropped_features.type:"hero_illustration_placeholder"` entry with `merchant_action:"upload a real product photo to surecart/product → featured image to replace the placeholder illustration"`.

**Why:** `useFeaturedImage:true` renders empty when the product has no featured image yet. The design's intended illustration disappears pre-image-upload.

**When NOT to apply:** purely decorative covers (CTA bands, colored hero backgrounds with overlaid text) — the bg color is fine on its own. Apply only when the design's gallery/hero area HAS a custom illustration that won't show without one.

**Same pattern applies to** related-products `surecart/product-template` inner `core/cover` blocks when the design has card illustrations.

**Failure mode** (visual, not paste-recovery): hero gallery renders as empty colored box; merchant doesn't know to upload an image. Worst-case: merchant publishes the page thinking it's working.

Empirically verified via Kobachi Ramen Bowl deep visual diff (2026-05-27).

### A-35. Server-rendered block style-attr bypass — apply wrap-and-target ONLY when no block-style variation exists (v7.14, AMENDED v7.15 — mirrors HC#37/HC#38)

Some `surecart/*` blocks are server-rendered with internal Stencil templates that ignore JSON style attrs. Affected blocks (incomplete list — audit per emission):

- `surecart/product-quantity` (border/color/spacing on JSON does NOT reach rendered stepper)
- `surecart/product-review-average-rating-value` (typography ignored)
- `surecart/product-buy-button` (color/border per HC#6 skipSerialization, but typography respected)
- `surecart/cart-button` family, `surecart/sticky-purchase` (server templates own chrome)

For every emission of an affected block:

- [ ] **FIRST: check `wp.data.select('core/blocks').getBlockStyles('surecart/<name>')`** for a registered variation matching the design's intended chrome. If one exists, apply `className:"is-style-{variant}"` (A-37 covers this enforcement).
- [ ] **Only if no variation matches:** wrap the block in a styled `core/group` with stable `className` carrying the design's chrome (background, border, padding, radius). Theme CSS keyed off the className overrides the inner Stencil chrome.
- [ ] Log a `server_rendered_style_attrs_bypassed[]` entry in the drift report naming the block + the chrome attrs that the outer wrapper carries.

**Failure mode** (visual): block renders with its default Stencil chrome (square box, default font), ignoring the JSON style attrs. **Fix path:** A-37 block-style variation FIRST, then wrap-and-target if no variation fits.

**Affected blocks needing attention per emission:** `surecart/product-quantity` (use A-37 with `is-style-orbit`/`pebble`/`borderless`), `surecart/product-review-average-rating-value` (typography wrap-and-target — no variations cover font-size), `surecart/sticky-purchase` (no variations; wrap-and-target).

Empirically verified via Kobachi R1 v2 paste-test (2026-05-27).

### A-36. Featured-image cover SVG placeholder is the SAME rule as B-30 — Tier A reclassification REJECTED

A-36 number reserved. Doctrine lives at B-30 (Tier B fidelity, NOT blocking). The empty-cover failure mode is visual fidelity, not paste-recovery — keep at Tier B.

### A-37. ALWAYS check registered block-style variations FIRST (v7.15 — mirrors HC#38)

Before emitting any `surecart/*` block with non-default visual chrome:

- [ ] Mentally `wp.data.select('core/blocks').getBlockStyles('surecart/<name>')` (or reference `surecart-blocks.md § Registered block-style variations`).
- [ ] If any variation matches the design's intended chrome, apply via `className:"is-style-{variant}"`.
- [ ] If a variation is `isDefault:true` and matches the design's chrome, **OMIT `className` entirely** (the default is implicit; emitting it triggers a round-trip rewrite).
- [ ] If no variation matches, fall through to A-35 wrap-and-target.

**Known variations (v7.15 catalog):**

| Block | Variations | `isDefault` |
|---|---|---|
| `surecart/product-quantity` | default, borderless, orbit, pebble | default |
| `surecart/product-buy-button` | fill, outline | fill |
| `surecart/product-review-add-button` | fill, outline | fill |
| `surecart/product-review-form-submit-button` | fill, outline | fill |
| `surecart/product-review-average-rating-value` | none, parentheses, slash | none |
| `surecart/product-review-total-rating` | default, **plus-sign** | **plus-sign** |
| `surecart/product-quick-view-button` | default, show-on-hover | default |
| `surecart/product-buy-button` (block-level variations) | cart, buy | cart |
| `surecart/product-media` (block-level variations) | slider, gallery | slider |

**Failure modes:**
- Emitting `className:"is-style-default"` for a block where `default` IS the registered isDefault → triggers round-trip rewrite, recovery on save (block's `<div class="is-style-default">` becomes `<div>` after re-serialization).
- Skipping the variation when one matches → falls back to default chrome, which doesn't match the design.
- Inventing a variation name not in the catalog → silent paste failure or className collision.

**Hidden_label attr** on `surecart/product-quantity`: when the design has no visible "Quantity" label above the stepper, emit `hidden_label:true`. Always keep the `label:"Quantity"` attr set (required for a11y).

Empirically verified via Kobachi R1 v2 user-fix paste-test (2026-05-27).

### A-38. Per-block sub-attribute defaults — emit only when overriding (v7.15)

Many `surecart/*` blocks have non-style attrs with non-trivial defaults documented in `surecart-blocks.md § Special block attrs`:

- [ ] For each block emitted, check `reference/surecart-blocks.md § Special block attrs` for defaults.
- [ ] If your emission uses the documented default, OMIT the attr from JSON (cleaner, smaller markup).
- [ ] If your emission overrides the default, emit explicitly.

**Defaults that commonly trip up emission:**

- `surecart/product-quantity` `label:"Quantity"` (default) — keep when `hidden_label:true` for a11y; omit otherwise.
- `surecart/product-buy-button` `add_to_cart` — block-level variation `cart` sets it to `true`; variation `buy` sets it to `false`. **Block.json default is `false`** — the variation overrides.
- `surecart/product-buy-button` `text` — required per HC#18; no usable default.
- `surecart/product-quick-view-button` `icon:"plus"`, `icon_position:"before"`, `label:"Add"`, `direct_add_to_cart:true` — emit only when overriding.

---

### A-39. `surecart/sticky-purchase` content-overflow guard (v7.16)

For every `surecart/sticky-purchase` emit, verify:

- [ ] Inner direct child is a SINGLE `core/group` with `layout.type:"flex",flexWrap:"nowrap",justifyContent:"space-between"`. Not 2+ sibling groups directly under the sticky-purchase wrapper.
- [ ] If a `surecart/product-media` is inside, cap its width via `style.dimensions.width:"44px"` AND `aspect-ratio:"1"`.
- [ ] Buy-button text ≤ ~18 chars (e.g., `"Add to cart"`, not `"Subscribe & Save — $49/month"`). Long labels wrap and explode bar height.

**Why:** the wrapper ships `position:fixed` but the inner row CAN overflow, which displaces the bar's anchor and pushes it off-screen via auto `top:Npx`. Empirically surfaced via R2 Atlas Greens (2026-05-27).

Maps to HC#39.

---

### A-40. `useFeaturedImage:false` on cover thumbnail strips (v7.16)

For every `core/cover` emit:

- [ ] If the cover is a THUMBNAIL (small placeholder square in a thumb strip), `useFeaturedImage:false`. Per-thumb `color.background` will then render correctly as a solid swatch.
- [ ] If the cover is the MAIN hero gallery image, `useFeaturedImage:true` is correct.

**Why:** `useFeaturedImage:true` ALWAYS wins over per-cover `color.background`. All thumbs render the same product image regardless of the design's 4 distinct swatch colors. Empirically surfaced via R2 Atlas Greens (2026-05-27).

**Alternative:** for pure decorative swatch strips, use `core/group` with `color.background:"<hex>"` + `aspect-ratio:1` instead of `core/cover`. Cleaner, no semantic-image baggage.

Maps to HC#40.

---

### A-41. `fontFamily` attr + literal `font-family` inline style — pick one (v7.16)

For every block emitting typography:

- [ ] If `style.typography.fontFamily:"<literal>"` (e.g., `"EB Garamond, serif"`) is set, do NOT also emit `fontFamily:"surecart-display"` attr.
- [ ] If `fontFamily:"surecart-display"` (or `-body`/`-mono`) is set, do NOT also emit `style.typography.fontFamily`.
- [ ] Pick based on whether the design's typeface is a known theme preset (Path A: theme-named) or a literal Google Font outside the theme (Path B: literal).

**Why:** the theme's `.has-surecart-display-font-family { font-family: var(--wp--preset--font-family--surecart-display); }` class loads LATE and can override the inline literal at runtime if the CSS var resolves to a different family. Mixing the two paths produces inconsistent typography (different headings rendering in different fonts depending on cascade order). Empirically surfaced via R2 Atlas Greens (2026-05-27) — hero h1 sans, stat-counter h3 serif, same skill emission, different runtime resolution.

Maps to HC#41.

---

### A-42. `core/cover` `isDark` MUST match bg luminance (v7.17)

For every `core/cover` emit with `color.background`:

- [ ] OMIT `isDark` from JSON entirely. Let `save()` compute from bg luminance.
- [ ] Never set `isDark:false` on a dark-bg cover (e.g. `#000`–`#444`, luminance < 50%) — `save()` will emit `is-light` AND `is-dark`, but stored HTML has only `is-dark`. Class-set mismatch → Block Recovery.
- [ ] If `useFeaturedImage:true` is set AND the post has no featured image AND `color.background` is set: drop `useFeaturedImage:true` (or set `false`). The placeholder's luminance computation can conflict with the bg-color-derived class.

**Greppable check:** `grep -E '"isDark":false.*"background":"#[0-3]'` — any match suggests a dark bg with explicit `isDark:false` (likely conflict).

Maps to HC#42.

---

### A-43. `surecart/product-review-breakdown` is the correct name — NOT `-average-rating-breakdown` (v7.17)

For every review-summary block emit:

- [ ] If emitting a star-rating histogram, the block is `surecart/product-review-breakdown` (short-form).
- [ ] Do NOT emit `surecart/product-review-average-rating-breakdown` — does not exist; editor shows "Your site does not support this block."
- [ ] Confirm against `reference/surecart-blocks.md` and `reference/full-inventory.json` before emit.

**Sibling reference (only the breakdown drops the `average-rating` prefix):**
- `surecart/product-review-breakdown` ✅
- `surecart/product-review-average-rating-stars` ✅
- `surecart/product-review-average-rating-value` ✅
- `surecart/product-review-total-rating` ✅

**Greppable check:** `grep -E "surecart/product-review-average-rating-breakdown"` — any match = hallucination, rewrite.

Maps to HC#43.

---

### A-44. `surecart/sticky-purchase` canonical inner blocks (v7.17)

For every `surecart/sticky-purchase` emit:

- [ ] Image block: `surecart/product-selected-variant-image` ✅ (NEVER `surecart/product-media` — gallery slider inappropriate for 44px sticky thumb).
- [ ] Title block: `surecart/product-title` with `level:4`.
- [ ] Selected variant displayed: `surecart/product-selected-variant`.
- [ ] Price stack: `surecart/product-selected-price-scratch-amount` + `surecart/product-selected-price-amount` + `surecart/product-selected-price-interval` (use whichever apply).
- [ ] Buy buttons: SINGLE `surecart/product-buy-button` (text "Add" or "Add to cart") — no Buy Now pair.
- [ ] Wrapper carries `layout.type:"flex",orientation:"horizontal",flexWrap:"nowrap",wideSize:"full",justifyContent:"space-between"` directly. NO inner constrained wrapper.
- [ ] Two-zone layout: LEFT info group `className:"is-vertically-aligned-center",style.layout.selfStretch:"fit"` + RIGHT buy-button group `selfStretch:"fill"`.

**Greppable check:** `grep -B5 "surecart/product-media" <markup>` — verify no `product-media` appears inside `surecart/sticky-purchase` open/close tags.

See `examples/patterns/sticky-purchase.example.md` for the byte-perfect canonical reference.

Maps to HC#44.

---

### A-45. NO descriptive HTML comments in block content areas (v7.18, supersedes A-21 with grep-check)

For every emit:

- [ ] **Pre-emit grep sweep:** `grep -nE '^<!-- [^/w]' <output.html>` returns ZERO matches.
- [ ] **Block delimiters ONLY:** the only `<!-- ... -->` allowed at the top of lines are `<!-- wp:* {...} -->` and `<!-- /wp:* -->`.
- [ ] **Comments in `core/html`/`core/code`/`core/preformatted` content** are OK — those block content areas are opaque to Gutenberg's validator.
- [ ] **NO section labels, tier labels, card-row labels, anything annotative** in the block tree.

**Forbidden patterns (each = recovery prompt):**
- `<!-- Section 1: Sticky header -->`
- `<!-- Starter tier -->`
- `<!-- Pro tier (highlighted) -->`
- `<!-- Card 3 / lifestyle -->`
- Any HTML comment NOT starting with `<!-- wp:` or `<!-- /wp:`.

**Strip command (last-resort if comments slipped in):**
```bash
sed -i.bak -E '/^<!-- (Section|Starter|Pro|Team|Card|Step|Tier|Row|Col|Sidebar|Footer)/d' <file>
```

Maps to HC#45.

---

### A-46. `surecart/product-variant-pills` emits ONCE — never per-axis (v7.18.2)

For every emit with multiple variant axes (color + size, material + size, etc.):

- [ ] Exactly ONE `<!-- wp:surecart/product-variant-pills -->` block in the markup.
- [ ] Pre-emit grep: `grep -c '^<!-- wp:surecart/product-variant-pills ' <file>` returns 1.
- [ ] Inside it, ONE `<!-- wp:surecart/product-variant-pill /-->` self-closed template.
- [ ] NO design-side per-axis labels emitted as sibling `<!-- wp:group -->`+`<!-- wp:paragraph -->` ("Color" / "Size" headers) wrapping each pills block — the server adds axis labels automatically.

**Anti-pattern:** two `product-variant-pills` blocks (one for color, one for size). Doubles the rendered variants because each instance iterates ALL the product's axes.

Maps to HC#46.

---

### A-47. `surecart/product-variant-pill` border attrs scoped to radius only (v7.18.2)

For every `surecart/product-variant-pill`:

- [ ] `style.border.radius` is OK (`"9999px"` for pill shape).
- [ ] `style.border.width` / `style.border.color` / `style.border.style` ONLY when design explicitly shows per-chip outlined pills.
- [ ] Default to `border:{radius:"9999px"}` (radius-only) — adds no visible per-chip border.
- [ ] Selected-pill chrome uses `highlight_*` attrs (`highlight_text`/`highlight_background`/`highlight_border`), NOT base border.

**Why:** per-chip 1px borders on tightly-packed pills LOOK like a wrapper outline around the pill group. Users flag this as "the variants group has a border" — but it's actually N individual borders that visually merge.

Maps to HC#47.

---

### A-48. File-output exclusivity — no chat-leak when file-write fires (v7.18.3 — PR2)

Walk this check **only when** the file-write branch fires (markup ≥ 5,120 bytes per Step 4 threshold). When it fires:

- [ ] Chat response contains ZERO fenced code blocks of ANY language (no ```html, ```text, ```xml, ```markdown, ```bash, or bare ``` fences).
- [ ] Chat response contains ZERO indented code blocks.
- [ ] Chat response contains ZERO lines beginning with `<!-- wp:` AND ZERO text containing `wp:surecart/` or `wp:core/`.
- [ ] Drift report JSON whitelist enforced — no field value contains `<!-- wp:` literal markup.
- [ ] Chat structure is EXACTLY: (1) self-validation line, (2) 1–2 sentence summary, (3) drift report JSON, (4) paste-instructions block.
- [ ] If merchant follow-up requests an excerpt / partial / "show me line N" / "just the hero section" — refusal + pointer to file path. The file is the authoritative source.

**Cross-turn binding:** once a response invoked file-write, subsequent responses in the same thread obey this rule until the merchant reports successful paste.

**Pre-respond greppable check** (run mentally before sending the response):
```
grep -nE '^[[:space:]]*<!-- wp:|^[[:space:]]*```|wp:surecart/|wp:core/' <draft-response>
```
MUST return zero matches.

Maps to HC#48.

---

### A-49. JSON string values — no unescaped `<`, `>`, `&`, `\"`, `--` (v7.18.3 — PR2)

For every block-comment JSON in the emitted markup:

- [ ] No raw `<`, `>`, `&`, `\"`, `--` inside `style.*` JSON values (pure-data attrs).
- [ ] Rendered-text attrs (`content`, `summary`, `text`, `label`, `placeholder`, `ariaLabel`, `metadata.name`) emit already-escaped HTML entities when the source has special chars: `&amp;`, `&lt;`, `&gt;`, `&quot;`, `&#xNN;`. Both raw chars and escaped entities are detectable; only RAW raw chars violate.
- [ ] No literal U+2028, U+2029, U+0085, U+FEFF anywhere (some merchant editors silently strip them).

**Pre-emit greppable check** (refined to exclude already-escaped HTML entities — `&amp;` / `&lt;` etc. are correct):
```
grep -oE '"[^"\\]*(\\.[^"\\]*)*"' <output> | \
  grep -E '(<(?!!--|/)|>(?<!--)|&(?!(amp|lt|gt|quot|apos|#[0-9]+|#x[0-9a-fA-F]+);)|--(?![->]))'
```
Zero matches expected.

**Why:** WP's `serializeAttributes` (`@wordpress/blocks/build/api/serializer.js:244-257`) Unicode-escapes 5 character classes (`--`, `<`, `>`, `&`, `\"`) on save() round-trip. First-paste works because the parser is lenient. But the editor save re-emits with the escapes; validateBlock then sees a stored↔generated mismatch on the next edit → recovery cascade.

Maps to HC#49.

---

### B-20. L2 dependency-block presence (v7.1)

For every `dropped_features` entry with `type:"raw_html_interactive"` and `level:"L2"`:

- [ ] The `namespace` field is one of the 7 documented `surecart/*` namespaces (no inventing).
- [ ] The `dependency_block` field is filled in with a SureCart block name from the namespace's allowlist row in `reference/custom-html-fallback.md` (e.g., `surecart/cart-icon` for `surecart/cart`; `surecart/product-media` for `surecart/lightbox`).
- [ ] The named `dependency_block` actually appears in the emitted markup. Search for the block name (`<!-- wp:surecart/cart-icon` or similar). Missing → fail; the L2 directives won't hydrate at runtime.
- [ ] All `data-wp-on--{event}="actions.X"` references match an action owned by the namespace's store per the allowlist table. No invented action names.
- [ ] If any L2 emit is present, SKILL.md's `unfiltered_html` capability warning is appended to the merchant's copy-paste instructions.

**Greppable:** for each `level:"L2"` entry in drift, `grep` the markup for the `dependency_block` value; expect ≥ 1 match.

---

## Tier C — Screenshot-only (v7.0 — active for `input_type:"screenshot"`)

These items gate emission ONLY when the input is a screenshot/image (Step 1.B in SKILL.md). For `input_type:"zip" | "html_css" | "live_url"`, this tier is reported as `skipped`.

### C-17. Resolution gate

- [ ] Image long edge ≥ 1200px. Smaller images yield unreliable color/typography extraction.
- [ ] If below the gate, do NOT emit. Ask the merchant for a higher-resolution export.

### C-18. Confidence dimension on every category

- [ ] Drift report includes a `confidence` object with five keys: `typography`, `color`, `spacing`, `layout`, `assets`. Each scored `"high"` / `"medium"` / `"low"`.
- [ ] Every dimension scored `"low"` is listed in the drift report's `merchant_verify[]` array with a specific verification step.
- [ ] No dimension is omitted. A missing dimension is a Tier C failure.

Example:
```json
"confidence": {
  "typography": "medium",
  "color": "low",
  "spacing": "low",
  "layout": "high",
  "assets": "medium"
},
"merchant_verify": [
  {"dimension": "color", "action": "Verify the hex values in your design system match what we extracted; we couldn't snap to a SureCart slug for 6 colors."},
  {"dimension": "spacing", "action": "Open each section in the WP block editor and verify padding/margin values match your design — we estimated visually."}
]
```

### C-19. No literal hex without snap attempt

- [ ] For every literal hex emitted (`style.color.text`, `style.color.background`, `style.border.color`), there is a `color_snap_misses[]` entry naming the source hex and explaining why no SureCart slug matched within ΔE 6.
- [ ] Empty `color_snap_misses[]` is suspicious for a screenshot input — verify you actually attempted the snap.

### C-20. Section-count plausibility

- [ ] Detected sections ≥ 2. A single-band screenshot rarely represents a full page.
- [ ] If only 1 section detected, ask the merchant: *"Is this a full-page screenshot or a single section? If single, you can paste it as-is and I'll wrap it in `surecart/product-page`. If full-page, please re-attach a screenshot showing the entire page so I don't miss content."*

### C-21. Family-only typography (no exact-font claims)

- [ ] Typography emission uses SureCart slugs (`surecart-display` / `surecart-body` / `surecart-mono`) when source font is unidentified. Do NOT invent specific font names like "DM Sans" or "Poppins" from screenshot evidence — fonts cannot be reliably identified from images.
- [ ] If the merchant explicitly told us the font family ("we use DM Sans"), use that family literal in `fontFamily` AND log under `dropped_features` with `type:"custom-font"` so the merchant knows to register it in theme.json.

### C-22. Multi-page completeness (v7.11)

For screenshot inputs (Mode B), check for truncation before emitting:

- [ ] **Footer presence check.** Scan the bottom of the screenshot for a footer-archetype band (dark/colored band with brand mark, link columns, copyright). If the screenshot has aspect-ratio ≥ 3:1 (tall page) AND no footer is detected, treat as likely-multi-page.
- [ ] **Sibling source-file scan.** When multi-page is suspected, look for sibling files in the same input directory: `*.source.pdf`, `*.psd`, `*.ai`, `*.fig`, or additional `.png`/`.jpg` files with sequential naming (`page-1.png`, `page-2.png`). Read them for additional sections.
- [ ] **Merchant prompt when no source files.** If no sibling source materials exist and footer is absent, explicitly ask the merchant: *"This screenshot looks like page 1 of a multi-page design — I don't see a footer band. Are there additional pages? Attach them as additional screenshots or a single multi-page PDF."*
- [ ] **Common omitted-on-page-1 sections** (audit explicitly): footer, related products / "Pairs well with" / "You might also like", newsletter signup, secondary CTA band, full-page testimonials.

**Failure mode:** silent truncation. The skill emits a complete-looking product page that's actually missing standard product-page chrome (footer, related grid). No paste-validation error fires — the merchant only notices when they scroll to the bottom and see the page just ends. Worse than a paste-recovery error because the failure is invisible until visual inspection.

**Reference:** the Northwind Pour-Over Kettle fixture (14) demonstrates this pattern. The PNG is page 1 only; the source PDF (`northwind-kettle.source.pdf`) has page 2 with related products + footer. Initial v7.10 conversion missed both sections; v7.11 added them after the merchant flagged the missing footer.

Empirically verified Northwind paste-test (2026-05-13).

---

## Quick anti-pattern grep

Before emitting, mentally `grep` for:

```
surecart/variant-picker     → ❌ rewrite (A-2)
surecart/quantity           → ❌ rewrite (A-2)
surecart/add-to-cart-button → ❌ rewrite (A-2)
surecart/buy-now-button     → ❌ rewrite (A-2)
surecart/product-price[^-]  → ❌ rewrite (A-2)
<!-- wp:core/columns /-->   → ❌ unpaired, fix (A-3)
<!-- wp:core/group /-->     → ❌ unpaired, fix (A-3)
<!-- wp:core/details /-->   → ❌ unpaired, fix (A-3)
metadata.*on inner block    → ❌ strip (A-7)
"backgroundColor":"surecart-bg-N" w/o source bg declaration → ❌ remove (B-13)
"style":{"color":{"background":"#…"}} w/o source bg declaration → ❌ remove (B-13)
```

Each match in Tier A is a guaranteed "Attempt block recovery" prompt; each match in Tier B is a fidelity drift entry.

---

# v7.2.0 — additional rubric items from production-pattern audit

The four new items below were derived by auditing the 20 production block-pattern files in the SureCart plugin's own pattern library. The Tier A items are blocking (recovery-cascade candidates); the Tier B items are fidelity (page renders, but visually/structurally drifts from production conventions).

These are appended to the existing 21-item rubric. Total active items: **25** (Tier A 16 + Tier B 9 + Tier C 5 when input is screenshot-only).

## A-16. Custom-block context guards

**Rule:** `surecart/columns` and `surecart/column` (custom blocks) are LEGAL **only** as direct children inside `surecart/upsell`. They are NOT aliases for `core/columns` / `core/column` and must never be substituted for them.

**Why:** the custom blocks expose CSS variable hooks (`--sc-column-content-width`, `--sc-form-row-spacing`) that wire into the upsell flow's CSS pipeline. Outside that scope, browsers ignore the variables and the layout collapses or recovers.

**How to apply:**
- Inside `surecart/upsell` → emit `surecart/columns` and `surecart/column` (with `width:"60%"` etc. directly).
- Anywhere else → emit `core/columns` and `core/column`.

**Pass example:** `examples/patterns/upsell-info.example.md` (the only canonical exemplar).

**Fail patterns:**
```html
<!-- ❌ surecart/columns outside surecart/upsell -->
<!-- wp:surecart/product-page -->
  <!-- wp:surecart/columns -->
    ...
  <!-- /wp:surecart/columns -->
<!-- /wp:surecart/product-page -->

<!-- ❌ core/column inside surecart/columns -->
<!-- wp:surecart/columns -->
  <!-- wp:core/column {"width":"60%"} -->
    ...
  <!-- /wp:core/column -->
<!-- /wp:surecart/columns -->
```

**Self-check grep:**
```
grep -B2 "surecart/columns" emit.html | grep -v "surecart/upsell"  # any hit = ❌
```

## A-17. `*-template` blocks emit ONCE; never expand `.map()`

**Rule:** the 13 server-iterating template blocks (`product-template`, `product-price-choice-template`, `product-list-filter-tags-template`, `product-list-filter-checkboxes-template`, `product-list-sort-radio-group-template`, `product-review-template`, `product-review-list-filter-tags-template`, `product-review-list-filter-checkboxes-template`, `cart-order-bump-template`) emit their inner block tree **exactly once**. Gutenberg's server-side renderer expands the template over the dataset.

**Why:** if the skill expands a JSX `.map()` over a known prices array into N concrete `<!-- wp:surecart/product-price-choice-template -->` siblings, the server then re-iterates and produces N×M output blocks, breaking the page. Empirical: every production pattern that uses these templates emits exactly one inner instance.

**How to apply:**
- Detecting a `.map()` over `prices`/`variants`/`reviews`/`tags` in the source JSX → emit ONE template with ONE child structure.
- Resist the temptation to "be helpful" and pre-expand the dataset.

**Pass example:** `examples/patterns/product-standard.example.md` (`product-price-choice-template` with one inner `price-name` + group of `price-amount/scratch/interval`).

**Fail patterns:**
```html
<!-- ❌ Three sibling templates from a JSX `.map()` -->
<!-- wp:surecart/product-price-chooser -->
  <!-- wp:surecart/product-price-choice-template -->...Monthly...<!-- /wp:... -->
  <!-- wp:surecart/product-price-choice-template -->...Annual...<!-- /wp:... -->
  <!-- wp:surecart/product-price-choice-template -->...Lifetime...<!-- /wp:... -->
<!-- /wp:surecart/product-price-chooser -->
```

**Self-check grep:**
```
grep -c "surecart/product-price-choice-template" emit.html
# Expected: 2 (one open + one close). 4+ = unexpanded .map() artifact.
```

## B-21. Inline-style key serialization order matches `save()`

**Rule:** when a wrapper block emits inline `style="…"`, the CSS property order MUST match Gutenberg's `save()` canonical order:
```
margin-{T,R,B,L} → padding-{T,R,B,L} → border-{color,width,style,radius} → font-{size,weight,style} → line-height → text-{decoration,transform,align} → letter-spacing → color → background-color → min-height → aspect-ratio → max-width → flex-basis → --sc-* CSS vars
```

**Why:** out-of-order styles do NOT trigger recovery in current Gutenberg, but they make round-trip diffs noisy and may regress in future WP releases that tighten the save() comparator. The 20 production patterns ALL match the canonical order across 150+ inline styles audited — zero deviations.

**How to apply:**
- Generate inline styles by walking the JSON `style` object in the canonical key order (color → spacing → border → typography → elements → layout → dimensions → position).
- Within `spacing.padding`/`spacing.margin`, emit T/R/B/L in that order — never alphabetical.

**Pass example:**
```html
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-size:16px;font-weight:500;color:#5b5048">
```

**Fail example:**
```html
<!-- ❌ alphabetical order; not what save() emits -->
<div class="wp-block-group" style="background-color:#fff;color:#5b5048;font-size:16px;margin-bottom:0">
```

## B-22. Class-set order on paired wrappers

**Rule:** the wrapper `<div>` (or `<h1>`/`<ul>`/`<blockquote>`) on a paired block must emit classes in the canonical order:
```
wp-block-{name} → align{wide,full,center} → has-{slug}-color → has-text-color → has-link-color
→ has-{slug}-background-color → has-background → has-border-color
→ is-style-* | is-layout-* | custom
```

**Why:** save() produces classes deterministically; out-of-order class sets cause silent recovery in some Gutenberg versions and failed CSS specificity matches when themes target by `:nth-class()`. The 20 production patterns all comply.

**How to apply:**
- Determine class set from the JSON attributes (slug-attr → `has-{slug}-color` etc.; presence of `style.color.text` → `has-text-color`; etc.).
- Emit in the canonical order.
- `has-text-color` is a TRAILING INDICATOR — always after the specific color class. Never put it first.

**Pass examples (from production patterns):**
```html
<div class="wp-block-group has-background" style="...">
<div class="wp-block-group has-border-color has-white-background-color has-background" style="...">
<div class="wp-block-columns alignwide">
<p class="has-black-color has-text-color has-link-color" style="...">
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
<div class="wp-block-surecart-column is-layout-constrained is-horizontally-aligned-center" style="...">
```

**Fail patterns:**
```html
<!-- ❌ has-text-color before the specific color class -->
<p class="has-text-color has-black-color">

<!-- ❌ has-background before the specific bg class -->
<div class="wp-block-group has-background has-white-background-color">

<!-- ❌ has-border-color after has-background -->
<div class="wp-block-group has-white-background-color has-background has-border-color">  <!-- order swap -->
```

## (Refinement) A-9a clarification on layout double-class

The existing A-9a rule says `core/group` with `layout:{type:"constrained"}` MUST carry both `is-layout-constrained` AND `wp-block-group-is-layout-constrained` on the wrapper div. The 20-pattern audit found 2 production patterns where the double-class is NOT emitted on a constrained group, yet they paste cleanly in the current editor (see `findings-rubric.md`). This implies save() in current Gutenberg is tolerant of the omission.

**Skill behavior unchanged:** continue emitting BOTH classes. The production patterns are forward-compatible (newer save() will produce both). Don't relax A-9a; tolerate the upstream omission.

## (Refinement) A-15 on `core/html` SVG patterns

The 8+ inline SVGs in the production corpus are all L1-safe and meet a tighter checklist than the existing A-15 implies:
```
✓ xmlns="http://www.w3.org/2000/svg"
✓ viewBox (fixed)
✓ fill="none"
✓ stroke="<palette-hex>" — matches design palette
✓ stroke-width="1.25"
✓ stroke-linecap="round"
✓ stroke-linejoin="round"
✗ NO <script>
✗ NO <use href> external refs
✗ NO <animate>/<animateTransform>
✗ NO event handlers (onclick, onload)
```

**Skill behavior:** for any SVG that fails this checklist, drop to L3 — replace with `core/image` referencing an exported PNG, or omit entirely with a `dropped_features` log entry. Do not emit a noncompliant SVG inside `core/html` and hope it works.

## Updated self-validation output format

When emitting markup, append:

```
> Self-validation: Tier A 16/16, Tier B 9/9, Tier C skipped (input: zip).
>   v7.2.0 items checked:
>     A-16 custom-block context guard ✓
>     A-17 *-template emit-once ✓
>     B-21 inline-style key order ✓
>     B-22 class-set order ✓
```

Switch to `Tier C N/5` only when input is a screenshot/image.

## Updated self-check grep extension

Append these to the pre-emit sweep:

```
# Tier A new
grep -E "<!-- wp:surecart/columns" emit.html | grep -B5 "surecart/upsell"  # must show upsell ancestor; else A-16
grep -c "<!-- wp:surecart/product-price-choice-template" emit.html  # 2 expected (open + close); ≥4 → A-17 violation
grep -c "<!-- wp:surecart/product-template" emit.html  # 2 expected (open + close); ≥4 → A-17 violation
grep -c "<!-- wp:surecart/product-review-template" emit.html  # 2 expected
grep -c "<!-- wp:surecart/cart-order-bump-template" emit.html  # 2 expected

# Tier B new (manual review; no clean grep)
# B-21: visually scan inline style="" attributes for canonical order
# B-22: visually scan class="" attributes on paired wrappers for canonical order
```

---

# v7.3 — Aether Notebook regression fixes (5 new rubric items + 2 updates)

The following rubric items were added in v7.3 after the Aether Notebook fixture exposed multiple recovery / fidelity issues that the previous rubric didn't catch.

## A-18 (Tier A blocking) — JSON string-value cap

**Rule:** No individual JSON string value emitted in any block-comment attr exceeds **80 characters**.

**Why:** the dominant paste-recovery trigger is `JSON.parse` rejecting literal newlines (U+000A) inside JSON string values per ECMA-404 §9. Long strings (especially `fontFamily` literal stacks) routinely soft-wrap during chat-render or terminal copy-paste, inserting a literal newline mid-value.

**How to apply:**
- Cap `fontFamily` literal stacks at primary face + ONE generic fallback (e.g., `"Cormorant Garamond, serif"` is 25 chars, safe).
- Register custom slugs in `reference/theme-partial.json` for non-default fonts and emit slug-only.
- Cap `summary` (on `core/details`), `metadata.name`, `metadata.patternName`, gradient strings, URLs at 80 chars.

**Greppable check:**
```
awk -F'"' '/<!-- wp:/ { for (i=2; i<=NF; i+=2) if (length($i) > 80) print NR ":" $i }' emit.html
# Expected: empty
```

**Pass example:** `"fontFamily":"surecart-display"` (slug, 17 chars — registered in production theme partial). Literal-short-stack alternative: `"fontFamily":"Cormorant Garamond, serif"` (25 chars; HC#35/HC#41 Path B).
**Fail example:** `"fontFamily":"Cormorant Garamond, Cormorant, Garamond, Times New Roman, serif"` (64 chars, line-wrap risk).

## A-19 (Tier A blocking) — Buy-button text attr mandatory

**Rule:** every `surecart/product-buy-button` carries a non-empty `text` attr.

**Why:** self-closed `<!-- wp:surecart/product-buy-button /-->` (no attrs) renders the default text or empty string. Recovery on paste strips JSON attrs and leaves the block in this bare state, with the merchant unable to identify which button was Add to Cart vs Buy Now.

**How to apply:**
- Primary: `{"add_to_cart":true,"text":"Add To Cart"}`
- Secondary (Buy Now): `{"text":"Buy Now","className":"is-style-outline"}`
- Inverse-styled (white-on-brown): include explicit `style.color.background` + `style.color.text`.

**Greppable check:**
```
grep -c '<!-- wp:surecart/product-buy-button /-->' emit.html  # expect 0
grep -c '<!-- wp:surecart/product-buy-button {' emit.html     # expect ≥ 1
```

## B-23 (Tier B fidelity) — Card grids use `core/group` flex, not `core/columns`

**Rule:** card grids (3+ sibling cards) use `core/group` flex with each child card a `core/group` carrying `style.layout:{selfStretch:"fixed",flexSize:"NN%"}`. NEVER `core/columns`.

**Why:** `core/columns` produces theme-conflict on borders (column wrappers inherit theme `border-color: currentColor` overrides → visible black borders on cards), no per-card width control via inline style, and harder to tighten gaps. Production patterns at `examples/patterns/product-physical.example.md:26-31` and `product-course-dark.example.md:94-134` exclusively use `core/group` flex for card grids.

**How to apply:**
- 4-up: `flexSize:"23%"`
- 3-up: `flexSize:"31%"`
- 2-up: `flexSize:"48%"`

**Greppable check:**
```
awk '/<!-- wp:columns/,/<!-- \/wp:columns/' emit.html | grep -c '<!-- wp:core/group'
# If ≥ 3 (typical card grid): fail — switch to core/group flex
```

## B-24 (Tier B fidelity) — Body-bg shim

**Rule:** if input CSS has `body{background:...}` or a body-level CSS var (e.g., `--bg-page`), the emitted output's first inner `core/group` of `surecart/product-page` carries that bg.

**Why:** `surecart/product-page` is `apiVersion:3` server-rendered (no save()), so it can't carry `style.color.background` directly. The canonical host is the inner `core/group` shim. Logging body bg as `dropped_features` is wrong — there's always a host.

**How to apply:** emit body-bg shim per `examples/patterns/product-physical.example.md:25-26`:
```html
<!-- wp:surecart/product-page {...} -->
<!-- wp:group {"style":{"color":{"background":"#hex"},"spacing":{"padding":{...}}},"layout":{"type":"constrained","contentSize":"NNNNpx"}} -->
<div class="wp-block-group has-background" style="background-color:#hex;...">
  …all sections…
</div>
<!-- /wp:group -->
<!-- /wp:surecart/product-page -->
```

**Greppable check:** for any input with `body{background:#xyz}`, emitted output must have `#xyz` in a `style.color.background` attr or inline `background-color:` of the first inner `core/group`.

## B-25 (Tier B fidelity) — Inline review summary in hero

**Rule:** if the design shows a single-line review chrome (stars + numeric rating + count), the output uses the inline `core/group` flex pair (stars + total-rating, **className OMITTED** per HC#19 v7.15 — `plus-sign` is `isDefault:true`), NOT the bare `<!-- wp:surecart/product-review-summary /-->` self-closed (which renders the expanded summary card chrome).

**Why:** the bare self-closed `surecart/product-review-summary` renders SureCart's default chrome (a gray card with breakdown bars + "Write a review" CTA), which is wrong for hero contexts where the design wants `★★★★★ 4.9 · 1,284 reviews` in a single line.

**How to apply:** emit per `examples/patterns/product-standard.example.md:35-41`:
```html
<!-- wp:group {"style":{"spacing":{"blockGap":"10px","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px">
    <!-- wp:surecart/product-review-average-rating-stars /-->
    <!-- wp:surecart/product-review-total-rating {"style":{"spacing":{"blockGap":"4px"}}} /-->
</div>
<!-- /wp:group -->
```

**Greppable check:**
```
grep -c 'product-review-average-rating-stars' emit.html  # expect ≥ 1
grep -c '<!-- wp:surecart/product-review-summary /-->' emit.html  # expect 0 (in hero contexts)
```

## B-26 (Tier B fidelity) — Design-section coverage

**Rule:** every visually distinct horizontal band (section) in the input design appears in the emitted output as a primitive (P1–P10 from `reference/design-patterns.md` Part 1) or as a SureCart block, OR is logged in `dropped_features` with a `merchant_action`. **No section is silently dropped.**

**Why:** the dominant cause of "design doesn't match" reports is silent section omission. Hard Constraint #9 already forbids this for named JSX sections; B-26 extends it to all input modes (zip, screenshot, html_css, live_url) and ties it to the per-section ledger built in Step 3a.

**How to apply:**
1. Walk the design top-to-bottom and enumerate every distinct section (body-bg shim, hero, feature grid, specs row, reviews, FAQ, CTA band, footer, etc.). For zip input, the source-of-truth section list is the components rendered in `product-page.jsx`. For screenshot/html_css/live_url, it's the visual section count from the per-section ledger.
2. Cross-check the emitted output: every section in the ledger appears either as a primitive (P1–P10), as a SureCart block (`surecart/product-reviews`, `surecart/product-list-related`, etc.), or in `dropped_features[]` with a `section` key + `merchant_action` string explaining how to restore it.
3. If a section is split across multiple primitives (e.g., a "testimonial carousel" decomposed into a P6 CTA-band wrapper + multiple P3 vertical cards), the carousel-specific behavior (auto-rotate, swipe) is a `dropped_features` entry with `merchant_action` pointing at `/surecart-new-block`.

**Greppable check:**
```
# section count in drift report sections_detected[] must equal section count in source
# (manually verify against the per-section ledger from Step 3a)
jq '.sections_detected | length' drift.json

# every section in sections_detected[] must either appear in the markup OR have a dropped_features entry
# (manual cross-check; section names appear in metadata.name on the relevant primitive's outer wrapper, OR in dropped_features[].section)
```

## B-27 (Tier B fidelity) — Wrapper set-equality (inline `style="..."` ↔ JSON `style.*` 1:1)

**Rule:** for every `core/group`, `core/cover`, or other wrapper that carries an inline `style="..."` attribute, every `prop:value` rule in the inline string MUST have a matching JSON `style.*` path in the block-comment attrs, AND every JSON `style.*` path that style-engine would emit MUST appear as a `prop:value` rule in the inline string. The two sets must be 1:1.

**Why:** Gutenberg's `isEquivalentHTML` (`gutenberg/packages/blocks/src/api/validation/index.js:622-697`) validates wrapper-class sets and inline-style rule sets — orphan rules (inline rule with no JSON correlate) and missing rules (JSON path with no inline correlate) both trigger "Block validation: Expected attributes Array(N), instead saw Array(M)" → the "Attempt block recovery" prompt. Set-equality enforcement is the primary defense against the recovery cascade beyond paste-time JSON.parse failures.

**How to apply:**
1. For every paste-bearing wrapper (`core/group`, `core/cover`, `core/columns`, `core/column`, `core/buttons`), walk the inline `style="..."` attribute. Each `prop:value` rule (e.g., `padding-top:24px`, `border-color:#e6dccb`, `background-color:#ffffff`) maps to a JSON `style.*` path:
   - `background-color` → `style.color.background`
   - `color` → `style.color.text`
   - `padding-{side}` → `style.spacing.padding.{side}`
   - `margin-{side}` → `style.spacing.margin.{side}`
   - `border-color` → `style.border.color`
   - `border-{side}-color` → `style.border.{side}.color`
   - `border-style` / `border-width` / `border-radius` → `style.border.{style|width|radius}`
   - `font-size` / `line-height` / `letter-spacing` → `style.typography.{fontSize|lineHeight|letterSpacing}`
2. For each inline rule, confirm a corresponding JSON path. If absent → orphan rule → drop or add the JSON path.
3. For each JSON `style.*` leaf, confirm a corresponding inline-mirror rule. If absent (and the wrapper is in the inline-mirror group per Rule 0 in `reference/style-conversion.md`) → missing rule → add the inline-mirror.
4. **Slug + literal mixing is forbidden on chrome-bearing wrappers.** If a wrapper carries `backgroundColor:"surecart-cream"` (slug), do NOT also emit inline `background-color:#hex` — the slug is its own emission and the literal becomes orphan. Use literal-everywhere OR slug-everywhere on chrome-bearing wrappers (the literal-everywhere route is preferred per `feedback_design_to_blocks_paste.md`).

**Greppable check:**
```
# For every core/group with inline style="...", verify every prop:value rule has a JSON correlate
# (manual cross-check on each block-comment + wrapper pair; no clean grep — the rule sets are inside both the JSON and the HTML)

# As a heuristic, count rules and JSON paths per wrapper and confirm they roughly match:
# extract inline style="..." → split on ;
# extract JSON style.{color,spacing,border,typography}.* → walk leaves
# counts should be equal (with the prop ↔ path mapping above)
```

**Verified:** Aether Field Notebook regression — every "Block validation: Expected attributes Array(1), instead saw Array(2)" error during the iteration phase was a set-mismatch (orphan inline rule or missing inline-mirror). Final `~/Desktop/aether-notebook.html` passes B-27 → zero recovery prompts (2026-05-08).

## Update to A-9 — Paste-safe inline-style content

The original A-9 captured `var:preset|spacing|*` as the paste-fragility surface. v7.3 broadens: the actual fragility is in JSON string VALUES (per A-18), not inline style content. Production patterns have inline styles up to 399 chars and paste safely.

**v7.3 sub-rules:**
- (i) No `var:preset|spacing|*` in `style.spacing.*` (existing — these create long `var(--wp--preset--spacing--N)` strings inside inline `style=""`).
- (ii) No JSON string value > 80 chars (A-18 — covers `fontFamily`, `summary`, `metadata.*`).
- (iii) Cap font-family stacks at primary + generic fallback only.

## Update to B-14 — `has-border-color` + `border-style:solid` (v7.6 REVERSAL of v7.3)

History:
- **v5.6** said "do NOT include `border-style:solid`" — wrong, reversed in v7.3.
- **v7.3** reinstated `border-style:solid` BUT also said "OMIT `has-border-color` for literal-hex" — empirically wrong (Aurora Lamp paste-test 2026-05-11), reversed in v7.6.
- **v7.6 (current)** unifies the rule: BOTH `border-style:solid` AND `has-border-color` emit for ANY border-color (slug or literal-hex).

**v7.6 B-14 sub-rules (the canonical truth):**
- ALWAYS emit `style.border.style:"solid"` in JSON AND `border-style:solid` in inline-style mirror when `border.color` or `border.width` is set.
- ALWAYS emit `has-border-color` class on the wrapper when `style.border.color` is set, regardless of whether the value is a slug or literal-hex. `save()` always emits it; omitting it triggers paste recovery on every card.
- ADDITIONALLY emit `has-{slug}-border-color` when `borderColor:"<slug>"` is used.
- The inline `style="border-color:#hex"` mirror always wins via CSS specificity (inline > class), so the prior `--wp--custom--color--border` concern is moot.
- Wrapper class set for literal-bg + literal-hex border card (v7.6 canonical): `wp-block-group has-border-color has-background` (+ optional layout classes if `layout.type:"constrained"`).
- Wrapper class set for slug border-color: `wp-block-group has-border-color has-{slug}-border-color has-background` (+ optional layout classes).
- Per-side asymmetric borders (`border:{bottom:{color,width},top:[],left:[],right:[]}`, no top-level `color`): NO `has-border-color` class. Only `border-bottom-color:#hex;border-bottom-width:1px` in inline.

**Greppable check (v7.6):**
```
# Every block with style.border.color in JSON must have has-border-color in HTML class
# Audit: count JSON blocks that have "border":{"color": vs. HTML wrappers with has-border-color
grep -c '"border":{"color":"#' emit.html  # JSON literal-hex borders
grep -c 'has-border-color' emit.html       # HTML class hits
# Expected: HTML hits ≥ JSON hits (the gap is slug-borderColor which doubles up: has-border-color + has-{slug}-border-color)

# border-style:solid IS emitted (Rule 4 unchanged from v7.3)
grep -c 'border-style:solid' emit.html  # expect ≥ 1 per card/border
```

## B-28 (Tier B fidelity) — Prefer `core/icon` over inline-SVG `core/html` for built-in icon slugs (v7.12)

**Rule:** when the design has an icon glyph that matches one of the 88 built-in `core/icon` slugs (arrow-*, chevron-*, cart, check, plus, star-{filled,empty,half}, info, search, menu, shield, share, etc.), EMIT `core/icon` — not `core/html` with inline `<svg>` markup.

**Why:** WordPress 7.0's native `core/icon` block resolves slugs via `WP_Icons_Registry` server-side. It's paste-safe (no `unfiltered_html` capability dependency), theme-overridable via the `.wp-block-icon svg` selector, and schema-clean (no set-equality drift risk). Inline-SVG `core/html` was the previous L1 fallback and is still legitimate for novel/custom glyphs — but for the 88 built-in slugs, it's a downgrade on every dimension.

**How to apply:**

1. For every `core/html` block in your candidate output containing `<svg>` markup, check whether the glyph's shape matches a built-in slug. The most-common matches:

    | Glyph | Slug |
    |---|---|
    | right-arrow / "continue" / "next" | `core/arrow-right` |
    | down chevron / accordion indicator | `core/chevron-down` |
    | check mark / feature tick | `core/check` |
    | cart / shopping bag | `core/cart` |
    | star (full/empty/half) for ratings | `core/star-filled` / `core/star-empty` / `core/star-half` |
    | plus / "add" / "expand" | `core/plus` |
    | search / magnifier | `core/search` |
    | info circle / tooltip | `core/info` |
    | menu / hamburger | `core/menu` |
    | shield (warranty, security) | `core/shield` |

    Full catalog: `reference/wp-core-blocks.md § core/icon`.

2. If a match exists, swap the `core/html` for `<!-- wp:icon {"icon":"core/{slug}"} /-->`. Move color attrs into `textColor` (slug) or `style.color.text` (literal hex), and size attrs into `style.dimensions.width`.

3. If no built-in slug matches, prefer `core/image` (SVG/PNG URL) before falling back to `core/html` L1.

**Greppable check:**

```
# Every <svg> inside core/html in the output should fail this check (i.e., no svg in core/html for built-in glyphs)
grep -A5 "<!-- wp:html" emit.html | grep -E "(arrow|chevron|cart|check|plus|star|info|search|menu|shield|share|tag|home|external)" | wc -l
# Expected: 0. Any hit → there's a built-in slug match the skill missed.
```

**Pass example:** `<!-- wp:icon {"icon":"core/shield","style":{"color":{"text":"#01824C"},"dimensions":{"width":"32px"}}} /-->` instead of `<!-- wp:html --><svg…/></svg><!-- /wp:html -->` for a shield glyph.

**Fail example:** a P3 vertical icon-text card (warranty/security/eco-cert) emitting an inline-SVG `core/html` shield when `core/icon` with `"core/shield"` is available.

**Note:** B-28 is Tier B (fidelity) NOT Tier A (blocking) — the inline-SVG form still pastes successfully for users with `unfiltered_html` capability; the issue is loss of theme-override capability and downstream maintainability. Flag it in the drift report but don't block emission.

---

## A-33 (Tier A blocking) — `core/group` `style.position` does NOT inline-mirror (v7.13 — REVERSES v7.0 Exemplar 9)

For every `core/group` with `style.position.{type,top}` in JSON, the wrapper `<div>`'s inline `style=""` MUST NOT contain `position:*`, `top:*`, `right:*`, `bottom:*`, `left:*`, or `z-index:*` rules. The position behavior routes through the `is-position-sticky` class only.

- [ ] No `core/group` `<div>` element has `position:sticky` / `position:fixed` / `position:absolute` / `position:relative` in its inline `style="..."` attribute.
- [ ] No `core/group` `<div>` has `top:Npx` / `bottom:Npx` / `left:Npx` / `right:Npx` / `z-index:N` in its inline `style="..."`.
- [ ] When JSON has `style.position.{type:"sticky",top:"0px"}` (or similar), the wrapper class includes `is-position-sticky` (or `is-position-{type}` for non-sticky variants).
- [ ] The `top` numeric value lives in JSON only — save() applies via class-based CSS, NOT inline mirror.
- [ ] Drop z-index requirements under `dropped_features.type:"z_index"` with a `merchant_action` recommending theme CSS targeting `.is-position-sticky`.

**Failure signature:** `Generated: style="border-bottom-color:#E8E1D8;...;padding-left:32px", retrieved: style="...padding-left:32px;position:sticky;top:0px;z-index:50"` — recovery cascade on the wrapper. Same routing family as A-24 (no aspect-ratio inline on core/group) and A-32 (no aspect-ratio inline on cover with style.dimensions.aspectRatio).

**Canonical exemplar (corrects v7.0 Exemplar 9):**

```html
<!-- wp:group {"align":"full","style":{"position":{"type":"sticky","top":"0px"},"color":{"background":"#FBF7F2"},"border":{"bottom":{"color":"#E8E1D8","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1240px"}} -->
<div class="wp-block-group alignfull is-position-sticky has-background" style="border-bottom-color:#E8E1D8;border-bottom-width:1px;background-color:#FBF7F2">
…
</div>
<!-- /wp:group -->
```

NO `position:sticky`, NO `top:0px`, NO `z-index:50` in inline. JSON retains the position attrs.

Empirically verified via Morning Glow v2 paste-test (2026-05-26).

## A-34 (Tier A blocking) — `core/button` `has-custom-font-size` class + canonical class order (v7.13)

For every `core/button` block whose JSON has `style.typography.fontSize` as a literal px value, the inner `<a class="wp-block-button__link">` MUST include `has-custom-font-size` in its class set, AND `wp-element-button` MUST appear as the FINAL (trailing) class.

- [ ] For every `core/button` with literal-px `style.typography.fontSize`, the `<a>` class includes `has-custom-font-size`.
- [ ] When `fontSize:"<slug>"` (preset slug) is used instead of literal px, emit `has-{slug}-font-size` — NOT `has-custom-font-size`.
- [ ] Canonical class set for consistency (order is NOT validator-enforced — see "Class order — pure set-equality" note below): `wp-block-button__link has-text-color has-background has-{slug}-font-family has-custom-font-size wp-element-button` (when all four modifier classes apply).

**Class order — pure set-equality (v7.18.3 PR2 correction).** WP's `validateBlock` `isEqualAttributesOfName.class` (`@wordpress/blocks/build/api/validation/index.js:348-356`) splits the class list into a set and compares set-membership only. **Class order is NEVER validator-enforced.** The prior B-22 / A-34 prose "trailing positions are order-sensitive" was empirically wrong against the validator source — order is a convention for aesthetics + diff stability, not a paste-recovery trigger. The canonical order above stays in the rubric as a CONSISTENCY recommendation; deviating from it does NOT cause recovery.

**Failure signature (missing `has-custom-font-size` — the REAL failure):** `Expected class "...has-custom-font-size...", saw "..." (no has-custom-font-size)`. This IS validator-enforced (set-membership). Missing the class entirely triggers recovery; mid-sequencing the existing class does NOT.

**Canonical exemplar (v7.13):**

```html
<!-- wp:button {"style":{"color":{"background":"#2A2620","text":"#FBF7F2"},"spacing":{"padding":{"top":"16px","right":"32px","bottom":"16px","left":"32px"}},"border":{"radius":"9999px"},"typography":{"fontSize":"16px","fontWeight":"500"}},"fontFamily":"surecart-body"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background has-surecart-body-font-family has-custom-font-size wp-element-button" href="#subscribe" style="border-radius:9999px;color:#FBF7F2;background-color:#2A2620;padding-top:16px;padding-right:32px;padding-bottom:16px;padding-left:32px;font-size:16px;font-weight:500">Start subscription — $42 / month</a></div>
<!-- /wp:button -->
```

**This is the same rule already documented for `core/heading` and `core/paragraph` per Rule 5 in `reference/wp-core-blocks.md`** (`has-custom-font-size` is added when `style.typography.fontSize` is a literal px value). A-34 extends the rule to `core/button` where it was previously implicit but not explicitly required by the rubric.

Empirically verified via Morning Glow v2 paste-test (2026-05-26).

## Updated rubric tally (v7.18.2)

- **Tier A blocking:** A-1 through A-47 (with A-9 expanded to sub-bullets; A-13/A-14 reserved-but-unused; A-18..A-47 added across v7.3 → v7.18.2)
- **Tier B fidelity:** B-13 through B-30 (B-29 and B-30 added v7.15 backfill)
- **Tier C screenshot-only:** C-17 through C-22

**Total active items:** ~59 distinct numbered items (some reservations).

State the rubric result at the start of every emission:

> Self-validation: Tier A 45/45, Tier B 18/18, Tier C skipped (input: zip).
>   v7.14 items checked (Kobachi deep visual diff 2026-05-27):
>     B-29 font-family literal fallback alongside slug ✓
>     B-30 featured-image cover SVG placeholder ✓
>   v7.15 items checked (Kobachi R1 v2 user-fix 2026-05-27):
>     A-35 server-rendered block style-attr bypass — wrap-and-target fallback ✓
>     A-37 registered block-style variations checked FIRST ✓
>     A-38 per-block sub-attribute defaults — emit only when overriding ✓
>   v7.16 items checked (R2 Atlas Greens paste-test 2026-05-27):
>     A-39 sticky-purchase content-overflow guard ✓
>     A-40 useFeaturedImage:false on thumb-strip covers ✓
>     A-41 fontFamily attr + literal font-family conflict (pick one) ✓
>   v7.17 items checked (R2 Atlas Greens editor recovery + user-flag 2026-05-27):
>     A-42 core/cover isDark matches bg luminance (omit when bg dark) ✓
>     A-43 product-review-breakdown (NOT -average-rating-breakdown) ✓
>     A-44 sticky-purchase canonical inner blocks (selected-variant-image) ✓
>   v7.18 items checked (R3 Lumen SaaS comment-strip paste-test 2026-05-27):
>     A-45 NO descriptive HTML comments — grep '^<!-- [^/w]' returns 0 ✓

---

## Updated rubric tally (v7.13, legacy)

- **Tier A blocking:** 30 items (A-1 through A-28, with A-9 expanded to 3 sub-bullets; A-18..A-28 added across v7.3/v7.6/v7.7/v7.8; A-29..A-32 added across v7.10/v7.11; A-33..A-34 added v7.13 — total 34 items including A-29..A-34).
- **Tier B fidelity:** 16 items (B-13 through B-28, with B-14 REVERSED in v7.6 and B-23..B-28 added).
- **Tier C screenshot-only:** 6 items (C-17 through C-22 — C-22 added in v7.11).
- **Total active items:** 48 (was 46 in v7.12, 45 in v7.11, 44 in v7.10, 41 in v7.8, 39 in v7.7, 36 in v7.6, 32 in v7.5, 30 in v7.3, 25 in v7.2).

State the rubric result at the start of every emission:

> Self-validation: Tier A 28/28, Tier B 15/15, Tier C skipped (input: zip).
>   v7.3 items checked:
>     A-18 JSON string-value cap (≤80 chars) ✓
>     A-19 buy-button text attr ✓
>   v7.5 items checked:
>     B-23 group flex card grids ✓
>     B-24 body-bg shim ✓
>     B-25 inline review summary ✓
>     B-26 design-section coverage ✓ (every section in source maps to a primitive or dropped_features entry)
>     B-27 wrapper set-equality ✓ (every inline style:"..." rule has a 1:1 JSON style.* correlate)
>   v7.6 items checked (Aurora Lamp paste-test 2026-05-11):
>     A-20 has-border-color always-emit (REVERSES v7.3 OMIT rule) ✓
>     A-21 no HTML comments inside surecart/product-page ✓
>     A-22 core/image strict-schema (no freehand <figure>/<img> inline styles) ✓
>     A-23 core/list wp-block-list class + no inline-styled <li> children ✓
>     B-14 REVERSED: has-border-color emitted for BOTH slug and literal-hex border colors
>   v7.7 items checked (Morning Glow Serum paste-test 2026-05-11):
>     A-24 NO aspect-ratio on core/group (use padding/min-height/cover instead) ✓
>     A-25 zero-value spacing — JSON↔inline parity (drop both or emit both) ✓
>     A-26 core/cover gradient class set: wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient ✓
>   v7.8 items checked (Loom & Ash Throw paste-test 2026-05-11):
>     A-27 product-review-list paired with Start-Basic inner template (no template-picker placeholder) ✓
>     A-28 product-list-related paired with Start-Basic inner template (no template-picker placeholder) ✓
>   v7.12 items checked (core/icon native support, 2026-05-26):
>     B-28 prefer core/icon over inline-SVG core/html for built-in slugs ✓
>   v7.13 items checked (Morning Glow v2 paste-test 2026-05-26):
>     A-33 core/group position has NO inline mirror (REVERSES v7.0 Exemplar 9) ✓
>     A-34 core/button has-custom-font-size + wp-element-button trailing class ✓
