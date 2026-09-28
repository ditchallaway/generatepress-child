# Claude Design prompt template — product-detail surface

This file is the source-of-truth template that the intake phase fills with the merchant's questionnaire answers and emits as a fenced markdown block. The merchant copies it and pastes into design.claude.com.

## Workflow (after questionnaire completes)

1. Collect questionnaire answers from the prior turn(s) — see `questions.md` for the slot names.
2. Resolve Q5 (visual mood) → exemplar handle via `shared/typography-presets.md` mapping table.
3. Resolve Q2 (accent) → preset slug via `shared/palette-presets.md` lookup. If the merchant gave a hex with no close preset (ΔE > 6 from all known slugs), use `surecart-custom-accent` and register the hex.
4. Substitute every `{{SLOT}}` below with the resolved value.
5. Embed the compressed exemplar text for Q5's mood under §5 (from `shared/typography-presets.md` exemplar references).
6. Embed the constraint digest (`intake/constraint-digest.md`) inline AT the §7 / §7b boundary — that's where the alias map + extra HC bullets live.
7. Emit the populated template as ONE fenced markdown block to the merchant, prefixed with the standard intake closing.

## Closing message (always emit first, above the fenced prompt)

> I've prepared a Claude Design prompt for you based on your answers. Copy everything in the fenced block below and paste it into [design.claude.com](https://design.claude.com). Once Claude Design produces your export, come back here and paste/attach it — I'll convert it to paste-ready Gutenberg markup.
>
> If you want to refine any answer (mood, accent, sections), just tell me which one and re-invoke me.

Then emit the fenced template (below).

---

## Template body (substitute `{{SLOT}}` then emit between fenced ```` ```md ```` markers)

````md
# SureCart Product Page — Design Brief

## §0. Role + non-negotiable framing

You are designing for production WordPress e-commerce. Output is mechanically compiled into WordPress Gutenberg blocks by a server-side renderer with a fixed grammar — like generating MIDI for a sampler rather than recording audio. Visual flair beyond what the grammar supports cannot be preserved and will be discarded during compilation.

The constraints in §7 are not stylistic preferences; they are the grammar of the target format. Producing beautiful work means producing work that survives compilation intact.

## §1. Output contract

Produce a Claude Design project with the canonical 4-file structure:
- `product-page.jsx` — page composition
- `sections-1.jsx` — first half of section components
- `sections-2.jsx` — second half of section components
- `assets/colors_and_type.css` — `:root { --token: value }` design tokens

Use **only** components from §2 visual vocabulary. Annotate render surfaces with JSX comments from §3. Style with CSS variables from §4 (raw hex reserved for non-color literals only).

## §2. Visual vocabulary you may use

Each line is a component you may use and its canonical SureCart block slug. Do not invent component names — see §7b alias map if a name you reach for is not here.

```jsx
<ProductTitle/>                   // surecart/product-title
<ProductGallery/>                 // surecart/product-media
<ProductMedia/>                   // surecart/product-media (alias)
<ProductPrice/>                   // surecart/product-selected-price-amount
<PriceScratch/>                   // surecart/product-selected-price-scratch-amount
<PriceInterval/>                  // surecart/product-selected-price-interval
<PriceTrial/>                     // surecart/product-selected-price-trial
<PriceFees/>                      // surecart/product-selected-price-fees
<PriceChooser/>                   // surecart/product-price-chooser
<BuyButton primary addToCart/>    // surecart/product-buy-button (add_to_cart:true)
<BuyButton outline/>              // surecart/product-buy-button (is-style-outline)
<BuyButtons>...</BuyButtons>      // surecart/product-buy-buttons (wrapper)
<VariantPills axis="color"/>      // surecart/product-variant-pills
<VariantPills axis="size"/>       // surecart/product-variant-pills
<Quantity/>                       // surecart/product-quantity
<ReviewStars/>                    // surecart/product-review-summary (inline rating row)
<ReviewSection/>                  // surecart/product-review-list (FULL-WIDTH section)
<RelatedProducts/>                // surecart/product-list-related (FULL-WIDTH grid)
<ProductTags/>                    // surecart/product-collection-tags
<AddReviewButton/>                // surecart/product-review-add-button
<StickyHeader>...</StickyHeader>  // core/group + is-position-sticky class
<CartIcon/>                       // surecart/cart-menu-icon-button
<Icon slug="..."/>                // core/icon (88 built-in slugs — see §4 catalog)
<Pill text="..."/>                // core/button + is-style-fill (small)
```

