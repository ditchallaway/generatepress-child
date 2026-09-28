#!/usr/bin/env node
// intake-99-convert-contract.test.mjs
//
// Zero-regression contract: every existing convert fixture must produce
// structurally identical output in v7.22+ vs. v7.21. This is defense layer 5
// in the plan — the backstop ensuring Step 0's addition does not perturb any
// byte of the existing convert pipeline.
//
// What this script does:
//   1. Asserts the 14 expected fixtures exist on disk (sanity check).
//   2. Invokes the existing test harness via `node tests/diff.mjs --all`.
//   3. Exits 0 if all 14 fixtures pass structural-equivalence vs. their goldens.
//   4. Exits 1 if any fixture diverges.
//   5. Surfaces freshness warnings: if any fixture's actual.html is missing or
//      older than the parent SKILL.md, it has not been re-paste-tested at the
//      current skill version — the merchant must re-run the manual paste-test
//      workflow before this contract can be asserted.
//
// Usage:
//   node tests/fixtures/intake-99-convert-contract.test.mjs
//
// Exit codes:
//   0 — all 14 fixtures structurally identical to their goldens
//   1 — at least one fixture diverged (contract VIOLATED)
//   2 — fixture missing on disk, or actual.html files stale (re-paste-test needed)
//
// Manual replay workflow (see tests/README.md):
//   For each of the 14 fixtures, open a fresh Claude conversation with the skill
//   activated, paste the input/, save the emitted markup to actual.html, then
//   run this script. The script DOES NOT invoke Claude — the skill is interactive.

import fs from "node:fs";
import path from "node:path";
import { execSync } from "node:child_process";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const SKILL_ROOT = path.resolve(__dirname, "..", "..");
const FIXTURES_DIR = path.join(SKILL_ROOT, "tests", "fixtures");
const SKILL_MD = path.join(SKILL_ROOT, "SKILL.md");
const DIFF_SCRIPT = path.join(SKILL_ROOT, "tests", "diff.mjs");

// The 14 fixtures whose goldens define the zero-regression contract.
// This list is FROZEN — adding a fixture means adding a contract row + a paste-test.
const CONTRACT_FIXTURES = [
	"01-iphone",
	"02-pricing-table",
	"03-feature-grid",
	"04-faq",
	"05-hero-cta",
	"06-custom-html-l2",
	"07-product-standard",
	"08-product-physical",
	"09-product-course",
	"10-list-standard",
	"11-list-sidebar",
	"12-sticky-purchase",
	"13-product-quick-view",
	"14-screenshot-northwind",
];

let exitCode = 0;

console.log("=== Convert-mode contract test (v7.22 zero-regression gate) ===\n");

// --- Sanity: all 14 fixtures present on disk
const missing = CONTRACT_FIXTURES.filter(
	(name) => !fs.existsSync(path.join(FIXTURES_DIR, name))
);
if (missing.length > 0) {
	console.error("FAIL — contract fixtures missing on disk:");
	missing.forEach((name) => console.error(`  • ${name}`));
	console.error("\nThe 14 convert fixtures defining the v7.21 contract are required for this test.");
	process.exit(2);
}
console.log(`✓ All ${CONTRACT_FIXTURES.length} contract fixtures present on disk`);

// --- Freshness: actual.html must exist and be at-or-after SKILL.md mtime
const skillMtime = fs.statSync(SKILL_MD).mtimeMs;
const stale = [];
const noActual = [];
for (const name of CONTRACT_FIXTURES) {
	const actualPath = path.join(FIXTURES_DIR, name, "actual.html");
	if (!fs.existsSync(actualPath)) {
		noActual.push(name);
		continue;
	}
	const actualMtime = fs.statSync(actualPath).mtimeMs;
	if (actualMtime < skillMtime) {
		stale.push(name);
	}
}

if (noActual.length > 0) {
	console.error("\n⚠ FRESHNESS — actual.html missing (paste-test not yet run at current skill version):");
	noActual.forEach((name) => console.error(`  • ${name}`));
	console.error("\nRun the manual paste-test workflow (tests/README.md) before this contract can be asserted.");
	exitCode = 2;
}

if (stale.length > 0) {
	console.error("\n⚠ FRESHNESS — actual.html older than SKILL.md (paste-test may be stale):");
	stale.forEach((name) => console.error(`  • ${name}`));
	console.error("\nRe-run the manual paste-test workflow for these fixtures.");
	if (exitCode === 0) exitCode = 2;
}

if (exitCode === 2) {
	console.error("\nContract NOT YET asserted — re-paste-test the listed fixtures, then re-run.");
	process.exit(2);
}

// --- Structural equivalence: invoke existing diff.mjs --all
console.log("\n→ Running diff.mjs --all against the 14 contract fixtures…\n");
try {
	execSync(`node "${DIFF_SCRIPT}" --all`, { stdio: "inherit" });
	console.log("\n=== CONTRACT PASSED — all 14 convert fixtures structurally identical to v7.21 goldens ===");
	process.exit(0);
} catch (err) {
	console.error("\n=== CONTRACT VIOLATED — at least one convert fixture diverged ===");
	console.error("\nThis means Step 0 (or some other v7.22 change) perturbed the convert pipeline.");
	console.error("Inspect the diff above. If the divergence is intentional, the contract has been broken — bump to v8.0.0 MAJOR.");
	console.error("If the divergence is unintentional, the v7.22 change has regressed convert mode — revert or fix before merging.");
	process.exit(1);
}
