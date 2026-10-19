<aside class="blog-sidebar-column">

    <?php if ( is_singular( 'user_guide' ) ) : ?>
        <!-- Author Widget (Only on Single Guides) -->
        <?php
        $author_id = get_post_field( 'post_author', get_the_ID() );
        $first_name = get_the_author_meta( 'first_name', $author_id );
        $last_name  = get_the_author_meta( 'last_name', $author_id );
        $author_name = trim( $first_name . ' ' . $last_name );
        if ( empty( $author_name ) ) {
            $author_name = get_the_author_meta( 'display_name', $author_id );
        }
        $author_bio = get_the_author_meta( 'description', $author_id );
        if ( empty( $author_bio ) ) {
            $author_bio = 'We share insights on technology, workflows, and premium web experiences.';
        }
        ?>
        <div class="sidebar-widget widget-about">
            <h4 class="widget-title-classic">Author</h4>
            <div style="display: flex; justify-content: center; margin-bottom: 15px;">
                <?php echo get_avatar( $author_id, 150, '', '', array( 'class' => 'author-avatar', 'style' => 'border-radius: 50%; max-width: 150px; height: auto;' ) ); ?>
            </div>
            <p style="text-align: center; margin-bottom: 0; font-size: 0.95rem; color: var(--color-text-muted);"><?php echo wp_kses_post($author_bio); ?></p>
        </div>
    <?php endif; ?>

    <!-- 1. Newsletter Widget -->
    <div class="sidebar-widget widget-newsletter blog-newsletter-widget">
        <h4 class="widget-title-classic">Newsletter</h4>
        <?php if ( is_active_sidebar( 'blog-sidebar-newsletter' ) ) : ?>
            <?php dynamic_sidebar( 'blog-sidebar-newsletter' ); ?>
        <?php else : ?>
            <p class="post-classic-excerpt">Subscribe to get the latest updates.</p>
            <p style="font-size: 0.8rem; opacity: 0.5; margin-top: 10px;">[Add your Brevo Widget here via WP Admin > Appearance > Widgets > Blog Sidebar Newsletter]</p>
        <?php endif; ?>
    </div>

    <!-- 2. Featured Guides Widget -->
    <div class="sidebar-widget widget-recent-posts">
        <h4 class="widget-title-classic">Featured Guides</h4>
        <ul>
            <?php
            $feat_args = array(
                'post_type'      => 'user_guide',
                'posts_per_page' => 4,
                'post_parent'    => 0,
                'post_status'    => 'publish',
                'meta_key'       => '_is_featured_post',
                'meta_value'     => '1'
            );
            $feat_query = new WP_Query($feat_args);

            if ( ! $feat_query->have_posts() ) {
                $feat_args = array(
                    'post_type'      => 'user_guide',
                    'posts_per_page' => 4,
                    'post_parent'    => 0,
                    'post_status'    => 'publish',
                );
                $feat_query = new WP_Query($feat_args);
            }

            if ($feat_query->have_posts()) :
                while ($feat_query->have_posts()) : $feat_query->the_post();
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
                            $item_sectors = get_the_terms(get_the_ID(), 'guide_sector');
                            if (!empty($item_sectors) && !is_wp_error($item_sectors)) {
                                echo '<a href="' . esc_url(get_term_link($item_sectors[0]->term_id, 'guide_sector')) . '" class="sidebar-post-category-chip">' . esc_html($item_sectors[0]->name) . '</a>';
                            }
                            ?>
                        </div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<li>No guides found.</li>';
            endif;
            ?>
        </ul>
    </div>

    <!-- 3. Sectors Widget -->
    <div class="sidebar-widget widget-categories">
        <h4 class="widget-title-classic">Sectors</h4>
        <ul>
            <?php
            $current_sector_id = is_tax('guide_sector') ? get_queried_object_id() : 0;
            
            $sectors = get_terms(array(
                'taxonomy'   => 'guide_sector',
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false,
            ));
            if (!empty($sectors) && !is_wp_error($sectors)) {
                foreach ($sectors as $sector) {
                    $active_class = ($sector->term_id === $current_sector_id) ? 'style="font-weight: bold; color: var(--color-accent);"' : '';
                    echo '<li><a href="' . esc_url(get_term_link($sector->term_id, 'guide_sector')) . '" ' . $active_class . '>' . esc_html($sector->name) . ' <span>(' . esc_html($sector->count) . ')</span></a></li>';
                }
            } else {
                echo '<li>No sectors found.</li>';
            }
            ?>
        </ul>
    </div>

    <!-- 4. Tags Widget -->
    <div class="sidebar-widget widget-tags">
        <h4 class="widget-title-classic">Guide Tags</h4>
        <div class="post-tags-classic" style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php
            $current_tag_id = is_tax('guide_tags') ? get_queried_object_id() : 0;
            
            $tags = get_terms(array(
                'taxonomy'   => 'guide_tags',
                'orderby'    => 'count',
                'order'      => 'DESC',
                'hide_empty' => false,
                'number'     => 15,
            ));
            if (!empty($tags) && !is_wp_error($tags)) {
                foreach ($tags as $tag_obj) {
                    $active_class = ($tag_obj->term_id === $current_tag_id) ? 'style="border-color: var(--color-accent); color: var(--color-accent); margin: 0;"' : 'style="margin: 0;"';
                    echo '<a href="' . esc_url(get_term_link($tag_obj->term_id, 'guide_tags')) . '" ' . $active_class . '>' . esc_html($tag_obj->name) . '</a>';
                }
            } else {
                echo '<p>No tags found.</p>';
            }
            ?>
        </div>
    </div>

</aside>
