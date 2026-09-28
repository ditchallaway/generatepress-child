# Custom HTML fallback ladder — when no block fits

Lazy-loaded. Open this file when a design element has no matching SureCart or WP-core block (`product-page-blocks.md` and `wp-core-blocks.md` both came up empty), and you need to decide between dropping, embedding raw HTML, or piggybacking on SureCart's Interactivity API stores.

**Decision precedence:**

1. WP-core primitive (`core/group`, `core/columns`, `core/heading`, `core/paragraph`, `core/image`, `core/details`, `core/buttons`, **`core/icon`**, …) — try first.
2. SureCart block — search `surecart-blocks.md` + `full-inventory.json`.
3. **This file's 3-tier `core/html` ladder** — only when 1 and 2 both fail.

> **v7.12 — `core/icon` precedence before L1.** WordPress 7.0 ships a native `core/icon` block resolving 88 built-in SVG glyph slugs (arrow-*, chevron-*, cart, check, plus, star-filled, info, etc.). For any decorative icon glyph in the design, **try `core/icon` FIRST** before reaching for `core/html` L1 inline SVG. See `reference/wp-core-blocks.md § core/icon` for the full slug catalog and emission rules. The L1 inline-SVG fallback below is now reserved for novel/custom SVG that does NOT match a built-in slug.

---

## The 3-tier ladder

### L1 — Static raw HTML

**When:** decorative content with no behavior. Custom badges, marketing flourishes, infographic chrome, hand-styled non-interactive widgets. **NOT for built-in icon glyphs** — use `core/icon` (see precedence note above; v7.12 addition).

**Icon-glyph branch:** before emitting any `core/html` block containing `<svg>` markup, check whether the glyph matches one of the 88 built-in slugs in `core/icon` (`reference/wp-core-blocks.md § core/icon`). If yes → emit `<!-- wp:icon {"icon":"core/{slug}"} /-->` instead. If no but the glyph exists as a file → `core/image` with the SVG/PNG URL. Only when neither applies does L1 inline SVG remain the right answer.

**Emit:** `core/html` with the design's literal HTML.

```html
<!-- wp:html -->
<div class="custom-promo-badge">
	<span class="badge-eyebrow">LIMITED EDITION</span>
	<span class="badge-text">Holiday Bundle 2026</span>
</div>
<!-- /wp:html -->
```

**Drift entry (mandatory):**

```json
{
	"type": "raw_html_static",
	"section": "Hero badge",
	"detail": "decorative chrome with no Gutenberg block equivalent",
	"merchant_action": "merchant edits via the Code editor; not visible in Visual mode. Add styling via theme CSS using class .custom-promo-badge."
}
```

---

### L2 — Raw HTML piggybacking on an existing SureCart store

**When:** the design needs interactive behavior that maps to an action SureCart **already exposes** (toggle cart drawer, switch variant, advance gallery image, open lightbox, increment quantity), AND a sibling SureCart block on the same emit will load the namespace's script module.

