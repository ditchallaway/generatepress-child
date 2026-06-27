<?php
/**
 * Template Name: Fulfillment Page
 */

// Security Gate
$url_cust_id = isset($_GET['cust']) ? intval($_GET['cust']) : 0;
$order_id = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';
$current_user_id = get_current_user_id();

if ($url_cust_id === 0 || $url_cust_id !== $current_user_id) {
    wp_die('Unauthorized access. Please return to your dashboard and click the download link directly.');
}

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
    const custId = "<?php echo esc_js($current_user_id); ?>";
    const orderId = "<?php echo esc_js($order_id); ?>";
    
    // Using your exact custom R2 domain
    const baseUrl = "https://pics.brokertricks.com"; 
    
    // We check if the main image loads instead of using fetch() to bypass any CORS restrictions on the R2 bucket
    const testImageUrl = `${baseUrl}/cust_${custId}/order_${orderId}/front_elevation.png`;
    const container = document.querySelector('.moonshot-gallery-container');
    
    if (!container) return;

    const img = new Image();
    
    img.onload = () => {
        // The image loaded successfully, render the custom assets
        container.innerHTML = `
            <div style="display: grid; gap: 20px;">
                <img src="${baseUrl}/cust_${custId}/order_${orderId}/front_elevation.png" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" />
                <img src="${baseUrl}/cust_${custId}/order_${orderId}/top_down.png" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" />
                
                <a href="${baseUrl}/cust_${custId}/order_${orderId}/all_assets.zip" download="assets.zip" style="background: var(--button, #1e73be); color: var(--contrast, #ffffff); padding: 15px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 10px; display: block;">
                    Download ZIP Archive
                </a>
            </div>
        `;
    };
    
    img.onerror = () => {
        // The image failed to load (probably 404 because n8n is still working)
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: var(--base-3, #f5f5f5); border-radius: 12px;">
                <h3 style="margin-bottom: 15px;">Generating your graphics...</h3>
                <p>We are rendering your custom assets right now. This page will automatically refresh when they are ready.</p>
            </div>
        `;
        // Auto-refresh the page every 15 seconds to check again
        setTimeout(() => location.reload(), 15000);
    };
    
    // Trigger the load
    img.src = testImageUrl;
});
</script>
