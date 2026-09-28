# Intake questionnaire — AskUserQuestion driver

This file drives the intake-mode questionnaire. When Step 0 in `SKILL.md` routes to intake mode, follow the workflow below.

## Workflow

1. **Open the conversation** with a one-sentence framing message (see "Opening message" below).
2. **Run Batch A** — one `AskUserQuestion` call with 4 questions (Q1, Q2, Q3a, Q5). All four are always asked.
3. **Branch and run Batch B** — one `AskUserQuestion` call with up to 3 conditional questions (Q3b, Q4, Q6). Each question's inclusion follows the branching rules below.
4. **Resolve answers** — Q5 → exemplar handle via `shared/typography-presets.md`; Q2 → preset slug via `shared/palette-presets.md`; Q3a/Q3b → variant + pricing block strings; Q4 → `{{STATIC_SECTIONS}}` JSX list.
5. **Read** `prompt-template.md` and substitute every `{{SLOT}}` with the resolved value.
6. **Emit** the populated template per `prompt-template.md`'s closing instructions: opening message + fenced markdown block.
7. **End the turn.** Do not run Steps 1–5 of the convert pipeline. The merchant will return with a design on their next turn — Step 0 will route them to convert mode then.

**Maximum AskUserQuestion calls per intake flow: 3** (Batch A always; Batch B optional one call; one final clarification only if a free-text answer is ambiguous). Sequential single-question calls are forbidden — every question lives in either Batch A or Batch B.

## Opening message (verbatim shape — adapt phrasing if needed)

