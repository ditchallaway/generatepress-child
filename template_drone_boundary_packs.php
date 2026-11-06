<?php
/**
 * Template Name: Irresistible Offer — Drone Style (Land & Ranch)
 * Description: Jobs-to-be-Done conversion landing page (Pedro Cortes 9-Section Framework) for Photorealistic Drone Style Boundary 2-Pack & 6-Pack.
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme_uri = get_stylesheet_directory_uri();

add_filter( 'generate_sidebar_layout', function() {
	return 'no-sidebar';
} );

get_header(); ?>

<style id="rp-jtbd-offer-styles">
/* ================================================================
   ReadyPlat JTBD Irresistible Offer Layout (Pedro Cortes Framework)
   ================================================================ */
.rp-offer-page {
	color: var(--btx-text-main);
	background-color: var(--btx-bg-page);
	line-height: 1.6;
}

.rp-hero {
	padding: var(--btx-space-12) 0 var(--btx-space-10);
	background: linear-gradient(180deg, #ffffff 0%, var(--btx-gray-50) 100%);
	border-bottom: 1px solid var(--btx-border-color);
	text-align: center;
}

.rp-eyebrow {
	display: inline-flex;
	align-items: center;
	gap: var(--btx-space-2);
	padding: 0.4rem 1rem;
	background-color: var(--btx-color-primary-50);
	color: var(--btx-color-primary-700);
	border: 1px solid var(--btx-color-primary-200);
	border-radius: var(--btx-radius-full);
	font-size: var(--btx-text-xs);
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.06em;
	margin-bottom: var(--btx-space-4);
}

.rp-hero-title {
	font-size: clamp(2rem, 4.2vw + 0.75rem, 3.25rem);
	font-weight: 800;
	color: var(--btx-gray-900);
	line-height: 1.15;
	max-width: 900px;
	margin: 0 auto var(--btx-space-4);
	letter-spacing: -0.025em;
}

.rp-hero-subtitle {
	font-size: var(--btx-text-lg);
	color: var(--btx-gray-600);
	max-width: 740px;
	margin: 0 auto var(--btx-space-6);
	line-height: 1.6;
}

.rp-ctv-stack {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: var(--btx-space-3);
	margin-bottom: var(--btx-space-10);
}

.rp-btn-row {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--btx-space-4);
	flex-wrap: wrap;
}

.rp-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 0.95rem 1.85rem;
	font-size: var(--btx-text-base);
	font-weight: 700;
	border-radius: var(--btx-radius-md);
	text-decoration: none !important;
	transition: all var(--btx-transition-fast);
	cursor: pointer;
	line-height: 1.25;
}

.rp-btn--primary {
	background-color: var(--btx-color-primary-600);
	color: #ffffff !important;
	box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.35);
	border: 1px solid transparent;
}

.rp-btn--primary:hover {
	background-color: var(--btx-color-primary-700);
	transform: translateY(-2px);
}

.rp-btn--secondary {
	background-color: #ffffff;
	color: var(--btx-gray-800) !important;
	border: 1px solid var(--btx-gray-300);
	box-shadow: var(--btx-shadow-sm);
}

.rp-btn--secondary:hover {
	background-color: var(--btx-gray-100);
	border-color: var(--btx-gray-400);
}

.rp-micro-proof {
	font-size: var(--btx-text-xs);
	color: var(--btx-gray-500);
	font-weight: 600;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: var(--btx-space-3);
	flex-wrap: wrap;
}

/* Visual Aha Showcase */
.rp-aha-box {
	max-width: 1020px;
	margin: 0 auto;
	background: #ffffff;
	border: 1px solid var(--btx-gray-200);
	border-radius: var(--btx-radius-xl);
	padding: var(--btx-space-6);
	box-shadow: var(--btx-shadow-xl);
	text-align: left;
}

.rp-aha-grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: var(--btx-space-6);
	align-items: center;
}

@media (min-width: 768px) {
	.rp-aha-grid {
		grid-template-columns: 1fr auto 1.25fr;
	}
}

.rp-aha-card {
	border-radius: var(--btx-radius-lg);
	overflow: hidden;
	border: 1px solid var(--btx-border-color);
	background: var(--btx-gray-50);
	position: relative;
}

.rp-aha-card--after {
	border: 2px solid var(--btx-color-primary-600);
	box-shadow: var(--btx-shadow-md);
}

