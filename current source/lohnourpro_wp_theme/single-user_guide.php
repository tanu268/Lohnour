<?php
/**
 * The template for displaying a single User Guide (Structured Learning).
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

$post_id = get_the_ID();
$parent_id = wp_get_post_parent_id( $post_id );

$is_topic = ( $parent_id === 0 );
$root_id = $is_topic ? $post_id : $parent_id;
$topic_title = get_the_title( $root_id );

// Fetch outline sibling guides (lessons)
$siblings = get_posts( array(
    'post_type'      => 'user_guide',
    'post_parent'    => $root_id,
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

// Group siblings by module name
$sibling_modules = array();
foreach ( $siblings as $sib ) {
    $mod_name = get_post_meta( $sib->ID, '_guide_module_name', true );
    if ( empty( $mod_name ) ) {
        $mod_name = 'General Resources';
    }
    $sibling_modules[$mod_name][] = $sib;
}

// Sort modules based on parent topic's defined modules list order
$parent_modules_list = get_post_meta( $root_id, '_guide_modules_list', true );
if ( is_array( $parent_modules_list ) && ! empty( $parent_modules_list ) ) {
    $module_order_map = array();
    foreach ( $parent_modules_list as $index => $mod ) {
        if (isset($mod['name'])) {
            $module_order_map[$mod['name']] = isset($mod['order']) ? $mod['order'] : $index;
        }
    }

    uksort( $sibling_modules, function( $a, $b ) use ( $module_order_map ) {
        $order_a = isset( $module_order_map[$a] ) ? $module_order_map[$a] : PHP_INT_MAX;
        $order_b = isset( $module_order_map[$b] ) ? $module_order_map[$b] : PHP_INT_MAX;
        
        if ( $order_a === $order_b ) {
            return strcasecmp( $a, $b );
        }
        return $order_a - $order_b;
    } );
}

// Flatten for Pagination and Progress calculation
$ordered_siblings = array();
foreach ( $sibling_modules as $mod_name => $m_lessons ) {
    foreach ( $m_lessons as $les ) {
        $ordered_siblings[] = $les;
    }
}

$total_articles = count($ordered_siblings);
$current_position = 0;
$current_index = -1;

foreach($ordered_siblings as $idx => $les) {
    if ($les->ID === $post_id) {
        $current_index = $idx;
        $current_position = $idx + 1;
        break;
    }
}

// Calculate Structural Progress
$progress_percentage = 0;
$progress_text = "";
if (!$is_topic && $total_articles > 0) {
    $progress_percentage = round(($current_position / $total_articles) * 100);
    $progress_text = "Article {$current_position} of {$total_articles}";
} elseif ($is_topic && $total_articles > 0) {
    $progress_text = "{$total_articles} Articles";
}

$prev_post = null;
$next_post = null;
if ($current_index !== -1) {
    if (isset($ordered_siblings[$current_index - 1])) {
        $prev_post = $ordered_siblings[$current_index - 1];
    } elseif ($current_index === 0 && !$is_topic) {
        $prev_post = get_post($root_id); // Previous is the parent overview
    }
    if (isset($ordered_siblings[$current_index + 1])) {
        $next_post = $ordered_siblings[$current_index + 1];
    }
} elseif ($is_topic && !empty($ordered_siblings)) {
    $next_post = $ordered_siblings[0]; // Next is the first lesson
}

// Parse Content and Generate TOC
$raw_content = get_the_content();
$parsed_content = apply_filters('the_content', $raw_content);
$parsed_content = str_replace(']]>', ']]&gt;', $parsed_content);

$toc_items = array();
$parsed_content = preg_replace_callback('/<(h[23])([^>]*)>(.*?)<\/\1>/is', function($matches) use (&$toc_items) {
    $tag = $matches[1];
    $attributes = $matches[2];
    $inner_html = $matches[3];
    
    $title_text = wp_strip_all_tags($inner_html);
    $id = sanitize_title($title_text);
    
    if (strpos($attributes, 'id=') === false) {
        $attributes .= ' id="' . esc_attr($id) . '"';
    } else {
        preg_match('/id=["\'](.*?)["\']/is', $attributes, $id_match);
        if (isset($id_match[1])) {
            $id = $id_match[1];
        }
    }
    
    if (!empty(trim($title_text))) {
        $toc_items[] = array(
            'id' => $id,
            'title' => $title_text,
            'level' => (int) str_replace('h', '', $tag)
        );
    }
    
    return "<{$tag}{$attributes}>{$inner_html}</{$tag}>";
}, $parsed_content);

?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xxl);">

    <!-- GUIDE HERO -->
    <header class="guide-hero" style="padding: var(--spacing-lg) 0 var(--spacing-md) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
        <div class="container" style="max-width: 1200px;">
            <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 800px; margin: 0 auto;">
                


                <h1 class="headline-md" style="margin-bottom: var(--spacing-xs); font-family: var(--font-heading); color: var(--color-text-graphite); line-height: 1.2;">
                    <?php echo esc_html($topic_title); ?>
                </h1>
                
                <?php if ($is_topic) : ?>
                    <p style="font-size: 1.125rem; color: var(--color-text-secondary); margin-bottom: var(--spacing-md); line-height: 1.5;">
                        <?php echo has_excerpt($root_id) ? get_the_excerpt($root_id) : get_post_meta($root_id, '_lohnour_card_excerpt', true); ?>
                    </p>
                <?php endif; ?>

                <!-- Structural Progress -->
                <?php if ($total_articles > 0) : ?>
                    <div class="guide-structural-progress" style="width: 100%; max-width: 320px; margin-top: var(--spacing-sm);">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px;">
                            <span>Current Position</span>
                            <span><?php echo esc_html($progress_text); ?></span>
                        </div>
                        <div style="width: 100%; height: 4px; background: var(--color-border-subtle); border-radius: var(--radius-full); overflow: hidden;">
                            <div style="height: 100%; width: <?php echo $progress_percentage; ?>%; background: var(--color-text-graphite); transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </header>

    <!-- MAIN LAYOUT -->
    <div class="container" style="max-width: 1300px;">
        
        <!-- Mobile Curriculum Toggle -->
        <button id="mobile-curriculum-toggle" class="btn btn-outline" style="display: none; width: 100%; margin-bottom: var(--spacing-md); justify-content: space-between; align-items: center;" aria-expanded="false" aria-controls="guide-curriculum-nav">
            <span>Guide Contents</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        
        <div class="guide-layout-grid" style="display: grid; grid-template-columns: 260px minmax(0, 720px) 200px; justify-content: center; gap: var(--spacing-xl); align-items: start;">
            
            <!-- LEFT: CURRICULUM NAVIGATION RAIL -->
            <aside id="guide-curriculum-nav" class="guide-curriculum" style="position: sticky; top: calc(var(--header-clearance) + 20px); max-height: calc(100vh - var(--header-clearance) - 40px); overflow-y: auto; padding-right: 16px;">
                
                <div style="margin-bottom: var(--spacing-md);">
                    <a href="<?php echo esc_url(get_permalink($root_id)); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: <?php echo ($is_topic) ? 'var(--color-text-graphite)' : 'var(--color-text-secondary)'; ?>; font-weight: <?php echo ($is_topic) ? '600' : '500'; ?>; transition: color 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Guide Overview
                    </a>
                </div>
                
                <nav aria-label="Guide Contents">
                    <?php 
                    $module_index = 1;
                    foreach ($sibling_modules as $mod_name => $m_lessons) : 
                        $is_active_module = false;
                        foreach ($m_lessons as $les) {
                            if ($les->ID === $post_id) {
                                $is_active_module = true;
                                break;
                            }
                        }
                    ?>
                        <div class="curriculum-module <?php echo $is_active_module ? 'is-expanded' : ''; ?>" style="margin-bottom: var(--spacing-md);">
                            
                            <button class="module-header" aria-expanded="<?php echo $is_active_module ? 'true' : 'false'; ?>" style="width: 100%; display: flex; justify-content: space-between; align-items: flex-start; padding: 0; margin-bottom: 8px; background: transparent; border: none; cursor: pointer; text-align: left; color: var(--color-text-graphite);">
                                <div>
                                    <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 700; display: block; margin-bottom: 2px;">MODULE <?php echo $module_index++; ?></span>
                                    <span style="font-weight: 600; font-size: 0.9rem; line-height: 1.3;"><?php echo esc_html($mod_name); ?></span>
                                </div>
                                <svg class="module-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted); margin-top: 4px; transition: transform 0.2s; <?php echo $is_active_module ? 'transform: rotate(180deg);' : ''; ?>"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            
                            <div class="module-lessons" style="display: <?php echo $is_active_module ? 'block' : 'none'; ?>;">
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                                    <?php foreach ($m_lessons as $les) : 
                                        $is_current = ($les->ID === $post_id);
                                    ?>
                                        <li style="position: relative;">
                                            <a href="<?php echo esc_url(get_permalink($les->ID)); ?>" style="display: block; padding: 6px 8px 6px 12px; font-size: 0.85rem; line-height: 1.4; text-decoration: none; border-radius: 4px; border-left: 3px solid <?php echo $is_current ? 'var(--color-text-graphite)' : 'transparent'; ?>; background: <?php echo $is_current ? 'var(--color-bg-alt)' : 'transparent'; ?>; color: <?php echo $is_current ? 'var(--color-text-graphite)' : 'var(--color-text-secondary)'; ?>; font-weight: <?php echo $is_current ? '600' : '400'; ?>; transition: all 0.2s;">
                                                <?php echo esc_html($les->post_title); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <!-- CENTER: ARTICLE CONTENT -->
            <article class="guide-article" id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="max-width: 780px; width: 100%;">
                
                <?php if (!$is_topic) : ?>
                    <h2 style="font-size: 2.25rem; color: var(--color-text-graphite); font-family: var(--font-heading); margin-bottom: var(--spacing-sm); line-height: 1.2;">
                        <?php the_title(); ?>
                    </h2>
                    
                    <?php if (has_excerpt()) : ?>
                        <p style="font-size: 1.125rem; color: var(--color-text-secondary); margin-bottom: var(--spacing-lg); line-height: 1.6;">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div style="margin-bottom: var(--spacing-xl); border-radius: var(--radius-md); overflow: hidden;">
                        <?php the_post_thumbnail('large', array('style' => 'width: 100%; height: auto; display: block; object-fit: cover;')); ?>
                    </div>
                <?php endif; ?>

                <div class="lohnour-editorial-content">
                    <?php echo $parsed_content; ?>
                </div>
                
                <!-- Sequential Navigation -->
                <nav class="guide-sequential-nav" aria-label="Sequential Article Navigation" style="margin-top: var(--spacing-xxl); padding-top: var(--spacing-lg); border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; gap: var(--spacing-md);">
                    
                    <div style="flex: 1;">
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="nav-prev-link" style="display: flex; flex-direction: column; text-decoration: none; padding: var(--spacing-md); border-radius: var(--radius-md); transition: background-color 0.2s;">
                                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">← Previous Article</span>
                                <span style="font-size: 1.05rem; color: var(--color-text-graphite); font-weight: 600;"><?php echo esc_html($prev_post->post_title); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div style="flex: 1; text-align: right;">
                        <?php if ($next_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="nav-next-link" style="display: flex; flex-direction: column; text-decoration: none; padding: var(--spacing-md); border-radius: var(--radius-md); transition: background-color 0.2s;">
                                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); font-weight: 600; margin-bottom: 4px;">Next Article →</span>
                                <span style="font-size: 1.05rem; color: var(--color-text-graphite); font-weight: 600;"><?php echo esc_html($next_post->post_title); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </nav>
            </article>

            <!-- RIGHT: ON THIS PAGE TOC -->
            <aside id="guide-toc-nav" class="guide-toc" style="position: sticky; top: calc(var(--header-clearance) + 20px);">
                <?php if (!empty($toc_items)) : ?>
                    <div style="padding-left: var(--spacing-md); border-left: 1px solid var(--color-border-subtle);">
                        <h4 style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: var(--spacing-sm); font-weight: 700;">On This Page</h4>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($toc_items as $item) : 
                                $padding = ($item['level'] === 3) ? 'padding-left: 12px;' : '';
                            ?>
                                <li style="<?php echo $padding; ?>">
                                    <a href="#<?php echo esc_attr($item['id']); ?>" style="font-size: 0.85rem; color: var(--color-text-secondary); text-decoration: none; line-height: 1.4; display: block; transition: color 0.2s;">
                                        <?php echo esc_html($item['title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</main>

<style>
/* Responsive Layout */
@media (max-width: 1024px) {
    .guide-layout-grid {
        grid-template-columns: 240px minmax(0, 1fr) !important;
        gap: var(--spacing-lg) !important;
    }
    .guide-toc {
        display: none;
    }
}
@media (max-width: 768px) {
    .guide-layout-grid {
        grid-template-columns: 1fr !important;
        gap: var(--spacing-md) !important;
    }
    .guide-curriculum {
        display: none;
        position: relative !important;
        top: 0 !important;
        max-height: none !important;
        padding-right: 0 !important;
    }
    .guide-curriculum.is-mobile-open {
        display: block;
        margin-bottom: var(--spacing-lg);
    }
    #mobile-curriculum-toggle {
        display: flex !important;
    }
    .guide-sequential-nav {
        flex-direction: column;
    }
    .nav-next-link {
        text-align: left !important;
    }
}

