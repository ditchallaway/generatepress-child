<?php
/**
 * Template Name: ReadyPlat - Site Renders (Pedro Cortes)
 * Description: 9-Section Pedro Cortes Conversion Framework for Illustrated Site Render Boundary Graphics (2-Pack BB2 & 6-Pack BB1).
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$theme_uri = get_stylesheet_directory_uri();

add_filter( 'generate_sidebar_layout', function() { return 'no-sidebar'; } );

get_header(); ?>
<style id="readyplat-cortes-styles">
/* ================================================================
   ReadyPlat Landing Pages Stylesheet (Pedro Cortes 9-Section Framework)
   Conversion-focused layout built on Results, Perceived Superiority,
   and Risk Mitigation.
   ================================================================ */

.rp-landing-wrapper {
  color: var(--btx-text-main);
  background-color: var(--btx-bg-page);
  line-height: 1.6;
}

/* --- 1. Hero Section & Visual "Aha Moment" --- */
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
  font-size: var(--btx-text-4xl);
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
  max-width: 720px;
  margin: 0 auto var(--btx-space-6);
  line-height: 1.6;
}

.rp-hero-ctv-group {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--btx-space-3);
  margin-bottom: var(--btx-space-10);
}

.rp-hero-buttons {
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
  text-decoration: none;
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
  box-shadow: 0 8px 22px 0 rgba(79, 70, 229, 0.45);
}

.rp-btn--secondary {
  background-color: var(--btx-bg-surface);
  color: var(--btx-gray-800) !important;
  border: 1px solid var(--btx-gray-300);
  box-shadow: var(--btx-shadow-sm);
}

.rp-btn--secondary:hover {
  background-color: var(--btx-gray-100);
  border-color: var(--btx-gray-400);
}

.rp-risk-reversal {
  font-size: var(--btx-text-xs);
  color: var(--btx-gray-500);
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--btx-space-3);
  flex-wrap: wrap;
}

