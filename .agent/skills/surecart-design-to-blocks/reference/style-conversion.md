# Style Conversion — JSX `style={{}}` → Gutenberg attrs

## At-a-glance: where each CSS property lands

**Table A — CSS-var → preset slug (color + fontFamily) and resolved literal (spacing).** Values mirror `app/data/surecart-theme-partial.json` (the runtime palette source-of-truth). In Phase 2 this becomes a sibling file `reference/token-aliases.json` so the skill can resolve both slug AND hex without re-reading the palette.

**Color slugs** (CSS var → slug → hex). Slug is emitted as `backgroundColor`/`textColor`/`borderColor` attr; hex is the literal fallback when the slug class doesn't render (classic themes without global-styles enqueue):

| CSS var | Slug | Hex |
|---|---|---|
| `--brand` | `surecart-brand` | `#01824C` |
| `--brand-deep` | `surecart-brand-deep` | `#004C3F` |
| `--brand-soft` | `surecart-brand-soft` | `#ECFDF5` |
| `--bg-1` | `surecart-white` | `#FFFFFF` |
| `--bg-2` | `surecart-gray-50` | `#F9FAFB` |
| `--bg-3` | `surecart-gray-100` | `#F3F4F6` |
| `--bg-dark` | `surecart-bg-dark` | `#042F2E` |
| `--bg-dark-alt` | `surecart-bg-dark-alt` | `#004C3F` |
| `--fg-1` | `surecart-gray-900` | `#111827` |
| `--fg-2` | `surecart-gray-700` | `#374151` |
| `--fg-3` | `surecart-gray-500` | `#6B7280` |
| `--fg-4` | `surecart-gray-400` | `#9CA3AF` |
| `--fg-on-dark-muted` | (no slug — emit literal hex) | `rgba(255,255,255,0.72)` |
| `--border-1` | `surecart-gray-200` | `#E5E7EB` |
| `--border-2` | `surecart-gray-300` | `#D1D5DB` |
| `--border-dark` | (no slug — emit literal hex) | `rgba(255,255,255,0.10)` |
| `--ring` | (no slug — emit literal hex) | `rgba(1,130,76,0.32)` |

**Font-family slugs** (CSS var → slug):

| CSS var | Slug | Resolved family |
|---|---|---|
| `--font-display` | `surecart-display` | Geist, Inter, system-ui |
| `--font-body` | `surecart-body` | Figtree, Inter, system-ui |
| `--font-mono` | `surecart-mono` | Kode Mono, JetBrains Mono, monospace |

**Spacing** — emit as **literal px**, never preset slug (v5.9 hybrid policy). When source CSS uses `var(--space-N)` tokens, resolve to the literal px end-value:

| CSS var | Literal px |
|---|---|
| `--space-1` | `4px` |
| `--space-2` | `8px` |
| `--space-3` | `12px` |
| `--space-4` | `16px` |
| `--space-5` | `20px` |
| `--space-6` | `24px` |
| `--space-8` | `32px` |
| `--space-10` | `40px` |
| `--space-12` | `48px` |
| `--space-14` | `56px` |
| `--space-16` | `64px` |
| `--space-18` | `72px` |
| `--space-20` | `80px` |
| `--space-24` | `96px` |

---

**Table B — Per-CSS-property destination map.** For each source CSS property, where the value goes (block attr key, inline-style mirror, wrapper class) and whether emission is required:

| Source CSS | Block attr | Inline-style mirror? | Wrapper class | When required |
|---|---|---|---|---|
| `color` (slug match in Table A) | `textColor:"slug"` | no | `has-{slug}-color has-text-color` | always when source has `color` |
| `color` (literal hex, no slug) | `style.color.text:"#…"` | yes (on inner element for headings/paragraphs; **NOT on paired wrapper**) | `has-text-color` | always when source has `color` |
| `background-color` (slug match) | `backgroundColor:"slug"` | no — slug-only on paired wrapper (Rule 0) | `has-{slug}-background-color has-background` | only when source CSS/JSX has explicit `background`/`backgroundColor` declaration |
| `background-color` (literal hex) | `style.color.background:"#…"` | yes — Rule 0 carve-out for paired wrappers | `has-background` | only when source has it (no-source-no-emit) |
| `padding[*]` | `style.spacing.padding.{side}:"Npx"` | yes on inner blocks; **on paired wrappers ONLY when wrapper also has literal-bg** (Rule 0 + Item 10 hierarchy) | none | always when source has it |
| `margin[*]` | `style.spacing.margin.{side}:"Npx"` | yes — preserve 1:1 (do NOT round 28→32) | none | always when source has it |
| `gap` (parent of grid/flex) | parent's `style.spacing.blockGap:"Npx"` | yes on `core/media-text` and other non-paired contexts; for `core/columns` blockGap ends up in the auto-injected layout `<style>` rule | none | always when source has it |
| `font-family` (slug match) | `fontFamily:"slug"` | no | `has-{slug}-font-family` | always for text-bearing blocks |
| `font-size` (slug match in theme) | `fontSize:"slug"` | no | `has-{slug}-font-size` | when slug exists |
| `font-size` (literal px) | `style.typography.fontSize:"Npx"` | yes (inner element only — never wrapper) | `has-custom-font-size` | always when source has non-default size |
| `font-weight` | `style.typography.fontWeight:"700"` (string) | yes (inner element) | none | when ≠ 400 |
| `line-height` | `style.typography.lineHeight:"1.6"` (string) | yes (inner element) | none | always when source has it |
| `letter-spacing` | `style.typography.letterSpacing:"-0.03em"` (em units, not px) | yes (inner element) | none | preserve 1:1 |
| `text-transform` | `style.typography.textTransform:"uppercase"` | yes (inner element) | none | always when source has it |
| `text-align` | `textAlign:"center"` (top-level attr) | no | `has-text-align-{value}` | always when source has it |
| `border.width/color` (slug) | `borderColor:"slug"` + `style.border.{width,style:"solid",radius}` | no (slug uses class only) | `has-{slug}-border-color has-border-color` | always when source has it. ALWAYS emit `style.border.style:"solid"` (Rule 4 v7.3 re-reversal). |
| `border.width/color` (literal hex) | `style.border.{width,color,style:"solid",radius}` | yes — mirror `border-color:#hex;border-style:solid;border-width:Npx;border-radius:Npx` to inline | **`has-border-color` class IS REQUIRED** (v7.6 — REVERSES v7.3). `save()` always emits this class when `style.border.color` is set, regardless of slug-vs-literal. The inline `style="border-color:#hex"` mirror wins over any theme class-based rule via CSS specificity (inline > class), so the prior `--wp--custom--color--border` worry is moot. Omitting the class triggers "Expected `class` of value `has-border-color`, saw …" recovery on every card. | always when source has it |
| `border-radius` | `style.border.radius:"Npx"` | yes | `has-custom-border` (added by save() whenever ANY `style.border.*` is set, even radius-only) | always |
| `aspect-ratio` on **`core/group`** | **FORBIDDEN — never emit (v7.7)** | **never** | `save()` routes via `--wp--style--aspect-ratio` CSS var / class chain; inline mirror is NOT emitted. Including `aspect-ratio:N` in inline breaks set-equality. Use explicit `padding-top`/`padding-bottom` as workaround for square boxes. | **NEVER on core/group.** OK on `core/cover` (via `aspectRatio` top-level attr) + `core/image` (via own attr) |
| `aspect-ratio` on `core/cover` | `aspectRatio:"16/9"` (top-level attr, NOT under `style.*`) | yes — inline `aspect-ratio:16/9` on the outer `<div class="wp-block-cover">` | none | when source has it |
| `min-height` on `core/cover` | `minHeight:N` (top-level number) + `minHeightUnit:"px"` | yes — inline `min-height:Npx` on outer | none | when source has it |

### Editor round-trip note (Gutenberg UI behavior)

The Gutenberg block editor does **NOT** auto-convert literal px values back to preset slugs. When a merchant opens a card emitted with `"padding":"24px"` and adjusts the spacing in the UI, the value stays in the **Custom** tab (pre-filled with 24). The merchant must explicitly click **Use preset size** to convert to a slug. This is intentional — the v5.9 hybrid policy keeps literals to avoid paste corruption, and the editor preserves the merchant's pasted authorial intent.

Pre-empts a recurring "why isn't my page using my theme spacing" support question: literal px is design-faithful by intent, not a bug.

---

> ## 🚨🚨 v5.9 — SPACING POLICY: literal px ONLY, never `var:preset|spacing|*`
>
> Empirical finding from console diagnostic on the iPhone fixture: long `var(--wp--preset--spacing--60)` strings inside an inline `style="..."` attribute get **line-wrapped during paste** (especially when the wrapper is in a deep tree where the surrounding indentation is wide). The wrap inserts a literal `\n  ` inside the `var()` parens, breaking validation:
>
> ```
> Expected: padding-right:var(--wp--preset--spacing--60);
> Saw:      padding-right:var(--wp--preset--spacing--60\n  );
> ```
>
> The save() output is correct. The post-body markup gets corrupted at paste time.
>
> ### The hybrid policy (v5.9)
>
> | Token category | Emit as | Why |
> |---|---|---|
> | **Color** (background, text, border) | Slug attr (`backgroundColor:"surecart-white"`) → emits short class `has-surecart-white-background-color` | Theme-overrideable. Short class string. No paste fragility. |
> | **Color** (no slug match) | Literal hex on `style.color.background` → inline `style="background-color:#aabbcc"` | When the design hex isn't in the SureCart palette, fall back to literal. |
> | **Font family** | Slug attr (`fontFamily:"surecart-display"`) → emits class `has-surecart-display-font-family` | **Mandatory** for Geist rendering. Theme defines the @font-face. |
> | **Font size** | Slug for theme sizes (`fontSize:"large"`); literal otherwise (`style.typography.fontSize:"56px"` → inline `style="font-size:56px"` + class `has-custom-font-size`) | Slugs are short; literal px is fine for unique sizes. |
> | **Spacing** (padding/margin/blockGap) | **Literal px ONLY** (`"24px"`, `"48px"`, `"96px"`). NEVER `"var:preset|spacing|60"`. | Eliminates the paste-line-wrap fragility. Static values are byte-perfect. |
> | **Border** (radius/width/color) | Literal (`"24px"`, `"1px"`, `"#E5E7EB"`) on `style.border.*` | Same reasoning as spacing — short, stable. |
>
> ### Why slug colors stay (and slug spacing doesn't)
>
> A slug color emits a SHORT class (`has-surecart-white-background-color`) with NO inline style. No `var()` chain in inline style means no paste-wrap risk. The class is also theme-overrideable.
>
> A slug spacing in inline style emits the LONG `var(--wp--preset--spacing--60)` string four times in one attribute. That's the failure mode.
>
> ### Examples
>
> ❌ Wrong (paste-fragile):
> ```html
> <!-- wp:group {"style":{"color":{"background":"#aabbcc"},"spacing":{"padding":{"top":"var:preset|spacing|60",...}}}} -->
> <div class="wp-block-group has-background" style="background-color:#aabbcc;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);...">
> ```
>
> ✅ Right (byte-perfect):
> ```html
> <!-- wp:group {"style":{"color":{"background":"#aabbcc"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"},"margin":{"bottom":"20px"}}}} -->
> <div class="wp-block-group has-background" style="background-color:#aabbcc;margin-bottom:20px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">
> ```
>
> ### Rough preset → px mapping (when source CSS uses tokens)
>
> Use these only if the design's CSS truly uses `var(--wp--preset--spacing--{N})`. Otherwise emit the actual JSX literal value.
>
> | Preset slug | Literal px |
> |---|---|
> | `spacing|30` | `12px` |
> | `spacing|40` | `16px` |
> | `spacing|50` | `20px` or `24px` |
> | `spacing|60` | `24px` or `32px` |
> | `spacing|70` | `48px` |
> | `spacing|80` | `64px` |
>
> ---

