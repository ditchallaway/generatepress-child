// Fixture 13 — Product Quick View modal
// 500px-wide modal product card. Em-relative typography (1.2em / 0.88em / 0.75em)
// scales from a 16px base set on the wrapper. Single section.

export default function ProductPage() {
	const product = {
		title: "Wayfarer Backpack",
		variant: "Charcoal • 22L",
		image: "wayfarer-charcoal-22l.jpg",
		price: { amount: "$129.00", scratch: "$159.00", interval: "/each" },
		trial: null,
		fees: null,
		variants: ["Charcoal", "Olive", "Slate"],
		prices: [
			{ name: "One-time", amount: "$129.00", scratch: "$159.00", interval: "" },
			{ name: "Subscribe & save", amount: "$109.00", scratch: "$139.00", interval: "/month" },
		],
	};

	return (
		<aside
			role="dialog"
			aria-modal="true"
			className="sc-quick-view"
			style={{
				width: "500px",
				margin: "0 auto",
				padding: "24px",
				background: "var(--bg-1)",
				fontSize: "16px",
				color: "var(--fg-1)",
			}}
		>
			{/* Top row: image + info column + close button */}
			<div
				style={{
					display: "flex",
					justifyContent: "space-between",
					alignItems: "flex-start",
					gap: "16px",
				}}
			>
				<div style={{ display: "flex", flexWrap: "nowrap", gap: "16px" }}>
					<img
						src={product.image}
						alt={product.title}
						width="120"
						height="120"
						style={{
							width: "120px",
							height: "120px",
							aspectRatio: "1",
							borderRadius: "10px",
							objectFit: "cover",
							flex: "0 0 120px",
						}}
					/>
					<div style={{ display: "flex", flexDirection: "column", gap: 0 }}>
						<a
							href="/product/wayfarer-backpack"
							className="sc-quick-view-title"
							style={{
								fontSize: "1.2em",
								fontWeight: 600,
								lineHeight: 1.2,
								color: "var(--fg-1)",
								textDecoration: "none",
							}}
						>
							{product.title}
						</a>
						<span style={{ fontSize: "1em", color: "var(--fg-2)" }}>
							{product.variant}
						</span>
						<div
							style={{
								display: "flex",
								flexWrap: "wrap",
								alignItems: "flex-end",
								gap: "0.4em",
								fontWeight: 500,
								marginTop: "4px",
							}}
						>
							<s style={{ color: "var(--fg-mute)", lineHeight: 1.5, fontSize: "1em" }}>
								{product.price.scratch}
							</s>
							<div style={{ display: "flex", flexWrap: "nowrap", gap: "0.25em" }}>
								<strong style={{ lineHeight: 1.5, fontSize: "1em" }}>
									{product.price.amount}
								</strong>
								<span
									className="sc-interval"
									style={{ fontSize: "0.88em", lineHeight: 1.5, color: "var(--accent-4)" }}
								>
									{product.price.interval}
								</span>
							</div>
							<span
								className="sc-sale-badge"
								style={{
									background: "var(--brand)",
									color: "#FFFFFF",
									padding: "4px 10px",
									borderRadius: "15px",
									fontSize: "12px",
									lineHeight: 2.1,
									alignSelf: "flex-start",
								}}
							>
								Sale
							</span>
						</div>
					</div>
				</div>
				<button
					type="button"
					className="sc-quick-view-close"
					aria-label="Close"
					style={{
						background: "transparent",
						border: "none",
						fontSize: "20px",
						cursor: "pointer",
					}}
				>
					×
				</button>
			</div>

			{/* Variant pills */}
			<div
				className="sc-variant-pills"
				style={{ display: "flex", gap: "8px", marginTop: "16px" }}
			>
				{product.variants.map((v) => (
					<button
						key={v}
						type="button"
						style={{
							padding: "6px 14px",
							border: "1px solid var(--border-1)",
							borderRadius: "999px",
							background: "transparent",
							fontSize: "0.88em",
							cursor: "pointer",
						}}
					>
						{v}
					</button>
				))}
			</div>

			{/* Multi-tier price chooser */}
			<div className="sc-price-chooser" style={{ marginTop: "16px" }}>
				{product.prices.map((tier, i) => (
					<label
						key={tier.name}
						className="sc-price-choice"
						style={{
							display: "flex",
							justifyContent: "flex-start",
							flexWrap: "nowrap",
							flexDirection: "row",
							padding: "12px 0",
							borderBottom: i < product.prices.length - 1 ? "1px solid var(--border-1)" : "none",
						}}
					>
						<span
							style={{
								flex: "0 0 50%",
								fontWeight: 600,
								fontSize: "0.88em",
							}}
						>
							{tier.name}
						</span>
						<div
							style={{
								flex: "1 1 auto",
								display: "flex",
								flexDirection: "column",
								alignItems: "flex-end",
							}}
						>
							<div style={{ display: "flex", gap: "0.5rem" }}>
								<s style={{ color: "var(--fg-mute)", fontWeight: 500, fontSize: "0.88em" }}>
									{tier.scratch}
								</s>
								<strong style={{ fontWeight: 700, fontSize: "0.88em" }}>{tier.amount}</strong>
								<span style={{ fontWeight: 700, fontSize: "0.75em" }}>{tier.interval}</span>
							</div>
						</div>
					</label>
				))}
			</div>

			{/* Buy buttons — 50/50 split */}
			<div className="sc-buy-buttons" style={{ display: "flex", gap: "8px", marginTop: "16px" }}>
				<a
					href="#add-to-cart"
					className="sc-btn-primary"
					style={{
						flex: "1 1 50%",
						background: "var(--brand)",
						color: "#FFFFFF",
						padding: "12px 20px",
						borderRadius: "8px",
						textAlign: "center",
						fontWeight: 600,
						textDecoration: "none",
					}}
				>
					Add To Cart
				</a>
				<a
					href="#buy-now"
					className="sc-btn-outline"
					style={{
						flex: "1 1 50%",
						background: "transparent",
						color: "var(--fg-1)",
						border: "1px solid var(--border-1)",
						padding: "12px 20px",
						borderRadius: "8px",
						textAlign: "center",
						fontWeight: 600,
						textDecoration: "none",
					}}
				>
					Buy Now
				</a>
			</div>
		</aside>
	);
}