/* Editorial Content Styling */
.lohnour-editorial-content {
    font-size: 1.125rem; 
    line-height: 1.7; 
    color: var(--color-text-graphite);
}
.lohnour-editorial-content h2 {
    font-size: 1.75rem;
    margin-top: 2em;
    margin-bottom: 1em;
    color: var(--color-text-graphite);
    font-family: var(--font-heading);
    scroll-margin-top: calc(var(--header-clearance) + 20px);
}
.lohnour-editorial-content h3 {
    font-size: 1.35rem;
    margin-top: 1.5em;
    margin-bottom: 0.75em;
    color: var(--color-text-graphite);
    scroll-margin-top: calc(var(--header-clearance) + 20px);
}
.lohnour-editorial-content p {
    margin-bottom: 1.5em;
}
.lohnour-editorial-content ul, .lohnour-editorial-content ol {
    margin-bottom: 1.5em;
    padding-left: 1.5em;
}
.lohnour-editorial-content li {
    margin-bottom: 0.5em;
}
.lohnour-editorial-content a {
    color: var(--color-accent);
    text-decoration: underline;
    text-decoration-color: transparent;
    transition: text-decoration-color 0.2s;
}
.lohnour-editorial-content a:hover {
    text-decoration-color: var(--color-accent);
}
.lohnour-editorial-content blockquote {
    margin: 2em 0;
    padding: 1em 1.5em;
    border-left: 3px solid var(--color-border);
    background: transparent;
    font-style: italic;
    color: var(--color-text-secondary);
}
.lohnour-editorial-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-sm);
    margin: 1.5em 0;
}

