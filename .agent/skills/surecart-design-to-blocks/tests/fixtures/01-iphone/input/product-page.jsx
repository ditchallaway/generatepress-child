// SureCart Product Page Template — iPhone 17 Pro
// Annotated to map 1:1 to SureCart blocks + WordPress core blocks.

const { useState, useEffect, useRef } = React;

// ────────────────────────────────────────────────────────────────────────────
// Tokens & helpers
// ────────────────────────────────────────────────────────────────────────────
const ppGreen = "var(--brand)";
const ppDeep = "var(--brand-deep)";
const ppBg = "var(--bg-1)";
const ppBgAlt = "var(--bg-2)";
const ppFg1 = "var(--fg-1)";
const ppFg2 = "var(--fg-2)";
const ppFg3 = "var(--fg-3)";
const ppBorder = "var(--border-1)";

const Icon = ({ name, size = 20, stroke = 1.6, style }) => (
  <i data-lucide={name} style={{ width: size, height: size, strokeWidth: stroke, ...style }} />
);

// Annotation chip — tags a section with the WP/SureCart blocks that compose it.
// Toggled globally via window.__showBlockAnnotations.
const BlockTag = ({ blocks, position = "top-left", showAnnotations }) => {
  if (!showAnnotations) return null;
  const pos = {
    "top-left": { top: 12, left: 12 },
    "top-right": { top: 12, right: 12 },
    "bottom-left": { bottom: 12, left: 12 },
    "bottom-right": { bottom: 12, right: 12 },
  }[position];
  return (
    <div style={{
      position: "absolute", ...pos, zIndex: 5,
      display: "flex", flexWrap: "wrap", gap: 4, maxWidth: 360,
      pointerEvents: "none",
    }}>
      {blocks.map((b, i) => (
        <span key={i} style={{
          fontFamily: "var(--font-mono)", fontSize: 10, fontWeight: 700,
          padding: "3px 7px", borderRadius: 4,
          background: b.startsWith("surecart/") ? "rgba(1,130,76,0.92)" : "rgba(17,24,39,0.88)",
          color: "#fff", letterSpacing: "0.02em",
          boxShadow: "0 1px 2px rgba(0,0,0,0.15)",
          backdropFilter: "blur(4px)",
        }}>{b}</span>
      ))}
    </div>
  );
};

// ────────────────────────────────────────────────────────────────────────────
// Product data
// ────────────────────────────────────────────────────────────────────────────
const COLORWAYS = [
  { id: "titanium", name: "Natural Titanium", hex: "#C8C2B6", g1: "#E8E2D6", g2: "#A8A296" },
  { id: "desert",   name: "Desert Titanium",  hex: "#B6926E", g1: "#D9B58C", g2: "#8E6B47" },
  { id: "blue",     name: "Blue Titanium",    hex: "#3F4E5E", g1: "#5C6F84", g2: "#2A3744" },
  { id: "black",    name: "Black Titanium",   hex: "#3A3A3C", g1: "#5A5A5C", g2: "#1A1A1C" },
];

const STORAGE = [
  { id: "256", label: "256 GB", priceDelta: 0 },
  { id: "512", label: "512 GB", priceDelta: 200 },
  { id: "1tb", label: "1 TB",   priceDelta: 400 },
];

const HIGHLIGHTS = [
  { icon: "cpu",         title: "A19 Pro Bionic", body: "The fastest chip ever in a smartphone. Ray-tracing graphics, on-device AI, and all-day efficiency." },
  { icon: "camera",      title: "48MP Pro Fusion",  body: "A redesigned triple-lens system with 5× telephoto and ProRES video at 4K 120fps." },
  { icon: "battery-charging", title: "29-hour battery",  body: "The longest battery life ever on iPhone, with 45W fast charge and refined MagSafe." },
  { icon: "shield-check",title: "Aerospace titanium",body: "Grade-5 titanium frame, IP68 rated, and Ceramic Shield 3 — the toughest glass on any phone." },
];

