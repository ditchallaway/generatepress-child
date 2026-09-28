<!-- GENERATED FROM SKILL.md §HC-table + §Doctrine-reversals — DO NOT EDIT — regenerate with scripts/regen-digest.mjs -->
<!-- digest-version: 7.22.2 -->
<!-- source-skill-version: 7.22.2 -->
<!-- source-hash: f384691b5903d708 -->
<!-- hc-count: 48 (after MERGED-entry suppression) -->

# Constraint digest — for embedding inside the Claude Design prompt

> Compressed one-line-per-HC summary auto-generated from `SKILL.md`. Embed by reference (not inlined) in the emitted Claude Design prompt's §7 prohibitions section. Re-run `scripts/regen-digest.mjs` after any change to `SKILL.md`'s HC list or doctrine-reversals table.

## Sync contract

CI gate: `node scripts/regen-digest.mjs --check` exits 1 if the `source-hash` above does not match a freshly-computed hash of SKILL.md's HC + doctrine-reversals sections. Re-run the generator before merging.

## Source fingerprint

- **digest-version:** `7.22.2`
- **source-skill-version:** `7.22.2`
- **source-hash:** `f384691b5903d708` (sha256 of HC + doctrine-reversals sections, first 16 hex chars)
- **hc-count:** 48 active HCs (MERGED-entries point to canonical homes; not duplicated here)

## Doctrine reversals — recognized wrong forms (canonical right-form table)

