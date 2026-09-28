# SureCart blocks — full reference

Lazy-loaded. Open this file when the skill needs to look up a specific SureCart block name, its attrs, or its ancestor/parent constraints. For schema-level detail (full attribute defaults, supports tree, variations), the authoritative source is `reference/full-inventory.json` — this file is the human-readable index.

**Two systems.** SureCart has two block systems:

- **Next-gen** (`packages/blocks-next/src/blocks/`, ~153 blocks) — `apiVersion:3` server-rendered, WordPress Interactivity API. **Always prefer next-gen** when an equivalent exists.
- **Legacy** (`packages/blocks/Blocks/`, ~84 blocks) — older `apiVersion:2` Stencil-rendered. Maintenance-only, but still emit-able. Use when next-gen has no equivalent (forms, checkout, donations, customer dashboard, collections).

Block names with `surecart/product-*`, `surecart/cart-*`, `surecart/slide-out-cart-*`, `surecart/product-list-*`, `surecart/product-review-*`, `surecart/cart-order-bump-*`, `surecart/icon`, `surecart/currency-switcher`, `surecart/sticky-purchase` are **next-gen**. Everything else is **legacy** unless noted.

---

## Quick lookup — by use case

| Use case | Block(s) | System |
|---|---|---|
| Product page outer wrapper (mandatory) | `surecart/product-page` | next-gen |
| Product title / description / media | `surecart/product-title`, `-description`, `-media` | next-gen |
| Product price (single) | `surecart/product-selected-price-amount`, `-scratch-amount`, `-interval` | next-gen |
| Product price chooser (multi-plan) | `surecart/product-price-chooser` + `surecart/product-price-choice-template` | next-gen |
| Variant pills | `surecart/product-variant-pills` + `surecart/product-variant-pill` | next-gen |
| Quantity stepper | `surecart/product-quantity` | next-gen |
| Buy buttons | `surecart/product-buy-buttons` + `surecart/product-buy-button` | next-gen |
| Sticky purchase bar | `surecart/sticky-purchase` | next-gen |
| Reviews module | `surecart/product-review-*` (see Reviews section) | next-gen |
| Related products | `surecart/product-list-related` | next-gen |
| Product list / catalog | `surecart/product-list` + `surecart/product-template` + `-image`/`-title`/`-list-price` | next-gen |
| Product list filters / sort / search / pagination | `surecart/product-list-filter-*`, `-sort-*`, `-search`, `-pagination*` | next-gen |
| Slide-out cart drawer | `surecart/slide-out-cart` + line-items + coupon + subtotal + submit | next-gen |
| Cart icon / count badge | `surecart/cart-icon`, `-count`, `-icon-button`, `-menu-icon-button` | next-gen |
| Order bumps in cart | `surecart/cart-order-bumps` + `surecart/cart-order-bump-template` | next-gen |
| Icon library | `surecart/icon` | next-gen |
| Currency switcher | `surecart/currency-switcher` | next-gen |
| Checkout / payment forms | `surecart/form`, `-checkout-form`, `-payment`, `-submit`, `-address`, `-email`, `-phone`, etc. | legacy |
| Donations | `surecart/donation`, `-donation-amount`, `-product-donation*` | legacy |
| Customer account / dashboard | `surecart/customer-dashboard-button`, `-logout-button`, `-session-detail` | legacy |
| Order confirmation | `surecart/order-confirmation`, `-order-confirmation-customer`, `-order-confirmation-line-items` | legacy |
| Invoices | `surecart/invoice-*` (5 blocks) | legacy |
| Coupons (form) | `surecart/coupon` (legacy form) or `surecart/slide-out-cart-coupon` (next-gen drawer) | both |

When in doubt: search `reference/full-inventory.json` for the block name before emitting. **Never invent block names.**

---

## Next-gen — Product page (single product detail)

These blocks must be inside `surecart/product-page` (or on a page where `surecart_current_product` query var is set). Each reads product context automatically.

### Outer wrapper
- **`surecart/product-page`** — *mandatory page wrapper.* No `save()`, server-rendered, no inner `<div>`. Children sit as siblings between the comment markers. **Provides context:** `surecart/product_id` (so descendants know which product). `metadata` attr only allowed here.