Use `core/group`, `core/columns`, `core/cover`, `core/heading`, `core/paragraph`, `core/buttons`, `core/image`, `core/separator`, `core/details`, `core/list`, `core/table`, `core/html` for static content — composed under `{{/* SURFACE: STATIC-CONTENT */}}`.

## §2b. Block-style variations (visual hints)

| Component | Variation | Visual effect |
| --- | --- | --- |
| `<BuyButton/>` | `is-style-outline` | secondary CTA — transparent bg + border |
| `<QuickViewButton/>` | `is-style-show-on-hover` | hidden until card hover |
| `<ProductGallery/>` | `is-style-orbit` | circular thumbnail orbit |
| `<Quantity/>` | `is-style-borderless` / `is-style-pebble` | borderless / pill-shaped stepper |
| `<core/Image/>` | `is-style-rounded` | corner-rounded |
| `<core/Separator/>` | `is-style-dots` / `is-style-wide` | dotted / full-width separator |
| `<core/Button/>` | `is-style-fill` / `is-style-outline` | filled / outlined button |
| `<core/Quote/>` | `is-style-plain` | unboxed quote |
| `<core/Table/>` | `is-style-stripes` | zebra-row table |

Apply via `className="is-style-{variant}"`.

## §3. Render surfaces

Annotate your JSX with JSX comments to mark the two surfaces. The converter strips these markers before Gutenberg emission — they are routing-only.

```jsx
{/* SURFACE: PRODUCT-TEMPLATE */}
  // ...all loop-driven product content lives here:
  // hero (gallery + title + price + variants + buy buttons), sticky purchase bar, etc.
{/* /SURFACE: PRODUCT-TEMPLATE */}

{/* SURFACE: STATIC-CONTENT */}
  // ...static authoring content lives here:
  // feature grids, FAQ, related products, custom HTML sections, footer.
{/* /SURFACE: STATIC-CONTENT */}
```

**Placement rule:** surface markers appear ONLY at the boundary between top-level sections — never nested inside a component subtree. If you nest a `{/* SURFACE: ... */}` marker inside a `<Hero>` or `<Section>`, the converter routes the inner block tree to the wrong destination.

## §4. Style tokens (CSS variables, not raw hex)

Use these CSS variables for every color and font reference in `colors_and_type.css` and inline style:

```css
:root {
  --sc-accent: var(--wp--preset--color--{{ACCENT_PRESET_SLUG}});
  --sc-text:   var(--wp--preset--color--surecart-text);
  --sc-bg:     var(--wp--preset--color--surecart-bg);
  --sc-display: var(--wp--preset--font-family--{{DISPLAY_FONT_SLUG}});
  --sc-body:    var(--wp--preset--font-family--{{BODY_FONT_SLUG}});
}
```

Raw `#RRGGBB` is reserved for non-color literals only (decorative shadows, opacity stops). The accent resolves to **`{{ACCENT_PRESET_SLUG}}`** (`{{ACCENT_RESOLVED_HEX}}` — for your visual reference only; the emitted CSS uses the preset variable).

**Palette discipline.** Use ONLY the named accent slug above (`{{ACCENT_PRESET_SLUG}}`) and this small SureCart neutral palette: `surecart-text`, `surecart-text-muted`, `surecart-text-inverse`, `surecart-bg`, `surecart-surface`, `surecart-border`. Do **NOT** extrapolate companion slugs — no `-100` / `-200` / `-700` / `-900` shades of the accent, and no thematically-adjacent named slugs like `surecart-oak-900`, `surecart-olive-700`, `surecart-ochre-500`, `surecart-cream-50`. Those slugs do NOT exist in the merchant's `theme.json` and produce silently-failing references at server-render time. When the design needs additional shades or earth tones for visual depth, write raw `#RRGGBB` literals — the converter handles them via D7 dual-emit and they render correctly on every theme. The accent + 6 neutrals above are the only slug names guaranteed to resolve.

