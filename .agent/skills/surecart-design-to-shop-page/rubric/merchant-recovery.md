# Merchant Recovery Playbook — Shop Page

When the merchant pastes the markup into the WordPress block editor and something visible goes wrong, walk through these scenarios in order. Most shop-page failure modes are **silent** — they don't produce "Attempt block recovery" prompts. The merchant sees an unstyled setup widget where products should be.

## Scenario 1 — "Start Basic / Select template" picker UI appears

**What the merchant sees:** instead of the products grid, a centered placeholder card with a "Start Basic" button or "Choose a template" picker.

**Cause:** the `surecart/product-list` block (or a paired child like `surecart/product-pagination` or `surecart/product-list-no-products`) was emitted self-closed or with a partial inner template. The editor's Placeholder component activates when the inner blocks don't match the expected template shape.

**Fix:**

1. Switch to **Code editor** (block editor ⋮ menu → Code editor).
2. Search for `<!-- wp:surecart/product-list /-->` (self-closed). If found, this is the cause.
3. Replace with the full Start-Basic template from `reference/start-basic-template.md`.
4. If `<!-- wp:surecart/product-list {"...":"..."} -->` is paired but contents are missing/truncated:
   - The pasted markup may have been truncated by the chat surface. Re-emit using the **Write tool to ~/Desktop** (skill workflow Step 4) and copy from the file.
   - Or the inner template is missing one of: header row, `product-template`, `product-pagination`, `product-list-no-products`. Compare against `reference/start-basic-template.md` and add the missing block.
5. Switch back to Visual editor. Grid should render with products.

**Diagnostic tip:** the picker also appears on `surecart/product-pagination` (self-close shows pagination picker), `surecart/product-list-no-products` (self-close shows empty-state picker), and several other paired SureCart blocks. Search the Code editor for any `<!-- wp:surecart/X /-->` self-closes — they're all candidates.

## Scenario 2 — Filter / sort / search blocks show "Block has been deleted or is unavailable"

**What the merchant sees:** the header row has placeholder grey boxes with "block has been deleted or is unavailable" or "block type 'surecart/product-list-sort' is not registered" messages.

**Cause:** filter/sort/search blocks have `ancestor: ["surecart/product-list"]` declared in their `block.json`. They only register inside a `surecart/product-list` parent. If they were emitted at top level (outside `surecart/product-list`) or inside the wrong parent, registration fails at editor load.

**Fix:**

1. Switch to **Code editor**.
2. Confirm the structure is:
   ```
   <!-- wp:surecart/product-list ... -->
     <!-- wp:group ... -->  (header row)
       <!-- wp:surecart/product-list-sort /-->
       <!-- wp:surecart/product-list-filter /-->
       <!-- wp:surecart/product-list-search /-->
     <!-- /wp:group -->
     ...
   <!-- /wp:surecart/product-list -->
   ```
3. If any filter/sort/search block is OUTSIDE the `surecart/product-list` wrapper, move it inside.
4. Switch back to Visual editor.

## Scenario 3 — Grid renders empty (no product cards at all) despite products existing on the site

**What the merchant sees:** the header row, filters, and pagination render — but the grid area is blank. No cards, no "No products" message.

**Cause:** the `surecart/product-template` block was replaced with a `core/columns` or `core/group` flex layout containing static placeholder cards. The product-template is what server-side iterates the WP_Query results; without it, no products render.

**Fix:**

1. Switch to **Code editor**.
2. Find the grid section. If you see `<!-- wp:core/columns -->` or `<!-- wp:core/group {"layout":{"type":"grid"}} -->` with multiple hand-authored `core/group` "card" children, this is the cause.
3. Replace with `<!-- wp:surecart/product-template {"style":{...},"layout":{"type":"grid","columnCount":N}} -->`...`<!-- /wp:surecart/product-template -->` containing ONE per-card block tree (NOT N copies — the server iterates).
4. Use the per-card structure from `reference/start-basic-template.md` lines under "ONE card (repeated server-side per product)".

## Scenario 4 — "Attempt Block Recovery" prompts on first paste

**What the merchant sees:** WordPress shows "Block validation failed for…" with an "Attempt Block Recovery" button on one or more blocks. Clicking through recovers them as freeform HTML (visible but no longer editable as the intended block).

