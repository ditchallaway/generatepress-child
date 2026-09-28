# Test harness — `surecart-design-to-shop-page` fixtures

Regression test bench for the shop-page skill. Mirrors the harness pattern from the sibling `surecart-design-to-blocks` skill — see `../../surecart-design-to-blocks/tests/README.md` for the full workflow, diff-script conventions, and Mode A vs Mode B paste-test procedure.

## Status (v0.1.0)

**No fixtures committed yet.** The shop-page skill ships v0.1.0 with the workflow, vocabulary, rubric, and 6 paste-verified production exemplars (`examples/patterns/list-*.example.md` moved from the sibling skill). The next step in the v0.x.y paste-test cycle is to attach a fresh Claude Design shop-page export and run end-to-end paste tests, producing the first gold-reference fixture(s).

## How to add the first fixture

1. Pick a Claude Design export representing a shop / collection / product-listing page (zip, screenshot, or HTML+CSS dump).
2. Create `tests/fixtures/01-<archetype>/` with:
   - `meta.json` — `{ "archetype": "responsive-grid|sidebar|carousel|bento|row|staggered", "input_mode": "zip|screenshot|html_css|live_url", "notes": "..." }`
   - `input/` — design files (per sibling skill's Mode A/B fixture-layout conventions)
3. Open a fresh Claude conversation with **this skill** activated.
4. Attach the input and ask: *"Use the surecart-design-to-shop-page skill to convert this design."*
5. Save the emitted markup to `tests/fixtures/01-<archetype>/actual.html` (strip the drift report + merchant preamble — same as sibling skill).
6. Paste `actual.html` into a fresh WordPress page via Code editor → switch to Visual editor. Confirm:
   - **No "Start Basic" picker UI** appears anywhere (the silent failure mode A-2 / SHC#2 prevents).
   - **No "Attempt Block Recovery" prompts** on first load.
   - The grid renders with the merchant's actual products.
   - Filter / sort / search controls render correctly in the header (or sidebar).
   - Pagination triad renders.
7. If steps 5–6 pass, commit `actual.html` as `golden.html`. Note the date + skill version in the commit message.

## Reusing the diff.mjs harness

The sibling skill's `tests/diff.mjs` extracts and compares Gutenberg payload between `<!-- wp:surecart/product-page -->` and `<!-- /wp:surecart/product-page -->`. Shop-page fixtures need the **same harness behavior** but scoped to `<!-- wp:surecart/product-list -->` / `<!-- /wp:surecart/product-list -->` instead.

**Option A — symlink the harness with an env override.** When the sibling skill's `diff.mjs` is patched to accept a `--root-block=surecart/product-list` flag, the shop-page tests can run via `node ../../surecart-design-to-blocks/tests/diff.mjs --root-block=surecart/product-list tests/fixtures/01-<name>`.

**Option B — fork the harness inline.** When the shop-page skill reaches v1.0 (after the first gold reference fixture lands), copy `diff.mjs` into this directory and change the payload-extraction root to `surecart/product-list`. Acceptable duplication for the regression-test purpose.

Option A is preferred — keeps the diff logic in one place.

## Per-fixture expectations

For shop-page fixtures, the `meta.json` should additionally declare:

```json
{
  "archetype": "responsive-grid",
  "input_mode": "zip",
  "expected_grid_shape": {"type":"grid","columnCount":null,"minimumColumnWidth":"225px"},
  "expected_filter_ui": "dropdown_plus_tags",
  "expected_sidebar": false,
  "expected_pagination": "full_triad",
  "expected_per_card_blocks": [
    "core/cover (useFeaturedImage)",
    "surecart/product-quick-view-button",
    "surecart/product-sale-badge",
    "surecart/product-title (level:2)",
    "surecart/product-list-price",
    "surecart/product-scratch-price"
  ],
  "notes": "..."
}
```

The diff script can validate these against the emitted markup as a structural sanity check independent of byte-for-byte golden matching.

## Reference designs to consider for v0.2.0 paste-test cycle

Designs that would stress different aspects of the skill (in priority order):

1. **Generic responsive grid** — 1440px viewport, 4-up cards with auto-fit reflow at narrower widths. Smallest possible fixture, validates A-1, A-2, A-3, A-4, A-5, A-6, A-7.
2. **Sidebar variant** — sticky filter sidebar on left with checkbox-style filters + main grid. Validates SHC#7 + Tier B B-2 sidebar emission.
3. **Carousel / fixed-column** — 3-up fixed columns. Validates `columnCount:3` vs `minimumColumnWidth` choice.
4. **Load-more variant** — single "Load more" button instead of pagination triad. Validates A-5 load-more variant.
5. **Radio-sort variant** — radio buttons for sort instead of dropdown. Validates Variant C in `start-basic-template.md`.
6. **Heavy chrome** — design with a hero band above the grid + collection description + breadcrumbs. Validates above-grid block emission outside the `surecart/product-list` wrapper.

After ~3 gold references accumulate, the skill graduates from v0.x to v1.x stability.
