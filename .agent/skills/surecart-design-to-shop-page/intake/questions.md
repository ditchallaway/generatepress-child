# Shop-page intake questionnaire — AskUserQuestion driver

This file drives the shop-page intake-mode questionnaire. When Step 0 in `SKILL.md` routes to intake mode, follow the workflow below.

## Workflow

1. **Open the conversation** with a one-sentence framing message (see "Opening message" below).
2. **Run Batch A** — one `AskUserQuestion` call with 4 questions (SQ1, SQ2, SQ3, SQ5). All four always asked.
3. **Run Batch B** — one `AskUserQuestion` call with 1 question (SQ4). Always included.
4. **Resolve answers** — SQ5 → exemplar handle via `../surecart-design-to-blocks/intake/shared/typography-presets.md`; SQ2 → preset slug via `../surecart-design-to-blocks/intake/shared/palette-presets.md`.
5. **Read** `prompt-template.md` and substitute every `{{SLOT}}` with the resolved value.
6. **Emit** the populated template per `prompt-template.md`'s closing instructions: opening message + fenced markdown block.
7. **End the turn.** Do not run Steps 1–5 of the convert pipeline.

**Maximum AskUserQuestion calls per shop-page intake flow: 2** (Batch A + Batch B). Sequential single-question calls are forbidden.

## Opening message (verbatim shape — adapt phrasing if needed)

