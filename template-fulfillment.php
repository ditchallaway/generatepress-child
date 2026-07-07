<?php
/**
 * Template Name: Fulfillment Page
 */

// ── Auth Gate ─────────────────────────────────────────────────────
// SureCart customers have real WP accounts (role: sc_customer).
// We trust the WP auth cookie — no need for a 'cust' URL parameter.
$current_user_id = get_current_user_id();

if ( $current_user_id === 0 ) {
    // Not logged in — send to WP login and redirect back here afterwards
    auth_redirect();
    exit;
}

// ── URL Parameters ────────────────────────────────────────────────
// Order ID arrives as 'sc_order' (direct from checkout) or 'order' (from dashboard interceptor)
$order_id = '';
if ( ! empty( $_GET['sc_order'] ) ) {
    $order_id = sanitize_text_field( $_GET['sc_order'] );
} elseif ( ! empty( $_GET['order'] ) ) {
    $order_id = sanitize_text_field( $_GET['order'] );
}

// Pack type: single (overhead_only), double (overhead_north), full
$pack = isset( $_GET['pack'] ) ? sanitize_text_field( $_GET['pack'] ) : '';

get_header(); ?>

<div id="primary" <?php if (function_exists('generate_do_element_classes')) { generate_do_element_classes('content'); } else { echo 'class="content-area"'; } ?>>
	<main id="main" <?php if (function_exists('generate_do_element_classes')) { generate_do_element_classes('main'); } else { echo 'class="site-main"'; } ?>>
        <div class="inside-article" style="padding: 40px 20px;">
            <?php
            // Output page content if there is any
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
            ?>
            <!-- Container for the graphics -->
            <div class="moonshot-gallery-container"></div>
        </div>
	</main>
</div>

