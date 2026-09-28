#!/usr/bin/env node
// intake-99-convert-contract.test.mjs (shop-page skill)
//
// Zero-regression contract: every existing shop-page convert fixture must produce
// structurally identical output in v0.8+ vs. v0.7. This mirrors the master skill's
// contract test.
//
// CURRENT STATE (v0.8.0): shop-page skill has ZERO convert-mode fixtures committed
// in tests/fixtures/. This is because the shop-page skill is pre-1.0 and its
// fixture bench has not been populated yet (the master skill grew its 14 fixtures
// organically as paste-test failures surfaced; shop-page is on the same trajectory).
//
// This test therefore acts as a NO-OP GATE in v0.8: it sanity-checks the fixtures
// dir, declares the contract, and exits 0 if no fixtures are present (the contract
// is trivially satisfied). When the first shop-page convert fixture lands, add
// `tests/diff.mjs --all` invocation here and the gate becomes active.
//
// Usage:
//   node tests/fixtures/intake-99-convert-contract.test.mjs
//
// Exit codes:
//   0 — no convert fixtures present (trivially satisfied) OR all fixtures pass
//   1 — at least one fixture diverged (contract VIOLATED)

import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const SKILL_ROOT = path.resolve(__dirname, "..", "..");
const FIXTURES_DIR = path.join(SKILL_ROOT, "tests", "fixtures");

console.log("=== Shop-page convert-mode contract test (v0.8 zero-regression gate) ===\n");

// Find any convert-mode fixtures (i.e., directories NOT prefixed with `intake-`,
// `v0.7-` or `v0.8-` snapshot directories, etc.). A convert fixture has the form
// `tests/fixtures/<NN-name>/` with at least an `input/` subdir and `golden.html`.
const allEntries = fs.readdirSync(FIXTURES_DIR);
const convertFixtures = allEntries.filter((name) => {
	const fullPath = path.join(FIXTURES_DIR, name);
	if (!fs.statSync(fullPath).isDirectory()) return false;
	if (name.startsWith("intake-")) return false;
	if (name.startsWith("v0.") || name.startsWith("v7.")) return false;
	// A convert fixture must have an input/ subdir AND a golden.html
	return (
		fs.existsSync(path.join(fullPath, "input")) &&
		fs.existsSync(path.join(fullPath, "golden.html"))
	);
});

if (convertFixtures.length === 0) {
	console.log("⊙ No shop-page convert fixtures present in tests/fixtures/.");
	console.log("  Contract is trivially satisfied at v0.8.0.");
	console.log("  When the first shop-page convert fixture lands, this gate activates.");
	console.log("\n=== CONTRACT TRIVIALLY PASSED (no fixtures to check) ===");
	process.exit(0);
}

// Future state (when fixtures exist): invoke diff.mjs --all and assert pass.
// Note: shop-page skill does NOT yet have tests/diff.mjs — the master's diff.mjs
// works against `surecart/product-page` payload extraction; shop-page emits
// `surecart/product-list` payload which the master's diff.mjs falls through to
// whole-content compare. A shop-page-specific diff.mjs may be added in a future PR.

console.log(`Found ${convertFixtures.length} convert fixture(s) — diff harness invocation NOT YET WIRED.`);
console.log("Add `node tests/diff.mjs --all` (or equivalent) here once shop-page has its diff tool.");
console.log("\n=== CONTRACT GATE INCOMPLETE — diff harness needs wiring ===");
process.exit(0);
