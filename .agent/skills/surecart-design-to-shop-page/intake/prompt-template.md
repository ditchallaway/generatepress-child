# Claude Design prompt template — shop-page surface

This file is the source-of-truth template that the shop-page intake phase fills with the merchant's questionnaire answers and emits as a fenced markdown block. The merchant copies it and pastes into design.claude.com.

## Workflow (after questionnaire completes)

1. Collect questionnaire answers from the prior turn(s) — see `questions.md` for the slot names.
2. Resolve SQ2 (accent) → preset slug via `../surecart-design-to-blocks/intake/shared/palette-presets.md`.
3. Resolve SQ3 (grid layout) → wrapper structure + `GRID_COLS` value.
4. Resolve SQ5 (card content) → `CARD_INNER_BLOCKS` JSX expansion list.
5. Substitute every `{{SLOT}}` below with the resolved value.
6. Emit the populated template as ONE fenced markdown block to the merchant, prefixed with the closing message.

## Closing message (always emit first, above the fenced prompt)

> I've prepared a Claude Design prompt for your shop page based on your answers. Copy everything in the fenced block below and paste it into [design.claude.com](https://design.claude.com). Once Claude Design produces your export, come back here and paste/attach it — I'll convert it to paste-ready Gutenberg markup.
>
> The prompt asks Claude Design to render ONE canonical product card (not the full grid populated with sample products). If Claude Design tries to render multiple varied cards, that's fine — the converter takes card #1 as the template and discards the rest as visual padding.

Then emit the fenced template (below).

---

## Template body (substitute `{{SLOT}}` then emit between fenced ```` ```md ```` markers)

````md
# SureCart Shop Page — Design Brief

## §0. Role + non-negotiable framing

You are designing a product-listing (catalog/shop/collection) page for production WordPress e-commerce. Output is mechanically compiled into WordPress Gutenberg blocks by a server-side renderer with a fixed grammar — like generating MIDI for a sampler rather than recording audio.

The grid you draw is a **server-iterated template**. The merchant's actual product catalog populates it at render time. Your design's job is to show ONE canonical product card structure; the server expands it across the merchant's product count automatically.

The constraints in §7 are not stylistic preferences; they are the grammar of the target format. Producing beautiful work means producing work that survives compilation intact.

## §1. Output contract

Produce a Claude Design project with the canonical 4-file structure:
- `product-page.jsx` — page composition (treat as shop-page composition here)
- `sections-1.jsx` — header + filter chrome
- `sections-2.jsx` — grid + pagination + page footer
- `assets/colors_and_type.css` — `:root { --token: value }` design tokens

Use **only** components from §2 visual vocabulary. Annotate render surfaces with JSX comments from §3 — **three surfaces** for shop pages (not two like product-detail). Style with CSS variables from §4.

## §2. Visual vocabulary you may use

Each line is a component you may use and its canonical SureCart block slug. Do not invent component names — see §7b alias map.

```jsx
{/* page-level chrome */}
<StickyHeader>...</StickyHeader>          // core/group + is-position-sticky class
<CartIcon/>                               // surecart/cart-menu-icon-button
<CollectionHero>...</CollectionHero>       // core/cover or core/group (page banner)
<DarkFooter>...</DarkFooter>               // core/group footer

{/* filter chrome (lives inside SURFACE: FILTER-CHROME) */}
<Sort/>                                   // surecart/product-list-sort or sort-radio-group
<Search/>                                 // surecart/product-list-search
<FilterDropdown/>                         // surecart/product-list-filter
<FilterCheckboxes/>                       // surecart/product-list-filter-checkboxes (paired)
<FilterTags/>                             // surecart/product-list-filter-tags (paired)
<FilterSidebar>...</FilterSidebar>        // surecart/product-list-sidebar (sticky)

{/* product card content (lives inside SURFACE: PRODUCT-GRID — render ONE canonical card) */}
<Card>...</Card>                          // surecart/product-template (inner template)
<ProductCover useFeaturedImage/>          // core/cover with useFeaturedImage:true
<SaleBadge/>                              // surecart/product-sale-badge
<QuickViewButton is-style-show-on-hover/> // surecart/product-quick-view-button
<ProductTitle/>                           // surecart/product-title
<ReviewStars/>                            // surecart/product-review-summary (inline)
<ProductPrice/>                           // surecart/product-selected-price-amount
<PriceScratch/>                           // surecart/product-selected-price-scratch-amount
<VariantChips/>                           // surecart/product-variant-pills (per-card)

{/* pagination + fallback */}
<NumberedPagination/>                     // surecart/product-pagination (full triad)
<LoadMore/>                               // surecart/product-pagination-next (custom label)
<NoProductsFallback/>                     // surecart/product-list-no-products (paired)

{/* icons */}
<Icon slug="..."/>                        // core/icon (88 built-in slugs)
```

