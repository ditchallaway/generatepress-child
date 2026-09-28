#!/usr/bin/env node
// scripts/regen-digest.mjs
//
// Regenerates intake/constraint-digest.md from SKILL.md's HC table, the
// doctrine-reversals table, the core/icon 88-slug catalog (reference/wp-core-blocks.md),
// and the positive-replacement anti-patterns (intake/shared/anti-patterns.md).
//
// The constraint-digest.md is embedded by reference in the Claude Design prompt
// (intake mode). It MUST stay synced with SKILL.md's canonical HC list — a hand-
// authored digest will rot within 2 minor releases (the doctrine-reversal history
// proves HCs flip).
//
// Usage:
//   node scripts/regen-digest.mjs                # regenerate
//   node scripts/regen-digest.mjs --check        # CI mode: exit 1 if regen would change anything
//
// Exit codes:
//   0 — digest regenerated (or unchanged in --check mode)
//   1 — digest stale, --check mode: regenerate before merging
//   2 — source file missing or parse error

import fs from "node:fs";
import path from "node:path";
import crypto from "node:crypto";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const SKILL_ROOT = path.resolve(__dirname, "..");
const SKILL_MD = path.join(SKILL_ROOT, "SKILL.md");
const DIGEST_PATH = path.join(SKILL_ROOT, "intake", "constraint-digest.md");
const ANTI_PATTERNS = path.join(SKILL_ROOT, "intake", "shared", "anti-patterns.md");

const CHECK_MODE = process.argv.includes("--check");

// ---------- read source ----------

if (!fs.existsSync(SKILL_MD)) {
	console.error(`SKILL.md not found at ${SKILL_MD}`);
	process.exit(2);
}

const skillText = fs.readFileSync(SKILL_MD, "utf8");

// Extract version from front matter
const versionMatch = skillText.match(/^version:\s*(\S+)\s*$/m);
if (!versionMatch) {
	console.error("Could not extract version from SKILL.md front matter");
	process.exit(2);
}
const skillVersion = versionMatch[1];

