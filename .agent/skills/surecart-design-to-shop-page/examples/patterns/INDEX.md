# Shop-Page Pattern Exemplar Index

Six **production patterns** extracted from the plugin's own block-pattern library + one **gold reference** (paste-tested end-to-end through this skill, full validation cascade cleared). Each exemplar contains paste-ready Gutenberg block markup that loads cleanly in the WordPress block editor — zero "Start Basic" picker UIs, zero "Attempt block recovery" cascades, zero `Block validation failed` errors on first paste.

When matching a Claude Design shop-page export to an exemplar, prefer the closest **layout archetype** match (responsive grid vs. sidebar vs. carousel vs. bento) — pull the markup as a starting skeleton, then substitute the design's tokens (colors, typography, spacing, column count) into the slot positions.

## Gold references (paste-tested through this skill)

| Pattern | File | Layout | Round-trip discoveries |
|---|---|---|---|
| **Hearth & Hollow Shop** | [hearth-hollow-shop.example.md](hearth-hollow-shop.example.md) · [.html](hearth-hollow-shop.example.html) | Sidebar shop with hero `core/cover` banner above + 4-col footer below | v0.2.0 SHC#8/#9 (`is-position-<v>-<h>`, `has-border-color`), v0.3.0 SHC#10 (no descriptive HTML comments inside block content). 158 lines / ~23 KB / ~52 blocks. |

## Production patterns (extracted from the plugin)

| Pattern | File | Layout archetype | Distinguishing markers |
|---|---|---|---|
| **Classic Product List** | [list-standard.example.md](list-standard.example.md) | 9-up responsive grid with header (sort + search + filter-tags) | `minimumColumnWidth:"225px"` (responsive auto-fit), `core/cover` (3/4 aspect, `useFeaturedImage:true`), sale-badge + quick-view inside cover overlay, plain card chrome with subtle `#0000000d` bg |
| **Product List Sidebar** | [list-sidebar.example.md](list-sidebar.example.md) | Sticky filter sidebar + main grid | `position:sticky, top:0`, `flexSize:"225px"` sidebar, `product-template-container` `flex:fill`, filter-checkboxes inside sidebar with `-checkboxes-template` repeater |
| **Classic Product Carousel** | [list-carousel.example.md](list-carousel.example.md) | Simple 3-col grid with title + pagination | Grid `columnCount:3`, header flex space-between, minimal chrome |
| **Simple Product Row** | [list-row.example.md](list-row.example.md) | Compact 4-col grid | Small card chrome, suitable for thumbnail-density browsing or related-product strips |
| **Product Bento** | [list-bento.example.md](list-bento.example.md) | Asymmetric bento grid (1 large + 2 stacked) | Mixed `core/columns` widths inside the grid, `core/cover` aspect-1 hero card |
| **Staggered Product List** | [list-staggered.example.md](list-staggered.example.md) | 2-col staggered with vertical spacers | `core/spacer` for vertical offset between rows |

## Selection rules of thumb

- **Start with `list-standard`** when designing a generic responsive product grid. Replace tokens; don't restructure.
- **Use `list-sidebar`** when the design has a sticky filter column on the left (or right — flip with `flex-direction:row-reverse` via theme CSS).
- **Use `list-carousel`** when the design has a simple fixed-column-count grid without filter/sort chrome.
- **Use `list-row`** when the design wants thumbnail-density (4+ cards per row) with compact card chrome.
- **Use `list-bento`** when the design has visually distinct card sizes (one feature card + smaller siblings).
- **Use `list-staggered`** when the design has intentional vertical offset between alternating columns.

## Per-card structure (consistent across all 6)

All six exemplars share the same per-card inner-block tree inside `surecart/product-template`:

1. Outer `core/group` (`layout.type:"default"`) — card wrapper
2. Card chrome `core/group` (background + border + radius + padding) wrapping the cover
3. `core/cover` with `useFeaturedImage:true`, `dimensions.aspectRatio` per design (`3/4` portrait is most common)
4. Inside cover's `__inner-container`: flex `core/group` with `surecart/product-quick-view-button` (left) + `surecart/product-sale-badge` (right)
5. `surecart/product-title {"level":2}`
6. (Optional) `surecart/product-review-average-rating-stars`
7. Price row: flex `core/group` containing `surecart/product-list-price` + `surecart/product-scratch-price`

The per-card tree is what `reference/start-basic-template.md` documents as the canonical card. The 6 exemplars vary in container chrome (grid layout, sidebar split, gap, card chrome) but converge on the same per-card structure.

## Bytes saved by referencing the sibling skill

These 6 exemplars total ~60 KB. The shop-page skill does NOT duplicate the sibling skill's foundation (alias-map, full-inventory, theme-partial, design-patterns, core-blocks-cheatsheet, style-conversion, custom-html-fallback) — those are loaded LAZY from `../../surecart-design-to-blocks/reference/`. Total shop-page-skill footprint: < 90 KB additive on top of the sibling skill.

## Paste-test fixture cycle

The shop-page skill is at **v0.3.0** with **1 gold-reference exemplar** (Hearth & Hollow Shop, paste-tested 2026-05-19). The 6 production patterns above are paste-verified extracts from the SureCart block-pattern library; the Hearth & Hollow exemplar is the first fresh Claude Design export paste-tested end-to-end through this skill.

Next steps in the v0.x.y paste-test cycle (analogous to v7.0 → v7.11 on the sibling skill):

1. Merchant attaches a Claude Design shop-page export.
2. Skill emits markup per the workflow.
3. Merchant pastes into WP editor; reports any picker UIs / recovery prompts / visual drift.
4. Skill version bumps with new rubric items / Hard Constraint additions corresponding to discovered failure modes.
5. Successful paste-tested output saved as a new gold-reference exemplar (e.g., `examples/patterns/<merchant-design-name>.example.{md,html}`).

After ~3 gold references accumulate, the skill reaches v1.x stability (analogous to the sibling skill's v7.x phase). Currently 1/3.