> Great — let's build a SureCart shop / collection page from scratch. I'll ask 4 quick questions, then one short follow-up, then generate a Claude Design prompt you can paste into [design.claude.com](https://design.claude.com). Once Claude Design produces your export, come back here, drop it in, and I'll convert it to paste-ready Gutenberg markup.
>
> If you already have a Claude Design export, paste it now and I'll skip the questionnaire and go straight to conversion.

Then immediately call AskUserQuestion with Batch A.

## Batch A — always asked (4 questions, 1 AskUserQuestion call)

```json
{
  "questions": [
    {
      "header": "Catalog type",
      "question": "What kind of catalog is this shop page for?",
      "multiSelect": false,
      "options": [
        { "label": "Physical goods", "description": "Shippable products — hardware, apparel, accessories. Shipping-aware chrome." },
        { "label": "Mixed catalog (physical + digital)", "description": "Bundles physical and digital. Sale badges and stock chrome relevant on some cards." },
        { "label": "Digital downloads", "description": "Digital-only catalog — ebooks, presets, templates. No shipping; download icons may apply." },
        { "label": "SaaS / software", "description": "Multiple software products. No shipping; pricing-tier-aware card chrome." },
        { "label": "Online courses", "description": "Course catalog. No shipping; instructor + duration metadata on cards." }
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
      "header": "Grid layout",
      "question": "What's the product grid layout?",
      "multiSelect": false,
      "options": [
        { "label": "3-up grid", "description": "3 cards per row at desktop. Standard layout for fashion / lifestyle." },
        { "label": "4-up grid", "description": "4 cards per row at desktop. Dense; common for SaaS / digital." },
        { "label": "2-up grid", "description": "2 cards per row at desktop. Editorial; large hero-style cards." },
        { "label": "Bento (asymmetric mix)", "description": "Mix of card sizes (1 large + 4 small + etc.). Use sparingly." },
        { "label": "Sidebar + main (sticky filter left + grid right)", "description": "Classic e-commerce. Sticky filter sidebar, scrolling grid." }
      ]
    },
    {
      "header": "Card content",
      "question": "What should each product card show? (multi-select)",
      "multiSelect": true,
      "options": [
        { "label": "Quick-view button (show on hover)", "description": "Reveals product details modal without leaving the listing. Uses is-style-show-on-hover variation." },
        { "label": "Variant chips (color/size swatches)", "description": "Per-card color or size selector. Skip if variants don't vary across products in the catalog." },
        { "label": "Sale badge (on-sale corner ribbon)", "description": "Highlights discounted items. Auto-shown when product has scratch price." },
        { "label": "Rating stars (review summary)", "description": "Inline 5-star rating row. Maps to `product-review-summary` per-card." },
        { "label": "Numbered pagination", "description": "1 2 3 … N at bottom. Standard." },
        { "label": "Load more button", "description": "Infinite-scroll-like UX. Replaces numbered pagination." }
      ]
    }
  ]
}
```

## Batch B — always asked (1 question, 1 AskUserQuestion call)

```json
{
  "questions": [
    {
      "header": "Filter UI",
      "question": "How should filters be presented?",
      "multiSelect": false,
      "options": [
        { "label": "Dropdown menus", "description": "Compact `Filter ▾` chrome. Best for tight horizontal layouts." },
        { "label": "Checkbox groups", "description": "Vertical lists with checkboxes (Color: ☐ Red ☐ Blue). Best for sidebar layouts." },
        { "label": "Tag chips", "description": "Pill-shaped clickable tags. Best for horizontal filter rows." },
        { "label": "Search only", "description": "No filter chrome; single search input. Minimalist." },
        { "label": "None — sort and search only", "description": "Sort + search dropdowns with no filter category UI." }
      ]
    }
  ]
}
```

**Default branching:** If SQ3=Sidebar from Batch A, the AskUserQuestion call for SQ4 should pre-select "Checkbox groups" as the visually focused option (Claude Code surfaces the first option as default — set order accordingly when SQ3=Sidebar is detected).

## Answer → slot resolution

Populate the substitution table for `prompt-template.md`:

| Slot | Resolved from | Resolution rule |
| --- | --- | --- |
| `SQ1_CATALOG_TYPE` | SQ1 answer label | Verbatim |
| `SQ2_ACCENT_DESCRIPTION` | SQ2 answer label | Verbatim |
| `ACCENT_PRESET_SLUG`, `ACCENT_RESOLVED_HEX` | SQ2 + master's `intake/shared/palette-presets.md` lookup | Same resolver as master skill |
| `SQ3_GRID_LAYOUT` | SQ3 answer label | Verbatim |
| `GRID_COLS` | SQ3 derivation | "3-up" → `columnCount:3`; "4-up" → `columnCount:4`; "2-up" → `columnCount:2`; "Bento" → custom (see template); "Sidebar + main" → `minimumColumnWidth:"225px"` on the main column |
| `GRID_WRAPPER` | SQ3 derivation | "Sidebar + main" wraps the grid in a sidebar+main flex layout; others leave the wrapper as default |
| `SQ4_FILTER_UI` | SQ4 answer | Verbatim |
| `FILTER_BLOCKS` | SQ4 derivation | Dropdown → `<FilterDropdown/>`; Checkbox → `<FilterCheckboxes/>`; Tag chips → `<FilterTags/>`; Search only → `<Search/>` (alone); None — sort and search → `<Sort/> <Search/>` |
| `SQ5_CARD_CONTENT` | SQ5 multi answers | Comma-joined labels |
| `CARD_INNER_BLOCKS` | SQ5 derivation | Per-label JSX: Quick-view → `<QuickViewButton is-style-show-on-hover/>`; Variant chips → `<VariantChips/>`; Sale badge → `<SaleBadge/>`; Rating stars → `<ReviewStars/>`; Numbered pagination → `<NumberedPagination/>`; Load more → `<LoadMore/>` |
| `PAGINATION_BLOCK` | SQ5 derivation | "Numbered pagination" → `<NumberedPagination/>` (full triad); "Load more" → `<LoadMore/>`; both selected → numbered wins; neither selected → numbered (default) |
| `Q5_VISUAL_MOOD` | (not asked here — derived from SQ1 + SQ2) | Default `Clean default` (aurora-lamp exemplar); merchant can override via post-emit refinement |
| `EXEMPLAR_HANDLE` | Default `aurora-lamp` (or per SQ1 mapping when refined) | Same exemplar lookup as master |
| `Q6_TYPOGRAPHY` | Not asked | Always `Match theme` (shop-page typography follows merchant theme unless overridden post-emit) |

## After substitution

Follow `prompt-template.md`'s closing instructions: emit the opening message above the fenced template, then the populated template inside fenced ```` ```md ```` markers. **End the turn.**
