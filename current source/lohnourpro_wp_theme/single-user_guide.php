<?php
/**
 * The template for displaying a single User Guide (Topic or Lesson).
 *
 * @package LohnourPro
 */

get_header();

$post_id = get_the_ID();
$parent_id = wp_get_post_parent_id( $post_id );

// Check if this is a Topic page (Level 1) or a Lesson page (Level 3)
$is_topic = ( $parent_id === 0 );

// Get the primary sector of the parent Topic or current post for the breadcrumbs
$sectors = get_the_terms( $is_topic ? $post_id : $parent_id, 'guide_sector' );
$sector_html = '';
if ( ! empty( $sectors ) && ! is_wp_error( $sectors ) ) {
	$sector = $sectors[0]; // Primary sector
	$sector_html = '<a href="' . esc_url( get_term_link( $sector ) ) . '" style="color: inherit; text-decoration: none;">' . esc_html( $sector->name ) . '</a>';
}

if ( $is_topic ) {
    $parent_id = $post_id;
    $topic_title = get_the_title( $post_id );
} else {
    $topic_title = get_the_title( $parent_id );
}

$sibling_status = current_user_can( 'edit_posts' ) ? array( 'publish', 'draft' ) : 'publish';
$siblings = get_posts( array(
    'post_type'      => 'user_guide',
    'post_parent'    => $parent_id,
    'post_status'    => $sibling_status,
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

// Group siblings by Module -> Lesson for the Left Sidebar Outline
$sibling_hierarchy = array();
foreach ( $siblings as $sib ) {
    $mod_name = get_post_meta( $sib->ID, '_guide_module_name', true );
    if ( empty( $mod_name ) ) {
        $mod_name = 'General Resources';
    }
    $lesson_name = get_post_meta( $sib->ID, '_guide_lesson_name', true );
    // Strict adherence: Do NOT create fake lesson groups. Empty string key if unassigned.
    if ( empty( $lesson_name ) ) {
        $lesson_name = '';
    }
    $sibling_hierarchy[$mod_name][$lesson_name][] = $sib;
}

// Sort modules and lessons based on parent topic's defined modules list order
$parent_modules_list = get_post_meta( $parent_id, '_guide_modules_list', true );
$module_order_map = array();
$module_lesson_order_map = array();

if ( is_array( $parent_modules_list ) && ! empty( $parent_modules_list ) ) {
    foreach ( $parent_modules_list as $mod ) {
        $m_name = isset( $mod['name'] ) ? $mod['name'] : '';
        $module_order_map[$m_name] = isset( $mod['order'] ) ? $mod['order'] : PHP_INT_MAX;
        if ( isset( $mod['lessons'] ) && is_array( $mod['lessons'] ) ) {
            foreach ( $mod['lessons'] as $les ) {
                $l_name = isset( $les['name'] ) ? $les['name'] : '';
                $module_lesson_order_map[$m_name][$l_name] = isset( $les['order'] ) ? $les['order'] : PHP_INT_MAX;
            }
        }
    }

    // Sort modules by parent order
    uksort( $sibling_hierarchy, function( $a, $b ) use ( $module_order_map ) {
        $order_a = isset( $module_order_map[$a] ) ? $module_order_map[$a] : PHP_INT_MAX;
        $order_b = isset( $module_order_map[$b] ) ? $module_order_map[$b] : PHP_INT_MAX;

        if ( $order_a === $order_b ) {
            preg_match( '/Module\s+(\d+)/i', $a, $a_match );
            preg_match( '/Module\s+(\d+)/i', $b, $b_match );
            if ( isset( $a_match[1] ) && isset( $b_match[1] ) ) {
                return (int)$a_match[1] - (int)$b_match[1];
            }
            return strcasecmp( $a, $b );
        }
        return $order_a - $order_b;
    } );

    // Sort lessons within each module
    foreach ( $sibling_hierarchy as $m_name => &$m_lessons ) {
        $l_order_map = isset( $module_lesson_order_map[$m_name] ) ? $module_lesson_order_map[$m_name] : array();
        uksort( $m_lessons, function( $a, $b ) use ( $l_order_map ) {
            if ( $a === '' ) return 1;
            if ( $b === '' ) return -1;

            $order_a = isset( $l_order_map[$a] ) ? $l_order_map[$a] : PHP_INT_MAX;
            $order_b = isset( $l_order_map[$b] ) ? $l_order_map[$b] : PHP_INT_MAX;

            if ( $order_a === $order_b ) {
                preg_match( '/Lesson\s+(\d+)/i', $a, $a_match );
                preg_match( '/Lesson\s+(\d+)/i', $b, $b_match );
                if ( isset( $a_match[1] ) && isset( $b_match[1] ) ) {
                    return (int)$a_match[1] - (int)$b_match[1];
                }
                return strcasecmp( $a, $b );
            }
            return $order_a - $order_b;
        } );
    }
    unset( $m_lessons );
} else {
    // Fallback alphanumeric sorting if no explicit modules list exists on parent
    uksort( $sibling_hierarchy, function( $a, $b ) {
        preg_match( '/Module\s+(\d+)/i', $a, $a_match );
        preg_match( '/Module\s+(\d+)/i', $b, $b_match );
        if ( isset( $a_match[1] ) && isset( $b_match[1] ) ) {
            return (int)$a_match[1] - (int)$b_match[1];
        }
        return strcasecmp( $a, $b );
    } );
}

// Flatten the sorted hierarchy into a properly ordered array of siblings for pagination
$ordered_siblings = array();
foreach ( $sibling_hierarchy as $m_name => $m_lessons ) {
    foreach ( $m_lessons as $l_name => $guides ) {
        foreach ( $guides as $les ) {
            $ordered_siblings[] = $les;
        }
    }
}
?>
    <main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
        <div class="container">
            <?php
            $guide_breadcrumbs = array(
                array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
                array( 'label' => __( 'Guides', 'twentytwentyfive-child' ), 'url' => home_url( '/guides/' ) ),
            );
            if ( ! empty( $sectors ) && ! is_wp_error( $sectors ) ) {
                $guide_breadcrumbs[] = array( 'label' => $sectors[0]->name, 'url' => get_term_link( $sectors[0] ) );
            }
            if ( ! $is_topic ) {
                $guide_breadcrumbs[] = array( 'label' => $topic_title, 'url' => get_permalink( $parent_id ) );
                $guide_breadcrumbs[] = array( 'label' => get_the_title() );
            } else {
                $guide_breadcrumbs[] = array( 'label' => get_the_title() );
            }

            $guide_excerpt = has_excerpt() ? get_the_excerpt() : get_post_meta( get_the_ID(), '_lohnour_card_excerpt', true );

            get_template_part( 'template-parts/page-header', null, array(
                'title'       => get_the_title(),
                'subtitle'    => $guide_excerpt,
                'breadcrumbs' => $guide_breadcrumbs,
            ) );
            ?>
        </div>

        <!-- Mobile Outline Toggle Button -->
        <div class="mobile-outline-toggle-wrapper">
            <button id="mobile-outline-toggle" class="mobile-outline-btn" aria-label="Toggle Outline Menu">
                <span class="toggle-icon-span" style="position: relative; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                    <svg class="icon-menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; transition: transform 0.4s ease, opacity 0.4s ease; transform: rotate(0deg); opacity: 1;"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <svg class="icon-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; transition: transform 0.4s ease, opacity 0.4s ease; transform: rotate(-90deg); opacity: 0;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </span>
            </button>
        </div>

        <div class="blog-classic-container guides-three-column-grid" style="display: grid; grid-template-columns: 280px 1fr 280px; gap: var(--spacing-lg); max-width: 1440px;">
            
            <!-- Left: Course Outline Sidebar -->
            <aside class="guides-sidebar-left" style="position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto; text-align: left;">
                <div class="sidebar-widget" style="padding: var(--spacing-md); background: var(--color-glass); backdrop-filter: blur(10px); border: 1px solid var(--color-glass-border); border-radius: var(--radius-md); text-align: left;">
                    <h4 class="widget-title-classic" style="font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid var(--color-glass-border); padding-bottom: 10px; text-align: left;">
                        <a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>" class="guide-outline-parent-link <?php echo ( $post_id === $parent_id ) ? 'active' : ''; ?>"><?php echo esc_html( $topic_title ); ?></a>
                    </h4>
                    <div class="guides-outline-menu" style="text-align: left;">
                        <?php foreach ( $sibling_hierarchy as $m_title => $m_lessons ) :
                            $is_current_module = false;
                            foreach ( $m_lessons as $l_title => $l_guides ) {
                                foreach ( $l_guides as $les ) {
                                    if ( $les->ID === $post_id ) {
                                        $is_current_module = true;
                                        break 2;
                                    }
                                }
                            }
                            ?>
                            <div class="outline-module <?php echo $is_current_module ? 'expanded active-lesson-module' : ''; ?>" style="margin-bottom: 12px; text-align: left;">
                                <div class="outline-module-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding: 8px 0; text-align: left;">
                                    <h5 style="font-size: 0.85rem; text-transform: uppercase; color: <?php echo $is_current_module ? 'var(--color-accent)' : 'var(--color-text-muted)'; ?>; margin: 0; font-weight: 600; text-align: left;"><?php echo esc_html( $m_title ); ?></h5>
                                    <span class="module-chevron" style="font-size: 0.65rem; transition: transform 0.3s; transform: <?php echo $is_current_module ? 'rotate(90deg)' : 'rotate(0deg)'; ?>; color: var(--color-text-muted);">▶</span>
                                </div>
                                <div class="outline-module-content" style="transition: max-height 0.3s ease-out; overflow: hidden; <?php echo $is_current_module ? '' : 'max-height: 0;'; ?>">
                                    <div class="outline-lessons-container" style="padding-left: 6px; margin-top: 6px;">
                                        <?php foreach ( $m_lessons as $l_title => $l_guides ) :
                                            $is_current_lesson = false;
                                            foreach ( $l_guides as $les ) {
                                                if ( $les->ID === $post_id ) {
                                                    $is_current_lesson = true;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <div class="outline-lesson-group" style="margin-bottom: 10px; text-align: left;">
                                                <?php if ( ! empty( $l_title ) ) : ?>
                                                    <div class="outline-lesson-title" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: <?php echo $is_current_lesson ? 'var(--color-accent)' : 'var(--color-text)'; ?>; font-weight: 600; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                                        <span style="font-size: 0.5rem; color: var(--color-accent); opacity: 0.75;">◆</span>
                                                        <span><?php echo esc_html( $l_title ); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <ul style="list-style: none; margin: 0 0 0 8px; padding-left: 8px; border-left: 1px solid var(--color-glass-border, rgba(255,255,255,0.1)); text-align: left;">
                                                    <?php foreach ( $l_guides as $les ) : ?>
                                                        <li style="margin-bottom: 6px; text-align: left;">
                                                            <a href="<?php echo esc_url( get_permalink( $les->ID ) ); ?>" class="guide-outline-child-link <?php echo ( $les->ID === $post_id ) ? 'active' : ''; ?>">
                                                                <?php echo esc_html( $les->post_title ); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <!-- Center: Main Content Column -->
            <div class="blog-main-column">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if ( $is_topic && has_post_thumbnail() ) : ?>
                        <div class="single-classic-image" style="margin-bottom: var(--spacing-md);">
                            <?php the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: auto; display: block; border-radius: var(--radius-md);' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="single-reading-content guide-content">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( ! $is_topic ) : ?>
                        <!-- Next / Previous Lesson Pagination -->
                        <?php
                        $prev_post = null;
                        $next_post = null;
                        foreach ( $ordered_siblings as $index => $sib ) {
                            if ( $sib->ID === $post_id ) {
                                if ( isset( $ordered_siblings[$index - 1] ) ) {
                                    $prev_post = $ordered_siblings[$index - 1];
                                }
                                if ( isset( $ordered_siblings[$index + 1] ) ) {
                                    $next_post = $ordered_siblings[$index + 1];
                                }
                                break;
                            }
                        }
                        ?>
                        <?php endif; ?>

                    <footer class="post-footer-classic" style="margin-top: var(--spacing-xl); padding-top: var(--spacing-lg); border-top: 1px solid var(--color-glass-border);">

                        <!-- Sectors, Tags & Share -->
                        <div class="guide-meta-footer guide-footer-grid" style="margin-bottom: <?php echo ( ! $is_topic && ( $prev_post || $next_post ) ) ? '30px' : '0'; ?>;">
                            <div class="post-tags-classic" style="text-align: left; display: flex; flex-wrap: wrap; gap: 15px; align-items: center; margin: 0;">
                                <?php
                                $guide_sectors = get_the_terms(get_the_ID(), 'guide_sector');
                                $guide_tags = get_the_terms(get_the_ID(), 'guide_tags');

                                if ( ($guide_sectors && !is_wp_error($guide_sectors)) || ($guide_tags && !is_wp_error($guide_tags)) ) {
                                    if ($guide_sectors && !is_wp_error($guide_sectors)) {
                                        echo '<div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">';
                                        echo '<span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500; margin-right: 4px;">Sectors:</span>';
                                        foreach($guide_sectors as $cat) {
                                            echo '<a href="' . esc_url(get_term_link($cat->term_id, 'guide_sector')) . '">' . esc_html($cat->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    if ($guide_tags && !is_wp_error($guide_tags)) {
                                        echo '<div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">';
                                        echo '<span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500; margin-right: 4px;">Tags:</span>';
                                        foreach($guide_tags as $tag) {
                                            echo '<a href="' . esc_url(get_term_link($tag->term_id, 'guide_tags')) . '">' . esc_html($tag->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>

                            <div class="post-share-classic" style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin: 0;">
                                <span style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500;">Share:</span>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Twitter" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Facebook" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" aria-label="Share on LinkedIn" style="display: inline-flex; align-items: center; justify-content: center; margin: 0;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                            </div>
                        </div>

                        <?php if ( ! $is_topic && ( $prev_post || $next_post ) ) : ?>
                            <!-- Next / Previous Lesson Pagination -->
                            <div class="guide-lesson-pagination" style="display: flex; justify-content: space-between; padding: 25px; background: var(--color-glass); border: 1px solid var(--color-glass-border); border-radius: var(--radius-md); align-items: center;">
                                <?php if ( $prev_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="lesson-nav-link" style="text-decoration: none; display: flex; flex-direction: column; text-align: left; max-width: 48%;">
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 4px;">← Previous Lesson</span>
                                        <span class="nav-title" style="font-size: 0.95rem; color: var(--color-text); font-weight: 600; transition: color 0.2s;"><?php echo esc_html( $prev_post->post_title ); ?></span>
                                    </a>
                                <?php else : ?>
                                    <div></div>
                                <?php endif; ?>

                                <?php if ( $next_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="lesson-nav-link" style="text-decoration: none; display: flex; flex-direction: column; text-align: right; max-width: 48%;">
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 4px;">Next Lesson →</span>
                                        <span class="nav-title" style="font-size: 0.95rem; color: var(--color-accent); font-weight: 600; transition: color 0.2s;"><?php echo esc_html( $next_post->post_title ); ?></span>
                                    </a>
                                <?php else : ?>
                                    <div></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </footer>
                </article>
            </div>

            <!-- Right Column -->
            <?php if ( $is_topic ) : ?>
                <!-- Right: Author & Standard Guide Sidebar (Topic view) -->
                <?php get_template_part('sidebar-guide'); ?>
            <?php else : ?>
                <!-- Right: "On this page" TOC Column (Lesson view) -->
                <aside class="guides-sidebar-right" style="position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto; text-align: left;">
                    <div class="sidebar-widget" style="padding: var(--spacing-md); background: var(--color-glass); backdrop-filter: blur(10px); border: 1px solid var(--color-glass-border); border-radius: var(--radius-md); text-align: left;">
                        <h4 class="widget-title-classic" style="font-size: 0.8rem; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid var(--color-glass-border); padding-bottom: 10px; text-align: left;">On this page</h4>
                        <ul class="guides-toc-list" style="list-style: none; margin: 0; padding-left: 0; text-align: left;">
                            <!-- Dynamically populated by guides.js -->
                        </ul>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </main>
<?php
get_footer();
