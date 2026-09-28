# Tone presets — brand voice options for the merchant brief

Lookup used by the emitted Claude Design prompt's §6 merchant-brief section. Q5 visual mood + (optional) Q6 typography combine to produce a one-sentence tone descriptor that anchors copy + photography choices in the design.

## Q5 visual mood → tone descriptor

| Q5 answer | Tone descriptor (spliced into §6) |
| --- | --- |
| Light & airy | Confident but understated. Generous whitespace, soft photography, warm-but-restrained copy. Avoid sales-y exclamations. |
| Bold & modern | High-energy, contemporary, decisive. Strong CTAs, color-blocked sections, copy is direct ("Designed for today. Built to last."). |
| Dark & premium | Editorial, restrained, considered. Dense typography is permitted; copy is short and weight-bearing. Avoid hard-sell language. |
| Earthy & organic | Story-led, grounded, tactile. Hand-feel imagery (texture, depth-of-field), narrative copy that references provenance + craft. |
| Tech-SaaS / dashboard | Information-dense, metric-forward, precise. Data visualizations welcome; copy is benefit-led ("Cut deployment time by 40%."). Avoid lifestyle imagery. |
| Clean default | Neutral, accessible, no commitment. Stock-photography placeholder energy. Copy is generic ("Quality you can rely on."). |

## Optional copy-tone overlays (PR4+)

When the merchant explicitly mentions a brand voice in the questionnaire's free-text fields or in a follow-up turn, layer one of these adjectives onto the Q5-derived tone:

- **Conversational** — second-person, contractions, sentences that could be read aloud
- **Editorial** — third-person, full sentences, magazine-style headers
- **Technical** — precise terminology, specifications-led, benchmark-quoting
- **Playful** — wordplay, lower-case headings, punctuation chosen for rhythm
- **Authoritative** — declarative statements, no qualifiers, citation-style references

The emitted prompt's §6 splices the Q5-derived descriptor as the default and adds any overlay adjective(s) as a parenthetical clause: *"Confident but understated, with a playful overlay."*

## Use note

These presets exist to give Claude Design a copy-and-photography compass — not to dictate exact wording. The merchant edits copy post-paste. The tone descriptor's job is to ensure Claude Design's invented placeholders don't fight the merchant's brand.
