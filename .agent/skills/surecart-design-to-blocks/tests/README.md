# Test harness — `surecart-design-to-blocks` fixtures

Regression test bench for the skill. Each fixture pairs a design input with a hand-verified golden Gutenberg-markup output. Whenever the skill changes (new rule, new pattern, new input handler), re-run the harness; any fixture that diverges from its golden either reveals a regression to fix or a deliberate change that needs to be reflected in the golden.

The harness is **not** automated end-to-end (there's no programmatic skill emitter). It's a workflow:

1. Pick a fixture under `tests/fixtures/<name>/input/`.
2. Open a fresh Claude conversation with this skill activated.
3. Paste the input files (or attach the `input/` zip).
4. Save Claude's emitted markup — strip the merchant-facing preamble + drift-report block — to `tests/fixtures/<name>/actual.html`.
5. Run `node tests/diff.mjs tests/fixtures/<name>` (or `node tests/diff.mjs --all` for the full bench).

The diff script normalizes whitespace + JSON key order before comparing, so it tolerates Rule-11-compliant emit ordering differences and ignores cosmetic spacing. Real divergence (different attrs, different block names, different nesting) shows up as a unified diff.

## Fixtures

| # | Directory | Archetype | Input mode | What it covers |
|---|---|---|---|---|
| 01 | `01-iphone/` | full product detail page | zip | Comprehensive baseline — hero, highlights grid, specs, FAQ, related, final CTA, sticky bar. Mirrors `examples/iphone-input/` + `examples/iphone-output.html`. |
| 02 | `02-pricing-table/` | pricing table | zip | 3-plan static marketing pricing table with a recommended-plan emphasis variant. Tests `core/columns` + literal-bg cards + button styles. |
| 03 | `03-feature-grid/` | feature grid | zip | 4-card highlights grid with `.map()` expansion + literal-bg card chrome + Exemplar 3.5. |
| 04 | `04-faq/` | FAQ | zip | 5-item FAQ — `core/details` with mandatory `summary` attr, vertical stack, .sc-h2 heading. |
| 05 | `05-hero-cta/` | minimal hero + CTA | zip | 2-column hero with title/lead/buttons + media right column. Smallest possible page. Tests Step 4 outer-wrapper rules and `core/columns` `verticalAlignment`. |
| 06 | `06-custom-html-l2/` | custom-HTML L2 piggyback | zip | Hero + a custom "View cart" pill that toggles the cart drawer via `data-wp-interactive='{"namespace":"surecart/cart"}'` directives, alongside a sibling `surecart/cart-icon` block that triggers the namespace's module load. Tests v7.1 rubric items A-15 (L2 directives + namespace allowlist) and B-20 (`dependency_block` presence in markup). |
| 07 | `07-product-standard/` | classic 2-col product page | zip | Canonical 2-col hero (media left + info 36% right). Mirrors `examples/patterns/product-standard.example.md`. Exercises `core/columns` `blockGap:{top,left}`, `product-collection-tags` paired wrapper, review-rating row, scratch+amount+interval+sale-badge price stack, multi-tier `product-price-chooser` w/ `product-price-choice-template`, `product-variant-pills` paired, `product-buy-buttons` wrapper with the 4 required classes, two buy buttons. |
| 08 | `08-product-physical/` | themed-bg product page (brown) | zip | Full-width product page on cream `#f3f0ec` with brown palette (`#8b4513`/`#28201b`/`#5b5048`). `contentSize:"1320px"` literal byte-perfect. Asymmetric flex (800px media + 424px info via `selfStretch:"fixed",flexSize:N`). `product-quantity-control` paired with explicit `*-decrease`/`*-input`/`*-increase` children (pill-rounded `is-style-pebble`). Two `product-buy-buttons` instances (themed outline + filled). Inline SVGs in `core/html` for shipping/returns benefits. |
| 09 | `09-product-course/` | course product page (light) | zip | Light-themed course page with bg `#f9fafb`. Course-info row (Level/Duration/Language) with vertical dividers. Below hero: 6-card feature grid with inline-SVG icons (Video Classes / Mobile / Bonus / Downloads / Lifetime / Community). Tests `core/html` SVG ladder (L1 static, stroke-only, viewBox-based). |
| 10 | `10-list-standard/` | classic product grid | zip | `surecart/product-list` with header (title + sort + search + filter-tags row) + `product-template` responsive grid (`minimumColumnWidth:"225px"`) + cards (cover w/ `useFeaturedImage:true`, sale-badge, title, price row) + pagination + `product-list-no-products` fallback. |
| 11 | `11-list-sidebar/` | sidebar product list | zip | `surecart/product-list` + sticky filter sidebar (`flexSize:"225px"`, `position:sticky,top:0`) + `product-template-container` flex:fill + responsive grid. Sidebar contains `product-list-sort-radio-group` paired + `product-list-filter-checkboxes` paired + `product-list-filter-tags` paired. Each `*-template` emits ONE inner structure. |
| 12 | `12-sticky-purchase/` | sticky purchase bar | zip | Horizontal `surecart/sticky-purchase` (NOT product-page outer). Two-zone flex (`selfStretch:"fit"` left + `selfStretch:"fill"` right). Title level:4. Single Add CTA. **Diff harness's `extractGutenbergPayload` looks for `surecart/product-page`; falls through to whole-content compare for this fixture.** |
| 13 | `13-product-quick-view/` | quick-view modal | zip | 500px modal `surecart/product-quick-view` (NOT product-page outer). Em-relative typography (16px wrapper base, 1.2em / 0.88em / 0.75em scaled). `product-selected-variant-image` 120px square + close button. Variant pills + price chooser + 50/50 buy buttons. **Diff harness falls through to whole-content compare (same as fixture 12).** |
| 14 | `14-screenshot-northwind/` | full product detail page (Mode B) | **screenshot** | First Mode B fixture (v7.9.0). `input/northwind-kettle.png` (1080×4050 — page 1 of Claude Design's `testnorth.pdf` export, rasterized via `sips`; source PDF retained alongside as `.source.pdf` for reproducibility). Product: Northwind Pour-Over Kettle on a cream/forest-green/walnut palette intentionally off-slug from SureCart's preset palette. 8 sections visible (nav, hero w/ price + save badge + dual CTAs + review row, variant chips, 3-up benefit strip, spec table, editorial story, reviews+histogram+3 cards, FAQ). Exercises every Tier C rubric item (C-17 resolution gate, C-18 confidence dimensions, C-19 ≥4 color_snap_misses against the cream/green/walnut/black palette, C-20 section count, C-21 family-only typography against Instrument Serif / Kode Mono / Figtree). **No `golden.html` committed yet** — diff.mjs reports `missing-golden` until the first paste-test pass at v7.9.0 produces one. See "Mode B paste-test workflow" below. |

## Mode B paste-test workflow (screenshot input)

Mode B fixtures differ from Mode A (zip) in two ways the harness cannot fully automate:

1. **The skill emits via vision extraction, not deterministic parsing.** Two paste-test passes against the same PNG can produce different literal-hex values, snapped-spacing values, and `confidence` scores depending on how Claude sampled the image. Byte-perfect compare against a frozen `golden.html` is therefore unsuitable; **structural compare** (block names + nesting parity vs the Mode A golden for an equivalent design) is the acceptance criterion.

2. **Tier C drift-report keys are mandatory.** Every Mode B paste-test must produce a drift report carrying:
   - `confidence` object with five keys: `typography`, `color`, `spacing`, `layout`, `assets` (each `high`/`medium`/`low`)
   - `merchant_verify[]` array listing every dimension scored `low`
   - `color_snap_misses[]` array with one entry per literal-hex color emitted (naming source hex + reason no SureCart slug snapped within ΔE 6)
   - `input_type: "screenshot"`

   If any of those keys are absent from the emit, the paste-test fails Tier C regardless of how clean `actual.html` looks.

**Paste-test steps (using fixture 14):**

1. Open a fresh Claude conversation with this skill activated.
2. Attach `tests/fixtures/14-screenshot-northwind/input/northwind-kettle.png` only (NOT the `.source.pdf`). Say: *"Use the surecart-design-to-blocks skill to convert this design."*
3. Confirm the skill emits one fenced ```html block (Gutenberg markup) + a fenced ```json drift report. Verify all 4 Tier C keys above are present in the drift report.
4. Save the markup to `tests/fixtures/14-screenshot-northwind/actual.html` (strip the drift-report block + merchant-facing preamble — same as Mode A fixtures).
5. Paste `actual.html` into a fresh WordPress page via Code editor → switch to Visual editor. Confirm **zero "Attempt Block Recovery" notices** on first load.
6. Spot-check the drift report:
   - `color_snap_misses[]` should have entries for at least `#f6f0e6` (cream), `#1f3a2d` (forest green), `#7a4a2a` (walnut), `#1a1a1a` (soft black) — the source palette is intentionally off-slug.
   - `confidence.typography` should be `medium` or `low` (Instrument Serif / Kode Mono / Figtree are unidentifiable from pixels).
   - Top-level block names should include `surecart/product-title`, `surecart/product-price-chooser`, `surecart/product-buy-buttons`, `surecart/product-review-summary` (paired with template), `core/details` × 5 (FAQ), `core/group` for the spec rows.
7. If steps 3–6 pass, commit `actual.html` as the new `golden.html` for fixture 14. Note the date + skill version in the commit message; Mode B goldens are tied to a specific skill version and not expected to byte-match across minor version bumps.

**When Mode B regresses:**

- A `missing-actual.html` from diff.mjs means the paste-test has not been run at the current skill version — re-run steps 1–7.
- A diff against the Mode B golden means either (a) the skill's vision extraction drifted, (b) the skill's Tier C rubric changed, or (c) the input PNG was replaced. Inspect the diff with the same regression-vs-deliberate lens as Mode A.

## Adding a new fixture

**Zip / HTML / live-URL fixture layout:**

```
tests/fixtures/<NN-name>/
├── meta.json              { "archetype": "...", "input_mode": "zip|html_css|live_url", "notes": "..." }
├── input/
│   ├── product-page.jsx
│   ├── sections-1.jsx          (optional — consolidate into product-page.jsx for tiny fixtures)
│   ├── sections-2.jsx          (optional)
│   └── assets/colors_and_type.css
├── golden.html            ← hand-verified expected emit
└── actual.html            ← (gitignored or .gitkeep) what the skill produced; created on each test run
```

**Screenshot (Mode B) fixture layout:**

```
tests/fixtures/<NN-screenshot-name>/
├── meta.json              { "input_mode": "screenshot", "tier_c_expectations": { ... }, "notes": "..." }
├── input/
│   ├── <name>.png         ← long edge ≥ 1200px (C-17). Higher resolution = higher color/typography confidence.
│   └── <name>.source.pdf  ← optional — retained for reproducibility if the PNG was rasterized from a PDF
├── golden.html            ← committed AFTER first paste-test pass; tied to a specific skill version
└── actual.html            ← per-run artifact, gitignored
```

After authoring `golden.html` (zip/html/live-URL) — or after the first paste-test pass produces an acceptable emit (screenshot) — run the skill once and confirm `actual.html` matches. Commit `golden.html` and the input. Don't commit `actual.html` — it's a per-run artifact.

## Diff-script behavior

- **Whitespace-insensitive.** Multiple blanks → single space. Block comments compared after trimming.
- **JSON-key-order-insensitive.** Every `<!-- wp:NAME {JSON} -->` opener has its JSON parsed and re-serialized with keys sorted recursively. Rule 11 ordering can change without breaking the diff; only the *set* of keys + values matters.
- **Payload-scoped.** Diff runs only on the content between `<!-- wp:surecart/product-page` and `<!-- /wp:surecart/product-page -->`. Anything outside (drift-report, copy-paste instructions) is ignored.

If you need to compare with key order intact (e.g., to verify Rule 11 specifically), comment out `sortJsonInBlockComments` in `diff.mjs`.

## When a fixture fails

1. **Read the diff.** Lines starting with `-` are the golden; `+` is the new emit.
2. **Decide: regression or deliberate change?**
   - Regression — fix the skill to restore the golden. Don't update `golden.html`.
   - Deliberate (new rule, new attr, intentional shape change) — update `golden.html` to match the new emit, document why in the commit message, and re-run.
3. **Cross-check rubric.** A real Tier A failure in `actual.html` (broken paired blocks, missing wrappers, etc.) means the skill's self-validation was bypassed — investigate why before patching the fixture.

## CI integration (future)

The harness runs in user space today. To wire it into CI, the cheapest path is to capture the skill's emit deterministically by piping a fixture's input through a thin wrapper script that calls the Anthropic API with the skill loaded. Out of scope for v7.0 — current invocation is interactive, by hand.