> ## 🚨🚨🚨 v7.4 — Validation is SET-based (Gutenberg `isEquivalentHTML` forensic finding)
>
> **Source:** `gutenberg/packages/blocks/src/api/validation/index.js:622-697` (`isEquivalentHTML`).
>
> Both pattern-insertion AND paste paths run `validateBlock` — there is NO bypass. Production patterns "work via insert" only because their markup is ALREADY byte-equivalent to `save()` output. Pasting the SAME markup also paste-validates cleanly.
>
> **The comparator tokenizes both strings and uses set equality:**
> - `class` attribute: compared as space-separated SET (order-insensitive)
> - `style` attribute: compared as semicolon-separated SET of `prop:value` pairs (order-insensitive)
> - Other attributes: order-insensitive at attribute level, type-aware equality
>
> **Implications:**
> 1. Class order on the wrapper does NOT matter
> 2. Inline-style property order does NOT matter
> 3. The SET of classes must match what `save()` would emit
> 4. The SET of `prop:value` rules in inline-style must match what style-engine would emit from JSON
>
> **The actual paste-failure root cause** is one of:
> - **A:** wrapper's inline `style="..."` contains a `prop:value` that has no corresponding JSON `style.*` path (extra rule in pasted markup)
> - **B:** JSON `style.*` has a path whose CSS rule is missing from wrapper inline (missing rule in pasted markup)
> - **C:** the JSON attrs failed to parse at paste-time (long string + line-wrap → `JSON.parse` SyntaxError → `attrs={}` → save() emits naked wrapper, mismatch)
>
> ### v7.4 emission rule — single source of truth
>
> For ANY chrome-bearing `core/group` wrapper, every CSS rule in the wrapper inline `style=""` MUST correspond 1:1 to a JSON path under `attributes.style.*`. The skill emits inline-style by walking the JSON `style.*` tree using style-engine's emission rules:
>
> 1. **`style.border.color/style/width/radius`** → `border-color:V; border-style:V; border-width:V; border-radius:V` (radius=string → single rule; radius=object → 4 corner rules)
> 2. **`style.color.background`** → `background-color:V`
> 3. **`style.color.text`** → `color:V`
> 4. **`style.dimensions.minHeight`** → `min-height:V` (aspectRatio is server-side, skip on save)
> 5. **`style.spacing.margin.{top,right,bottom,left}`** → `margin-{side}:V` per side present (zero values DO emit: `margin-top:0` not `0px`)
> 6. **`style.spacing.padding.{top,right,bottom,left}`** → `padding-{side}:V` per side present
> 7. **`style.typography.{fontSize,fontWeight,fontStyle,lineHeight,letterSpacing,textTransform,textDecoration,fontFamily}`** → respective rules
>
> **Class set is determined by JSON, not free-form:**
> - `has-background` if `backgroundColor` slug OR `style.color.background` OR `gradient` OR `style.color.gradient`
> - `has-text-color` if `textColor` slug OR `style.color.text`
> - `has-link-color` if `style.elements.link.color`
> - `has-border-color` if `borderColor` slug OR `style.border.color`
> - `has-{slug}-background-color` if `backgroundColor` is a registered slug
> - `has-{slug}-color` if `textColor` is a registered slug
> - `has-{slug}-border-color` if `borderColor` is a registered slug
>
> **Forbidden mixing:** never use slug attr + literal inline style for the same property. `backgroundColor:"surecart-cream"` (slug → class only, NO inline) AND inline `style="background-color:#fff"` (literal, no JSON correlate) is the dominant pre-v7.4 failure mode. Pick one path:
> - **Slug path:** `backgroundColor:"<slug>"` — class only on wrapper, no inline style
> - **Literal path:** `style.color.background:"#hex"` — class `has-background` on wrapper PLUS inline `style="background-color:#hex"`
>
> ### v7.4 paste-corruption mitigation
>
> The merchant's empirical paste failures are also caused by **chat-render line-wrapping mid-JSON-string** in long content. Cap any single JSON string value at 80 chars. For long markup, output to a `.html` file and instruct the merchant to paste from the file (which has no soft-wrap), not from the chat surface.
>
> ---
>
> ## 🚨 v7.3.1 — `layout.type` controls inline-style emission on paired wrappers (paste-test verified)
>
> Empirical browser-console evidence (Aether Notebook paste test, 2026-05-08): when `core/group` carries `layout.type:"constrained"` (with the `is-layout-constrained wp-block-group-is-layout-constrained` classes), `save()` does NOT emit inline `style=""` on the wrapper — even with literal-bg + literal-border + padding all set. Pasting markup that includes the inline mirror triggers `Block validation: Expected attributes Array(1), instead saw Array(2)` recovery on every card.
>
> **Production patterns at `examples/patterns/product-physical.example.md:25-26,42` use `layout.type:"default"` (or NO layout attr) for body-bg shims and cards. With `type:"default"`, `save()` DOES emit inline `style=""` AND the `is-layout-*` classes are NOT added.**
>
> **The rule:**
>
> | layout.type | `is-layout-*` classes on wrapper | Inline `style=""` mirror |
> |---|---|---|
> | `"constrained"` (with contentSize) | YES — `is-layout-constrained wp-block-group-is-layout-constrained` | NO inline style — Rule 0 strips |
> | `"default"` or omitted | NO layout classes | YES — full inline mirror of bg/border/padding/margin |
> | `"flex"` | NO layout classes (Rule 0a) | YES inline style mirror |
> | `"flow"` | NO layout classes (Rule 0a) | YES inline style mirror |
>
> **Practical guide for the skill:**
> - **Body-bg shim (when no contentSize needed):** use `layout.type:"default"` + literal bg + inline-style mirror.
> - **Body-bg shim (when contentSize needed):** put `contentSize` on the OUTER `surecart/product-page` block (it accepts `layout:{type:"constrained",contentSize:"1280px"}`). Inner shim uses `type:"default"` + literal bg + inline-style mirror.
> - **Cards:** use `layout.type:"default"` (or omit `layout` entirely) on each card's `core/group` wrapper. Keep literal-bg + literal-border + padding inline-style mirror.
> - **Sections needing constrained content:** use `type:"constrained"` ONLY on wrappers WITHOUT bg/border. If both, split: outer constrained group (no bg) wrapping inner default group (with bg).
>
> **`core/details` follows the same pattern:** save() does NOT emit inline `style=""` on `<details>` even when `style.border.bottom`, `style.spacing.padding` are set in JSON. **Drop the inline-style mirror from every `<details>` wrapper.** The styling will apply via Gutenberg's auto-generated CSS at render (only on themes that load global styles — classic themes lose the chrome). For maximum paste portability, put visual chrome (border, padding) on the parent `core/group` (with `type:"default"`) instead of on the `<details>` itself.
>
> **Updated emission contract:**
>
> ✅ Right (card with bg + border, paste-safe):
> ```html
> <!-- wp:group {"style":{"color":{"background":"#ffffff"},"border":{"color":"#e6dccb","width":"1px","style":"solid","radius":"12px"},"spacing":{"padding":{"top":"24px","right":"20px","bottom":"24px","left":"20px"}},"layout":{"selfStretch":"fixed","flexSize":"23%"}},"layout":{"type":"default"}} -->
> <div class="wp-block-group has-background" style="border-color:#e6dccb;border-style:solid;border-width:1px;border-radius:12px;background-color:#ffffff;padding-top:24px;padding-right:20px;padding-bottom:24px;padding-left:20px">
>   …card content…
> </div>
> <!-- /wp:group -->
> ```
> Note: `layout.type:"default"` → NO `is-layout-*` classes → inline style PRESERVED on save().
>
> ❌ Wrong (card with `type:"constrained"` + inline mirror — save() strips inline → recovery):
> ```html
> <!-- wp:group {"style":{...},"layout":{"type":"constrained"}} -->
> <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-background" style="border-color:#e6dccb;...">
> ```
>
> ---
>
> ## 🚨🚨🚨 v7.3 — JSON string-value cap (THE actual paste-safety boundary)
>
> The v5.9 policy correctly caught the `var:preset|spacing|*` family but stopped short of the broader rule. **The dominant paste-recovery trigger is `JSON.parse` rejecting literal newlines (U+000A) inside JSON string values per ECMA-404 §9.** Any long string value embedded in block-comment JSON can soft-wrap during chat-render or terminal copy-paste, inserting a literal newline mid-value.
>
> **Forensic evidence** (Aether Notebook fixture, May 2026): the skill emitted `<!-- wp:paragraph {"style":...,"fontFamily":"Cormorant Garamond, Cormorant, Garamond, Times New Roman, serif"} -->`. The 64-char `fontFamily` value soft-wrapped during paste between `Garamond,` and `Times New Roman,`, inserting a literal newline + 2-space indent. `JSON.parse` threw `SyntaxError: Bad control character in string literal` (verified at `gutenberg/packages/block-serialization-default-parser/src/index.ts:366-372`) → catch returned `null` → block parsed with empty attrs → `validateBlock()` mismatched the block's class+style against the `save()` output of the empty attrs → "Attempt Block Recovery" notice. After recovery, the rich-text source-matcher (`packages/blocks/src/api/parser/get-block-attributes.js`) captured the OUTER `<p class="...">...</p>` as the paragraph's `content` attribute, then `save()` wrapped it again, producing the artifact `<p><p class="...">...</p></p>`.
>
> ### The cap (v7.3)
>
> | Cap | Value | Applies to |
> |---|---|---|
> | **Hard cap on any single JSON string value** | **80 characters** | `fontFamily` literal stacks, `summary` (on `core/details`), `metadata.name`, `metadata.patternName`, gradient strings, URLs, any other string emitted inside block-comment JSON |
> | Inline `style=""` length | NOT a hard cap | Production patterns have inline styles up to 399 chars and paste safely. The risk is in JSON values, not post-comment HTML |
>
> ### Path B — emit literal short-stack (≤30 chars) when design needs a specific typeface
>
> Per HC#41 Path B + HC#35 literal fallback, when the design uses fonts not registered in production's `app/data/surecart-theme-partial.json` (which is auto-mirrored to `reference/theme-partial.json` via `yarn build:theme-partial-mirror`), **DO NOT register new custom slugs in the skill mirror — they will be wiped on next mirror sync.** Use the literal-short-stack path:
>
> ```html
> <!-- wp:heading {"style":{"typography":{"fontSize":"48px","fontWeight":"500","fontFamily":"Cormorant Garamond, serif"}}} -->
> <h2 class="wp-block-heading" style="font-family:Cormorant Garamond, serif;font-size:48px;font-weight:500">…</h2>
> <!-- /wp:heading -->
> ```
>
> Key rule (HC#41): pick ONE path per design — slug-only (Path A, when production has the slug) OR literal-only (Path B, no `fontFamily:""` attr). Mixing both creates CSS-cascade ambiguity. **NEVER emit literal font stacks longer than ~30 chars** — cap at primary face + ONE generic fallback (e.g., `"Cormorant Garamond, serif"` is 25 chars, safe; `"Inter, sans-serif"` is 18 chars; `"JetBrains Mono, monospace"` is 26 chars).
>
> ### Examples
>
> ❌ Wrong (paste-fragile, 64-char `fontFamily` value):
> ```html
> <!-- wp:paragraph {"style":{"typography":{"fontFamily":"Cormorant Garamond, Cormorant, Garamond, Times New Roman, serif"}}} -->
> ```
>
> ✅ Right (slug-based, registered in production theme partial):
> ```html
> <!-- wp:paragraph {"fontFamily":"surecart-display"} -->
> ```
>
> ✅ Right (literal short stack, 25 chars — HC#41 Path B, no `fontFamily:""` slug attr):
> ```html
> <!-- wp:paragraph {"style":{"typography":{"fontFamily":"Cormorant Garamond, serif"}}} -->
> ```
>
> ### Why this rule supersedes the inline-style fixation
>
> v5.9 focused on `var:preset|spacing|*` because the resulting `var(--wp--preset--spacing--N)` strings inside inline `style=""` line-wrap. But empirically, **production patterns regularly emit inline styles of 329-399 chars and they paste fine.** The actual failure surface is JSON values, where strict ECMA-404 parsing rejects literal newlines.
>
> ### What about `metadata.name` / `metadata.patternName` / `summary`?
>
> Same cap. A 100-char product name in `metadata.name` will line-wrap and break `JSON.parse`. Truncate or rephrase to ≤ 80 chars. For `summary` on `core/details`, keep FAQ questions concise (most natural questions are well under 80 chars; long questions should be split into a heading + paragraph above the details).
>
> ---

> ## 🚨 v5.7 — DEFINITIVE FIX from console diagnostic
>
> Pasting v5.6 markup and reading the WP browser console revealed THE root cause of recovery prompts:
>
> ```
> Content generated by save function:
> <div class="wp-block-group is-layout-constrained ... has-surecart-white-background-color has-background"></div>
>
> Content retrieved from post body:
> <div class="wp-block-group is-layout-constrained ... has-surecart-white-background-color has-background"
>      style="padding-top:48px;padding-right:32px;padding-bottom:96px;padding-left:32px">
> ```
>
> Classes match perfectly. **The mismatch is `style="padding-..."` exists in the saved markup but NOT in `save()` output.**
>
> **Rule 0 family (read all four — they're tightly coupled):**
> - **Rule 0** (line 157) — paired-block wrappers strip inline `style=""`
> - **Rule 0c** (line 189) — `core/cover` is the ONE exception (must mirror inline)
> - **Rule 0a** (line 209) — `core/group` flex/flow drops the layout double-class
> - **Rule 0b** (line 221) — `core/cover` overlay dim class encodes literal `dimRatio` value
>
> ### Rule 0 (TRUMPS ALL OTHERS) — Strip inline `style="..."` from paired-block wrappers
>
> In WP 6.5+/6.6+, `save()` for paired blocks (`core/group`, `core/columns`, `core/column`, `core/buttons`, `core/media-text`, `core/list`, `core/quote`) does **NOT** mirror `style.spacing.padding` / `style.spacing.margin` / `style.border.radius` / `style.border.color` / `style.border.width` etc. into an inline `style=""` on the wrapper.
>
> **`core/cover` is the ONE exception.** Its outer `<div>` DOES require the inline style mirrored — see Rule 0c below. Earlier drafts of this rule listed `core/cover` here; that was wrong and caused a paste-recovery prompt on the v6 iPhone fixture's product-list-related cover.
>
> Those attrs go in the comment-marker JSON only. The styles get applied via auto-generated `<style>` tags or CSS-class targeting at render time, not inline.
>
> ❌ Wrong (causes recovery on every parent block):
> ```html
> <!-- wp:group {"backgroundColor":"surecart-white","style":{"spacing":{"padding":{"top":"48px",...}}},"layout":{"type":"constrained"}} -->
> <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-white-background-color has-background"
>      style="padding-top:48px;padding-right:32px;...">
> ```
>
> ✅ Right:
> ```html
> <!-- wp:group {"backgroundColor":"surecart-white","style":{"spacing":{"padding":{"top":"48px",...}}},"layout":{"type":"constrained"}} -->
> <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-white-background-color has-background">
> ```
>
> **Keep the `style` JSON in the comment marker.** The styles still apply at runtime — Gutenberg's render injects them. You just don't put them as inline `style=""` on the wrapper div.
>
> The ONLY inline style still allowed on a wrapper is:
> - **literal background color** when not using a slug: `style="background-color:#aabbcc"` for raw hex without a slug
>
> Everything else (`padding-*`, `margin-*`, `border-*`, `border-radius`) is set via attrs only, never inline.
>
> ### Why "Attempt Block Recovery" doesn't fix it
>
> Clicking recovery just dismisses the validation flag for that session — it does NOT rewrite the markup. So inline `style="padding-..."` stays in the saved markup. On reload, validation runs again, recovery fires again. **The fix must be in the emitted markup, not in user action.**
>
> ### Rule 0c (NEW v6.0) — `core/cover` is NOT in the Rule 0 strip-inline carve-out
>
> Rule 0 (paired-block wrappers strip inline `style=""`) applies ONLY to: `core/group`, `core/columns`, `core/column`, `core/media-text`, `core/buttons`, `core/list`, `core/quote`. **`core/cover` is NOT in that list — its outer `<div>` MUST mirror the inline style.**
>
> Specifically, when a `core/cover` block carries `style.spacing.margin.*`, `style.spacing.padding.*`, or `style.border.radius`, save() emits those as inline CSS on the outer `<div class="wp-block-cover ...">`. Omitting the inline style triggers a paste-recovery prompt. (Empirically confirmed via `wp.blocks.getSaveContent()` on the v6 fixture's product-list-related cover.)
>
> Note: `style.dimensions.aspectRatio` is NOT mirrored to the outer wrapper inline style (it lives in attrs only and is rendered server-side); only `border.radius`, `spacing.margin.*`, and `spacing.padding.*` mirror.
>
> ✅ Right (cover with `border.radius:"10px"` + `spacing.margin.bottom:"15px"`):
> ```html
> <div class="wp-block-cover is-light has-custom-content-position is-position-top-center" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>…
> ```
>
> ❌ Wrong (no inline style → recovery cascade):
> ```html
> <div class="wp-block-cover is-light …"><span …></span>…
> ```
>
> ---

> ### Rule 0a — `core/group` `layout.type:"flex"` does NOT get layout double-class
>
> Console diagnostic showed save() emits `<div class="wp-block-group"></div>` (just the base class) for a flex group. The double-class `is-layout-flex wp-block-group-is-layout-flex` is **only** for `layout.type:"constrained"`.
>
> | layout.type | Wrapper class on `core/group` |
> |---|---|
> | `"constrained"` | `wp-block-group is-layout-constrained wp-block-group-is-layout-constrained` |
> | `"flex"` | `wp-block-group` (no layout classes) |
> | `"flow"` (or no layout attr) | `wp-block-group` (no layout classes) |
>
> `core/columns` and `core/column` STILL get their double-class (verified empirically). Only `core/group` flex/flow drops it.
>
> ### Rule 0b (CORRECTED v6.0) — `core/cover` overlay dim class is `has-background-dim-{dimRatio}`
>
> The overlay span class encodes the literal `dimRatio` attribute value, not a constant `100`. The earlier rule that claimed "always 100" was wrong and triggered a paste-recovery prompt on the v6 iPhone fixture's product-list-related cover (which uses `dimRatio:0`). Empirically verified via `wp.blocks.getSaveContent()`: with `dimRatio:N`, save() emits `has-background-dim-N` always, plus a second `has-background-dim` token when `N > 0`.
>
> Emission table:
>
> | `dimRatio` value | Overlay class string |
> |---|---|
> | `0` (no dim — common with `useFeaturedImage:true` for transparent overlay) | `wp-block-cover__background has-background-dim-0 has-background-dim` |
> | `50` (default fixed dim) | `wp-block-cover__background has-background-dim-50 has-background-dim` |
> | `100` (full overlay) | `wp-block-cover__background has-background-dim-100 has-background-dim` |
>
> ✅ Right (`dimRatio:0`):
> ```html
> <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
> ```
>
> ✅ Right (`dimRatio:50`):
> ```html
> <span aria-hidden="true" class="wp-block-cover__background has-background-dim-50 has-background-dim"></span>
> ```
>
> ❌ Wrong (will trigger recovery): `has-background-dim-100` when the attr says `dimRatio:0`.
>
> ---

> ## v5.6 — EMPIRICALLY VERIFIED byte-perfect rules (superseded by Rule 0 above for inline styles)
>
> Earlier rules from expert audits were partially wrong. These rules are now verified against actual recovered markup from a real WP install with the SureCart theme partial. **Rules below correct two earlier expert errors AND add critical patterns the experts missed.**
>
> Pattern-match every emission against `examples/iphone-output.html` (the new empirical golden).
>
> ### Rule 1 (CORRECTED) — Layout double-class is duplicated in BOTH `className` attr AND wrapper div
>
> Every block with layout support emits the layout double-class in TWO places:
>   - In the attribute JSON: `"className":"is-layout-{type} wp-block-{name}-is-layout-{type}"`
>   - On the wrapper div: `class="wp-block-{name} is-layout-{type} wp-block-{name}-is-layout-{type} ..."`
>
> Without `className` in the attr JSON, save() doesn't predict those classes — but my div has them — recovery triggers. **This was THE recovery cascade trigger.**
>
> ✅ Right (every group/columns/column/buttons):
> ```html
> <!-- wp:group {"className":"is-layout-constrained wp-block-group-is-layout-constrained",...} -->
> <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained ...">
> ```
>
> | Block | className value (also the wrapper class suffix) |
> |---|---|
> | `core/group` constrained | `is-layout-constrained wp-block-group-is-layout-constrained` |
> | `core/group` flow (default) | `is-layout-flow wp-block-group-is-layout-flow` |
> | `core/group` flex | `is-layout-flex wp-block-group-is-layout-flex` |
> | `core/columns` (always) | `is-layout-flex wp-block-columns-is-layout-flex` |
> | `core/column` (always) | `is-layout-flow wp-block-column-is-layout-flow` |
> | `core/buttons` (always) | `is-layout-flex wp-block-buttons-is-layout-flex` |
>
> ### Rule 2 (NEW) — `surecart/product-buy-buttons` wrapper has FOUR classes
>
> ```html
> <div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
> ```
>
> Required: `wp-block-surecart-product-buy-buttons`, `wp-block-buttons`, `sc-block-buttons`, `is-layout-flex`. Plus inline `style="margin-bottom:..."` mirroring `style.spacing.margin.bottom` if set.
>
> ### Rule 3 (NEW) — SureCart container blocks need their inner template expanded
>
> Several SureCart blocks LOOK leaf-like but are actually containers with mandatory inner templates. Self-closing them triggers recovery because the template expands at render. Always emit the template:
>
> | Block | Required inner template |
> |---|---|
> | `surecart/product-review-summary` | `core/group` (flex) → `core/group` (flex vertical) → `surecart/product-review-average-rating-value` + `surecart/product-review-average-rating-stars` |
> | `surecart/product-price-chooser` | `surecart/product-price-choice-template` (flex space-between) → `surecart/price-name` + `core/group` (flex right) containing `surecart/price-scratch-amount` + `surecart/price-amount` + `surecart/price-interval` + `surecart/price-trial` + `surecart/price-setup-fee` |
> | `surecart/product-variant-pills` | `surecart/product-variant-pill` (template) |
> | `surecart/product-quantity` | `surecart/product-quantity-control` → `-input-decrease` + `-input` + `-input-increase` |
> | `surecart/product-list-related` | `surecart/product-template` (grid layout) containing the per-card structure (cover with quick-view-button + sale-badge, product-title, list-price + scratch-price) + `surecart/product-pagination` (with `-previous` + `-numbers` + `-next`) |
>
> See `examples/iphone-output.html` for the canonical expanded form of each.
>
> ### Rule 4 (CORRECTED v7.3 — REVERSED AGAIN) — `border-style:solid` IS emitted
>
> The v5.6 reversal that claimed "save() does NOT auto-inject `border-style:solid`" was based on a single non-representative paste test against a Bundle Store / classic theme that suppressed it. **Production patterns in `examples/patterns/` consistently emit `border-style:solid`** in BOTH JSON `style.border.style:"solid"` AND inline-style mirror `border-style:solid`. Verified at `examples/patterns/product-physical.example.md:55,75,87,94,99` and ~30 other occurrences across the corpus.
>
> ✅ Right (v7.3 canonical, from `product-physical.example.md:42`):
> ```html
> <div class="wp-block-group has-border-color has-ast-global-color-5-background-color has-background"
>      style="border-color:#dfe4ea;border-style:solid;border-width:1px;border-radius:4px;padding-top:50px;…">
> ```
>
> ❌ Wrong (v5.6 reversal — omits `border-style:solid`):
> ```html
> <div class="wp-block-group has-background"
>      style="border-color:#e6dccb;border-width:1px;border-radius:12px;…">
> ```
>
> When `border.color` or `border.width` is set, ALWAYS emit `style.border.style:"solid"` in JSON AND `border-style:solid` in inline-style mirror. (For dashed/dotted borders, swap `solid` with the design's actual `border-style` value.)
>
> ### Rule 5 (CORRECTED — REVERSED FROM v5.5) — `has-custom-font-size` IS emitted for literal font sizes
>
> Earlier rule: "has-custom-font-size is deprecated, never emitted." **Wrong.** When `style.typography.fontSize:"14px"` is set as a literal, save() adds `has-custom-font-size` to the wrapper class. Always pair them:
>
> ```html
> <a class="... has-custom-font-size wp-element-button" style="...font-size:14px;...">Label</a>
> ```
>
> ### Rule 6 (CORRECTED v7.3 — REVERSED AGAIN) — `core/details` MUST carry `summary` attr
>
> The v5.6 reversal that claimed "core/details does NOT need summary attr" was wrong and contradicted SKILL.md Hard Constraint #11 + rubric A-4 + the production patterns. **Production patterns ALWAYS emit `{"summary":"<text>"}` on `core/details`** matching the inner `<summary>` text. Without it, the round-trip parser re-serializes with `summary:""` and the block re-renders with literal `<summary>Details</summary>` (default), losing the merchant's text.
>
> ✅ Right (v7.3 canonical):
> ```html
> <!-- wp:details {"summary":"How long does shipping take?"} -->
> <details class="wp-block-details"><summary>How long does shipping take?</summary>
> <!-- wp:paragraph --><p>Orders ship same business day…</p><!-- /wp:paragraph -->
> </details>
> <!-- /wp:details -->
> ```
>
> ❌ Wrong (drops the summary attr):
> ```html
> <!-- wp:details -->
> <details class="wp-block-details"><summary>How long does shipping take?</summary>…</details>
> <!-- /wp:details -->
> ```
>
> The `<summary>` HTML element itself MUST be plain — no `class`, no `style` attr — per A-4 line 54. Style the summary text via theme CSS keyed off `.wp-block-details summary` instead.
>
> ### Rule 7 (v6.0 NARROWED) — Centered-eyebrow paragraph: className-only fallback
>
> **Scope (v6.0):** This rule applies ONLY to `core/paragraph` blocks that combine ALL of: `align:"center"` + uppercase text + letter-spacing 0.04em + brand color + font-size ≤ 14px (eyebrow pattern). For ALL OTHER `core/heading` and `core/paragraph` emissions, use full `style.typography.*` emission (see B-15 in `rubric/self-validate.md`).
>
> **Why narrowed:** the original v5.6 rule said "drop typography on every heading/paragraph" because of recovery damage. Empirically, that overreached — the recovery damage is specific to the `align:"center"` + small-eyebrow pattern, not generic typography emission. The narrowed rule keeps recovery-safety where it's needed and restores fidelity (font-weight, line-height, letter-spacing, font-size on hero titles, lead paragraphs, body copy) everywhere else.
>
> ✅ Centered eyebrow (Rule 7 applies — className-only):
> ```html
> <!-- wp:paragraph {"align":"center","className":"has-text-align-center has-surecart-brand-color has-text-color has-surecart-body-font-family"} -->
> <p class="has-text-align-center has-surecart-brand-color has-text-color has-surecart-body-font-family">FAQ</p>
> ```
>
> ✅ Hero title (NOT eyebrow — full typography emission):
> ```html
> <!-- wp:heading {"level":1,"textColor":"surecart-gray-900","style":{"color":{"text":"#111827"},"typography":{"fontSize":"56px","fontWeight":"700","lineHeight":"1.05","letterSpacing":"-0.03em"}},"fontFamily":"surecart-display"} -->
> <h1 class="wp-block-heading has-surecart-gray-900-color has-text-color has-surecart-display-font-family" style="color:#111827;font-size:56px;font-weight:700;line-height:1.05;letter-spacing:-0.03em">…</h1>
> ```
>
> ✅ Lead paragraph (NOT eyebrow — full typography emission):
> ```html
> <!-- wp:paragraph {"textColor":"surecart-gray-700","style":{"color":{"text":"#374151"},"typography":{"fontSize":"18px","lineHeight":"28px"}},"fontFamily":"surecart-body"} -->
> <p class="has-surecart-gray-700-color has-text-color has-surecart-body-font-family" style="color:#374151;font-size:18px;line-height:28px">Aerospace titanium…</p>
> ```
>
> Note D7 dual-emit applied above: `textColor` slug + `style.color.text` literal hex + inline `color:#…` mirror — leaf elements get both layers for classic-theme rendering safety.
>
> ### Rule 8 (NEW) — `core/media-text` image goes in ATTRS, not inner blocks
>
> `core/media-text`'s media slot is filled via attrs (`mediaUrl`, `mediaId`, `mediaType`), NOT inner blocks. Putting `<!-- wp:image -->` inside `<figure class="wp-block-media-text__media">` causes recovery to dump the image into the content area instead.
>
> ✅ Right structure for media-text:
> ```html
> <!-- wp:media-text {"align":"wide","mediaPosition":"right","verticalAlignment":"center"} -->
> <div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center">
>   <div class="wp-block-media-text__content">
>     <!-- ALL inner blocks go here, including the wp:image -->
>     <!-- wp:paragraph -->...<!-- /wp:paragraph -->
>     <!-- wp:image -->...<!-- /wp:image -->
>   </div>
>   <figure class="wp-block-media-text__media"></figure>  <!-- empty -->
> </div>
> <!-- /wp:media-text -->
> ```
>
> Or — better — set `mediaUrl` / `mediaId` / `mediaType` attrs on `wp:media-text` and the image renders in the media `<figure>` automatically.
>
> ### Rule 9 (CORRECTED) — `core/image` border-radius lives on `<img>`, NOT `<figure>`
>
> Plus `<figure>` gets a `has-custom-border` class.
>
> ❌ Wrong:
> ```html
> <figure class="wp-block-image size-large" style="border-radius:24px"><img src="" alt=""/></figure>
> ```
>
> ✅ Right:
> ```html
> <figure class="wp-block-image size-large has-custom-border"><img src="" alt="" style="border-radius:24px"/></figure>
> ```
>
> ### Rule 10 (NEW) — Centered eyebrow paragraphs: use `className`-only pattern
>
> The combination of `align:"center"` + `style.typography.*` on `core/paragraph` triggers recovery damage (output gets nested `<p><p>...</p></p>`).
>
> ✅ Recovery-stable:
> ```html
> <!-- wp:paragraph {"align":"center","className":"has-text-align-center has-surecart-brand-color has-text-color has-surecart-body-font-family"} -->
> <p class="has-text-align-center has-surecart-brand-color has-text-color has-surecart-body-font-family">FAQ</p>
> <!-- /wp:paragraph -->
> ```
>
> Drop `style.typography.*`. Put alignment + color + font-family classes in `className`. The eyebrow's small-text + uppercase + letter-spacing styling can come from the SureCart theme's `.sc-eyebrow` CSS class — add it to className if you want those styles, otherwise accept that they render at default size.
>
> ### Quick anti-pattern grep (run mentally before emit)
>
> The five rules below catch the most common drift in hand-emitted markup. Apply each every time the corresponding attr is set, no exceptions.
>
> ### Rule 1 (RESTORED v7.3) — `border-style:solid` IS mandatory when border.width / border.color is set
>
> The v5.6 reversal was wrong (see Rule 4 above v7.3 reversal). **Production patterns ALWAYS emit `border-style:solid` in both JSON `style.border.style:"solid"` and inline-style mirror.** `iphone-output.html`'s "no border-style:solid" pattern was a non-representative case that passed validation only on a specific theme stack — production-pattern reality is the universal canonical.
>
> ### Rule 2 — Layout double-class requirement (WP 6.1+) — TWO classes per block
>
> Every block with layout support emits **two** classes per layout type since WP 6.1:
>   - `is-layout-{type}` (the canonical flag)
>   - `wp-block-{block-name}-is-layout-{type}` (the block-specific scope)
>
> Both are required. Missing either triggers recovery.
>
> | Block + layout | Classes (BOTH required) |
> |---|---|
> | `core/group` (no layout attr — default) | `is-layout-flow wp-block-group-is-layout-flow` |
> | `core/group` `layout.type:"constrained"` | `is-layout-constrained wp-block-group-is-layout-constrained` |
> | `core/group` `layout.type:"flex"` | `is-layout-flex wp-block-group-is-layout-flex` |
> | `core/group` `layout.type:"grid"` | `is-layout-grid wp-block-group-is-layout-grid` |
> | `core/columns` (always, even with no layout attr) | `is-layout-flex wp-block-columns-is-layout-flex` |
> | `core/column` (always) | `is-layout-flow wp-block-column-is-layout-flow` |
> | `core/buttons` (always) | `is-layout-flex wp-block-buttons-is-layout-flex` |
>
> ❌ Wrong (recovery on every group/columns/column):
> ```html
> <div class="wp-block-group has-surecart-white-background-color has-background">
> <div class="wp-block-columns are-vertically-aligned-center">
> <div class="wp-block-column is-vertically-aligned-center">
> ```
>
> ✅ Right:
> ```html
> <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-white-background-color has-background">
> <div class="wp-block-columns are-vertically-aligned-center is-layout-flex wp-block-columns-is-layout-flex">
> <div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow">
> ```
>
> **Critical:** even when you don't emit a `"layout":{...}` attribute, `core/group` still emits `is-layout-flow wp-block-group-is-layout-flow` by default. Same for `core/columns` (always `is-layout-flex …`), `core/column` (always `is-layout-flow …`), `core/buttons` (always `is-layout-flex …`).
>
> ### Rule 3 — `core/media-text` needs `grid-template-columns` inline style
>
> `core/media-text` save() always emits `grid-template-columns` based on `mediaWidth` (default 50) and `mediaPosition` (default "left"):
>
> | `mediaPosition` | `mediaWidth` | Inline style |
> |---|---|---|
> | `"left"` (default) | `50` (default) | `grid-template-columns:50% auto` |
> | `"left"` | `30` | `grid-template-columns:30% auto` |
> | `"right"` | `50` | `grid-template-columns:auto 50%` |
> | `"right"` | `40` | `grid-template-columns:auto 40%` |
>
> Always include this inline style — recovery triggers without it.
>
> ### Rule 4 — `core/button` `<a>` needs `wp-element-button` class
>
> WP 6.1+ adds `wp-element-button` to the inner `<a>` (or `<button>`) of every `core/button`. Required.
>
> ✅ Right: `<a class="wp-block-button__link wp-element-button" href="...">Label</a>`
>
> Also: every `<a>` needs an `href` attribute. Without `href`, the parser falls back to `<button>` and triggers recovery.
>
> ### Rule 5 — `core/columns` and `core/media-text` stacking-class asymmetry (gotcha)
>
> The two blocks have **opposite** defaults — easy to flip:
>
> | Block | Default | When to emit |
> |---|---|---|
> | `core/columns` | `isStackedOnMobile:true` (no class on default) | only when `false` → `is-not-stacked-on-mobile` |
> | `core/media-text` | `isStackedOnMobile:true` (CLASS IS REQUIRED on default) | always emit `is-stacked-on-mobile` (default IS the class), only omit when `false` |
>
> ### Rule 6 — `verticalAlignment` class prefix is BLOCK-SPECIFIC
>
> | Block | When `verticalAlignment` set | Class on wrapper |
> |---|---|---|
> | `core/columns` (parent) | any value | `are-vertically-aligned-{value}` (plural `are-`) |
> | `core/column` (child) | any value | `is-vertically-aligned-{value}` (singular `is-`) |
> | `core/media-text` | any value | `is-vertically-aligned-{value}` (singular `is-`) |
>
> ### Rule 7 — `core/cover` overlay span is mandatory
>
> Whenever `core/cover` has `dimRatio` (default `50`) or any `overlayColor`/`gradient`, the wrapper MUST contain an inner `<span aria-hidden="true" class="wp-block-cover__background">…</span>` AND inner content goes in `<div class="wp-block-cover__inner-container">`. Plus default `dimRatio:50` always emits `has-background-dim` on the wrapper. Cover is class-heavy — see `reference/core-blocks-cheatsheet.md` if you use it.
>
> ### Rule 8 — `core/heading` `<h{level}>` always has `class="wp-block-heading"`
>
> Every `<h1>`/`<h2>`/`<h3>` etc. must include `wp-block-heading`. Plus `has-text-align-{x}` if `textAlign` set, `has-{slug}-color has-text-color` for color slug, etc.
>
> ### Rule 9 — `has-custom-font-size` is LITERAL-only
>
> | `fontSize` attr | Class on wrapper |
> |---|---|
> | `fontSize:"slug"` | `has-{slug}-font-size` (no `has-custom-font-size`) |
> | `style.typography.fontSize:"24px"` (literal) | `has-custom-font-size` (no slug class) |
>
> ### Rule 10a — Default-attribute traps (these defaults are ALWAYS in the HTML even when absent from the comment delimiter)
>
> Some block attributes have defaults that `save()` writes into the HTML literally. If your saved markup omits them, recovery triggers. Always emit these:
>
> | Block | Default attr | What `save()` emits regardless |
> |---|---|---|
> | `core/heading` | `level: 2` | `<h2>…</h2>` (always — even with no level in attrs) |
> | `core/list` | `ordered: false` | `<ul>` (always) |
> | `core/table` | `hasFixedLayout: true` | `<table class="has-fixed-layout">` (always — this class is the most-missed) |
> | `core/separator` | `opacity: "alpha-channel"` | `class="…has-alpha-channel-opacity"` always |
> | `core/spacer` | `height: "100px"` | `style="height:100px"` + `aria-hidden="true"` always |
> | `core/details` | `summary: ""` | renders literal `<summary>Details</summary>` (NOT empty `<summary></summary>`) |
> | `core/image` | `alt: ""` | `alt=""` always present (don't omit the attribute) |
>
> ### Rule 10b — `core/table` structure: `<figure>` wraps `<table>`; classes are split
>
> ```html
> <figure class="wp-block-table is-style-stripes">
>   <table class="has-fixed-layout">  <!-- has-fixed-layout is mandatory (default true) -->
>     <tbody>
>       <tr><td class="has-text-align-left" data-align="left">…</td></tr>
>     </tbody>
>   </table>
>   <figcaption class="wp-element-caption">caption text</figcaption>  <!-- only if caption set -->
> </figure>
> ```
>
> - `wp-block-table` → on `<figure>` (NOT `<table>`)
> - `has-fixed-layout` → on `<table>` (always — default true)
> - Color slug classes (`has-X-background-color`, `has-background`) → on `<table>` (table uses `__experimentalSkipSerialization` for color/border, then re-applies to `<table>`)
> - `is-style-stripes`/`is-style-regular` → on `<figure>` via `className`
> - Cell alignment: BOTH `class="has-text-align-{value}"` AND `data-align="{value}"` on each `<td>`/`<th>` (parser reads `data-align` to round-trip)
> - Caption: `<figcaption class="wp-element-caption">` INSIDE `<figure>` (NEVER `<caption>` inside `<table>`)
>
> ### Rule 10c — Whitespace inside single-block tags is zero
>
> Inside a single block's HTML tags, `save()` outputs **zero whitespace**. Don't introduce newlines or spaces inside `<table>`, `<img>`, `<h{level}>`, `<p>`, `<hr>`, `<div class="wp-block-spacer">`. The newlines you see between blocks come from `\n\n` separators between sibling InnerBlocks (e.g., inside `<details>`, `<blockquote>`, `<ul>`).
>
> Emit children inline within their wrapper element. Don't pretty-print individual block markup.
>
> ### Rule 10 — `has-background` accompanies BOTH slug and literal background
>
> | Background attr | Wrapper class |
> |---|---|
> | `backgroundColor:"slug"` | `has-{slug}-background-color has-background` |
> | `style.color.background:"#hex"` | `has-background` (no slug class) + inline `background-color:#hex` |
> | `gradient:"slug"` | `has-{slug}-gradient-background has-background` (NOT `has-gradient-background` — that's `core/cover`-only) |
> | `style.color.gradient:"linear-gradient(...)"` | `has-background` + inline `background:linear-gradient(...)` |
>
> ### Quick anti-pattern grep (run mentally before emit)
>
> Search for these in your candidate output. Each match is a guaranteed recovery prompt:
>
> ```
> border-width  WITHOUT border-style:solid emitted              → Rule 4 v7.3 (RE-REVERSED — DO emit border-style:solid)
> wp-block-group  but no is-layout-* AND wp-block-group-is-layout-*    → Rule 2
> wp-block-columns  but no is-layout-flex wp-block-columns-is-layout-flex  → Rule 2
> wp-block-column  but no is-layout-flow wp-block-column-is-layout-flow    → Rule 2
> wp-block-buttons  but no is-layout-flex wp-block-buttons-is-layout-flex  → Rule 2
> wp-block-media-text  but no grid-template-columns inline style          → Rule 3
> wp-block-media-text  but no is-stacked-on-mobile (default ON)           → Rule 5
> wp-block-button__link  with no wp-element-button                        → Rule 4
> wp-block-button__link  with no href=                                    → Rule 4
> wp-block-columns with verticalAlignment but no are-vertically-aligned-* → Rule 6
> wp-block-column with verticalAlignment but no is-vertically-aligned-*   → Rule 6 (singular!)
> <h2> without class="wp-block-heading"                                   → Rule 8
> fontSize:"slug" with has-custom-font-size (wrong)                       → Rule 9
> backgroundColor:"slug" with no has-background companion                 → Rule 10
> ```
>
> ---



Two output forms:

1. **Slug attrs** (preferred) — when a token from `reference/theme-partial.json` matches. Renders as `class="has-{slug}-{kind}"`.
2. **Literal `style.*`** — when no slug match. Renders as inline `style="…"` on the wrapper.

For SureCart blocks with `__experimentalSkipSerialization` (e.g., `surecart/product-buy-button`), write the attrs but **DO NOT** inject `class=""` or `style=""` on the wrapper — the block's server render handles styling. See `reference/product-page-blocks.md`.

---

## Color

JSX:
```jsx
color: "var(--brand)"           // → token in theme-partial.json
color: "#01824C"                // → no token match
backgroundColor: "var(--bg-dark)"
backgroundColor: "#042F2E"
```

Output:
```json
"textColor":"surecart-brand"
"style":{"color":{"text":"#01824C"}}
"backgroundColor":"surecart-bg-dark"
"style":{"color":{"background":"#042F2E"}}
```

When using slug attrs, **also** add the matching class to the wrapper element:
- `textColor:"surecart-brand"` → wrapper class `"has-surecart-brand-color has-text-color"`
- `backgroundColor:"surecart-bg-dark"` → wrapper class `"has-surecart-bg-dark-background-color has-background"`

Example:
```html
<!-- wp:group {"backgroundColor":"surecart-bg-dark","textColor":"surecart-white"} -->
<div class="wp-block-group has-surecart-white-color has-surecart-bg-dark-background-color has-text-color has-background">…</div>
<!-- /wp:group -->
```

### Explicit-per-block visual contract (v6.0 hard rule)

**Every visible block MUST carry its own explicit declaration for EVERY visual property the design exhibits — including 0 values.** No inheritance assumptions. No "the theme will handle it." No "the parent set it." No implicit defaults. **If the design has it, the block declares it. If the design has 0, the block declares 0.**

**The 8 property categories every block declares:**

| Category | Properties to emit (when design has them, including 0) | Where it goes |
|---|---|---|
| **Typography** | `fontSize`, `fontWeight`, `lineHeight`, `letterSpacing`, `textTransform`, `textDecoration`, `fontStyle` | `style.typography.*` + inline mirror on inner element |
| **Font family** | `fontFamily` slug | block attr + class on wrapper |
| **Spacing** | `padding.{top,right,bottom,left}`, `margin.{top,right,bottom,left}`, `blockGap` — emit even when value is `"0px"` | `style.spacing.*` + inline mirror per Rule 0 carve-out |
| **Dimensions** | `width`, `height`, `aspectRatio`, `layout.contentSize`, `layout.wideSize`, `layout.flexSize` | block attr (no inline) |
| **Color (text)** | slug attr + literal hex (D7 dual-emit on leaves) | `textColor` + `style.color.text` + inline `color:` |
| **Color (background)** | slug attr + literal hex (D7 dual-emit on leaves; slug-only on paired wrappers) | `backgroundColor` + `style.color.background` + inline `background-color:` |
| **Border** | `radius`, `width`, `color` (NOT `style:solid` — Rule 4) — emit even when radius is `"0px"` if design's at 0 | `style.border.*` + inline mirror |
| **Layout** | `type`, `orientation`, `flexWrap`, `justifyContent`, `verticalAlignment` | `layout.*` |

**The "even when 0" mandate.** If the design has `padding-top: 0` on a card, emit `"padding":{"top":"0px"}` explicitly. If the design has `border-radius: 0` on an image, emit `"border":{"radius":"0px"}`. If the design has `marginBottom: 0` on a paragraph, emit it. **The skill must NEVER omit a property that the design declares — even at 0 — because omission means "use the theme/parent default" which is unpredictable across themes.**

**Why this rule exists** (v6.0 Phase 4 + iteration 2 empirical findings, 2026-05-04):
1. **Price-chooser bug:** `fontSize:44px` on parent → all 6 price children inherited 44px. Design wanted price-name=16px, price-amount=44px, price-interval=16px, price-trial=14px, price-setup-fee=14px. Fix: per-child typography, NOT parent.
2. **Body paragraph weight bug:** body paragraphs without `fontWeight:"400"` inherited theme's `font-weight: 300`. Fix: emit `400` explicitly even though it's the CSS default — themes override defaults.
3. **Card chrome inheritance failure:** highlights cards using `className="has-border-color"` only had `border-color: rgb(17,17,17)` on Bundle Store theme (not the design's `#E5E7EB`). Fix: literal-bg variant with full inline border declarations.
4. **Section padding gap:** sections with slug `backgroundColor` only had `padding: 0` in editor on classic themes. Fix: explicit `style.spacing.padding` JSON on every section, not relying on theme.

**Container blocks (parents that own layout, not visual style of children):**

The following blocks MUST own ONLY layout + spacing attrs. They MUST NOT carry typography, color, or font-family attrs — those go on each child individually:

- `surecart/product-price-chooser` — children: `price-name`, `price-amount`, `price-interval`, `price-trial`, `price-setup-fee`, `price-scratch-amount`
- `surecart/product-quantity` — children: `product-quantity-input-decrease`, `-input`, `-input-increase`
- `surecart/product-buy-buttons` — children: each `surecart/product-buy-button`
- `surecart/product-review-summary` — children: `product-review-average-rating-value`, `-stars`
- `surecart/product-list-related` → `surecart/product-template` — children: per-card composition
- `core/columns` — children: `core/column` siblings (each can have their own width)
- `core/group` (paired wrappers per Rule 0) — children own typography/color; wrapper owns layout/spacing/background-only

**Leaf blocks (the ones that render visible content):**

For every leaf block, regardless of size, emit ALL applicable properties from the 8 categories. Even tiny labels like `surecart/cart-count`, `surecart/product-sale-badge`, `surecart/product-quick-view-button`, `surecart/price-trial`, individual `core/list-item` content blocks, individual buy buttons, etc. must each declare their own properties. **Smallness does not justify omission.**

Examples of leaves that MUST get explicit per-block properties:
- `core/heading` (each h1/h2/h3/h4/h5/h6) — full typography + color + (optional) margin
- `core/paragraph` — full typography + color + (optional) margin
- `core/list` — typography + color (applies to wrapper which children inherit, with explicit margin on the list)
- `core/button` — typography + color + bg + border on the inner element via the block's own attrs
- `core/image` — width, height, aspectRatio (when design fixes them), border-radius, alignment
- `core/spacer` — height (always — design's exact px)
- All `surecart/price-*` children — full typography + color + fontFamily
- `surecart/product-buy-button` — full typography + (chrome via parent group when design needs it)
- `surecart/product-collection-tag`, `surecart/product-sale-badge`, `surecart/cart-count` — typography + color + spacing + (optional border)

**Anti-pattern (v6.0 fixture bug, fixed 2026-05-04):**
```html
<!-- ❌ WRONG: only chooser typography; price-name / price-interval inherit 44px from parent -->
<!-- wp:surecart/product-price-chooser {"style":{"typography":{"fontSize":"44px","fontWeight":"700"}}} -->
  <!-- wp:surecart/price-name /-->                  <!-- no typography → inherits 44px from parent — wrong -->
  <!-- wp:surecart/price-amount {"style":{"typography":{"fontWeight":"700"}}} /-->  <!-- inherits parent fontSize -->
  <!-- wp:surecart/price-interval /-->              <!-- inherits 44px — wrong, design wants 16px -->
<!-- /wp:surecart/product-price-chooser -->
```

```html
<!-- ✅ RIGHT: chooser owns spacing only; each child owns full typography + color + fontFamily -->
<!-- wp:surecart/product-price-chooser {"label":"Storage","columns":3,"style":{"spacing":{"margin":{"bottom":"32px"}}}} -->
  <!-- wp:surecart/price-name {"style":{"typography":{"fontSize":"16px","fontWeight":"500","lineHeight":"24px"},"color":{"text":"#374151"}},"fontFamily":"surecart-body"} /-->
  <!-- wp:surecart/price-scratch-amount {"style":{"typography":{"fontSize":"16px","fontWeight":"500","textDecoration":"line-through"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
  <!-- wp:surecart/price-amount {"style":{"typography":{"fontSize":"44px","fontWeight":"700","letterSpacing":"-0.03em","lineHeight":"1.1"},"color":{"text":"#111827"}},"fontFamily":"surecart-display"} /-->
  <!-- wp:surecart/price-interval {"style":{"typography":{"fontSize":"16px","fontWeight":"400","lineHeight":"24px"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
  <!-- wp:surecart/price-trial {"style":{"typography":{"fontSize":"14px","fontWeight":"400","lineHeight":"20px"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
  <!-- wp:surecart/price-setup-fee {"style":{"typography":{"fontSize":"14px","fontWeight":"400","lineHeight":"20px"},"color":{"text":"#6B7280"}},"fontFamily":"surecart-body"} /-->
<!-- /wp:surecart/product-price-chooser -->
```

**Anti-pattern (zero-value omission):**
```html
<!-- ❌ WRONG: card with no top padding — relies on default -->
<!-- wp:group {"style":{"color":{"background":"#FFFFFF"},"spacing":{"padding":{"bottom":"28px","left":"28px","right":"28px"}}}} -->
<div class="..."  style="background-color:#FFFFFF;padding-bottom:28px;padding-right:28px;padding-left:28px">
```

```html
<!-- ✅ RIGHT: emit padding-top:"0px" explicitly when design has 0 -->
<!-- wp:group {"style":{"color":{"background":"#FFFFFF"},"spacing":{"padding":{"top":"0px","bottom":"28px","left":"28px","right":"28px"}}}} -->
<div class="..." style="background-color:#FFFFFF;padding-top:0px;padding-right:28px;padding-bottom:28px;padding-left:28px">
```

**Bottom line:** every block declares everything that's in the design — even 0. Inheritance and theme defaults are paste-time fragility. Explicit per-block declaration is paste-portable.

---

### No-source-no-emit rule (background)

**A background is emitted ONLY when the source explicitly declares one.** This is rubric check **B-13**.

A "source declaration" means one of:
- An inline JSX `style={{ background: "...", backgroundColor: "..." }}` on the element
- A CSS rule for the element's `className` that sets `background`, `background-color`, or `background-image`
- A CSS-var token like `var(--bg-1)` referenced by either of the above

If none of these exist on a given element, the element has **no** background. Do NOT manufacture a literal hex (`#aabbcc`, `#f7f9fb`, etc.) for visual prettiness — default-to-transparent is correct, and Gutenberg/the active theme renders the page background through.

**Why this rule exists:** before v6.0 the skill emitted hallucinated card-style backgrounds on review-summary boxes, hero sub-groups, and other "container-looking" wrappers whose source had no actual background. Root cause: the skill was pattern-matching its own worked examples (which used `#f7f9fb` as the canonical literal-hex demo) into output. The fix: scrubbed examples + this rule + B-13 rubric check.

**Applies to:** every block that supports `backgroundColor` / `style.color.background` — `core/group`, `core/cover`, `core/columns`, `core/column`, `core/heading`, `core/paragraph`, `core/button`, `surecart/*` text blocks. Same rule for gradients (`gradient` slug, `style.color.gradient` literal).

### Classic-theme dual-emit rule (D7) — leaf elements only

**Why:** ~50% of WP installs run classic themes that don't enqueue `wp-block-library` global styles. On those sites, `has-surecart-*-color` slug classes do NOT actually apply, even when `--wp--preset--color--surecart-*` CSS vars are present on the page — because the per-slug class rules (`has-surecart-gray-900-color { color: var(--wp--preset--color--surecart-gray-900) }`) are only injected by FSE theme.json processing, which classic themes skip.

**Empirical proof (Hello Elementor classic theme + SureCart, 2026-05-04):**

| Test leaf | Computed color (classic theme) |
|---|---|
| `<p class="has-surecart-gray-900-color has-text-color">` (slug only) | `rgb(51, 51, 51)` — host theme default gray, slug ignored |
| `<p class="has-surecart-gray-900-color has-text-color" style="color:#111827">` (slug + inline D7) | `rgb(17, 24, 39)` = #111827 — inline wins, design intent preserved |
| `<p class="has-surecart-brand-color has-text-color">` (slug only) | `rgb(51, 51, 51)` — slug ignored, eyebrow renders as default gray |
| `<p class="has-surecart-brand-color has-text-color" style="color:#01824C">` (slug + inline D7) | `rgb(1, 130, 76)` = #01824C — brand color renders correctly |
| `<p class="has-surecart-white-background-color has-background">` (slug only) | bg `rgba(0, 0, 0, 0)` — transparent (slug ignored) |

**Verdict:** Without D7 dual-emit, every leaf text and CTA on a classic-theme install renders as default theme color. With D7, the inline literal hex wins regardless of theme. **Non-negotiable on leaves.**

**The fix is dual-emit on leaf elements only.**

For every **leaf element** carrying a `textColor` or `backgroundColor` slug, ALSO emit the literal hex inline. The slug class wins when global styles are enqueued (FSE / classic-with-block-styles); the inline literal wins when they aren't.

**Leaf elements** (D7 applies):
- `core/heading`, `core/paragraph`, `core/button`
- All `surecart/*` text/CTA blocks: `product-title`, `product-description`, `product-buy-button`, `product-price-chooser`, `price-name`/`price-amount`/`price-interval`, `product-quantity`, `product-variant-pills`, `cart-count`

**Paired-block wrappers EXCLUDED** (D7 does NOT apply):
- `core/group`, `core/columns`, `core/column`, `core/cover`, `core/media-text`, `core/buttons`, `core/list`, `core/quote`, `core/details`

These wrappers stay slug-only — Rule 0 / v5.7 paste-safety forbids inline color on them. Wrapper backgrounds remain theme-dependent. A merchant on a classic-no-global-styles theme whose section bgs render transparent must either (a) activate a theme that loads SureCart's theme partial, or (b) accept that wrapper bgs render unstyled. There is no safe leaf-only fix for wrapper bgs.

**Resolving the literal hex:** look up `reference/token-aliases.json` `color.{var}.hex`. Example: `textColor:"surecart-brand"` → look up `--brand` → hex `#01824C`. Slug not in `token-aliases.json`? Either it's a hallucinated slug (verify against `theme-partial.json`) or the alias map needs a new entry — log under `dropped_features` until added.

**Editor-preview vs front-end rendering** (v6.0 Phase 4 empirical finding, 2026-05-04): when D7 emits inline `style="color:#FFFFFF"` on a `core/heading` or `core/paragraph`, Gutenberg's RichText edit() component **strips the inline color attr** in the editor canvas and replaces it with editor-internal styles (`white-space: pre-wrap; min-width: 1px; …` plus other typography). The merchant viewing the editor will see theme-default colors, NOT the literal hex.

**The inline color IS preserved in `save()` output** — the static HTML stored in `post_content` keeps the inline `style="color:..."` declaration. Front-end rendering uses save() output, so D7's classic-theme fix works on the live page even though it doesn't show in the editor preview.

Tell merchants: "If editor preview shows your CTA section as transparent / dark text on light, that's the editor inheriting from theme defaults. Click Preview → External Preview to verify front-end rendering, which respects the inline color and shows the design correctly."

**Worked examples.**

Plain heading with brand color (D7 applied):
```html
<!-- wp:heading {"textColor":"surecart-brand","style":{"color":{"text":"#01824C"}},"fontFamily":"surecart-display"} -->
<h2 class="wp-block-heading has-surecart-brand-color has-text-color has-surecart-display-font-family" style="color:#01824C">Section heading</h2>
<!-- /wp:heading -->
```

Buy-button with brand color (D7 applied — and per B-16, `surecart/product-buy-button` is a leaf):
```html
<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add to Bag","textColor":"surecart-white","backgroundColor":"surecart-brand","fontFamily":"surecart-display","style":{"color":{"text":"#FFFFFF","background":"#01824C"},"typography":{"fontWeight":"700"}}} /-->
```
Note: `surecart/product-buy-button` skips serialization on color, so no class/inline lands on the wrapper from those slug attrs — but the JSON literals reach the server-render where they apply server-side. (Typography is NOT skipSerialization, so `fontFamily` slug class DOES land — see B-16.)

Paired wrapper, slug-only (D7 NOT applied — Rule 0 protects):
```html
<!-- wp:group {"backgroundColor":"surecart-brand-deep","textColor":"surecart-white","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained has-surecart-white-color has-surecart-brand-deep-background-color has-text-color has-background">…</div>
<!-- /wp:group -->
```

Inside the wrapper, the leaf children (heading, paragraph, button) DO get D7 dual-emit on their own colors — typically the same colors inherited from the wrapper, but each leaf carries them explicitly so they survive on classic-no-global-styles themes.

---

## Spacing (padding, margin, gap)

| JSX | Output |
|---|---|
| `padding: "var(--space-8)"` (preset slug) | `"style":{"spacing":{"padding":{"top":"var:preset\|spacing\|surecart-8","right":"var:preset\|spacing\|surecart-8","bottom":"var:preset\|spacing\|surecart-8","left":"var:preset\|spacing\|surecart-8"}}}` |
| `padding: 32` (number, no unit) | `"style":{"spacing":{"padding":{"top":"32px","right":"32px","bottom":"32px","left":"32px"}}}` |
| `padding: "40px 60px"` | expand to `{top:"40px",right:"60px",bottom:"40px",left:"60px"}` (TRBL) |
| `padding: "10px 20px 30px 40px"` | `{top:"10px",right:"20px",bottom:"30px",left:"40px"}` |
| `paddingTop: 24` | `"style":{"spacing":{"padding":{"top":"24px"}}}` (only the side that's set) |
| `gap: 60` (on a flex/grid container) | parent's `"style":{"spacing":{"blockGap":"60px"}}` |
| `gap: "20px 60px"` (row gap, col gap) | `"style":{"spacing":{"blockGap":{"top":"20px","left":"60px"}}}` |
| `marginBottom: 24` | `"style":{"spacing":{"margin":{"bottom":"24px"}}}` |
| `margin: "0 auto"` | typically a layout hint — use `"layout":{"type":"constrained"}` on parent and omit literal margin |

When you write `style.spacing.padding`, **also** mirror to inline `style="padding-top:…;padding-right:…;…"` on the wrapper element.

### Preserve `marginBottom` / `marginTop` values 1:1 — DO NOT round

JSX uses very specific margin values for vertical rhythm: 12, 14, 16, 20, 24, 28, 32, 48, 56, 64. The designer chose 28 over 32 (or 14 over 16) deliberately. **Emit each value exactly as written.** Do NOT round to nearest 8px or nearest "round" multiple.

❌ Wrong: `marginBottom: 28` → `"margin":{"bottom":"32px"}`
✅ Right: `marginBottom: 28` → `"margin":{"bottom":"28px"}`

### Section padding canonical pattern

Claude Designs use a recurring two-layer padding pattern:
- The outer `<section>` carries vertical-only padding (`padding: "96px 0"` or asymmetric `"48px 0 96px"`).
- An inner wrapper `<div>` carries horizontal gutter padding (`padding: "0 32px"`).

**Merge both into ONE `core/group`** with all four sides explicit:

```
JSX outer:  padding: "96px 0"
JSX inner:  padding: "0 32px"
            ↓
Output:     "padding":{"top":"96px","right":"32px","bottom":"96px","left":"32px"}
```

For asymmetric vertical:
```
JSX outer:  padding: "48px 0 96px"  (top:48, bottom:96)
JSX inner:  padding: "0 32px"
            ↓
Output:     "padding":{"top":"48px","right":"32px","bottom":"96px","left":"32px"}
```

**Default gutter is 32px**, not 24px. Do NOT collapse the design's gutter to a smaller value.

### `maxWidth: N` → `layout.contentSize:"{N}px"` literally

When JSX has `maxWidth: 1240` on an inner wrapper, emit `"layout":{"type":"constrained","contentSize":"1240px"}` — verbatim. Do NOT snap to 1200 (or any "round" multiple). The design's grid is precisely tuned; rounding breaks the layout.

| JSX `maxWidth` | Output `contentSize` |
|---|---|
| `1240` | `"1240px"` |
| `1040` | `"1040px"` |
| `880`  | `"880px"`  |
| `720`  | `"720px"`  |

---

## Typography

| JSX | Output |
|---|---|
| `fontSize: 56` | `"style":{"typography":{"fontSize":"56px"}}` |
| `fontSize: "56px"` | same as above |
| `fontSize: "var(--text-display)"` (slug match) | `"fontSize":"surecart-display"` |
| `fontFamily: "var(--font-display)"` | `"fontFamily":"surecart-display"` |
| `fontFamily: "Inter, sans-serif"` (no slug) | `"style":{"typography":{"fontFamily":"Inter, sans-serif"}}` |
| `fontWeight: 700` | `"style":{"typography":{"fontWeight":"700"}}` (string!) |
| `fontStyle: "italic"` | `"style":{"typography":{"fontStyle":"italic"}}` |
| `lineHeight: 1.1` | `"style":{"typography":{"lineHeight":"1.1"}}` (string!) |
| `letterSpacing: "-0.02em"` | `"style":{"typography":{"letterSpacing":"-0.02em"}}` |
| `textTransform: "uppercase"` | `"style":{"typography":{"textTransform":"uppercase"}}` |
| `textDecoration: "underline"` | `"style":{"typography":{"textDecoration":"underline"}}` |
| `textAlign: "center"` | `"textAlign":"center"` (top-level attr, not nested under style) |
| `textWrap: "balance"` | `"style":{"typography":{"textWrap":"balance"}}` |

`fontWeight`, `lineHeight` are stored as **strings** in Gutenberg attrs, even when they look like numbers in JSX.

### `fontFamily` is mandatory for every text-bearing block

**This is the #1 visual-fidelity gap when missed.** Every heading and body paragraph in your output MUST carry a `fontFamily` slug AND its matching wrapper class. Without it, headings render in the active theme's default font (e.g. system sans-serif), not the design's chosen typeface.

Map by source CSS class or visual role:

| Source signal | `fontFamily` slug | Wrapper class to add |
|---|---|---|
| `.sc-h1`, `.sc-h2`, `.sc-h3`, `.sc-h4`, `.sc-display-*`, any heading using `--font-display` | `surecart-display` | `has-surecart-display-font-family` |
| `.sc-lead`, `.sc-body`, `.sc-small`, `.sc-xs`, `.sc-eyebrow`, `.sc-quote`, body paragraphs, list text | `surecart-body` | `has-surecart-body-font-family` |
| `.sc-eyebrow-mono`, `.sc-promo-*`, `.sc-code`, mono-styled labels | `surecart-mono` | `has-surecart-mono-font-family` |

Example:
```html
<!-- wp:heading {"level":2,"fontFamily":"surecart-display","style":{"typography":{"fontSize":"48px","fontWeight":"700"}}} -->
<h2 class="wp-block-heading has-surecart-display-font-family" style="font-size:48px;font-weight:700">Built different</h2>
<!-- /wp:heading -->
```

Even when the design's CSS doesn't explicitly call out a font-family for a paragraph (because the body's `font-family` cascades), emit `fontFamily:"surecart-body"` on every text block. Better to over-tag than to silently render in the wrong font.

---

## Border

| JSX | Output |
|---|---|
| `borderRadius: 8` | `"style":{"border":{"radius":"8px"}}` |
| `borderRadius: "8px 0"` | `"style":{"border":{"radius":{"topLeft":"8px","topRight":"0px","bottomRight":"8px","bottomLeft":"0px"}}}` |
| `border: "1px solid #ddd"` | `"style":{"border":{"width":"1px","style":"solid","color":"#ddd"}}` |
| `borderTop: "2px solid #01824C"` | `"style":{"border":{"top":{"width":"2px","style":"solid","color":"#01824C"}}}` |
| `borderColor: "var(--border-1)"` (slug) | `"borderColor":"surecart-gray-200"` (if slug exists in palette) |

### Border-color emission policy (v7.6 — REVERSES v7.3; class ALWAYS emitted when `style.border.color` is set)

**Reversal source:** Aurora Smart Desk Lamp paste-test (2026-05-11). The v7.3 rule "OMIT `has-border-color` for literal-hex" was empirically wrong. Gutenberg's `save()` function always emits the `has-border-color` class when the JSON has `style.border.color` — regardless of whether the value is a slug or literal-hex. Omitting the class triggers `"Block validation: Expected attribute class of value `wp-block-group has-border-color has-background`, saw `wp-block-group has-background`"` recovery on every card.

**Why the v7.3 reasoning was wrong.** The Aether Notebook hypothesis was that `has-border-color` delegates to `--wp--custom--color--border`, which themes may leave undefined → theme `border-color: currentColor` overrides the inline mirror. This is false: CSS specificity rules say **inline styles always win over class-based rules**, so `style="border-color:#hex"` on the wrapper takes precedence regardless of what any theme class chain resolves to. The Aether Notebook's apparent "black border" likely had a different cause (e.g., the `has-border-color` class being applied without an inline `border-color` mirror, which the v7.3 reversal then "fixed" by accident).

**Rule (v7.6 canonical):**
- `borderColor:"<slug>"` JSON attr → emit `has-border-color has-{slug}-border-color` class. Both classes required.
- `style.border.color:"#hex"` literal → emit `has-border-color` class AND inline-mirror: `style="border-color:#hex;border-style:solid;border-width:Npx;..."`. The inline mirror wins via CSS specificity.

**Always emit `style.border.style:"solid"`** in JSON AND `border-style:solid` in inline-style mirror when `border.color` or `border.width` is set. Production patterns at `examples/patterns/product-physical.example.md:55,75,87,94,99` confirm this is canonical (Rule 4 v7.3 reversal of the v5.6 reversal — unchanged).

✅ Right (literal hex border, v7.6 canonical):
```html
<!-- wp:group {"style":{"color":{"background":"#FBF9F5"},"border":{"color":"#F0EBE0","width":"1px","style":"solid","radius":"12px"},"spacing":{"padding":{"top":"28px","right":"28px","bottom":"28px","left":"28px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-border-color has-background"
     style="border-color:#F0EBE0;border-style:solid;border-width:1px;border-radius:12px;background-color:#FBF9F5;padding-top:28px;padding-right:28px;padding-bottom:28px;padding-left:28px">
```

Note: BOTH `has-border-color` AND `has-background` are present. They follow the SAME emission rule (v7.6 unification):
- `has-background` is emitted for both slug AND literal bg (it's a generic "this element has a background" marker).
- `has-border-color` is emitted for both slug AND literal border-color (same generic marker — v7.6 reversal).

❌ Wrong (literal hex border WITHOUT `has-border-color`, the v7.3 prescription):
```html
<div class="wp-block-group has-background"
     style="border-color:#F0EBE0;...">  <!-- triggers "Expected `has-border-color`, saw …" recovery -->
```

**Per-side asymmetric borders DO NOT get `has-border-color`.** When `style.border` uses the per-side form (`{top:{...},bottom:{...},left:[],right:[]}`) without a top-level `color`, `save()` does not emit `has-border-color`. Only the per-side inline rules (`border-bottom-color:#hex;border-bottom-width:1px`) appear. Production confirmation: `product-physical.example.md:135` (border-bottom only, no `has-border-color`).

---

## Gradients (v7.0 — `core/cover` only emits; everything else drops + logs)

Gutenberg has gradient support **only on `core/cover`** (via `gradient` slug attr or `style.color.gradient` literal). No other block exposes a gradient attr in the public schema. Linear/radial gradients on `core/group`, `core/buttons`, etc. are silently lost unless the merchant adds theme CSS.

### Linear gradient on `core/cover` background

Source:
```jsx
<section style={{ background: "linear-gradient(135deg, #01824C 0%, #042F2E 100%)" }}>
```

If the gradient matches a slug in `theme-partial.json` `color.gradients[]`:
```html
<!-- wp:cover {"gradient":"surecart-brand-fade","minHeight":480} -->
<div class="wp-block-cover has-surecart-brand-fade-gradient-background has-background-gradient">
  …content…
</div>
<!-- /wp:cover -->
```

If no slug match — emit literal:
```html
<!-- wp:cover {"customGradient":"linear-gradient(135deg, #01824C 0%, #042F2E 100%)","minHeight":480} -->
<div class="wp-block-cover has-background-gradient" style="background:linear-gradient(135deg, #01824C 0%, #042F2E 100%)">
  …content…
</div>
<!-- /wp:cover -->
```

### Radial gradient on `core/cover`

Same pattern; Gutenberg accepts arbitrary CSS gradient strings in `customGradient`:
```html
<!-- wp:cover {"customGradient":"radial-gradient(circle at 30% 20%, #ECFDF5 0%, #FFFFFF 100%)"} -->
```

### Gradient on any non-cover block — drop + log

For `core/group`, `core/buttons`, `core/column`, etc.:

```jsx
<div style={{ background: "linear-gradient(180deg, #FFFFFF 0%, #F3F4F6 100%)" }}>
```

→ Resolve to first stop's solid color as visual fallback (keeps the page from looking broken):
```html
<!-- wp:group {"backgroundColor":"surecart-white"} -->
<div class="wp-block-group has-surecart-white-background-color has-background">…</div>
<!-- /wp:group -->
```

→ Log the loss with a CSS escape hatch:
```json
"dropped_features": [
  {
    "type": "gradient",
    "mode": "linear",
    "section": "Hero",
    "detail": "linear-gradient(180deg, #FFFFFF 0%, #F3F4F6 100%)",
    "fallback_emitted": "surecart-white (first stop)",
    "merchant_action": "Add `background: linear-gradient(180deg, #FFFFFF 0%, #F3F4F6 100%)` to .wp-block-group.{section-class} via theme stylesheet"
  }
]
```

**Never emit a gradient via inline `style="background:linear-gradient(...)"`** on a non-cover block — Gutenberg's deserializer doesn't round-trip arbitrary background CSS on `core/group`/`core/columns` and the merchant gets a recovery prompt on first save.

### `conic-gradient(...)`, `color-mix(...)` in gradient stops

→ Drop + log. Resolve compute the average / first-stop fallback for visual continuity.

---

## Shadow

```jsx
boxShadow: "0 4px 16px rgba(0,0,0,0.1)"
```
```json
"style":{"shadow":"0 4px 16px rgba(0,0,0,0.1)"}
```

Multi-layer / complex shadows: pick the most prominent layer; emit single `style.shadow`. Log "shadow_simplified" under drift.

---

## Layout (`display`, `flexDirection`, etc.)

This goes on the parent block's `layout` attribute, not in `style`:

| JSX | Output |
|---|---|
| `display: "grid"; gridTemplateColumns: "1fr 1fr"` | parent is `core/columns` with 2 `core/column`; do NOT emit `layout` directly |
| `display: "grid"; gridTemplateColumns: "repeat(N, 1fr)"` | parent is `core/columns` with N `core/column` |
| `display: "flex"; flexDirection: "row"` | parent's `"layout":{"type":"flex","flexWrap":"nowrap"}` |
| `display: "flex"; justifyContent: "center"` | `"layout":{"type":"flex","justifyContent":"center"}` |
| `display: "flex"; flexDirection: "column"` | parent's `"layout":{"type":"constrained"}` (most common) — or use a regular vertical `core/group` |
| (no `display` on a `<section>`) | `"layout":{"type":"constrained"}` |

---

## Position, transform, transitions

### `position: sticky` (v6.0 — emit via Gutenberg layout API)

Sticky headers, sticky filter sidebars, and sticky add-to-bag bars are common product-page patterns. Gutenberg supports them via `style.position` on `core/group` (and `core/column` for sidebars).

JSX:
```jsx
<header style={{ position: "sticky", top: 0, zIndex: 40, background: "rgba(255,255,255,0.92)" }}>
```

Output:
```html
<!-- wp:group {"style":{"position":{"type":"sticky","top":"0px"},"color":{"background":"#FFFFFF"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-position-sticky" style="position:sticky;top:0px;background-color:#FFFFFF;z-index:40">
  …header content…
</div>
<!-- /wp:group -->
```

Rules:
- Emit `style.position.type:"sticky"` and `style.position.top:"0px"` (or whatever the design specifies, e.g., `"60px"` for sticky-below-header)
- Add the `is-position-sticky` class on the wrapper (Gutenberg standard)
- Mirror `position:sticky;top:Npx` to inline `style=""` (this IS allowed on paired wrappers — sticky positioning is a documented Gutenberg pattern, NOT a v5.7 corruption case)
- For STICKY ADD-TO-BAG bars, prefer `surecart/sticky-purchase` block over manual `core/group` (it's a dedicated block — see `examples/sticky-buy-bar.example.md` and `reference/product-page-blocks.md` Cart chrome section)
- For sticky FILTER SIDEBARS in product list pages, emit the sticky on `core/column` with `width:"280px"` per the canonical pattern from `templates/parts/product-review-list.html`

### `backdrop-filter: blur(...)` (v6.0 — drop and log)

Gutenberg has no `backdrop-filter` attr on any block. Glassmorphism is rendered via custom CSS in the merchant's theme.

```jsx
<div style={{ backdropFilter: "saturate(180%) blur(8px)" }}>
```

→ Drop the value. Log:
```json
"dropped_features": [
  {
    "type": "backdrop-filter",
    "section": "Header",
    "detail": "backdrop-filter: saturate(180%) blur(8px) — requires custom CSS (see merchant docs)",
    "merchant_action": "Add `backdrop-filter: saturate(180%) blur(8px)` to the section's wrapper class via theme stylesheet to restore the glassmorphism effect"
  }
]
```

### `transition: …` and `animation: …` (v6.0 — drop and log)

Drop the value. Log under `dropped_features` with `type:"animation"`. Gutenberg blocks have no animation attrs; restore via theme CSS or custom block extensions if the merchant needs them.

### `transform: rotate(…)` / `scale(…)` / `translate(…)` — drop and log

Same — Gutenberg has no transform attr. Drop unless trivial (e.g., a label rotated 90deg in a sidebar — that's CSS the merchant can add).

### `box-shadow` — drop and log

`--shadow-*` tokens (per `reference/token-aliases.json` `$shadows`) — drop the value, log:
```json
{ "type": "shadow", "detail": "--shadow-md → custom CSS required" }
```

### `clip-path`, `mask`, `filter: blur(…)` — drop and log

Same. Decorative effects without Gutenberg attrs.

### `cursor: pointer` — drop silently (buttons already handle it natively)

---

## Rule 11 — Stable JSON key ordering (v7.0)

Within every block-comment JSON object, emit keys in this order. Stable ordering makes `git diff` reviews tractable, makes the re-read self-check pass (SKILL.md Step 5a) deterministic, and helps merchants visually scan attrs in the same place every time.

**Top-level attribute order:**

1. `metadata` — only on outer `surecart/product-page`; strip from inner blocks (rubric A-7)
2. `align` — `"wide"` / `"full"` / `"center"`
3. `width` / `height` / `aspectRatio` — block-level dimension attrs
4. **Slug attrs** in this order: `backgroundColor` → `textColor` → `borderColor` → `gradient` → `fontFamily` → `fontSize` (theme-overrideable layer)
5. **Block-specific top-level attrs** that aren't `style.*`: e.g., `level` (heading), `summary` (details), `url` / `linkTarget` / `rel` (image, button), `placeholder` (input blocks), `add_to_cart` / `text` / `out_of_stock_text` (surecart/product-buy-button), `dimRatio` / `customGradient` / `minHeight` / `minHeightUnit` (cover), `verticalAlignment`, `isStackedOnMobile`, `mediaUrl` / `mediaId` / `mediaType` / `mediaWidth` (media-text)
6. `style` — nested object in property order: `color` → `spacing` → `typography` → `border` → `position` → `dimensions` → `shadow`. Within each, child keys alphabetically (e.g., `style.spacing.padding` before `style.spacing.margin`? — see exception below).
7. `layout` — `type` → `orientation` → `flexWrap` → `justifyContent` → `verticalAlignment` → `contentSize` → `wideSize`
8. **Tail attrs** — block-internal flags that don't belong with style/layout: `className`, `id`, ancestor-only attrs, etc.

**Within `style.spacing`:** preserve the source-CSS order — `padding` before `margin` before `blockGap`. This matches the editor's UI ordering.

**Within `style.typography`:** `fontSize` → `fontWeight` → `lineHeight` → `letterSpacing` → `textTransform` → `textDecoration` → `fontStyle`.

**Within `style.color`:** `text` → `background` → `gradient`.

**Within `style.border`:** `width` → `color` → `radius`. Per-side overrides (`style.border.top.{width,color}`) come after the shared keys.

### Example — fully ordered emit

```json
{
  "metadata": { "name": "Hero", "patternName": "hero-with-cta" },
  "align": "full",
  "backgroundColor": "surecart-bg-dark",
  "textColor": "surecart-white",
  "fontFamily": "surecart-display",
  "style": {
    "color": { "text": "#FFFFFF", "background": "#042F2E" },
    "spacing": { "padding": { "top": "96px", "right": "24px", "bottom": "96px", "left": "24px" }, "blockGap": "32px" },
    "typography": { "fontSize": "84px", "fontWeight": "700", "lineHeight": "1.05", "letterSpacing": "-0.04em" }
  },
  "layout": { "type": "constrained", "contentSize": "1200px" }
}
```

**When a key isn't present in source:** omit it (don't emit `null` or empty objects). Stable order applies only to keys that actually appear.

**Self-check:** the re-read pass (SKILL.md Step 5a) verifies key order. A single out-of-order block is not a paste-safety issue (Gutenberg's parser is order-insensitive), but it breaks diff review and makes regression tracking harder.

---

## Multi-value shorthand expansion

```
"40px 60px"            → {top:"40px",right:"60px",bottom:"40px",left:"60px"}
"40px 60px 80px"       → {top:"40px",right:"60px",bottom:"80px",left:"60px"}
"40px 60px 80px 100px" → {top:"40px",right:"60px",bottom:"80px",left:"100px"}
```

CSS shorthand convention: 1=all, 2=v|h, 3=top|h|bottom, 4=top|right|bottom|left.

---

## `var()` resolution algorithm

For every `var(--token)` reference in JSX:

1. **Recursively resolve** the chain through `:root` until you reach a literal (hex, rgba, px, font stack). Track every hop: `--brand` → `--sc-green-600` → `#01824C`.
2. **At every hop**, check `reference/theme-partial.json` for a matching slug. **Prefer the most semantic match**:
   - `brand-*` > `bg-*` / `fg-*` / `border-*` > numeric scale slugs
   - `surecart-brand` (semantic) > `surecart-green-600` (numeric) when both match `#01824C`
   - `surecart-bg-dark` (semantic) > `surecart-green-950` (numeric) when both match `#042F2E`
3. If matched in `color.palette[].slug` → emit slug attr (`textColor` / `backgroundColor` / `borderColor`)
4. If matched in `spacing.spacingSizes[].slug` → emit `var:preset|spacing|<slug>`
5. If matched in `typography.fontFamilies[].slug` → emit `fontFamily` slug
6. If no slug matches at any hop → emit the literal end-value.

### Slug validation (mandatory)

**Before emitting any preset slug, you MUST verify it exists as a literal `"slug": "<name>"` line in `reference/theme-partial.json`.** Never invent slugs by pattern. Common phantom slugs to watch for:

| ❌ Phantom (does NOT exist) | ✅ Real slug |
|---|---|
| `surecart-white` (used to exist before v5.2) | now valid — added as alias for `#FFFFFF` |
| Slugs you "guessed" by pattern | open the file, search, confirm |

Phantom slugs render as `class="has-X-background-color"` which Gutenberg's CSS won't match → no background appears. Same for any kind: `textColor`, `fontFamily`, `borderColor`. **When in doubt, grep `theme-partial.json` for the slug name.**

Always prefer slugs when available — they make the page theme-overridable.

---

## `calc()`, `clamp()`, `min()`, `max()`

Pass through as literal CSS strings — Gutenberg accepts arbitrary CSS in `style.*` values:

```jsx
fontSize: "clamp(2rem, 5vw, 4rem)"
```
```json
"style":{"typography":{"fontSize":"clamp(2rem, 5vw, 4rem)"}}
```

---

## Class on the wrapper

When you emit slug attrs, you must also emit the matching class on the wrapper `<div>`/`<h{level}>`/`<p>`. Gutenberg's `save()` does this automatically when running in JS — but you're hand-writing the markup, so YOU must match what `save()` would have produced.

Class patterns:

| Slug attr | Class on wrapper |
|---|---|
| `textColor:"X"` | `has-X-color has-text-color` |
| `backgroundColor:"X"` | `has-X-background-color has-background` |
| `borderColor:"X"` | `has-X-border-color has-border-color` |
| `fontSize:"X"` (slug) | `has-X-font-size` |
| `fontFamily:"X"` | `has-X-font-family` |
| `align:"wide"` | `alignwide` |
| `align:"full"` | `alignfull` |
| `align:"center"` | `aligncenter` |
| `textAlign:"center"` | `has-text-align-center` |

When you also write `style.color`, `style.spacing`, etc. as literal values, you must inline a matching `style="…"` on the wrapper too.

---

## Rule of thumb

- **Slug exists in `theme-partial.json`** → use the slug attr + class. **Cleaner.**
- **No slug** → use `style.{kind}.{prop}` literal + inline `style=""` on wrapper.
- **`__experimentalSkipSerialization` block** → write attrs only, never the class/style on wrapper. The block's PHP render handles it.

---

# v7.2.0 — empirical findings from production patterns

The findings below were extracted by auditing every inline `style=""`, every class set, and every `style.*` attribute object across the 20 production block-pattern files in the SureCart plugin's own pattern library. They confirm and extend the rules above.

## Inline-style key serialization order (canonical)

Gutenberg's `save()` produces inline styles in a deterministic order. When the skill emits inline `style=""`, MATCH this order — out-of-order styles do not break recovery in practice but make round-trip diffs noisy.

**Order:**
```
margin-{top,right,bottom,left}
→ padding-{top,right,bottom,left}
→ border-{color,width,style,radius}
→ font-{size,weight,style}
→ line-height
→ text-decoration / text-transform / text-align / letter-spacing
→ color
→ background-color
→ min-height / aspect-ratio / max-width
→ flex-basis / --sc-* CSS vars
```

**Examples (verbatim from patterns):**
```html
style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"
style="border-color:#dfe4ea;border-style:solid;border-width:1px;border-radius:4px;padding-top:50px;padding-right:50px;padding-bottom:50px;padding-left:50px"
style="background-color:#f9fafb;min-height:100vh;padding-top:80px;padding-right:0px;padding-bottom:80px;padding-left:0px"
style="border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:24px;border-bottom-right-radius:24px;margin-bottom:0"
```

## `style` attribute (JSON) key order

The block-comment JSON `"style":{...}` mirrors the inline-style order. Canonical JSON-key order:

```
"color" → "spacing" → "border" → "typography" → "elements" → "layout" → "dimensions" → "position"
```

Inside each subkey:
- `spacing`: `padding` → `margin` → `blockGap`
- `padding` / `margin`: `top` → `right` → `bottom` → `left`
- `border`: `color` → `width` → `style` → `radius` (radius is either string `"10px"` or object `{topLeft, topRight, bottomLeft, bottomRight}`)
- `typography`: `fontSize` → `fontWeight` → `fontStyle` → `lineHeight` → `textDecoration` → `textTransform` → `textAlign` → `letterSpacing`
- `color`: `text` → `background`
- `elements.link`: `color.text` (slug or hex)

## Class-set order (paired wrappers)

```
wp-block-{name}
→ alignment (alignwide | alignfull | aligncenter)
→ has-{slug}-color
→ has-text-color | has-link-color (indicators)
→ has-{slug}-background-color
→ has-background (indicator)
→ has-border-color
→ is-style-* | is-layout-* | custom (user-defined)
```

**Verbatim examples:**
```html
<div class="wp-block-group has-background" style="...">
<div class="wp-block-group has-border-color has-white-background-color has-background" style="...">
<div class="wp-block-group has-border-color has-ast-global-color-5-background-color has-background" style="...">
<div class="wp-block-columns alignwide">
<div class="wp-block-column" style="flex-basis:36%">
<div class="wp-block-surecart-product-buy-buttons wp-block-buttons sc-block-buttons is-layout-flex">
<div class="wp-block-surecart-column is-layout-constrained is-horizontally-aligned-center" style="flex-basis:60%;--sc-column-content-width:100%">
```

**Critical:** `has-border-color` always precedes `has-{slug}-background-color`. `has-background` is a trailing indicator after the specific background class.

## Zero-value reset is always explicit

Gutenberg's `save()` never omits zero-value spacing properties. When a block has any explicit padding or margin, ALL four sides are emitted (even if zero):

```html
<!-- ❌ NEVER omit zero sides -->
<div style="padding-top:50px"> </div>

<!-- ✅ Always emit all sides -->
<div style="padding-top:50px;padding-right:0;padding-bottom:0;padding-left:0"> </div>
```

This is observed in 47+ wrappers across the corpus. Skip it and round-trip diffs explode.

JSON form:
```json
"spacing":{"padding":{"top":"50px","right":"0","bottom":"0","left":"0"}}
```

## `var:preset|*` usage (101 total occurrences)

| Preset | Count | Use case |
|---|---|---|
| `var:preset\|color\|white` | 96 | Most common — text/bg/sale-badge |
| `var:preset\|color\|black` | 2 | List patterns |
| `var:preset\|color\|contrast` | 1 | `product-quick-view` title link color |
| `var:preset\|color\|accent-4` | 1 | `product-quick-view` interval color |
| `var:preset\|spacing\|60` | 1 | `cart-new` (the ONLY spacing preset use) |

**Spacing policy reaffirmed:** with 1/2000+ spacing values using a preset, the rule **"always literal px/em/% for spacing, never `var:preset|spacing|*`"** holds. The single exception in `cart-new` is the closest the corpus comes to a violation; treat it as a quirk, not a precedent.

**Color policy:** prefer slugs when the design uses neutrals (`white`/`black`/`contrast`). For brand/themed palettes (browns, dark+emerald), inline literal hex with the leaf-element dual-emit pattern (slug class + style.color.text hex).

## Color theming families (palette archetypes)

| Family | Patterns | Roles & hex |
|---|---|---|
| **Brown/earth** | `product-physical*` | text-primary `#28201b`, text-secondary `#5b5048`, action `#8b4513`, muted-border `#8b451340`, page-bg `#f3f0ec`, sale-red `#dc2626` |
| **Dark + emerald** | `product-course-dark*` | outer-bg `#1a1b26`, card-bg `#212231`, border `#2b2d3b`, muted-text `#9ca3af`, action `#34d399`, primary-text `#fff`/`var:preset\|color\|white`, muted-light `#e5e7eb` |
| **Light neutral** | `product-standard`, `product-alternate`, `list-*`, `upsell-info`, `product-course` | text-primary `#111827`, text-secondary `#4b5563`, strikethrough `#686868`, muted `#8a8a8a`, page-bg `#f9fafb`, sale-red `#dc2626`, accent-mute `#0000000d` |

When a Claude Design palette doesn't match any family within ΔE 6, emit literal hex on each leaf and skip the slug attribute (per `style-conversion.md` Rule 3).

## Typography placement matrix (leaf vs container)

| Block category | Carries `typography.*`? | Notes |
|---|---|---|
| **Leaves** (`product-title`, `product-description`, `product-selected-price-*`, `price-*`, `core/heading`, `core/paragraph`, `product-list-price`, `product-scratch-price`, `product-sale-badge`, `cart-line-item-*`) | YES | Each leaf carries its own typography. Properties: `fontSize`, `fontWeight`, `fontStyle`, `lineHeight`, `textDecoration`, `textTransform`, `letterSpacing`. |
| **Containers** (`core/group`, `core/columns`, `core/column`, `core/cover`, `core/buttons`, `surecart/product-page`, `surecart/product-buy-buttons`, `surecart/product-price-chooser`, `surecart/product-variant-pills`, `surecart/product-quantity`) | NO | Containers carry only `spacing`, `border`, `layout`, `color.background`. They do NOT cascade typography. |
| **Quick-view & sticky-purchase wrappers** | YES (base size only) | `surecart/product-quick-view` carries `typography.fontSize:"16px"` — sets the em-base for child em-relative sizes. This is the ONE legitimate container-typography case. |

**Anti-pattern (recovery candidate):** putting `typography.fontSize` on `surecart/product-buy-buttons` and expecting it to flow to children. It won't — emit on the `product-buy-button` itself.

## Border-radius variants observed

| Radius | Use case | Patterns | Form |
|---|---|---|---|
| `4px` | Small inputs, subtle borders | `upsell-info`, `product-course-dark*` | `"radius":"4px"` (string) |
| `10px` | Product cards, group bgs | `list-row`, `list-carousel`, `list-sidebar`, `list-standard`, `list-bento`, `related-carousel*`, `product-quick-view` | `"radius":"10px"` (string) |
| `12px` | Content cards | `product-course-dark-content` | `"radius":"12px"` |
| `15px` | Sale badges | `product-standard`, `product-alternate`, `product-quick-view`, `related-carousel` | `"radius":"15px"` |
| `20px` | Featured-image rounding | `product-course-dark` (`core/post-featured-image`) | `"radius":"20px"` |
| `24px` | Course-card top-cap, large rounded containers | `product-course-dark*`, `related-carousel-alternate` | `"radius":{"topLeft":"24px","topRight":"24px","bottomLeft":"24px","bottomRight":"24px"}` (object — emit ALL 4 corners even when equal) |
| `999px` / `9999px` | Pill buttons, pebble quantity | `product-physical`, `cart-new` | `"radius":{"topLeft":"999px",...}` (object) |
| `0px` | Reset / disabled | rare | `"radius":"0px"` |

When emitting per-corner radius (object form), ALL 4 corners are required — even if some are zero.

## Layout-config catalog

**Distinct configurations observed:**

| Type | Key properties | Frequency | Examples |
|---|---|---|---|
| `default` | (none) | very common | basic groups, paragraphs |
| `flex` | `flexWrap`, `justifyContent`, `verticalAlignment`, `orientation` | 45+ | review row, price row, sticky bar |
| `grid` | `columnCount` (2-4) OR `minimumColumnWidth` ("225px") | 3+ | product-template grids |
| `constrained` | `contentSize` literal ("1080px"/"1240px"/"1320px"/"1440px") | 8 | product-page wrappers, upsell-info |

**Child layout (`selfStretch`/`flexSize`):**
- `"selfStretch":"fixed","flexSize":"50%"` — fixed-size column (50% of parent flex)
- `"selfStretch":"fixed","flexSize":"800px"` — fixed pixel size
- `"selfStretch":"fixed","flexSize":"225px"` — sidebar width
- `"selfStretch":"fill","flexSize":null` — absorbs remaining space
- `"selfStretch":"fit","flexSize":null` — sizes to content

**`contentSize` literal byte-perfect:** when a Claude Design has `maxWidth: 1240` in CSS, emit `"contentSize":"1240px"` exactly. Don't round to "1200px" or "1280px".

## `core/html` SVG patterns (L1-safe)

The 8+ inline SVGs in the corpus all share these properties:
```html
<svg width="N" height="N" viewBox="0 0 N N" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="..." stroke="<palette-hex>" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
```

**L1-safe checklist:**
- `xmlns="http://www.w3.org/2000/svg"` (required)
- `viewBox` (fixed, no JS)
- `fill="none"` + stroke (no animation, no `<use>`, no `<script>`)
- `stroke="<hex>"` matches palette (e.g. `#8B4513` for brown, `#34D399` for emerald)
- `stroke-width="1.25"` (consistent)
- `stroke-linecap="round"` + `stroke-linejoin="round"` (consistent)

When a Claude Design SVG fails the checklist (animation, scripts, filters), drop to L3 — replace with a `core/image` referencing an exported PNG asset, or omit entirely with a `dropped_features` log entry.

## Custom CSS variable hooks (used by SureCart blocks)

Several patterns set `--sc-*` CSS variables inline. These are CONSUMED by the SureCart block's CSS — they are not arbitrary names.

| Variable | Block context | Effect |
|---|---|---|
| `--sc-column-content-width` | `surecart/column` (custom) | Inner content width inside an upsell column |
| `--sc-form-row-spacing` | `surecart/column` (custom) | Vertical rhythm between form rows |
| `--sc-cart-scrollable` | (class, not var) | Scrollable cart content area |
| `--sc-input-help-text-color` | cart styling | Help-text color for cart inputs |

When emitting upsell-info markup, preserve these inline CSS-var declarations verbatim (don't substitute literal CSS values). They wire into the SureCart block CSS pipeline.

## Anti-patterns NOT to inherit from production

- `sticky-purchase.php` line 10 has malformed JSON (`{""metadata":...,layout":...`). The skill exemplar has been corrected to valid JSON. Do not emit the malformed form.
- A handful of `core/group` blocks with `layout:{type:"constrained"}` may omit the `is-layout-constrained` / `wp-block-group-is-layout-constrained` double-class on the wrapper div (rubric A-9a strict reading would flag these). The production patterns paste cleanly without them in current Gutenberg, suggesting save() tolerates the omission. Emit BOTH classes anyway — it's safer and matches save() output in newer WordPress versions.
