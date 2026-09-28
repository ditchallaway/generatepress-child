// Remaining product page sections — highlights, description, specs, FAQ, related, CTA, footer.

// ────────────────────────────────────────────────────────────────────────────
// Feature highlights — 4-up icon grid
// ────────────────────────────────────────────────────────────────────────────
const Highlights = ({ showAnnotations }) => (
  <section style={{ position: "relative", background: ppBgAlt, padding: "96px 0", borderTop: `1px solid ${ppBorder}` }}>
    <BlockTag blocks={["core/group", "core/columns", "core/heading", "core/paragraph"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 1240, margin: "0 auto", padding: "0 32px" }}>
      <div style={{ maxWidth: 720, marginBottom: 64 }}>
        <div className="sc-eyebrow" style={{ marginBottom: 14 }}>Why iPhone 17 Pro</div>
        <h2 className="sc-h1" style={{ fontSize: 48, color: ppFg1, marginBottom: 16 }}>
          Built for what's next.<br/>And what's after that.
        </h2>
        <p className="sc-lead" style={{ color: ppFg2 }}>
          Four upgrades that change everything you do with your phone — from the photos you frame to how long the battery lasts on a long day.
        </p>
      </div>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: 20 }}>
        {HIGHLIGHTS.map(h => (
          <div key={h.title} style={{
            background: "#fff", border: `1px solid ${ppBorder}`,
            borderRadius: 16, padding: 28,
            display: "flex", flexDirection: "column", gap: 16,
            transition: "transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease)",
          }}
          onMouseEnter={e => { e.currentTarget.style.transform = "translateY(-2px)"; e.currentTarget.style.boxShadow = "var(--shadow-md)"; }}
          onMouseLeave={e => { e.currentTarget.style.transform = ""; e.currentTarget.style.boxShadow = ""; }}
          >
            <div style={{ width: 44, height: 44, borderRadius: 12, background: "var(--brand-soft)", color: ppGreen, display: "flex", alignItems: "center", justifyContent: "center" }}>
              <Icon name={h.icon} size={22} />
            </div>
            <h3 className="sc-h4" style={{ color: ppFg1, fontSize: 18 }}>{h.title}</h3>
            <p className="sc-small" style={{ color: ppFg2, fontSize: 14, lineHeight: "22px" }}>{h.body}</p>
          </div>
        ))}
      </div>
    </div>
  </section>
);

// ────────────────────────────────────────────────────────────────────────────
// Detailed description — alternating media+text
// ────────────────────────────────────────────────────────────────────────────
const DescriptionBlocks = ({ state, showAnnotations }) => {
  const color = COLORWAYS[state.colorIdx];
  const blocks = [
    {
      eyebrow: "Titanium Design",
      title: "Lighter. Stronger. Pro-grade.",
      body: "Aerospace-grade titanium gives iPhone 17 Pro the strongest enclosure ever on a phone — yet 19% lighter than its predecessor. The matte-brushed finish resists fingerprints and looks better with use, not worse.",
      bullets: ["Grade-5 titanium frame", "Ceramic Shield 3 front", "IP68 — 6m for 30 min"],
      view: "side",
    },
    {
      eyebrow: "A19 Pro Bionic",
      title: "The most powerful chip in any phone.",
      body: "A new 3nm architecture, 6-core GPU with hardware-accelerated ray tracing, and a 16-core Neural Engine that runs on-device AI privately. It's a console in your pocket, and a studio for editing 4K video on the go.",
      bullets: ["3nm process technology", "Hardware ray-tracing", "16-core Neural Engine"],
      view: "back",
      reverse: true,
    },
    {
      eyebrow: "Pro Camera System",
      title: "A camera you'll want to use.",
      body: "Three 48MP sensors — main, ultra-wide, and a true 5× telephoto — capture every focal length without compromise. Computational photography is now invisible: no more waiting, no more blur, no more washed-out skies.",
      bullets: ["5× telephoto, 120mm equivalent", "ProRES 4K at 120fps", "Night mode, every lens"],
      view: "detail",
    },
  ];

  return (
    <section style={{ background: "#fff" }}>
      <BlockTag blocks={["core/group", "core/media-text (×3)"]} position="top-right" showAnnotations={showAnnotations} />
      {blocks.map((b, i) => (
        <div key={i} style={{
          maxWidth: 1240, margin: "0 auto", padding: "96px 32px",
          display: "grid", gridTemplateColumns: "1fr 1fr", gap: 80, alignItems: "center",
          direction: b.reverse ? "rtl" : "ltr",
        }}>
          <div style={{ direction: "ltr" }}>
            <div className="sc-eyebrow" style={{ marginBottom: 14 }}>{b.eyebrow}</div>
            <h2 className="sc-h1" style={{ fontSize: 40, color: ppFg1, marginBottom: 20, textWrap: "balance" }}>{b.title}</h2>
            <p className="sc-lead" style={{ color: ppFg2, marginBottom: 28 }}>{b.body}</p>
            <ul style={{ listStyle: "none", padding: 0, margin: 0, display: "flex", flexDirection: "column", gap: 12 }}>
              {b.bullets.map(bl => (
                <li key={bl} style={{ display: "flex", alignItems: "center", gap: 12, fontSize: 15, color: ppFg1, fontWeight: 500 }}>
                  <span style={{ width: 24, height: 24, borderRadius: 9999, background: "var(--brand-soft)", color: ppGreen, display: "inline-flex", alignItems: "center", justifyContent: "center", flexShrink: 0 }}>
                    <Icon name="check" size={14} stroke={2.5} />
                  </span>
                  {bl}
                </li>
              ))}
            </ul>
          </div>
          <div style={{ direction: "ltr", aspectRatio: "1 / 1", borderRadius: 24, overflow: "hidden", background: ppBgAlt, border: `1px solid ${ppBorder}` }}>
            <PhoneRender colorway={color} view={b.view} />
          </div>
        </div>
      ))}
    </section>
  );
};

