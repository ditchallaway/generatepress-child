// Main product page sections — composed in App.

const { useState: useS, useEffect: useE, useRef: useR } = React;

// ────────────────────────────────────────────────────────────────────────────
// Header (lightweight site chrome)
// ────────────────────────────────────────────────────────────────────────────
const StoreHeader = ({ showAnnotations }) => (
  <header style={{
    position: "sticky", top: 0, zIndex: 40,
    background: "rgba(255,255,255,0.92)",
    borderBottom: `1px solid ${ppBorder}`,
    backdropFilter: "saturate(180%) blur(8px)",
  }}>
    <BlockTag blocks={["core/template-part: Header"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{
      maxWidth: 1240, margin: "0 auto", padding: "0 32px", height: 72,
      display: "flex", alignItems: "center", justifyContent: "space-between",
    }}>
      <div style={{ display: "flex", alignItems: "center", gap: 40 }}>
        <img src="assets/logo-full-green.svg" alt="SureCart" style={{ height: 26 }} />
        <nav style={{ display: "flex", gap: 28 }}>
          {["Shop", "iPhone", "Mac", "Accessories", "Support"].map(l => (
            <a key={l} href="#" style={{ color: ppFg2, fontWeight: 600, fontSize: 14, textDecoration: "none" }}>{l}</a>
          ))}
        </nav>
      </div>
      <div style={{ display: "flex", gap: 4, alignItems: "center" }}>
        <button style={iconBtn}><Icon name="search" size={18} /></button>
        <button style={iconBtn}><Icon name="user" size={18} /></button>
        <button style={{ ...iconBtn, position: "relative" }}>
          <Icon name="shopping-bag" size={18} />
          <span style={{
            position: "absolute", top: 6, right: 6,
            background: ppGreen, color: "#fff",
            fontFamily: "var(--font-mono)", fontSize: 9, fontWeight: 700,
            minWidth: 16, height: 16, borderRadius: 9999,
            display: "flex", alignItems: "center", justifyContent: "center",
            padding: "0 4px",
          }}>2</span>
        </button>
      </div>
    </div>
  </header>
);

const iconBtn = {
  width: 40, height: 40, borderRadius: 9999,
  display: "flex", alignItems: "center", justifyContent: "center",
  background: "transparent", border: "none", cursor: "pointer",
  color: ppFg1,
};

// ────────────────────────────────────────────────────────────────────────────
// Breadcrumbs
// ────────────────────────────────────────────────────────────────────────────
const Breadcrumbs = ({ showAnnotations }) => (
  <div style={{ position: "relative", borderBottom: `1px solid ${ppBorder}`, background: "#fff" }}>
    <BlockTag blocks={["core/group", "core/breadcrumbs"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 1240, margin: "0 auto", padding: "16px 32px", display: "flex", alignItems: "center", gap: 8, fontSize: 13, color: ppFg3 }}>
      <a href="#" style={{ color: ppFg3, textDecoration: "none" }}>Shop</a>
      <Icon name="chevron-right" size={12} />
      <a href="#" style={{ color: ppFg3, textDecoration: "none" }}>iPhone</a>
      <Icon name="chevron-right" size={12} />
      <span style={{ color: ppFg1, fontWeight: 600 }}>iPhone 17 Pro</span>
    </div>
  </div>
);

// ────────────────────────────────────────────────────────────────────────────
// HERO — gallery + product info + buy
// ────────────────────────────────────────────────────────────────────────────
const Hero = ({ state, set, showAnnotations }) => {
  const { colorIdx, storageIdx, qty, view } = state;
  const color = COLORWAYS[colorIdx];
  const storage = STORAGE[storageIdx];
  const basePrice = 1099;
  const price = basePrice + storage.priceDelta;

  const views = ["front", "back", "detail", "side"];

  return (
    <section style={{ position: "relative", background: "#fff", padding: "48px 0 96px" }}>
      <BlockTag blocks={["surecart/product-page", "core/columns"]} position="top-right" showAnnotations={showAnnotations} />
      <div style={{ maxWidth: 1240, margin: "0 auto", padding: "0 32px", display: "grid", gridTemplateColumns: "1fr 1fr", gap: 64 }}>
        {/* GALLERY */}
        <div style={{ position: "relative" }}>
          <BlockTag blocks={["surecart/product-media"]} position="top-left" showAnnotations={showAnnotations} />
          <div style={{
            background: ppBgAlt, borderRadius: 24, aspectRatio: "1 / 1",
            position: "relative", overflow: "hidden",
            border: `1px solid ${ppBorder}`,
          }}>
            <PhoneRender colorway={color} view={view} />
            {/* Color tag overlay */}
            <div style={{
              position: "absolute", top: 20, left: 20,
              padding: "6px 12px", borderRadius: 9999,
              background: "rgba(255,255,255,0.92)", border: `1px solid ${ppBorder}`,
              fontSize: 12, fontWeight: 600, color: ppFg1, whiteSpace: "nowrap",
              backdropFilter: "blur(8px)",
            }}>{color.name}</div>
            {/* Wishlist */}
            <button style={{
              position: "absolute", top: 20, right: 20,
              width: 40, height: 40, borderRadius: 9999,
              background: "rgba(255,255,255,0.92)", border: `1px solid ${ppBorder}`,
              display: "flex", alignItems: "center", justifyContent: "center",
              cursor: "pointer", color: ppFg2,
            }}>
              <Icon name="heart" size={18} />
            </button>
          </div>
          {/* Thumbnails */}
          <div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: 12, marginTop: 16 }}>
            {views.map(v => (
              <button key={v} onClick={() => set({ view: v })} style={{
                background: ppBgAlt, borderRadius: 12, aspectRatio: "1 / 1",
                border: `2px solid ${view === v ? ppGreen : "transparent"}`,
                padding: 0, cursor: "pointer", overflow: "hidden",
                transition: "border-color var(--dur) var(--ease)",
              }}>
                <PhoneRender colorway={color} view={v} />
              </button>
            ))}
          </div>
        </div>

        {/* INFO */}
        <div style={{ position: "relative", paddingTop: 8 }}>
          {/* Eyebrow */}
          <div style={{ display: "flex", alignItems: "center", gap: 12, marginBottom: 16 }}>
            <span style={{ display: "inline-flex", alignItems: "center", gap: 6, padding: "4px 10px", borderRadius: 9999, background: "var(--brand-soft)", color: ppGreen, fontSize: 12, fontWeight: 700, letterSpacing: "0.04em", textTransform: "uppercase" }}>
              <span style={{ width: 5, height: 5, borderRadius: 9999, background: ppGreen }} />
              In Stock
            </span>
            <span style={{ fontSize: 13, color: ppFg3 }}>SKU IP17P-{color.id.toUpperCase()}-{storage.id.toUpperCase()}</span>
          </div>

          {/* Title */}
          <div style={{ position: "relative" }}>
            <BlockTag blocks={["surecart/product-title"]} position="top-right" showAnnotations={showAnnotations} />
            <h1 className="sc-display-2" style={{ fontSize: 56, marginBottom: 12, color: ppFg1 }}>iPhone 17 Pro</h1>
          </div>
          <p className="sc-lead" style={{ color: ppFg2, marginBottom: 24, maxWidth: 480 }}>
            Aerospace titanium. The A19 Pro chip. A camera system rebuilt around how you actually shoot. The most advanced iPhone ever made.
          </p>

          {/* Rating */}
          <div style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: 28, paddingBottom: 28, borderBottom: `1px solid ${ppBorder}` }}>
            <div style={{ display: "flex", gap: 1, color: "#F59E0B" }}>
              {[1,2,3,4,5].map(i => <Icon key={i} name="star" size={16} stroke={2} />)}
            </div>
            <span style={{ fontSize: 14, fontWeight: 600, color: ppFg1 }}>4.9</span>
            <span style={{ fontSize: 14, color: ppFg3 }}>· 2,847 reviews</span>
          </div>

          {/* Price */}
          <div style={{ position: "relative", marginBottom: 32 }}>
            <BlockTag blocks={["surecart/product-price"]} position="top-right" showAnnotations={showAnnotations} />
            <div style={{ display: "flex", alignItems: "baseline", gap: 12 }}>
              <span style={{ fontFamily: "var(--font-display)", fontWeight: 700, fontSize: 44, letterSpacing: "-0.03em", color: ppFg1 }}>
                ${price.toLocaleString()}
              </span>
              <span style={{ fontSize: 16, color: ppFg3 }}>or ${(price/24).toFixed(2)}/mo for 24 mo. at 0% APR</span>
            </div>
          </div>

          {/* Variant: Color */}
          <div style={{ position: "relative", marginBottom: 28 }}>
            <BlockTag blocks={["surecart/variant-picker", "(option: Color)"]} position="top-right" showAnnotations={showAnnotations} />
            <div style={{ display: "flex", justifyContent: "space-between", alignItems: "baseline", marginBottom: 12 }}>
              <span style={{ fontSize: 13, fontWeight: 600, color: ppFg1, letterSpacing: "0.04em", textTransform: "uppercase" }}>Color</span>
              <span style={{ fontSize: 14, color: ppFg2 }}>{color.name}</span>
            </div>
            <div style={{ display: "flex", gap: 10 }}>
              {COLORWAYS.map((c, i) => (
                <button key={c.id} onClick={() => set({ colorIdx: i })} style={{
                  width: 44, height: 44, borderRadius: 9999, padding: 3,
                  background: "transparent",
                  border: `2px solid ${i === colorIdx ? ppGreen : "transparent"}`,
                  cursor: "pointer", transition: "border-color var(--dur) var(--ease)",
                }} aria-label={c.name}>
                  <span style={{ display: "block", width: "100%", height: "100%", borderRadius: 9999, background: `linear-gradient(135deg, ${c.g1}, ${c.hex} 50%, ${c.g2})`, boxShadow: "inset 0 0 0 1px rgba(0,0,0,0.15)" }} />
                </button>
              ))}
            </div>
          </div>

          {/* Variant: Storage */}
          <div style={{ position: "relative", marginBottom: 28 }}>
            <BlockTag blocks={["surecart/variant-picker", "(option: Storage)"]} position="top-right" showAnnotations={showAnnotations} />
            <div style={{ display: "flex", justifyContent: "space-between", alignItems: "baseline", marginBottom: 12 }}>
              <span style={{ fontSize: 13, fontWeight: 600, color: ppFg1, letterSpacing: "0.04em", textTransform: "uppercase" }}>Storage</span>
              <a href="#" style={{ fontSize: 14, color: ppGreen, fontWeight: 600, textDecoration: "none" }}>How much do I need?</a>
            </div>
            <div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 8 }}>
              {STORAGE.map((s, i) => (
                <button key={s.id} onClick={() => set({ storageIdx: i })} style={{
                  padding: "16px 12px", borderRadius: 12,
                  border: `2px solid ${i === storageIdx ? ppGreen : ppBorder}`,
                  background: i === storageIdx ? "var(--brand-soft)" : "#fff",
                  cursor: "pointer", textAlign: "center",
                  transition: "all var(--dur) var(--ease)",
                }}>
                  <div style={{ fontFamily: "var(--font-display)", fontWeight: 700, fontSize: 16, color: ppFg1 }}>{s.label}</div>
                  <div style={{ fontSize: 12, color: ppFg3, marginTop: 2 }}>
                    {s.priceDelta === 0 ? "Included" : `+$${s.priceDelta}`}
                  </div>
                </button>
              ))}
            </div>
          </div>

          {/* Quantity + Buy */}
          <div style={{ position: "relative", display: "flex", gap: 12, marginBottom: 20 }}>
            <BlockTag blocks={["surecart/quantity", "surecart/add-to-cart-button"]} position="top-right" showAnnotations={showAnnotations} />
            <div style={{
              display: "flex", alignItems: "center",
              border: `1px solid ${ppBorder}`, borderRadius: 9999,
              overflow: "hidden", background: "#fff",
            }}>
              <button onClick={() => set({ qty: Math.max(1, qty - 1) })} style={{ width: 48, height: 56, border: "none", background: "transparent", cursor: "pointer", color: ppFg1 }}>
                <Icon name="minus" size={16} />
              </button>
              <span style={{ width: 32, textAlign: "center", fontFamily: "var(--font-display)", fontWeight: 600, fontSize: 16, color: ppFg1 }}>{qty}</span>
              <button onClick={() => set({ qty: qty + 1 })} style={{ width: 48, height: 56, border: "none", background: "transparent", cursor: "pointer", color: ppFg1 }}>
                <Icon name="plus" size={16} />
              </button>
            </div>
            <button onClick={() => set({ added: true })} style={{
              flex: 1,
              background: ppGreen, color: "#fff",
              border: "none", borderRadius: 9999,
              fontFamily: "var(--font-body)", fontWeight: 700, fontSize: 16,
              padding: "0 32px", height: 56,
              cursor: "pointer", display: "inline-flex", alignItems: "center", justifyContent: "center", gap: 10,
              boxShadow: "var(--shadow-xs)",
              transition: "background var(--dur) var(--ease)",
            }}
            onMouseEnter={e => e.currentTarget.style.background = "var(--sc-green-700)"}
            onMouseLeave={e => e.currentTarget.style.background = ppGreen}
            >
              <Icon name="shopping-bag" size={18} stroke={2} />
              {state.added ? "Added to Bag" : `Add to Bag — $${(price * qty).toLocaleString()}`}
            </button>
          </div>

          {/* Secondary CTA — Buy Now (SureCart Buy Now block, skips cart → checkout) */}
          <div style={{ position: "relative", marginBottom: 28 }}>
            <BlockTag blocks={["surecart/buy-now-button"]} position="top-right" showAnnotations={showAnnotations} />
            <button style={{
              width: "100%", background: ppDeep, color: "#fff",
              border: "none", borderRadius: 9999,
              fontFamily: "var(--font-body)", fontWeight: 700, fontSize: 16,
              padding: "0 32px", height: 56,
              cursor: "pointer", display: "inline-flex", alignItems: "center", justifyContent: "center", gap: 10,
              transition: "background var(--dur) var(--ease)",
            }}
            onMouseEnter={e => e.currentTarget.style.background = "#0a3a30"}
            onMouseLeave={e => e.currentTarget.style.background = ppDeep}
            >
              <Icon name="zap" size={18} stroke={2} />
              Buy Now — Express Checkout
            </button>
          </div>

          {/* Trust strip */}
          <div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 16, padding: "20px 0", borderTop: `1px solid ${ppBorder}` }}>
            {[
              { icon: "truck",        label: "Free 2-day shipping" },
              { icon: "rotate-ccw",   label: "30-day free returns" },
              { icon: "shield-check", label: "AppleCare+ available" },
            ].map(t => (
              <div key={t.label} style={{ display: "flex", alignItems: "center", gap: 8, fontSize: 13, color: ppFg2 }}>
                <span style={{ color: ppGreen }}><Icon name={t.icon} size={18} /></span>
                <span style={{ fontWeight: 500 }}>{t.label}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

window.StoreHeader = StoreHeader;
window.Breadcrumbs = Breadcrumbs;
window.Hero = Hero;
