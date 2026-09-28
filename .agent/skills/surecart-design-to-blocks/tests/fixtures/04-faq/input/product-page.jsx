// Fixture 04 — FAQ archetype
// 5-item collapsible FAQ section. Tests core/details with summary attr.

const FAQS = [
	{
		q: "What does the warranty cover?",
		a: "Every product ships with a 1-year limited warranty against manufacturing defects. Wear and tear from normal use is not covered.",
	},
	{
		q: "How do I return a product?",
		a: "Open your account dashboard, find the order, and click \"Request a return.\" You'll get a prepaid label by email. Returns must arrive within 30 days of delivery.",
	},
	{
		q: "Do you ship internationally?",
		a: "Yes — we ship to 47 countries. Shipping costs and estimated arrival are calculated at checkout based on your address.",
	},
	{
		q: "Can I change or cancel my order after I've placed it?",
		a: "Orders can be edited or cancelled within 1 hour of placing them. After that, the order is locked into our fulfillment queue.",
	},
	{
		q: "Do you offer business / volume pricing?",
		a: "We do — for orders of 25 units or more, contact our sales team and we'll send a custom quote within 1 business day.",
	},
];

export default function ProductPage() {
	return (
		<section
			style={{
				padding: "96px 24px",
				maxWidth: "800px",
				margin: "0 auto",
				background: "var(--bg-1)",
			}}
		>
			<h2
				className="sc-h2"
				style={{
					textAlign: "center",
					color: "var(--fg-1)",
					marginBottom: "48px",
				}}
			>
				Frequently asked questions
			</h2>

			<div style={{ display: "flex", flexDirection: "column", gap: "16px" }}>
				{FAQS.map((f) => (
					<details
						key={f.q}
						style={{
							borderTop: "1px solid var(--border-1)",
							padding: "16px 0",
						}}
					>
						<summary
							className="sc-h4"
							style={{
								color: "var(--fg-1)",
								cursor: "pointer",
								listStyle: "none",
							}}
						>
							{f.q}
						</summary>
						<p
							className="sc-body"
							style={{
								color: "var(--fg-2)",
								marginTop: "12px",
								lineHeight: "1.6",
							}}
						>
							{f.a}
						</p>
					</details>
				))}
			</div>
		</section>
	);
}