### Identity
- **`surecart/product-title`** — `level` (h1–h6), `isLink`, `rel`, `linkTarget`. Renders `<h{level}>{name}</h{level}>`.
- **`surecart/product-description`** — no attrs. Renders the long description HTML.
- **`surecart/product-sale-badge`** — `text` (e.g., "Save 20%"). Shown when product has a scratch price.
- **`surecart/product-collection-tags`** — `count` (max collections shown). Container for `surecart/product-collection-tag` children.
- **`surecart/product-collection-tag`** — `isLink`. Inside `-tags`.

### Media
- **`surecart/product-media`** — `auto_height`, `desktop_gallery`, `height`, `width`, `show_thumbnails`, `thumbnails_per_page`, `hide_empty`, `lightbox`, `id`. Full gallery with thumbnails.
- **`surecart/product-selected-variant-image`** — `sizing`, `aspectRatio`, `width`, `height`, `hide_on_mobile`. Reactive to variant selection.
- **`surecart/product-image`** — same attrs as `-selected-variant-image` but renders the primary image.

### Pricing (display)
- **`surecart/product-list-price`** — container for price display in lists.
- **`surecart/product-scratch-price`** — strike-through original price.
- **`surecart/price-amount`** — formatted amount of a price.
- **`surecart/price-name`** — name of a price (e.g., "Monthly").
- **`surecart/price-interval`** — interval string (e.g., "/month").
- **`surecart/price-scratch-amount`** — strike-through amount inside a price-choice.
- **`surecart/price-setup-fee`** — one-time setup fee text.
- **`surecart/price-trial`** — trial period text (e.g., "7-day trial").
- **`surecart/product-selected-price-amount`** — currently selected price amount (reactive).
- **`surecart/product-selected-price-scratch-amount`** — currently selected scratch amount.
- **`surecart/product-selected-price-interval`** — currently selected interval.
- **`surecart/product-selected-price-trial`** — currently selected trial.
- **`surecart/product-selected-price-fees`** — currently selected fees.
- **`surecart/product-selected-price-ad-hoc-amount`** — name-your-price amount.

### Pricing (selection — chooser)
- **`surecart/product-price-chooser`** — `label`, `columns` (1–4). Container for plan options.
- **`surecart/product-price-choice-template`** — `highlight_border` (boolean). Per-plan template inside `-chooser`. **Context required:** `postId`, `surecart/product`, `surecart/price`.

### Variants
- **`surecart/product-variant-pills`** — variant selector (color/size/material chips). Container.
- **`surecart/product-variant-pill`** — `highlight_text`, `highlight_background`, `highlight_border`. Template child.
- **`surecart/product-selected-variant`** — `separator`. Renders the currently selected variant string.

### Quantity
- **`surecart/product-quantity`** — `label`, `hidden_label`, `id`. Container.
- **`surecart/product-quantity-control`** — `width`. Holds input + buttons.
- **`surecart/product-quantity-input`** — text input.
- **`surecart/product-quantity-input-increase`** — + button.
- **`surecart/product-quantity-input-decrease`** — − button.

### Purchase
- **`surecart/product-buy-buttons`** — container. **Required ancestor for `-buy-button`.**
- **`surecart/product-buy-button`** — `add_to_cart` (boolean — true for "Add to Cart", false for "Buy Now"), `show_sticky_purchase_button`, `text`, `out_of_stock_text`, `unavailable_text`, `width`. **`__experimentalSkipSerialization`** on color/spacing/border (typography NOT skipped).
- **`surecart/product-quick-view`** — `alignment`, `width`, `height`. Container.
- **`surecart/product-quick-view-button`** — `icon`, `icon_position`, `label`, `quick_view_button_type`, `width`, `direct_add_to_cart`.
- **`surecart/product-quick-view-close`** — close button for quick-view modal.

### Customer input
- **`surecart/product-line-item-note`** — `label`, `placeholder`, `help_text`. Optional note attached to the line item.

### Sticky purchase bar
- **`surecart/sticky-purchase`** — `width`. Floating bottom-bar with title + price + buy button. No legacy equivalent.

---

## Next-gen — Reviews (40 blocks total)

Full review module. Compose by combining the blocks below.