const SPECS = [
  ["Display",   "6.3″ Super Retina XDR · ProMotion 1–120Hz · 2,000 nits peak"],
  ["Chip",      "Apple A19 Pro · 6-core CPU · 6-core GPU · 16-core Neural Engine"],
  ["Camera",    "48MP Main · 48MP Ultra Wide · 48MP 5× Telephoto · 12MP TrueDepth"],
  ["Video",     "4K Dolby Vision up to 120fps · ProRES · Cinematic mode"],
  ["Storage",   "256 GB / 512 GB / 1 TB"],
  ["Battery",   "Up to 29 hours video · 45W wired · 25W MagSafe"],
  ["Connectivity", "5G · Wi-Fi 7 · Bluetooth 5.4 · Thread · USB-C (USB 3)"],
  ["Materials", "Aerospace-grade Titanium · Ceramic Shield 3"],
  ["Water resistance", "IP68 — 6m for 30 minutes"],
  ["Weight",    "199 g"],
  ["In the box","iPhone 17 Pro · USB-C Cable (1m) · Documentation"],
];

const FAQ_ITEMS = [
  { q: "Is the iPhone 17 Pro unlocked?", a: "Yes. Every iPhone 17 Pro purchased here ships SIM-free and works with any carrier. eSIM is supported on all U.S. models." },
  { q: "What's your trade-in value?", a: "We accept trade-ins on iPhone 12 and later, plus most flagship Android devices. Get an instant quote at checkout — credit is applied directly to your order." },
  { q: "When will my order ship?", a: "In-stock colors and configurations ship within 1 business day via free 2-day delivery. Custom engravings add 1–2 days." },
  { q: "What's the return policy?", a: "Free returns within 30 days. The device must be in original condition with all accessories. Refunds are issued to the original payment method within 5 business days." },
  { q: "Do you offer financing?", a: "Yes — 0% APR for 24 months on approved credit. AppleCare+ can be bundled into the monthly payment." },
  { q: "Is AppleCare+ included?", a: "AppleCare+ is optional. Add it at checkout for $9.99/mo or $199 upfront. Coverage starts the day your iPhone is delivered." },
];

const RELATED = [
  { name: "AirPods Pro 3",         price: 249,  badge: "New",      g1: "#FFFFFF", g2: "#E8E8EA", icon: "headphones" },
  { name: "MagSafe Charger",       price: 49,   badge: null,        g1: "#F5F5F7", g2: "#D1D1D6", icon: "battery-charging" },
  { name: "FineWoven Case",        price: 59,   badge: null,        g1: "#3F4E5E", g2: "#2A3744", icon: "shield" },
  { name: "AppleCare+ for iPhone", price: 199,  badge: "Recommended", g1: "#01824C", g2: "#004C3F", icon: "shield-check" },
];