(extracted verbatim from SKILL.md "Doctrine reversals" section — the wrong-form / right-form table is the single most important reference for the Claude Design prompt's anti-pattern framing)

| Topic | Wrong form (don't emit) | Right form (canonical) | HC |
|---|---|---|---|
| `has-border-color` on literal-hex border | OMIT class when border is literal-hex (v7.3) | ALWAYS emit `has-border-color` when `style.border.color` is set, regardless of slug-or-hex (v7.6) | HC#21 |
| `product-review-total-rating` className | Emit `className:"is-style-plus-sign"` (pre-v7.15) | OMIT className — `plus-sign` is `isDefault:true` (v7.15) | HC#19 |
| `core/group` sticky position | Inline `style="position:sticky;top:Npx;z-index:N"` mirror (v7.0 Exemplar 9) | `is-position-sticky` class only, NO inline mirror (v7.13) | HC#25 (unified) |
| `core/group` `style.dimensions.aspectRatio` | Inline `aspect-ratio:1` mirror | NEVER emit — drop+log, use `core/cover` or `padding-top` proxy (v7.7) | HC#25 (unified) |
| `core/cover` `isDark` rationale | "save() emits both is-light AND is-dark" (v7.17 prose) | save() emits `is-light` only; edit-time `setAttributes` overwrites JSON (v7.19 PR2). Rule unchanged: OMIT `isDark` from JSON when bg is set. | HC#42 |
| `core/cover` aspect-ratio inline mirror | Inline `aspect-ratio:X` for ALL cover variants (v7.0 Exemplar 8) | Inline-mirror ONLY when JSON uses top-level `aspectRatio`; `style.dimensions.aspectRatio` does NOT inline-mirror (v7.11) | HC#25 (unified) |
| `core/icon` slug catalog count | 90 slugs | 88 slugs (verified vs `wp-includes/assets/icon-library-manifest.php`) | HC#32 |
| Class order in `<a class="wp-block-button__link">` | "Trailing positions order-sensitive" (pre-v7.19) | Pure set-equality — order is convention only, NEVER validator-enforced (v7.19 PR2 — `validation/index.js:348-356`) | HC#34 / B-22 |
| HC#22 / HC#23 / HC#25 / HC#29 / HC#30 / HC#33 / HC#37 standalone | Each as its own rule (pre-v7.19) | HC#22 → merged into HC#45. HC#29 → absorbed by HC#23. HC#30 + HC#33 → unified into HC#25. HC#37 → fallback under HC#38. (v7.19 PR2) | various |

## Hard constraints (one-line compression, keyed by HC#)

HC#01: Outer wrapper: entire output is wrapped in `<!-- wp:surecart/product-pag…
HC#02: Apply the alias map: rewrite the 6 designer-time names BEFORE emit
HC#03: Never self-close paired core blocks: `core/columns`, `core/column`, `cor…
HC#04: Buy buttons coalesce: Add-to-Cart and Buy-Now go in ONE shared `surecart…
HC#05: `metadata` only on the outer block
HC#06: `__experimentalSkipSerialization` carve-out: for `surecart/product-buy-b…
HC#07: Token-first: prefer `textColor:"surecart-brand"` (slug) over `style.colo…
HC#08: `.map()` over a literal array → expand to N siblings
HC#09: Every named section in JSX MUST appear in the output OR be logged in `dr…
HC#10: `fontFamily` is mandatory for every text-bearing block when the source C…
HC#11: `<!-- wp:details -->` MUST carry `{"summary":"<question text>"}` matchin…
HC#12: Comment markers use the unprefixed core form
HC#13: Slug validation
HC#14: Hybrid spacing/color policy
HC#15: Explicit-per-block visual contract
HC#16: JSON string-value cap
HC#17: Body-bg shim
HC#18: Buy-button text attr mandatory
HC#19: Hero review summary fork
HC#20: Card grids use `core/group` flex
HC#21: `has-border-color` class always-emit policy
HC#23: `core/image` is strict-schema
HC#24: `core/list` requires `wp-block-list` class; no inline-styled inline chil…
HC#25: Structural-CSS class-only routing on `core/group` + `core/cover`
HC#26: Zero-value spacing properties REQUIRE JSON↔inline parity
HC#27: `core/cover` gradient-only emission class set canonical form
HC#28: `surecart/product-buy-buttons` margin inline-mirror parity
HC#31: No hand-written `font-style:normal` in inline mirror without `fontStyle:…
HC#32: `core/icon` is the canonical block for built-in icon glyphs
HC#34: `core/button` `has-custom-font-size` class emitted for literal-px `fontS…
HC#35: Font-family literal fallback alongside slug
HC#36: Featured-image cover placeholder fallback
HC#38: Server-rendered chrome — registered block-style variations FIRST, wrap-a…
HC#39: `surecart/sticky-purchase` content can overflow without explicit positio…
HC#40: `useFeaturedImage:true` on cover thumbnails overrides per-thumb `backgro…
HC#41: `fontFamily:"surecart-display"` attr + literal `font-family` inline styl…
HC#42: `core/cover` `isDark` MUST match `color.background` luminance — OMIT `is…
HC#43: `surecart/product-review-average-rating-breakdown` is a HALLUCINATION — …
HC#44: `surecart/sticky-purchase` MUST use `surecart/product-selected-variant-i…
HC#45: NO descriptive HTML comments inside block content areas — only block del…
HC#46: `surecart/product-variant-pills` emits ONCE — server iterates ALL varian…
HC#47: `surecart/product-variant-pill` border attrs cause apparent "wrapper bor…
HC#48: File-output exclusivity — chat-leak hard gate
HC#49: JSON string values: unescaped `<`, `>`, `&`, `\"`, `--` are forbidden in…
HC#50: Review-template inner-block hallucinations — `-author-info` / `-rating` …
HC#51: Intake-emitted Claude Design prompt §4 palette discipline — name ONE acc…
HC#52: `core/separator` with literal-hex `style.color.background` REQUIRES `opa…
HC#53: `surecart/product-review-list` canonical inner structure — summary + tem…

## Anti-patterns (positive replacements)

See `intake/shared/anti-patterns.md` for the 10 positive-replacement rules (referenced by the emitted prompt's §7).

## Surface markers (load-bearing for converter routing)

- Product-detail (master skill) emits 2 surfaces: `{/* SURFACE: PRODUCT-TEMPLATE */}`, `{/* SURFACE: STATIC-CONTENT */}`
- Shop-page (sibling skill) emits 3 surfaces: `{/* SURFACE: FILTER-CHROME */}`, `{/* SURFACE: PRODUCT-GRID */}`, `{/* SURFACE: PAGE-CHROME */}`
- Markers MUST appear ONLY at top-level boundaries — never nested inside a component subtree (HC#45 silent-drop trap)
- Markers are routing-only and are STRIPPED before Gutenberg emission (Tier I-9 verifies)

## Constraint family tags (used in the emitted prompt's §7)

- `[SC-DATA]` — SureCart server-rendered data assumption (title, price, variants, reviews are placeholder components)
- `[SC-BLOCK]` — SureCart block grammar (paired blocks, alias-map, class set-equality)
- `[WP-CORE]` — WordPress core block capability bounds (no animations, no position:fixed, no ::before content, named icon slugs only)

---

*Generated by `scripts/regen-digest.mjs` at digest-version 7.22.2. Do not hand-edit; changes will be overwritten on next regen.*
