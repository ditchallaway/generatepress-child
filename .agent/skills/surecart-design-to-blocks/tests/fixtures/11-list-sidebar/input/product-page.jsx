// Fixture 11 — Product List with Sidebar
// Top: controls bar + filter tags. Main: 2-col flex (sidebar 225px sticky + content fill).

const products = Array.from({ length: 9 }).map((_, i) => ({
	id: i + 1,
	title: `Product ${i + 1}`,
	price: `$${(i + 2) * 9}`,
	image: `product-${i + 1}.jpg`,
}));

export default function ProductList() {
	return (
		<section
			style={{
				maxWidth: "1320px",
				margin: "0 auto",
				padding: "60px 24px",
				background: "var(--bg-page)",
			}}
		>
			{/* Top controls bar */}
			<div
				style={{
					display: "flex",
					justifyContent: "space-between",
					alignItems: "center",
					gap: "20px",
					marginBottom: "20px",
				}}
			>
				<button
					aria-label="toggle sidebar"
					style={{
						padding: "8px 14px",
						border: "1px solid var(--border-1)",
						borderRadius: "8px",
						background: "var(--bg-1)",
						color: "var(--fg-1)",
						display: "flex",
						alignItems: "center",
						gap: "8px",
					}}
				>
					<svg
						width="16"
						height="16"
						viewBox="0 0 24 24"
						fill="none"
						stroke="currentColor"
						strokeWidth="2"
						strokeLinecap="round"
					>
						<path d="M4 6h16" />
						<path d="M4 12h16" />
						<path d="M4 18h16" />
					</svg>
					Filters
				</button>

				<input
					type="search"
					placeholder="Search"
					style={{
						padding: "8px 12px",
						border: "1px solid var(--border-1)",
						borderRadius: "8px",
						background: "var(--bg-1)",
						color: "var(--fg-1)",
						fontSize: "14px",
						minWidth: "240px",
					}}
				/>
			</div>

			{/* Filter tags row */}
			<div
				style={{
					display: "flex",
					gap: "8px",
					flexWrap: "wrap",
					marginBottom: "32px",
				}}
			>
				<span
					style={{
						padding: "6px 12px",
						borderRadius: "999px",
						border: "1px solid var(--border-1)",
						color: "var(--fg-1)",
						fontSize: "13px",
					}}
				>
					New
				</span>
				<span
					style={{
						padding: "6px 12px",
						borderRadius: "999px",
						border: "1px solid var(--border-1)",
						color: "var(--fg-1)",
						fontSize: "13px",
					}}
				>
					Sale
				</span>
				<span
					style={{
						padding: "6px 12px",
						borderRadius: "999px",
						border: "1px solid var(--border-1)",
						color: "var(--fg-1)",
						fontSize: "13px",
					}}
				>
					In Stock
				</span>
			</div>

			{/* Main: 2-col flex */}
			<div
				style={{
					display: "flex",
					gap: "40px",
					alignItems: "flex-start",
				}}
			>
				{/* Sidebar — sticky 225px */}
				<aside
					style={{
						flex: "0 0 225px",
						position: "sticky",
						top: "24px",
						display: "flex",
						flexDirection: "column",
						gap: "32px",
					}}
				>
					{/* Sort radio group */}
					<div style={{ display: "flex", flexDirection: "column", gap: "12px" }}>
						<h4
							style={{
								fontSize: "13px",
								fontWeight: 700,
								color: "var(--fg-1)",
								margin: 0,
								textTransform: "uppercase",
								letterSpacing: "0.06em",
							}}
						>
							Sort by
						</h4>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="radio" name="sort" defaultChecked /> Newest
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="radio" name="sort" /> Popular
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="radio" name="sort" /> Price
						</label>
					</div>

					{/* Color filter */}
					<div style={{ display: "flex", flexDirection: "column", gap: "12px" }}>
						<h4
							style={{
								fontSize: "13px",
								fontWeight: 700,
								color: "var(--fg-1)",
								margin: 0,
								textTransform: "uppercase",
								letterSpacing: "0.06em",
							}}
						>
							Color
						</h4>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> Red
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> Blue
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> Green
						</label>
					</div>

					{/* Size filter */}
					<div style={{ display: "flex", flexDirection: "column", gap: "12px" }}>
						<h4
							style={{
								fontSize: "13px",
								fontWeight: 700,
								color: "var(--fg-1)",
								margin: 0,
								textTransform: "uppercase",
								letterSpacing: "0.06em",
							}}
						>
							Size
						</h4>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> S
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> M
						</label>
						<label style={{ display: "flex", gap: "8px", alignItems: "center", color: "var(--fg-1)" }}>
							<input type="checkbox" /> L
						</label>
					</div>
				</aside>

				{/* Content — fill */}
				<div style={{ flex: "1 1 auto", display: "flex", flexDirection: "column", gap: "40px" }}>
					<div
						style={{
							display: "grid",
							gridTemplateColumns: "repeat(auto-fill, minmax(220px, 1fr))",
							gap: "24px",
							rowGap: "40px",
						}}
					>
						{products.map((p) => (
							<article
								key={p.id}
								style={{
									display: "flex",
									flexDirection: "column",
									gap: "10px",
									background: "var(--bg-1)",
									borderRadius: "10px",
									overflow: "hidden",
								}}
							>
								<div style={{ aspectRatio: "3/4" }}>
									<img
										src={p.image}
										alt={p.title}
										width="320"
										height="426"
										style={{
											width: "100%",
											height: "100%",
											objectFit: "cover",
											display: "block",
										}}
									/>
								</div>
								<div style={{ padding: "0 10px 14px", display: "flex", flexDirection: "column", gap: "4px" }}>
									<h3
										style={{
											fontSize: "15px",
											fontWeight: 600,
											color: "var(--fg-1)",
											margin: 0,
										}}
									>
										{p.title}
									</h3>
									<span style={{ fontSize: "14px", color: "var(--fg-1)", fontWeight: 600 }}>
										{p.price}
									</span>
								</div>
							</article>
						))}
					</div>

					{/* Pagination */}
					<div
						style={{
							display: "flex",
							justifyContent: "center",
							gap: "12px",
						}}
					>
						<a
							href="#prev"
							style={{
								padding: "10px 18px",
								border: "1px solid var(--border-1)",
								borderRadius: "8px",
								color: "var(--fg-1)",
								textDecoration: "none",
							}}
						>
							Previous
						</a>
						<a
							href="#next"
							style={{
								padding: "10px 18px",
								border: "1px solid var(--border-1)",
								borderRadius: "8px",
								color: "var(--fg-1)",
								textDecoration: "none",
							}}
						>
							Next
						</a>
					</div>
				</div>
			</div>
		</section>
	);
}
