<?php
/**
 * The template for displaying all single posts (Classic Layout)
 *
 * @package LohnourPro
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">

    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <div class="container">
            <?php
            $post_breadcrumbs = array(
                array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
                array( 'label' => __( 'Blog', 'twentytwentyfive-child' ), 'url' => home_url( '/blog' ) ),
            );
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $post_breadcrumbs[] = array( 'label' => $categories[0]->name, 'url' => get_category_link( $categories[0]->term_id ) );
            }
            $post_breadcrumbs[] = array( 'label' => get_the_title() );

            $post_subtitle = has_excerpt() ? get_the_excerpt() : '';

            get_template_part( 'template-parts/page-header', null, array(
                'title'       => get_the_title(),
                'subtitle'    => $post_subtitle,
                'breadcrumbs' => $post_breadcrumbs,
            ) );
            ?>
        </div>

        <div class="blog-classic-container">
            
            <!-- Left: Main Content Column -->
            <div class="blog-main-column">
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <!-- Featured Image (First in column) -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="single-classic-image" style="margin-bottom: var(--spacing-md);">
                            <?php the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: auto; display: block; border-radius: var(--radius-md);' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Left-aligned Author Meta -->
                    <div style="text-align: left; margin-bottom: 15px;">
                        <span class="post-classic-date" style="display: block; text-align: left;">By <a href="<?php echo esc_url( $author_link ); ?>" style="color: inherit; text-decoration: underline; font-weight: 500;"><?php echo esc_html( $author_name ); ?></a> &bull; <?php echo get_the_date('F j, Y'); ?></span>
                    </div>

                    <!-- Left-aligned Share Buttons -->
                    <div class="post-share-classic" style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin-bottom: var(--spacing-md); text-align: left;">
                        <span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500;">Share this article:</span>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Twitter" style="display: inline-flex; align-items: center; justify-content: center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Facebook" style="display: inline-flex; align-items: center; justify-content: center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" aria-label="Share on LinkedIn" style="display: inline-flex; align-items: center; justify-content: center;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    </div>

                    <div class="single-reading-content" style="margin-bottom: var(--spacing-md);">
                        <?php the_content(); ?>
                    </div>
                    
                    <footer class="post-footer-classic" style="margin-top: var(--spacing-md); padding-top: var(--spacing-md);">
                        <!-- Bottom Left-aligned Share Buttons -->
                        <div class="post-share-classic" style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin-top: 0; margin-bottom: 25px; text-align: left;">
                            <span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500;">Share this article:</span>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Twitter" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Facebook" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" aria-label="Share on LinkedIn" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                        </div>
                        
                        <!-- Categories & Tags -->
                        <div class="post-tags-classic" style="text-align: left; margin-bottom: 20px;">
                            <?php 
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<div style="margin-bottom: 12px; display: flex; align-items: center; flex-wrap: wrap;"><span style="font-size: 0.9rem; color: var(--color-text); font-weight: 500; margin-right: 12px;">Categories:</span> ';
                                foreach($categories as $cat) {
                                    echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a>';
                                }
                                echo '</div>';
                            }
                            
                            $tags = get_the_tags();
                            if ($tags) {
                                echo '<div style="display: flex; align-items: center; flex-wrap: wrap;"><span style="font-size: 0.9rem; color: var(--color-text); font-weight: 500; margin-right: 12px;">Tags:</span> ';
                                foreach($tags as $tag) {
                                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </footer>
                    
                </article>
                
                <?php
            endwhile; // End of the loop.
            ?>

        </div> <!-- /.blog-main-column -->

        <!-- Right: Sidebar Column -->
        <?php get_template_part('sidebar-blog'); ?>

    </div> <!-- /.blog-classic-container -->

    <!-- Related Posts (Positioned below main column & sidebar on mobile & desktop) -->
    <div class="blog-classic-container" style="margin-top: 0; padding-top: 0;">
        <div style="grid-column: 1 / -1;">
            <section class="related-posts-classic" style="margin-top: var(--spacing-md);">
                <h3>You Might Also Like</h3>
                <div class="related-grid">
                    <?php
                    $categories = get_the_category();
                    $cat_ids = array();
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            $cat_ids[] = $category->term_id;
                        }
                    }
                    $related_args = array(
                        'category__in'   => $cat_ids,
                        'post__not_in'   => array( get_the_ID() ),
                        'posts_per_page' => 3,
                        'orderby'        => 'rand'
                    );
                    $related_query = new WP_Query( $related_args );
                    if ( $related_query->have_posts() ) :
                        while ( $related_query->have_posts() ) : $related_query->the_post();
                        ?>
                            <div class="related-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php endif; ?>
                                <h4><a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a></h4>
                                <div style="text-align: center; margin-top: 4px;">
                                    <?php
                                    $rel_cats = get_the_category();
                                    if (!empty($rel_cats)) {
                                        echo '<a href="' . esc_url(get_category_link($rel_cats[0]->term_id)) . '" class="post-classic-category" style="font-size: 0.7rem; padding: 2px 8px;">' . esc_html($rel_cats[0]->name) . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </section>
        </div>
    </div>

</main>

<?php get_footer(); ?>
