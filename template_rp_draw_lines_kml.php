<?php
/**
 * Template Name: ReadyPlat - Draw Property Lines KML (Pedro Cortes)
 * Description: 9-Section Pedro Cortes Conversion Framework for Inbound SEO Lead Magnet (Free KML D1) + Upgrade Funnel to 2-Pack & 6-Pack.
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
                    The Instant County GIS Boundary Tool For Real Estate Agents &amp; Land Specialists
                </div>
                
                <h1 class="rp-hero-title">Stop Tracing Crooked Property Lines by Hand. Get Exact Parcel Boundaries on Google Earth in 10 Seconds.</h1>
                
                <p class="rp-hero-subtitle">
                    Google Maps doesn't have a built-in property line layer, and tracing county PDF plats in Canva takes 45 minutes. Enter any US address or APN to download the official county tax assessor KML polygon for free—or generate 3000px MLS-compliant aerial photos in 5 minutes.
                </p>

                <div class="rp-hero-ctv-group">
                    <div class="rp-hero-buttons">
                        <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
                            Find Your Parcel on the Map &mdash; Download Free Google Earth KML ($0) &rarr;
                        </a>
                        <a href="#mls-upgrade" class="rp-btn rp-btn--secondary">
                            Need MLS Listing Photos? See 5-Minute Aerial Packages ($49)
                        </a>
                    </div>
                    <div class="rp-risk-reversal">
                        <span>&#10003; 100% Free KML Download ($0)</span>
                        <span>&bull;</span>
                        <span>&#10003; Official County Tax Assessor GIS</span>
                        <span>&bull;</span>
                        <span>&#10003; Opens in Google Earth Desktop, Web &amp; Mobile</span>
                    </div>
                </div>

                <!-- Visual "Aha Moment" Transformation Spec -->
                <div class="rp-aha-showcase">
                    <div class="rp-aha-grid">
                        <div class="rp-aha-panel">
                            <span class="rp-aha-tag rp-aha-tag--before">Manual Way: 45 Mins Tracing Lines in Canva / Google Maps</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/before.jpg' ); ?>" alt="Unmarked satellite map before GIS polygon import" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>Why manual drawing fails:</strong> Eyeballing vertices over trees and hills leads to crooked lines, false acreage impressions, and MLS compliance fines.
                            </div>
                        </div>

                        <div class="rp-aha-arrow">
                            <div class="rp-aha-arrow-circle">&rarr;</div>
                            <span>10 Seconds</span>
                        </div>

                        <div class="rp-aha-panel rp-aha-panel--after">
                            <span class="rp-aha-tag rp-aha-tag--after">ReadyPlat Way: Official Georeferenced GIS Boundary</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/hero-aerial.jpg' ); ?>" alt="Exact county GIS boundary locked to 3D terrain" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>Zero manual drawing:</strong> GPS-locked county survey vertices snapped directly to 3D elevation in Google Earth (Free KML) or exported as 3000px MLS JPGs.
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
                        <div class="rp-metric-number">10 Seconds</div>
                        <div class="rp-metric-label">To Export Official Georeferenced KML Polygon</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">3,140+</div>
                        <div class="rp-metric-label">US County Assessor GIS Databases Indexed</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">0 Software</div>
                        <div class="rp-metric-label">No ArcGIS, QGIS, or Photoshop Skills Required</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">100% Free</div>
                        <div class="rp-metric-label">Instant $0 Download for Parcel Field Inspection</div>
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
                    <div class="rp-section-tag">Why Drawing Lines By Hand Backfires</div>
                    <h2 class="rp-section-title">3 Reasons Manual Map Screenshots Cost You Time &amp; Compliance</h2>
                    <p class="rp-section-desc">
                        Every week, thousands of agents search for how to draw property lines on Google Maps. Trying to sketch boundaries manually creates three expensive headaches:
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Trap #1: Blind Vertex Guessing</span>
                        <h3 class="rp-card-title">Eyeballing Corners Over Dense Tree Canopy</h3>
                        <p class="rp-card-text">
                            Looking at a black-and-white county plat PDF on one monitor and trying to click matching points on a green satellite map in Google My Maps or Canva is pure guesswork when there are no visible fences.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Misrepresenting road frontage or encroaching on neighbor parcels
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Trap #2: MLS Compliance Takedowns</span>
                        <h3 class="rp-card-title">Getting Fining Notices for Browser Screenshots</h3>
                        <p class="rp-card-text">
                            Taking a screen capture of Google Maps or county GIS portals leaves browser toolbars, scale bars, street labels, and Google copyright watermarks in the image—violating strict MLS photo rules.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Embarrassing MLS photo removal notices + compliance fines
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Trap #3: 2D Parallax Distortion</span>
                        <h3 class="rp-card-title">Flat Lines That Float Off the Ground on Slopes</h3>
                        <p class="rp-card-text">
                            Drawing a flat 2D box over an angled screenshot fails to follow hills, gullies, and ridge lines—making sloped acreage look warped and amateurish in your listing presentation.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Losing the listing pitch to a competing agent with 3D visuals
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
                    <div class="rp-section-tag">The 10-Second Workflow</div>
                    <h2 class="rp-section-title">How to Get Official Property Lines on Google Earth in 3 Clicks</h2>
                    <p class="rp-section-desc">
                        Instead of drawing lines yourself, let ReadyPlat extract the exact mathematical polygon from county tax records:
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 1: Search by Address or APN</span>
                        <h3 class="rp-card-title">Locate Any US Parcel on Our Live Map</h3>
                        <p class="rp-card-text">
                            Enter the street address or county Assessor's Parcel Number. Our database queries over 3,140 US counties to locate the exact legal boundary vertices.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 2: Confirm the Highlighted Polygon</span>
                        <h3 class="rp-card-title">Verify the Footprint With 1 Click</h3>
                        <p class="rp-card-text">
                            See the boundary highlighted on our interactive map and click <strong>"Yes, I Confirm Parcel"</strong>. Choose the $0 Free KML option at checkout.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 3: Open in Google Earth or Upgrade</span>
                        <h3 class="rp-card-title">Fly Around in 3D or Export 3000px MLS Photos</h3>
                        <p class="rp-card-text">
                            Double-click your downloaded <code>.kml</code> file to open the parcel in Google Earth Desktop or Mobile—or select our $49 / $99 automated rendering packages to get ready-to-upload 3000px MLS JPGs.
                        </p>
                    </div>
                </div>

                <div style="text-align: center; margin-top: var(--btx-space-8);">
                    <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
                        Search Your Parcel &mdash; Download Free KML in 10 Seconds ($0) &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 5: REINFORCING BENEFITS & PERCEIVED SUPERIORITY
             ============================================================ -->
        <section id="mls-upgrade" class="rp-section">
            <div class="btx-container btx-container--xl">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Free KML vs. MLS-Ready Photo Packages</div>
                    <h2 class="rp-section-title">When to Use the Free KML vs. Upgrading to Our 3000px Aerial Packs</h2>
                    <p class="rp-section-desc">
                        Our Free KML gets you 80% of the way there for personal research and field walks. When you are ready to publish a listing on the MLS, Zillow, or LoopNet, our automated rendering engine finishes the job:
                    </p>
                </div>

                <div style="overflow-x: auto;">
                    <table class="rp-compare-table">
                        <thead>
                            <tr>
                                <th>Capability</th>
                                <th>Manual Canva / Google Drawing</th>
                                <th>ReadyPlat Free KML (SKU D1)</th>
                                <th class="rp-col-highlight">ReadyPlat 2-Pack ($49) &amp; 6-Pack ($99)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Primary Job-To-Be-Done</strong></td>
                                <td>Improvised DIY Graphic</td>
                                <td>Field Walks &amp; Google Earth Research</td>
                                <td class="rp-col-highlight">Live MLS Gallery, Zillow, OMs &amp; Flyers</td>
                            </tr>
                            <tr>
                                <td><strong>Boundary Source</strong></td>
                                <td>Hand-Traced Guesswork</td>
                                <td>100% Official County GIS Polygon</td>
                                <td class="rp-col-highlight">100% Official County GIS Polygon</td>
                            </tr>
                            <tr>
                                <td><strong>File Format</strong></td>
                                <td>Low-Res Screen Capture</td>
                                <td>Georeferenced <code>.KML</code> Vector File</td>
                                <td class="rp-col-highlight">3000px High-Resolution MLS <code>.JPG</code> Images</td>
                            </tr>
                            <tr>
                                <td><strong>MLS Photo Compliance</strong></td>
                                <td>Rejected (Watermarks &amp; UI Clutter)</td>
                                <td>Not an Image File (Cannot Upload to MLS)</td>
                                <td class="rp-col-highlight">100% MLS, Zillow &amp; Print Compliant</td>
                            </tr>
                            <tr>
                                <td><strong>Camera Angles Included</strong></td>
                                <td>Flat 2D Top-Down Only</td>
                                <td>Interactive Inside Google Earth App</td>
                                <td class="rp-col-highlight">2 Overheads + 4 Cardinal 3D Obliques (N,E,S,W)</td>
                            </tr>
                            <tr>
                                <td><strong>Turnaround &amp; Price</strong></td>
                                <td>45 Minutes of Frustration</td>
                                <td><strong>10 Seconds &bull; $0 Free</strong></td>
                                <td class="rp-col-highlight"><strong>2&ndash;5 Minutes &bull; $49 or $99 One-Time</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Upgrade Package Cards -->
                <div class="rp-grid-2" style="margin-top: var(--btx-space-10);">
                    <div class="rp-pricing-card">
                        <h3 class="rp-pricing-title">Upgrade Option 1: Photorealistic Drone Style</h3>
                        <div class="rp-pricing-target">Best for Land, Ranches, Acreage &amp; Residential Listings</div>
                        <div class="rp-pricing-price">$49 <span>(2-Pack) or</span> $99 <span>(6-Pack)</span></div>
                        <div class="rp-pricing-timing">&#9889; Rendered in 2 to 5 Minutes</div>
                        <ul class="rp-pricing-features">
                            <li><span class="rp-check-icon">&check;</span> <strong>2-Pack ($49, SKU AA2):</strong> 1 Tight Overhead + 1 Wide Context Aerial.</li>
                            <li><span class="rp-check-icon">&check;</span> <strong>6-Pack ($99, SKU AA1):</strong> 2 Overheads + 4 Cardinal 3D Obliques (N, E, S, W).</li>
                            <li><span class="rp-check-icon">&check;</span> <strong>Photorealistic Terrain:</strong> Showcases real trees, pastures, ponds, and topography.</li>
                        </ul>
                        <a href="<?php echo esc_url( home_url( '/drone-boundary-images/' ) ); ?>" class="rp-btn rp-btn--primary">
                            See Photorealistic Drone Packages ($49 / $99) &rarr;
                        </a>
                    </div>

                    <div class="rp-pricing-card rp-pricing-card--featured">
                        <div class="rp-pricing-badge">Commercial &amp; Infill Favorite</div>
                        <h3 class="rp-pricing-title">Upgrade Option 2: Illustrated Site Render Style</h3>
                        <div class="rp-pricing-target">Best for Commercial Lots, Urban Infill &amp; Muddy Sites</div>
                        <div class="rp-pricing-price">$49 <span>(2-Pack) or</span> $99 <span>(6-Pack)</span></div>
                        <div class="rp-pricing-timing">&#9889; Rendered in 2 to 5 Minutes</div>
                        <ul class="rp-pricing-features">
                            <li><span class="rp-check-icon">&check;</span> <strong>Render 2-Pack ($49, SKU BB2):</strong> 2 Clean Architectural Overhead Site Plans.</li>
                            <li><span class="rp-check-icon">&check;</span> <strong>360&deg; Render 6-Pack ($99, SKU BB1):</strong> 2 Overheads + 4 Cardinal 3D Site Renders.</li>
                            <li><span class="rp-check-icon">&check;</span> <strong>Zero Dirt Distractions:</strong> Replaces mud and construction debris with clean vector form.</li>
                        </ul>
                        <a href="<?php echo esc_url( home_url( '/site-render-boundaries/' ) ); ?>" class="rp-btn rp-btn--secondary">
                            See Illustrated Site Render Packages ($49 / $99) &rarr;
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
                    <div class="rp-section-tag">Zero Technical Hurdles</div>
                    <h2 class="rp-section-title">Works With the Tools Already on Your Phone and Laptop</h2>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card">
                        <h3 class="rp-card-title">1. One-Click Google Earth Launch</h3>
                        <p class="rp-card-text">
                            Our free <code>.kml</code> file opens natively in Google Earth Pro on desktop, Google Earth Web in Chrome, and the Google Earth app on iPhone or Android for walking property lines in the field.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">2. No GIS Training Required</h3>
                        <p class="rp-card-text">
                            You don't need to know what projections, shapefiles, or coordinate systems are. If you can type a street address, you can pull a parcel boundary in 10 seconds.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">3. 1-Click Upgrade to MLS JPGs</h3>
                        <p class="rp-card-text">
                            When that prospect turns into a signed listing agreement, come back and generate the 3000px 2-Pack or 6-Pack from the exact same APN in 5 minutes.
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
                    <div class="rp-section-tag">Real Agent Feedback</div>
                    <h2 class="rp-section-title">Why Agents Stopped Drawing Property Lines by Hand</h2>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">From 45 Mins in Canva to 10 Seconds</span>
                        <p class="rp-testimonial-quote">
                            "I used to spend 45 minutes trying to match county GIS screenshots onto Google Earth so I could show buyers where the back corners of a wooded lot were. Now I grab the Free KML from ReadyPlat in 10 seconds before every showing."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Tyler Brooks</strong>
                            Acreage &amp; Land Specialist &bull; Pine Ridge Realty
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Zero MLS Compliance Warnings</span>
                        <p class="rp-testimonial-quote">
                            "Our local MLS fined an agent in our office $250 for uploading a Google Maps screenshot with hand-drawn red lines and a watermark. We switched the whole team to ReadyPlat's $49 2-Pack—clean 3000px files in 2 minutes with zero compliance issues."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Rebecca Lin</strong>
                            Managing Broker &bull; Summit Property Group
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Converted Free KML Into $99 6-Pack Sale</span>
                        <p class="rp-testimonial-quote">
                            "I downloaded the free KML to walk a 15-acre parcel with a prospective seller on my iPad. Seeing the exact boundary in 3D impressed him so much he signed the listing agreement in his truck, and I ordered the $99 6-Pack that afternoon."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Cole Henderson</strong>
                            Ranch &amp; Land Advisor &bull; Frontier Real Estate
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
                <h2 class="rp-section-title">Pull Your First Official Parcel Boundary in 10 Seconds for $0</h2>
                <p class="rp-section-desc" style="margin-bottom: var(--btx-space-6);">
                    Enter any US address or county APN to download the georeferenced Google Earth KML for free—or order your 3000px MLS aerial package in 5 minutes.
                </p>
                <div class="rp-hero-buttons" style="margin-bottom: var(--btx-space-4);">
                    <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
                        Verify Parcel on Map &mdash; Download Free KML ($0) &rarr;
                    </a>
                    <a href="https://readyplat.com/map?cf6ro4a2lm=c88d107b-bce8-4a78-a98d-d4cd62e671b2&cfd3r3gjpt=3cac6d12-f07a-4637-97eb-4ffee4731a28" class="rp-btn rp-btn--secondary">
                        Order MLS Overhead 2-Pack ($49) &rarr;
                    </a>
                </div>
                <div class="rp-risk-reversal" style="color: var(--btx-gray-400);">
                    <span>&#10003; No credit card required for $0 KML</span>
                    <span>&bull;</span>
                    <span>&#10003; Instant download</span>
                    <span>&bull;</span>
                    <span>&#10003; 3,140+ US counties covered</span>
                </div>
            </div>
        </section>

        <!-- ============================================================
             SECTION 9: OBJECTION-BUSTING FAQ
             ============================================================ -->
        <section class="rp-section">
            <div class="btx-container btx-container--lg">
                <div class="rp-section-header">
                    <div class="rp-section-tag">Objection-Busting FAQs</div>
                    <h2 class="rp-section-title">Common Questions About Drawing Property Lines &amp; KML Files</h2>
                </div>

                <div class="rp-faq-list">
                    <div class="rp-faq-item">
                        <div class="rp-faq-question">1. Can I upload the Free KML file directly to my MLS or Zillow listing?</div>
                        <div class="rp-faq-answer">
                            No. A <code>.kml</code> file is an interactive 3D coordinate file for Google Earth, not an image file. MLS boards and Zillow require high-resolution <code>.jpg</code> or <code>.png</code> images. To get MLS-ready images without watermarks, choose our <strong>$49 2-Pack</strong> or <strong>$99 Complete 6-Pack</strong>.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">2. Why is the Boundary KML free? What is the catch?</div>
                        <div class="rp-faq-answer">
                            There is no catch. Our automated GIS lookup costs us fractions of a cent to run, and we know that once real estate agents see how accurately our system pulls parcel boundaries for their pre-listing research, they come back to order our $49 and $99 rendered aerial packages when their listings go live.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">3. How do I open the KML file on my computer or phone?</div>
                        <div class="rp-faq-answer">
                            On Mac or Windows, install free <strong>Google Earth Pro</strong> and double-click the downloaded <code>.kml</code> file—it will fly directly to your parcel. In Chrome, go to <code>earth.google.com</code>, click <em>Projects &rarr; Import KML file</em>. On iPhone or Android, tap the file to open it inside the free Google Earth mobile app while walking the property.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">4. What if a rural parcel doesn't have a street address?</div>
                        <div class="rp-faq-answer">
                            Our map search accepts both street addresses and county Assessor's Parcel Numbers (APNs). For unaddressed vacant land, simply enter the APN or pan the interactive map to click the parcel directly.
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

<?php
get_footer();