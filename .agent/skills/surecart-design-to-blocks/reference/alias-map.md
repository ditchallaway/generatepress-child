# Alias Map — Stop Hallucinations Cold

Claude Designs use designer-time block names that **DO NOT EXIST** in the SureCart inventory. You MUST rewrite each occurrence before emitting markup. This is the single most common cause of "Attempt block recovery" prompts in the WordPress editor.

## The 6 hard aliases

| ❌ DO NOT EMIT (does not exist in inventory) | ✅ EMIT INSTEAD |
|---|---|
| `surecart/variant-picker` | `surecart/product-variant-pills` |
| `surecart/quantity` | `surecart/product-quantity` |
| `surecart/add-to-cart-button` | `surecart/product-buy-button` (inside `surecart/product-buy-buttons`; attrs `add_to_cart=true, text="Add To Cart"`) |
| `surecart/buy-now-button` | `surecart/product-buy-button` (inside `surecart/product-buy-buttons`; attrs `add_to_cart=false, text="Buy Now"`) |
| `surecart/product-price` (when design shows BIG hero price near title) | `surecart/product-selected-price-amount` (+ `-interval`, `-scratch-amount`, `-trial`, `-fees` siblings as needed) |
| `surecart/product-price` (when design shows OPTIONS GRID — multiple price tiers/storage sizes) | `surecart/product-price-chooser` (with `price-*` children per option) |
| `surecart/product-list` (in a "related products" context) | `surecart/product-list-related` |

## The buy-button pair specifically

When a design has BOTH "Add to Cart" AND "Buy Now" buttons, they coalesce under a single wrapper:

```html
<!-- wp:surecart/product-buy-buttons -->
<!-- wp:surecart/product-buy-button {"add_to_cart":true,"text":"Add To Cart"} /-->
<!-- wp:surecart/product-buy-button {"add_to_cart":false,"text":"Buy Now"} /-->
<!-- /wp:surecart/product-buy-buttons -->
```

**Never emit a bare `surecart/product-buy-button`** — the block declares an `ancestor` constraint requiring `surecart/product-buy-buttons` and will fail validation otherwise.

## Other names to avoid

These are common LLM hallucinations from training data — none exist in SureCart's inventory:

- `surecart/product-rating` → use `surecart/product-review-summary` or `surecart/product-review-average-rating-stars`
- `surecart/product-sku` → there is no SKU block; drop or use `core/paragraph` with the SKU literal
- `surecart/product-stock-status` → no stock-status block exists in SureCart; drop or use `core/paragraph` with the stock literal AND log under `dropped_features.type:"unsupported_block"`
- `surecart/product-variant-summary` → use `surecart/product-selected-variant`
- `surecart/cart-icon-button` → use `surecart/cart-menu-icon-button`

### Review-template inner-block hallucinations (v7.22.1 — surfaced 2026-05-28 Hearth & Hollow paste-test)

When emitting per-review card inner blocks inside `surecart/product-review-template`, these names are HALLUCINATED. They trip the editor's "Your site doesn't include support for this block" error.

| ❌ DO NOT EMIT (does not exist) | ✅ EMIT INSTEAD |
|---|---|
| `surecart/product-review-author-info` | `surecart/product-review-reviewer-name` (+ optional sibling `surecart/product-review-date` + `surecart/product-review-verified-badge`) |
| `surecart/product-review-rating` | `surecart/product-review-rating-stars` |
| `surecart/product-review-author` | `surecart/product-review-reviewer-name` |
| `surecart/product-review-stars` | `surecart/product-review-rating-stars` |
| `surecart/product-review-card` | (compose: `core/group` flex with the per-review inner blocks below) |

**Canonical per-review card inner blocks** (verified against `packages/blocks-next/src/blocks/product-review-list/template.js`):

- `surecart/product-review-reviewer-name` — the reviewer's name
- `surecart/product-review-verified-badge` — "Verified purchase" badge
- `surecart/product-review-date` — submission date
- `surecart/product-review-rating-stars` — per-review 5-star row (NOT `-rating`, NOT `-stars`)
- `surecart/product-review-title` — review headline
- `surecart/product-review-content` — review body text

Minimal paste-safe inner template:

```html
<!-- wp:surecart/product-review-template -->
<!-- wp:surecart/product-review-reviewer-name /-->
<!-- wp:surecart/product-review-verified-badge /-->
<!-- wp:surecart/product-review-date /-->
<!-- wp:surecart/product-review-rating-stars /-->
<!-- wp:surecart/product-review-title /-->
<!-- wp:surecart/product-review-content /-->
<!-- /wp:surecart/product-review-template -->
```

