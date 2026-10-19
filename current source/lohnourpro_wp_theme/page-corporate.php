<?php
/**
 * Template Name: Corporate Training
 * 
 * Note: WordPress will automatically use this for the page with slug "corporate"
 * even if not explicitly assigned via the Template Name dropdown.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: 0;">
    
    <!-- 1. CORPORATE HERO -->
    <header class="page-hero bg-alt" style="padding: var(--spacing-xxl) 0 var(--spacing-xl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
        <div class="container">
            <div style="max-width: 800px;">
                

                
                <h1 class="headline-lg" style="margin-bottom: var(--spacing-md); font-family: var(--font-heading); line-height: 1.15;">
                    <?php
                    $hero_title = get_post_meta( get_the_ID(), '_lohnour_hero_title', true );
                    echo ! empty( $hero_title ) ? wp_kses_post( $hero_title ) : 'AI capability building designed for organizations.';
                    ?>
                </h1>
                
                <p class="section-subtitle" style="font-size: 1.25rem; line-height: 1.6; color: var(--color-text-secondary); margin-bottom: var(--spacing-lg); max-width: 700px;">
                    <?php
                    $hero_subtitle = get_post_meta( get_the_ID(), '_lohnour_hero_subtitle', true );
                    echo ! empty( $hero_subtitle ) ? wp_kses_post( $hero_subtitle ) : 'We design and deliver structured AI training for teams, departments, and entire organizations to build practical, secure, and scalable AI capability.';
                    ?>
                </p>
                
                <div style="display: flex; gap: var(--spacing-sm); align-items: center; flex-wrap: wrap;">
                    <?php
                    $cta_label = get_post_meta( get_the_ID(), '_lohnour_cta_label', true );
                    $cta_label = ! empty( $cta_label ) ? esc_html( $cta_label ) : 'Discuss Your Training Needs';
                    ?>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-lg"><?php echo $cta_label; ?></a>
                </div>
                
            </div>
        </div>
    </header>

    <!-- 2. VALUE PROPOSITION & AUDIENCE -->
    <section class="corporate-value-prop" style="padding: var(--spacing-xl) 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-xl); align-items: start;">
                <div class="prose-content">
                    <?php
                    $content = get_the_content();
                    if ( ! empty( $content ) ) {
                        echo apply_filters( 'the_content', $content );
                    } else {
                    ?>
                        <h2 style="font-size: 2rem; color: var(--color-text-graphite); margin-bottom: var(--spacing-md);">Strategic AI integration starts with capable teams.</h2>
                        <p style="font-size: 1.125rem; color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg);">
                            LOHNOUR corporate training provides structured, rigorous education paths tailored to your organizational goals. We move past theoretical concepts and focus on immediate, practical application of AI tools within your specific business context.
                        </p>
                    <?php } ?>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-lg); margin-top: var(--spacing-xl);">
                <div class="glass-card" style="padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle); background: var(--color-bg-surface);">
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">For Teams</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Equip specific departments with the focused AI tools and methodologies relevant to their daily operations and strategic outputs.</p>
                </div>
                
                <div class="glass-card" style="padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle); background: var(--color-bg-surface);">
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">For Leadership</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Build executive fluency in AI capabilities, security considerations, and strategic implementation planning.</p>
                </div>
                
                <div class="glass-card" style="padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle); background: var(--color-bg-surface);">
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">For Institutions</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Deploy organization-wide foundational training to establish a baseline of AI literacy and safe usage policies.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. HOW IT WORKS -->
    <section class="corporate-process" style="padding: var(--spacing-xxl) 0; background: var(--color-bg-alt); border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
        <div class="container">
            <h2 style="font-size: 2rem; color: var(--color-text-graphite); margin-bottom: var(--spacing-xl); text-align: center;">How We Engage</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--spacing-xl);">
                <div style="position: relative;">
                    <div style="font-size: 3rem; font-weight: 700; color: var(--color-border); line-height: 1; margin-bottom: 16px; font-family: var(--font-heading);">01</div>
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">Consultation</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; font-size: 0.95rem;">We discuss your organizational objectives, current AI maturity, and specific team requirements.</p>
                </div>
                
                <div style="position: relative;">
                    <div style="font-size: 3rem; font-weight: 700; color: var(--color-border); line-height: 1; margin-bottom: 16px; font-family: var(--font-heading);">02</div>
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">Proposal</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; font-size: 0.95rem;">We outline a structured training plan, whether utilizing our existing catalog or developing customized modules.</p>
                </div>
                
                <div style="position: relative;">
                    <div style="font-size: 3rem; font-weight: 700; color: var(--color-border); line-height: 1; margin-bottom: 16px; font-family: var(--font-heading);">03</div>
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">Delivery</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; font-size: 0.95rem;">Live, structured training sessions focused on practical application, security, and measurable outcomes.</p>
                </div>
                
                <div style="position: relative;">
                    <div style="font-size: 3rem; font-weight: 700; color: var(--color-border); line-height: 1; margin-bottom: 16px; font-family: var(--font-heading);">04</div>
                    <h3 style="font-size: 1.25rem; color: var(--color-text-graphite); margin-bottom: 12px;">Follow-up</h3>
                    <p style="color: var(--color-text-secondary); line-height: 1.5; font-size: 0.95rem;">Post-training assessment and continued access to LOHNOUR learning materials as needed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. INQUIRY / FINAL CTA -->
    <section class="corporate-inquiry" style="padding: var(--spacing-xxl) 0; text-align: center;">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto; background: var(--color-bg-surface); padding: var(--spacing-xl); border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle); box-shadow: var(--shadow-sm);">
                <h2 style="font-size: 1.75rem; color: var(--color-text-graphite); margin-bottom: var(--spacing-sm);">Ready to build your team's capability?</h2>
                <p style="color: var(--color-text-secondary); margin-bottom: var(--spacing-lg);">Reach out to discuss your training requirements. Our team will coordinate a consultation to explore how LOHNOUR can support your organization.</p>
                
                <!-- Explicitly routing to the verified contact flow per prompt instructions to not fake backend forms -->
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">Submit an Inquiry</a>
            </div>
        </div>
    </section>

</main>

<style>
    @media (min-width: 768px) {
        .corporate-process > .container > div {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

<?php get_footer(); ?>
