<?php
/**
 * The template for displaying tag archives (Classic Layout).
 *
 * @package LohnourPro
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">

    <div class="container">
        <?php
        get_template_part( 'template-parts/page-header', null, array(
            'title'       => '#' . single_tag_title( '', false ),
            'subtitle'    => tag_description(),
            'breadcrumbs' => array(
                array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
                array( 'label' => __( 'Blog', 'twentytwentyfive-child' ), 'url' => home_url( '/blog' ) ),
                array( 'label' => '#' . single_tag_title( '', false ) ),
            ),
        ) );
        ?>
    </div>

    <div class="blog-classic-container">
        
        <!-- Left: Main Content Column -->
        <div class="blog-main-column">

            <?php if (have_posts()) : ?>
                <div class="blog-classic-grid">
                    <?php 
                    while (have_posts()) : the_post(); 
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
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                     echo '<div class="post-categories" style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 10px;">';
                                     $cat_count = 0;
                                     foreach($categories as $cat) {
                                         if ($cat_count >= 2) break;
                                         echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="post-classic-category">' . esc_html($cat->name) . '</a>';
                                         $cat_count++;
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
                                <a href="<?php the_permalink(); ?>" class="post-classic-readmore">Continue Reading</a>
                            </div>

                        </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination" style="margin-top: 60px;">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '&laquo; Newer',
                        'next_text' => 'Older &raquo;',
                    ));
                    ?>
                </div>

            <?php else : ?>
                <p>No articles found in this category.</p>
            <?php endif; ?>

        </div> <!-- /.blog-main-column -->

        <!-- Right: Sidebar Column -->
        <?php get_template_part('sidebar-blog'); ?>

    </div>

</main><!-- #main -->

<?php
get_footer();
