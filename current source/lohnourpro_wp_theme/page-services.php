<?php
/**
 * Template Name: Services Hub
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: 0;">
    
    <!-- 1. SERVICES HERO -->
    <header class="page-hero bg-alt" style="padding: var(--spacing-xxl) 0 var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
        <div class="container text-center">
            
            <div style="margin-bottom: var(--spacing-sm);">
                <span class="badge" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); background: transparent; border: 1px solid var(--color-border); padding: 4px 12px; border-radius: var(--radius-full);">Services</span>
            </div>
            
            <h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading);">
                <?php
                $hero_title = get_post_meta( get_the_ID(), '_lohnour_hero_title', true );
                echo ! empty( $hero_title ) ? wp_kses_post( $hero_title ) : 'Strategic AI capability-building<br>for organizations and teams.';
                ?>
            </h1>
            
            <p class="section-subtitle" style="max-width: 600px; margin: 0 auto; color: var(--color-text-secondary); font-size: 1.125rem; line-height: 1.6;">
                <?php
                $hero_subtitle = get_post_meta( get_the_ID(), '_lohnour_hero_subtitle', true );
                echo ! empty( $hero_subtitle ) ? wp_kses_post( $hero_subtitle ) : 'LOHNOUR PRO provides organizational and business solutions to integrate AI effectively into your workflows.';
                ?>
            </p>
            
        </div>
    </header>

    <!-- 2. SERVICES COLLECTION -->
    <section class="services-grid-section" style="padding-bottom: var(--spacing-xxl);">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto;">
                
                <!-- Corporate Training -->
                <a href="<?php echo esc_url( home_url( '/services/corporate/' ) ); ?>" class="lohnour-card card-service hover-lift corporate-card-main" style="display: flex; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; border: 1px solid var(--color-border-subtle); border-radius: var(--radius-lg); background: var(--color-bg-surface); align-items: center;">
                    <div class="card-image corporate-card-img" style="width: 250px; height: 100%; min-height: 250px; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    </div>
                    <div class="card-content" style="padding: var(--spacing-xl); flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 class="card-title" style="margin-bottom: var(--spacing-sm); font-size: 1.75rem; color: var(--color-text-graphite); line-height: 1.3; font-weight: 600;">Corporate Training</h3>
                        <p class="card-excerpt" style="color: var(--color-text-secondary); font-size: 1.05rem; margin-bottom: var(--spacing-lg); line-height: 1.6;">Customized AI training and capability building designed for organizations and leadership teams. We design and deliver structured AI training tailored to your organizational goals.</p>
                        <div class="card-footer" style="margin-top: auto; padding-top: var(--spacing-md); border-top: 1px solid var(--color-border-subtle);">
                            <span class="link-arrow" style="color: var(--color-text-graphite); font-weight: 600; font-size: 1.05rem;">Explore Corporate Training →</span>
                        </div>
                    </div>
                </a>

            </div>
            
            <div style="text-align: center; margin-top: var(--spacing-xl);">
                <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-md);">Looking for individual learning paths or cohorts?</p>
                <a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>" class="btn btn-outline">Explore Programs</a>
            </div>
        </div>
    </section>
</main>

<style>
    .corporate-card-main {
        flex-direction: row;
    }
    .corporate-card-img {
        border-right: 1px solid var(--color-border-subtle);
        border-bottom: none;
    }
    @media (max-width: 768px) {
        .corporate-card-main {
            flex-direction: column;
        }
        .corporate-card-img {
            width: 100% !important;
            height: 200px !important;
            min-height: 200px !important;
            border-right: none;
            border-bottom: 1px solid var(--color-border-subtle);
        }
    }
</style>

<?php get_footer(); ?>
