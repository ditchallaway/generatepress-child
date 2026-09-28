# v7.21 golden snapshot

Frozen copies of the 14 fixture `golden.html` files as they existed at skill version **7.21.0** (the last release before the v7.22 intake-phase work began).

**Why this exists:** the zero-regression contract for v7.22 says convert-mode output must be structurally identical to v7.21. If, in the future, the canonical `tests/fixtures/<NN>/golden.html` files are intentionally updated (a deliberate doctrine reversal that re-golden's a fixture), this snapshot directory preserves the v7.21 byte-state for audit.

**Layout:**

```
v7.21-golden-snapshot/
├── README.md
├── 01-iphone.golden.html
├── 02-pricing-table.golden.html
├── ...
└── 14-screenshot-northwind.golden.html
```

**Do not edit these files.** They are an immutable snapshot. If a contract violation surfaces during v7.22 development, diff the live golden against the snapshot here to confirm whether the v7.21 byte-state was preserved.

**Cleanup policy:** delete this directory when v7.22.0 ships and the canonical goldens are confirmed to match (defense layer 3 → layer 5 transition).
