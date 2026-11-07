<?php
/**
 * The template for displaying the guides CPT archive (Topics Index).
 *
 * @package LohnourPro
 */

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$main_args = array(
    'post_type'   => 'user_guide',
    'post_status' => 'publish',
    'post_parent' => 0, // Only fetch parent Topic pages (Courses)
    'paged'       => $paged,
);
$main_query = new WP_Query($main_args);
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">

    <div class="container">
        <?php
        get_template_part( 'template-parts/page-header', null, array(
            'title'       => __( 'User Guides', 'twentytwentyfive-child' ),
            'subtitle'    => __( 'Learn step-by-step with our comprehensive modular guides and resources.', 'twentytwentyfive-child' ),
            'breadcrumbs' => array(
                array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
                array( 'label' => __( 'Guides', 'twentytwentyfive-child' ) ),
            ),
        ) );
        ?>
    </div>

    <div class="blog-classic-container">
        
        <!-- Left: Main Content Column -->
        <div class="blog-main-column">
            
            <?php if ($main_query->have_posts()) : ?>
                <div class="blog-classic-grid">
                    <?php 
                    while ($main_query->have_posts()) : $main_query->the_post(); 
                    ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class("post-classic-card"); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-classic-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-classic-meta">
                                <?php
                                $sectors = get_the_terms(get_the_ID(), 'guide_sector');
                                if (!empty($sectors) && !is_wp_error($sectors)) {
                                    echo '<div class="post-categories" style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 15px;">';
                                    $sector_count = 0;
                                    foreach($sectors as $sec) {
                                        if ($sector_count >= 2) break;
                                        echo '<a href="' . esc_url(get_term_link($sec->term_id, 'guide_sector')) . '" class="post-classic-category">' . esc_html($sec->name) . '</a>';
                                        $sector_count++;
                                    }
                                    echo '</div>';
                                }
                                ?>
                                <h2 class="post-classic-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <span class="post-classic-date"><?php echo get_the_date('F j, Y'); ?></span>
                            </div>
                            
                            <div class="post-classic-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            
                            <div style="text-align: center;">
                                <a href="<?php the_permalink(); ?>" class="post-classic-readmore">View Guide</a>
                            </div>

                        </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination" style="margin-top: 60px;">
                    <?php
                    echo paginate_links(array(
                        'total' => $main_query->max_num_pages,
                        'prev_text' => '&laquo; Newer',
                        'next_text' => 'Older &raquo;',
                    ));
                    ?>
                </div>

            <?php else : ?>
                <p>No guides found.</p>
            <?php endif; wp_reset_postdata(); ?>

        </div> <!-- /.blog-main-column -->

        <!-- Right: Sidebar Column -->
        <?php get_template_part('sidebar-guide'); ?>

    </div>

</main>

<?php
get_footer();