/* Visual "Aha Moment" Transformation Showcase */
.rp-aha-showcase {
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

.rp-aha-panel {
  border-radius: var(--btx-radius-lg);
  overflow: hidden;
  border: 1px solid var(--btx-border-color);
  background: var(--btx-gray-50);
  position: relative;
}

.rp-aha-panel--after {
  border: 2px solid var(--btx-color-primary-500);
  box-shadow: var(--btx-shadow-md);
}

.rp-aha-tag {
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

.rp-aha-tag--before {
  background: rgba(153, 27, 27, 0.9);
  color: #ffffff;
}

.rp-aha-tag--after {
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
  text-align: center;
  gap: 0.25rem;
}

.rp-aha-arrow-circle {
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

/* --- 2. Authority Strip (Initial Social Proof) --- */
.rp-authority-strip {
  background-color: #ffffff;
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

.rp-metric-box {
  padding: var(--btx-space-2);
}

.rp-metric-number {
  font-size: var(--btx-text-2xl);
  font-weight: 800;
  color: var(--btx-gray-900);
  line-height: 1.1;
  margin-bottom: 0.2rem;
}

.rp-metric-label {
  font-size: var(--btx-text-xs);
  color: var(--btx-text-muted);
  font-weight: 500;
}

/* --- Section General Layouts --- */
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

.rp-section-header {
  text-align: center;
  max-width: 760px;
  margin: 0 auto var(--btx-space-10);
}

.rp-section-tag {
  color: var(--btx-color-primary-600);
  font-weight: 700;
  font-size: var(--btx-text-xs);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin-bottom: var(--btx-space-2);
}

.rp-section-title {
  font-size: var(--btx-text-3xl);
  font-weight: 800;
  color: var(--btx-gray-900);
  margin-bottom: var(--btx-space-3);
  letter-spacing: -0.015em;
  line-height: 1.2;
}

.rp-section--dark .rp-section-title {
  color: #ffffff;
}

.rp-section-desc {
  font-size: var(--btx-text-base);
  color: var(--btx-text-muted);
}

.rp-section--dark .rp-section-desc {
  color: var(--btx-gray-300);
}

/* --- Grids & Cards --- */
.rp-grid-3 {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--btx-space-6);
}

@media (min-width: 768px) {
  .rp-grid-3 {
    grid-template-columns: repeat(3, 1fr);
  }
}

.rp-grid-2 {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--btx-space-6);
}

@media (min-width: 768px) {
  .rp-grid-2 {
    grid-template-columns: repeat(2, 1fr);
  }
}

.rp-grid-4 {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--btx-space-4);
}

@media (min-width: 640px) {
  .rp-grid-4 {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .rp-grid-4 {
    grid-template-columns: repeat(4, 1fr);
  }
}

.rp-card {
  background: var(--btx-bg-surface);
  border: 1px solid var(--btx-border-color);
  border-radius: var(--btx-radius-lg);
  padding: var(--btx-space-6);
  box-shadow: var(--btx-shadow-sm);
  display: flex;
  flex-direction: column;
}

.rp-card--pain {
  border-top: 4px solid var(--btx-color-danger);
}

.rp-card--step {
  border-top: 4px solid var(--btx-color-primary-600);
}

.rp-card-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.2rem 0.6rem;
  font-size: 0.7rem;
  font-weight: 700;
  border-radius: var(--btx-radius-sm);
  width: fit-content;
  margin-bottom: var(--btx-space-3);
  text-transform: uppercase;
}

.rp-card-badge--danger {
  background: var(--btx-color-danger-bg);
  color: var(--btx-color-danger);
}

.rp-card-badge--primary {
  background: var(--btx-color-primary-50);
  color: var(--btx-color-primary-700);
}

.rp-card-title {
  font-size: var(--btx-text-xl);
  font-weight: 700;
  color: var(--btx-gray-900);
  margin-bottom: var(--btx-space-2);
}

.rp-card-text {
  font-size: var(--btx-text-sm);
  color: var(--btx-gray-600);
  line-height: 1.6;
  margin-bottom: var(--btx-space-4);
  flex-grow: 1;
}

.rp-cost-callout {
  font-size: var(--btx-text-xs);
  font-weight: 700;
  color: var(--btx-color-danger);
  background: var(--btx-color-danger-bg);
  padding: var(--btx-space-2) var(--btx-space-3);
  border-radius: var(--btx-radius-sm);
}

/* Cardinal 4-Angle Preview Grid */
.rp-angle-card {
  background: #ffffff;
  border: 1px solid var(--btx-border-color);
  border-radius: var(--btx-radius-md);
  overflow: hidden;
  box-shadow: var(--btx-shadow-sm);
}

.rp-angle-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  display: block;
}

.rp-angle-card-label {
  padding: var(--btx-space-2) var(--btx-space-3);
  font-size: var(--btx-text-xs);
  font-weight: 700;
  color: var(--btx-gray-800);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* --- 5. Contrast Table (Old Way vs ReadyPlat Way) --- */
.rp-compare-table {
  width: 100%;
  border-collapse: collapse;
  background: #ffffff;
  border-radius: var(--btx-radius-lg);
  overflow: hidden;
  box-shadow: var(--btx-shadow-md);
  margin: var(--btx-space-6) 0;
  border: 1px solid var(--btx-border-color);
}

.rp-compare-table th,
.rp-compare-table td {
  padding: var(--btx-space-4);
  text-align: left;
  border-bottom: 1px solid var(--btx-border-color);
  font-size: var(--btx-text-sm);
}

.rp-compare-table th {
  background: var(--btx-gray-900);
  color: #ffffff;
  font-weight: 700;
}

.rp-compare-table th.rp-col-highlight {
  background: var(--btx-color-primary-600);
}

.rp-compare-table td.rp-col-highlight {
  background: var(--btx-color-primary-50);
  font-weight: 600;
  color: var(--btx-gray-900);
}

.rp-compare-table tr:last-child td {
  border-bottom: none;
}

/* --- Pricing Cards (JTBD Selection) --- */
.rp-pricing-container {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--btx-space-8);
  max-width: 960px;
  margin: 0 auto;
}

@media (min-width: 768px) {
  .rp-pricing-container {
    grid-template-columns: repeat(2, 1fr);
    align-items: stretch;
  }
}

.rp-pricing-card {
  background: var(--btx-bg-surface);
  border: 2px solid var(--btx-border-color);
  border-radius: var(--btx-radius-xl);
  padding: var(--btx-space-8);
  box-shadow: var(--btx-shadow-md);
  display: flex;
  flex-direction: column;
  position: relative;
}