Use `core/group`, `core/columns`, `core/cover`, `core/heading`, `core/paragraph`, `core/buttons`, `core/image`, `core/separator`, `core/html` for static page-chrome content — composed under `{/* SURFACE: PAGE-CHROME */}`.

## §2b. Block-style variations (shop-page subset)

| Component | Variation | Visual effect |
| --- | --- | --- |
| `<QuickViewButton/>` | `is-style-show-on-hover` | hidden until card hover |
| `<FilterSidebar/>` | `is-position-sticky` | sticky filter column |
| `<core/Image/>` | `is-style-rounded` | corner-rounded card covers |
| `<core/Separator/>` | `is-style-dots` / `is-style-wide` | dotted / full-width section dividers |
| `<core/Button/>` | `is-style-fill` / `is-style-outline` | sort/filter buttons |

## §3. Render surfaces (three surfaces for shop-page)

Annotate your JSX with JSX comments to mark the three surfaces. The converter strips these markers before Gutenberg emission.

```jsx
{/* SURFACE: PAGE-CHROME */}
  // page-level chrome that lives above and below the product list:
  // sticky header, collection hero banner, page footer.
{/* /SURFACE: PAGE-CHROME */}

{/* SURFACE: FILTER-CHROME */}
  // the filter/sort/search row that sits above the grid:
  // sort dropdown, search input, filter blocks (per SQ4 answer).
  // sidebar variant: wrap in <FilterSidebar/> instead of inline row.
{/* /SURFACE: FILTER-CHROME */}

{/* SURFACE: PRODUCT-GRID */}
  // ONE canonical product card structure.
  // You MAY render 2–6 visually identical cards for completeness (vary photo + title only).
  // The converter discards cards 2–N and uses card #1 as the iteration template.
{/* /SURFACE: PRODUCT-GRID */}
```

**Placement rule:** surface markers appear ONLY at the boundary between top-level sections — never nested inside a component subtree.

**One-canonical-card rule:** the merchant's actual products fill the grid at render time. Your design shows the **template** for one card. If you draw 6 cards with different layouts (one with badge, two without; some with quick-view, some without), the converter cannot determine which variant is canonical. **Vary only product photo and title** across padding cards; keep structure, classes, and inner blocks identical.

## §4. Style tokens (CSS variables, not raw hex)

```css
:root {
  --sc-accent: var(--wp--preset--color--{{ACCENT_PRESET_SLUG}});
  --sc-text:   var(--wp--preset--color--surecart-text);
  --sc-bg:     var(--wp--preset--color--surecart-bg);
  --sc-display: var(--wp--preset--font-family--surecart-display);
  --sc-body:    var(--wp--preset--font-family--surecart-body);
}
```

Raw `#RRGGBB` is reserved for non-color literals only. The accent resolves to **`{{ACCENT_PRESET_SLUG}}`** (`{{ACCENT_RESOLVED_HEX}}` — visual reference only; emitted CSS uses the preset variable).

**Palette discipline.** Use ONLY the named accent slug above (`{{ACCENT_PRESET_SLUG}}`) and this small SureCart neutral palette: `surecart-text`, `surecart-text-muted`, `surecart-text-inverse`, `surecart-bg`, `surecart-surface`, `surecart-border`. Do **NOT** extrapolate companion slugs — no `-100` / `-200` / `-700` / `-900` shades of the accent, and no thematically-adjacent named slugs like `surecart-oak-900`, `surecart-olive-700`, `surecart-ochre-500`, `surecart-cream-50`. Those slugs do NOT exist in the merchant's `theme.json` and produce silently-failing references at server-render time. When the design needs additional shades or earth tones for visual depth, write raw `#RRGGBB` literals — the converter handles them via D7 dual-emit and they render correctly on every theme. The accent + 6 neutrals above are the only slug names guaranteed to resolve.