### Submission form
- **`surecart/product-review-form`** — `alignment`, `width`, `height`, `editingView`. Top-level form container.
- **`surecart/product-review-form-template`** — template for the form's inner fields.
- **`surecart/product-review-form-content`** — `label`, `placeholder`, `rows`, `text_align`. Textarea field.
- **`surecart/product-review-form-title`** — `label`, `placeholder`, `text_align`. Title input.
- **`surecart/product-review-form-rating`** — `label`, `size`, `fill_color`, `text_align`. Star rating input.
- **`surecart/product-review-form-submit-button`** — `text`, `width`. Submit button.
- **`surecart/product-review-form-close`** — close form (X) button.
- **`surecart/product-review-confirmation-template`** — content shown after successful submit.
- **`surecart/product-review-add-button`** — `icon`, `icon_position`, `icon_size`, `label`, `button_type`, `width`. CTA to open the form.

### Display (single review)
- **`surecart/product-review-template`** — per-review template (loops over reviews).
- **`surecart/product-review-title`** — review headline.
- **`surecart/product-review-date`** — `datetime`, `textAlign`, `format`. Review timestamp.
- **`surecart/product-review-content`** — review body text.
- **`surecart/product-review-reviewer-name`** — `format`. Reviewer name (full / first-only / first+initial).
- **`surecart/product-review-rating-stars`** — `fill_color`, `size`. Star icons for this review.
- **`surecart/product-review-verified-badge`** — `show_label`, `label`, `icon_size`, `icon_color`. "Verified buyer" badge.

### Summary (aggregate)
- **`surecart/product-review-summary`** — top-level summary container. **Per Rule 3** (style-conversion.md), needs an inner template (flex group → average-rating-value + stars).
- **`surecart/product-review-average-rating-stars`** — `fill_color`, `size`, `link_to_reviews`. Aggregate stars.
- **`surecart/product-review-average-rating-value`** — `link_to_reviews`. Numeric average (e.g., "4.7").
- **`surecart/product-review-total-rating`** — `show_label`, `show_for_zero_reviews`, `link_to_reviews`. Total review count.
- **`surecart/product-review-breakdown`** — `columns`, `row_gap`, `column_gap`, `fill_color`, `size`, `bar_fill_color`, `bar_background_color`. Histogram of star distribution.

### List + filters + pagination
- **`surecart/product-review-list`** — `query`. Top-level list container.
- **`surecart/product-review-list-content`** — list body.
- **`surecart/product-review-list-sidebar`** — `label`, `open`. Filter sidebar.
- **`surecart/product-review-list-sidebar-toggle`** — `label`, `icon`. Toggle button for sidebar.
- **`surecart/product-review-list-filter-checkboxes`** — checkbox filter group.
- **`surecart/product-review-list-filter-checkboxes-label`** — `label`.
- **`surecart/product-review-list-filter-checkboxes-template`** — template.
- **`surecart/product-review-list-filter-checkbox`** — single checkbox.
- **`surecart/product-review-list-filter-tags`** — applied-filters chip row.
- **`surecart/product-review-list-filter-tags-label`** — `label`.
- **`surecart/product-review-list-filter-tags-template`** — `label`.
- **`surecart/product-review-list-filter-tag`** — single tag chip.
- **`surecart/product-review-list-filter-tags-clear-all`** — `label`. "Clear all" link.
- **`surecart/product-review-list-no-reviews`** — empty state container.
- **`surecart/product-review-pagination`** — `paginationArrow`, `showLabel`. Pagination control.
- **`surecart/product-review-pagination-next`** — `label`.
- **`surecart/product-review-pagination-previous`** — `label`.
- **`surecart/product-review-pagination-numbers`** — page-number list.

---

## Next-gen — Product list / catalog (65 blocks)

For catalog pages, archive pages, and product carousels.

### Top-level
- **`surecart/product-list`** — `ids` (array of product IDs), `type` (`"all"` | `"specific"` | `"collection"`), `limit`, `collection_id`, `query` (full WP_Query args).
- **`surecart/product-list-content`** — list body.
- **`surecart/product-list-related`** — `limit`, `query`. "You may also like" — uses current product context.
- **`surecart/product-template`** — per-card template (loops over products).
- **`surecart/product-template-container`** — wrapper inside the template.

### Per-card primitives (used inside `surecart/product-template`)
- **`surecart/product-image`** — `sizing`, `aspectRatio`, `width`, `height`, `isLink`, `rel`, `linkTarget`.
- **`surecart/product-title`** — same as product-page version.
- **`surecart/product-list-price`** — formatted price string for the card.
- **`surecart/product-scratch-price`** — strike-through.

