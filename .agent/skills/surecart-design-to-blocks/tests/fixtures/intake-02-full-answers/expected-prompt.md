# Expected emitted prompt (golden)

Given `answers.json` in this directory, the intake-mode emission should produce the message below.

---

## Closing message (plain prose, above the fenced block)

> I've prepared a Claude Design prompt for you based on your answers. Copy everything in the fenced block below and paste it into [design.claude.com](https://design.claude.com). Once Claude Design produces your export, come back here and paste/attach it — I'll convert it to paste-ready Gutenberg markup.
>
> If you want to refine any answer (mood, accent, sections), just tell me which one and re-invoke me.

## Populated prompt (inside fenced ```md block)

````md
# SureCart Product Page — Design Brief

## §0. Role + non-negotiable framing

(verbatim from prompt-template.md §0)

## §1. Output contract

(verbatim from prompt-template.md §1)

## §2. Visual vocabulary you may use

(verbatim from prompt-template.md §2)

## §2b. Block-style variations

(verbatim from prompt-template.md §2b)

## §3. Render surfaces

(verbatim from prompt-template.md §3)

## §4. Style tokens (CSS variables, not raw hex)

```css
:root {
  --sc-accent: var(--wp--preset--color--surecart-blue-500);
  --sc-text:   var(--wp--preset--color--surecart-text);
  --sc-bg:     var(--wp--preset--color--surecart-bg);
  --sc-display: var(--wp--preset--font-family--sc-display);
  --sc-body:    var(--wp--preset--font-family--sc-body);
  --sc-mono:    var(--wp--preset--font-family--sc-mono);
}
```

Raw `#RRGGBB` is reserved for non-color literals only. The accent resolves to **`surecart-blue-500`** (`#0EA5E9` — for your visual reference only; the emitted CSS uses the preset variable).

(`core/icon` catalog — verbatim from prompt-template.md §4)

## §5. Reference design — visual anchor

Use this structural shape as your starting point (paste-tested **northwind-kettle**):

```jsx
{/* SURFACE: PRODUCT-TEMPLATE */}
<StickyHeader> :: brand-mark + nav + <CartIcon/> </StickyHeader>
<Hero cols="2">
  <ProductGallery is-style-orbit/>
  <Pill text="14-DAY FREE TRIAL"/>
  <ProductTitle/>
  <ReviewStars/>            {/* inline summary */}
  <PriceChooser/>           {/* monthly + yearly toggle */}
  <VariantPills axis="size"/>
  <Quantity is-style-pebble/>
  <BuyButtons>
    <BuyButton primary addToCart/>
    <BuyButton outline/>
  </BuyButtons>
</Hero>
{/* /SURFACE: PRODUCT-TEMPLATE */}

{/* SURFACE: STATIC-CONTENT */}
<ReviewSection/>
<ComparisonTable/>
<HowItWorks/>
{/* /SURFACE: STATIC-CONTENT */}
```

## §6. Merchant brief

- **Brand mood:** Tech-SaaS / dashboard (visual reference: `northwind-kettle`).
- **Product type:** SaaS / software.
- **Pricing model:** Subscription monthly + yearly toggle.
- **Variants:** 1-axis (size or color or storage).
- **Sections to include:** Reviews (full section + ratings histogram), Comparison table, How it works (steps).
- **Typography:** Mono accents (price, badges, code).
- **Accent color:** Cool — blue / teal / cyan (preset: `surecart-blue-500`).

Photography, copy tone, product specifics: invent placeholders consistent with the product type — the merchant will swap them post-paste. Title, price, buy-button text are **server-rendered** (see §7 rule 1) — use the placeholder components from §2.

Apply `sc-mono` font-family to `<ProductPrice/>`, scratch-price text, and any tabular price-comparison cells in `<ComparisonTable/>` per the Mono accents typography choice.

## §7. Hard prohibitions

(verbatim from prompt-template.md §7)

## §7b. Extended alias map

(verbatim from prompt-template.md §7b)

## §8. Self-check (advisory only)

(verbatim from prompt-template.md §8)
````

---

## Slot substitution table (for this fixture)

| Slot | Resolved value |
| --- | --- |
| `Q1_PRODUCT_TYPE` | SaaS / software |
| `Q2_ACCENT_DESCRIPTION` | Cool — blue / teal / cyan |
| `ACCENT_PRESET_SLUG` | `surecart-blue-500` |
| `ACCENT_RESOLVED_HEX` | `#0EA5E9` |
| `Q3A_VARIANTS` | 1-axis (size or color or storage) |
| `VARIANT_BLOCK` | `<VariantPills axis="size"/>` (single axis, default storage axis used) |
| `Q3B_PRICING` | Subscription monthly + yearly toggle |
| `Q4_SECTIONS` | Reviews (full section + ratings histogram), Comparison table, How it works (steps) |
| `STATIC_SECTIONS` | `<ReviewSection/>\n<ComparisonTable/>\n<HowItWorks/>` |
| `Q5_VISUAL_MOOD` | Tech-SaaS / dashboard |
| `EXEMPLAR_HANDLE` | northwind-kettle |
| `Q6_TYPOGRAPHY` | Mono accents (price, badges, code) |
| `DISPLAY_FONT_SLUG` | `sc-display` |
| `BODY_FONT_SLUG` | `sc-body` |
| `HERO_PILL_TEXT` | 14-DAY FREE TRIAL (derived from Q1=SaaS + Q3b=Subscription) |
| `HERO_TRAILING_SECTIONS` | (empty — Q4 does not include "Sticky bar"; SaaS branch filtered it out) |

## Branching coverage

This fixture exercises:
- **Q3b option filter** — "Pay-what-you-want" was removed from Q3b options before AskUserQuestion was called, because Q1=SaaS triggered the filter
- **Q4 option filter** — "Sticky bar" was removed from Q4 options, same reason
- **Q6 override** — `Q6=Mono accents` overrode the Q5 default pairing; emits `sc-mono` slug + adds a Mono-accent note to §6
- **PriceChooser substitution** — `Q3b=Subscription monthly + yearly toggle` substitutes `<PriceChooser/>` in §5 instead of the default `<ProductPrice/> <PriceScratch/> <PriceInterval/>` block, because the merchant chose a toggle UI

## Verification

Same harness as `intake-01`. If `expected-prompt.md` and the renderer output diverge, either the renderer drifted (regression) or `prompt-template.md` was intentionally updated (then update this golden).