.rp-aha-badge {
	position: absolute;
	top: 12px;
	left: 12px;
	padding: 0.25rem 0.65rem;
	font-size: 0.7rem;
	font-weight: 700;
	text-transform: uppercase;
	border-radius: var(--btx-radius-sm);
	z-index: 2;
}

.rp-aha-badge--before {
	background: rgba(153, 27, 27, 0.9);
	color: #ffffff;
}

.rp-aha-badge--after {
	background: var(--btx-color-primary-600);
	color: #ffffff;
}

.rp-aha-img {
	width: 100%;
	height: 240px;
	object-fit: cover;
	display: block;
}

.rp-aha-caption {
	padding: var(--btx-space-3) var(--btx-space-4);
	font-size: var(--btx-text-xs);
	color: var(--btx-gray-700);
	background: #ffffff;
	border-top: 1px solid var(--btx-border-color);
}

.rp-aha-arrow {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	color: var(--btx-color-primary-600);
	font-weight: 800;
	font-size: var(--btx-text-xs);
	gap: 0.25rem;
}

.rp-aha-arrow span.circle {
	width: 44px;
	height: 44px;
	border-radius: var(--btx-radius-full);
	background: var(--btx-color-primary-50);
	border: 1px solid var(--btx-color-primary-200);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 1.25rem;
}

/* Authority Strip */
.rp-authority {
	background: #ffffff;
	border-bottom: 1px solid var(--btx-border-color);
	padding: var(--btx-space-6) 0;
}

.rp-authority-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--btx-space-4);
	text-align: center;
}

@media (min-width: 768px) {
	.rp-authority-grid {
		grid-template-columns: repeat(4, 1fr);
	}
}

.rp-stat-val {
	font-size: var(--btx-text-2xl);
	font-weight: 800;
	color: var(--btx-gray-900);
	line-height: 1.1;
}

.rp-stat-lbl {
	font-size: var(--btx-text-xs);
	color: var(--btx-text-muted);
	font-weight: 500;
	margin-top: 0.2rem;
}

/* Sections */
.rp-section {
	padding: var(--btx-space-12) 0;
}

.rp-section--alt {
	background-color: var(--btx-gray-100);
}

.rp-section--dark {
	background-color: var(--btx-gray-900);
	color: #ffffff;
}

.rp-header {
	text-align: center;
	max-width: 760px;
	margin: 0 auto var(--btx-space-10);
}

.rp-tag {
	color: var(--btx-color-primary-600);
	font-weight: 700;
	font-size: var(--btx-text-xs);
	text-transform: uppercase;
	letter-spacing: 0.08em;
	margin-bottom: var(--btx-space-2);
}

.rp-title {
	font-size: var(--btx-text-3xl);
	font-weight: 800;
	color: var(--btx-gray-900);
	margin: 0 0 var(--btx-space-3);
	line-height: 1.2;
}

.rp-section--dark .rp-title {
	color: #ffffff;
}

.rp-desc {
	font-size: var(--btx-text-base);
	color: var(--btx-text-muted);
	margin: 0;
}

.rp-section--dark .rp-desc {
	color: var(--btx-gray-300);
}

/* JTBD Split Offer Cards */
.rp-jtbd-grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: var(--btx-space-8);
	max-width: 1020px;
	margin: 0 auto;
}

@media (min-width: 768px) {
	.rp-jtbd-grid {
		grid-template-columns: repeat(2, 1fr);
	}
}

.rp-offer-card {
	background: #ffffff;
	border: 1px solid var(--btx-border-color);
	border-radius: var(--btx-radius-xl);
	padding: var(--btx-space-8);
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	position: relative;
	box-shadow: var(--btx-shadow-md);
}

