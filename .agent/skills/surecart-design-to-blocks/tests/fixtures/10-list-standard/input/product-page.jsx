// Fixture 10 — Product List Standard
// Header (title + sort + search + filter tags) + responsive grid + pagination.

const products = Array.from({ length: 9 }).map((_, i) => ({
	id: i + 1,
	title: `Bestseller ${i + 1}`,
	price: `$${(i + 2) * 12}`,
	image: `product-${i + 1}.jpg`,
	onSale: i % 3 === 0,
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
			{/* Header */}
			<div
				style={{
					display: "flex",
					flexDirection: "column",
					gap: "20px",
					marginBottom: "40px",
				}}
			>
				<div
					style={{
						display: "flex",
						justifyContent: "space-between",
						alignItems: "center",
						gap: "20px",
						flexWrap: "wrap",
					}}
				>
					<h2
						style={{
							fontSize: "32px",
							fontWeight: 700,
							color: "var(--fg-1)",
							margin: 0,
						}}
					>
						Shop Bestsellers
					</h2>

					<div style={{ display: "flex", gap: "12px", alignItems: "center" }}>
						<select
							aria-label="sort"
							style={{
								padding: "8px 12px",
								border: "1px solid var(--border-1)",
								borderRadius: "8px",
								background: "var(--bg-1)",
								color: "var(--fg-1)",
								fontSize: "14px",
							}}
						>
							<option>Newest</option>
							<option>Price: Low to High</option>
							<option>Price: High to Low</option>
							<option>Most Popular</option>
						</select>
						<input
							type="search"
							placeholder="Search products"
							style={{
								padding: "8px 12px",
								border: "1px solid var(--border-1)",
								borderRadius: "8px",
								background: "var(--bg-1)",
								color: "var(--fg-1)",
								fontSize: "14px",
								minWidth: "200px",
							}}
						/>
					</div>
				</div>

				{/* Filter tags */}
				<div style={{ display: "flex", gap: "8px", flexWrap: "wrap" }}>
					<button
						style={{
							padding: "6px 12px",
							borderRadius: "999px",
							border: "1px solid var(--border-1)",
							background: "var(--bg-1)",
							color: "var(--fg-1)",
							fontSize: "13px",
						}}
					>
						All
					</button>
					<button
						style={{
							padding: "6px 12px",
							borderRadius: "999px",
							border: "1px solid var(--border-1)",
							background: "var(--bg-1)",
							color: "var(--fg-1)",
							fontSize: "13px",
						}}
					>
						Apparel
					</button>
					<button
						style={{
							padding: "6px 12px",
							borderRadius: "999px",
							border: "1px solid var(--border-1)",
							background: "var(--bg-1)",
							color: "var(--fg-1)",
							fontSize: "13px",
						}}
					>
						Accessories
					</button>
					<button
						style={{
							padding: "6px 12px",
							borderRadius: "999px",
							border: "1px solid var(--border-1)",
							background: "var(--bg-1)",
							color: "var(--fg-1)",
							fontSize: "13px",
						}}
					>
						Home
					</button>
				</div>
			</div>

			{/* Grid (template — server iterates) */}
			<div
				style={{
					display: "grid",
					gridTemplateColumns: "repeat(auto-fill, minmax(260px, 1fr))",
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
							gap: "12px",
							background: "var(--bg-1)",
							borderRadius: "12px",
							overflow: "hidden",
						}}
					>
						<div style={{ position: "relative", aspectRatio: "3/4" }}>
							<img
								src={p.image}
								alt={p.title}
								width="400"
								height="533"
								style={{
									width: "100%",
									height: "100%",
									objectFit: "cover",
									display: "block",
								}}
							/>
							{p.onSale ? (
								<span
									style={{
										position: "absolute",
										top: "12px",
										left: "12px",
										background: "var(--sale-red)",
										color: "#FFFFFF",
										fontSize: "12px",
										padding: "4px 10px",
										borderRadius: "999px",
										fontWeight: 600,
									}}
								>
									Sale
								</span>
							) : null}
						</div>

						<div style={{ padding: "0 12px 16px", display: "flex", flexDirection: "column", gap: "4px" }}>
							<h3
								style={{
									fontSize: "16px",
									fontWeight: 600,
									color: "var(--fg-1)",
									margin: 0,
								}}
							>
								{p.title}
							</h3>
							<div style={{ display: "flex", gap: "0.5em", alignItems: "baseline" }}>
								<span style={{ fontSize: "15px", fontWeight: 600, color: "var(--fg-1)" }}>
									{p.price}
								</span>
							</div>
						</div>
					</article>
				))}
			</div>

			{/* Empty fallback */}
			<div style={{ marginTop: "40px", textAlign: "center", color: "var(--fg-2)" }}>
				<p data-no-products>No products found.</p>
			</div>

			{/* Pagination */}
			<div
				style={{
					marginTop: "60px",
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
		</section>
	);
}
