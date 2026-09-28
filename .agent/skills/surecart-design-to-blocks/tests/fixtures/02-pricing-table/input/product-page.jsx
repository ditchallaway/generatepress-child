// Fixture 02 — Pricing table (static / marketing, not product-driven)
// 3 plans, middle one highlighted as "recommended" (thicker accent border + brand bg tint).

const PLANS = [
	{
		name: "Starter",
		price: "$9",
		interval: "/month",
		features: ["100 shipments / mo", "Email tracking", "Single user", "Community support"],
		cta: "Choose Starter",
		recommended: false,
	},
	{
		name: "Growth",
		price: "$29",
		interval: "/month",
		features: ["1,000 shipments / mo", "Email + SMS tracking", "5 users", "Priority email support", "Branded notifications"],
		cta: "Choose Growth",
		recommended: true,
	},
	{
		name: "Scale",
		price: "$99",
		interval: "/month",
		features: ["Unlimited shipments", "All notification channels", "Unlimited users", "Slack + phone support", "Custom domain", "SAML SSO"],
		cta: "Choose Scale",
		recommended: false,
	},
];

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
			<p
				className="sc-eyebrow"
				style={{
					textAlign: "center",
					color: "var(--brand)",
					marginBottom: "12px",
				}}
			>
				PRICING
			</p>
			<h2
				className="sc-h2"
				style={{
					textAlign: "center",
					color: "var(--fg-1)",
					marginBottom: "16px",
				}}
			>
				Simple plans, no surprises.
			</h2>
			<p
				className="sc-lead"
				style={{
					textAlign: "center",
					color: "var(--fg-2)",
					marginBottom: "64px",
					maxWidth: "640px",
					marginLeft: "auto",
					marginRight: "auto",
				}}
			>
				Pick the plan that fits today. Upgrade or downgrade any time — we'll prorate.
			</p>

			<div
				style={{
					display: "grid",
					gridTemplateColumns: "repeat(3, 1fr)",
					gap: "24px",
				}}
			>
				{PLANS.map((p) => (
					<div
						key={p.name}
						style={{
							background: p.recommended ? "var(--brand-soft)" : "#FFFFFF",
							border: p.recommended ? "2px solid var(--brand)" : "1px solid var(--border-1)",
							borderRadius: "16px",
							padding: "32px",
						}}
					>
						<h3
							className="sc-h3"
							style={{
								color: "var(--fg-1)",
								marginBottom: "8px",
							}}
						>
							{p.name}
						</h3>
						<p
							className="sc-display-4"
							style={{
								color: "var(--fg-1)",
								marginBottom: "24px",
							}}
						>
							{p.price}
							<span
								className="sc-small"
								style={{ color: "var(--fg-2)", marginLeft: "4px" }}
							>
								{p.interval}
							</span>
						</p>
						<ul
							style={{
								listStyle: "none",
								padding: 0,
								margin: "0 0 32px 0",
								display: "flex",
								flexDirection: "column",
								gap: "8px",
							}}
						>
							{p.features.map((feat) => (
								<li
									key={feat}
									className="sc-body"
									style={{ color: "var(--fg-2)" }}
								>
									{feat}
								</li>
							))}
						</ul>
						<a
							href="#checkout"
							style={{
								display: "block",
								textAlign: "center",
								background: p.recommended ? "var(--brand)" : "transparent",
								color: p.recommended ? "#FFFFFF" : "var(--fg-1)",
								border: p.recommended ? "1px solid var(--brand)" : "1px solid var(--border-1)",
								padding: "12px 24px",
								borderRadius: "8px",
								textDecoration: "none",
								fontWeight: 600,
							}}
						>
							{p.cta}
						</a>
					</div>
				))}
			</div>
		</section>
	);
}
