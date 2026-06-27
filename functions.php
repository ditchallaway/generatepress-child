<?php  

function enqueue_jquery() {
        // Enqueue jQuery (WordPress's built-in version)
        wp_enqueue_script('jquery');
    }

 add_action('wp_enqueue_scripts', 'enqueue_jquery');

/**
 * SureCart Download Button Interceptor for Fulfillment Page
 */
add_action('wp_footer', function() {
    // Only run on the dashboard page
    if (!is_page('dash')) return; 
    
    $user_id = get_current_user_id();
    ?>
    <script>
    (function() {
        const custId = "<?php echo esc_js($user_id); ?>";
        
        // 1. THE NUCLEAR CLICK INTERCEPTOR
        // Attaching to 'window' with 'true' (Capture Phase) fires BEFORE SureCart's scripts
        window.addEventListener('click', function(e) {
            // Check if what was clicked (or a child element like an icon) belongs to our link
            const link = e.target.closest('a[href*="/fulfillment"]');
            
            if (link) {
                // Kill the event completely so SureCart never sees it
                e.preventDefault(); 
                e.stopPropagation(); 
                e.stopImmediatePropagation(); 
                
                // Extract order ID
                const urlParams = new URLSearchParams(window.location.search);
                const orderId = urlParams.get('id') || 'unknown';
                
                // Build the final URL securely
                const separator = link.href.includes('?') ? '&' : '?';
                let finalUrl = link.href;
                
                // Prevent duplicate parameters if clicked multiple times
                if (!finalUrl.includes('cust=')) {
                    finalUrl += `${separator}cust=${custId}&order=${orderId}`;
                }
                
                // Force a hard redirect, bypassing any SureCart routing/download logic
                window.location.href = finalUrl;
            }
        }, true); // <-- This 'true' is the magic bullet
        
        // 2. DOM CLEANUP
        // We still run the observer just to strip the HTML5 'download' attribute 
        // in case the user right-clicks to "Open in New Tab"
        const observer = new MutationObserver(() => {
            document.querySelectorAll('a[href*="/fulfillment"]').forEach(link => {
                if (!link.dataset.cleaned) {
                    link.removeAttribute('download');
                    link.dataset.cleaned = "true";
                }
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <?php
});