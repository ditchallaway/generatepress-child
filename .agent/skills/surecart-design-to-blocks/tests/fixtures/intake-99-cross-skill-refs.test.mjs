#!/usr/bin/env node
// intake-99-cross-skill-refs.test.mjs
//
// Asserts that every relative path of the form `../surecart-design-to-blocks/...`
// inside the sibling skill (surecart-design-to-shop-page) resolves to a real file.
//
// The sibling skill borrows references from the master via lazy-reference. If
// the master renames or removes a referenced file without updating the sibling,
// the sibling silently degrades (the LLM follows the broken path → empty read).
//
// This test catches that breakage immediately at PR time.
//
// Usage:
//   node tests/fixtures/intake-99-cross-skill-refs.test.mjs
//
// Exit codes:
//   0 — every cross-skill path resolves
//   1 — at least one cross-skill path is broken
//   2 — sibling SKILL.md not found

import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const MASTER_ROOT = path.resolve(__dirname, "..", "..");
const SKILLS_ROOT = path.dirname(MASTER_ROOT); // .../.claude/skills
const SIBLING_ROOT = path.join(SKILLS_ROOT, "surecart-design-to-shop-page");
const SIBLING_SKILL_MD = path.join(SIBLING_ROOT, "SKILL.md");

console.log("=== Cross-skill ref integrity test ===\n");

if (!fs.existsSync(SIBLING_SKILL_MD)) {
	console.error(`Sibling skill SKILL.md not found at ${SIBLING_SKILL_MD}`);
	process.exit(2);
}

const skillText = fs.readFileSync(SIBLING_SKILL_MD, "utf8");

// Extract every `../surecart-design-to-blocks/...` path from the sibling.
// Path forms encountered:
//   - markdown link: [text](../surecart-design-to-blocks/reference/X.md)
//   - inline backtick: `../surecart-design-to-blocks/reference/X.md`
//   - prose mention: ../surecart-design-to-blocks/reference/X.md § HC#N
//   - chained suffix: ../surecart-design-to-blocks/reference/X.md § core/icon
//
// Capture everything up to the first whitespace, closing paren, or backtick. Strip
// trailing punctuation (e.g., section markers `§ HC#21`) by stopping at whitespace.
const refPattern = /\.\.\/surecart-design-to-blocks\/[^\s`)]+/g;
const matches = [...new Set(skillText.match(refPattern) || [])];

if (matches.length === 0) {
	console.log("No cross-skill refs found in sibling SKILL.md (sibling may not yet borrow from master). Exiting clean.");
	process.exit(0);
}

console.log(`Found ${matches.length} unique cross-skill ref(s) in sibling SKILL.md\n`);

const broken = [];
const resolved = [];

for (const ref of matches) {
	// Strip any anchor / section marker the regex might have captured by accident
	const cleanRef = ref.replace(/[.,;]+$/, "");
	const absolutePath = path.resolve(SIBLING_ROOT, cleanRef);
	if (fs.existsSync(absolutePath)) {
		resolved.push({ ref: cleanRef, abs: absolutePath });
	} else {
		broken.push({ ref: cleanRef, abs: absolutePath });
	}
}

if (resolved.length > 0) {
	console.log("✓ Resolved refs:");
	resolved.forEach(({ ref }) => console.log(`  • ${ref}`));
}

if (broken.length > 0) {
	console.error("\n✗ BROKEN refs:");
	broken.forEach(({ ref, abs }) => console.error(`  • ${ref}\n    → ${abs}`));
	console.error("\nFAIL — the master skill renamed or removed file(s) the sibling references.");
	console.error("Fix: update the sibling SKILL.md to point to the new path, OR restore the master path.");
	process.exit(1);
}

console.log("\n=== PASS — all cross-skill refs resolve ===");
process.exit(0);