**Cause:** standard paste-safety failure modes — NOT shop-page-specific. The shop-page skill inherits all 31 hard constraints (HC#1–HC#31) from the sibling skill. The common triggers:

- HC#16 — JSON string value > 80 chars (typically a long `fontFamily` literal stack) line-wrapped during paste.
- HC#27 — `core/cover` gradient class-set mismatch.
- HC#25 — `style.dimensions.aspectRatio` on `core/group` (forbidden — routes through CSS class, not inline).
- HC#23/29 — `core/image` freehand inline styles or width/height as HTML attrs instead of inline.
- HC#28 — `surecart/product-buy-buttons` margin inline-mirror mismatch (but shop-page typically doesn't use buy-buttons inside cards).
- HC#20 — `has-border-color` class missing when `style.border.color` is set.
- HC#21 — HTML comments between top-level children of an apiVersion-3 wrapper.

**Diagnostic:**

1. Open the browser DevTools console at the moment of paste.
2. Look for `Block validation failed: <reason>`. The message names the block and the property that mismatched.
3. Cross-reference the reason against `../../surecart-design-to-blocks/SKILL.md` Hard Constraints section.
4. Edit the markup in the Code editor to fix the constraint violation.
5. Re-paste OR click "Attempt Block Recovery" — recovery preserves the visible content but flags the block as freeform.

## Scenario 5 — Per-card content shows for ONE product only

**What the merchant sees:** the per-card inner blocks (title, price, image) appear once at the top of the grid, then the rest is empty.

**Cause:** the per-card block tree was emitted N times inside `surecart/product-template` (the skill mistakenly produced one block tree per visible card in the design). The server-side iteration then runs the WP_Query N times against the same product (since the merchant typically only has a few test products), or fails entirely.

**Fix:**

1. Switch to **Code editor**.
2. Inside `<!-- wp:surecart/product-template ... -->` ... `<!-- /wp:surecart/product-template -->`, there should be EXACTLY ONE block tree describing one card.
3. If you see multiple `<!-- wp:core/group ... -->` siblings each containing a full card, delete all but the first.
4. The server-side renderer repeats that one block tree per product in the query.

## Scenario 6 — Sticky filter sidebar doesn't stick

**What the merchant sees:** the sidebar variant works visually at the top of the page, but when scrolling the sidebar doesn't stick — it scrolls away with the rest of the content.

**Cause:** `position: sticky` requires an ancestor element to have `overflow: visible` (the default). Many WordPress themes set `overflow: hidden` on the page content wrapper, breaking sticky positioning. This is a theme-level issue, not a markup issue.

**Fix:**

1. Confirm the markup is correct: `<!-- wp:surecart/product-list-sidebar {"style":{"position":{"type":"sticky","top":"0px"}}} -->`.
2. If markup is correct, the merchant needs theme-level CSS. Suggest adding to their theme's custom CSS:
   ```css
   .wp-block-surecart-product-list .wp-block-surecart-product-list-sidebar {
     position: sticky;
     top: 0;
   }
   .wp-block-surecart-product-list,
   .wp-block-surecart-product-list > .wp-block-group {
     overflow: visible !important;
   }
   ```
3. Or switch to a theme that doesn't set `overflow:hidden` on page content (Twenty Twenty-Four works correctly out of the box).

## Scenario 7 — "Load more" button reloads the entire page instead of fetching next products

**What the merchant sees:** clicking the "Load more" button refreshes the URL with `?page=2` instead of dynamically appending products.

**Cause:** the `surecart/product-list` block uses WordPress Interactivity API for client-side pagination. The runtime needs to hydrate the block, which requires the script files to load correctly. Common causes:

- The merchant's site has aggressive JS optimization (e.g., "Defer all JS" plugin) that delays the Interactivity API runtime past the user's first click.
- The user is logged out and a security plugin blocks the `data-wp-*` directives.

**Fix:**

1. Confirm the merchant sees `data-wp-interactive='{"namespace":"surecart/product-list"}'` on the rendered `<div class="wp-block-surecart-product-list">` element (View Source).
2. If absent, the Interactivity API isn't loading — check JS optimization plugins.
3. If present but inert, check the browser console for hydration errors.
4. The `?page=2` URL fallback is the no-JS path — it's correct degradation behavior, not a bug. Just slower than the merchant expected.

---

## When to escalate to a code change vs. merchant action

| Cause | Fix path |
|---|---|
| Skill emitted wrong markup (self-close, missing children, wrong block name) | Skill bug — file a v0.x.y commit fixing the emission |
| Skill emitted correct markup, merchant's theme conflicts | Merchant action — CSS fix in their theme |
| Skill emitted correct markup, WordPress version too old | Merchant action — upgrade WP to 6.5+ for Interactivity API |
| Skill emitted correct markup, SureCart plugin not active | Merchant action — install/activate SureCart |
| Failure is a known SureCart bug | File a separate Linear ticket on the relevant block |

When you're uncertain whether a paste-test failure is the skill's fault or the merchant's, paste the same markup yourself into a clean WordPress install with SureCart's default theme. If it renders correctly, the issue is merchant-environment-specific.
