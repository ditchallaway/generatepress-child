<?php
/**
 * Template Name: Irresistible Offer — Site Render Style (Commercial & City Lots)
 * Description: Jobs-to-be-Done conversion landing page (Pedro Cortes 9-Section Framework) for Illustrated Site Render Boundary 2-Pack & 6-Pack.
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

<style id="rp-jtbd-render-offer-styles">
/* ================================================================
   ReadyPlat JTBD Irresistible Offer Layout — Site Render Flavor
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
	max-width: 920px;
	margin: 0 auto var(--btx-space-4);
	letter-spacing: -0.025em;
}

.rp-hero-subtitle {
	font-size: var(--btx-text-lg);
	color: var(--btx-gray-600);
	max-width: 750px;
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
					Flavor 2: Illustrated Site Render Style &bull; Best for Commercial, City Lots &amp; Infill
				</div>

				<h1 class="rp-hero-title">
					When the Value Is in the Location, Don't Let Dirt, Mud, or Debris Distract From Your Offer
				</h1>

				<p class="rp-hero-subtitle">
					For commercial pads, city infill lots, and development parcels, raw drone photos of weeds and construction clutter hurt buyer perception. Get clean, illustrated property boundary graphics in 2 to 5 minutes—without hiring an in-house graphic designer ($49) or a $600 commercial drone crew ($99).
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
						<span>&#10003; Ready in 2&ndash;5 minutes</span>
						<span>&bull;</span>
						<span>&#10003; LoopNet, Crexi, MLS &amp; Flyer Ready</span>
					</div>
				</div>

				<!-- Visual "Aha Moment" Transformation -->
				<div class="rp-aha-box">
					<div class="rp-aha-grid">
						<div class="rp-aha-card">
							<span class="rp-aha-badge rp-aha-badge--before">Raw Satellite / Drone: Mud, Weeds &amp; Clutter</span>
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/before.jpg' ); ?>" alt="Cluttered raw satellite photo of lot" class="rp-aha-img">
							<div class="rp-aha-caption">
								<strong>What distracts buyers:</strong> Patchy dirt, mud ruts, construction debris, and messy adjacent roofs pull focus away from the location.
							</div>
						</div>

						<div class="rp-aha-arrow">
							<span class="circle">&rarr;</span>
							<span>2&ndash;5 Mins</span>
						</div>

						<div class="rp-aha-card rp-aha-card--after">
							<span class="rp-aha-badge rp-aha-badge--after">ReadyPlat Illustrated Site Render Style</span>
							<img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/overhead.jpg' ); ?>" alt="Clean illustrated site render boundary graphic" class="rp-aha-img">
							<div class="rp-aha-caption">
								<strong>What sells the location:</strong> Clean architectural site rendering that removes ground eyesores and spotlights lot shape, street frontage, and access.
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
						<div class="rp-stat-lbl">Start Selling the Day You Win the Listing</div>
					</div>
					<div>
						<div class="rp-stat-val">Zero Clutter</div>
						<div class="rp-stat-lbl">Hides Mud, Dirt &amp; Construction Eyesores</div>
					</div>
					<div>
						<div class="rp-stat-val">0 Airspace Locks</div>
						<div class="rp-stat-lbl">Works Near Downtown Heliports &amp; Airports</div>
					</div>
					<div>
						<div class="rp-stat-val">3,140+ Counties</div>
						<div class="rp-stat-lbl">Official Municipal &amp; Tax Assessor Parcel Lines</div>
					</div>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     3. THE PROBLEM & STATUS QUO (WHY THIS WAS HARD UNTIL NOW)
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--xl">
				<div class="rp-header">
					<div class="rp-tag">Why Infill &amp; Commercial Listings Stall</div>
					<h2 class="rp-title">You Can't Start Selling Until Your Listing Has Images</h2>
					<p class="rp-desc">
						Whether you're listing a city tear-down lot, a commercial pad, or an infill parcel, buyers care about <strong>what is for sale and where it sits</strong>. Yet getting clean boundary graphics used to mean two frustrating dead ends:
					</p>
				</div>

				<div class="btx-grid btx-grid--cols-2" style="max-width: 960px; margin: 0 auto;">
					<div class="btx-card" style="border-top: 4px solid #dc2626;">
						<span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #991b1b; background: #fee2e2; padding: 3px 8px; border-radius: 4px;">Dead End #1: No In-House Graphic Designer (Group A)</span>
						<h3 class="btx-heading-3" style="margin-top: 0.75rem;">Buying a Simple Boundary Graphic Shouldn't Require a Design Team</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem;">
							You see big brokerages putting crisp site boundary graphics on their flyers, OMs, and listing photos. But without an in-house graphic designer, a clever listing coordinator, or a family member who knows Illustrator, it has never been a straightforward thing to just buy.
						</p>
					</div>

					<div class="btx-card" style="border-top: 4px solid #dc2626;">
						<span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #991b1b; background: #fee2e2; padding: 3px 8px; border-radius: 4px;">Dead End #2: Expensive or Grounded Drone Shoots (Group B)</span>
						<h3 class="btx-heading-3" style="margin-top: 0.75rem;">Urban Airspace Locks, Narrow Margins, and Unflattering Dirt Photos</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem;">
							City and commercial corridors often sit near airports where drones can't fly. Even when they can, spending $500+ on a small lot eats your commission margin—and a real photo just shows mud, weeds, or construction debris that distracts buyers from the offer.
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
					<h2 class="rp-title">Hire the Exact Site Render Package for Your Job Today</h2>
					<p class="rp-desc">
						Both packages use our clean <strong>Illustrated Site Render Style</strong> to strip away ground blemishes and spotlight your parcel's location, frontage, and dimensions.
					</p>
				</div>

				<div class="rp-jtbd-grid">

					<!-- ====================================================
					     GROUP A OFFER: SITE RENDER 2-PACK ($49)
					     ==================================================== -->
					<div class="rp-offer-card">
						<div>
							<span class="rp-jtbd-who">Group A &bull; The "Explain What's For Sale" Job</span>
							<h3 class="rp-offer-name">Site Render Boundary 2-Pack</h3>

							<div class="rp-jtbd-quote">
								"I want to hire a clean property boundary image to clearly explain what is for sale and why buyers should care—for my listing photos, pitch presentation, or flyer."
							</div>

							<p class="rp-offer-story">
								No graphic designer or CAD drafter needed. In 2 minutes, get two clean architectural overhead renders that erase ugly dirt or debris and make your parcel stand out on the MLS, LoopNet, or a printed brochure.
							</p>

							<div class="rp-price-row">
								<span class="rp-price-num">$49</span>
								<span class="rp-price-sub">/ parcel &bull; one-time flat fee</span>
							</div>
							<div class="rp-speed-badge">&#9889; Ready in 2 Minutes &bull; Zero Design Work</div>

							<ul class="rp-deliverables">
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>1x Close-Up Site Render (B1):</strong> Tightly framed overhead illustration showing exact lot proportions and street frontage without mud or clutter.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>1x Wide Location Context Render (B2):</strong> Zoomed-out view showing nearby intersections, traffic corridors, and why the location matters.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Verified County Parcel Lines:</strong> Locked to official municipal tax assessor records and confirmed by you on our map before payment.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>MLS, Flyer &amp; Presentation Ready:</strong> Unbranded, high-resolution files ready to upload or print immediately.</span>
								</li>
							</ul>
						</div>

						<a href="https://readyplat.com/map?cf6ro4a2lm=ef091e28-d5aa-4362-a056-b7e43e55f47b&cfd3r3gjpt=cc82358d-eb12-4a9d-bd82-761a32d006a4" class="rp-btn rp-btn--primary btx-btn--block">
							Confirm Parcel on Map &mdash; Get Render 2-Pack ($49) &rarr;
						</a>
					</div>

					<!-- ====================================================
					     GROUP B OFFER: SITE RENDER 6-PACK ($99)
					     ==================================================== -->
					<div class="rp-offer-card rp-offer-card--featured">
						<span class="rp-offer-pill">New Category &bull; Full Multi-Angle Shoot Replacement</span>
						<div>
							<span class="rp-jtbd-who">Group B &bull; The "Start Selling Right Now" Job</span>
							<h3 class="rp-offer-name">Site Render Boundary 6-Pack</h3>

							<div class="rp-jtbd-quote">
								"I want all the benefits of sending a drone pilot to shoot my listing from every angle with boundary lines—but in a clean render style right now, without the steep price tag."
							</div>

							<p class="rp-offer-story">
								Built for salespeople who want to start selling the moment the listing agreement is signed. Bypass airport no-fly zones, tight commission margins, and ugly construction debris with a full 6-image 360&deg; site visualization gallery in 5 minutes.
							</p>

							<div class="rp-price-row">
								<span class="rp-price-num">$99</span>
								<span class="rp-price-sub">/ parcel &bull; replaces $600+ drone/drafting costs</span>
							</div>
							<div class="rp-speed-badge">&#9889; Full 6-Image Gallery Ready in 5 Minutes</div>

							<ul class="rp-deliverables">
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Includes Both Top-Down Site Renders (B1 + B2):</strong> Tight parcel footprint render plus wide corridor location render.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Plus 4x Cardinal 3D Oblique Renders (North, East, South, West):</strong> Angled 3D aerial perspectives showing ingress/egress, corner visibility, and surrounding streets.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Removes Ground Distractions:</strong> Buyers focus 100% on location potential instead of mud, weeds, or tear-down clutter.</span>
								</li>
								<li>
									<span class="rp-check">&#10003;</span>
									<span><strong>Instant Turnaround anywhere in the US:</strong> Zero FAA airport lockouts and zero waiting on photographers.</span>
								</li>
							</ul>
						</div>

						<a href="https://readyplat.com/map?cf6ro4a2lm=d891c2e7-cadc-4d45-a845-7973fe15aa42&cfd3r3gjpt=a08a2e67-8157-4987-a879-c1a8570098ca" class="rp-btn rp-btn--primary btx-btn--block">
							Confirm Parcel on Map &mdash; Get Complete 6-Pack ($99) &rarr;
						</a>
					</div>

				</div>

				<!-- Flavor Router back to Drone Style Page -->
				<div class="rp-switcher-box">
					<h4 style="margin: 0 0 0.5rem; font-size: var(--btx-text-xl); font-weight: 800;">
						Listing Rural Land, Ranches, Timber, or Scenic Acreage?
					</h4>
					<p style="margin: 0 0 1.25rem; color: var(--btx-gray-600); font-size: var(--btx-text-sm); max-width: 680px; margin-left: auto; margin-right: auto;">
						Site Render Style is built for commercial and city lots where ground clutter is a distraction. For land and ranch listings where real trees, ponds, and natural terrain sell the property, use our <strong>Photorealistic Drone Style</strong> instead.
					</p>
					<a href="<?php echo esc_url( home_url( '/drone-boundary-images/' ) ); ?>" class="rp-btn rp-btn--secondary">
						See Flavor 1: Photorealistic Drone Style 2-Pack &amp; 6-Pack &rarr;
					</a>
				</div>
			</div>
		</section>

		<!-- ============================================================
		     5. THE SOLUTION & MECHANISM (3 SIMPLE STEPS)
		     ============================================================ -->
		<section class="rp-section">
			<div class="btx-container btx-container--lg">
				<div class="rp-header">
					<div class="rp-tag">Straightforward &amp; Instant</div>
					<h2 class="rp-title">From Street Address to Clean Site Renders in 3 Steps</h2>
				</div>

				<div class="btx-grid btx-grid--cols-3">
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 1</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Enter Address or APN</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							Search any US street address or county parcel number on our interactive map—no survey CAD files required.
						</p>
					</div>
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 2</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Verify Your Lot Lines</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							Confirm the highlighted county tax assessor boundary on the screen so you know the exact parcel is locked in before paying.
						</p>
					</div>
					<div class="btx-card">
						<div style="font-weight: 800; color: var(--btx-color-primary-600); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Step 3</div>
						<h3 class="btx-heading-3" style="font-size: 1.2rem;">Download &amp; Publish</h3>
						<p class="btx-text-muted" style="font-size: 0.95rem; margin: 0;">
							Get your 2-Pack in 2 minutes or 6-Pack in 5 minutes. Upload straight to the MLS, LoopNet, Crexi, or your flyer template.
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
					<div class="rp-tag">Why Site Renders Win</div>
					<h2 class="rp-title">Why Brokers Prefer Site Renders for Commercial &amp; City Lots</h2>
				</div>

				<div style="overflow-x: auto;">
					<table class="rp-table">
						<thead>
							<tr>
								<th>What Matters to Your Offer</th>
								<th>Raw Drone / Satellite Photo</th>
								<th>Hiring a Graphic Designer / Drafter</th>
								<th class="highlight">ReadyPlat Site Render ($49 / $99)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><strong>What buyers focus on</strong></td>
								<td>Dirt, mud, weeds &amp; neighbor clutter</td>
								<td>Clean lot lines (if done right)</td>
								<td class="highlight">Location, frontage &amp; lot geometry</td>
							</tr>
							<tr>
								<td><strong>Turnaround speed</strong></td>
								<td>3 to 5 days + weather delays</td>
								<td>3 to 7 days of back-and-forth emails</td>
								<td class="highlight">2 to 5 minutes (Start selling today)</td>
							</tr>
							<tr>
								<td><strong>Airport / Urban No-Fly Zones</strong></td>
								<td>Grounded by FAA airspace rules</td>
								<td>2D flat drawing only</td>
								<td class="highlight">360&deg; 3D angles anywhere with zero restrictions</td>
							</tr>
							<tr>
								<td><strong>Cost per listing</strong></td>
								<td>$350 to $600+</td>
								<td>$150 to $800+ per site plan</td>
								<td class="highlight">$49 (2-Pack) or $99 (Full 6-Pack)</td>
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
					<div class="rp-tag">Real-World Scenarios</div>
					<h2 class="rp-title">How Agents &amp; Brokers Use Site Render Packs</h2>
				</div>

				<div class="btx-grid btx-grid--cols-3">
					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group A &bull; Hired the $49 2-Pack</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"I needed a clean boundary graphic for a commercial flyer and didn't have an in-house designer to clean up the ugly satellite view. For $49, the Site Render 2-Pack gave me a sharp close-up and wide intersection shot in 2 minutes."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>Grant Holloway</strong><br>
							<span class="btx-text-muted">Commercial &amp; Land Associate</span>
						</div>
					</div>

					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group B &bull; Hired the $99 6-Pack (City Infill)</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"We had an infill tear-down lot covered in mud and old concrete. A real drone photo would have scared builders off, and the commission didn't justify a $500 shoot anyway. The $99 Site Render 6-Pack gave us 6 clean angles focused purely on location."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>Elena Rostova</strong><br>
							<span class="btx-text-muted">Urban Infill Specialist</span>
						</div>
					</div>

					<div class="btx-card">
						<span style="display: inline-block; background: var(--btx-color-success-bg); color: var(--btx-color-success); font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; margin-bottom: 0.75rem;">Group B &bull; Hired the $99 6-Pack (Near Airport)</span>
						<p style="font-style: italic; color: var(--btx-gray-800); font-size: 0.95rem;">
							"Our highway pad listing was 1 mile from a regional airport runway—no drone pilot could legally fly it. I ordered the $99 6-Pack and had all 6 cardinal site renders uploaded to LoopNet and the MLS in 5 minutes."
						</p>
						<div style="border-top: 1px solid var(--btx-gray-100); padding-top: 0.75rem; margin-top: 1rem; font-size: 0.85rem;">
							<strong>Jonathan Cross, CCIM</strong><br>
							<span class="btx-text-muted">Commercial Broker</span>
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
				<h2 class="rp-title">Spotlight Your Location—Not the Dirt. Get Your Site Renders in Minutes.</h2>
				<p class="rp-desc" style="margin-bottom: var(--btx-space-6);">
					Confirm your exact parcel boundary on our map before checkout. Start selling today with zero graphic design hassle.
				</p>
				<div class="rp-btn-row" style="margin-bottom: var(--btx-space-4);">
					<a href="https://readyplat.com/map?cf6ro4a2lm=d891c2e7-cadc-4d45-a845-7973fe15aa42&cfd3r3gjpt=a08a2e67-8157-4987-a879-c1a8570098ca" class="rp-btn rp-btn--primary">
						Get the Complete Site Render 6-Pack ($99) &rarr;
					</a>
					<a href="https://readyplat.com/map?cf6ro4a2lm=ef091e28-d5aa-4362-a056-b7e43e55f47b&cfd3r3gjpt=cc82358d-eb12-4a9d-bd82-761a32d006a4" class="rp-btn rp-btn--secondary">
						Get the Site Render 2-Pack ($49) &rarr;
					</a>
				</div>
				<div class="rp-micro-proof" style="color: var(--btx-gray-400);">
					<span>&#10003; Visual APN confirmation before payment</span>
					<span>&bull;</span>
					<span>&#10003; Delivered in 2&ndash;5 minutes</span>
					<span>&bull;</span>
					<span>&#10003; One-time fee per parcel</span>
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
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">Why choose Site Render Style over Photorealistic Drone Style?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							When you are selling a commercial lot, city infill parcel, or construction site, the value is in the <strong>location, frontage, and dimensions</strong>—showing actual dirt, mud, weeds, or debris in a satellite photo is a distraction from your offer. Site Render Style replaces ground clutter with a clean architectural illustration. (If you're selling rural land or ranches where trees and creeks are the main attraction, <a href="<?php echo esc_url( home_url( '/drone-boundary-images/' ) ); ?>">use our Photorealistic Drone Style instead</a>.)
						</p>
					</div>

					<div class="btx-card">
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">Should I get the $49 2-Pack or the $99 6-Pack?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							Get the <strong>$49 2-Pack</strong> (Group A) when you just need a clean boundary image and location overview to add to existing listing photos, an Offering Memorandum, or a flyer without hiring a graphic designer. Get the <strong>$99 6-Pack</strong> (Group B) when you want a complete multi-angle visual shoot (2 overheads + North, East, South, West 3D angles) so you can publish and start selling the listing right now.
						</p>
					</div>

					<div class="btx-card">
						<h4 style="margin: 0 0 0.5rem; font-size: 1.05rem;">Are these images compliant with MLS, LoopNet, and Crexi?</h4>
						<p class="btx-text-muted" style="margin: 0; font-size: 0.95rem;">
							Yes. Every image is delivered unbranded at high resolution with zero watermarks or text overlays, ready for immediate upload to any residential MLS or commercial portal.
						</p>
					</div>
				</div>

				<!-- Free KML Lead Magnet -->
				<div class="rp-switcher-box" style="background: var(--btx-color-primary-50); border-color: var(--btx-color-primary-300);">
					<h4 style="margin: 0 0 0.5rem; font-size: var(--btx-text-lg); font-weight: 800;">
						Want to Inspect the Parcel Boundary in Google Earth First for $0?
					</h4>
					<p style="margin: 0 0 1.25rem; color: var(--btx-gray-700); font-size: var(--btx-text-sm);">
						Enter any US address or APN to download the official county tax assessor KML polygon in 10 seconds at zero cost.
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