<?php 
if (function_exists('generate_construct_sidebars')) {
    generate_construct_sidebars();
}
get_footer(); 
?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const custId  = "<?php echo esc_js( $current_user_id ); ?>";
    const orderId = "<?php echo esc_js( $order_id ); ?>";
    const pack    = "<?php echo esc_js( $pack ); ?>";

    // R2 bucket base URL
    const baseUrl = "https://pics.brokertricks.com";

    // ── Pack → Directions mapping ─────────────────────────────────
    // Must mirror editor/js/config.js PACK_MAP (minus 'map' — that's internal)
    const PACK_MAP = {
        overhead_only:  { label: "Overhead Only",      directions: ["overhead"] },
        overhead_north: { label: "Overhead + North",   directions: ["overhead", "north"] },
        full:           { label: "Full (5 Directions)", directions: ["overhead", "north", "east", "south", "west"] },
        kml_only:       { label: "KML Boundary File",  directions: [] },
    };

    const DIRECTION_LABELS = {
        overhead: "Overhead",
        north:    "North",
        east:     "East",
        south:    "South",
        west:     "West",
    };

    const container = document.querySelector(".moonshot-gallery-container");
    if (!container || !orderId) {
        if (container && !orderId) {
            container.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                    <h3 style="margin-bottom: 15px;">Missing Order ID</h3>
                    <p>We couldn't find your order. Please return to your dashboard and use the download link.</p>
                </div>`;
        }
        return;
    }

    // R2 path prefix
    const pathPrefix = `${baseUrl}/cust_${custId}/order_${orderId}`;

    // ── Readiness Check via ready.txt ─────────────────────────────
    // The n8n workflow uploads ready.txt to R2 after all rendering is complete.
    const readyUrl = `${pathPrefix}/ready.txt?t=${Date.now()}`;

    const readyImg = new Image();

    // We use an Image() object for the probe because a cross-origin fetch()
    // may be blocked by CORS, but <img> requests always go through.
    // ready.txt isn't an image, so onload won't fire — but we can also use
    // fetch with no-cors mode to detect existence.
    checkReady();

    function checkReady() {
        fetch(readyUrl, { mode: "no-cors" })
            .then(response => {
                // no-cors gives opaque response (status 0).
                // If the resource doesn't exist, the browser gets a network error
                // that goes to .catch(). A successful opaque response means the
                // resource exists.
                // However, opaque responses always return status 0 and type "opaque"
                // regardless of the actual HTTP status. We need another approach.
                //
                // Fallback: Try loading the first expected image instead.
                probeFirstImage();
            })
            .catch(() => {
                showWaiting();
            });
    }

    function probeFirstImage() {
        // KML-only orders have no rendered images — probe the KML file instead
        if (pack === "kml_only") {
            const probeUrl = `${pathPrefix}/parcel_boundary.kml?t=${Date.now()}`;
            fetch(probeUrl, { method: "HEAD" })
                .then(r => r.ok ? renderGallery() : showWaiting())
                .catch(() => showWaiting());
            return;
        }

        // Snapshot orders — probe first expected image
        const probeDir = "overhead";
        const probeUrl = `${pathPrefix}/property_${probeDir}.png?t=${Date.now()}`;

        const img = new Image();
        img.onload = () => renderGallery();
        img.onerror = () => showWaiting();
        img.src = probeUrl;
    }

    function showWaiting() {
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                <h3 style="margin-bottom: 15px;">Generating your graphics...</h3>
                <p>We are rendering your custom assets right now. This page will automatically refresh when they are ready.</p>
            </div>`;
        setTimeout(() => location.reload(), 15000);
    }

    function renderGallery() {
        // Resolve the pack config. If pack param is provided, use it.
        // Otherwise, probe all possible images to determine the pack dynamically.
        const packConfig = PACK_MAP[pack];

        if (packConfig) {
            buildGallery(packConfig.directions, packConfig.label);
        } else {
            // Pack not provided — detect which images exist
            detectAndRender();
        }
    }

    function detectAndRender() {
        const allDirs = ["overhead", "north", "east", "south", "west"];
        const found = [];
        let checked = 0;

        allDirs.forEach(dir => {
            const img = new Image();
            img.onload = () => {
                found.push(dir);
                checked++;
                if (checked === allDirs.length) finalize();
            };
            img.onerror = () => {
                checked++;
                if (checked === allDirs.length) finalize();
            };
            img.src = `${pathPrefix}/property_${dir}.png?t=${Date.now()}`;
        });

        function finalize() {
            if (found.length === 0) {
                // No direction images found — check if this is a KML-only order
                fetch(`${pathPrefix}/parcel_boundary.kml?t=${Date.now()}`, { method: "HEAD" })
                    .then(r => {
                        if (r.ok) {
                            buildGallery([], "KML Boundary File");
                        } else {
                            showWaiting();
                        }
                    })
                    .catch(() => showWaiting());
                return;
            }

            // Sort found directions in the canonical order
            const order = ["overhead", "north", "east", "south", "west"];
            found.sort((a, b) => order.indexOf(a) - order.indexOf(b));

            const label = found.length === 1 ? "Overhead Only"
                        : found.length === 2 ? "Overhead + North"
                        : `Full (${found.length} Directions)`;

            buildGallery(found, label);
        }
    }

    function buildGallery(directions, packLabel) {
        const ts = Date.now();

        // Build image cards
        const imageCards = directions.map(dir => `
            <div class="moonshot-item">
                <img src="${pathPrefix}/property_${dir}.png?t=${ts}" alt="${DIRECTION_LABELS[dir] || dir}" />
                <div style="padding: 10px 15px 0; font-weight: bold;">${DIRECTION_LABELS[dir] || dir}</div>
                <div class="moonshot-actions">
                    <a href="${pathPrefix}/property_${dir}.png" class="moonshot-btn" download="property_${dir}.png">Download</a>
                </div>
            </div>
        `).join("");

        // Static map card (available for all packs including kml_only)
        const staticMapCard = `
            <div class="moonshot-item">
                <img src="${pathPrefix}/parcel_boundary.png?t=${ts}" alt="Parcel Boundary Map" />
                <div style="padding: 10px 15px 0; font-weight: bold;">Parcel Boundary Map</div>
                <div class="moonshot-actions">
                    <a href="${pathPrefix}/parcel_boundary.png" class="moonshot-btn" download="parcel_boundary.png">Download Map</a>
                </div>
            </div>
        `;

        // KML file card
        const kmlCard = `
            <div class="moonshot-item">
                <div style="padding: 30px 20px; text-align: center; background: var(--base-3, #f5f5f5); min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--contrast-2, #666); margin-bottom: 12px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <div style="font-size: 18px; font-weight: bold; color: var(--contrast-2, #666);">KML File</div>
                    <div style="font-size: 13px; color: var(--contrast-3, #999); margin-top: 4px;">Property boundary for Google Earth</div>
                </div>
                <div class="moonshot-actions">
                    <a href="${pathPrefix}/parcel_boundary.kml" class="moonshot-btn" download="parcel_boundary.kml">Download KML</a>
                </div>
            </div>
        `;

        container.innerHTML = `
            <style>
            .moonshot-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            .moonshot-item {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            .moonshot-item img {
                width: 100%;
                height: auto;
                display: block;
                border-bottom: 1px solid #eee;
            }
            .moonshot-actions {
                padding: 15px;
                display: flex;
                gap: 10px;
            }
            .moonshot-btn {
                flex: 1;
                background: var(--button, #1e73be);
                color: var(--contrast, #ffffff);
                padding: 10px;
                text-align: center;
                border-radius: 4px;
                text-decoration: none;
                font-size: 14px;
                font-weight: bold;
                cursor: pointer;
                border: none;
                display: inline-block;
                transition: opacity 0.2s;
            }
            .moonshot-btn:hover {
                opacity: 0.85;
            }
            .moonshot-header {
                text-align: center;
                margin-bottom: 30px;
            }
            </style>
            
            <div class="moonshot-header">
                <h2>${directions.length > 0 ? 'Your Assets are Ready!' : 'Your KML File is Ready!'}</h2>
                <p>${directions.length > 0 
                    ? `Preview and download your ${packLabel} images below.`
                    : 'Download your property boundary file below. Open it in Google Earth or any mapping software.'}</p>
            </div>
            
            <div class="moonshot-gallery">
                ${imageCards}
                ${staticMapCard}
                ${kmlCard}
            </div>
        `;
    }
});
</script>
