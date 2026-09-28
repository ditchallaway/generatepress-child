# Pattern Exemplar Index

Twenty production-grade SureCart block patterns extracted from the plugin's own block-pattern library. Each exemplar contains paste-ready Gutenberg block markup that has been verified to load cleanly in the WordPress block editor — zero "Attempt block recovery" cascades on first paste.

When matching a Claude Design export to an exemplar, prefer the closest **block-type + visual archetype** match. Pull the markup as a starting skeleton, then substitute the design's tokens (colors, typography, spacing, max-width) into the slug/literal slots.

## By family

### Product page (10 — `surecart/product-page` block type)

| Pattern | File | Visual archetype | Distinguishing markers |
|---|---|---|---|
| Classic Product | [product-standard.example.md](product-standard.example.md) | 2-col, media left + info 36% right | `blockGap:{top:"30px",left:"60px"}`, default theme palette. Includes folded "Mirror variant" doctrine for info-left + media-right layouts (formerly `product-alternate.example.md`, deprecated v7.16). |
| Product Physical | [product-physical.example.md](product-physical.example.md) | Brown/earth themed, full-width bg | `contentSize:"1320px"`, brown palette `#8b4513`/`#28201b`/`#5b5048`, SVG benefit icons, pill-rounded quantity. |
| Product Course | [product-course.example.md](product-course.example.md) | Light course page with feature cards | Bg `#f9fafb`, course-info row (Level/Duration/Language), 6 feature cards w/ SVG icons |
| Product Course — Dark | [product-course-dark.example.md](product-course-dark.example.md) | Dark course page | Bg `#1a1b26`/`#212231`, emerald `#34d399` accents, `core/post-featured-image` (16:9 rounded). |
| **Aurora Smart Desk Lamp (v7.6 gold reference)** | [aurora-lamp.example.md](aurora-lamp.example.md) + [aurora-lamp.example.html](aurora-lamp.example.html) | **Compositional 8-section page** (Hero / Features 3×2 / Gallery 2×2 / Tabs→Accordion / Reviews / FAQ / Related / Dark CTA) | First exemplar that strings 8 distinct section archetypes through one page. Off-white body + 2 white sections + dark CTA. Encodes the v7.6 paste-test corrections (A-20/21/22/23). 72 KB / 88 blocks. Use as the gold reference for `has-border-color` always-emit, no-comments-inside-product-page, `core/image` strict-schema, and `core/list` wrapper class. |
| **Morning Glow Vitamin C Serum (v7.7 gold reference)** | [morning-glow-serum.example.md](morning-glow-serum.example.md) + [morning-glow-serum.example.html](morning-glow-serum.example.html) | **Compositional 12-section page** (Header / Hero w/ subscription radio + variant chips / Benefits strip / Before-After / Ingredients 2×3 / How to Use 4-step / Reviews w/ bar chart / FAQ / Subscribe CTA / Related / Footer / Sticky add-bar) | Adds 4 sections beyond Aurora: subscription pricing via price-chooser, variant pills with active-state highlight chrome, breakdown bar chart via core/html L1, dedicated sticky add-bar via surecart/sticky-purchase. Encodes the v7.7 paste-test corrections (A-24/25/26). 84 KB / 112 blocks. Use as the gold reference for: NO aspect-ratio on core/group, zero-value spacing JSON↔inline parity, core/cover gradient fallback to core/group solid-bg. |
| **Loom & Ash Hand-Woven Throw (v7.8 gold reference)** | [loom-ash-throw.example.md](loom-ash-throw.example.md) + [loom-ash-throw.example.html](loom-ash-throw.example.html) | **Compositional 18-section page** (Hero / Trust strip / Story / Materials / Care / Press / How-made / Reviews w/ histogram + 4-col related grid / Bundle / Restock CTA / Footer + more) | First 18-section gold; carries A-27/A-28 (silent template-picker fix for paired `product-review-list` + `product-list-related`). 159 KB / ~510 blocks. Use as the gold reference for: full Start-Basic templates on both review-list and related, hotspot annotated callouts, press strip with differential per-logo typography. |
| **Atlas Greens Daily Essentials (v7.16 gold reference)** | [atlas-greens.example.md](atlas-greens.example.md) + [atlas-greens.example.html](atlas-greens.example.html) | **Compositional 14-section wellness page** (Sticky header / Hero w/ subscription radio + quantity-discount tier / Press strip / Stat counter / Science 3-step / Ingredients 3×2 / Comparison table / Testimonial carousel / Reviews / FAQ / Subscribe CTA / Related / Dark footer / Sticky bar) | First non-warm-earth palette (sage + cream + warm gold). First-try clean PARSE + 3 user-flagged anti-patterns retro-corrected v7.17 (HC#42 cover isDark, HC#43 product-review-breakdown name, HC#44 sticky-purchase canonical inner — see CHANGELOG). Validates 5 patterns the audit predicted would need primitives (press strip / stat counter / comparison table / testimonial carousel / quantity-discount tier) emit cleanly via compositional flex without dedicated primitives. Encodes v7.16 paste-test corrections (HC#39/40/41 — sticky-purchase content overflow / cover thumb useFeaturedImage override / fontFamily attr+class conflict). 113 KB / 294 block opens. Use as the gold reference for: HC#33 + HC#38 working in concert, quantity-discount tier-strip composition, full dark footer with newsletter form, compositional reach without P14-P18 primitives. |
| **Lumen AI Writing Assistant (v7.18 gold reference — post-fix)** | [lumen-saas.example.md](lumen-saas.example.md) + [lumen-saas.example.html](lumen-saas.example.html) | **Compositional 13-section ALL-DARK SaaS landing page** (Sticky header / Hero w/ mock terminal / Customer logos / Feature grid 3×2 / 3-tier pricing / Compare table / Integrations 4×2 / Testimonials 3-up / Stat counters / FAQ / Mid-page CTA / Resources changelog / 4-col footer) | First dark-everywhere palette (deep navy + electric blue + warm gold + cream-white). First B2B SaaS / digital subscription archetype. First sans + monospace ONLY (no serif anywhere) — pure HC#41 Path B. **PASS after HC#45 comment-strip** (not first-try clean — Lumen v1 emitted 17 descriptive `<!-- Section N: -->` comments that triggered Block Recovery; sed-strip cleared all). Encodes HC#45 (NO descriptive comments — grep-checkable). 113 KB / 254 block opens. Use as the gold reference for: all-dark page archetype, B2B SaaS pricing (3-tier with middle highlighted), mock terminal via `core/html` L1, monospace numerics via HC#41 Path B, 4-col dark footer. |
| **Halcyon Field Jacket (v7.18 gold reference — first-try clean)** | [halcyon-field-jacket.example.md](halcyon-field-jacket.example.md) + [halcyon-field-jacket.example.html](halcyon-field-jacket.example.html) | **Compositional 14-section warm-charcoal dark fashion page** (Sticky header / Hero w/ size + color variants / Lookbook 4-up / Materials & construction / Size guide table / Care & longevity 3-up / Press strip / Reviews / FAQ / Related / Brand story band / 4-col dark footer / Sticky purchase) | **First gold to PASS on first paste** — v7.18 pre-emit grep gauntlet caught zero violations. Different dark palette family (warm charcoal `#1A1816` vs Lumen's cool navy `#0A1124`). First fashion/apparel archetype. **First paste-test of `surecart/product-variant-pills`** (color + size, 2 separate groups). EB Garamond + Inter + JetBrains Mono mixed-typography (all HC#41 Path B). 92 KB / 14 sections. 2/3 streak toward production-ready cert. Use as the gold reference for: physical apparel with multi-axis variants (size + color), warm-charcoal dark palette, lookbook galleries, size guide tables, brand story bands, mixed serif+sans+mono typography. |
| **Hearth & Hollow Almanac (v7.22.2 gold reference — first intake-mode loop closure)** | [hearth-hollow-almanac.example.md](hearth-hollow-almanac.example.md) + [hearth-hollow-almanac.example.html](hearth-hollow-almanac.example.html) | **Compositional 7-section earthy digital download** (Sticky header / Hero w/ price-chooser + 1-axis variants / L/R reviews / 3-step how-it-works / FAQ accordion / Related / Oak footer) | **First gold sourced from the intake-mode flow** — design.claude.com generated from the skill's emitted prompt (digital download + warm orange + earthy mood + multi-tier + serif display + sans body). Validates v7.22.x intake architecture end-to-end. **Earthy digital-download archetype** (companion to R4 Halcyon for physical earthy). First gold with `surecart/product-price-chooser` solo (no inline price family) for multi-tier pricing. First L/R reviews layout via HC#53 canonical structure (`product-reviews` wrapper + `core/columns` 2-col). 14 invented Claude-Design slugs fell back to literal-hex via HC#13. Encodes v7.22.1/v7.22.2 paste-test corrections (HC#50/51/52/53). ~38 KB / ~85 blocks. Use as the gold reference for: intake-mode loop closure, digital download archetype, multi-tier price-chooser solo, L/R reviews layout via HC#53, literal-hex earth-tone palettes, Fraunces+Inter typography pairing. |

### Related products grid (1 — `surecart/product-list-related`)

| Pattern | File | Visual archetype | Distinguishing markers |
|---|---|---|---|
| Related Products Carousel | [related-carousel.example.md](related-carousel.example.md) | Compact 3-col card grid | `surecart/product-list-related` (NOT `product-list`) — related-products section on a single-product page. Includes folded "Bordered card variant" doctrine (formerly `related-carousel-alternate.example.md`, deprecated v7.16). |

> **Shop-page exemplars moved to sibling skill (SUR-5186).** The 6 standalone product-list exemplars (`list-standard`, `list-sidebar`, `list-carousel`, `list-row`, `list-bento`, `list-staggered`) now live in `../../surecart-design-to-shop-page/examples/patterns/` since they target top-level `surecart/product-list` shop pages, not single-product detail pages with related-products grids. Refer to `surecart-design-to-shop-page` when the merchant asks for a shop / collection / product-listing page.

### Cart / checkout flow (1 — `surecart/slide-out-cart`)

| Pattern | File | Visual archetype | Distinguishing markers |
|---|---|---|---|
| Cart (Simple) | [cart-new.example.md](cart-new.example.md) | Full slide-out cart panel | Header + scrollable line items + suggested order bumps + summary + checkout buttons. **Only pattern with `metadata.ignoredHookedBlocks:["surecart/cart-line-item-divider"]`.** |

### Quick view / sticky / upsell (3)

| Pattern | File | Visual archetype | Distinguishing markers |
|---|---|---|---|
| Product Quick View | [product-quick-view.example.md](product-quick-view.example.md) | 500px modal product card | `surecart/product-quick-view` wrapper, em-relative typography (1.2em / 0.88em / 0.75em), `surecart/product-quick-view-close` button |
| Sticky Purchase | [sticky-purchase.example.md](sticky-purchase.example.md) | Horizontal sticky purchase bar | `surecart/sticky-purchase`, flex space-between, single Add-to-Cart button. **Upstream JSON bug fixed in this exemplar** (line 10 of source had `{""metadata"...,layout"...`) |
| Upsell Info | [upsell-info.example.md](upsell-info.example.md) | Post-purchase upsell offer | `surecart/upsell` inside outer page `core/group`. **Only pattern using `surecart/columns` and `surecart/column` (custom blocks, NOT aliases for `core/columns`)** |

### Reviews (1 — `surecart/product-review-list`)

| Pattern | File | Visual archetype | Distinguishing markers |
|---|---|---|---|
| Default Review List | [product-review-standard.example.md](product-review-standard.example.md) | Reviews page with summary + filter sidebar + review cards | Heading + summary card (rating value + stars + total + breakdown bar chart) + sticky filter sidebar (rating-stars + tags) + review template (name + verified badge + date + stars + title + content) + pagination |

### Primitives (v7.12+ — reference recipes, not full pages)

| Pattern | File | Purpose |
|---|---|---|
| `core/icon` primitive recipes | [core-icon-primitive.example.md](core-icon-primitive.example.md) | 7 paste-form recipes for WP 7.0's native `core/icon` block — minimal, color+size, slug+ariaLabel, P3 icon-text card (v7.12 replacement for inline-SVG cards), static review stars, CTA with trailing arrow, search input prefix. Includes the most-emitted-slug lookup table. **Reference recipes, not a paste-target page.** The three gold pages (Aurora / Morning Glow / Loom & Ash) remain frozen — their custom-art SVGs don't cleanly map to built-in slugs. Use this exemplar for NEW emissions where the design glyph matches a built-in slug. |

## Selection rules of thumb

- **Start with `product-standard`** when designing a generic 2-column product page. Replace tokens; don't restructure.
- **Use `product-physical`** as the template when the design has a tinted background, themed accents, and benefit icons below the buy button.
- **Use `product-course-dark`** when the design is dark-themed; copy the palette family but substitute the design's exact hex values.
- **Use `related-carousel`** when the design has a "Related products" / "You may also like" grid at the bottom of the product page.
- **Use `cart-new` and `product-review-standard`** as VOCABULARY references for cart-line-item-* and product-review-* family blocks — these patterns aren't typically what `design-to-blocks` outputs (the skill targets product pages), but they teach the alias-safe shape of those families.
- **For shop / collection / multi-product pages**, refer to the sibling skill `surecart-design-to-shop-page` — that's where the 6 `list-*` exemplars now live, and where the top-level `surecart/product-list` workflow is documented.

## Bytes saved by replacing the old exemplars

The previous skill shipped 12 hand-authored `.example.md` files (~50 KB total). The new 20 production-derived exemplars total ~615 KB but cover 100% of the family vocabulary in current use. The size delta is mostly the large course / physical patterns which contain inline SVG icon markup — kept verbatim because the SVG is L1-safe and merchants paste them as-is.
