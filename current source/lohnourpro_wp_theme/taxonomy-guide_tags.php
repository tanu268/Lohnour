<?php
/**
 * The template for displaying guide tags.
 *
 * @package LohnourPro
 */

get_header();

$term = get_queried_object();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$main_args = array(
    'post_type'   => 'user_guide',
    'post_status' => 'publish',
    'post_parent' => 0, // Only fetch parent Topic pages (Courses)
    'paged'       => $paged,
    'tax_query'   => array(
        array(
            'taxonomy' => 'guide_tags',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        )
    )
);
$main_query = new WP_Query($main_args);
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">

    <div class="container">
        <?php
        get_template_part( 'template-parts/page-header', null, array(
            'title'       => sprintf( __( 'Tag: %s', 'twentytwentyfive-child' ), $term->name ),
            'subtitle'    => ! empty( $term->description ) ? $term->description : sprintf( __( 'Browse user guides tagged with %s.', 'twentytwentyfive-child' ), $term->name ),
            'breadcrumbs' => array(
                array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
                array( 'label' => __( 'Guides', 'twentytwentyfive-child' ), 'url' => home_url( '/guides/' ) ),
                array( 'label' => '#' . $term->name ),
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
                                <div class="post-categories" style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 15px;">
                                    <span class="post-classic-category"><?php echo esc_html($term->name); ?></span>
                                </div>
                                <h2 class="post-classic-title" style="margin-top: 0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
                <p>No guides found with this tag.</p>
            <?php endif; wp_reset_postdata(); ?>

        </div> <!-- /.blog-main-column -->

        <!-- Right: Sidebar Column -->
        <?php get_template_part('sidebar-guide'); ?>

    </div>

</main>

<?php
get_footer();