(`core/icon` catalog: see master skill `../surecart-design-to-blocks/intake/prompt-template.md` §4 — same 88-slug semantic table applies.)

## §5. Reference design — visual anchor

Use this structural shape as your starting point (paste-tested **{{EXEMPLAR_HANDLE}}**):

```jsx
{/* SURFACE: PAGE-CHROME */}
<StickyHeader> :: brand-mark + nav + <CartIcon/> </StickyHeader>
<CollectionHero> :: title + subtitle + decorative imagery </CollectionHero>
{/* /SURFACE: PAGE-CHROME */}

{/* SURFACE: FILTER-CHROME */}
{{FILTER_BLOCKS}}
{/* /SURFACE: FILTER-CHROME */}

{/* SURFACE: PRODUCT-GRID */}
<Grid cols="{{GRID_COLS}}">
  <Card>     {/* canonical — render 2-6 visually identical padding cards if needed */}
    <ProductCover useFeaturedImage/>
    {{CARD_INNER_BLOCKS}}
  </Card>
</Grid>
{{PAGINATION_BLOCK}}
<NoProductsFallback/>     {/* shown when filter returns zero results */}
{/* /SURFACE: PRODUCT-GRID */}

{/* SURFACE: PAGE-CHROME */}
<DarkFooter>...</DarkFooter>
{/* /SURFACE: PAGE-CHROME */}
```

## §6. Merchant brief

- **Catalog type:** {{SQ1_CATALOG_TYPE}}.
- **Grid layout:** {{SQ3_GRID_LAYOUT}}.
- **Filter UI:** {{SQ4_FILTER_UI}}.
- **Card content:** {{SQ5_CARD_CONTENT}}.
- **Accent color:** {{SQ2_ACCENT_DESCRIPTION}} (preset: `{{ACCENT_PRESET_SLUG}}`).
- **Visual reference:** `{{EXEMPLAR_HANDLE}}`.

Photography, copy tone, product specifics: invent placeholders consistent with the catalog type. Product titles, prices, ratings on cards are **server-rendered** — use the placeholder components from §2. Vary photo + title across padding cards; keep all other structure identical.

## §7. Hard prohibitions

1. **[SC-DATA]** Product titles, prices, and ratings on cards are **not literal text**. Use the placeholder components: `<ProductTitle/>`, `<ProductPrice/>`, `<ReviewStars/>`.
2. **[SC-BLOCK]** Use only the components in §2 and the §7b alias map. Forbidden hallucinated names: `product-card`, `product-grid`, `filter-bar`, `collection-banner`, `category-tabs`, `search-box`, `breadcrumb-list`, `infinite-scroll`. See §7b for replacements.
3. **[SC-BLOCK]** First card in PRODUCT-GRID is canonical. Render ONE structure; if drawing padding cards for visual completeness, vary ONLY photo and title — keep wrappers, classes, badges, buttons identical across cards.
4. **[WP-CORE]** Static markup only — no `animation`, `transition`, `transform`, `@keyframes`, parallax, scroll-reveal. Motion added by theme CSS post-paste.
5. **[WP-CORE]** Layout flows top-to-bottom. Sticky filter sidebar uses the semantic `<FilterSidebar/>` wrapper (maps to `is-position-sticky`). Do NOT inline-write `position:sticky;top:Npx;z-index:N` in CSS.
6. **[WP-CORE]** All visible content lives in real DOM nodes — no `::before` / `::after` content; use `<Icon slug="..."/>` from §4 catalog for glyphs.
7. **[SC-BLOCK]** The only HTML comments in your output are the six `{/* SURFACE: ... */}` markers from §3 (open + close for each of 3 surfaces). Other JSX comments (`{/* TODO */}`) are fine; HTML comments inside what becomes an `apiVersion:3` region silently drop inner block trees.
8. **[WP-CORE]** Use named `<Icon slug="..."/>` from §4 catalog. Custom inline SVG > 60 chars is rejected.
9. **[SC-DATA]** Sale badges, variant chips, and quick-view buttons are per-card SureCart blocks. Do not hand-author them as `<div className="badge">SALE</div>`.
10. **[SC-BLOCK]** No-products fallback (`<NoProductsFallback/>`) MUST appear in PRODUCT-GRID as the empty-state. Do not omit it — the converter requires the paired block for shop pages with filters.