/* Interactive Hover States */
.module-lessons a:hover {
    background: var(--color-bg-alt) !important;
    color: var(--color-text-graphite) !important;
}
.nav-prev-link:hover, .nav-next-link:hover {
    background-color: var(--color-bg-alt);
}
.guide-toc a:hover {
    color: var(--color-text-graphite) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Module Expand/Collapse Logic
    const moduleHeaders = document.querySelectorAll('.module-header');
    moduleHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            
            const chevron = this.querySelector('.module-chevron');
            const content = this.nextElementSibling;
            
            if (isExpanded) {
                chevron.style.transform = 'rotate(0deg)';
                content.style.display = 'none';
            } else {
                chevron.style.transform = 'rotate(180deg)';
                content.style.display = 'block';
            }
        });
    });

    // Mobile Curriculum Toggle
    const mobileToggle = document.getElementById('mobile-curriculum-toggle');
    const curriculumNav = document.getElementById('guide-curriculum-nav');
    if (mobileToggle && curriculumNav) {
        mobileToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            curriculumNav.classList.toggle('is-mobile-open');
            
            const svg = this.querySelector('svg');
            if (isExpanded) {
                svg.style.transform = 'rotate(0deg)';
            } else {
                svg.style.transform = 'rotate(180deg)';
            }
        });
    }

    // TOC Active State (Simple scroll spy)
    const tocLinks = document.querySelectorAll('.guide-toc a');
    const headings = Array.from(tocLinks).map(link => {
        const id = link.getAttribute('href').substring(1);
        return document.getElementById(id);
    }).filter(h => h);

    if (headings.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    tocLinks.forEach(link => {
                        link.style.color = 'var(--color-text-secondary)';
                        link.style.fontWeight = '400';
                    });
                    const activeLink = document.querySelector(`.guide-toc a[href="#${entry.target.id}"]`);
                    if (activeLink) {
                        activeLink.style.color = 'var(--color-text-graphite)';
                        activeLink.style.fontWeight = '600';
                    }
                }
            });
        }, { rootMargin: '-10% 0px -80% 0px' });

        headings.forEach(h => observer.observe(h));
    }
});
</script>

<?php get_footer(); ?>