**The contract.** WordPress's Interactivity runtime hydrates `data-wp-*` directives inside any element that descends from a `data-wp-interactive='{"namespace":"X"}'` root, **but only if the script module for namespace `X` is loaded on the page**. SureCart's next-gen blocks load these modules when rendered. So if you emit a `core/html` block that references `surecart/cart::actions.toggle`, the directives only hydrate when a block like `surecart/cart-icon` is also on the page (it's the one that triggers the `surecart/cart` module load).

**Mandatory check** (Tier A item A-15 / Tier B item B-20):

1. Identify the namespace your directives use (e.g., `surecart/cart`).
2. Verify a sibling block in the same emit triggers that namespace's module load (use the table below).
3. List the dependency in the drift report's `dependency_block` field.
4. Use **only** namespaces in the allowlist below. Don't invent namespaces.
5. **Verify every `state.X` / `actions.X` reference resolves against the namespace of its nearest ancestor `data-wp-interactive` root.** Cross-namespace references (e.g., a `state.itemsCount` from `surecart/checkout` referenced inside a `surecart/cart` root) WILL silently fail — the runtime returns `undefined` and the directive renders as if absent. See "Cross-namespace references" below.

#### Namespace → dependency block allowlist

| Namespace | Sibling block(s) that load it | What you can call |
|---|---|---|
| `surecart/checkout` | `surecart/slide-out-cart`, any `surecart/cart-*` block | `state.itemsCount`, `state.subtotal`, `state.discount`, `state.hasItems` |
| `surecart/cart` | `surecart/cart-icon`, `surecart/cart-menu-icon-button`, `surecart/slide-out-cart` | `actions.toggle`, `actions.open`, `actions.close`, `state.isOpen` |
| `surecart/product-page` | `surecart/product-page` (the outer wrapper — always present) | `state.selectedVariant`, `state.selectedPriceId`, `state.quantity`, `actions.setQuantity`, `actions.selectVariant` |
| `surecart/order-bumps` | `surecart/cart-order-bumps` | `state.currentPage`, `state.hasOrderBumps`, `actions.next`, `actions.previous` |
| `surecart/product-quick-view` | `surecart/product-quick-view` | `actions.open`, `actions.close`, `state.isOpen` |
| `surecart/lightbox` | `surecart/product-media` (with `lightbox:true`) | `actions.openLightbox`, `actions.closeLightbox`, `state.isLightboxOpen`, `state.activeIndex` |
| `surecart/image-slider` | `surecart/product-media` (with `desktop_gallery:true`) | `actions.next`, `actions.previous`, `actions.goTo`, `state.activeIndex` |

**If your design's behavior doesn't map to any row above → escalate to L3.** Do not invent action names.

#### Cross-namespace references (v7.1.1 — empirically discovered bug class)

Every `data-wp-*` directive resolves against the namespace of the **nearest ancestor with `data-wp-interactive`**. A directive that references state/actions from a *different* namespace fails silently — the runtime returns `undefined` and the element renders as if the directive weren't there. The button still hydrates (it has its own resolvable directive); siblings with mismatched references render their static fallback content.

**Example bug.** A "View cart" pill button toggles the drawer (`surecart/cart::actions.toggle`) AND shows the live item count (`surecart/checkout::state.itemsCount`). Single-root markup looks reasonable but is broken:

```html
<!-- ❌ BROKEN — state.itemsCount resolves against surecart/cart, returns undefined -->
<div data-wp-interactive='{"namespace":"surecart/cart"}'>
	<button data-wp-on--click="actions.toggle">
		View cart <span data-wp-text="state.itemsCount">0</span>
	</button>
</div>
```

The button works (toggle is in `surecart/cart`); the count never updates beyond its initial "0".

**Fix — nested `data-wp-interactive` boundary** (recommended; works for every directive type):

```html
<!-- ✅ FIXED — span declares its own surecart/checkout root -->
<div data-wp-interactive='{"namespace":"surecart/cart"}'>
	<button data-wp-on--click="actions.toggle" data-wp-bind--aria-expanded="state.isOpen">
		View cart
		<span data-wp-interactive='{"namespace":"surecart/checkout"}' data-wp-text="state.itemsCount">0</span>
	</button>
</div>
```

**Alternative — inline namespace prefix** (compact; verified working in SureCart source for `data-wp-on--*`, less consistently documented for state directives like `data-wp-text` / `data-wp-bind--*`):

```html
<!-- ✅ FIXED with inline prefix — uses namespace::path for the cross-namespace reference -->
<div data-wp-interactive='{"namespace":"surecart/cart"}'>
	<button data-wp-on--click="actions.toggle">
		View cart
		<span data-wp-text="surecart/checkout::state.itemsCount">0</span>
	</button>
</div>
```

**Authoring rule:** for every `data-wp-*` directive, find the nearest ancestor `data-wp-interactive` root and confirm the referenced state/action is owned by that namespace per the allowlist table above. If it isn't, either (a) wrap the element in a nested `data-wp-interactive` declaring the correct namespace, or (b) use the inline `namespace::path` prefix. **Do not assume sibling directives can share a single namespace root** when their references span more than one store.

**Why this matters:** the v7.1 namespace allowlist correctly documented which state/actions live in which namespace, but a single root in the emit assumed all directives shared one. Verified empirically on a live install: pill button toggled (correct namespace) but item count never rendered (wrong namespace).

**Emit shape.**

```html
<!-- wp:html -->
<div class="custom-cart-banner" data-wp-interactive='{"namespace":"surecart/cart"}'>
	<button type="button" class="custom-cart-banner__btn" data-wp-on--click="actions.toggle">
		<span data-wp-text="state.itemsCount">0</span>
		items in cart →
	</button>
</div>
<!-- /wp:html -->

<!-- The sibling block that loads the surecart/cart module: -->
<!-- wp:surecart/cart-icon /-->
```

**Drift entry (mandatory):**

```json
{
	"type": "raw_html_interactive",
	"level": "L2",
	"section": "Hero cart banner",
	"namespace": "surecart/cart",
	"dependency_block": "surecart/cart-icon",
	"detail": "custom banner that toggles cart drawer using surecart/cart::actions.toggle",
	"merchant_action": "merchant must keep the surecart/cart-icon block on the page for the banner's toggle to work; pasted content survives only when merchant has unfiltered_html capability (admin or editor role)."
}
```

#### Allowed L2 directives

Only the directives below — these are the runtime's documented public API:

- `data-wp-interactive='{"namespace":"surecart/X"}'` — root attribute (mandatory on the outermost element)
- `data-wp-context='{...}'` — local component state (use `wp_interactivity_data_wp_context` only in PHP; in `core/html` write the JSON literal)
- `data-wp-on--{event}="actions.X"` — event handlers (`click`, `submit`, `change`, `keydown`, `mouseenter`, etc.)
- `data-wp-bind--{attr}="state.X"` or `="!state.X"` — bind any HTML attribute to state
- `data-wp-class--{className}="state.X"` — toggle a class
- `data-wp-text="state.X"` — set element text content (replaces innerHTML — children removed)
- `data-wp-init="callbacks.X"` — one-time mount hook
- `data-wp-each="state.list"` — iterate (rare; usually let SureCart blocks own iteration)
- `data-wp-watch="callbacks.X"` — re-run on state change

#### Forbidden in L2 (keep paste-safety)

- Inline `onclick=`/`onchange=`/`onsubmit=` — never. The Interactivity directives replace them.
- `<script>` tags inside `core/html` — KSES strips these for non-admin roles, and they break round-trip recovery.
- `<style>` tags — put CSS in the theme stylesheet; reference via `class=""` instead.
- Custom namespaces (any `data-wp-interactive` namespace not in the allowlist) — escalate to L3.

---

### L3 — Custom interactivity (drop + recommend developer follow-up)

**When:** the design needs behavior that **cannot** map to any `surecart/*` namespace in the L2 allowlist. Animated counters, custom configurators, third-party API-backed widgets, anything that needs a brand-new state machine.

**Emit:** the design's outer chrome as static `core/html` (best-effort visual fallback so the page has *something* in place), then drop the behavior + log under `dropped_features`. **Always recommend the merchant invoke `/surecart-new-block` (for a custom block) or `/surecart-new-integration` (for a third-party API-backed widget).**

```html
<!-- Best-effort static fallback for an animated stats counter -->
<!-- wp:html -->
<div class="custom-stats-counter">
	<div class="stat">
		<span class="stat__num">10,000+</span>
		<span class="stat__label">customers</span>
	</div>
	<div class="stat">
		<span class="stat__num">50ms</span>
		<span class="stat__label">avg. response</span>
	</div>
</div>
<!-- /wp:html -->
```

**Drift entry (mandatory):**

```json
{
	"type": "raw_html_interactive",
	"level": "L3",
	"section": "Animated stats counter",
	"detail": "count-up animation requires custom state machine; emitted static fallback with final values",
	"merchant_action": "to restore the count-up animation, invoke /surecart-new-block to scaffold a next-gen interactive block. If pulling stats from a third-party API, invoke /surecart-new-integration instead."
}
```

---

## Worked examples

### Example 1 — L1 decorative ribbon

Design: a thin ribbon banner across the top of a section with custom shape (notched edge) — no behavior.

```html
<!-- wp:html -->
<div class="ribbon ribbon--notched">
	<span class="ribbon__text">Free shipping over $99 — limited time</span>
</div>
<!-- /wp:html -->
```

→ `dropped_features.type:"raw_html_static"`. Merchant adds `.ribbon` and `.ribbon--notched` CSS to theme.

### Example 2 — L2 cart drawer trigger from a custom hero CTA

Design: a hero "View Cart" pill button that opens the slide-out cart drawer with the cart's current item count rendered inside.

```html
<!-- wp:html -->
<div class="hero-cart-pill" data-wp-interactive='{"namespace":"surecart/cart"}'>
	<button type="button" class="hero-cart-pill__btn" data-wp-on--click="actions.toggle" data-wp-bind--aria-expanded="state.isOpen">
		View cart
		<span class="hero-cart-pill__count" data-wp-text="state.itemsCount">0</span>
	</button>
</div>
<!-- /wp:html -->

<!-- Sibling block on the same page (any one of these triggers the surecart/cart module): -->
<!-- wp:surecart/cart-icon /-->
```

→ `dropped_features.type:"raw_html_interactive", level:"L2", namespace:"surecart/cart", dependency_block:"surecart/cart-icon"`.

### Example 3 — L2 lightbox piggyback on `surecart/product-media`

Design: a "View larger image" link below the product title that opens the gallery lightbox.

```html
<!-- wp:html -->
<a href="#" class="view-larger" data-wp-interactive='{"namespace":"surecart/lightbox"}' data-wp-on--click="actions.openLightbox">
	View larger image
</a>
<!-- /wp:html -->

<!-- Sibling: -->
<!-- wp:surecart/product-media {"lightbox":true,"desktop_gallery":true} /-->
```

→ `dropped_features.type:"raw_html_interactive", level:"L2", namespace:"surecart/lightbox", dependency_block:"surecart/product-media"`.

### Example 4 — L3 animated counter (drop with static fallback)

Design: a stat row with three counters that animate from 0 to their value when scrolled into view.

```html
<!-- wp:html -->
<div class="stats-row">
	<div class="stats-row__stat"><span class="stats-row__num">12,500</span><span class="stats-row__label">stores</span></div>
	<div class="stats-row__stat"><span class="stats-row__num">$2.1M</span><span class="stats-row__label">processed daily</span></div>
	<div class="stats-row__stat"><span class="stats-row__num">99.99%</span><span class="stats-row__label">uptime</span></div>
</div>
<!-- /wp:html -->
```

→ Emits the static chrome with final values. `dropped_features.type:"raw_html_interactive", level:"L3", merchant_action:"invoke /surecart-new-block to add the count-up animation."`.

### Example 5 — Third-party content (use `core/embed` instead)

Design: a Twitter / X timeline or Instagram embed.

```html
<!-- wp:embed {"url":"https://twitter.com/surecart","providerNameSlug":"twitter"} -->
<figure class="wp-block-embed is-type-rich is-provider-twitter wp-block-embed-twitter"><div class="wp-block-embed__wrapper">https://twitter.com/surecart</div></figure>
<!-- /wp:embed -->
```

**Don't** use L2 or L3 for embeddable third-party content — `core/embed` exists. Only fall back to `core/html` when the embed is custom (e.g., a vendor's HTML snippet that doesn't match a Gutenberg embed provider).

---

## Capability gate (merchant role)

`core/html` content survives the WP database round-trip **only when the saving user has the `unfiltered_html` capability** (administrator and editor by default; lower roles get `wp_kses_post()` filtering, which strips `data-wp-*` attributes). When emitting at L2:

- Add a single-line warning to the merchant's copy-paste instructions: *"This page uses custom HTML with WordPress Interactivity directives. You must save the page as an Administrator or Editor — Author / Contributor / lower roles will have the `data-wp-*` attributes stripped on save."*
- Log under `dropped_features` with no further action — the warning is at the top of the response, not in the drift report.

---

## Round-trip safety notes

- **Balanced tags only.** Unbalanced `<div>`, `<span>`, etc. inside `core/html` flag the block as invalid on save and may trigger recovery. Author balanced HTML and verify in the editor's code view before saving.
- **No `<script>` / `<style>` tags.** Strip them at emit time. CSS belongs in the theme; behavior belongs to the Interactivity API directives or a registered block.
- **JSON in directives must be single-quoted on the outside, double-quoted inside.** `data-wp-interactive='{"namespace":"surecart/cart"}'` survives WP's HTML serializer; the reverse (`data-wp-interactive="{\"namespace\":...}"`) does not.
- **`data-wp-text` replaces children** — never put text content inside an element that also has `data-wp-text`. The runtime overwrites it.
- **No SSR fallback.** The first paint shows whatever HTML you wrote (e.g., `<span data-wp-text="state.itemsCount">0</span>` shows `0` until the runtime hydrates and overwrites). Author the static HTML so it looks reasonable as an initial state.