## §7b. Extended alias map (designer-time → canonical)

| Don't say | Say | Notes |
| --- | --- | --- |
| `product-card` | (compose: `<Card>` with cover + title + price) | no atomic card block |
| `product-grid` | `<Grid cols="N">` | maps to `surecart/product-template` |
| `filter-bar` | `<Sort/> + <Search/> + filter blocks` | individual blocks per chrome element |
| `collection-banner` | `<CollectionHero>` | `core/cover` or `core/group` |
| `category-tabs` | (drop or `core/buttons`) | no native block; tab UI is post-paste |
| `search-box` | `<Search/>` | `surecart/product-list-search` |
| `breadcrumb-list` | `core/breadcrumbs` or drop | no SureCart block |
| `infinite-scroll` | `<LoadMore/>` | maps to `surecart/product-pagination-next` |
| `variant-picker` | `<VariantChips/>` | `surecart/product-variant-pills` per-card |
| `cart-icon` | `<CartIcon/>` | `surecart/cart-menu-icon-button` |
| `checkout-button` | (forbidden on shop page) | cart/upsell only |
| `quick-view-modal` | `<QuickViewButton is-style-show-on-hover/>` | maps to `surecart/product-quick-view-button` |
| `sale-ribbon` / `sale-tag` | `<SaleBadge/>` | `surecart/product-sale-badge` |

## §8. Self-check (advisory only)

Your output is also validated by an automatic converter linter on receipt. The linter will reject and ask you to regenerate if any of these is missing:

- All three surface markers appear at top level (PAGE-CHROME, FILTER-CHROME, PRODUCT-GRID)
- Exactly one canonical card structure inside PRODUCT-GRID (additional padding cards must be structurally identical to card #1)
- Pagination block AND no-products fallback are both present inside PRODUCT-GRID
- No `<svg>` with path data > 60 chars (use Icon slug instead)
- No component name appears outside §2 vocabulary or §7b alias map
- No CSS rule contains `@keyframes`, `animation:`, `transition:`, `transform:`, or `position:fixed`

If your design needs a feature outside these constraints, write it anyway with a `// TODO:` JSX comment — the converter surfaces unresolved features as drift-report entries.
````

---

## Slot reference (what each `{{SLOT}}` resolves to)

| Slot | Source | Resolution |
| --- | --- | --- |
| `{{SQ1_CATALOG_TYPE}}` | SQ1 answer | Verbatim ("Physical goods", "SaaS / software", etc.) |
| `{{SQ2_ACCENT_DESCRIPTION}}` | SQ2 answer | Verbatim |
| `{{ACCENT_PRESET_SLUG}}` | `../surecart-design-to-blocks/intake/shared/palette-presets.md` lookup | Slug (e.g. `surecart-brand`, `surecart-blue-500`) |
| `{{ACCENT_RESOLVED_HEX}}` | Same lookup | Hex for visual reference |
| `{{SQ3_GRID_LAYOUT}}` | SQ3 answer | Verbatim |
| `{{GRID_COLS}}` | SQ3 derivation | "3" / "4" / "2" / "bento" / "auto-225" |
| `{{SQ4_FILTER_UI}}` | SQ4 answer | Verbatim |
| `{{FILTER_BLOCKS}}` | SQ4 derivation | JSX list of `<Sort/>`, `<Search/>`, filter block per SQ4 |
| `{{SQ5_CARD_CONTENT}}` | SQ5 multi answers | Comma-joined labels |
| `{{CARD_INNER_BLOCKS}}` | SQ5 derivation | JSX list of card inner blocks per SQ5 selections |
| `{{PAGINATION_BLOCK}}` | SQ5 derivation | `<NumberedPagination/>` (default) or `<LoadMore/>` |
| `{{EXEMPLAR_HANDLE}}` | Default `aurora-lamp` | Future PR: per-SQ1 mood inference |

## Token budget target

After substitution, the populated template targets **1,100–1,500 tokens** (slightly leaner than product-detail because of fewer slots and a 16→13 alias map). If above 1,500, trim §5's exemplar skeleton.