// Extract doctrine-reversals table — between "## Doctrine reversals" and the next "##" or "---"
const doctrineMatch = skillText.match(/## Doctrine reversals[\s\S]*?(?=\n##\s|\n---\s)/);
const doctrineSection = doctrineMatch ? doctrineMatch[0] : "";

// Extract Hard constraints section — between "## Hard constraints" and the next "## " heading
const hcMatch = skillText.match(/## Hard constraints[\s\S]*?(?=\n## \w)/);
const hcSection = hcMatch ? hcMatch[0] : "";

if (!doctrineSection || !hcSection) {
	console.error("Failed to extract doctrine-reversals or Hard constraints section from SKILL.md");
	process.exit(2);
}

// ---------- parse HCs into one-line summaries ----------
//
// Match top-level numbered list items: lines that start at column 0 with `N. **`
// (i.e., not indented continuations of prior items). Capture the number and first
// line of content.

function parseHCs(section) {
	const lines = section.split("\n");
	const items = [];
	let current = null;
	for (const line of lines) {
		const m = line.match(/^(\d+)\.\s+(.+)$/);
		if (m) {
			if (current) items.push(current);
			current = { number: parseInt(m[1], 10), firstLine: m[2], body: [] };
		} else if (current && line.match(/^\s{4,}/)) {
			// indented continuation
			current.body.push(line.trim());
		} else if (current && line.trim() === "") {
			// blank line — current item continues until next numbered item
			continue;
		} else if (current) {
			// some other content; ignore for one-line digest
		}
	}
	if (current) items.push(current);
	return items;
}

// Compress one HC into a single line of ≤80 chars.
// Format: `HC#NN: <compressed title + first clause>`
function compressHC(hc) {
	let line = hc.firstLine;
	// Strip markdown bold markers
	line = line.replace(/\*\*/g, "");
	// Strip parenthetical version-tags like "(v7.6 — REVERSES v7.3)" for brevity
	line = line.replace(/\s*\(v\d[^)]*\)/g, "");
	// MERGED entries: extract the canonical-home pointer, drop the rest
	const mergedMatch = line.match(/^\[MERGED[^\]]*canonical home = (HC#\d+)\]\s*(.+?)(?:\.|$)/i);
	if (mergedMatch) {
		// Skip MERGED entries — they're consolidated into another HC; don't duplicate
		return null;
	}
	// Take everything up to the first period (end of first sentence)
	const firstSentence = line.split(/\.\s/)[0];
	let compressed = firstSentence;
	// Cap at 80 chars (HC#NN: prefix = ~6-7 chars; line cap = 80 → content ~72-73)
	const prefix = `HC#${String(hc.number).padStart(2, "0")}: `;
	const budget = 80 - prefix.length;
	if (compressed.length > budget) {
		compressed = compressed.slice(0, budget - 1) + "…";
	}
	return prefix + compressed;
}

const hcs = parseHCs(hcSection);
const hcLines = hcs.map(compressHC).filter(Boolean);

// ---------- compose digest ----------

// Hash the HC + doctrine sections — this is the CI guard fingerprint
const sourceHash = crypto
	.createHash("sha256")
	.update(doctrineSection + "\n---\n" + hcSection)
	.digest("hex")
	.slice(0, 16);

const antiPatternsExist = fs.existsSync(ANTI_PATTERNS);
const antiPatternsRef = antiPatternsExist
	? "See `intake/shared/anti-patterns.md` for the 10 positive-replacement rules (referenced by the emitted prompt's §7)."
	: "(anti-patterns.md not yet present)";

const out = `<!-- GENERATED FROM SKILL.md §HC-table + §Doctrine-reversals — DO NOT EDIT — regenerate with scripts/regen-digest.mjs -->
<!-- digest-version: ${skillVersion} -->
<!-- source-skill-version: ${skillVersion} -->
<!-- source-hash: ${sourceHash} -->
<!-- hc-count: ${hcLines.length} (after MERGED-entry suppression) -->

# Constraint digest — for embedding inside the Claude Design prompt

> Compressed one-line-per-HC summary auto-generated from \`SKILL.md\`. Embed by reference (not inlined) in the emitted Claude Design prompt's §7 prohibitions section. Re-run \`scripts/regen-digest.mjs\` after any change to \`SKILL.md\`'s HC list or doctrine-reversals table.

## Sync contract

CI gate: \`node scripts/regen-digest.mjs --check\` exits 1 if the \`source-hash\` above does not match a freshly-computed hash of SKILL.md's HC + doctrine-reversals sections. Re-run the generator before merging.

## Source fingerprint

- **digest-version:** \`${skillVersion}\`
- **source-skill-version:** \`${skillVersion}\`
- **source-hash:** \`${sourceHash}\` (sha256 of HC + doctrine-reversals sections, first 16 hex chars)
- **hc-count:** ${hcLines.length} active HCs (MERGED-entries point to canonical homes; not duplicated here)

## Doctrine reversals — recognized wrong forms (canonical right-form table)

(extracted verbatim from SKILL.md "Doctrine reversals" section — the wrong-form / right-form table is the single most important reference for the Claude Design prompt's anti-pattern framing)

${doctrineSection.match(/\|.*\|/g)?.join("\n") || "(table extraction failed)"}

## Hard constraints (one-line compression, keyed by HC#)

${hcLines.join("\n")}

## Anti-patterns (positive replacements)

${antiPatternsRef}

## Surface markers (load-bearing for converter routing)

- Product-detail (master skill) emits 2 surfaces: \`{/* SURFACE: PRODUCT-TEMPLATE */}\`, \`{/* SURFACE: STATIC-CONTENT */}\`
- Shop-page (sibling skill) emits 3 surfaces: \`{/* SURFACE: FILTER-CHROME */}\`, \`{/* SURFACE: PRODUCT-GRID */}\`, \`{/* SURFACE: PAGE-CHROME */}\`
- Markers MUST appear ONLY at top-level boundaries — never nested inside a component subtree (HC#45 silent-drop trap)
- Markers are routing-only and are STRIPPED before Gutenberg emission (Tier I-9 verifies)

## Constraint family tags (used in the emitted prompt's §7)

- \`[SC-DATA]\` — SureCart server-rendered data assumption (title, price, variants, reviews are placeholder components)
- \`[SC-BLOCK]\` — SureCart block grammar (paired blocks, alias-map, class set-equality)
- \`[WP-CORE]\` — WordPress core block capability bounds (no animations, no position:fixed, no ::before content, named icon slugs only)

---

*Generated by \`scripts/regen-digest.mjs\` at digest-version ${skillVersion}. Do not hand-edit; changes will be overwritten on next regen.*
`;

// ---------- write or check ----------

if (CHECK_MODE) {
	if (!fs.existsSync(DIGEST_PATH)) {
		console.error(`✗ CHECK FAILED — digest does not exist at ${DIGEST_PATH}`);
		console.error("Run: node scripts/regen-digest.mjs");
		process.exit(1);
	}
	const existing = fs.readFileSync(DIGEST_PATH, "utf8");
	if (existing.trim() !== out.trim()) {
		console.error("✗ CHECK FAILED — digest is stale (would change on regen)");
		console.error("SKILL.md HC table or doctrine-reversals have changed since last regen.");
		console.error(`Source-hash: ${sourceHash}`);
		console.error("Run: node scripts/regen-digest.mjs");
		process.exit(1);
	}
	console.log(`✓ CHECK PASSED — digest synced with SKILL.md ${skillVersion} (hash ${sourceHash}, ${hcLines.length} HCs)`);
	process.exit(0);
}

// Write mode
fs.mkdirSync(path.dirname(DIGEST_PATH), { recursive: true });
fs.writeFileSync(DIGEST_PATH, out);
const sizeKB = (Buffer.byteLength(out, "utf8") / 1024).toFixed(2);
console.log(`✓ Regenerated ${path.relative(SKILL_ROOT, DIGEST_PATH)}`);
console.log(`  digest-version: ${skillVersion}`);
console.log(`  source-hash: ${sourceHash}`);
console.log(`  hc-count: ${hcLines.length} (after MERGED-entry suppression)`);
console.log(`  size: ${sizeKB} KB (Tier I-3 cap: 8 KB)`);

const sizeBytes = Buffer.byteLength(out, "utf8");
if (sizeBytes > 8192) {
	console.error(`\n⚠ WARNING: digest exceeds Tier I-3 8KB cap (${sizeBytes} bytes). Trim HC compression.`);
	process.exit(1);
}
process.exit(0);