// ────────────────────────────────────────────────────────────────────────────
// Device illustration — clean SVG card for product imagery
// ────────────────────────────────────────────────────────────────────────────
const PhoneRender = ({ colorway, view = "front", style }) => {
  const c = colorway;
  return (
    <svg viewBox="0 0 600 720" style={{ width: "100%", height: "100%", display: "block", ...style }}>
      <defs>
        <linearGradient id={`bg-${c.id}-${view}`} x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stopColor={c.g1} stopOpacity="0.45" />
          <stop offset="100%" stopColor={c.g2} stopOpacity="0.25" />
        </linearGradient>
        <linearGradient id={`body-${c.id}-${view}`} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor={c.g1} />
          <stop offset="50%" stopColor={c.hex} />
          <stop offset="100%" stopColor={c.g2} />
        </linearGradient>
        <linearGradient id={`screen-${c.id}-${view}`} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor="#0a0a0c" />
          <stop offset="100%" stopColor="#1a1a20" />
        </linearGradient>
        <radialGradient id={`lens-${c.id}-${view}`} cx="0.5" cy="0.4">
          <stop offset="0%" stopColor="#3a3a44" />
          <stop offset="60%" stopColor="#0a0a0c" />
          <stop offset="100%" stopColor="#000" />
        </radialGradient>
      </defs>
      <rect width="600" height="720" fill={`url(#bg-${c.id}-${view})`} />
      {view === "front" && (
        <g transform="translate(180, 80)">
          {/* Phone body */}
          <rect x="0" y="0" width="240" height="520" rx="42" fill={`url(#body-${c.id}-${view})`} stroke="rgba(0,0,0,0.18)" strokeWidth="1" />
          <rect x="6" y="6" width="228" height="508" rx="38" fill="#0a0a0c" />
          {/* Screen with subtle hint of UI */}
          <rect x="14" y="14" width="212" height="492" rx="32" fill={`url(#screen-${c.id}-${view})`} />
          {/* Dynamic island */}
          <rect x="92" y="34" width="56" height="20" rx="10" fill="#000" />
          {/* Wallpaper hint — subtle radial */}
          <circle cx="120" cy="280" r="80" fill={c.hex} opacity="0.12" />
          <circle cx="120" cy="280" r="40" fill={c.hex} opacity="0.18" />
          {/* Time */}
          <text x="120" y="120" textAnchor="middle" fill="#fff" fontFamily="Geist, sans-serif" fontWeight="600" fontSize="14" opacity="0.85">9:41</text>
        </g>
      )}
      {view === "back" && (
        <g transform="translate(180, 80)">
          <rect x="0" y="0" width="240" height="520" rx="42" fill={`url(#body-${c.id}-${view})`} stroke="rgba(0,0,0,0.2)" strokeWidth="1" />
          {/* Apple logo */}
          <circle cx="120" cy="260" r="28" fill="rgba(255,255,255,0.18)" />
          {/* Camera plateau — pill running across top */}
          <rect x="28" y="40" width="184" height="120" rx="24" fill={c.g2} stroke="rgba(0,0,0,0.18)" strokeWidth="1" />
          {/* Lenses */}
          <circle cx="68" cy="100" r="22" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="2" />
          <circle cx="120" cy="100" r="22" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="2" />
          <circle cx="172" cy="100" r="22" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="2" />
          <circle cx="68" cy="100" r="6" fill="#1a1a20" />
          <circle cx="120" cy="100" r="6" fill="#1a1a20" />
          <circle cx="172" cy="100" r="6" fill="#1a1a20" />
          {/* LiDAR + flash */}
          <circle cx="200" cy="60" r="6" fill="#3a3a44" />
          <circle cx="40"  cy="60" r="6" fill="#fafafa" opacity="0.85" />
        </g>
      )}
      {view === "side" && (
        <g transform="translate(260, 60)">
          <rect x="0" y="0" width="80" height="560" rx="22" fill={`url(#body-${c.id}-${view})`} stroke="rgba(0,0,0,0.18)" strokeWidth="1" />
          <rect x="8" y="14" width="64" height="540" rx="14" fill="#0a0a0c" />
          {/* Buttons */}
          <rect x="-3" y="120" width="6" height="42" rx="2" fill={c.g2} />
          <rect x="-3" y="180" width="6" height="64" rx="2" fill={c.g2} />
          <rect x="-3" y="260" width="6" height="64" rx="2" fill={c.g2} />
          <rect x="77" y="160" width="6" height="80" rx="2" fill={c.g2} />
        </g>
      )}
      {view === "detail" && (
        <g transform="translate(120, 100)">
          {/* Zoomed camera plateau */}
          <rect x="0" y="0" width="360" height="360" rx="48" fill={c.g2} stroke="rgba(0,0,0,0.2)" strokeWidth="1" />
          <circle cx="100" cy="170" r="58" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="3" />
          <circle cx="260" cy="170" r="58" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="3" />
          <circle cx="180" cy="280" r="58" fill={`url(#lens-${c.id}-${view})`} stroke="#000" strokeWidth="3" />
          <circle cx="100" cy="170" r="18" fill="#1a1a20" />
          <circle cx="260" cy="170" r="18" fill="#1a1a20" />
          <circle cx="180" cy="280" r="18" fill="#1a1a20" />
          <circle cx="100" cy="170" r="6" fill="#3a3a44" opacity="0.8" />
          <circle cx="260" cy="170" r="6" fill="#3a3a44" opacity="0.8" />
        </g>
      )}
    </svg>
  );
};

window.PhoneRender = PhoneRender;
window.COLORWAYS = COLORWAYS;
window.STORAGE = STORAGE;
window.HIGHLIGHTS = HIGHLIGHTS;
window.SPECS = SPECS;
window.FAQ_ITEMS = FAQ_ITEMS;
window.RELATED = RELATED;
window.BlockTag = BlockTag;
window.Icon = Icon;