.rp-offer-card--featured {
	border: 2px solid var(--btx-color-primary-600);
	box-shadow: var(--btx-shadow-xl);
	background: linear-gradient(180deg, #ffffff 0%, #faf5ff 100%);
}

.rp-offer-pill {
	position: absolute;
	top: -14px;
	right: 24px;
	background: var(--btx-color-primary-600);
	color: #ffffff;
	font-size: 0.72rem;
	font-weight: 800;
	text-transform: uppercase;
	padding: 0.3rem 0.85rem;
	border-radius: var(--btx-radius-full);
	letter-spacing: 0.05em;
}

.rp-jtbd-who {
	display: inline-block;
	font-size: var(--btx-text-xs);
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	color: var(--btx-color-primary-700);
	background: var(--btx-color-primary-50);
	padding: 0.25rem 0.65rem;
	border-radius: var(--btx-radius-sm);
	margin-bottom: var(--btx-space-3);
}

.rp-offer-name {
	font-size: var(--btx-text-2xl);
	font-weight: 800;
	color: var(--btx-gray-900);
	margin: 0 0 var(--btx-space-2);
}

.rp-jtbd-quote {
	font-size: var(--btx-text-sm);
	font-weight: 600;
	color: var(--btx-gray-800);
	background: var(--btx-gray-100);
	border-left: 3px solid var(--btx-color-primary-600);
	padding: var(--btx-space-3);
	border-radius: 0 var(--btx-radius-sm) var(--btx-radius-sm) 0;
	margin-bottom: var(--btx-space-4);
}

.rp-offer-story {
	font-size: var(--btx-text-sm);
	color: var(--btx-gray-600);
	margin-bottom: var(--btx-space-5);
}

.rp-price-row {
	display: flex;
	align-items: baseline;
	gap: var(--btx-space-2);
	margin-bottom: var(--btx-space-2);
}

.rp-price-num {
	font-size: 2.75rem;
	font-weight: 800;
	color: var(--btx-gray-900);
	line-height: 1;
}

.rp-price-sub {
	font-size: var(--btx-text-sm);
	color: var(--btx-text-muted);
}

.rp-speed-badge {
	display: inline-block;
	font-size: var(--btx-text-xs);
	font-weight: 700;
	color: var(--btx-color-success);
	background: var(--btx-color-success-bg);
	padding: 0.2rem 0.6rem;
	border-radius: var(--btx-radius-sm);
	margin-bottom: var(--btx-space-5);
}

.rp-deliverables {
	list-style: none;
	padding: 0;
	margin: 0 0 var(--btx-space-6);
	border-top: 1px solid var(--btx-gray-200);
	padding-top: var(--btx-space-4);
}

.rp-deliverables li {
	display: flex;
	align-items: flex-start;
	gap: var(--btx-space-2);
	font-size: var(--btx-text-sm);
	color: var(--btx-gray-700);
	margin-bottom: var(--btx-space-3);
}

.rp-deliverables li strong {
	color: var(--btx-gray-900);
}

.rp-check {
	color: var(--btx-color-primary-600);
	font-weight: 800;
	flex-shrink: 0;
}

/* Angle Preview Grid */
.rp-angle-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--btx-space-4);
}

@media (min-width: 768px) {
	.rp-angle-grid {
		grid-template-columns: repeat(4, 1fr);
	}
}

.rp-angle-item {
	background: #ffffff;
	border: 1px solid var(--btx-border-color);
	border-radius: var(--btx-radius-md);
	overflow: hidden;
}

.rp-angle-item img {
	width: 100%;
	height: 150px;
	object-fit: cover;
	display: block;
}

.rp-angle-caption {
	padding: var(--btx-space-2) var(--btx-space-3);
	font-size: var(--btx-text-xs);
	font-weight: 700;
	color: var(--btx-gray-800);
	display: flex;
	justify-content: space-between;
}

/* Comparison Table */
.rp-table {
	width: 100%;
	border-collapse: collapse;
	background: #ffffff;
	border-radius: var(--btx-radius-lg);
	overflow: hidden;
	box-shadow: var(--btx-shadow-sm);
	border: 1px solid var(--btx-border-color);
}

.rp-table th,
.rp-table td {
	padding: var(--btx-space-4);
	text-align: left;
	border-bottom: 1px solid var(--btx-border-color);
	font-size: var(--btx-text-sm);
}

.rp-table th {
	background: var(--btx-gray-50);
	font-weight: 800;
	color: var(--btx-gray-900);
}

.rp-table td.highlight,
.rp-table th.highlight {
	background: var(--btx-color-primary-50);
	color: var(--btx-color-primary-900);
	font-weight: 700;
}

/* Switcher & Lead Magnet Banners */
.rp-switcher-box {
	background: #ffffff;
	border: 2px dashed var(--btx-color-primary-300);
	border-radius: var(--btx-radius-lg);
	padding: var(--btx-space-6);
	margin: var(--btx-space-10) auto 0;
	max-width: 860px;
	text-align: center;
}

.rp-faq-wrap {
	max-width: 780px;
	margin: 0 auto;
	display: flex;
	flex-direction: column;
	gap: var(--btx-space-4);
}
</style>

