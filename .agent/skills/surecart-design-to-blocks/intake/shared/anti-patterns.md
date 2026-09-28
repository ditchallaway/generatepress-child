# Anti-patterns — banned design moves (positive replacements)

These constraints apply to **both** product-detail and shop-page surfaces. They are derived from production failure modes (the 47 HCs in the master `SKILL.md`) and the doctrine-reversal table.

The rules below are written as **positive replacements**, not negatives — LLMs follow "do Y instead" 2–3× more reliably than "do not X". The emitted Claude Design prompt embeds these in its §7 prohibitions section.

Each rule is tagged with the constraint family it serves: `[SC-DATA]` SureCart server-rendered data, `[SC-BLOCK]` SureCart block grammar, `[WP-CORE]` WordPress core block capability.

## 1. Product data lives in placeholders, not literal text

**[SC-DATA]**

Product title, price, buy-button label, variant chips, and review stars are rendered server-side from the SureCart API. Hand-authoring them as plain HTML breaks server binding.

**Use placeholders with triple-channel encoding:**

```jsx
<h1
  className="sc-bind sc-bind--title sc-h1"
  data-sc-bind="product.title"
>
  {/* sc:bind=product.title */}
  Your Product Name
</h1>
```

For prices, use the selected-price family — never a literal currency string:

```jsx
<div className="sc-bind sc-bind--price" data-sc-bind="product.price">
  <span data-sc-bind="product.scratch_amount">$199</span>
  <span data-sc-bind="product.amount">$149</span>
  <span data-sc-bind="product.interval">/mo</span>
</div>
```

## 2. Static markup only — motion is added post-paste by the theme

**[WP-CORE]**

WordPress core blocks have no animation primitives. CSS `animation`, `transition`, `transform`, `@keyframes`, and parallax effects are silently dropped during Gutenberg parse/save round-trip.

**Design as if the page is a still frame.** Visual motion (hover states, scroll reveals, parallax) is added by the merchant's theme CSS after they paste your markup. Your job is the resting state, structured cleanly.

## 3. Layout flows top-to-bottom — sticky chrome via semantic wrapper, not raw CSS

**[WP-CORE], [SC-BLOCK] HC#25**

`position:fixed` and `position:absolute` for layout chrome do not survive Gutenberg save round-trip (the inline-style mirror is set-equality-validated and silently corrected).

