<?php
/**
 * Template Name: Full Width App / Dashboard
 * Description: Edge-to-edge container layout for web applications, fulfillment dashboards, and responsive portal views.
 *
 * @package GeneratePressChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area btx-app-layout">
    <main id="main" class="site-main">
        <div class="btx-container btx-container--xl">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'btx-app-article' ); ?>>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </div>
    </main>
</div>

<?php
get_footer();
