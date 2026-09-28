// Fixture 07 — Classic Product (product-standard)
// Canonical 2-column product page: media left, info right (36% width).

const prices = [
	{ name: "Monthly", amount: "$19", interval: "/mo" },
	{ name: "Annual", amount: "$190", interval: "/yr", scratch: "$228" },
	{ name: "Lifetime", amount: "$499", interval: "" },
];

export default function ProductPage() {
	return (
		<section
			style={{
				maxWidth: "1200px",
				margin: "0 auto",
				padding: "60px 24px",
				background: "var(--bg-1)",
			}}
		>
			<div
				style={{
					display: "grid",
					gridTemplateColumns: "1fr 36%",
					gap: "60px",
					rowGap: "30px",
					alignItems: "start",
				}}
			>
				{/* Media column */}
				<div>
					<img
						src="product-hero.jpg"
						alt="Product hero"
						width="800"
						height="800"
						style={{
							width: "100%",
							height: "auto",
							borderRadius: "8px",
						}}
					/>
				</div>

				{/* Info column */}
				<div style={{ display: "flex", flexDirection: "column", gap: "0.75rem" }}>
					{/* Collection tags */}
					<div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
						<span style={{ fontSize: "12px", color: "var(--fg-mute)" }}>Apparel</span>
					</div>

					{/* Review row */}
					<div
						style={{
							display: "flex",
							flexWrap: "nowrap",
							gap: "10px",
							alignItems: "center",
						}}
					>
						<span aria-label="rating" style={{ color: "var(--fg-1)" }}>★★★★★</span>
						<span style={{ color: "var(--fg-1)", fontSize: "14px" }}>(124)</span>
					</div>

					{/* Title */}
					<h1
						style={{
							fontSize: "32px",
							fontWeight: 600,
							color: "var(--fg-1)",
							margin: 0,
						}}
					>
						Classic Field Tee
					</h1>

					{/* Price stack */}
					<div style={{ display: "flex", flexDirection: "column", gap: "0" }}>
						{/* Row 1: scratch / amount / interval / sale-badge */}
						<div
							style={{
								display: "flex",
								flexWrap: "wrap",
								gap: "0.5em",
								alignItems: "flex-end",
							}}
						>
							<span
								style={{
									fontSize: "24px",
									lineHeight: "1.5",
									textDecoration: "line-through",
									color: "#686868",
								}}
							>
								$48
							</span>
							<span style={{ fontSize: "24px", lineHeight: "1.5", color: "var(--fg-1)" }}>$32</span>
							<span style={{ lineHeight: "2", color: "var(--fg-2)" }}>/each</span>
							<span
								style={{
									fontSize: "12px",
									lineHeight: "2.1",
									borderRadius: "15px",
									background: "var(--brand)",
									color: "#FFFFFF",
									padding: "0 10px",
								}}
							>
								Sale
							</span>
						</div>

						{/* Row 2: trial + fees */}
						<div
							style={{
								display: "flex",
								flexWrap: "nowrap",
								gap: "0.5em",
							}}
						>
							<span style={{ color: "var(--fg-mute)", fontSize: "13px" }}>14-day trial</span>
							<span style={{ color: "var(--fg-mute)", fontSize: "13px" }}>+ $5 setup</span>
						</div>
					</div>

					{/* Description */}
					<p style={{ color: "var(--fg-2)", fontSize: "16px", margin: 0 }}>
						Garment-dyed cotton tee with reinforced shoulders. Pre-shrunk, machine-washable,
						built for daily wear.
					</p>

					{/* Variant pills */}
					<div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
						<button
							style={{
								padding: "6px 12px",
								borderRadius: "999px",
								border: "1px solid var(--border-1)",
								background: "var(--bg-1)",
								color: "var(--fg-1)",
							}}
						>
							S
						</button>
						<button
							style={{
								padding: "6px 12px",
								borderRadius: "999px",
								border: "1px solid var(--border-1)",
								background: "var(--bg-1)",
								color: "var(--fg-1)",
							}}
						>
							M
						</button>
						<button
							style={{
								padding: "6px 12px",
								borderRadius: "999px",
								border: "1px solid var(--border-1)",
								background: "var(--bg-1)",
								color: "var(--fg-1)",
							}}
						>
							L
						</button>
					</div>

					{/* Multi-tier price chooser */}
					<div style={{ display: "flex", flexDirection: "column", gap: "8px" }}>
						{prices.map((p) => (
							<div
								key={p.name}
								style={{
									display: "flex",
									flexWrap: "nowrap",
									justifyContent: "space-between",
									alignItems: "center",
									padding: "12px 16px",
									border: "1px solid var(--border-1)",
									borderRadius: "8px",
								}}
							>
								<span
									style={{
										flex: "0 0 50%",
										fontWeight: 600,
										color: "var(--fg-1)",
									}}
								>
									{p.name}
								</span>
								<div
									style={{
										flex: "0 0 50%",
										display: "flex",
										flexDirection: "column",
										alignItems: "flex-end",
										gap: "0",
									}}
								>
									<div style={{ display: "flex", gap: "0.5rem", alignItems: "baseline" }}>
										{p.scratch ? (
											<span
												style={{
													textDecoration: "line-through",
													fontWeight: 500,
													color: "#686868",
												}}
											>
												{p.scratch}
											</span>
										) : null}
										<span style={{ fontWeight: 700, color: "var(--fg-1)" }}>{p.amount}</span>
										<span style={{ fontWeight: 700, color: "var(--fg-1)" }}>{p.interval}</span>
									</div>
									<span style={{ fontSize: "13px", color: "#8a8a8a" }}>14-day trial</span>
									<span style={{ fontSize: "13px", color: "#8a8a8a" }}>+ $5 setup</span>
								</div>
							</div>
						))}
					</div>

					{/* Quantity stepper */}
					<div
						style={{
							display: "flex",
							alignItems: "center",
							gap: "8px",
							marginTop: "8px",
						}}
					>
						<button aria-label="decrease" style={{ width: "32px", height: "32px" }}>−</button>
						<input type="number" defaultValue={1} style={{ width: "48px", textAlign: "center" }} />
						<button aria-label="increase" style={{ width: "32px", height: "32px" }}>+</button>
					</div>

					{/* Buy buttons */}
					<div style={{ display: "flex", gap: "5px", flexWrap: "wrap" }}>
						<a
							href="#add"
							className="sc-btn-primary"
							data-add-to-cart
							style={{
								background: "var(--brand)",
								color: "#FFFFFF",
								padding: "12px 24px",
								borderRadius: "6px",
								fontWeight: 600,
								textDecoration: "none",
							}}
						>
							Add To Cart
						</a>
						<a
							href="#buy"
							className="sc-btn-outline"
							style={{
								background: "transparent",
								color: "var(--fg-1)",
								padding: "12px 24px",
								borderRadius: "6px",
								border: "1px solid var(--border-1)",
								fontWeight: 600,
								textDecoration: "none",
							}}
						>
							Buy Now
						</a>
					</div>
				</div>
			</div>
		</section>
	);
}