**`core/icon` catalog (88 slugs, semantic groups):**
- Cart/checkout: `cart`, `receipt`, `payment`, `store`
- Navigation: `arrow-down`, `arrow-up`, `arrow-left`, `arrow-right`, `chevron-down`, `chevron-up`, `chevron-left`, `chevron-right`, `chevron-up-down`
- Status: `check`, `caution`, `error`, `info`, `tip`, `shield`, `published`
- Social: `share`, `people`, `envelope`, `comment`
- Common: `search`, `menu`, `close`, `plus`, `minus`, `star-filled`, `star-empty`, `star-half`, `heart`, `block-default`

If no slug semantically matches your need, emit `<Icon slug="block-default"/>` and a `// TODO: needs custom icon` JSX comment. Do **not** inline custom SVG path data > 60 chars.

## §5. Reference design — visual anchor

Use this structural shape as your starting point (paste-tested **{{EXEMPLAR_HANDLE}}**):

```jsx
{/* SURFACE: PRODUCT-TEMPLATE */}
<StickyHeader> :: brand-mark + nav + <CartIcon/> </StickyHeader>
<Hero cols="2">
  <ProductGallery is-style-orbit/>
  <Pill text="{{HERO_PILL_TEXT}}"/>
  <ProductTitle/>
  <ReviewStars/>            {{/* inline summary */}}
  <ProductPrice/> <PriceScratch/> <PriceInterval/>
  {{VARIANT_BLOCK}}
  <Quantity is-style-pebble/>
  <BuyButtons>
    <BuyButton primary addToCart/>
    <BuyButton outline/>
  </BuyButtons>
</Hero>
{{HERO_TRAILING_SECTIONS}}
{/* /SURFACE: PRODUCT-TEMPLATE */}

{/* SURFACE: STATIC-CONTENT */}
{{STATIC_SECTIONS}}
{/* /SURFACE: STATIC-CONTENT */}
```

(`{{STATIC_SECTIONS}}` lists merchant-selected sections from Q4 — e.g. `<ReviewSection/>`, `<FAQ/>`, `<RelatedProducts/>`, `<HowItWorks/>`, `<PressBand/>`, `<ComparisonTable/>`, `<StickyBar/>`.)

## §6. Merchant brief

- **Brand mood:** {{Q5_VISUAL_MOOD}} (visual reference: `{{EXEMPLAR_HANDLE}}`).
- **Product type:** {{Q1_PRODUCT_TYPE}}.
- **Pricing model:** {{Q3B_PRICING}}.
- **Variants:** {{Q3A_VARIANTS}}.
- **Sections to include:** {{Q4_SECTIONS}}.
- **Typography:** {{Q6_TYPOGRAPHY}}.
- **Accent color:** {{Q2_ACCENT_DESCRIPTION}} (preset: `{{ACCENT_PRESET_SLUG}}`).

Photography, copy tone, product specifics: invent placeholders consistent with the product type — the merchant will swap them post-paste. Title, price, buy-button text are **server-rendered** (see §7 rule 1) — use the placeholder components from §2.

## §7. Hard prohibitions