.rp-pricing-card--featured {
  border-color: var(--btx-color-primary-600);
  box-shadow: var(--btx-shadow-xl);
}

.rp-pricing-badge {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--btx-color-primary-600);
  color: #ffffff;
  padding: 0.25rem 0.9rem;
  border-radius: var(--btx-radius-full);
  font-size: var(--btx-text-xs);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  white-space: nowrap;
}

.rp-pricing-title {
  font-size: var(--btx-text-2xl);
  font-weight: 800;
  color: var(--btx-gray-900);
  margin-bottom: var(--btx-space-1);
}

.rp-pricing-target {
  font-size: var(--btx-text-sm);
  color: var(--btx-color-primary-700);
  font-weight: 600;
  margin-bottom: var(--btx-space-4);
}

.rp-pricing-price {
  font-size: 3rem;
  font-weight: 800;
  color: var(--btx-gray-900);
  line-height: 1;
  margin-bottom: var(--btx-space-2);
}

.rp-pricing-price span {
  font-size: var(--btx-text-base);
  font-weight: 500;
  color: var(--btx-text-muted);
}

.rp-pricing-timing {
  display: inline-flex;
  align-items: center;
  gap: var(--btx-space-1);
  font-size: var(--btx-text-xs);
  font-weight: 700;
  color: var(--btx-color-success);
  background: var(--btx-color-success-bg);
  padding: 0.3rem 0.65rem;
  border-radius: var(--btx-radius-sm);
  width: fit-content;
  margin-bottom: var(--btx-space-6);
}

.rp-pricing-features {
  list-style: none;
  padding: 0;
  margin: 0 0 var(--btx-space-8) 0;
  flex-grow: 1;
}

.rp-pricing-features li {
  display: flex;
  align-items: flex-start;
  gap: var(--btx-space-3);
  font-size: var(--btx-text-sm);
  color: var(--btx-gray-700);
  margin-bottom: var(--btx-space-3);
}

.rp-check-icon {
  color: var(--btx-color-primary-600);
  font-weight: bold;
  flex-shrink: 0;
}

/* --- 7. Outcome-Driven Testimonials --- */
.rp-testimonial-card {
  background: #ffffff;
  border: 1px solid var(--btx-border-color);
  border-radius: var(--btx-radius-lg);
  padding: var(--btx-space-6);
  box-shadow: var(--btx-shadow-sm);
  display: flex;
  flex-direction: column;
}

.rp-testimonial-metric {
  display: inline-block;
  background: var(--btx-color-success-bg);
  color: var(--btx-color-success);
  font-size: var(--btx-text-xs);
  font-weight: 700;
  padding: 0.25rem 0.6rem;
  border-radius: var(--btx-radius-sm);
  margin-bottom: var(--btx-space-3);
  width: fit-content;
}

.rp-testimonial-quote {
  font-size: var(--btx-text-sm);
  color: var(--btx-gray-800);
  font-style: italic;
  line-height: 1.6;
  margin-bottom: var(--btx-space-4);
  flex-grow: 1;
}

.rp-testimonial-author {
  border-top: 1px solid var(--btx-gray-100);
  padding-top: var(--btx-space-3);
  font-size: var(--btx-text-xs);
}

.rp-testimonial-author strong {
  display: block;
  color: var(--btx-gray-900);
  font-size: var(--btx-text-sm);
}

/* --- Cross Flavor & Low-Friction Banner --- */
.rp-flavor-banner {
  background: #ffffff;
  border: 2px dashed var(--btx-color-primary-300);
  border-radius: var(--btx-radius-lg);
  padding: var(--btx-space-6);
  margin: var(--btx-space-10) auto 0;
  max-width: 840px;
  text-align: center;
}

.rp-flavor-banner h4 {
  font-size: var(--btx-text-xl);
  font-weight: 800;
  color: var(--btx-gray-900);
  margin-bottom: var(--btx-space-2);
}

.rp-flavor-banner p {
  font-size: var(--btx-text-sm);
  color: var(--btx-gray-600);
  margin-bottom: var(--btx-space-4);
}

/* --- 9. FAQ Section --- */
.rp-faq-list {
  max-width: 780px;
  margin: 0 auto;
}

