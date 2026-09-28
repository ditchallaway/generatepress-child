// Fixture 12 — Sticky Purchase Bar
// Single horizontal sticky bar: variant image + title/price (left) + Add To Cart (right).

export default function StickyPurchase() {
	return (
		<div
			style={{
				position: "sticky",
				bottom: 0,
				display: "flex",
				justifyContent: "space-between",
				alignItems: "center",
				flexWrap: "nowrap",
				width: "100%",
				padding: "12px 24px",
				background: "var(--bg-1)",
				borderTop: "1px solid var(--border-1)",
				gap: "16px",
			}}
		>
			{/* Left zone: variant image + stacked title + variant + price */}
			<div
				style={{
					display: "flex",
					alignItems: "center",
					gap: "16px",
					flexWrap: "nowrap",
				}}
			>
				<img
					src="variant.jpg"
					alt="Selected variant"
					width="80"
					height="80"
					style={{
						width: "80px",
						height: "80px",
						objectFit: "cover",
						borderRadius: "8px",
						display: "block",
					}}
				/>

				<div style={{ display: "flex", flexDirection: "column", gap: "2px" }}>
					<h4
						style={{
							fontSize: "16px",
							fontWeight: 700,
							color: "var(--fg-1)",
							margin: 0,
						}}
					>
						Heritage Field Tee
					</h4>
					<span style={{ fontSize: "13px", color: "var(--fg-2)" }}>Saddle / M</span>

					<div style={{ display: "flex", gap: "0.4em", alignItems: "baseline", marginTop: "2px" }}>
						<span style={{ fontSize: "15px", fontWeight: 600, color: "var(--fg-1)" }}>$32</span>
						<span style={{ fontSize: "13px", color: "var(--fg-2)" }}>/each</span>
					</div>
				</div>
			</div>

			{/* Right zone: single Add To Cart */}
			<div>
				<a
					href="#add"
					data-add-to-cart
					className="sc-btn-primary"
					style={{
						background: "var(--brand)",
						color: "#FFFFFF",
						padding: "12px 24px",
						borderRadius: "8px",
						fontWeight: 600,
						textDecoration: "none",
					}}
				>
					Add To Cart
				</a>
			</div>
		</div>
	);
}
