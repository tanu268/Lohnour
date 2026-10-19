<?php
/**
 * The template for displaying the guides CPT archive (Guides Hub).
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$main_args = array(
    'post_type'   => 'user_guide',
    'post_status' => 'publish',
    'post_parent' => 0, // Only fetch parent Guides (Curriculums)
    'paged'       => $paged,
);
$main_query = new WP_Query($main_args);
?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-xxl);">
    
    <!-- 1. GUIDES HERO -->
    <header class="page-hero bg-alt" style="padding: var(--spacing-xxl) 0 var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
        <div class="container text-center">
            
            <div style="margin-bottom: var(--spacing-sm);">
                <span class="badge" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); background: transparent; border: 1px solid var(--color-border); padding: 4px 12px; border-radius: var(--radius-full);">Learning Resources</span>
            </div>
            
            <h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading);">
                Structured Guides
            </h1>
            
            <p class="section-subtitle" style="max-width: 600px; margin: 0 auto; color: var(--color-text-secondary); font-size: 1.125rem; line-height: 1.6;">
                Comprehensive, step-by-step curriculums designed to build practical capability and strategic understanding.
            </p>
            
        </div>
    </header>

    <div class="container">
        
        <?php
        // Optional Category Filtering (Sectors)
        $sectors = get_terms(array(
            'taxonomy'   => 'guide_sector',
            'hide_empty' => true,
        ));
        
        if (!empty($sectors) && !is_wp_error($sectors)) : ?>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: var(--spacing-xl);">
                <span style="padding: 8px 16px; font-size: 0.95rem; font-weight: 500; color: var(--color-text-graphite); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-full); background: var(--color-bg-surface);">All Guides</span>
                <?php foreach ($sectors as $sector) : ?>
                    <a href="<?php echo esc_url(get_term_link($sector)); ?>" style="padding: 8px 16px; font-size: 0.95rem; font-weight: 500; color: var(--color-text-secondary); text-decoration: none; border: 1px solid transparent; border-radius: var(--radius-full); transition: color 0.2s;">
                        <?php echo esc_html($sector->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($main_query->have_posts()) : ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--spacing-lg);">
                <?php while ($main_query->have_posts()) : $main_query->the_post(); 
                    
                    // Derive structural counts safely
                    $guide_id = get_the_ID();
                    
                    // Count modules
                    $modules_list = get_post_meta($guide_id, '_guide_modules_list', true);
                    $module_count = (is_array($modules_list)) ? count($modules_list) : 0;
                    
                    // Count articles (children)
                    $child_args = array(
                        'post_type' => 'user_guide',
                        'post_parent' => $guide_id,
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids'
                    );
                    $child_posts = get_posts($child_args);
                    $article_count = count($child_posts);
                ?>
                    
                    <a href="<?php the_permalink(); ?>" class="lohnour-card hover-lift" style="display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; border: 1px solid var(--color-border-subtle); border-radius: var(--radius-lg); background: var(--color-bg-surface); height: 100%; text-decoration: none;">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image" style="aspect-ratio: 16/9; width: 100%; overflow: hidden; border-bottom: 1px solid var(--color-border-subtle);">
                                <?php the_post_thumbnail('medium_large', array('style' => 'width: 100%; height: 100%; object-fit: cover; display: block;')); ?>
                            </div>
                        <?php else : ?>
                            <div class="card-image" style="aspect-ratio: 16/9; width: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--color-border-subtle);">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-content" style="padding: var(--spacing-lg); flex-grow: 1; display: flex; flex-direction: column;">
                            
                            <?php
                            $guide_sectors = get_the_terms(get_the_ID(), 'guide_sector');
                            if (!empty($guide_sectors) && !is_wp_error($guide_sectors)) {
                                echo '<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-accent); font-weight: 600; margin-bottom: 8px; display: block;">' . esc_html($guide_sectors[0]->name) . '</span>';
                            }
                            ?>
                            
                            <h3 style="margin-bottom: var(--spacing-xs); font-size: 1.35rem; color: var(--color-text-graphite); line-height: 1.3; font-weight: 600;"><?php the_title(); ?></h3>
                            
                            <p style="flex-grow: 1; color: var(--color-text-secondary); font-size: 0.95rem; margin-bottom: var(--spacing-lg); line-height: 1.5;">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            
                            <div style="margin-top: auto; padding-top: var(--spacing-md); border-top: 1px solid var(--color-border-subtle); display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; gap: 12px; color: var(--color-text-muted); font-size: 0.85rem; font-weight: 500;">
                                    <?php if ($module_count > 0) : ?>
                                        <span><svg style="vertical-align: text-bottom; margin-right: 4px;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> <?php echo $module_count; ?> <?php echo _n('Module', 'Modules', $module_count); ?></span>
                                    <?php endif; ?>
                                    <?php if ($article_count > 0) : ?>
                                        <span><svg style="vertical-align: text-bottom; margin-right: 4px;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> <?php echo $article_count; ?> <?php echo _n('Article', 'Articles', $article_count); ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="link-arrow" style="color: var(--color-accent); font-weight: 600; font-size: 0.95rem;">Start →</span>
                            </div>
                            
                        </div>
                    </a>

                <?php endwhile; ?>
            </div>
            
            <?php if ($main_query->max_num_pages > 1) : ?>
                <div class="pagination" style="margin-top: var(--spacing-xl); text-align: center; border-top: 1px solid var(--color-border-subtle); padding-top: var(--spacing-lg);">
                    <?php
                    echo paginate_links(array(
                        'total' => $main_query->max_num_pages,
                        'prev_text' => '&laquo; Newer',
                        'next_text' => 'Older &raquo;',
                    ));
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            
            <!-- Empty State -->
            <div style="text-align: center; padding: var(--spacing-xxl) 0; max-width: 600px; margin: 0 auto;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1" style="margin-bottom: var(--spacing-md);"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <h3 style="font-size: 1.5rem; color: var(--color-text-graphite); margin-bottom: var(--spacing-sm);">No guides are available yet.</h3>
                <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-lg);">We are currently expanding our curriculum. Please check back later for new resources.</p>
                <a href="<?php echo esc_url(home_url('/programs/')); ?>" class="btn btn-primary">Explore Programs</a>
            </div>
            
        <?php endif; wp_reset_postdata(); ?>

    </div>
</main>

<style>
.pagination .page-numbers {
    display: inline-block;
    padding: 8px 16px;
    margin: 0 4px;
    border: 1px solid var(--color-border-subtle);
    border-radius: var(--radius-sm);
    color: var(--color-text-secondary);
    text-decoration: none;
    transition: all 0.2s;
}
.pagination .page-numbers:hover,
.pagination .page-numbers.current {
    background: var(--color-bg-alt);
    color: var(--color-text-graphite);
    border-color: var(--color-border);
}
</style>

<?php get_footer(); ?>
