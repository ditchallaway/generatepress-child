# `lumen-saas` — B2B SaaS Dark Theme Product Page (v7.18 gold reference)

**Source:** Lumen AI writing assistant landing page (Claude Design export, 2026-05-27). Designed to test 3 dimensions simultaneously untested by R1+R2: **dark theme everywhere**, **B2B SaaS / digital subscription archetype**, **sans + monospace ONLY (no serif anywhere)**.

**Block type:** `surecart/product-page`
**Pattern name:** `lumen-saas-pdp`
**Output file:** [`lumen-saas.example.html`](./lumen-saas.example.html) (113 KB, 254 block opens, 13 sections — all dark)
**Paste-test status:** PASS after HC#45 comment-strip (zero "Attempt Block Recovery" prompts post-strip), 2026-05-27.

> **⚠️ Post-fix gold (not first-try clean):** Initial paste failed with recovery prompt due to 17 `<!-- Section N: ... -->` + 3 `<!-- {Tier} -->` descriptive HTML comments. Stripping them via `sed -i.bak -E '/^<!-- (Section|Starter|Pro|Team)/d'` produced clean parse. See HC#45 / A-45.

## Why this exemplar exists

Atlas Greens (R2 gold) covered warm-earth wellness in light theme. Lumen extends coverage to the **opposite ends** of the design space:

1. **Palette family — dark navy + electric blue + warm gold accent** (zero light/cream sections). EVERY section uses one of: `#0A1124` (page bg), `#131B33` (elevated card bg), `#050813` (deeper footer bg), `#1A2342` (highlight tint).
2. **Product category — B2B SaaS / digital subscription** with 3-tier pricing (Starter free / Pro $19/mo annual / Team $39/seat/mo), monthly/annual billing toggle, 14-day free trial. Different commercial model than physical/wellness products.
3. **Typography — Inter (sans) + JetBrains Mono ONLY, no serif anywhere.** All HC#41 Path B (literal font-family in inline style, NO `fontFamily:"surecart-display"` class attr). Numerics use monospace JetBrains Mono.

This is the first non-warm-earth, non-physical-product, non-serif gold exemplar. Covers SaaS / fintech / dev-tools landing pages.

## Section ledger (13 sections, all dark)

