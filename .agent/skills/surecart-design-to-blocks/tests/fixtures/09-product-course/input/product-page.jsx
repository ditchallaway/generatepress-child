// Fixture 09 — Product Course
// Light course product page. Hero + course-info row + 6-card feature grid.

const prices = [
	{ name: "Single Course", amount: "$49", interval: "" },
	{ name: "Bundle (3 courses)", amount: "$129", interval: "" },
	{ name: "All-Access", amount: "$19", interval: "/mo" },
];

const features = [
	{
		title: "Video Classes",
		body: "On-demand HD video lessons you can stream anywhere.",
		icon: "video",
	},
	{
		title: "Mobile Access",
		body: "Watch on your phone with our companion app.",
		icon: "mobile",
	},
	{
		title: "Bonus Resources",
		body: "Worksheets, templates, and reference PDFs.",
		icon: "book",
	},
	{
		title: "Downloads",
		body: "Save lessons offline for travel or commutes.",
		icon: "download",
	},
	{
		title: "Lifetime Access",
		body: "Buy once, learn forever — no expiration.",
		icon: "infinity",
	},
	{
		title: "Private Community",
		body: "Connect with classmates in our member forum.",
		icon: "users",
	},
];

function Icon({ name }) {
	const common = {
		width: 24,
		height: 24,
		viewBox: "0 0 24 24",
		fill: "none",
		stroke: "var(--brand)",
		strokeWidth: 1.75,
		strokeLinecap: "round",
		strokeLinejoin: "round",
	};
	switch (name) {
		case "video":
			return (
				<svg {...common}>
					<rect x="3" y="6" width="14" height="12" rx="2" />
					<path d="M17 10l4-2v8l-4-2z" />
				</svg>
			);
		case "mobile":
			return (
				<svg {...common}>
					<rect x="6" y="3" width="12" height="18" rx="2" />
					<path d="M11 18h2" />
				</svg>
			);
		case "book":
			return (
				<svg {...common}>
					<path d="M4 5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-2z" />
					<path d="M4 5v14" />
				</svg>
			);
		case "download":
			return (
				<svg {...common}>
					<path d="M12 4v12" />
					<path d="M7 11l5 5 5-5" />
					<path d="M5 20h14" />
				</svg>
			);
		case "infinity":
			return (
				<svg {...common}>
					<path d="M6 12c0-2 2-4 4-4s4 4 4 4 2 4 4 4 4-2 4-4-2-4-4-4-4 4-4 4-2 4-4 4-4-2-4-4z" />
				</svg>
			);
		case "users":
			return (
				<svg {...common}>
					<circle cx="9" cy="8" r="3" />
					<path d="M3 20a6 6 0 0 1 12 0" />
					<circle cx="17" cy="9" r="2" />
					<path d="M15 20a4 4 0 0 1 6 0" />
				</svg>
			);
		default:
			return null;
	}
}