### Search / sort / filter (18 filter blocks + 5 sort blocks)
- **`surecart/product-list-search`** — search input.
- **`surecart/product-list-sort`** — sort dropdown.
- **`surecart/product-list-sort-radio-group`** + `-radio-group-label` + `-radio-group-template` + `-radio` — radio-group sort variant.
- **`surecart/product-list-filter`** — `label`, `taxonomy`. Single filter dropdown.
- **`surecart/product-list-filter-checkboxes`** — `taxonomy`. Container.
- **`surecart/product-list-filter-checkboxes-label`** — `label`.
- **`surecart/product-list-filter-checkboxes-template`** — template.
- **`surecart/product-list-filter-checkbox`** — single checkbox.
- **`surecart/product-list-filter-tags`** + `-tags-label` + `-tags-template` + `-tag` + `-tags-clear-all` — applied-filters chip row.
- **`surecart/product-list-sidebar`** — sidebar wrapper for filters.
- **`surecart/product-list-sidebar-toggle`** — open/close toggle.

### Pagination
- **`surecart/product-pagination`** — `paginationArrow`, `showLabel`.
- **`surecart/product-pagination-next`** — `label`.
- **`surecart/product-pagination-previous`** — `label`.
- **`surecart/product-pagination-numbers`** — numbered page list.

### Empty state
- **`surecart/product-list-no-products`** — empty-state container.

---

## Next-gen — Cart (43 blocks)

Slide-out drawer + cart icon + line items + coupon + subtotal + submit + order bumps.

### Drawer container
- **`surecart/slide-out-cart`** — `title`, `width`. Outermost cart drawer.
- **`surecart/slide-out-cart-header`** — `text`, `border`, `padding`, `backgroundColor`, `textColor`.
- **`surecart/slide-out-cart-items`** — `removable`, `editable`, `border`, `padding`, `backgroundColor`, `textColor`. Line items list.
- **`surecart/slide-out-cart-line-items`** — alternative line items container.
- **`surecart/slide-out-cart-subtotal`** — `label`, `border`, `padding`, `backgroundColor`, `textColor`.
- **`surecart/slide-out-cart-submit`** — `text`, `border`, `sectionBackgroundColor`, `padding`. Checkout button.
- **`surecart/slide-out-cart-coupon`** — `text`, `button_text`, `placeholder`, `collapsed`, `disabled`, `border`, `padding`, `backgroundColor`, `textColor`.
- **`surecart/slide-out-cart-message`** — `text`, `border`, `padding`, `backgroundColor`, `textColor`. Custom message.
- **`surecart/slide-out-cart-bump-line-item`** — `label`, `border`, `padding`, `backgroundColor`, `textColor`. Order-bump line item display.
- **`surecart/slide-out-cart-items-submit`** — `text`, `width`. Alternative submit.
- **`surecart/slide-out-cart-items-subtotal`** — alternative subtotal display.

### Cart icon / count
- **`surecart/cart-icon`** — floating cart icon.
- **`surecart/cart-menu-icon-button`** — `cart_icon`, `cart_menu_always_shown`. Header cart icon button.
- **`surecart/cart-count`** — number-of-items badge.
- **`surecart/cart-close-button`** — `label`, `showLabel`, `icon`. Close drawer button.
- **`surecart/cart-subtotal-amount`** — subtotal amount value.
- **`surecart/cart-subtotal-scratch-amount`** — subtotal scratch value.

### Line item primitives (template children of `surecart/slide-out-cart-items`)
- **`surecart/cart-line-item-image`** — `sizing`, `aspectRatio`, `width`, `height`.
- **`surecart/cart-line-item-title`** — product title.
- **`surecart/cart-line-item-price-name`** — price name (e.g., "Monthly").
- **`surecart/cart-line-item-amount`** — line amount.
- **`surecart/cart-line-item-scratch-amount`** — scratch amount.
- **`surecart/cart-line-item-quantity`** — quantity stepper.
- **`surecart/cart-line-item-remove`** — `show_label`, `label`, `icon`. Remove (X) button.
- **`surecart/cart-line-item-interval`** — interval string.
- **`surecart/cart-line-item-trial`** — trial period.
- **`surecart/cart-line-item-variant`** — selected variant.
- **`surecart/cart-line-item-fees`** — fees.
- **`surecart/cart-line-item-status`** — line-item status.
- **`surecart/cart-line-item-note`** — line-item note.