| # | Section | Composition | Skill primitives |
|---|---|---|---|
| 1 | Sticky header | `is-position-sticky` (HC#33) + wordmark + 5-link nav + CTA pill. Bg `#0A1124` + bottom hairline `#1F2A44`. | Custom flex, HC#33 |
| 2 | Hero (2-col) | LEFT 48%: eyebrow + h1 (`surecart/product-title`) + tagline + 2-button row + monospace meta. RIGHT 52%: mock terminal/PR window via `core/html` L1 with cyan + gold + green code coloring. | P2 hero split + P10 info column + `core/html` L1 terminal |
| 3 | Customer logos | `#131B33` band + cyan eyebrow + 6 publication names in monospace uppercase. | NO primitive — compositional flex |
| 4 | Feature grid 3×2 | `#0A1124` bg + section-head + 6 dark elevated cards. Each: `#131B33` bg, `#1F2A44` hairline, 20px radius, icon-bubble (44px tinted-blue) + h3 + body. | P5 multi-card + P3 vertical icon-text card |
| 5 | Pricing 3-tier | `#0A1124` bg + center-aligned section-head + monthly/annual toggle (`core/html` L1) + 3 pricing cards. Pro card highlighted with electric-blue 2px border + `#1A2342` bg + gold "MOST POPULAR" pill (P11). Each card: title + subtitle + price ($) + features ul (`core/html` with green ✓ checks) + CTA. | P5 + P4 chrome + P11 pill |
| 6 | Comparison table | `#131B33` bg + section-head + 4-col `core/html` L1 table. Pro column highlighted with `#1A2342` bg tint + 2px electric-blue border + rounded top/bottom corners. Same chrome pattern as R2 Atlas Greens compare table. | `core/html` L1 (column-highlight chrome) |
| 7 | Integrations 4×2 | `#0A1124` bg + section-head + 8 small integration cards in flex-wrap (`flexBasis:23.5%`). Each: round colored letter-badge + monospace name. | P5 multi-card + `core/html` L1 letter badges |
| 8 | Testimonials 3-up | `#131B33` band + section-head + 3 cards. Each: avatar circle (varied bg color: cyan, gold, blue) + name + role (monospace muted) + 5 gold stars + serif... wait NO serif — h3 Inter 18px + body + monospace city attribution. | P5 multi-card + P4 chrome + custom avatar via `core/html` |
| 9 | Stat counters | `#0A1124` bg + 4-up flex of monospace electric-blue numbers (`JetBrains Mono` 56px) + Inter caption. | NO primitive — compositional flex (heavily literal-font HC#41 Path B) |
| 10 | FAQ | `#131B33` band + center-aligned section-head + 6 `core/details` with first OPEN (`showContent:true` per W2.6). Dark hairline border-top/bottom on each. | P9 FAQ details list |
| 11 | Mid-page CTA band | `#131B33` band + centered eyebrow + h2 + body + electric-blue pill button + 3-check row (green ✓ + body text). | P6 CTA band |
| 12 | Resources / Changelog | `#0A1124` bg + 3-column grid (changelog list with date+title+body / Resources nav links / Newsletter form via `core/html` L1). | Custom 3-col |
| 13 | Footer (deeper dark) | `#050813` bg + 4-column grid (wordmark+tagline 28% / Product 18% / Company 18% / Social 36%). Bottom row: monospace copyright + city tagline separated by hairline. | Dark-section archetype (4-col extension vs R2's 3-col) |

## v7.18 doctrine demonstrated

### HC#33 — Sticky header (no inline position-mirror)

```html
<!-- wp:group {"align":"full","className":"is-position-sticky","style":{"position":{"type":"sticky","top":"0px"},...} -->
<div class="wp-block-group alignfull is-position-sticky has-background" style="..." (no position:sticky or top:0px inline) >
```

Sticks at top of viewport. Class drives behavior; inline style omits the JSON position/top.

### HC#41 — Path B (literal font-family, NO `fontFamily` class attr)

Every heading and paragraph emits ONLY `style.typography.fontFamily:"Inter, sans-serif"` (or `"JetBrains Mono, monospace"`), NEVER paired with `fontFamily:"surecart-display"`. The class would override the literal via theme CSS variable resolution; emitting only the literal keeps it deterministic.

Stat counters use monospace JetBrains Mono for "12K+", "2.4M", "80%", "200+" — proves Path B works at scale across 4 heading instances.

### HC#42 — `core/cover` `isDark` (N/A — Lumen uses ZERO covers)

The dark hero terminal is rendered via `core/html` L1 (not `core/cover`), so HC#42 doesn't trigger. If a future SaaS design uses `core/cover` with dark bg, OMIT `isDark` from JSON entirely.

### HC#45 — NO descriptive HTML comments (the lesson Lumen taught)

```html
<!-- ❌ ANTI-PATTERN (what Lumen v1 emitted) -->
<!-- Section 5: Pricing -->
<!-- wp:group ... -->
...
<!-- Starter tier -->
<!-- wp:group ... -->
```

The bare `<!-- Section 5: Pricing -->` and `<!-- Starter tier -->` comments parse as content tokens inside their parent group → Block Recovery. The grep-check `grep '^<!-- [^/w]' <file>` catches all violations.

### Dark-section archetype (v7.14, extended for 4-col footer)

Footer wraps text nodes with explicit `color.text` cream/muted-cream. Social icons use translucent-on-dark technique: `background:rgba(255,255,255,0.04)` + `border:1px solid #1F2A44`. 4-col grid extends R2's 3-col footer pattern.

### W2.6 — `core/details.showContent:true` for first FAQ

First FAQ item ("Does Lumen train on my codebase?") opens by default. Verified.

## Patterns successfully composed WITHOUT dedicated primitives

R2 Atlas already proved this for press strip / stat counter / testimonial / comparison table. R3 Lumen adds:

| Pattern | Composition strategy |
|---|---|
| Mock terminal window (hero RIGHT) | `core/html` L1 with `<pre>` + per-span colored code |
| Pricing card with feature list + ✓ icons | `core/group` card + `core/html` `<ul>` with inline-SVG checks |
| Monthly/annual pricing toggle | `core/html` L1 (decorative — actual binding requires `surecart/product-price-chooser`) |
| Integrations grid 4×2 (8 small cards) | Flex-wrap `core/group` with `flexBasis:"23.5%"` children |
| Letter-badge integration logos | `core/html` L1 inline `<div>` with `border-radius:9999px` |
| Stat counter monospace numerics | `core/heading` h3 with literal `fontFamily:"JetBrains Mono, monospace"` (HC#41 Path B) |
| Changelog list with date+title+body | Sibling `core/group` blocks with hairline border-bottom |
| 4-column dark footer | `core/columns` with custom widths (28%/18%/18%/36%) |

## Read this exemplar as canonical for

- **B2B SaaS / digital subscription** product pages (no physical product, no quantity stepper, no buy-now button — just CTA links to trial signup)
- **Dark theme EVERYWHERE** (not just one section like R2's footer)
- **Sans + monospace typography** with NO serif fallback
- **3-tier pricing with middle highlighted** (alternative pattern to R2's quantity-tier-discount strip)
- **Mock terminal / code blocks** via `core/html` L1 with multi-color inline spans
- **Customer logos strip** with differential typography per logo (similar to R2 press strip)
- **Comparison table with column highlight** (matches R2 Atlas pattern)
- **Stat counter strip with monospace numerics** (vs R2's serif numerics)
- **4-col dark footer** with social-icon bubbles
- **HC#45 demonstration** — proves what happens when descriptive comments leak in (used as cautionary tale, NOT a positive pattern to copy)