export default function ProductPage() {
	return (
		<section
			style={{
				background: "var(--bg-page)",
				padding: "60px 0",
			}}
		>
			<div
				style={{
					maxWidth: "1320px",
					margin: "0 auto",
					padding: "0 24px",
					display: "flex",
					flexDirection: "column",
					gap: "60px",
				}}
			>
				{/* Hero — 2 columns */}
				<div
					style={{
						display: "grid",
						gridTemplateColumns: "1fr 1fr",
						gap: "60px",
						alignItems: "start",
					}}
				>
					{/* Left: media + course-info row */}
					<div style={{ display: "flex", flexDirection: "column", gap: "32px" }}>
						<img
							src="course-hero.jpg"
							alt="Course preview"
							width="640"
							height="480"
							style={{ width: "100%", height: "auto", borderRadius: "16px" }}
						/>

						{/* Course-info row: 3 stats with vertical dividers */}
						<div
							style={{
								background: "var(--bg-1)",
								borderRadius: "24px",
								padding: "32px",
								display: "flex",
								justifyContent: "space-between",
								gap: "0",
							}}
						>
							<div style={{ display: "flex", flexDirection: "column", gap: "4px" }}>
								<span style={{ color: "var(--fg-2)", fontSize: "14px" }}>Level</span>
								<span style={{ color: "var(--fg-1)", fontSize: "16px", fontWeight: 600 }}>
									All levels
								</span>
							</div>
							<div
								style={{
									display: "flex",
									flexDirection: "column",
									gap: "4px",
									paddingLeft: "32px",
									marginLeft: "32px",
									borderLeft: "1px solid #d0d5db96",
								}}
							>
								<span style={{ color: "var(--fg-2)", fontSize: "14px" }}>Duration</span>
								<span style={{ color: "var(--fg-1)", fontSize: "16px", fontWeight: 600 }}>
									4 Hours 52 m
								</span>
							</div>
							<div
								style={{
									display: "flex",
									flexDirection: "column",
									gap: "4px",
									paddingLeft: "32px",
									marginLeft: "32px",
									borderLeft: "1px solid #d0d5db96",
								}}
							>
								<span style={{ color: "var(--fg-2)", fontSize: "14px" }}>Language</span>
								<span style={{ color: "var(--fg-1)", fontSize: "16px", fontWeight: 600 }}>
									English
								</span>
							</div>
						</div>
					</div>

					{/* Right: info column */}
					<div style={{ display: "flex", flexDirection: "column", gap: "20px" }}>
						<span
							style={{
								color: "var(--brand)",
								fontSize: "13px",
								fontWeight: 600,
								letterSpacing: "0.08em",
								textTransform: "uppercase",
							}}
						>
							Online Course
						</span>
						<h1
							style={{
								fontSize: "40px",
								fontWeight: 700,
								color: "var(--fg-1)",
								margin: 0,
								lineHeight: 1.15,
							}}
						>
							Foundations of Type Design
						</h1>
						<p style={{ color: "var(--fg-2)", fontSize: "17px", margin: 0, lineHeight: 1.55 }}>
							A complete introduction to drawing letters, building character sets, and exporting
							production-ready font files. From first sketch to working OTF.
						</p>

						{/* Multi-tier price chooser */}
						<div style={{ display: "flex", flexDirection: "column", gap: "8px" }}>
							{prices.map((p) => (
								<div
									key={p.name}
									style={{
										display: "flex",
										justifyContent: "space-between",
										alignItems: "center",
										padding: "14px 18px",
										border: "1px solid var(--border-1)",
										borderRadius: "12px",
										background: "var(--bg-1)",
									}}
								>
									<span style={{ fontWeight: 600, color: "var(--fg-1)" }}>{p.name}</span>
									<div style={{ display: "flex", gap: "0.25em", alignItems: "baseline" }}>
										<span style={{ fontWeight: 700, color: "var(--fg-1)" }}>{p.amount}</span>
										<span style={{ color: "var(--fg-2)" }}>{p.interval}</span>
									</div>
								</div>
							))}
						</div>

						{/* Buy buttons */}
						<div style={{ display: "flex", gap: "10px", flexWrap: "wrap" }}>
							<a
								href="#enroll"
								data-add-to-cart
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
								Enroll Now
							</a>
							<a
								href="#preview"
								className="sc-btn-outline"
								style={{
									background: "transparent",
									color: "var(--fg-1)",
									padding: "14px 28px",
									borderRadius: "999px",
									border: "1px solid var(--border-1)",
									fontWeight: 600,
									textDecoration: "none",
								}}
							>
								Watch Preview
							</a>
						</div>
					</div>
				</div>

				{/* Feature grid */}
				<div style={{ marginTop: "20px", display: "flex", flexDirection: "column", gap: "32px" }}>
					<h2 style={{ fontSize: "28px", color: "var(--fg-1)", margin: 0, fontWeight: 700 }}>
						What's in every course?
					</h2>
					<div
						style={{
							display: "grid",
							gridTemplateColumns: "repeat(3, 1fr)",
							gap: "24px",
						}}
					>
						{features.map((f) => (
							<div
								key={f.title}
								style={{
									background: "var(--bg-1)",
									borderRadius: "20px",
									padding: "28px",
									display: "flex",
									flexDirection: "column",
									gap: "12px",
								}}
							>
								<Icon name={f.icon} />
								<h3
									style={{
										fontSize: "18px",
										fontWeight: 600,
										color: "var(--fg-1)",
										margin: 0,
									}}
								>
									{f.title}
								</h3>
								<p style={{ color: "var(--fg-2)", fontSize: "14px", margin: 0, lineHeight: 1.5 }}>
									{f.body}
								</p>
							</div>
						))}
					</div>
				</div>
			</div>
		</section>
	);
}