**Sticky headers use a `<StickyHeader/>` semantic wrapper** that maps to `core/group` with `is-position-sticky` class. Do **not** write `position:sticky;top:Npx;z-index:N` in your CSS — the class carries all the behavior; the inline mirror is wrong-form (HC#25 doctrine reversal v7.13).

```jsx
<StickyHeader>
  <Brand/> <Nav/> <CartIcon/>
</StickyHeader>
```

Map to: `<!-- wp:group {"className":"is-position-sticky"} -->` — no inline `style="position:sticky;..."`.

## 4. Visible content lives in real DOM nodes

**[WP-CORE]**

CSS `::before` / `::after` pseudo-element content is silently dropped on Gutenberg save (for non-administrator merchant accounts; `wp_kses_post` filters them out).

**Every glyph, badge, divider dot, decoration is a real DOM node** — a `<span>`, a `<div>`, or an `<Icon slug="..."/>`. If you would have used `::before { content: "→" }`, write `<Icon slug="arrow-right"/>` instead.

## 5. Surface markers are the only HTML comments in your output

**[SC-BLOCK] HC#45**

HTML comments are silently dropped from inside `apiVersion:3` server-rendered blocks (the entire inner-block tree drops with them — cascade failure through up to 9 ancestor levels per production paste-tests).

**Use JSX comments only**, and only the surface markers from the prompt template:

- `{/* SURFACE: PRODUCT-TEMPLATE */}` — product-detail loop-driven region
- `{/* SURFACE: STATIC-CONTENT */}` — product-detail static authoring region
- `{/* SURFACE: FILTER-CHROME */}` — shop-page filter UI
- `{/* SURFACE: PRODUCT-GRID */}` — shop-page card grid (one canonical card)
- `{/* SURFACE: PAGE-CHROME */}` — shop-page hero / footer

**No descriptive HTML comments anywhere** ("// Hero section" labels, TODO comments, section dividers). Either inline them as code commentary OR omit them entirely. The converter strips JSX surface markers before Gutenberg emission.

## 6. Use named `core/icon` slugs from the catalog — not custom SVG

**[WP-CORE] HC#32**

WordPress 7.0+ ships `core/icon` as a native block resolving 88 built-in SVG slugs via the WP icon registry. Custom SVGs render but inflate markup and lose the design-system benefits.

**Pick from the 88-slug catalog grouped by intent:**

- Cart/checkout: `cart`, `receipt`, `payment`, `store`
- Navigation/direction: `arrow-right`, `arrow-down`, `chevron-down`, `chevron-up`, `chevron-left`, `chevron-right`, `chevron-up-down` (12 total)
- Status/feedback: `check`, `caution`, `error`, `info`, `tip`, `shield`, `published`
- Social/share: `share`, `people`, `envelope`, `comment`
- Misc common: `search`, `menu`, `close`, `plus`, `minus`, `star-filled`, `star-empty`, `star-half`, `block-default`

(Full 88-slug catalog: see `reference/wp-core-blocks.md` icon table — embedded in the constraint digest grouped by semantic intent.)

If no slug semantically matches your design need, emit `<Icon slug="block-default"/>` and a `// TODO: needs custom icon` JSX comment. Do **not** inline custom SVG path data > 60 characters.

## 7. First card in PRODUCT-GRID is canonical — additional cards are visually identical padding

**[SC-BLOCK] (shop-page only)**

`surecart/product-template` is a server-iterated grid. The merchant's products fill the grid at render time — your design's job is to show **one card template**, not N populated cards.

**Render the first card as the canonical template.** You MAY render 2–6 additional cards for visual completeness, but they MUST be **structurally identical** to the first — vary only the product photo and title. The converter discards cards 2–N and uses card #1 as the iteration template.

If you render 6 cards with different layouts (one with badge, two without; some with quick-view, some without), the converter cannot determine which variant is canonical and bails to a "Start Basic" template picker — a Tier A defect.

## 8. "Add to Cart" + "Buy Now" render adjacent as siblings, never stacked rows

**[SC-DATA] HC#18**

`surecart/product-buy-buttons` wraps both buy buttons as inline siblings. Designing them on separate rows (with margin between) violates the wrapper's flex layout and produces visual ugliness post-paste.

**Use the buy-button group:**

```jsx
<BuyButtons>
  <BuyButton primary addToCart/>
  <BuyButton outline/>
</BuyButtons>
```

Where:
- Primary uses `addToCart={true}` and the default filled style
- Secondary uses `addToCart={false}` and `<BuyButton outline/>` → maps to `className:"is-style-outline"`

The two buttons live on ONE row, separated by the buy-buttons wrapper's gap. Never `<div className="row1"><BuyButton/></div><div className="row2"><BuyButton/></div>`.

## 9. Pricing logic is server-rendered — show one example state

**[SC-DATA]**

Subscription intervals, scratch prices, trial periods, setup fees, and pay-what-you-want amounts are all server-rendered from the merchant's price configuration. Your design must show **one canonical state**, not branching logic.

**Use the selected-price family placeholders:**

- `<ProductPrice/>` → `surecart/product-selected-price-amount` (the resolved amount)
- `<PriceScratch/>` → `surecart/product-selected-price-scratch-amount` (strikethrough original)
- `<PriceInterval/>` → `surecart/product-selected-price-interval` ("/mo", "/yr")
- `<PriceTrial/>` → `surecart/product-selected-price-trial` ("14-day free trial")
- `<PriceFees/>` → `surecart/product-selected-price-fees` (setup fees, processing)
- `<PriceChooser/>` → `surecart/product-price-chooser` (tier picker for multi-plan products)

Do not write JSX conditionals like `{subscription ? "/mo" : ""}` — the placeholder block resolves correctly at server-render time based on the product's actual configuration.

## 10. Use only vocabulary components — see the alias map for hallucinated names

**[SC-BLOCK]**

Designers reach for natural-language block names that don't exist in SureCart. The prompt's §7b alias map covers 16 known hallucinations.

**Quick reference (full map in `prompt-template.md` §7b):**

| Don't say | Say | Notes |
| --- | --- | --- |
| `variant-picker` | `<VariantPills/>` | product-variant-pills |
| `quantity-input` | `<Quantity/>` | product-quantity |
| `add-to-cart-button` / `buy-now-button` | `<BuyButton/>` | inside `<BuyButtons/>` |
| `product-rating` | `<ReviewStars/>` | inline summary |
| `product-sku` / `product-stock-status` | (drop or plain text) | no block exists |
| `related-products` | `<RelatedProducts/>` | product-list-related (paired) |
| `featured-image` | `<ProductMedia/>` | product-media |
| `cart-icon` | `<CartIcon/>` | cart-menu-icon-button |
| `checkout-button` | (forbidden) | cart/upsell only |
| `product-card` | (compose: group > cover > title > price) | no atomic block |
| `product-tags` | `<ProductTags/>` | product-collection-tags |
| `review-form` | `<AddReviewButton/>` | product-review-add-button |
| `order-bump` | (forbidden) | template-only |
| `product-price` (chooser) | `<PriceChooser/>` | distinct from inline `<ProductPrice/>` |
| `review-list` | `<ReviewSection/>` | full-width section, paired template |

When in doubt, compose from `core/group` + standard children rather than invent a SureCart block name.
