// Fixture 03 — Feature grid
// 4-card .map() expansion with literal-bg card chrome (Exemplar 3.5).

const FEATURES = [
	{
		title: "Real-time tracking",
		body: "See every package update the moment it happens. No refresh, no polling, no missed alerts.",
	},
	{
		title: "Multi-carrier support",
		body: "USPS, UPS, FedEx, DHL, and 30 more — under one roof, with one consistent API surface.",
	},
	{
		title: "Predictive ETAs",
		body: "We learn from your history and forecast arrival within a 30-minute window for 94% of shipments.",
	},
	{
		title: "Branded notifications",
		body: "Email and SMS templates that match your store. No \"Sent from Acme Corp\" tail at the bottom.",
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
			<h2
				className="sc-h2"
				style={{
					textAlign: "center",
					color: "var(--fg-1)",
					marginBottom: "16px",
				}}
			>
				Built for the long haul.
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
				Everything you need to run a serious shipping operation, without paying for everything you don't.
			</p>

			<div
				style={{
					display: "grid",
					gridTemplateColumns: "repeat(4, 1fr)",
					gap: "24px",
				}}
			>
				{FEATURES.map((f) => (
					<div
						key={f.title}
						style={{
							background: "#FFFFFF",
							border: "1px solid var(--border-1)",
							borderRadius: "16px",
							padding: "32px",
						}}
					>
						<h3
							className="sc-h3"
							style={{
								color: "var(--fg-1)",
								marginBottom: "12px",
							}}
						>
							{f.title}
						</h3>
						<p
							className="sc-body"
							style={{
								color: "var(--fg-2)",
								lineHeight: "1.6",
							}}
						>
							{f.body}
						</p>
					</div>
				))}
			</div>
		</section>
	);
}