> Great — let's build a SureCart product page from scratch. I'll ask 4 quick questions first, then a couple of conditional follow-ups, then generate a Claude Design prompt you can paste into [design.claude.com](https://design.claude.com). Once Claude Design produces your export, come back here, drop it in, and I'll convert it to paste-ready Gutenberg markup.
>
> If you already have a Claude Design export, paste it now and I'll skip the questionnaire and go straight to conversion.

Then immediately call AskUserQuestion with Batch A.

## Batch A — always asked (4 questions, 1 AskUserQuestion call)

```json
{
  "questions": [
    {
      "header": "Product type",
      "question": "What kind of product is this page for?",
      "multiSelect": false,
      "options": [
        { "label": "Physical product", "description": "Shippable goods — hardware, apparel, accessories, consumables. Triggers shipping-aware chrome." },
        { "label": "Consumable / refillable", "description": "Repeat-purchase physical (skincare, supplements, household). Variants likely; subscription common." },
        { "label": "Digital download", "description": "One-time digital purchase — ebooks, presets, templates, code packs." },
        { "label": "SaaS / software", "description": "Recurring software access. Tiered pricing, free trial likely. No shipping." },
        { "label": "Online course", "description": "Self-paced learning. Curriculum sections, instructor bio. No shipping." },
        { "label": "Service / booking", "description": "Time-based service — consulting, coaching, treatments. No variants; manual booking." }
      ]
    },
    {
      "header": "Accent color",
      "question": "What's the primary accent color for the page?",
      "multiSelect": false,
      "options": [
        { "label": "SureCart default (sage green)", "description": "`surecart-brand` preset (#01824C). Safe, neutral, ships with every install." },
        { "label": "Warm — orange / coral / amber", "description": "Earthy, friendly, food/beverage/lifestyle." },
        { "label": "Bold — red / magenta / electric", "description": "High-energy, sale-driven, contemporary." },
        { "label": "Cool — blue / teal / cyan", "description": "Tech, SaaS, finance, healthcare." },
        { "label": "Dark — charcoal / navy / black-on-cream", "description": "Premium, editorial, restrained." }
      ]
    },
    {
      "header": "Variants",
      "question": "Does this product have variants?",
      "multiSelect": false,
      "options": [
        { "label": "None", "description": "Single SKU. No variant pills shown." },
        { "label": "1-axis (size OR color OR storage)", "description": "One row of variant pills." },
        { "label": "2+ axes (e.g. size × color)", "description": "Two rows of variant pills." }
      ]
    },
    {
      "header": "Visual mood",
      "question": "What visual mood should the page have?",
      "multiSelect": false,
      "options": [
        { "label": "Light & airy", "description": "Cream + sage palette, generous whitespace, soft serif. Reference: atlas-greens." },
        { "label": "Bold & modern", "description": "High-contrast, sans-display, color-blocked CTAs. Reference: lumen-saas." },
        { "label": "Dark & premium", "description": "Charcoal + accent, dense type, restrained spacing. Reference: loom-ash-throw." },
        { "label": "Earthy & organic", "description": "Olive + ochre, photography-forward, condensed sans. Reference: halcyon-field-jacket." },
        { "label": "Tech-SaaS / dashboard", "description": "Grid + iconography, condensed metrics. Reference: northwind-kettle." },
        { "label": "Clean default", "description": "Neutral palette, no brand commitment. Reference: aurora-lamp." }
      ]
    }
  ]
}
```

## Batch B — conditional (at most 3 questions, 1 AskUserQuestion call)

After Batch A answers arrive, decide which Batch B questions to include based on branching rules:

### Branching rules

- **Q3b "Pricing model"**: included EXCEPT when Q1 = "Service / booking" (services bill ad-hoc — skip).
- **Q3b option filter**: when Q1 ∈ { "SaaS / software", "Digital download", "Online course" }, REMOVE the "Pay-what-you-want" option (not applicable).
- **Q4 "Page sections"**: always included.
- **Q4 option filter**: when Q1 ∈ { "SaaS / software", "Digital download", "Online course" }, REMOVE "Sticky bar" (the sticky-purchase-bar pattern is shipping-product-oriented).
- **Q6 "Typography"**: included EXCEPT when Q5 = "Clean default" (Clean default carries no typographic stance — skip).

If Batch B has 0 questions to ask (e.g. Q1=Service AND Q5=Clean default), skip the call entirely and proceed to substitution. If Batch B has 1+ questions, batch them into ONE AskUserQuestion call.

### Batch B question definitions

(Construct the AskUserQuestion call dynamically using only the included questions.)

**Q3b — Pricing model** (single-select):

```json
{
  "header": "Pricing model",
  "question": "How is the product priced?",
  "multiSelect": false,
  "options": [
    { "label": "One-time", "description": "Single payment at checkout. Default for physical / digital products." },
    { "label": "Subscription monthly", "description": "Recurring monthly billing. Use price-interval suffix `/mo`." },
    { "label": "Subscription monthly + yearly toggle", "description": "Two intervals, toggle in the price area." },
    { "label": "Multi-tier (price chooser)", "description": "2-4 plan tiers. Uses `<PriceChooser/>` atomic block, not inline price." },
    { "label": "Pay-what-you-want", "description": "Open-ended amount input. Common for donations / ad-hoc digital goods." }
  ]
}
```

**Q4 — Page sections** (multi-select; default if skipped: Reviews + FAQ + Related):

```json
{
  "header": "Page sections",
  "question": "Which sections should the page include (beyond the hero)?",
  "multiSelect": true,
  "options": [
    { "label": "Reviews (full section + ratings histogram)", "description": "Full-width `<ReviewSection/>` with summary card + filter + cards list." },
    { "label": "FAQ (accordion)", "description": "5-8 expandable items via `core/details`." },
    { "label": "Related products (4-up grid)", "description": "Full-width `<RelatedProducts/>` — paired-template grid below the hero." },
    { "label": "Press / quotes band", "description": "Logo strip or pull-quote testimonial row." },
    { "label": "Comparison table", "description": "Side-by-side feature comparison vs. competitors or other tiers." },
    { "label": "How it works (steps)", "description": "Numbered steps explaining product use or purchase flow." },
    { "label": "Sticky bar (mobile add-to-cart)", "description": "Bottom-fixed sticky-purchase-bar — visible on scroll past hero. SHIPPING products only." }
  ]
}
```

**Q6 — Typography** (single-select; default if skipped: Match theme):

```json
{
  "header": "Typography",
  "question": "What typography pairing fits the brand?",
  "multiSelect": false,
  "options": [
    { "label": "Serif display + sans body", "description": "Editorial, premium. Soft serif headlines, sans body copy." },
    { "label": "Sans-only", "description": "Modern, minimal. Same family for headlines and body." },
    { "label": "Mixed (display sans + body sans + serif accents)", "description": "Versatile, contemporary." },
    { "label": "Mono accents (price, badges, code)", "description": "Tech-flavored. Mono for numbers/code; sans for everything else." },
    { "label": "Match theme", "description": "Use whatever fonts the merchant's WordPress theme provides. Safest default." }
  ]
}
```

## Answer → slot resolution

Once both batches complete, populate the substitution table for `prompt-template.md`:

| Slot | Resolved from | Resolution rule |
| --- | --- | --- |
| `Q1_PRODUCT_TYPE` | Q1 answer label | Verbatim |
| `Q2_ACCENT_DESCRIPTION` | Q2 answer label | Verbatim (e.g. "Warm — orange / coral / amber") |
| `ACCENT_PRESET_SLUG`, `ACCENT_RESOLVED_HEX` | Q2 + `shared/palette-presets.md` lookup | Default `surecart-brand` / `#01824C` for "SureCart default"; per-color mapping for the others |
| `Q3A_VARIANTS` | Q3a answer label | Verbatim |
| `VARIANT_BLOCK` | Q3a answer | `None` → empty string; `1-axis` → `<VariantPills axis="size"/>` (or color/storage; default size); `2+ axes` → `<VariantPills axis="color"/>\n  <VariantPills axis="size"/>` |
| `Q3B_PRICING` | Q3b answer (or "One-time" default if skipped) | Verbatim |
| `Q4_SECTIONS` | Q4 answer (multi; default `["Reviews (full section)", "FAQ (accordion)", "Related products"]` if skipped) | Comma-joined labels |
| `STATIC_SECTIONS` | Q4 answer | Per-label JSX expansion: Reviews → `<ReviewSection/>`; FAQ → `<FAQ/>`; Related → `<RelatedProducts/>`; Press → `<PressBand/>`; Comparison → `<ComparisonTable/>`; How it works → `<HowItWorks/>`; Sticky bar → handled separately (see HERO_TRAILING_SECTIONS) |
| `Q5_VISUAL_MOOD` | Q5 answer label | Verbatim |
| `EXEMPLAR_HANDLE` | Q5 + `shared/typography-presets.md` | "atlas-greens (R2)" / "lumen-saas (R3)" / "loom-ash-throw" / "halcyon-field-jacket (R4)" / "northwind-kettle" / "aurora-lamp" |
| `Q6_TYPOGRAPHY` | Q6 answer (or "Match theme" if skipped) | Verbatim |
| `DISPLAY_FONT_SLUG`, `BODY_FONT_SLUG` | Q6 + Q5 mapping | Per `shared/typography-presets.md` table; default `surecart-display` / `surecart-body` |
| `HERO_PILL_TEXT` | Q1 + Q3b derivation | Physical/Consumable+One-time → "JUST RESTOCKED"; SaaS/Subscription → "14-DAY FREE TRIAL"; Course → "ENROLL NOW"; Digital → "INSTANT DOWNLOAD"; Service → "BOOK A SESSION"; fallback "AVAILABLE NOW" |
| `HERO_TRAILING_SECTIONS` | Q4 derivation | If Q4 includes "Sticky bar" → emit `<StickyBar><Quantity/><BuyButton primary addToCart/></StickyBar>` inside SURFACE: PRODUCT-TEMPLATE; otherwise empty |

## After substitution

Follow `prompt-template.md`'s closing instructions: emit the opening message above the fenced template, then the populated template inside fenced ```` ```md ```` markers. **End the turn.**

Do not preemptively explain what Claude Design will do, or what the converter will do next — keep the message short. The merchant pastes, Claude Design generates, the merchant returns with the design.