### Order bumps (13 blocks)
- **`surecart/cart-order-bumps`** — `hideAddedItems`, `style`. Container.
- **`surecart/cart-order-bump-template`** — per-bump template.
- **`surecart/cart-order-bump-image`** — `sizing`, `aspectRatio`, `width`, `height`.
- **`surecart/cart-order-bump-title`** — bump product title.
- **`surecart/cart-order-bump-description`** — bump description.
- **`surecart/cart-order-bump-amount`** — bump price.
- **`surecart/cart-order-bump-scratch-amount`** — strike-through.
- **`surecart/cart-order-bump-discount-badge`** — discount badge.
- **`surecart/cart-order-bump-cta`** — CTA text.
- **`surecart/cart-order-bump-add-button`** — add-to-cart button.
- **`surecart/cart-order-bump-pagination`** — `paginationArrow`, `paginationArrowSize`. Pagination.
- **`surecart/cart-order-bump-pagination-next`** — next page.
- **`surecart/cart-order-bump-pagination-previous`** — previous page.

---

## Next-gen — Utility

- **`surecart/icon`** — `icon_name`, `size`, `stroke_width`, `link_url`, `link_target`, `link_rel`, `nofollow`. Icons from the SureCart Stencil icon library.
- **`surecart/currency-switcher`** — `position`. Multi-currency dropdown.

---

## Legacy blocks (84 — maintenance-only, but emit-able for forms / checkout / collections / dashboard)

Use legacy blocks when next-gen has no equivalent. Order matters: legacy ones in this list have no next-gen equivalent today.

### Forms / inputs
- **`surecart/form`** — checkout/order form wrapper.
- **`surecart/checkout-form`** — checkout form.
- **`surecart/checkout-errors`** — display errors.
- **`surecart/conditional-form`** — show/hide section by condition.
- **`surecart/address`** — shipping address fields.
- **`surecart/email`** — email field.
- **`surecart/phone`** — phone field.
- **`surecart/first-name`** — first-name field.
- **`surecart/last-name`** — last-name field.
- **`surecart/name`** — full-name field.
- **`surecart/password`** — password field.
- **`surecart/input`** — text input.
- **`surecart/textarea`** — textarea.
- **`surecart/checkbox`** — checkbox.
- **`surecart/radio`** — radio.
- **`surecart/radio-group`** — radio group.
- **`surecart/switch`** — toggle switch.
- **`surecart/tax-id-input`** — VAT/GST field.
- **`surecart/coupon`** — checkout coupon form.

### Payment
- **`surecart/payment`** — payment options (cards/PayPal/etc).
- **`surecart/express-payment`** — express payment buttons (Apple Pay / Google Pay / Stripe Link).
- **`surecart/submit`** — submit button (`__experimentalSkipSerialization`).

### Donations
- **`surecart/donation`** — donation amount chooser.
- **`surecart/donation-amount`** — single donation amount.
- **`surecart/name-your-price`** — custom-price input.
- **`surecart/product-donation`** + `-amount` + `-amounts` + `-custom-amount` + `-prices` + `-recurring-prices` — product-driven donation widgets.

### Order confirmation / line items / totals
- **`surecart/order-confirmation`** — top-level confirmation page wrapper.
- **`surecart/order-confirmation-customer`** — customer info card.
- **`surecart/order-confirmation-line-items`** — line items summary.
- **`surecart/line-items`** — line items list (checkout).
- **`surecart/subtotal`** — subtotal display.
- **`surecart/total`** — total display.
- **`surecart/totals`** — full totals breakdown.
- **`surecart/line-item-shipping`** — shipping line.
- **`surecart/tax-line-item`** — tax line.
- **`surecart/trial-line-item`** — trial line.
- **`surecart/bump-line-item`** — order-bump line.

### Buttons
- **`surecart/button`** — generic checkout button.
- **`surecart/buy-button`** — direct-to-checkout redirect button.
- **`surecart/add-to-cart-button`** — add-to-cart button (legacy — prefer next-gen `surecart/product-buy-button`).
- **`surecart/customer-dashboard-button`** — link to dashboard.
- **`surecart/logout-button`** — logout.