**Pattern recognition:** review-domain blocks favor the `-reviewer-name` / `-rating-stars` / `-content` / `-title` / `-date` / `-verified-badge` suffix family. The shorter `-author` / `-author-info` / `-rating` / `-stars` / `-card` forms feel natural but were never registered. Compare with HC#43's `-average-rating-breakdown` → `-breakdown` family — same pattern, separate occurrence.

When in doubt, **search `reference/full-inventory.json` for the closest real name** before emitting. If no real block exists for what the design wants, either drop the section or replace with `core/group` + `core/heading` + `core/paragraph` and log under "fallback" in the drift report.

## Self-check before emit

Run this grep sweep mentally against your candidate markup (any match = hallucination, rewrite):

```
grep -E "surecart/(variant-picker|quantity|add-to-cart-button|buy-now-button|product-price[^-]|product-list[^-]|product-review-(author|author-info|rating[^-]|stars|card|average-rating-breakdown)|cart-icon-button|product-(sku|stock-status|variant-summary))"
```

If any match, rewrite using the tables above. The `-rating[^-]` lookahead distinguishes the canonical `-rating-stars` / `-total-rating` / `-form-rating` suffixes from the hallucinated bare `-rating`.

---

## v7.2.0 — additional aliases observed in production patterns

These rules came out of auditing the 20 production block-pattern files in the SureCart plugin's own pattern library. They are NOT new hallucinations — they are real disambiguations the skill must respect.

### `surecart/columns` and `surecart/column` are NOT aliases for `core/columns` / `core/column`

Both pairs exist. They are different blocks with different APIs:

| Block | Where it lives | Width API | Wrapper class |
|---|---|---|---|
| `core/columns` + `core/column` | Anywhere | `core/column` carries `width:"36%"` → `<div class="wp-block-column" style="flex-basis:36%">` | `wp-block-columns`, `wp-block-column` |
| `surecart/columns` + `surecart/column` | **ONLY inside `surecart/upsell`** in the production library | `surecart/column` carries `width:"60%"` directly + emits `flex-basis:60%`. Also accepts `layout:{type:"constrained",contentSize:"100%"}` and emits `--sc-column-content-width:100%` CSS var | `wp-block-surecart-columns`, `wp-block-surecart-column` |

**Rule:** Outside `surecart/upsell`, never emit `surecart/columns` or `surecart/column` — use `core/*` instead. Inside `surecart/upsell`, **never** substitute `core/columns` for `surecart/columns` — the upsell flow's CSS variable hooks (`--sc-column-content-width`, `--sc-form-row-spacing`) only attach to the SureCart variants.

See: `examples/patterns/upsell-info.example.md` (the only canonical exemplar).

### `surecart/product-list-related` vs `surecart/product-list` vs `surecart/product-list-content` vs `surecart/product-list-sidebar`

Four distinct blocks; do NOT collapse:

| Block | Use case | Exemplars |
|---|---|---|
| `surecart/product-list` | Main product collection page (with header, filter, sort, search, pagination) | `list-standard`, `list-bento`, `list-carousel`, `list-row`, `list-staggered` |
| `surecart/product-list-related` | "You may also like" / "Related products" grid below a product page | `related-carousel` (incl. Bordered variant — formerly `related-carousel-alternate`, deprecated v7.16) |
| `surecart/product-list-content` | The main content (results pane) inside a sidebar layout — contains the `product-template` | `list-sidebar` |
| `surecart/product-list-sidebar` | The sticky filter column inside a sidebar layout | `list-sidebar` |

**Heuristic:** If the design says "related products" or "you may also like" near the bottom of a product page → `product-list-related`. If the design has a sidebar+grid layout → outer `product-list` with nested `product-list-sidebar` and `product-list-content`.

### Quantity stepper anatomy: 5-block hierarchy

`surecart/product-quantity` may be self-closed (`/-->`) for a default stepper, OR paired with explicit children for full control:

```
surecart/product-quantity (paired)
  surecart/product-quantity-control (paired)
    surecart/product-quantity-input-decrease /
    surecart/product-quantity-input /
    surecart/product-quantity-input-increase /
```

Used in `product-physical.example.md` for a pill-rounded themed stepper. Default form (self-closed) is in `product-standard`, etc.

Do NOT alias `surecart/quantity-decrease-button` → `surecart/product-quantity-input-decrease`; the LLM may invent a shorter form. The full canonical name is required.

### Price block hierarchy disambiguation

