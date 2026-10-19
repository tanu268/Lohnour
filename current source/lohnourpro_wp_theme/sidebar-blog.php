<?php
/**
 * The template for the classic blog sidebar.
 *
 * @package LohnourPro
 */
?>
<aside class="blog-sidebar-column">
    
    <?php
    if ( is_singular( 'post' ) ) :
        $author_id = get_post_field( 'post_author', get_the_ID() );
        
        $first_name = get_the_author_meta( 'first_name', $author_id );
        $last_name  = get_the_author_meta( 'last_name', $author_id );
        $author_name = trim( $first_name . ' ' . $last_name );
        
        if ( empty( $author_name ) ) {
            $author_name = get_the_author_meta( 'display_name', $author_id );
        }
        
        $author_bio = get_the_author_meta( 'description', $author_id );
        if ( empty( $author_bio ) ) {
            $author_bio = 'We share insights on technology, workflows, and building premium web experiences.';
        }
    ?>
    <div class="sidebar-widget widget-about">
        <h4 class="widget-title-classic">Author</h4>
        <div style="display: flex; justify-content: center; margin-bottom: 15px;">
            <?php 
            // Fetch avatar from WordPress profile
            echo get_avatar( $author_id, 150, '', '', array( 'class' => 'author-avatar', 'style' => 'border-radius: 50%; max-width: 150px; height: auto;' ) ); 
            ?>
        </div>
        <p><?php echo wp_kses_post($author_bio); ?></p>
        
        <div style="margin-top: 15px; text-align: center;">
            <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="btn btn-outline" style="padding: 0.4rem 1.25rem; font-size: 0.75rem; display: inline-block; border-radius: var(--radius-full);">View All Posts</a>
        </div>
        
        <div class="widget-socials" style="margin-top: 20px; justify-content: center;">
            <?php
            $socials = array(
                'linkedin'  => array('label' => 'LinkedIn', 'svg' => '<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>'),
                'twitter'   => array('label' => 'Twitter/X', 'svg' => '<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>'),
                'instagram' => array('label' => 'Instagram', 'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>'),
                'facebook'  => array('label' => 'Facebook', 'svg' => '<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>'),
            );
            foreach ($socials as $key => $data) {
                $url = get_the_author_meta( $key, $author_id );
                if ( !empty($url) ) {
                    echo '<a href="' . esc_url($url) . '" aria-label="' . esc_attr($data['label']) . '" target="_blank" rel="noopener noreferrer"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">' . $data['svg'] . '</svg></a>';
                }
            }

            // Check for Website explicitly
            $website = get_the_author_meta( 'user_url', $author_id );
            if ( !empty($website) ) {
                echo '<a href="' . esc_url($website) . '" aria-label="Website" target="_blank" rel="noopener noreferrer"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg></a>';
            }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Newsletter Widget -->
    <div class="sidebar-widget widget-newsletter">
        <h4 class="widget-title-classic">Newsletter</h4>
        <?php if ( is_active_sidebar( 'blog-sidebar-newsletter' ) ) : ?>
            <?php dynamic_sidebar( 'blog-sidebar-newsletter' ); ?>
        <?php else : ?>
            <p class="post-classic-excerpt">Subscribe to get the latest updates.</p>
            <p style="font-size: 0.8rem; opacity: 0.5; margin-top: 10px;">[Add your Brevo Widget here via WP Admin > Appearance > Widgets > Blog Sidebar Newsletter]</p>
        <?php endif; ?>
    </div>

    <!-- Recent Posts Widget -->
    <div class="sidebar-widget widget-recent-posts">
        <h4 class="widget-title-classic">Featured Posts</h4>
        <ul>
            <?php
            // 1. Try querying featured posts
            $featured_args = array(
                'posts_per_page'      => 4,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'meta_key'            => '_is_featured_post',
                'meta_value'          => '1'
            );
            $featured_query = new WP_Query($featured_args);

            // 2. Fallback to recent posts if no posts are marked as featured
            if ( ! $featured_query->have_posts() ) {
                $featured_args = array(
                    'posts_per_page'      => 4,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true
                );
                $featured_query = new WP_Query($featured_args);
            }

            if ($featured_query->have_posts()) :
                while ($featured_query->have_posts()) : $featured_query->the_post();
                    ?>
                    <li>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="recent-post-thumb">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </a>
                        <?php endif; ?>
                        <div style="display: flex; flex-direction: column; justify-content: flex-start;">
                            <h4><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
                            <?php
                            $item_categories = get_the_category();
                            if (!empty($item_categories)) {
                                echo '<a href="' . esc_url(get_category_link($item_categories[0]->term_id)) . '" class="sidebar-post-category-chip">' . esc_html($item_categories[0]->name) . '</a>';
                            }
                            ?>
                        </div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<li>No featured posts.</li>';
            endif;
            ?>
        </ul>
    </div>

    <!-- Categories Widget -->
    <div class="sidebar-widget widget-categories">
        <h4 class="widget-title-classic">Categories</h4>
        <ul>
            <?php
            $current_cat_id = is_category() ? get_queried_object_id() : 0;
            
            $categories = get_categories(array('orderby' => 'name', 'order' => 'ASC'));
            foreach ($categories as $category) {
                $active_class = ($category->term_id === $current_cat_id) ? 'style="font-weight: bold; color: var(--color-accent);"' : '';
                echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '" ' . $active_class . '>' . esc_html($category->name) . ' <span>(' . esc_html($category->count) . ')</span></a></li>';
            }
            ?>
        </ul>
    </div>

    <!-- Tags Widget -->
    <div class="sidebar-widget widget-tags">
        <h4 class="widget-title-classic">Tags</h4>
        <div class="post-tags-classic" style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php
            $current_tag_id = is_tag() ? get_queried_object_id() : 0;
            
            $tags = get_tags(array(
                'orderby'    => 'count',
                'order'      => 'DESC',
                'hide_empty' => false,
                'number'     => 15,
            ));
            if (!empty($tags) && !is_wp_error($tags)) {
                foreach ($tags as $tag_obj) {
                    $active_class = ($tag_obj->term_id === $current_tag_id) ? 'style="border-color: var(--color-accent); color: var(--color-accent); margin: 0;"' : 'style="margin: 0;"';
                    echo '<a href="' . esc_url(get_tag_link($tag_obj->term_id)) . '" ' . $active_class . '>' . esc_html($tag_obj->name) . '</a>';
                }
            } else {
                echo '<p>No tags found.</p>';
            }
            ?>
        </div>
    </div>

</aside>
