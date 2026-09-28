# Merchant Guide — From Claude Design to a SureCart Product Page

This is the merchant-facing playbook. Hand it to anyone using the skill. It covers the full flow end-to-end: how to prompt Claude Design, how to export, how to ask Claude to convert, and how to handle the rare "Attempt block recovery" notice.

---

## The 5-step flow

1. **Design in Claude (or anywhere else)** — go to [design.claude.com](https://design.claude.com) and prompt Claude to design your product page (see prompt examples below). The skill also accepts other inputs — see "Input options" right below.
2. **Export the project** — *Share → Export as project (.zip)*. Do NOT use *Export as HTML* (single bundled file is unparseable for us).
3. **Open Claude Desktop or claude.ai** — start a new chat.
4. **Drag the zip + ask** — drop the zip in and say: *"Use the surecart-design-to-blocks skill to convert this design."*
5. **Paste into WordPress** — copy the fenced ```html block Claude returns → open WP editor → ⋮ menu → **Code editor** → paste → switch back to **Visual editor** → save.

That's it. Most pages convert with zero recovery prompts on first paste.

### Finding the SureCart product-page block in the inserter

If you're searching the block inserter (`/` slash command or the `+` button), the block is registered as **`surecart/product-page`** but its **display title in the inserter is "Product Form"** (per `packages/blocks-next/src/blocks/product-page/block.json#title`). Search for "Product Form" — not "Product Page" — when looking for it in the inserter UI. The skill's emitted markup always uses the canonical block name `surecart/product-page`; the inserter label is purely cosmetic.

### Input options (v7.0+)

You don't have to use Claude Design. The skill accepts four input modes — each routes to the same conversion pipeline:

| Input | When to use | Confidence |
|---|---|---|
| **Claude Design `.zip`** | You're starting from scratch and want the best design fidelity. | High (canonical) |
| **Single screenshot** (PNG/JPG/WebP) | You have a mock-up image but no source code. Image must be ≥ 1200px on the long edge. | Medium-Low |
| **HTML + CSS** | You have static `.html` + `.css` files, or a CodePen export. | Medium-High |
| **Live URL** | You want to clone a published static or SSR page (`https://…`). | Medium-High (rejected for SPAs) |

For screenshot input, Claude reports per-dimension confidence (typography / color / spacing / layout / assets) so you know what to verify in the editor after paste.

For Figma right now: export the frame as PNG (screenshot mode) or paste the rendered HTML+CSS — direct Figma URL support is coming in a later release.

---

## Prompt examples for Claude Design

The quality of the output depends on how you prompt the designer. Here are tested prompts that produce conversion-friendly designs.

### Recipe — section components with `<BlockTag>` annotations

The skill looks for `<BlockTag blocks={["surecart/..."]} />` markers in the JSX. Telling Claude Design to add them upfront is the single biggest fidelity boost.

#### Example prompt — full product page

> Design a premium product detail page for **{PRODUCT NAME}**, a {SHORT DESCRIPTION}.
>
> Layout requirements:
> - Hero with product gallery on the left, title + price + buy buttons on the right
> - Highlights section with 4 feature cards in a grid
> - 3 alternating media+text sections describing key features (image left, then right, then left)
> - Specifications table (one column for label, one for value)
> - FAQ section with 6 collapsible questions
> - "You may also like" related products grid (4 cards)
> - Final CTA banner with brand-color background
>
> Conversion hints (please add these to your JSX):
> - On the hero section, render `<BlockTag blocks={["surecart/product-page", "surecart/product-media", "surecart/product-title", "surecart/product-description", "surecart/product-price-chooser", "surecart/product-variant-pills", "surecart/product-buy-buttons"]} />`
> - On the FAQ items, use the native `<details><summary>...</summary>...</details>` pattern (no custom accordion)
> - On the related grid, render `<BlockTag blocks={["surecart/product-list-related"]} />`
> - On the final CTA, render `<BlockTag blocks={["surecart/product-buy-buttons"]} />`
>
> Use a clean modern aesthetic. Color palette: **{describe palette in 3 hex values}**. Typography: a display font for headings, a humanist sans for body.

#### Example prompt — minimal hero section only

> Design a product hero for **{PRODUCT NAME}** with:
> - Two-column layout (image gallery left at 50% width, product info right at 50%)
> - Product title (h1, large)
> - Star rating + review count
> - Storage tier picker (3 options: 256GB, 512GB, 1TB)
> - Color variant pills (5 colors)
> - Quantity stepper
> - Two buy buttons stacked: "Add to Bag" (primary) and "Buy Now" (secondary)
> - 3-column footer below buttons: "Free shipping", "30-day returns", "AppleCare+ available"
>
> Render `<BlockTag blocks={["surecart/product-page", "surecart/product-media", "surecart/product-title", "surecart/product-review-summary", "surecart/product-price-chooser", "surecart/product-variant-pills", "surecart/product-quantity", "surecart/product-buy-buttons"]} />` on the hero wrapper.
>
> Brand color: **#01824C**. Neutral backgrounds: **#FFFFFF** primary, **#F4F6F8** secondary.

#### Example prompt — landing page with multiple sections

> Design a course landing page for **{COURSE NAME}**:
> - Hero with course title, instructor name, "Enroll now" button, and a video placeholder
> - Outcomes section: 4 bullet points of what the student will learn
> - Curriculum: 8 numbered modules in a vertical list
> - Instructor bio with headshot left, bio right
> - Testimonials: 3 quote cards in a row
> - Pricing card with feature list and "Enroll now" button
> - FAQ accordion with 5 questions
>
> Conversion hints:
> - Render `<BlockTag blocks={["surecart/product-page", "surecart/product-buy-buttons"]} />` on the hero AND on the pricing card
> - For testimonials, use `<blockquote>` elements (will map to `core/quote`)
> - For curriculum, use `<ol>` with `<li>` (will map to `core/list` ordered)
>
> Sober editorial aesthetic. Brand color **#3B82F6**. Generous whitespace.

### What NOT to do in the design prompt

- **Don't ask for animations.** Hover states, scroll-triggered transforms, parallax — none of these survive the JSX-to-Gutenberg conversion. Design for the static rendered state.
- **Don't ask for custom React state.** Carousels with `useState`, accordions built from scratch — Gutenberg has native equivalents (`core/details` for accordions). Ask Claude Design to use semantic HTML.
- **Don't ask for fixed/sticky positioning** unless you tell Claude it'll be a `surecart/sticky-purchase-button` or you accept that it'll render statically.
- **Don't ask for data fetching.** No `useEffect` calls, no API calls. The design is static markup.
- **Don't ask for dark-mode toggle**, system color scheme, or `prefers-color-scheme` queries. Pick one palette.

### Mid-design refinement prompts

After the first design, you can refine in Claude Design with prompts like:

- *"Tighten the spacing between sections — reduce vertical padding from 96px to 64px on every section wrapper."*
- *"Make the highlights cards taller and add a subtle 1px border in #E5E7EB."*
- *"Replace the image placeholders in the description blocks with `<BlockTag blocks={["surecart/product-image"]} />` — they should pull from product media."*
- *"Add a 'Compare' link in the right rail of the specs section heading row."*

---

## What the skill output looks like

Claude returns:

1. **A self-validation line**: e.g., "Self-validation: Tier A 14/14, Tier B 7/7, Tier C skipped (input: zip)."
2. **One fenced ```html block** — the Gutenberg markup. Copy this whole block.
3. **A drift report** (small JSON) — sections detected, blocks emitted, anything dropped, and a per-image `assets[]` list with placeholder URLs you'll replace after upload.
4. **Paste instructions** — sometimes with a capability warning if your output uses custom HTML widgets (see "Custom HTML widgets in your output" below).

You only need the ```html block. The drift report is useful when:
- A section looks visually thin → check `dropped_features` for shadows/animations/gradients you'll need to add via theme CSS (each entry has a `merchant_action` field telling you exactly what CSS snippet to add).
- A widget doesn't behave the way you expected → check for `raw_html_interactive` entries with `level:"L2"` or `"L3"` (see "Custom HTML widgets" below).
- Some images aren't showing → cross-reference `assets[]` to see which placeholder URLs you need to replace.

---

## Pasting into WordPress

1. In your WP admin, open the page where you want the design (or create a new page).
2. Click the ⋮ menu (top right) → **Code editor**, or press `Cmd+Shift+Alt+M` (Mac) / `Ctrl+Shift+Alt+M` (Windows).
3. **Select all existing content in the code editor and delete it** (if you want a fresh page) or position your cursor where you want to insert.
4. Paste the markup.
5. Click **Exit code editor** (top of the panel) — this switches to Visual editor.
6. Save / Publish.

> **Tip:** Make sure SureCart is updated to the latest version (≥4.3) before pasting. Older versions don't ship the `SavePostFilter` safety net that normalizes any edge-case markup at save time.

---

## When you see "Attempt Block Recovery"

A yellow notice on a block. The recovery dialog has **4 distinct buttons** with very different behaviors — pick carefully.

### The 4 convert paths (v7.18.3 PR2 — verified against `@wordpress/block-editor` source)

| Button | Source path | Lossy? | What it does |
|---|---|---|---|
| **Attempt Block Recovery** | `block-invalid-warning.js:67-74` — `toRecoveredBlock` | **Safe** | Re-creates the block from PARSED attrs via `createBlock(block.name, block.attributes, block.innerBlocks)`. No HTML capture, no wrapping artifact. Attrs preserved. |
| **Resolve → Convert to Blocks** | `block-invalid-warning.js:84-95` — modal calls `rawHandler({HTML: block.originalContent})` | **Lossy** | Reparses the original-content HTML through the raw-HTML pipeline. Style/typography JSON attrs are lost; content becomes plain HTML. |
| **Convert to HTML** | `block-invalid-warning.js:51-62` — uses `block.originalContent` → `core/html` | **Lossy** | Wraps the original content in a `core/html` block (raw HTML escape hatch). |
| **Convert to Classic Block** | `block-invalid-warning.js:51-62` — uses `block.originalContent` → `core/freeform` | **Lossy** | Wraps the original content in `core/freeform` (Classic editor block). |

### Step 1 — Click "Attempt Block Recovery" first

Per the table, this is SAFE — it re-creates the block from parsed attrs. **Do not be afraid of this button.** Earlier versions of this doc warned against clicking it; that was based on a fictional "save filter" claim. Verified against WP 6.7 `block-invalid-warning.js`: recovery preserves attrs and re-renders the block.

**If recovery clears on click → no attrs lost.** Save and reload to verify.

### Step 2 — If recovery reappears on reload, inspect the source

The cause is usually a class/style mismatch in the post body that re-emerges every render. Open Code editor (⋮ menu → Code editor), find the failing block, and check for:

- Long string values in JSON attrs (`fontFamily` literal stacks, long `summary` text, gradient strings) — HC#16 (`JSON.parse` null cascade) trigger
- Unescaped `<`, `>`, `&`, `"`, or `--` inside JSON string values — HC#49 (`serializeAttributes` round-trip escape) trigger
- Comments inside block content areas (anything other than `<!-- wp:* -->` delimiters) — HC#45 trigger

### Step 3 — Re-paste from the file

If a JSON / class-set mismatch is hard to fix in-editor, the fastest path is to re-paste the corrected markup from the source file (per Step 4 paste-path in SKILL.md — `pbcopy < ~/Desktop/{slug}.html` on macOS, etc.).

### When to use the LOSSY buttons

Only when you've decided to convert the block to raw HTML / Classic editor permanently (intentional fallback). The lossy paths discard the structured-block JSON; you cannot go back to the original block after clicking them. **NEVER click these to "fix" a recovery prompt** — they don't fix anything, they just replace the block.

### Step 2 — If recovery clears once but reappears on reload

This is a save() output mismatch. Tell Claude:

> "Block X is showing recovery. I clicked the button and saved, but it comes back on reload. Here's the F12 console output: {paste the console output, especially the 'Content generated by save function:' and 'Content retrieved from post body:' diff}"

The console diff is the ground truth — it tells Claude exactly what byte-level mismatch caused validation to fail.

To open F12 console:
- **Chrome/Edge**: F12 → **Console** tab
- **Firefox**: F12 → **Console** tab
- **Safari**: Cmd+Option+I → **Console** tab

Filter for "Block validation" to see only the relevant errors.

### Step 3 — Surgical replacement

If a specific block keeps recovering, ask Claude:

> "Just regenerate the {section name} section — replace from `<!-- wp:group ... -->` through `<!-- /wp:group -->` for that section. Don't redo the whole page."

Find the block in your Code editor, select from its opening comment to closing comment, and replace.

---

## Common paste-time gotchas

### Gotcha 1 — Long inline `style="..."` got line-wrapped during paste

Symptom: F12 console shows the diff is just whitespace inside a `var(...)` call:

```
Expected: padding-right:var(--wp--preset--spacing--60);
Saw:      padding-right:var(--wp--preset--spacing--60
  );
```

This used to happen with preset spacing slugs. As of skill v5.9 the converter emits literal `24px`/`48px`/`96px` instead of `var(--wp--preset--spacing--60)`, so the long-string wrap is gone. If you see this on older markup, just delete the literal whitespace (newline + 2 spaces) inside the `var()` parens in Code editor.

### Gotcha 2 — "Block surecart/foo is already registered" warning in console

Cosmetic only. Doesn't affect rendering. SureCart double-registers some blocks on the editor page; ignore.

### Gotcha 3 — Image placeholders are blank

The conversion can't carry image bytes. After paste:
- For `core/image` blocks → click the placeholder → upload your image.
- For `surecart/product-media` and `surecart/product-image` → these pull from the **product**'s media, not from the design. Edit the product in SureCart to set its media.

### Gotcha 4 — Headings render in the wrong font

Geist (display) and the body font are loaded by SureCart's theme partial. If headings render in a generic system font:
- Confirm SureCart is active and ≥4.2.
- Confirm the SureCart theme partial is registered (it is by default — this is automatic).
- If you've overridden global styles, check the **Site Editor → Styles → Typography** doesn't override the heading font family.

### Gotcha 5 — Custom HTML widget renders but doesn't behave (v7.1+)

If your design includes a custom interactive widget (a "View cart" pill that toggles the drawer, a bespoke lightbox trigger, an "Add to cart" pill that lives outside the normal product blocks), the drift report will show a `raw_html_interactive` entry with `level:"L2"` and a `dependency_block` field. Two things must be true for that widget to actually work:

1. **You must save the page as Administrator or Editor.** Lower roles (Author / Contributor) have the `data-wp-*` directives stripped on save by WordPress's `unfiltered_html` capability check. The widget will paste fine, but the directives won't survive the first save → button does nothing on the front-end. If this happens, an admin/editor needs to re-save the same content.
2. **The `dependency_block` must remain on the page.** L2 widgets piggyback on a SureCart block already on the page (e.g., the cart drawer toggle relies on `surecart/cart-icon` loading the cart's interactivity module). If you delete that sibling block, the widget renders fine but its directives stop hydrating — clicks do nothing. Check the drift report's `dependency_block` field, find that block in the Code editor, and don't delete it.

If the widget *displays* incorrectly (square corners instead of pill shape, missing badge styling), that's theme CSS overriding the widget's inline styles — see "Custom HTML widgets in your output" below for the theme CSS snippets to add.

### Gotcha 6 — A whole section shows "0 content" after paste

A `core/details` accordion or a `core/group` section pastes but renders empty in the visual editor. This is almost always one of two save() mismatches that we fixed in v7.2 — but if you have older skill output sitting around, you might still hit it:

- **`<summary>` has classes/styles on it** — Gutenberg's `core/details` `save()` emits a bare `<summary>{text}</summary>`; any class or style attribute on `<summary>` makes the wrapper not match → recovery → empty content. Fix: open the block in Code editor, strip everything off `<summary>` so it's just `<summary>question text</summary>`.
- **`core/group` wrapper missing `is-layout-constrained wp-block-group-is-layout-constrained` classes** — when the block opener has `"layout":{"type":"constrained"}` in its JSON attrs, the wrapper `<div class="wp-block-group">` MUST include both layout classes. Fix: add ` is-layout-constrained wp-block-group-is-layout-constrained` to that div's class list.

Re-running the skill on the same input (with the v7.2+ skill) emits clean output for both cases automatically.

---

## Iteration — asking Claude for fixes

If you've pasted the design and want changes, you have two options:

### Option A — Edit in WP visual editor

For small tweaks (text changes, color swaps, padding adjustments), just edit the block directly in WP. The Visual editor handles all of these without recovery prompts.

### Option B — Ask Claude to regenerate a section

For larger changes ("redesign the FAQ", "make the highlights 3-column instead of 4"), tell Claude:

> "Regenerate just the {section} section of the page I sent before. Keep the rest of the page untouched. The change I want is: {describe change}."

Claude will return ONE fenced block with just that section. Find the section in your Code editor, replace it.

### Option C — Re-export from Claude Design + reconvert

If you want to redesign a whole page, go back to Claude Design, tell it the changes, re-export, and run through the skill again. Faster than asking Claude to manually rebuild.

---

## When SureCart blocks show recovery

`surecart/*` blocks are server-rendered. They almost never show recovery. If one does:

1. **Block name doesn't exist** — Claude hallucinated a block name. Tell Claude: "Block `surecart/X` doesn't exist. Check `reference/full-inventory.json` and use the correct name."
2. **Block is outside its required ancestor** — e.g., `surecart/product-buy-button` MUST be inside `surecart/product-buy-buttons`. Tell Claude: "Wrap `surecart/product-buy-button` in its required parent."
3. **SureCart plugin too old** — your installed SureCart version doesn't have this block yet. Update SureCart, then refresh the editor.

---

## Custom HTML widgets in your output (v7.1+)

Sometimes your design has an element that doesn't map to any SureCart or WP-core block — a custom badge, a cart-toggle pill, a "View larger image" link that opens the gallery lightbox. The skill emits these as `core/html` blocks (raw HTML) and classifies them in three tiers:

| Tier | Has behavior? | What to expect | What you do |
|---|---|---|---|
| **L1** static | No (decorative only) | A `core/html` block with your custom HTML. Renders as-is. Style it via theme CSS. | Add CSS rules in **Appearance → Customize → Additional CSS** keyed off the widget's class names. |
| **L2** Interactivity piggyback | Yes — uses SureCart's existing actions/state | A `core/html` block with `data-wp-interactive` directives. Behavior is provided by a sibling SureCart block on the same page. | (1) Save as admin/editor (capability gate). (2) Don't delete the `dependency_block` named in the drift report. (3) Style via theme CSS. |
| **L3** custom interactivity | Yes — but no SureCart equivalent | A static visual fallback (the design's chrome but not the behavior). Drift report recommends invoking `/surecart-new-block` to scaffold a real interactive block. | Either accept the static fallback or have a developer scaffold a custom block. |

### Theme CSS for L2 widgets

L2 widgets ship with inline styles, but most WordPress themes override `<button>` styles aggressively. If your widget renders with the wrong shape (square instead of pill, missing badge, wrong color), open **Appearance → Customize → Additional CSS** and paste a rule keyed off the widget's class. Example for a cart-toggle pill:

```css
.hero-cart-pill__btn {
	background-color: #01824C !important;
	color: #FFFFFF !important;
	padding: 16px 32px !important;
	border: none !important;
	border-radius: 999px !important;
	display: inline-flex !important;
	align-items: center !important;
	gap: 8px !important;
}
```

The `!important` flag is necessary specifically to beat your theme's button reset. Cross-reference the widget's `class` attribute in your output for the exact selector.

### Capability gate (important)

L2 widgets use `data-wp-*` attributes, which WordPress's `wp_kses_post()` filter strips for users without the `unfiltered_html` capability. By default that's **administrators and editors only**. Authors, contributors, and lower roles will paste the widget fine but on save the `data-wp-*` attributes get stripped → button stops working.

If you're on a multi-author site:
- Have an admin or editor save the page initially. Once saved, lower-role users can edit other content without re-stripping the directives (WP only re-runs KSES on the change being saved).
- OR ask a developer to install a small mu-plugin that grants the role you need `unfiltered_html`.

---

## What the skill won't do for you

- **Won't carry images.** You upload them after paste. Drift report's `assets[]` lists every placeholder URL to replace.
- **Won't carry custom JS interactions** (the design's `useState`, `useEffect`, custom carousels, animated counters, count-up effects). The L2 piggyback works only when the behavior maps to actions SureCart already exposes (`actions.toggle`, `actions.openLightbox`, etc.). Anything else → static fallback + drift report recommends `/surecart-new-block`.
- **Won't generate products.** The blocks reference products, but you create the actual products separately in SureCart admin.
- **Won't preserve responsive overrides.** The design renders the same on all breakpoints; you can't currently express "padding 32px on desktop, 16px on mobile" through the conversion. Use the WP block editor's **Mobile preview** mode to adjust per-breakpoint after paste.
- **Won't pick fonts that aren't in SureCart's theme.** The available fonts are Geist (`surecart-display`), the body sans (`surecart-body`), and a mono (`surecart-mono`). If the design uses Inter or DM Sans, those will fall back to one of the three. Drift report's `dropped_features` will log a `custom-font` entry recommending you add the font via `theme.json` if you want to register it as a slug.
- **Won't preserve gradients on non-cover blocks.** `core/cover` natively supports gradients; on `core/group` and similar wrappers, gradients flatten to the first stop's solid color. Drift report logs a `gradient` entry with the original CSS so you can paste it into theme CSS.
- **Won't preserve box shadows, hover effects, transforms, or `backdrop-filter`.** All logged in `dropped_features` with a CSS snippet you can paste into theme CSS.

---

## TL;DR — One-page summary for your team

```
1. design.claude.com  →  prompt for a product page  →  ask for <BlockTag> annotations
   (or skip 1+2: attach a screenshot, HTML+CSS, or a live URL instead)
2. Share → Export as project (.zip)
3. Claude Desktop  →  drag input  →  "Use the surecart-design-to-blocks skill"
4. Copy the html block Claude returns
5. WP editor → ⋮ → Code editor → paste → exit Code editor → save
   (must be admin/editor if drift report shows raw_html_interactive entries)
6. After paste:
   - upload images for every placeholder URL listed in drift report's assets[]
   - paste the CSS snippets from drift report's dropped_features[].merchant_action
     into Appearance → Customize → Additional CSS
7. If recovery: click button → save → reload. If it persists: paste F12 console diff back to Claude.
```