// ────────────────────────────────────────────────────────────────────────────
// Specs table
// ────────────────────────────────────────────────────────────────────────────
const Specs = ({ showAnnotations }) => (
  <section style={{ position: "relative", background: ppBgAlt, padding: "96px 0", borderTop: `1px solid ${ppBorder}` }}>
    <BlockTag blocks={["core/group", "core/table"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 1040, margin: "0 auto", padding: "0 32px" }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 48, gap: 32, flexWrap: "wrap" }}>
        <div>
          <div className="sc-eyebrow" style={{ marginBottom: 14 }}>Tech Specs</div>
          <h2 className="sc-h1" style={{ fontSize: 40, color: ppFg1 }}>Everything inside.</h2>
        </div>
        <a href="#" style={{ display: "inline-flex", alignItems: "center", gap: 6, color: ppGreen, fontWeight: 700, fontSize: 14, textDecoration: "none" }}>
          Download full spec sheet <Icon name="download" size={14} />
        </a>
      </div>
      <div style={{ background: "#fff", borderRadius: 16, border: `1px solid ${ppBorder}`, overflow: "hidden" }}>
        <table style={{ width: "100%", borderCollapse: "collapse" }}>
          <tbody>
            {SPECS.map(([k, v], i) => (
              <tr key={k} style={{ borderTop: i === 0 ? "none" : `1px solid ${ppBorder}` }}>
                <td style={{ padding: "20px 28px", fontSize: 13, fontWeight: 600, color: ppFg3, letterSpacing: "0.04em", textTransform: "uppercase", verticalAlign: "top", width: 200 }}>{k}</td>
                <td style={{ padding: "20px 28px", fontSize: 15, color: ppFg1, fontWeight: 500, lineHeight: 1.5 }}>{v}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  </section>
);

// ────────────────────────────────────────────────────────────────────────────
// FAQ accordion
// ────────────────────────────────────────────────────────────────────────────
const FAQ = ({ showAnnotations }) => {
  const [open, setOpen] = React.useState(0);
  return (
    <section style={{ position: "relative", background: "#fff", padding: "96px 0" }}>
      <BlockTag blocks={["core/group", "core/details (×N)"]} position="top-right" showAnnotations={showAnnotations} />
      <div style={{ maxWidth: 880, margin: "0 auto", padding: "0 32px" }}>
        <div style={{ textAlign: "center", marginBottom: 56 }}>
          <div className="sc-eyebrow" style={{ marginBottom: 14 }}>FAQ</div>
          <h2 className="sc-h1" style={{ fontSize: 44, color: ppFg1, marginBottom: 16 }}>Questions, answered.</h2>
          <p className="sc-lead" style={{ color: ppFg2 }}>Everything you'd want to know before you order — and a few things we'd want to know too.</p>
        </div>
        <div style={{ borderTop: `1px solid ${ppBorder}` }}>
          {FAQ_ITEMS.map((it, i) => {
            const isOpen = open === i;
            return (
              <div key={i} style={{ borderBottom: `1px solid ${ppBorder}` }}>
                <button onClick={() => setOpen(isOpen ? -1 : i)} style={{
                  width: "100%", display: "flex", justifyContent: "space-between", alignItems: "center", gap: 16,
                  padding: "22px 0", background: "transparent", border: "none", cursor: "pointer", textAlign: "left",
                  fontFamily: "var(--font-display)", fontWeight: 600, fontSize: 18, color: ppFg1,
                }}>
                  {it.q}
                  <span style={{
                    width: 32, height: 32, borderRadius: 9999,
                    background: isOpen ? ppGreen : ppBgAlt, color: isOpen ? "#fff" : ppFg2,
                    display: "flex", alignItems: "center", justifyContent: "center", flexShrink: 0,
                    transform: `rotate(${isOpen ? 45 : 0}deg)`,
                    transition: "transform var(--dur) var(--ease), background var(--dur) var(--ease), color var(--dur) var(--ease)",
                  }}>
                    <Icon name="plus" size={16} stroke={2} />
                  </span>
                </button>
                <div style={{
                  maxHeight: isOpen ? 200 : 0, overflow: "hidden",
                  transition: "max-height var(--dur-slow) var(--ease)",
                }}>
                  <p className="sc-body" style={{ color: ppFg2, paddingBottom: 24, paddingRight: 56, margin: 0 }}>{it.a}</p>
                </div>
              </div>
            );
          })}
        </div>
        <div style={{ textAlign: "center", marginTop: 48, padding: 32, background: ppBgAlt, borderRadius: 16 }}>
          <div style={{ fontFamily: "var(--font-display)", fontWeight: 600, fontSize: 18, color: ppFg1, marginBottom: 6 }}>Still have questions?</div>
          <p className="sc-small" style={{ color: ppFg2, marginBottom: 16 }}>Our support team responds in under an hour, every day.</p>
          <a href="#" style={{ display: "inline-flex", alignItems: "center", gap: 8, padding: "10px 20px", background: "#fff", border: `1px solid ${ppBorder}`, borderRadius: 9999, color: ppFg1, fontSize: 14, fontWeight: 700, textDecoration: "none" }}>
            <Icon name="message-circle" size={16} /> Start a chat
          </a>
        </div>
      </div>
    </section>
  );
};

// ────────────────────────────────────────────────────────────────────────────
// Related products / upsell
// ────────────────────────────────────────────────────────────────────────────
const Related = ({ showAnnotations }) => (
  <section style={{ position: "relative", background: ppBgAlt, padding: "96px 0", borderTop: `1px solid ${ppBorder}` }}>
    <BlockTag blocks={["surecart/product-list", "core/columns"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 1240, margin: "0 auto", padding: "0 32px" }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-end", marginBottom: 40, gap: 32, flexWrap: "wrap" }}>
        <div>
          <div className="sc-eyebrow" style={{ marginBottom: 14 }}>Complete the kit</div>
          <h2 className="sc-h1" style={{ fontSize: 40, color: ppFg1 }}>Made for iPhone 17 Pro.</h2>
        </div>
        <a href="#" style={{ display: "inline-flex", alignItems: "center", gap: 6, color: ppGreen, fontWeight: 700, fontSize: 14, textDecoration: "none" }}>
          Shop all accessories <Icon name="arrow-right" size={14} />
        </a>
      </div>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: 20 }}>
        {RELATED.map(r => (
          <div key={r.name} style={{
            background: "#fff", border: `1px solid ${ppBorder}`, borderRadius: 16,
            padding: 20, display: "flex", flexDirection: "column", gap: 14,
            cursor: "pointer", transition: "transform var(--dur) var(--ease), box-shadow var(--dur) var(--ease)",
          }}
          onMouseEnter={e => { e.currentTarget.style.transform = "translateY(-2px)"; e.currentTarget.style.boxShadow = "var(--shadow-md)"; }}
          onMouseLeave={e => { e.currentTarget.style.transform = ""; e.currentTarget.style.boxShadow = ""; }}
          >
            <div style={{
              aspectRatio: "1 / 1", borderRadius: 12, position: "relative", overflow: "hidden",
              background: `linear-gradient(135deg, ${r.g1}, ${r.g2})`,
              display: "flex", alignItems: "center", justifyContent: "center",
              color: r.g1 === "#01824C" || r.g1 === "#3F4E5E" ? "#fff" : ppFg1,
            }}>
              <Icon name={r.icon} size={64} stroke={1.2} />
              {r.badge && (
                <span style={{ position: "absolute", top: 12, left: 12, padding: "4px 10px", borderRadius: 9999, background: "rgba(255,255,255,0.92)", color: ppFg1, fontSize: 11, fontWeight: 700, letterSpacing: "0.04em", textTransform: "uppercase" }}>{r.badge}</span>
              )}
            </div>
            <div style={{ display: "flex", flexDirection: "column", gap: 4, flex: 1 }}>
              <div style={{ fontFamily: "var(--font-display)", fontWeight: 600, fontSize: 16, color: ppFg1 }}>{r.name}</div>
              <div style={{ fontSize: 14, color: ppFg3 }}>From ${r.price}</div>
            </div>
            <button style={{
              width: "100%", padding: "10px 16px", borderRadius: 9999,
              background: "#fff", border: `1px solid ${ppBorder}`,
              fontWeight: 700, fontSize: 13, color: ppFg1, cursor: "pointer",
              display: "inline-flex", alignItems: "center", justifyContent: "center", gap: 6,
            }}>
              <Icon name="plus" size={14} stroke={2} /> Add
            </button>
          </div>
        ))}
      </div>
    </div>
  </section>
);

// ────────────────────────────────────────────────────────────────────────────
// Final CTA strip
// ────────────────────────────────────────────────────────────────────────────
const FinalCTA = ({ showAnnotations }) => (
  <section style={{ position: "relative", background: ppDeep, color: "#fff", padding: "96px 0" }}>
    <BlockTag blocks={["core/cover", "core/heading", "core/buttons"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 880, margin: "0 auto", padding: "0 32px", textAlign: "center" }}>
      <div className="sc-eyebrow-mono" style={{ color: "var(--sc-neon)", marginBottom: 16 }}>Ready when you are</div>
      <h2 className="sc-h1" style={{ color: "#fff", fontSize: 56, marginBottom: 20, textWrap: "balance" }}>
        Make iPhone 17 Pro yours today.
      </h2>
      <p className="sc-lead" style={{ color: "rgba(255,255,255,0.78)", marginBottom: 32, maxWidth: 560, margin: "0 auto 32px" }}>
        Free 2-day shipping. 30-day returns, no questions. AppleCare+ available at checkout.
      </p>
      <div style={{ display: "flex", justifyContent: "center", gap: 12, flexWrap: "wrap" }}>
        <a href="#" style={{
          display: "inline-flex", alignItems: "center", gap: 8,
          padding: "16px 32px", borderRadius: 9999,
          background: "#fff", color: ppDeep,
          fontWeight: 700, fontSize: 16, textDecoration: "none",
          boxShadow: "var(--shadow-sm)",
        }}>
          <Icon name="shopping-bag" size={18} stroke={2} /> Add to Bag — From $1,099
        </a>
        <a href="#" style={{
          display: "inline-flex", alignItems: "center", gap: 8,
          padding: "16px 32px", borderRadius: 9999,
          background: "transparent", color: "#fff",
          fontWeight: 700, fontSize: 16, textDecoration: "none",
          border: "1px solid rgba(255,255,255,0.25)",
        }}>
          Compare iPhone models
        </a>
      </div>
    </div>
  </section>
);

// ────────────────────────────────────────────────────────────────────────────
// Footer
// ────────────────────────────────────────────────────────────────────────────
const StoreFooter = ({ showAnnotations }) => (
  <footer style={{ position: "relative", background: "#0B1A14", color: "rgba(255,255,255,0.7)", padding: "56px 0 32px" }}>
    <BlockTag blocks={["core/template-part: Footer"]} position="top-right" showAnnotations={showAnnotations} />
    <div style={{ maxWidth: 1240, margin: "0 auto", padding: "0 32px" }}>
      <div style={{ display: "grid", gridTemplateColumns: "2fr 1fr 1fr 1fr 1fr", gap: 40, marginBottom: 48 }}>
        <div>
          <img src="assets/logo-full-white.svg" alt="SureCart" style={{ height: 26, marginBottom: 14 }} />
          <p className="sc-small" style={{ color: "rgba(255,255,255,0.6)", maxWidth: 320, margin: 0 }}>
            Powered by SureCart — managed eCommerce for WordPress.
          </p>
        </div>
        {[
          { title: "Shop",     links: ["iPhone", "Mac", "iPad", "Accessories"] },
          { title: "Support",  links: ["Help Center", "Order Status", "Returns", "Contact"] },
          { title: "Company",  links: ["About", "Press", "Affiliates", "Careers"] },
          { title: "Legal",    links: ["Privacy", "Terms", "Refunds", "Cookies"] },
        ].map(col => (
          <div key={col.title}>
            <div style={{ fontFamily: "var(--font-display)", fontWeight: 700, fontSize: 13, color: "#fff", marginBottom: 14, letterSpacing: "0.04em", textTransform: "uppercase" }}>{col.title}</div>
            <ul style={{ listStyle: "none", padding: 0, margin: 0, display: "flex", flexDirection: "column", gap: 8 }}>
              {col.links.map(l => <li key={l}><a href="#" style={{ color: "rgba(255,255,255,0.7)", textDecoration: "none", fontSize: 14 }}>{l}</a></li>)}
            </ul>
          </div>
        ))}
      </div>
      <div style={{ borderTop: "1px solid rgba(255,255,255,0.10)", paddingTop: 24, display: "flex", justifyContent: "space-between", alignItems: "center", fontSize: 13, color: "rgba(255,255,255,0.5)", flexWrap: "wrap", gap: 16 }}>
        <div>© 2026. All rights reserved.</div>
        <img src="assets/payment-methods.png" alt="" style={{ height: 24, opacity: 0.85 }} />
      </div>
    </div>
  </footer>
);

// ────────────────────────────────────────────────────────────────────────────
// Sticky add-to-cart bar
// ────────────────────────────────────────────────────────────────────────────
const StickyBuyBar = ({ state, set, visible, showAnnotations }) => {
  const color = COLORWAYS[state.colorIdx];
  const storage = STORAGE[state.storageIdx];
  const price = (1099 + storage.priceDelta) * state.qty;
  return (
    <div style={{
      position: "fixed", bottom: 0, left: 0, right: 0, zIndex: 50,
      background: "rgba(255,255,255,0.96)",
      borderTop: `1px solid ${ppBorder}`,
      backdropFilter: "saturate(180%) blur(12px)",
      transform: `translateY(${visible ? 0 : 100}%)`,
      transition: "transform var(--dur-slow) var(--ease)",
      boxShadow: "0 -4px 16px rgba(16,24,40,0.06)",
    }}>
      <BlockTag blocks={["core/group (sticky)", "surecart/add-to-cart-button"]} position="top-right" showAnnotations={showAnnotations} />
      <div style={{ maxWidth: 1240, margin: "0 auto", padding: "12px 32px", display: "flex", alignItems: "center", justifyContent: "space-between", gap: 24 }}>
        <div style={{ display: "flex", alignItems: "center", gap: 16 }}>
          <div style={{ width: 56, height: 56, borderRadius: 12, background: ppBgAlt, overflow: "hidden", flexShrink: 0 }}>
            <PhoneRender colorway={color} view="back" />
          </div>
          <div>
            <div style={{ fontFamily: "var(--font-display)", fontWeight: 700, fontSize: 16, color: ppFg1, lineHeight: 1.2 }}>iPhone 17 Pro</div>
            <div style={{ fontSize: 13, color: ppFg3 }}>{color.name} · {storage.label}{state.qty > 1 ? ` · Qty ${state.qty}` : ""}</div>
          </div>
        </div>
        <div style={{ display: "flex", alignItems: "center", gap: 16 }}>
          <div style={{ fontFamily: "var(--font-display)", fontWeight: 700, fontSize: 22, color: ppFg1, letterSpacing: "-0.02em" }}>
            ${price.toLocaleString()}
          </div>
          <button onClick={() => set({ added: true })} style={{
            background: ppGreen, color: "#fff",
            border: "none", borderRadius: 9999,
            fontWeight: 700, fontSize: 15,
            padding: "0 28px", height: 48,
            cursor: "pointer", display: "inline-flex", alignItems: "center", gap: 8,
          }}>
            <Icon name="shopping-bag" size={16} stroke={2} /> Add to Bag
          </button>
        </div>
      </div>
    </div>
  );
};

window.Highlights = Highlights;
window.DescriptionBlocks = DescriptionBlocks;
window.Specs = Specs;
window.FAQ = FAQ;
window.Related = Related;
window.FinalCTA = FinalCTA;
window.StoreFooter = StoreFooter;
window.StickyBuyBar = StickyBuyBar;
