// Fixture 05 — Minimal hero + CTA
// Smallest possible page. 2-col hero, no other sections.

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
			<div
				style={{
					display: "grid",
					gridTemplateColumns: "1fr 1fr",
					gap: "60px",
					alignItems: "center",
				}}
			>
				<div>
					<h1
						className="sc-display-1"
						style={{
							color: "var(--fg-1)",
							marginBottom: "24px",
						}}
					>
						The simplest way to ship.
					</h1>
					<p
						className="sc-lead"
						style={{
							color: "var(--fg-2)",
							marginBottom: "32px",
						}}
					>
						Every package on autopilot. Track once, ship everywhere, sleep at night.
					</p>
					<div style={{ display: "flex", gap: "16px" }}>
						<a
							href="#start"
							className="sc-btn-primary"
							style={{
								background: "var(--brand)",
								color: "#FFFFFF",
								padding: "14px 28px",
								borderRadius: "8px",
								textDecoration: "none",
								fontWeight: 600,
							}}
						>
							Start free trial
						</a>
						<a
							href="#demo"
							className="sc-btn-outline"
							style={{
								background: "transparent",
								color: "var(--fg-1)",
								padding: "14px 28px",
								borderRadius: "8px",
								border: "1px solid var(--border-1)",
								textDecoration: "none",
								fontWeight: 600,
							}}
						>
							Watch demo
						</a>
					</div>
				</div>
				<div>
					<img
						src="hero.jpg"
						alt="Dashboard preview"
						width="640"
						height="480"
						style={{
							width: "100%",
							height: "auto",
							borderRadius: "16px",
						}}
					/>
				</div>
			</div>
		</section>
	);
}
