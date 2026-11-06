<?php
/**
 * Template Name: ReadyPlat - Drone Aerials (Pedro Cortes)
 * Description: 9-Section Pedro Cortes Conversion Framework for Drone Style Property Boundary Images (2-Pack AA2 & 6-Pack AA1).
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
                    The Automated GIS &amp; 3D Boundary Rendering Engine For Land &amp; Residential Listing Agents
                </div>
                
                <h1 class="rp-hero-title">Get MLS-Ready Drone Boundary Aerials in 5 Minutes Without Hiring a $600 Pilot or Graphic Designer</h1>
                
                <p class="rp-hero-subtitle">
                    ReadyPlat combines official county tax assessor GIS survey polygons with an automated 3D terrain camera engine so listing agents and coordinators can turn any US street address or APN into 3000px boundary photos on the same day they win the listing.
                </p>

                <div class="rp-hero-ctv-group">
                    <div class="rp-hero-buttons">
                        <a href="#pricing" class="rp-btn rp-btn--primary">
                            Verify Your Parcel on the Map &mdash; Get 6 Aerials in 5 Minutes ($99) &rarr;
                        </a>
                        <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--secondary">
                            Test Any Parcel First: Download Free Google Earth KML ($0)
                        </a>
                    </div>
                    <div class="rp-risk-reversal">
                        <span>&#10003; Interactive APN map confirmation before checkout</span>
                        <span>&bull;</span>
                        <span>&#10003; No subscription required</span>
                        <span>&bull;</span>
                        <span>&#10003; 3000px MLS-approved JPGs</span>
                    </div>
                </div>

                <!-- Visual "Aha Moment" Transformation Spec -->
                <div class="rp-aha-showcase">
                    <div class="rp-aha-grid">
                        <div class="rp-aha-panel">
                            <span class="rp-aha-tag rp-aha-tag--before">Status Quo: Raw County GIS / Google Screenshot</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/before.jpg' ); ?>" alt="Raw flat satellite map before boundary rendering" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>What buyers ignore:</strong> Flat 2D screenshot with zero terrain depth, ambiguous lot corners, and browser UI clutter that gets flagged by MLS compliance.
                            </div>
                        </div>

                        <div class="rp-aha-arrow">
                            <div class="rp-aha-arrow-circle">&rarr;</div>
                            <span>180 Seconds</span>
                        </div>

                        <div class="rp-aha-panel rp-aha-panel--after">
                            <span class="rp-aha-tag rp-aha-tag--after">ReadyPlat Output: 3D Oblique Drone Style (AA1 / AA2)</span>
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/hero-aerial.jpg' ); ?>" alt="Photorealistic 3D drone boundary aerial render" class="rp-aha-img">
                            <div class="rp-aha-caption">
                                <strong>What sells the parcel:</strong> High-contrast parcel boundary locked to official county survey coordinates over 3D terrain elevation at 3000px resolution.
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
                        <div class="rp-metric-number">3,140+</div>
                        <div class="rp-metric-label">US County Tax Assessor GIS Databases Connected</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">2&ndash;5 Mins</div>
                        <div class="rp-metric-label">Automated Browser &amp; Email Turnaround Time</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">3000px</div>
                        <div class="rp-metric-label">Print, Zillow, Land.com &amp; MLS Compliant Resolution</div>
                    </div>
                    <div class="rp-metric-box">
                        <div class="rp-metric-number">$501 Saved</div>
                        <div class="rp-metric-label">Average Savings vs. Rural Drone Pilot Dispatch</div>
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
                    <div class="rp-section-tag">The Cost of the Old Way</div>
                    <h2 class="rp-section-title">Why Getting Property Line Photos Delays Your Listing by 5 Days</h2>
                    <p class="rp-section-desc">
                        Every day your acreage or residential listing sits in draft waiting on boundary photos is a day you aren't collecting buyer inquiries. Today, listing agents are stuck with three broken options:
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #1: Local Drone Pilot</span>
                        <h3 class="rp-card-title">Paying $550+ and Waiting 5 Days for Clear Weather</h3>
                        <p class="rp-card-text">
                            Hiring a licensed drone photographer for a rural tract or infill lot means paying mileage surcharges, waiting for wind or rain to clear, dealing with FAA airport airspace locks, and still paying an extra $75 fee for someone to manually trace property lines in Photoshop.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: $450&ndash;$800 out of your marketing budget + 5-day listing delay
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #2: DIY Canva / Google Earth</span>
                        <h3 class="rp-card-title">Tracing Crooked Lines by Hand From County PDF Plats</h3>
                        <p class="rp-card-text">
                            Taking a screenshot of Google Maps and eyeballing boundary lines in Canva or Paint creates parallax distortion over hills. Worse, MLS compliance boards fine agents for uploading screenshots with visible Google watermarks or unverified borders.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: 45 minutes of wasted coordinator time + MLS compliance takedowns
                        </div>
                    </div>

                    <div class="rp-card rp-card--pain">
                        <span class="rp-card-badge rp-card-badge--danger">Old Way #3: Overseas Freelancers</span>
                        <h3 class="rp-card-title">Back-and-Forth Revision Emails Over the Wrong Parcel</h3>
                        <p class="rp-card-text">
                            Sending a tax map PDF to an offshore graphic editor results in 24-hour turnarounds where the freelancer outlines the neighbor's pasture by mistake because they cannot read US county assessor GIS coordinates.
                        </p>
                        <div class="rp-cost-callout">
                            Cost of Inaction: Seller embarrassment at the listing presentation + missed weekend traffic
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
                    <div class="rp-section-tag">The Automated 3-Step Mechanism</div>
                    <h2 class="rp-section-title">How ReadyPlat Generates Verified 3D Parcel Aerials in 5 Minutes</h2>
                    <p class="rp-section-desc">
                        No CAD files, no KML uploads, and no design briefs. Our robotic rendering pipeline handles the entire geospatial workflow in three steps:
                    </p>
                </div>

                <div class="rp-grid-3" style="margin-bottom: var(--btx-space-10);">
                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 1: Interactive Parcel Lock</span>
                        <h3 class="rp-card-title">Type the Street Address or County APN</h3>
                        <p class="rp-card-text">
                            Enter the property address or Assessor's Parcel Number on our live map. Our query engine pulls the exact legal polygon directly from county tax records and highlights it on your screen so you can visually confirm the boundary before paying a cent.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 2: Robotic 3D Terrain Framing</span>
                        <h3 class="rp-card-title">Cesium 3D Camera Spins 360&deg; Around the Parcel</h3>
                        <p class="rp-card-text">
                            Once confirmed, our cloud engine projects your county GIS boundary onto 3D satellite elevation data. Automated cameras calculate parcel bounding boxes to capture 2 top-down overheads (tight and wide) plus 4 cardinal 3D oblique angles at a 45-degree pitch.
                        </p>
                    </div>

                    <div class="rp-card rp-card--step">
                        <span class="rp-card-badge rp-card-badge--primary">Step 3: Instant MLS Delivery</span>
                        <h3 class="rp-card-title">Download 3000px JPGs in Your Browser Dashboard</h3>
                        <p class="rp-card-text">
                            Within 2 to 5 minutes, your fulfillment dashboard updates in real time as each angle finishes rendering. Download individual 3000px images or grab the complete ZIP archive to drop straight into MLS, Zillow, Land.com, or your listing presentation deck.
                        </p>
                    </div>
                </div>

                <!-- Visual Proof of the 4 Cardinal Angles (Crucial 20% Showcase) -->
                <div style="background: #ffffff; border: 1px solid var(--btx-border-color); border-radius: var(--btx-radius-lg); padding: var(--btx-space-6);">
                    <h4 style="margin: 0 0 var(--btx-space-4) 0; font-size: var(--btx-text-lg); font-weight: 800; text-align: center;">
                        Inside the Complete 6-Pack: 4 Cardinal 3D Obliques + 2 Top-Down Overheads
                    </h4>
                    <div class="rp-grid-4">
                        <div class="rp-angle-card">
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/north.jpg' ); ?>" alt="North-Facing 3D Boundary Aerial">
                            <div class="rp-angle-card-label">
                                <span>A3: North-Facing 3D Oblique</span>
                                <span style="color: var(--btx-color-primary-600);">3000px</span>
                            </div>
                        </div>
                        <div class="rp-angle-card">
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/overhead.jpg' ); ?>" alt="Top-Down Overhead Boundary Aerial">
                            <div class="rp-angle-card-label">
                                <span>A1/A2: Top-Down Overhead</span>
                                <span style="color: var(--btx-color-primary-600);">3000px</span>
                            </div>
                        </div>
                        <div class="rp-angle-card">
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/south.jpg' ); ?>" alt="South-Facing 3D Boundary Aerial">
                            <div class="rp-angle-card-label">
                                <span>A5: South-Facing 3D Oblique</span>
                                <span style="color: var(--btx-color-primary-600);">3000px</span>
                            </div>
                        </div>
                        <div class="rp-angle-card">
                            <img src="<?php echo esc_url( $theme_uri . '/design-prototype/assets/img/west.jpg' ); ?>" alt="West-Facing 3D Boundary Aerial">
                            <div class="rp-angle-card-label">
                                <span>A6: West-Facing 3D Oblique</span>
                                <span style="color: var(--btx-color-primary-600);">3000px</span>
                            </div>
                        </div>
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
                    <h2 class="rp-section-title">Why Top Producers Replace Manual Workflows With ReadyPlat</h2>
                </div>

                <div style="overflow-x: auto;">
                    <table class="rp-compare-table">
                        <thead>
                            <tr>
                                <th>Comparison Criteria</th>
                                <th>Hiring a Local Drone Pilot</th>
                                <th>DIY Screenshot + Canva</th>
                                <th class="rp-col-highlight">ReadyPlat Automated Engine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Turnaround Time</strong></td>
                                <td>3 to 7 Days (Weather Dependent)</td>
                                <td>45+ Minutes of Manual Tracing</td>
                                <td class="rp-col-highlight">2 to 5 Minutes (24/7 Instant)</td>
                            </tr>
                            <tr>
                                <td><strong>Cost Per Listing</strong></td>
                                <td>$450 to $800 + Travel Fees</td>
                                <td>$0 (Costs Coordinator Hourly Wage)</td>
                                <td class="rp-col-highlight">$49 (2-Pack) or $99 (6-Pack)</td>
                            </tr>
                            <tr>
                                <td><strong>Boundary Accuracy</strong></td>
                                <td>Pilot Eyeballs Fence Lines in Editor</td>
                                <td>High Risk of Parallax Error</td>
                                <td class="rp-col-highlight">Locked to County Tax Assessor GIS</td>
                            </tr>
                            <tr>
                                <td><strong>Airport / No-Fly Zones</strong></td>
                                <td>Grounded by FAA Controlled Airspace</td>
                                <td>2D Flat View Only</td>
                                <td class="rp-col-highlight">Zero Flight Restrictions Nationwide</td>
                            </tr>
                            <tr>
                                <td><strong>MLS &amp; Portal Compliance</strong></td>
                                <td>Compliant</td>
                                <td>Flagged for Watermarks / Low Res</td>
                                <td class="rp-col-highlight">100% Compliant 3000px Clean JPGs</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pricing Selection Cards (Group A vs Group B Jobs-to-be-Done) -->
                <div id="pricing" style="margin-top: var(--btx-space-12);">
                    <div class="rp-section-header">
                        <div class="rp-section-tag">Choose Your Job-To-Be-Done</div>
                        <h2 class="rp-section-title">Select Your Photorealistic Drone Boundary Package</h2>
                        <p class="rp-section-desc">
                            Pay per parcel with zero recurring subscriptions. Verify your exact boundary polygon on the map before completing checkout.
                        </p>
                    </div>

                    <div class="rp-pricing-container">
                        <!-- JOB A: 2-PACK ($49) -->
                        <div class="rp-pricing-card">
                            <h3 class="rp-pricing-title">Property Boundary 2-Pack</h3>
                            <div class="rp-pricing-target">Job A: For Listing Presentations, Flyers &amp; Hero Overviews</div>
                            
                            <div class="rp-pricing-price">$49 <span>/ one-time per parcel</span></div>
                            <div class="rp-pricing-timing">&#9889; Delivered in 2 Minutes</div>

                            <ul class="rp-pricing-features">
                                <li><span class="rp-check-icon">&check;</span> <strong>1x Close-Up Overhead Aerial (SKU A1):</strong> Framed tightly around the entire parcel boundary without clipping corners.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>1x Wide-Angle Context Aerial (SKU A2):</strong> Zoomed out to reveal road frontage, highway access, and neighboring parcels.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Official County GIS Overlay:</strong> Crisp, high-visibility boundary stroke locked to tax assessor coordinates.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Built for Non-Designers:</strong> Ready to drop straight into MLS photo slots, Canva flyers, and pitch packets.</li>
                            </ul>

                            <a href="https://readyplat.com/map?cf6ro4a2lm=c88d107b-bce8-4a78-a98d-d4cd62e671b2&cfd3r3gjpt=3cac6d12-f07a-4637-97eb-4ffee4731a28" class="rp-btn rp-btn--primary">
                                Verify Parcel on Map &mdash; Get 2-Pack in 2 Mins ($49) &rarr;
                            </a>
                        </div>

                        <!-- JOB B: 6-PACK ($99) -->
                        <div class="rp-pricing-card rp-pricing-card--featured">
                            <div class="rp-pricing-badge">Best Value &bull; Replaces a $600 Drone Shoot</div>
                            <h3 class="rp-pricing-title">Complete Listing 6-Pack</h3>
                            <div class="rp-pricing-target">Job B: Take Land &amp; Acreage Listings Live Today</div>
                            
                            <div class="rp-pricing-price">$99 <span>/ one-time per parcel</span></div>
                            <div class="rp-pricing-timing">&#9889; Delivered in 5 Minutes</div>

                            <ul class="rp-pricing-features">
                                <li><span class="rp-check-icon">&check;</span> <strong>Includes Both Top-Down Aerials (A1 + A2):</strong> Tight boundary shot plus wide neighborhood context view.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Plus 4x Cardinal 3D Oblique Views (A3&ndash;A6):</strong> Angled 3D drone perspectives looking North, East, South, and West.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Shows True Terrain &amp; Elevation:</strong> Buyers see ridge lines, valleys, tree cover, and road slopes from every compass direction.</li>
                                <li><span class="rp-check-icon">&check;</span> <strong>Protects Narrow Commission Margins:</strong> Full 6-photo MLS gallery for 85% less than dispatching a rural drone pilot.</li>
                            </ul>

                            <a href="https://readyplat.com/map?cf6ro4a2lm=bd0197ce-24d8-459e-b46d-1ab48c9d5bc4&cfd3r3gjpt=df31d8f8-6168-4024-b713-6e882421fd6d" class="rp-btn rp-btn--primary">
                                Verify Parcel on Map &mdash; Get 6-Pack in 5 Mins ($99) &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Cross-Flavor Routing Banner -->
                    <div class="rp-flavor-banner">
                        <h4>Listing a Commercial Lot, Urban Infill, or Muddy Construction Site?</h4>
                        <p>
                            When construction debris, dirt ruts, or messy neighboring yards distract buyers from location value, switch to our <strong>Illustrated Site Render Style</strong> for clean architectural site plans.
                        </p>
                        <a href="<?php echo esc_url( home_url( '/site-render-boundaries/' ) ); ?>" class="rp-btn rp-btn--secondary">
                            Switch to Illustrated Site Render Packages &rarr;
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
                    <div class="rp-section-tag">Zero Switching Friction</div>
                    <h2 class="rp-section-title">No Software to Learn. No Subscriptions. Live in 3 Clicks.</h2>
                    <p class="rp-section-desc">
                        You don't need to train your listing coordinator on GIS software or commit to a $199/month mapping license.
                    </p>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-card">
                        <h3 class="rp-card-title">1. Zero File Uploads</h3>
                        <p class="rp-card-text">
                            You never have to hunt down shapefiles or county plat PDFs. Just type the address or APN and click the parcel on our interactive map.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">2. Drop-In MLS Compatibility</h3>
                        <p class="rp-card-text">
                            Every image is delivered as a standard high-resolution JPG that uploads directly into Paragon, Matrix, Flexmls, Zillow, and Canva without resizing.
                        </p>
                    </div>
                    <div class="rp-card">
                        <h3 class="rp-card-title">3. Pay Only When You List</h3>
                        <p class="rp-card-text">
                            No monthly seat fees or annual contracts. Order a $49 2-Pack or $99 6-Pack only when you have a listing or presentation on deck.
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
                    <div class="rp-section-tag">Verified Agent Outcomes</div>
                    <h2 class="rp-section-title">How Real Estate Teams Use ReadyPlat to List Faster</h2>
                </div>

                <div class="rp-grid-3">
                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Saved $550 &amp; Listed 4 Days Faster</span>
                        <p class="rp-testimonial-quote">
                            "I had a 42-acre recreational tract 50 miles outside town. Two drone pilots quoted me $650 with a 5-day wait. I typed the APN into ReadyPlat, confirmed the polygon, and had 6 cardinal 3D aerials uploaded to the MLS in 4 minutes for $99. We had two cash offers by Sunday."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Marcus Vance</strong>
                            Managing Land Broker &bull; Timber &amp; Ranch Realty
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Reclaimed 40 Mins Per Listing</span>
                        <p class="rp-testimonial-quote">
                            "As a listing coordinator managing 18 active files a month, I used to dread drawing parcel lines in Canva from county GIS screenshots. ReadyPlat's $49 2-Pack gives me a tight shot and a wide context shot in 2 minutes that never get flagged by our MLS board."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>Sarah Jenkins</strong>
                            Senior Listing Coordinator &bull; Apex Residential Group
                        </div>
                    </div>

                    <div class="rp-testimonial-card">
                        <span class="rp-testimonial-metric">Won Listing in Airport No-Fly Zone</span>
                        <p class="rp-testimonial-quote">
                            "Our seller's 3-acre infill lot sat inside controlled airport airspace where drone pilots couldn't fly without a 30-day FAA waiver. I walked into the listing presentation with ReadyPlat's 6-Pack printed in my pitch book and won the exclusive agreement on the spot."
                        </p>
                        <div class="rp-testimonial-author">
                            <strong>David K. Miller</strong>
                            Associate Broker &bull; Metro Land &amp; Homes
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
                <h2 class="rp-section-title">Take Your Listing Live Today With Verified 3D Boundary Aerials</h2>
                <p class="rp-section-desc" style="margin-bottom: var(--btx-space-6);">
                    Confirm your exact parcel boundary on our interactive map before you pay. Your high-resolution aerial package is rendered and delivered in under 5 minutes.
                </p>
                <div class="rp-hero-buttons" style="margin-bottom: var(--btx-space-4);">
                    <a href="https://readyplat.com/map?cf6ro4a2lm=bd0197ce-24d8-459e-b46d-1ab48c9d5bc4&cfd3r3gjpt=df31d8f8-6168-4024-b713-6e882421fd6d" class="rp-btn rp-btn--primary">
                        Verify Parcel &amp; Get Complete 6-Pack ($99) &rarr;
                    </a>
                    <a href="https://readyplat.com/map?cf6ro4a2lm=c88d107b-bce8-4a78-a98d-d4cd62e671b2&cfd3r3gjpt=3cac6d12-f07a-4637-97eb-4ffee4731a28" class="rp-btn rp-btn--secondary">
                        Verify Parcel &amp; Get Overhead 2-Pack ($49) &rarr;
                    </a>
                </div>
                <div class="rp-risk-reversal" style="color: var(--btx-gray-400);">
                    <span>&#10003; Visual APN confirmation before payment</span>
                    <span>&bull;</span>
                    <span>&#10003; Instant browser + email delivery</span>
                    <span>&bull;</span>
                    <span>&#10003; Free re-render if parcel doesn't match county records</span>
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
                    <h2 class="rp-section-title">Everything You Need to Know Before Ordering</h2>
                </div>

                <div class="rp-faq-list">
                    <div class="rp-faq-item">
                        <div class="rp-faq-question">1. How do I know the automated engine will outline the exact right parcel?</div>
                        <div class="rp-faq-answer">
                            You never buy blind. Before entering payment details, our interactive map highlights the official county tax assessor polygon for your address or APN and asks you to click <strong>"Yes, I Confirm Parcel"</strong>. What you confirm on the map is the exact geometry our 3D engine renders.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">2. Will my local MLS board accept these boundary images?</div>
                        <div class="rp-faq-answer">
                            Yes. Unlike raw Google Maps screenshots which get rejected for visible UI chrome, copyright watermarks, and low resolution, ReadyPlat outputs clean 3000px JPG files formatted specifically for MLS, Zillow, Realtor.com, and Land.com galleries.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">3. Should I choose the $49 2-Pack or the $99 Complete 6-Pack?</div>
                        <div class="rp-faq-answer">
                            Choose the <strong>$49 2-Pack (SKU AA2)</strong> if you already have ground photos and just need a clean top-down boundary shot and wide neighborhood overview for your flyer or listing presentation. Choose the <strong>$99 Complete 6-Pack (SKU AA1)</strong> if you are listing vacant land, acreage, or a rural property and want to replace a full drone shoot with 4 cardinal 3D oblique views (North, East, South, West) plus the 2 overheads.
                        </div>
                    </div>

                    <div class="rp-faq-item">
                        <div class="rp-faq-question">4. Am I locked into a monthly software subscription?</div>
                        <div class="rp-faq-answer">
                            Never. Every order is a simple one-time transaction per parcel ($49 or $99) that you can expense directly to your listing marketing budget.
                        </div>
                    </div>
                </div>

                <!-- Alternative Low-Friction CTA (Group C Lead Magnet) -->
                <div class="rp-flavor-banner" style="background: var(--btx-color-primary-50); border-color: var(--btx-color-primary-400);">
                    <h4>Not Ready to Order Listing Photos Yet? Test Any Parcel for $0.</h4>
                    <p>
                        Enter any US address or APN to download our <strong>Free Google Earth Boundary KML (SKU D1)</strong> in 10 seconds. Inspect the exact county survey lines in 3D on Google Earth Desktop or Mobile at zero cost.
                    </p>
                    <a href="https://readyplat.com/map?cf6ro4a2lm=26f42ef2-efe5-44a5-85ab-b7fe5674cfc5&cfd3r3gjpt=372e0fc9-d4dd-455b-871a-fe188b08c5e7" class="rp-btn rp-btn--primary">
                        Download Free Google Earth Boundary KML ($0) &rarr;
                    </a>
                </div>
            </div>
        </section>

    </main>
</div>

<?php
get_footer();