<div id="primary" class="content-area rp-offer-page">
	<main id="main" class="site-main">

		<!-- ============================================================
		     1. HERO SECTION (ABOVE THE FOLD)
		     ============================================================ -->
		<section class="rp-hero">
			<div class="btx-container btx-container--xl">
				<div class="rp-eyebrow">
					Flavor 1: Photorealistic Drone Style &bull; Best for Land, Ranch &amp; Acreage Listings
				</div>

				<h1 class="rp-hero-title">
					Start Selling Your Land Listing Today—Without Waiting on a Graphic Designer or a $500 Drone Pilot
				</h1>

				<p class="rp-hero-subtitle">
					You are a salesperson, and you can start selling the minute your listing has photos. Whether you just need a clean boundary graphic to explain what is for sale ($49) or a full 6-angle drone shoot replacement right now ($99), get MLS-ready parcel images in under 5 minutes.
				</p>

				<div class="rp-ctv-stack">
					<div class="rp-btn-row">
						<a href="#jtbd-offers" class="rp-btn rp-btn--primary">
							Pick Your Job-To-Be-Done: 2-Pack ($49) or 6-Pack ($99) &darr;
						</a>
						<a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--secondary">
							Test Any Parcel First: Free Google Earth KML (10 Sec)
						</a>
					</div>
					<div class="rp-micro-proof">
						<span>&#10003; Confirm exact county parcel lines on the map before paying</span>
						<span>&bull;</span>
						<span>&#10003; Delivered in 2&ndash;5 minutes</span>
						<span>&bull;</span>
						<span>&#10003; Unbranded &amp; 100% MLS compliant</span>
					</div>
				</div>

				<!-- Visual "Aha Moment" Transformation -->
				<div class="rp-aha-box">
					<div class="rp-aha-grid">
						<div class="rp-aha-card">
							<span class="rp-aha-badge rp-aha-badge--before">Without Boundary Graphics: Just a Picture of Trees</span>
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/before.jpg' ); ?>" alt="Raw unmarked satellite map of land parcel" class="rp-aha-img">
							<div class="rp-aha-caption">
								<strong>Why buyers scroll past:</strong> They can't tell where the property starts, where the road frontage is, or why they should care.
							</div>
						</div>

						<div class="rp-aha-arrow">
							<span class="circle">&rarr;</span>
							<span>2&ndash;5 Mins</span>
						</div>

						<div class="rp-aha-card rp-aha-card--after">
							<span class="rp-aha-badge rp-aha-badge--after">ReadyPlat Photorealistic Drone Style</span>
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/hero-aerial.jpg' ); ?>" alt="Photorealistic 3D drone style property boundary image" class="rp-aha-img">
							<div class="rp-aha-caption">
								<strong>Instant buyer clarity:</strong> Crisp county-verified boundary lines hugged to real 3D terrain, timber, ponds, and road access.
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     2. INITIAL SOCIAL PROOF (AUTHORITY STRIP)
		     ============================================================ -->
		<section class="rp-authority">
			<div class="btx-container btx-container--xl">
				<div class="rp-authority-grid">
					<div>
						<div class="rp-stat-val">2&ndash;5 Mins</div>
						<div class="rp-stat-lbl">From Address/APN to Ready Listing Photos</div>
					</div>
					<div>
						<div class="rp-stat-val">3,140+ Counties</div>
						<div class="rp-stat-lbl">Official US Tax Assessor Parcel Boundaries</div>
					</div>
					<div>
						<div class="rp-stat-val">$400+ Saved</div>
						<div class="rp-stat-lbl">Per Listing vs. Rural Drone Pilot Dispatch</div>
					</div>
					<div>
						<div class="rp-stat-val">0 Airspace Locks</div>
						<div class="rp-stat-lbl">Works Near Airports, Ranches &amp; Remote Tracts</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     3. THE PROBLEM & STATUS QUO (WHY BUYING THIS WAS HARD UNTIL NOW)
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--xl">
				<div class="rp-header">
					<div class="rp-tag">The Status Quo Bottleneck</div>
					<h2 class="rp-title">Other Agents Show Crisp Boundary Lines—Why Is It So Hard to Buy?</h2>
					<p class="rp-desc">
						You see top listings using clean aerial boundary graphics, and you know your listing needs photos before you can start selling. Until now, getting them meant hitting one of two walls:
					</p>
				</div>

				<div class="btx-grid btx-grid--cols-2" style="max-width: 960px; margin: 0 auto;">
					<div class="btx-card" style="border-top: 4px solid #dc2626;">
						<span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #991b1b; background: #fee2e2; padding: 3px 8px; border-radius: 4px;">Wall #1: The Graphic Designer Gap (Group A)</span>
						<h3 class="btx-heading-3" style="margin-top: 0.75rem;">No In-House Designer, Tech-Savvy Coordinator, or Family Member to Call</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem;">
							If you just need one or two boundary images to explain what's for sale in your listing photos, pitch presentation, or flyer, where do you even buy that? Unless you have an in-house designer or a clever family member good with Photoshop, you're stuck drawing shaky lines on your phone that look amateur and get flagged by the MLS.
						</p>
					</div>

					<div class="btx-card" style="border-top: 4px solid #dc2626;">
						<span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #991b1b; background: #fee2e2; padding: 3px 8px; border-radius: 4px;">Wall #2: The Drone Pilot Bottleneck (Group B)</span>
						<h3 class="btx-heading-3" style="margin-top: 0.75rem;">Too Expensive for the Margin, Too Far for a Pilot, or Locked by Airspace</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem;">
							Sometimes commission on the list price leaves too narrow a margin for a $450 drone shoot. Maybe the parcel sits near an airport no-fly zone, or it's so rural that drone pilots charge hefty travel fees and make you wait a week. Meanwhile, your listing sits without photos when you could be selling right now.
						</p>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     4. THE IRRESISTIBLE OFFER: 2 JOBS TO BE DONE (GROUP A vs GROUP B)
		     ============================================================ -->
		<section id="jtbd-offers" class="rp-section rp-section--alt">
			<div class="btx-container btx-container--xl">
				<div class="rp-header">
					<div class="rp-tag">Simple, Straightforward Purchase</div>
					<h2 class="rp-title">Hire the Exact Package for the Job You Need Done Today</h2>
					<p class="rp-desc">
						Both packages use our <strong>Photorealistic Drone Style</strong>—showing real trees, pasture, topography, and road access—and let you confirm your exact county parcel boundary on the map before you pay.
					</p>
				</div>

				<div class="rp-jtbd-grid">

					<!-- ====================================================
					     GROUP A OFFER: THE BOUNDARY 2-PACK ($49)
					     ==================================================== -->
					<div class="rp-offer-card">
						<div>
							<span class="rp-jtbd-who">Group A &bull; The "Explain What's For Sale" Job</span>
							<h3 class="rp-offer-name">Property Boundary 2-Pack</h3>

							<div class="rp-jtbd-quote">
								"I just want to hire a clean property boundary image to clearly show what is for sale and why buyers should care—for my listing photos, listing presentation, or flyer."
							</div>

							<p class="rp-offer-story">
								You don't need an in-house graphic designer or a clever listing coordinator anymore. In 2 minutes, you get two crisp, high-resolution overhead boundary images ready to drop straight into the MLS, Canva flyers, or your seller pitch book.
							</p>

							<div class="rp-price-row">
								<span class="rp-price-num">$49</span>
								<span class="rp-price-sub">/ parcel &bull; one-time flat fee</span>
							</div>
							<div class="rp-speed-badge">&#9889; Ready in 2 Minutes &bull; No Designer Needed</div>

							<ul class="rp-deliverables">
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>1x Close-Up Boundary Aerial (A1):</strong> Top-down photorealistic view framed tightly around your entire property line so buyers immediately see the shape and footprint.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>1x Wide Context Aerial (A2):</strong> Zoomed-out view showing why they should care—highlighting road frontage, highway access, and neighboring land.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Verified County Parcel Lines:</strong> Pulled directly from county tax records and confirmed by you on our map before checkout.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Unbranded &amp; MLS-Compliant:</strong> High-res JPGs that never get flagged for watermarks or text overlays.</span>
								</li>
							</ul>
						</div>

						<a href="https://readyplat.com/map?cf6ro4a2lm=c88d107b-bce8-4a78-a98d-d4cd62e671b2&cfd3r3gjpt=3cac6d12-f07a-4637-97eb-4ffee4731a28" class="rp-btn rp-btn--primary btx-btn--block">
							Confirm Parcel on Map &mdash; Get Boundary 2-Pack ($49) &rarr;
						</a>
					</div>

					<!-- ====================================================
					     GROUP B OFFER: THE PROPERTY BOUNDARY 6-PACK ($99)
					     ==================================================== -->
					<div class="rp-offer-card rp-offer-card--featured">
						<span class="rp-offer-pill">New Category &bull; Instant Drone Shoot Replacement</span>
						<div>
							<span class="rp-jtbd-who">Group B &bull; The "Start Selling Right Now" Job</span>
							<h3 class="rp-offer-name">Property Boundary 6-Pack</h3>

							<div class="rp-jtbd-quote">
								"I want all the benefits of sending a drone pilot to do a shoot and adding boundary lines—but I want the images right now so I can start selling today, without the steep price tag."
							</div>

							<p class="rp-offer-story">
								Built for salespeople who don't let narrow commission margins, airport no-fly zones, or remote rural travel fees slow down a listing. Get a complete 6-image aerial gallery from every compass direction in 5 minutes and go live on the MLS today.
							</p>

							<div class="rp-price-row">
								<span class="rp-price-num">$99</span>
								<span class="rp-price-sub">/ parcel &bull; replaces a $450+ drone trip</span>
							</div>
							<div class="rp-speed-badge">&#9889; Full 6-Photo Shoot Ready in 5 Minutes</div>

							<ul class="rp-deliverables">
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Includes Both Overhead Aerials (A1 + A2):</strong> Close-up parcel boundary shot plus wide neighborhood context shot.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Plus 4x Angled 3D Drone Views (North, East, South, West):</strong> Photorealistic 3D oblique angles looking across the property from all four cardinal directions.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>True Terrain &amp; Elevation:</strong> Boundary lines hug slopes, ridges, creeks, and tree lines just like a custom-edited drone shoot.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Solves Rural, Airport &amp; Margin Constraints:</strong> Zero travel fees, zero FAA airspace waivers, and 80% less cost.</span>
								</li>
							</ul>
						</div>

						<a href="https://readyplat.com/map?cf6ro4a2lm=bd0197ce-24d8-459e-b46d-1ab48c9d5bc4&cfd3r3gjpt=df31d8f8-6168-4024-b713-6e882421fd6d" class="rp-btn rp-btn--primary btx-btn--block">
							Confirm Parcel on Map &mdash; Get Complete 6-Pack ($99) &rarr;
						</a>
					</div>

				</div>

				<!-- Visual Proof of What's Inside the 6-Pack -->
				<div style="max-width: 1020px; margin: var(--btx-space-10) auto 0; background: #ffffff; border: 1px solid var(--btx-border-color); border-radius: var(--btx-radius-lg); padding: var(--btx-space-6);">
					<h4 style="margin: 0 0 var(--btx-space-4); font-size: var(--btx-text-lg); font-weight: 800; text-align: center;">
						What You Get in 5 Minutes: Multi-Angle Drone Views + Exact Boundary Lines
					</h4>
					<div class="rp-angle-grid">
						<div class="rp-angle-item">
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/overhead.jpg' ); ?>" alt="Top-Down Overhead Boundary View">
							<div class="rp-angle-caption">
								<span>A1/A2: Top-Down Overheads</span>
								<span style="color: var(--btx-color-primary-600);">2-Pack &amp; 6-Pack</span>
							</div>
						</div>
						<div class="rp-angle-item">
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/north.jpg' ); ?>" alt="North 3D Drone Angle">
							<div class="rp-angle-caption">
								<span>A3: North 3D Drone View</span>
								<span style="color: var(--btx-color-primary-600);">6-Pack</span>
							</div>
						</div>
						<div class="rp-angle-item">
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/south.jpg' ); ?>" alt="South 3D Drone Angle">
							<div class="rp-angle-caption">
								<span>A5: South 3D Drone View</span>
								<span style="color: var(--btx-color-primary-600);">6-Pack</span>
							</div>
						</div>
						<div class="rp-angle-item">
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/west.jpg' ); ?>" alt="West 3D Drone Angle">
							<div class="rp-angle-caption">
								<span>A6: West 3D Drone View</span>
								<span style="color: var(--btx-color-primary-600);">6-Pack</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Flavor Router to Site Render Page -->
				<div class="rp-switcher-box">
					<h4 style="margin: 0 0 0.5rem; font-size: var(--btx-text-xl); font-weight: 800;">
						Listing a Commercial Property, City Lot, or Muddy Construction Parcel?
					</h4>
					<p style="margin: 0 0 1.25rem; color: var(--btx-gray-600); font-size: var(--btx-text-sm); max-width: 680px; margin-left: auto; margin-right: auto;">
						Photorealistic Drone Style is best for land and ranch listings. When a parcel's value is in its <strong>location</strong> and showing actual dirt, mud, or construction debris is a distraction from the offer, use our <strong>Illustrated Site Render Style</strong> instead.
					</p>
					<a href="<?php echo esc_url( home_url( '/site-render-boundaries/' ) ); ?>" class="rp-btn rp-btn--secondary">
						See Flavor 2: Illustrated Site Render 2-Pack &amp; 6-Pack &rarr;
					</a>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     5. THE SOLUTION & MECHANISM (HOW IT WORKS IN 3 CLICKS)
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--lg">
				<div class="rp-header">
					<div class="rp-tag">Zero Graphic Design or GIS Skills Needed</div>
					<h2 class="rp-title">How You Go From Address to MLS-Ready Images in 3 Steps</h2>
					<p class="rp-desc">
						No back-and-forth emails with freelancers, no software subscriptions, and no guessing if the lines are right.
					</p>
				</div>

				<div class="btx-grid btx-grid--cols-3">
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 1</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Find Your Parcel on the Map</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							Type the street address or county parcel number (APN)—or simply pan and click the property on our interactive satellite map.
						</p>
					</div>
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 2</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Confirm the Boundary Before Paying</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							We highlight the official county tax polygon right on your screen. You verify the exact shape and APN before entering payment.
						</p>
					</div>
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 3</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Download &amp; Start Selling</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							Your 2-Pack (2 mins) or 6-Pack (5 mins) renders automatically. Download individual high-res JPGs or a 1-click ZIP for the MLS.
						</p>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     6. PERCEIVED SUPERIORITY (CONTRAST TABLE)
		     ============================================================ -->
		<section class="rp-section rp-section--alt">
			<div class="btx-container btx-container--lg">
				<div class="rp-header">
					<div class="rp-tag">Why Agents Switch</div>
					<h2 class="rp-title">Compare Your Options for Getting Boundary Listing Photos</h2>
				</div>

				<div style="overflow-x: auto;">
					<table class="rp-table">
						<thead>
							<tr>
								<th>What Matters to You</th>
								<th>DIY / Asking a Family Member</th>
								<th>Hiring a Local Drone Pilot</th>
								<th class="highlight">ReadyPlat 2-Pack ($49) &amp; 6-Pack ($99)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><strong>How fast can I start selling?</strong></td>
								<td>Hours of frustration in Canva</td>
								<td>3 to 7 days (weather &amp; scheduling)</td>
								<td class="highlight">Today (Ready in 2 to 5 minutes)</td>
							</tr>
							<tr>
								<td><strong>What does it cost my margin?</strong></td>
								<td>Your evening + compliance headaches</td>
								<td>$350 to $600+ (plus rural mileage)</td>
								<td class="highlight">$49 (2-Pack) or $99 (Full 6-Pack)</td>
							</tr>
							<tr>
								<td><strong>Near an airport or rural area?</strong></td>
								<td>Flat 2D screenshot only</td>
								<td>Grounded by FAA / Pilots won't drive out</td>
								<td class="highlight">Works on any US parcel with zero flight limits</td>
							</tr>
							<tr>
								<td><strong>Boundary line accuracy</strong></td>
								<td>Hand-drawn guesswork over trees</td>
								<td>Editor eyeballs fence lines</td>
								<td class="highlight">Locked to official County Assessor GIS</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     7. RELATABLE, OUTCOME-DRIVEN SOCIAL PROOF
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--xl">
				<div class="rp-header">
					<div class="rp-tag">Built for Real Estate Salespeople</div>
					<h2 class="rp-title">When Agents Hire the 2-Pack vs. the 6-Pack</h2>
				</div>

				<div class="btx-grid btx-grid--cols-3">
					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group A &bull; Hired the $49 2-Pack</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"I don't have an in-house graphic designer, and I was tired of asking my nephew to draw property lines on my listing photos. For $49, I got a tight boundary shot and a wide road-frontage shot in 2 minutes for my listing presentation and flyer."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>Sarah Jenkins</strong><br>
							<span class="btx-text-muted">Residential &amp; Acreage Agent</span>
						</div>
					</div>

					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group B &bull; Hired the $99 6-Pack (Rural Land)</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"I listed a 35-acre ranch 60 miles from town with a tight commission margin. Drone pilots wanted $550 and a week to drive out. I grabbed the $99 6-Pack, got North, South, East, and West 3D drone views in 5 minutes, and had the listing live that afternoon."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>Marcus Vance</strong><br>
							<span class="btx-text-muted">Land &amp; Ranch Broker</span>
						</div>
					</div>

					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group B &bull; Hired the $99 6-Pack (Airport Zone)</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"Our 4-acre listing sat inside airport airspace where my normal drone photographer couldn't fly without a 30-day waiver. ReadyPlat gave me all 6 angles with boundary lines immediately so we didn't miss the weekend buyer rush."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>David K. Miller</strong><br>
							<span class="btx-text-muted">Associate Broker</span>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     8. FINAL CALL TO VALUE & RISK REVERSAL
		     ============================================================ -->
		<section class="rp-section rp-section--dark" style="text-align: center;">
			<div class="btx-container btx-container--lg">
				<h2 class="rp-title">Your Listing Needs Images So You Can Start Selling. Get Them Right Now.</h2>
				<p class="rp-desc" style="margin-bottom: var(--btx-space-6);">
					Verify your exact parcel boundary on our interactive map before you pay. No subscriptions, no waiting on weather, and no designer needed.
				</p>
				<div class="rp-btn-row" style="margin-bottom: var(--btx-space-4);">
					<a href="https://readyplat.com/map?cf6ro4a2lm=bd0197ce-24d8-459e-b46d-1ab48c9d5bc4&cfd3r3gjpt=df31d8f8-6168-4024-b713-6e882421fd6d" class="rp-btn rp-btn--primary">
						Get the Complete 6-Pack Drone Shoot Replacement ($99) &rarr;
					</a>
					<a href="https://readyplat.com/map?cf6ro4a2lm=c88d107b-bce8-4a78-a98d-d4cd62e671b2&cfd3r3gjpt=3cac6d12-f07a-4637-97eb-4ffee4731a28" class="rp-btn rp-btn--secondary">
						Get the Boundary 2-Pack ($49) &rarr;
					</a>
				</div>
				<div class="rp-micro-proof" style="color: var(--btx-gray-400);">
					<span>&#10003; Visual APN confirmation before checkout</span>
					<span>&bull;</span>
					<span>&#10003; Ready in 2&ndash;5 minutes</span>
					<span>&bull;</span>
					<span>&#10003; Human editor backup if your parcel is complex</span>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     9. OBJECTION-BUSTING FAQ & LOW-FRICTION KML LEAD MAGNET
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--lg">
				<div class="rp-header">
					<div class="rp-tag">Quick Answers</div>
					<h2 class="rp-title">Frequently Asked Questions</h2>
				</div>

				<div class="rp-faq-wrap">
					<div class="btx-card">
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">How do I decide between the $49 2-Pack and the $99 6-Pack?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							Hire the <strong>$49 Boundary 2-Pack</strong> if you already have ground photos and just need two crisp overhead boundary graphics (tight view + wide road context view) for your listing gallery, flyer, or listing presentation. Hire the <strong>$99 Boundary 6-Pack</strong> when you want the full benefit of sending a drone pilot—giving you the 2 overheads plus 4 angled 3D views (North, East, South, West) so you can put the listing on the market immediately.
						</p>
					</div>

					<div class="btx-card">
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">Should I choose Drone Style or Site Render Style?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							<strong>Photorealistic Drone Style (this page)</strong> is best for land, ranch, and recreational acreage where buyers want to see real trees, pasture, creeks, and terrain. If you are listing a commercial property, city infill lot, or construction site where dirt, mud, or debris distracts from the location value, <a href="<?php echo esc_url( home_url( '/site-render-boundaries/' ) ); ?>">switch to our Illustrated Site Render Style</a>.
						</p>
					</div>

					<div class="btx-card">
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">How do I know the boundary lines will match my listing?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							Before you pay a dime, our map highlights the official county tax assessor polygon for your address or APN. You visually confirm that it's the exact parcel you are selling before checking out.
						</p>
					</div>
				</div>

				<!-- Free KML Lead Magnet -->
				<div class="rp-switcher-box" style="background: var(--btx-color-primary-50); border-color: var(--btx-color-primary-300);">
					<h4 style="margin: 0 0 0.5rem; font-size: var(--btx-text-lg); font-weight: 800;">
						Want to Test a Parcel First for $0? Download the Free Google Earth KML.
					</h4>
					<p style="margin: 0 0 1.25rem; color: var(--btx-gray-700); font-size: var(--btx-text-sm);">
						Enter any US address or APN and download the official county boundary polygon file in 10 seconds to fly around in 3D on Google Earth.
					</p>
					<a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
						Download Free Parcel KML ($0) &rarr;
					</a>
				</div>
			</div>
		</section>

	</main>
</div>

<?php
get_footer();