### Collections
- **`surecart/collection-page`** — collection landing page wrapper.
- **`surecart/product-collection`** — collection list.
- **`surecart/product-collection-title`** — collection title.
- **`surecart/product-collection-description`** — collection description.
- **`surecart/product-collection-image`** — collection image.

### Product (legacy item primitives — prefer next-gen `surecart/product-*`)
- **`surecart/product-item`** — single product item.
- **`surecart/product-item-image`** — item image.
- **`surecart/product-item-title`** — item title.
- **`surecart/product-item-price`** — item price.
- **`surecart/product-item-list`** — list of items.
- **`surecart/price-choice`** — price choice within a product.
- **`surecart/price-selector`** — price selector dropdown.
- **`surecart/variant-price-selector`** — variant + price selector.

### Cart (legacy — prefer next-gen `surecart/slide-out-cart-*` and `surecart/cart-*`)
- **`surecart/cart`** — cart wrapper.
- **`surecart/cart-items`** — cart items.
- **`surecart/cart-header`** — cart header.
- **`surecart/cart-menu-icon`** — cart icon (use next-gen `surecart/cart-icon` instead).
- **`surecart/cart-coupon`** — cart coupon.
- **`surecart/cart-message`** — cart custom message.
- **`surecart/cart-bump-line-item`** — cart bump line item.
- **`surecart/cart-subtotal`** — cart subtotal.

### Dashboard / account / store
- **`surecart/store-logo`** — site logo.
- **`surecart/session-detail`** — session info display.
- **`surecart/shipping-choices`** — shipping options.

### Layout (legacy — prefer WP core `core/group`, `core/columns`)
- **`surecart/card`** — card wrapper.
- **`surecart/columns`** — multi-column wrapper (use `core/columns`).
- **`surecart/column`** — single column (use `core/column`).
- **`surecart/divider`** — horizontal rule (use `core/separator`).
- **`surecart/collapsible-row`** — collapsible row (use `core/details`).

### Typography (legacy — prefer `core/heading` / `core/paragraph`)
- **`surecart/heading`** — heading block.

### Order bumps (legacy — prefer next-gen `surecart/cart-order-bumps`)
- **`surecart/order-bumps`** — order bumps display.
- **`surecart/upsell`** — upsell offer block.

### Invoices
- **`surecart/invoice-details`** — invoice line-items summary.
- **`surecart/invoice-due-date`** — due date.
- **`surecart/invoice-memo`** — memo text.
- **`surecart/invoice-number`** — invoice number.
- **`surecart/invoice-receipt-download`** — download-receipt button.

---

## Registered block-style variations (v7.15)