1. **[SC-DATA]** Product title, price, and buy-button label are **not literal text**. Use the placeholder components: `<ProductTitle/>`, `<ProductPrice/>` (+ family), `<BuyButton text="..."/>`. The text inside `<BuyButton/>` is the button LABEL ("Add to Cart"), not the product name.
2. **[SC-BLOCK]** Use only the components in §2 and the §7b alias map. Specifically forbidden hallucinated names: `variant-picker`, `quantity-input`, `add-to-cart-button`, `buy-now-button`, `product-rating`, `product-sku`, `product-stock-status`, `related-products`, `featured-image`, `cart-icon`, `checkout-button`, `product-card`, `product-tags`, `review-form`, `order-bump`, `review-list`. See §7b for the canonical replacement.
3. **[SC-DATA]** "Add to Cart" and "Buy Now" buttons render adjacent as siblings inside `<BuyButtons>`, never on stacked rows.
4. **[WP-CORE]** Static markup only — no `animation`, `transition`, `transform`, `@keyframes`, parallax. Motion is added by the merchant's theme CSS after paste. Design as the resting state.
5. **[WP-CORE]** Layout flows top-to-bottom in normal document order. Sticky chrome uses the semantic `<StickyHeader/>` wrapper only. Do NOT inline-write `position:sticky;top:Npx;z-index:N` in CSS — the class `is-position-sticky` carries all the behavior; the inline mirror is silently corrected and breaks set-equality validation.
6. **[WP-CORE]** All visible content lives in real DOM nodes. Every glyph, badge, decoration is a `<span>`, `<div>`, or `<Icon slug="..."/>`. No `::before` / `::after` content — silently dropped.
7. **[SC-BLOCK]** The only HTML comments in your output are the four `{/* SURFACE: ... */}` markers from §3. Other JSX comments (`{/* TODO */}`) are fine; HTML comments inside what becomes an `apiVersion:3` region silently drop the inner block tree (HC#45 cascade — up to 9 ancestor levels affected).
8. **[WP-CORE]** Use named `<Icon slug="..."/>` from §4 catalog. If no slug semantically matches your need, emit `<Icon slug="block-default"/>` and a `// TODO: needs custom icon` JSX comment. Do not inline custom SVG path data > 60 chars.
9. **[SC-DATA]** Pricing/subscription/scratch logic is server-rendered. Render ONE example state. Use `<PriceScratch/>`, `<PriceInterval/>`, `<PriceTrial/>`, `<PriceFees/>`, `<PriceChooser/>` placeholders — do not write JSX conditionals like `{subscription ? "/mo" : ""}`.
10. **[SC-BLOCK]** Reviews and related products are FULL-WIDTH sections, not inline components. Use `<ReviewSection/>` (not `<ReviewSummary/>`) for the review list/histogram, and `<RelatedProducts/>` for the related grid. The inline `<ReviewStars/>` is only the rating row in the hero.

## §7b. Extended alias map (designer-time → canonical)

| Don't say | Say | Notes |
| --- | --- | --- |
| `variant-picker` | `<VariantPills/>` | `surecart/product-variant-pills` |
| `quantity-input` | `<Quantity/>` | `surecart/product-quantity` |
| `add-to-cart-button` / `buy-now-button` | `<BuyButton/>` | inside `<BuyButtons/>` wrapper |
| `product-rating` (inline) | `<ReviewStars/>` | `surecart/product-review-summary` |
| `product-sku` / `product-stock-status` | (drop or plain `<p>` text) | no block exists |
| `related-products` | `<RelatedProducts/>` | `surecart/product-list-related` (paired template required) |
| `featured-image` | `<ProductMedia/>` | `surecart/product-media` — NOT `core/post-featured-image` |
| `cart-icon` | `<CartIcon/>` | `surecart/cart-menu-icon-button` |
| `checkout-button` | (forbidden on product page) | cart/upsell template only |
| `product-card` | (compose: `core/group` > `core/cover` > title > price) | no atomic card block |
| `product-tags` | `<ProductTags/>` | `surecart/product-collection-tags` |
| `review-form` | `<AddReviewButton/>` | `surecart/product-review-add-button` |
| `order-bump` | (forbidden on product page) | upsell template only |
| `product-price` (chooser variant) | `<PriceChooser/>` | distinct from inline `<ProductPrice/>` |
| `review-list` | `<ReviewSection/>` | full-width paired-template section |
| `nav-menu` | `core/navigation` | core block, not a SureCart block |

## §8. Self-check (advisory only)

Your output is also validated by an automatic converter linter on receipt. The linter will reject and ask you to regenerate if any of these is missing:

- `{/* SURFACE: PRODUCT-TEMPLATE */}` and `{/* SURFACE: STATIC-CONTENT */}` appear at top level (not nested)
- At least one of `<ProductTitle/>`, `<ProductPrice/>`, `<BuyButton/>` is present
- No `<svg>` with path data > 60 characters (use Icon slug instead)
- No component name appears outside §2 vocabulary or §7b alias map
- No CSS rule contains `@keyframes`, `animation:`, `transition:`, `transform:`, or `position:fixed`

If your design needs a feature outside these constraints, write it anyway with a `// TODO:` JSX comment — the converter will surface unresolved features as drift-report entries for the merchant to address post-paste.
````

---

## Slot reference (what each `{{SLOT}}` resolves to)

| Slot | Source | Resolution |
| --- | --- | --- |
| `{{Q1_PRODUCT_TYPE}}` | Q1 answer | Verbatim ("Physical", "SaaS", "Course", etc.) |
| `{{Q2_ACCENT_DESCRIPTION}}` | Q2 answer | Free-text or vibe label (e.g. "warm orange #E8643A" or "atlas-greens default") |
| `{{ACCENT_PRESET_SLUG}}` | `palette-presets.md` | Slug name (e.g. `surecart-orange-500`, `surecart-brand`, `surecart-custom-accent`) |
| `{{ACCENT_RESOLVED_HEX}}` | `palette-presets.md` | Hex value of the preset for visual reference |
| `{{Q3A_VARIANTS}}` | Q3a answer | "None" / "1-axis (size or color or storage)" / "2+ axes (e.g. size × color)" |
| `{{VARIANT_BLOCK}}` | Q3a answer | None → omit; 1-axis → `<VariantPills axis="size"/>` (or color/storage); 2+ axes → two `<VariantPills/>` calls |
| `{{Q3B_PRICING}}` | Q3b answer | "One-time" / "Subscription monthly" / "Subscription monthly+yearly toggle" / "Multi-tier (price chooser)" / "Pay-what-you-want" |
| `{{Q4_SECTIONS}}` | Q4 answer (multi) | Comma-separated list of selected sections |
| `{{STATIC_SECTIONS}}` | Q4 answer (multi) | JSX-component list (e.g. `<ReviewSection/>\n<FAQ/>\n<RelatedProducts/>`) per selection |
| `{{Q5_VISUAL_MOOD}}` | Q5 answer | Verbatim ("Light & airy", "Bold & modern", etc.) |
| `{{EXEMPLAR_HANDLE}}` | `typography-presets.md` Q5 mapping | "atlas-greens (R2)", "lumen-saas (R3)", "halcyon-field-jacket (R4)", etc. |
| `{{Q6_TYPOGRAPHY}}` | Q6 answer | "Serif display + sans body", "Sans-only", "Mixed", "Mono accents", "Match theme" |
| `{{DISPLAY_FONT_SLUG}}` | Q6 + Q5 mapping | `sc-display`, `sc-serif-display`, `sc-condensed`, `surecart-display` (default) |
| `{{BODY_FONT_SLUG}}` | Q6 + Q5 mapping | `sc-body`, `surecart-body` (default) |
| `{{HERO_PILL_TEXT}}` | Q1 + Q3b derivation | "JUST RESTOCKED" (Physical), "14-DAY FREE TRIAL" (SaaS subscription), "ENROLL NOW" (Course), etc. |
| `{{HERO_TRAILING_SECTIONS}}` | Q4 + Q1 derivation | Sticky-bar component if Q4 includes "Sticky bar"; otherwise empty |

## Token budget target

After substitution, the populated template targets **1,200–1,700 tokens**. The constraint digest (`intake/constraint-digest.md`) is referenced by §7/§7b but NOT inlined — keeping the per-prompt cost reasonable. The merchant pastes the populated template; design.claude.com loads it as part of its conversation context.

If the populated prompt exceeds 1,700 tokens, trim §5's exemplar skeleton first (it's the largest variable cost). If still over, drop §2b's table to its most-used 3 rows.