| Context | Use this block | Pattern reference |
|---|---|---|
| Hero price near title (single price, large display) | `surecart/product-selected-price-amount` (+ `-interval`, `-scratch-amount`, `-trial`, `-fees`, `-ad-hoc-amount`) | `product-standard`, `product-physical` hero rows |
| Multi-tier price chooser (radio-style options) | `surecart/product-price-chooser` → `surecart/product-price-choice-template` → `surecart/price-name`, `surecart/price-amount`, `surecart/price-scratch-amount`, `surecart/price-interval`, `surecart/price-trial`, `surecart/price-setup-fee` | All product-page exemplars (lower section) |
| Product card (in a grid/list) | `surecart/product-list-price` + `surecart/product-scratch-price` + `surecart/product-sale-badge` | `list-standard`, `list-carousel`, `related-carousel` |
| Upsell offer page | Generic `surecart/product-price` (rare; only legitimate use is inside `surecart/upsell`) | `upsell-info` only |

**Anti-pattern:** Mixing `surecart/product-selected-price-amount` with `surecart/price-amount` in the same group — these are NOT siblings. The selected-price-* family is for hero context (no chooser), the bare `price-*` family is for inside `product-price-choice-template`.

### `surecart/product-variant-pills` vs `surecart/product-variant-choices`

| Block | Use case | Exemplars |
|---|---|---|
| `surecart/product-variant-pills` (paired, contains `surecart/product-variant-pill`) | Pill / chip selector (toggle UI) on product pages | All `product-*` exemplars |
| `surecart/product-variant-choices` | Radio/select dropdown style | `upsell-info` only |

**Rule:** Default to pills. Switch to choices ONLY in upsell context.

### `*-template` blocks emit ONCE; never expand `.map()`

These are server-iterating templates. The skill must emit the inner structure ONE TIME. Gutenberg's server-side renderer (or the surecart context) iterates over the dataset.

| Template block | Repeats over |
|---|---|
| `surecart/product-template` | All products in the `product-list` query |
| `surecart/product-price-choice-template` | All prices in the product's price chooser |
| `surecart/product-list-filter-tags-template` | All tag filters available |
| `surecart/product-list-filter-checkboxes-template` | All checkbox filter groups |
| `surecart/product-list-sort-radio-group-template` | All sort options |
| `surecart/product-review-template` | All reviews on the page |
| `surecart/product-review-list-filter-tags-template` | All review tag filters |
| `surecart/product-review-list-filter-checkboxes-template` | All review checkbox filters |
| `surecart/cart-order-bump-template` | All suggested order bumps |

**Anti-pattern:** Seeing a JS `.map()` over a `prices` array in JSX and emitting N `<!-- wp:surecart/product-price-choice-template -->` siblings. Wrong. Emit ONE template; the server expands.

### Eight more LLM hallucinations to refuse

Beyond the existing 5 in the "Other names to avoid" section, these have been observed as LLM completions that DO NOT exist:

- `surecart/product-card` → use a `core/group` wrapping `core/cover` + `surecart/product-title` + price blocks (see `list-standard.example.md` template inner structure)
- `surecart/cart-icon` → use `surecart/cart-menu-icon-button` (the icon variant) or `surecart/cart-count` (the badge)
- `surecart/checkout-button` → use `surecart/cart-submit` (inside cart) or `surecart/upsell-submit` (inside upsell). There is no generic "checkout-button"
- `surecart/related-products` → use `surecart/product-list-related`
- `surecart/featured-image` → use `surecart/product-media` (SureCart product context) or `core/post-featured-image` (course/dark page hero context)
- `surecart/product-tags` → use `surecart/product-collection-tags` (paired) wrapping `surecart/product-collection-tag /-->`
- `surecart/review-form` → there is no inline review form block; reviews are submitted via `surecart/product-review-add-button` which opens a modal
- `surecart/order-bump` → use `surecart/cart-order-bump-template` (singular template, paired). Do NOT emit `surecart/order-bump`

### Per-card inner-block hallucinations inside `surecart/product-template` (v7.22.3 — surfaced 2026-06-02 Aurora Pro paste-test, HC#54)

When emitting the per-card body inside `surecart/product-list-related > surecart/product-template`, the natural English-derived names are hallucinations. Same family as HC#50's per-review template hallucinations.