Many `surecart/*` blocks have **registered block style variations** that change the visual chrome of a block via `className:"is-style-{variant}"`. These are paste-safe, theme-overridable, and **MUST be checked BEFORE reaching for wrapper-group chrome (HC#37)**. See `SKILL.md` HC#38 for the doctrine.

**Detection (runtime — for live audits on a SureCart install):**

```js
// In WP block editor's browser console:
wp.data.select('core/blocks').getBlockStyles('surecart/product-quantity')
// → [{name:"default",isDefault:true},{name:"borderless"},{name:"orbit"},{name:"pebble"}]
```

**Detection (build-time — refresh this catalog whenever a new PHP-registered variation lands in the plugin):**

```bash
# Lists every PHP file that calls register_block_style — that's where variations are declared.
find packages/blocks-next/src/blocks-styles -name '*.php' -print0 | \
  xargs -0 grep -l register_block_style | \
  xargs -I {} basename {}

# Or grep the JSON-registered variations (block.json `styles:` arrays):
find packages/blocks-next/src/blocks -name 'block.json' -print0 | \
  xargs -0 grep -l '"styles"'
```

Re-audit this catalog after any commit that adds a new `register_block_style()` call or expands a block.json `styles:` array.

**Known catalog (audited via `getBlockStyles()` on a live SureCart install, 2026-05-27):**

| Block | Variations | `isDefault` | Visual effect / use case |
|---|---|---|---|
| `surecart/product-quantity` | `default`, `borderless`, **`orbit`**, `pebble` | `default` | `orbit` = round pill chrome (matches most designs); `pebble` = rounded but less circular; `borderless` = no chrome (icon-only stepper) |
| `surecart/product-buy-button` | `fill`, `outline` | `fill` | `outline` for secondary "Buy Now" buttons per HC#18; omit `className` when using default `fill` |
| `surecart/product-review-add-button` | `fill`, `outline` | `fill` | **v7.15 — newly cataloged.** Same fill/outline pattern as buy-button. `outline` for muted secondary CTAs (e.g., "Write a review" in soft chrome). |
| `surecart/product-review-form-submit-button` | `fill`, `outline` | `fill` | **v7.15 — newly cataloged.** Inside review-form submission flow. Same fill/outline pattern. |
| `surecart/product-review-average-rating-value` | `none`, `parentheses`, `slash` | `none` | Display format for the numeric rating value (`4.9` / `(4.9)` / `4.9/5`). |
| `surecart/product-review-total-rating` | `default`, **`plus-sign`** | **`plus-sign`** | **CORRECTED v7.15: `plus-sign` is `isDefault:true`** (previously misdocumented). For HC#19 inline review summary (the common case), OMIT `className` — `plus-sign` is the default. To get "· 1,284 reviews" with dot prefix, emit `className:"is-style-default"` explicitly. |
| `surecart/product-quick-view-button` | `default`, `show-on-hover` | `default` | `show-on-hover` for product-card designs that hide quick-view by default (most cards). For always-visible variant, omit `className`. |

**Block-level variations** (not `is-style-*` — these are `block.json#variations` entries that affect the editor inserter and round-trip resolution via `isActive`):

| Block | Variations | `isDefault` | Use case |
|---|---|---|---|
| `surecart/product-buy-button` | `cart` (`add_to_cart:true`, text `"Add To Cart"`), `buy` (`add_to_cart:false`, text `"Buy Now"`) | `cart` | **CRITICAL — block.json default for `add_to_cart` is `false`**; the `cart` variation overrides to `true`. Editor uses `isActive:["add_to_cart"]` to determine which variation the markup matches. Skill emission: set `add_to_cart` + `text` explicitly. Don't rely on defaults. |
| `surecart/product-media` | `slider` (`desktop_gallery:false`), `gallery` (`desktop_gallery:true`) | `slider` | Editor's `isActive:["desktop_gallery"]` resolves the variation. Emit `desktop_gallery:true` for the gallery (grid) layout; omit for the default slider (carousel) layout. |

**Other styles may exist** — when you encounter chrome that doesn't match the design's intent, ALWAYS audit `getBlockStyles(blockName)` for that block before assuming the block can't be styled. Append discovered variations to this table.

### How to apply

In your block JSON, set the `className` attr to `"is-style-{variant}"`:

```html
<!-- wp:surecart/product-quantity {"label":"Quantity","hidden_label":true,"className":"is-style-orbit","style":{"spacing":{"padding":{"top":"0","bottom":"0"}}}} /-->
```

The `is-style-{variant}` className is what Gutenberg uses internally to apply the style variation's CSS. Don't invent variation names — only use ones returned by `getBlockStyles()`.

### Special block attrs (v7.15)

A few `surecart/*` blocks have attrs the skill should know about beyond the standard `style.{...}` tree:

| Block | Attr | Type | Notes |
|---|---|---|---|
| `surecart/product-quantity` | `hidden_label` | boolean (default `false`) | When `true`, hides the visible "Quantity" label above the stepper. The a11y `label` attr is still required (defaults to `"Quantity"`). Apply whenever the design has no visible label. |
| `surecart/product-quantity` | `label` | string (default `"Quantity"`) | Accessible label text. Keep set even when `hidden_label:true`. |
| `surecart/product-buy-button` | `add_to_cart` | boolean | `true` = "Add to Cart" (default), `false` = "Buy Now" |
| `surecart/product-buy-button` | `text` | string | Button label. Mandatory per HC#18. |
| `surecart/product-buy-button` | `out_of_stock_text` | string | Optional override for out-of-stock state |
| `surecart/product-buy-button` | `unavailable_text` | string | Optional override for unavailable state |
| `surecart/product-buy-button` | `show_sticky_purchase_button` | boolean | When `true`, the button is mirrored in the auto-rendered sticky-purchase bar |
| `surecart/product-buy-button` | `width` | number | 25/50/75/100 percent — produces `wp-block-button__width-{N}` class |
| `surecart/product-quick-view-button` | `icon` | string (default `"plus"`) | Built-in icon slug for the button glyph. Other common values: `"eye"`, `"search"`. |
| `surecart/product-quick-view-button` | `icon_position` | enum (default `"before"`) | `"before"` / `"after"` — where the icon sits relative to the label text. |
| `surecart/product-quick-view-button` | `label` | string (default `"Add"`) | Button label. Skill override common: `""` (icon-only) or `"Quick view"`. |
| `surecart/product-quick-view-button` | `direct_add_to_cart` | boolean (default `true`) | When `true`, clicking adds to cart directly (skips the quick-view modal). When `false`, opens the modal. Design intent: most shop-page cards want the modal (`false`); simpler "Add" buttons want direct add (`true`, default). |
| `surecart/product-quick-view-button` | `quick_view_button_type` | enum (default `"both"`) | `"both"` / `"icon"` / `"label"`. Controls whether button shows icon+label, icon-only, or label-only. |
| `surecart/sticky-purchase` | `multiple` | boolean (block.json `"multiple":false`) | **Block-level constraint:** only ONE `surecart/sticky-purchase` allowed per page. Multiple instances trigger a "block already exists" editor warning + the second one is stripped on save. |
| `surecart/sticky-purchase` | `width` | number | 25/50/75/100 percent — produces wp-block-button__width-{N} class (mirrors buy-button). |
| `surecart/sticky-purchase` | (supports) `currencyConversion` | boolean (default `true` in supports) | Block subscribes to the currency-switcher store; price re-renders when shopper changes currency. No emission-time attr; informational. |
| `surecart/product-price-chooser` | (supports) `currencyConversion` | boolean (default `true`) | Same as sticky-purchase — subscribes to currency switcher. |
| `surecart/product-price-choice-template` | (forced layout) | — | Layout is **forced flex** (`allowSwitching:false`, `allowInheriting:false` in block.json). Skill must NOT emit `layout.type:"grid"` or any non-flex variant — recovery on round-trip. `__experimentalBorder` supports color/radius/width (no `style`). |
| `surecart/product-variant-pill` | (inserter constraint) | — | `inserter:false` in block.json — block is **template-only**, cannot be inserted via the editor's block library. Skill must ONLY emit inside `surecart/product-variant-pills` parent — never standalone. |
| `surecart/product-template` | (inserter constraint) | — | `inserter:false` AND `layout.__experimentalDefault:"grid"`. Emit only inside `surecart/product-list`/`-related`; default layout is grid (override per design). |
| `surecart/product-title` | (ancestor extension) | — | Ancestors are `surecart/product-page` OR `surecart/sticky-purchase` OR `surecart/product-template`. The sticky-purchase ancestor is the common omission: skill must allow `surecart/product-title` inside sticky-purchase too. |
| `surecart/product-buy-buttons` | (supports) `__experimentalExposeControlsToChildren` | boolean (`true`) | Child buy-button's color/typography props can be set at the wrapper level and cascade. Allows wrapper-level styling without skipSerialization carve-outs. |

---

## Validating an emit

For every `surecart/*` block emitted:

1. **Block name exists** in this file or `reference/full-inventory.json`. If you can't find it, search `full-inventory.json` for the exact string. **No exact match → do not emit; search for the right name or fall back to a `core/*` primitive.**
2. **Attributes are supported** by the block — search `full-inventory.json` for the block, check `attributes.{name}`. Invented attrs trigger recovery on round-trip.
3. **Ancestor / parent constraints satisfied** — `full-inventory.json` lists `ancestor[]` and `parent[]` per block. E.g., `surecart/product-buy-button` MUST be inside `surecart/product-buy-buttons`; `surecart/cart-line-item-*` MUST be inside `surecart/slide-out-cart-line-items` (or `-items`).
4. **`__experimentalSkipSerialization` carve-out applied** when relevant — affects `surecart/product-buy-button` (color + spacing + border, NOT typography), `surecart/submit`, and a handful of others. Check `supports.__experimentalSkipSerialization` in `full-inventory.json`.
5. **Preload entry exists in `app/config.php`** for any block whose underlying Stencil components (`sc-*` tags) need to be ready on first paint. Logged in drift report under `stencil_components_used[]`.
