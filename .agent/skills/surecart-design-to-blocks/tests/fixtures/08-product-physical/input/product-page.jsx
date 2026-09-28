// Fixture 08 — Product Physical
// Cream background, saddle-brown accents, themed quantity stepper, benefit rows.

const prices = [
	{ name: "1 Pack", amount: "$28", interval: "" },
	{ name: "3 Pack", amount: "$72", interval: "", scratch: "$84" },
	{ name: "Subscribe & Save", amount: "$24", interval: "/mo" },
];

export default function ProductPage() {
	return (
		<section
			style={{
				maxWidth: "1320px",
				margin: "0 auto",
				padding: "60px 75px 0",
				background: "var(--bg-page)",
			}}
		>
			<div
				style={{
					display: "flex",
					flexWrap: "nowrap",
					alignItems: "flex-start",
					justifyContent: "center",
					gap: "0",
				}}
			>
				{/* Media column — 800px fixed */}
				<div style={{ flex: "0 0 800px" }}>
					<img
						src="hero.jpg"
						alt="Product"
						width="800"
						height="900"
						style={{ width: "100%", height: "auto" }}
					/>
				</div>

				{/* Info column — 424px fixed, margin-left:96px */}
				<div
					style={{
						flex: "0 0 424px",
						marginLeft: "96px",
						display: "flex",
						flexDirection: "column",
						gap: "16px",
						fontSize: "16px",
						fontWeight: 500,
						color: "var(--fg-1)",
					}}
				>
					{/* Title */}
					<h1
						style={{
							fontSize: "40px",
							margin: "10px 0",
							color: "var(--fg-1)",
							fontWeight: 600,
						}}
					>
						Heritage Leather Wallet
					</h1>

					{/* Price row */}
					<div style={{ display: "flex", flexWrap: "wrap", alignItems: "flex-end", gap: "0.5em" }}>
						<span style={{ fontSize: "20px", lineHeight: "1.3", color: "var(--fg-1)" }}>$28</span>
						<span style={{ fontSize: "15px", lineHeight: "1.5", color: "var(--fg-1)" }}>/each</span>
						<span
							style={{
								fontSize: "15px",
								lineHeight: "1.5",
								textDecoration: "line-through",
								color: "var(--sale-red)",
							}}
						>
							$36
						</span>
					</div>

					{/* Trial row */}
					<div style={{ display: "flex", gap: "0.5em" }}>
						<span style={{ fontSize: "15px", color: "var(--fg-2)" }}>30-day trial</span>
						<span style={{ fontSize: "15px", color: "var(--fg-2)" }}>+ free shipping</span>
					</div>

					{/* Description */}
					<p style={{ color: "var(--fg-2)", fontSize: "15px", margin: 0, lineHeight: 1.6 }}>
						Hand-stitched full-grain leather. Develops a unique patina with use. Holds 8 cards
						and folded bills.
					</p>

					{/* Multi-tier price chooser */}
					<div style={{ display: "flex", flexDirection: "column", gap: "8px" }}>
						{prices.map((p) => (
							<div
								key={p.name}
								style={{
									display: "flex",
									justifyContent: "space-between",
									padding: "12px 16px",
									border: "1px solid var(--border-1)",
									borderRadius: "8px",
									background: "var(--bg-1)",
								}}
							>
								<span style={{ fontWeight: 600, color: "var(--fg-1)" }}>{p.name}</span>
								<div style={{ display: "flex", gap: "0.25em", alignItems: "baseline" }}>
									{p.scratch ? (
										<span style={{ textDecoration: "line-through", color: "var(--sale-red)" }}>
											{p.scratch}
										</span>
									) : null}
									<span style={{ fontWeight: 700, color: "var(--fg-1)" }}>{p.amount}</span>
									<span style={{ color: "var(--fg-2)" }}>{p.interval}</span>
								</div>
							</div>
						))}
					</div>

					{/* Variant pills (themed brown) */}
					<div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
						<button
							style={{
								padding: "6px 14px",
								borderRadius: "999px",
								border: "1px solid #8b451340",
								background: "var(--brand)",
								color: "#FFFFFF",
								fontSize: "14px",
							}}
						>
							Saddle
						</button>
						<button
							style={{
								padding: "6px 14px",
								borderRadius: "999px",
								border: "1px solid #8b451340",
								background: "transparent",
								color: "var(--fg-1)",
								fontSize: "14px",
							}}
						>
							Espresso
						</button>
						<button
							style={{
								padding: "6px 14px",
								borderRadius: "999px",
								border: "1px solid #8b451340",
								background: "transparent",
								color: "var(--fg-1)",
								fontSize: "14px",
							}}
						>
							Tan
						</button>
					</div>

					{/* Quantity stepper — pill rounded */}
					<div
						className="is-style-pebble"
						style={{
							display: "flex",
							alignItems: "center",
							gap: "0",
							border: "1px solid #8b451340",
							borderRadius: "999px",
							padding: "4px",
							alignSelf: "flex-start",
						}}
					>
						<button
							aria-label="decrease"
							style={{
								width: "32px",
								height: "32px",
								borderRadius: "999px",
								background: "transparent",
								color: "var(--fg-1)",
								border: "none",
							}}
						>
							−
						</button>
						<input
							type="number"
							defaultValue={1}
							style={{
								width: "44px",
								textAlign: "center",
								border: "none",
								background: "transparent",
								color: "var(--fg-1)",
							}}
						/>
						<button
							aria-label="increase"
							style={{
								width: "32px",
								height: "32px",
								borderRadius: "999px",
								background: "transparent",
								color: "var(--fg-1)",
								border: "none",
							}}
						>
							+
						</button>
					</div>

					{/* Buy buttons (themed) */}
					<div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
						<a
							href="#add"
							data-add-to-cart
							className="sc-btn-outline"
							style={{
								background: "transparent",
								color: "var(--brand)",
								padding: "14px 28px",
								borderRadius: "999px",
								border: "1px solid var(--brand)",
								fontWeight: 600,
								textDecoration: "none",
							}}
						>
							Add To Cart
						</a>
						<a
							href="#buy"
							className="sc-btn-primary"
							style={{
								background: "var(--brand)",
								color: "#FFFFFF",
								padding: "14px 28px",
								borderRadius: "999px",
								fontWeight: 600,
								textDecoration: "none",
							}}
						>
							Buy Now
						</a>
					</div>

					{/* Benefit rows */}
					<div
						style={{
							marginTop: "16px",
							display: "flex",
							flexDirection: "column",
							gap: "12px",
						}}
					>
						<div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
							<svg
								width="20"
								height="20"
								viewBox="0 0 24 24"
								fill="none"
								stroke="var(--brand)"
								strokeWidth="1.75"
								strokeLinecap="round"
								strokeLinejoin="round"
							>
								<path d="M3 7h13v10H3z" />
								<path d="M16 10h4l2 3v4h-6" />
								<circle cx="7" cy="18" r="2" />
								<circle cx="18" cy="18" r="2" />
							</svg>
							<span style={{ color: "var(--fg-1)", fontSize: "14px" }}>
								Free shipping over $50
							</span>
						</div>
						<div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
							<svg
								width="20"
								height="20"
								viewBox="0 0 24 24"
								fill="none"
								stroke="var(--brand)"
								strokeWidth="1.75"
								strokeLinecap="round"
								strokeLinejoin="round"
							>
								<path d="M3 12a9 9 0 1 0 3-6.7" />
								<path d="M3 4v5h5" />
							</svg>
							<span style={{ color: "var(--fg-1)", fontSize: "14px" }}>
								30-day easy returns
							</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	);
}