| ❌ DO NOT EMIT (does not exist) | ✅ EMIT INSTEAD | Notes |
|---|---|---|
| `surecart/product-list-title` | `surecart/product-title` (level:2 typical for cards) | `product-title`'s ancestor allowlist already includes `surecart/product-template` — no need for a separate `list-` prefixed block |
| `surecart/product-list-image` | `core/cover {useFeaturedImage:true}` (canonical Start-Basic uses cover, NOT `surecart/product-image` directly) OR `surecart/product-image` (registered, ancestor `surecart/product-list`) | Cover is preferred per the verified Start-Basic template at `packages/blocks-next/src/blocks/product-list/block.json:140-205` |
| `surecart/product-list-name` | `surecart/product-title` | Same family as `-title` confusion |
| `surecart/product-list-card` | (compose: `core/group` wrapping cover + title + price) | No atomic card block exists |

**Why the `list-` prefix trips us up:** the directory `packages/blocks-next/src/blocks/product-price/` registers the block as `surecart/product-list-price` — a counterintuitive name/directory mismatch. The LLM extrapolates `surecart/product-list-*` as the canonical per-card prefix and invents `-title`/`-image`/`-name` to match. **Only `-price` and `-related` actually carry the `list-` prefix.** The per-card title and image use the same blocks as the hero (`product-title` and `core/cover` respectively) — their `ancestor` allowlists already include `surecart/product-template`.

**Canonical Start-Basic per-card template** (verified at `packages/blocks-next/src/blocks/product-list/block.json:140-300`):

```html
<!-- wp:surecart/product-template -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:group {"style":{"color":{"background":"#0000000d"},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;background-color:#0000000d">
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.5},"contentPosition":"top right","isDark":false,"style":{"dimensions":{"aspectRatio":"3/4"},"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"margin":{"bottom":"15px"}},"border":{"radius":"10px"}}} -->
<div class="wp-block-cover" style="border-radius:10px;margin-bottom:15px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"></div></div>
<!-- /wp:cover -->
</div>
<!-- /wp:group -->

<!-- wp:surecart/product-title {"level":2,"style":{"typography":{"fontSize":"15px","fontStyle":"normal","fontWeight":"400"},"spacing":{"margin":{"top":"0px","bottom":"5px"}}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5em","margin":{"top":"0px","bottom":"0px"}},"typography":{"lineHeight":"1"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:surecart/product-list-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /-->
<!-- wp:surecart/product-scratch-price {"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"5px","bottom":"5px"}}}} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- /wp:surecart/product-template -->
```

This is the byte-perfect canonical that ships with the plugin's own pattern library. Copy this template into `surecart/product-list-related` without modification (the layout/typography can be tuned, but the BLOCK NAMES and NESTING are load-bearing).

### Pre-emit grep extension

Add these patterns to the self-check sweep:

```
grep -E "surecart/(product-card|cart-icon$|checkout-button|related-products$|featured-image|product-tags$|review-form|order-bump$)"
grep -E "surecart/columns|surecart/column"  # only legal inside surecart/upsell
grep -E "surecart/quantity-(decrease|increase|input)-button"  # always wrong; use *-input-decrease etc.
grep -E "surecart/product-list-(title|image|name|card)([[:space:]]|/?-->|$)"  # HC#54 per-card hallucinations
```

If any of these match in non-upsell contexts, REWRITE before emitting.

---

## `core/icon` is the canonical block for built-in icon glyphs (v7.12)

WordPress 7.0+ ships a native `core/icon` block resolving 88 built-in SVG icon slugs (arrow-*, chevron-*, cart, check, plus, star-filled, etc.) via `WP_Icons_Registry`. **First choice** for any decorative icon glyph; replaces inline-SVG `core/html` L1 fallback for the 88 built-in slugs.

**Anti-pattern:** emitting `<!-- wp:html --><svg…/></svg><!-- /wp:html -->` when a built-in icon slug exists. Recovery-safe icons should use `core/icon`.

**Canonical reference:** `reference/wp-core-blocks.md § core/icon` — full attribute schema + 88-slug catalog + paste-form recipes + emission rules. All `core/icon` doctrine lives there. This alias-map entry is a pointer only.

**Quick lookup for the most-emitted slugs (in design contexts):**

| Design intent | Slug |
|---|---|
| Right arrow / "continue" / "next" | `core/arrow-right` |
| Down chevron / accordion indicator | `core/chevron-down` |
| Check mark / "included" / feature tick | `core/check` |
| Cart icon (mini-cart, "view cart") | `core/cart` |
| Star (rating, featured) | `core/star-filled` / `core/star-empty` / `core/star-half` |
| Plus / "add" / "expand" | `core/plus` |
| Search / magnifier | `core/search` |
| Info circle / tooltip trigger | `core/info` |
| Menu / hamburger | `core/menu` |
| Close / dismiss | (no `core/close` — use `core/plus` rotated 45° via theme CSS, or `core/html` for an X glyph) |
