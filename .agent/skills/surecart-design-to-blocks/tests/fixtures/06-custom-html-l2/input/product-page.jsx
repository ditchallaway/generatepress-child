// Fixture 06 — custom-HTML L2 piggyback (Interactivity API)
// Hero with a custom "View cart" pill button that toggles the cart drawer.
// The behavior maps to surecart/cart::actions.toggle, which a sibling
// surecart/cart-icon block will load on the page.

export default function ProductPage() {
	return (
		<section
			style={{
				padding: "96px 24px",
				maxWidth: "1200px",
				margin: "0 auto",
				background: "var(--bg-1)",
			}}
		>
			<h1
				className="sc-display-1"
				style={{
					textAlign: "center",
					color: "var(--fg-1)",
					marginBottom: "16px",
				}}
			>
				Build your kit.
			</h1>
			<p
				className="sc-lead"
				style={{
					textAlign: "center",
					color: "var(--fg-2)",
					marginBottom: "40px",
				}}
			>
				Add the pieces you need, then check out — your cart is always one click away.
			</p>

			{/* Custom widget: pill button with live cart-item count.
			    Behavior: clicking it toggles the SureCart slide-out cart drawer.
			    Live count: reflects the current items in the cart. */}
			<div
				className="hero-cart-pill"
				style={{
					display: "flex",
					justifyContent: "center",
					marginBottom: "16px",
				}}
			>
				<button
					type="button"
					className="hero-cart-pill__btn"
					style={{
						background: "var(--brand)",
						color: "#FFFFFF",
						padding: "16px 32px",
						borderRadius: "999px",
						border: "none",
						fontWeight: 600,
						fontSize: "16px",
						cursor: "pointer",
						display: "inline-flex",
						alignItems: "center",
						gap: "8px",
					}}
					data-action="cart.toggle"
				>
					View cart
					<span className="hero-cart-pill__count">0</span>
				</button>
			</div>

			{/* The cart icon at the top-right is what triggers the
			    surecart/cart Interactivity module to load on the page. */}
			<div className="cart-icon-anchor" style={{ position: "absolute", top: "16px", right: "16px" }}>
				<span data-block="surecart/cart-icon" />
			</div>
		</section>
	);
}