.rp-faq-item {
  background: var(--btx-bg-surface);
  border: 1px solid var(--btx-border-color);
  border-radius: var(--btx-radius-md);
  margin-bottom: var(--btx-space-4);
  padding: var(--btx-space-5);
}

.rp-faq-question {
  font-size: var(--btx-text-lg);
  font-weight: 700;
  color: var(--btx-gray-900);
  margin-bottom: var(--btx-space-2);
}

.rp-faq-answer {
  font-size: var(--btx-text-sm);
  color: var(--btx-gray-600);
  line-height: 1.6;
}

</style>


<div id="primary" class="content-area rp-landing-wrapper">
    <main id="main" class="site-main">

        <!-- ============================================================
             SECTION 1: HERO SECTION & VISUAL "AHA MOMENT"
             ============================================================ -->
        <section class="rp-hero">
            <div class="btx-container btx-container--xl">
                <div class="rp-eyebrow">
                    The Architectural Site Plan Rendering Engine For Commercial, Infill &amp; City Lot Brokers
                </div>
                
                <h1 class="rp-hero-title">Stop Distracting Buyers With Mud and Debris. Render Clean Architectural Site Plans in 5 Minutes.</h1>
                
                <p class="rp-hero-subtitle">
                    When a parcel's value is in its location, zoning, and street frontage, raw satellite photos of dirt ruts and cluttered neighbors hurt your offer. ReadyPlat converts official county tax assessor GIS boundaries into clean, illustrated 3D site renders in under 5 minutes without hiring a CAD drafter.
                </p>

                <div class="rp-hero-ctv-group">
                    <div class="rp-hero-buttons">
                        <a href="#pricing" class="rp-btn rp-btn--primary">
                            Verify Your Parcel on the Map &mdash; Get 6 Site Renders in 5 Minutes ($99) &rarr;
                        </a>
                        <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--secondary">
                            Test Any Parcel First: Download Free Google Earth KML ($0)
                        </a>
                    </div>
                    <div class="rp-risk-reversal">
                        <span>&#10003; Confirm exact APN footprint before checkout</span>
                        <span>&bull;</span>
                        <span>&#10003; One-time fee per parcel</span>
                        <span>&bull;</span>
                        <span>&#10003; 3000px LoopNet, Crexi &amp; OM ready</span>
                    </div>
                </div>

                <!-- Visual "Aha Moment" Transformation Spec -->
                <div class="rp-aha-showcase">
                    <div class="rp-aha-grid">
                        <div class="rp-aha-panel">
                            <span class="rp-aha-tag rp-aha-tag--before">Status Quo: Cluttered Satellite / Muddy Lot Photo</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/before.jpg' ); ?>" alt="Cluttered raw satellite view with dirt and debris" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>What kills buyer imagination:</strong> Tire ruts, dead weeds, parked construction trailers, and visual noise that obscure the buildable envelope.
                            </div>
                        </div>

                        <div class="rp-aha-arrow">
                            <div class="rp-aha-arrow-circle">&rarr;</div>
                            <span>180 Seconds</span>
                        </div>

                        <div class="rp-aha-panel rp-aha-panel--after">
                            <span class="rp-aha-tag rp-aha-tag--after">ReadyPlat Output: Illustrated Site Render (BB1 / BB2)</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/overhead.jpg' ); ?>" alt="Clean architectural site render boundary graphic" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>What closes developers:</strong> Clean architectural color-blocking locked to official county GIS coordinates that spotlights frontage, depth, and access.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 2: INITIAL SOCIAL PROOF (AUTHORITY STRIP)
             ============================================================ -->
        <section class="rp-authority-strip">
            <div class="btx-container btx-container--xl">
                <div class="rp-authority-grid">
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">$1,401 Saved</div>
                        <div class="rp-metric-label">Average Savings vs. Architectural CAD Site Plan</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">2&ndash;5 Mins</div>
                        <div class="rp-metric-label">From APN Search to 3000px Offering Memorandum Files</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">3,140+</div>
                        <div class="rp-metric-label">US Municipal &amp; County GIS Parcel Databases</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">360&deg; Views</div>
                        <div class="rp-metric-label">4 Cardinal 3D Obliques + 2 Top-Down Site Plans</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 3: THE PROBLEM & STATUS QUO (THE CONTRAST)
             ============================================================ -->
        <section class="rp-section">
            <div class="btx-container btx-container--xl">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Why Satellite Photos Hurt Infill Listings</div>
                    <h2 class="rp-section-title">Showing Raw Dirt Distracts Investors From Location Value</h2>
                    <p class="rp-section-desc">
                        When you list a commercial pad, tear-down lot, or urban infill parcel, buyers are buying the geometry and street corridor—not the weeds currently growing on the lot. Yet brokers remain trapped between three bad choices:
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #1: Unfiltered Drone / Satellite</span>
                        <h3 class="rp-card-title">Highlighting Mud, Dumpsters, and Neighbor Clutter</h3>
                        <p class="rp-card-text">
                            Photorealistic drone shots of an urban lot draw the buyer's eye to cracked asphalt, neighboring roof tarps, and construction mud. Instead of picturing a new retail pad or townhome project, investors fixate on site cleanup costs.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Lower perceived land value + lowball developer offers
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #2: Architectural CAD Houses</span>
                        <h3 class="rp-card-title">Paying $1,200+ and Waiting 2 Weeks for a Site Plan</h3>
                        <p class="rp-card-text">
                            Commissioning a commercial drafting firm or civil engineer to illustrate a clean site graphic for your Offering Memorandum (OM) takes 10 to 14 business days and eats up over $1,200 before you even test buyer appetite.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: 2-week delay getting your OM onto LoopNet and Crexi
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #3: 1990s County Tax Map PDFs</span>
                        <h3 class="rp-card-title">Pasting Pixellated Black-and-White Assessor Grids</h3>
                        <p class="rp-card-text">
                            Screenshotting the county GIS web portal gives you low-resolution, cluttered dimension numbers and neon parcel lines that look amateurish inside a $2M commercial pitch deck.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Weakened broker authority during seller pitch presentations
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 4: THE SOLUTION & MECHANISM (THE CRUCIAL 20%)
             ============================================================ -->
        <section class="rp-section rp-section--alt">
            <div class="btx-container btx-container--xl">
                <div class="rp-section-header">
                    <div class="rp-section-tag">The 3-Step Site Render Engine</div>
                    <h2 class="rp-section-title">From County APN to Architectural Site Renders in 5 Minutes</h2>
                    <p class="rp-section-desc">
                        ReadyPlat strips away ground clutter while preserving exact municipal survey geometry and 3D street context:
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 1: Municipal GIS Lock</span>
                        <h3 class="rp-card-title">Confirm Your Exact Parcel Polygon on the Map</h3>
                        <p class="rp-card-text">
                            Enter the property address or county APN. Our map engine retrieves the official tax assessor boundary polygon and displays it for your 1-click confirmation before checkout.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 2: Illustrated Style Synthesis</span>
                        <h3 class="rp-card-title">Engine Converts Raw Dirt Into Clean Architectural Form</h3>
                        <p class="rp-card-text">
                            Our specialized Site Render pipeline (Segmind architectural model + 3D Cesium camera rig) abstracts away mud, ruts, and trash while keeping surrounding roadways, intersections, and lot lines razor-sharp.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 3: 3000px OM &amp; Portal Export</span>
                        <h3 class="rp-card-title">Drop Directly Into LoopNet, Crexi &amp; Pitch Decks</h3>
                        <p class="rp-card-text">
                            Download your 2-Pack overheads in 2 minutes or your full 6-Pack 360&deg; cardinal suite in 5 minutes. Every file is rendered at 3000px resolution for crisp full-page printing in Offering Memorandums.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 5: REINFORCING BENEFITS & PERCEIVED SUPERIORITY
             ============================================================ -->
        <section class="rp-section">
            <div class="btx-container btx-container--xl">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Perceived Superiority</div>
                    <h2 class="rp-section-title">How ReadyPlat Site Renders Beat CAD Drafters and Raw Satellite</h2>
                </div>

                <div style="overflow-x: auto;">
                    <table class="rp-compare-table">
                        <thead>
                            <tr>
                                <th>Evaluation Metric</th>
                                <th>Raw Satellite / Drone Photo</th>
                                <th>Custom Architectural Illustrator</th>
                                <th class="rp-col-highlight">ReadyPlat Site Render Engine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Visual Focus</strong></td>
                                <td>Shows Mud, Weeds &amp; Neighbor Junk</td>
                                <td>Clean Architectural Focus</td>
                                <td class="rp-col-highlight">Clean Architectural Focus + 3D Context</td>
                            </tr>
                            <tr>
                                <td><strong>Delivery Speed</strong></td>
                                <td>3&ndash;5 Days (Drone) / 45 Mins (DIY)</td>
                                <td>7 to 14 Business Days</td>
                                <td class="rp-col-highlight">2 to 5 Minutes (Automated)</td>
                            </tr>
                            <tr>
                                <td><strong>Cost Per Parcel</strong></td>
                                <td>$500+ for Drone Shoot</td>
                                <td>$800 to $1,500+ Per Site Plan</td>
                                <td class="rp-col-highlight">$49 (2-Pack) or $99 (360&deg; 6-Pack)</td>
                            </tr>
                            <tr>
                                <td><strong>3D Cardinal Angles</strong></td>
                                <td>Costs Extra Per Flight Angle</td>
                                <td>2D Top-Down Only</td>
                                <td class="rp-col-highlight">Includes N, E, S, W 3D Oblique Renders</td>
                            </tr>
                            <tr>
                                <td><strong>Source Geometry</strong></td>
                                <td>Manual Editor Approximation</td>
                                <td>Requires You to Supply Survey CAD</td>
                                <td class="rp-col-highlight">Direct County Tax Assessor GIS Lookup</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pricing Selection Cards (BB2 vs BB1) -->
                <div id="pricing" style="margin-top: var(--btx-space-12);">
                    <div class="rp-section-header">
                        <div class="rp-section-tag">Select Your Package</div>
                        <h2 class="rp-section-title">Choose Your Illustrated Site Render Package</h2>
                        <p class="rp-section-desc">
                            One-time pricing per parcel. Confirm your exact lot boundary on the map before completing payment.
                        </p>
                    </div>

                    <div class="rp-pricing-container">
                        <!-- JOB A: RENDER 2-PACK ($49, SKU BB2) -->
                        <div class="rp-pricing-card">
                            <h3 class="rp-pricing-title">Site Render 2-Pack</h3>
                            <div class="rp-pricing-target">Job A: For Infill MLS Listings, Tear-Downs &amp; Property Flyers</div>
                            
                            <div class="rp-pricing-price">$49 <span>/ one-time per parcel</span></div>
                            <div class="rp-pricing-timing">&#9889; Delivered in 2 Minutes</div>

                            <ul class="rp-pricing-features">
                                <li><span class="rp-check-icon">&check;</span> <strong>1x Close-Up Site Render (SKU B1):</strong> Framed tightly on the parcel boundary in clean illustrated style without edge clipping.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>1x Wide-Angle Corridor Render (SKU B2):</strong> Zoomed-out overhead showing arterial streets, intersections, and traffic corridors.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Eliminates Ground Blemishes:</strong> Abstracts away dirt, debris, and unsightly adjacent structures.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>3000px Print &amp; Portal Resolution:</strong> Crisp vector-like clarity for flyers, brochures, and residential/commercial MLS.</li>
                            </ul>

                            <a href="https://readyplat.com/map?cf6ro4a2lm=ef091e28-d5aa-4362-a056-b7e43e55f47b&cfd3r3gjpt=cc82358d-eb12-4a9d-bd82-761a32d006a4" class="rp-btn rp-btn--primary">
                                Verify Parcel on Map &mdash; Get Render 2-Pack ($49) &rarr;
                            </a>
                        </div>

                        <!-- JOB B: 360 SITE RENDER 6-PACK ($99, SKU BB1) -->
                        <div class="rp-pricing-card rp-pricing-card--featured">
                            <div class="rp-pricing-badge">Most Popular For Commercial OMs &amp; Pitch Decks</div>
                            <h3 class="rp-pricing-title">360&deg; Site Render 6-Pack</h3>
                            <div class="rp-pricing-target">Job B: Complete 6-Angle Suite for Developers &amp; Investors</div>
                            
                            <div class="rp-pricing-price">$99 <span>/ one-time per parcel</span></div>
                            <div class="rp-pricing-timing">&#9889; Delivered in 5 Minutes</div>

                            <ul class="rp-pricing-features">
                                <li><span class="rp-check-icon">&check;</span> <strong>2x Top-Down Site Renders (B1 + B2):</strong> Both Close-Up footprint and Wide-Angle street corridor views.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>4x Cardinal 3D Oblique Renders (B3&ndash;B6):</strong> 3D architectural perspectives looking North, East, South, and West.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Showcases Access &amp; Ingress/Egress:</strong> Helps commercial buyers evaluate curb cuts, corner visibility, and lot depth.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Replaces $1,500 Drafting Fees:</strong> Institutional-quality visuals for LoopNet, Crexi, and city council packets in 5 minutes.</li>
                            </ul>

                            <a href="https://readyplat.com/map?cf6ro4a2lm=d891c2e7-cadc-4d45-a845-7973fe15aa42&cfd3r3gjpt=a08a2e67-8157-4987-a879-c1a8570098ca" class="rp-btn rp-btn--primary">
                                Verify Parcel on Map &mdash; Get 360&deg; 6-Pack ($99) &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Cross-Flavor Routing Banner -->
                    <div class="rp-flavor-banner">
                        <h4>Listing Rural Land, Ranches, Timberland, or Recreational Acreage?</h4>
                        <p>
                            When natural tree canopy, ponds, and pasture textures are your best selling points, use our <strong>Photorealistic Drone Style Aerials</strong> instead.
                        </p>
                        <a href="<?php echo esc_url( home_url( '/drone-boundary-images/' ) ); ?>" class="rp-btn rp-btn--secondary">
                            Switch to Photorealistic Drone Style Packages &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 6: SWITCHING EASE & FRICTION REMOVAL
             ============================================================ -->
        <section class="rp-section rp-section--alt">
            <div class="btx-container btx-container--lg">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Effortless Adoption</div>
                    <h2 class="rp-section-title">No CAD Files Needed. No Designer Briefs. Ready in 5 Minutes.</h2>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card">
                        <h3 class="rp-card-title">1. Zero Survey Uploads</h3>
                        <p class="rp-card-text">
                            Don't wait for the property owner to dig up an old ALTA survey PDF. Our system pulls parcel vectors directly from county tax records via address or APN.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">2. Built for LoopNet, Crexi &amp; OMs</h3>
                        <p class="rp-card-text">
                            High-resolution 3000px JPG exports drop directly into commercial listing portals, InDesign offering memorandums, and PowerPoint pitch decks.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">3. Per-Deal Expense Simplicity</h3>
                        <p class="rp-card-text">
                            No annual software licenses. Pay $49 or $99 per parcel and download your itemized receipt immediately for brokerage reimbursement.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 7: RELATABLE, OUTCOME-DRIVEN SOCIAL PROOF
             ============================================================ -->
        <section class="rp-section">
            <div class="btx-container btx-container--xl">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Commercial &amp; Infill Proof</div>
                    <h2 class="rp-section-title">How Brokers Use Illustrated Site Renders to Win &amp; Close Deals</h2>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Cut OM Production Time by 6 Days</span>
                        <p class="rp-testimonial-quote">
                            "We were listing a 1.4-acre commercial corner pad that had gravel piles and old fencing on it. The raw satellite photo looked terrible on LoopNet. ReadyPlat's $99 360&deg; Site Render Pack gave us 6 clean architectural views in 5 minutes that made the intersection frontage pop."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Jonathan R. Cross, CCIM</strong>
                            Senior Vice President &bull; Meridian Commercial Partners
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Sold 4 Infill Tear-Down Lots in 14 Days</span>
                        <p class="rp-testimonial-quote">
                            "When selling city infill lots to custom home builders, showing the dilapidated structure currently on the lot anchors buyers on demolition headaches. Using the $49 Site Render 2-Pack shifts the conversation entirely to lot width, alley access, and buildable footprint."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Elena Rostova</strong>
                            Urban Infill Specialist &bull; Capitol City Properties
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Saved $1,100 on Pitch Book Graphics</span>
                        <p class="rp-testimonial-quote">
                            "Our marketing team used to pay an external illustrator $600 per parcel to draw clean site maps for seller pitch books. Now our analysts generate the 360&deg; Site Render 6-Pack in 5 minutes for $99 before every listing presentation."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>grant T. Holloway</strong>
                            Director of Brokerage Marketing &bull; Vanguard CRE
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 8: FINAL CALL TO VALUE & RISK REVERSAL
             ============================================================ -->
        <section class="rp-section rp-section--dark" style="text-align: center;">
            <div class="btx-container btx-container--lg">
                <h2 class="rp-section-title">Turn Any Muddy Lot Into an Investor-Ready Site Plan in 5 Minutes</h2>
                <p class="rp-section-desc" style="margin-bottom: var(--btx-space-6);">
                    Verify your exact parcel boundary on our interactive map before checkout. Download 3000px architectural site renders in minutes.
                </p>
                <div class="rp-hero-buttons" style="margin-bottom: var(--btx-space-4);">
                    <a href="https://readyplat.com/map?cf6ro4a2lm=d891c2e7-cadc-4d45-a845-7973fe15aa42&cfd3r3gjpt=a08a2e67-8157-4987-a879-c1a8570098ca" class="rp-btn rp-btn--primary">
                        Verify Parcel &amp; Get 360&deg; Site Render 6-Pack ($99) &rarr;
                    </a>
                    <a href="https://readyplat.com/map?cf6ro4a2lm=ef091e28-d5aa-4362-a056-b7e43e55f47b&cfd3r3gjpt=cc82358d-eb12-4a9d-bd82-761a32d006a4" class="rp-btn rp-btn--secondary">
                        Verify Parcel &amp; Get Site Render 2-Pack ($49) &rarr;
                    </a>
                </div>
                <div class="rp-risk-reversal" style="color: var(--btx-gray-400);">
                    <span>&#10003; 100% APN polygon verification before payment</span>
                    <span>&bull;</span>
                    <span>&#10003; No recurring subscription</span>
                    <span>&bull;</span>
                    <span>&#10003; Instant browser + email fulfillment</span>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 9: OBJECTION-BUSTING FAQ & ALTERNATIVE LOW-FRICTION CTA
             ============================================================ -->
        <section class="rp-section">
            <div class="btx-container btx-container--lg">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Objection-Busting FAQs</div>
                    <h2 class="rp-section-title">Questions From Commercial &amp; Infill Brokers</h2>
                </div>

                <div class="rp-faq-list">
                    <div class="rp-faq-item">
                        <div class="rp-faq-question">1. When should I use Site Render Style instead of Drone Style?</div>
                        <div class="rp-faq-answer">
                            Use <strong>Site Render Style (BB1 / BB2)</strong> for commercial lots, urban infill, tear-downs, or construction sites where dirt, debris, or messy adjacent yards distract from the parcel's location and geometry. Use <strong>Drone Style (AA1 / AA2)</strong> for rural land, ranches, and recreational acreage where buyers want to see real trees, pastures, and creeks.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">2. How accurate are the parcel boundary lines?</div>
                        <div class="rp-faq-answer">
                            Every boundary is generated directly from official county tax assessor GIS survey polygons. Before checkout, our interactive map shows you the exact polygon over your lot so you can verify accuracy with 100% certainty.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">3. What resolution are the final files for our Offering Memorandum?</div>
                        <div class="rp-faq-answer">
                            All images are exported at 3000px+ high resolution in standard landscape aspect ratios, ensuring razor-sharp quality whether viewed full-screen on LoopNet/Crexi or printed in a physical pitch book.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">4. How long does fulfillment take after I confirm my parcel?</div>
                        <div class="rp-faq-answer">
                            The Site Render 2-Pack (BB2) completes in approximately 2 minutes. The 360&deg; Site Render 6-Pack (BB1) renders all 6 angles in under 5 minutes. You can watch progress live on your fulfillment dashboard and receive a backup download link via email.
                        </div>
                    </div>
                </div>

                <!-- Alternative Low-Friction CTA (Group C Lead Magnet) -->
                <div class="rp-flavor-banner" style="background: var(--btx-color-primary-50); border-color: var(--btx-color-primary-400);">
                    <h4>Want to Inspect the Parcel in Google Earth First? Download the Free KML.</h4>
                    <p>
                        Test our county GIS accuracy in 10 seconds at zero cost. Download the official georeferenced <strong>Property Boundary KML (SKU D1)</strong> and open it directly in Google Earth Desktop or Mobile.
                    </p>
                    <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
                        Download Free Parcel Boundary KML ($0) &rarr;
                    </a>
                </div>
            </div>
        </section>

    </main>
</div>

<?php
get_footer();