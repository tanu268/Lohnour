<?php
/**
 * Template Name: Coaching Page
 * The template for displaying the Coaching services page.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main coaching-page" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">

		<!-- Hero / Introduction -->
		<?php
		get_template_part( 'template-parts/page-header', null, array(
			'title'       => __( 'Coaching', 'twentytwentyfive-child' ),
			'subtitle'    => __( 'Personalized guidance and collaborative coaching for practical AI implementation and professional growth.', 'twentytwentyfive-child' ),
			'breadcrumbs' => array(
				array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
				array( 'label' => __( 'Services', 'twentytwentyfive-child' ), 'url' => home_url( '/services' ) ),
				array( 'label' => __( 'Coaching', 'twentytwentyfive-child' ) ),
			),
		) );
		?>

		<!-- Group Coaching Section -->
		<section class="pathway-section group-coaching-section" style="margin-bottom: var(--spacing-xl);">
			<div class="pathway-header" style="margin-bottom: var(--spacing-md); display: flex; justify-content: space-between; align-items: flex-end;">
				<div>
					<h2 class="section-title" style="font-size: 1.75rem; margin-bottom: 4px;"><?php esc_html_e( 'Group Coaching', 'twentytwentyfive-child' ); ?></h2>
					<p class="section-subtitle" style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0;">
						<?php esc_html_e( 'Collaborative cohort programs for teams and practitioners.', 'twentytwentyfive-child' ); ?>
					</p>
				</div>
			</div>

			<div class="programs-grid">
				<!-- Agentic AI Foundations Card -->
				<div class="service-card glass-card hover-lift" style="display: flex; flex-direction: column; height: 100%; text-decoration: none; overflow: hidden; padding: 0;">
					<div class="card-body" style="padding: var(--spacing-md); display: flex; flex-direction: column; flex-grow: 1;">
						<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--spacing-sm);">
							<div class="card-icon" style="margin-bottom: 0; color: var(--color-accent);">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
							</div>
							<span class="card-badge"><?php esc_html_e( 'Group Program', 'twentytwentyfive-child' ); ?></span>
						</div>

						<h3 class="card-title" style="margin-bottom: var(--spacing-xs);">
							<a href="<?php echo esc_url( home_url( '/coaching/agentic-ai-foundations' ) ); ?>" style="color: inherit; text-decoration: none;">
								<?php esc_html_e( 'Agentic AI Foundations', 'twentytwentyfive-child' ); ?>
							</a>
						</h3>
						<p class="card-desc" style="flex-grow: 1; margin-bottom: var(--spacing-md); color: var(--color-text-muted);">
							<?php esc_html_e( 'A structured group coaching program focusing on agentic AI architecture, practical implementation, and autonomous workflows.', 'twentytwentyfive-child' ); ?>
						</p>

						<div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
							<a href="<?php echo esc_url( home_url( '/coaching/agentic-ai-foundations' ) ); ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
								<?php esc_html_e( 'Explore Program', 'twentytwentyfive-child' ); ?>
							</a>
							<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
								<?php esc_html_e( 'Inquire', 'twentytwentyfive-child' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Individual Coaching Section -->
		<section class="pathway-section individual-coaching-section" style="margin-bottom: var(--spacing-xl);">
			<div class="pathway-header" style="margin-bottom: var(--spacing-md); display: flex; justify-content: space-between; align-items: flex-end;">
				<div>
					<h2 class="section-title" style="font-size: 1.75rem; margin-bottom: 4px;"><?php esc_html_e( 'Individual Coaching', 'twentytwentyfive-child' ); ?></h2>
					<p class="section-subtitle" style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0;">
						<?php esc_html_e( 'Personalized 1:1 mentorship and guidance tailored to your specific goals and workflows.', 'twentytwentyfive-child' ); ?>
					</p>
				</div>
			</div>

			<div class="programs-grid">
				<!-- Individual Coaching Card -->
				<div class="service-card glass-card hover-lift" style="display: flex; flex-direction: column; height: 100%; text-decoration: none; overflow: hidden; padding: 0;">
					<div class="card-body" style="padding: var(--spacing-md); display: flex; flex-direction: column; flex-grow: 1;">
						<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--spacing-sm);">
							<div class="card-icon" style="margin-bottom: 0; color: var(--color-accent);">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							</div>
							<span class="card-badge"><?php esc_html_e( '1:1 Mentorship', 'twentytwentyfive-child' ); ?></span>
						</div>

						<h3 class="card-title" style="margin-bottom: var(--spacing-xs);"><?php esc_html_e( '1:1 AI Coaching', 'twentytwentyfive-child' ); ?></h3>
						<p class="card-desc" style="flex-grow: 1; margin-bottom: var(--spacing-md); color: var(--color-text-muted);">
							<?php esc_html_e( 'Direct, personalized guidance focused on solving targeted AI challenges, developing hands-on skills, and advancing professional capabilities.', 'twentytwentyfive-child' ); ?>
						</p>

						<div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between;">
							<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
								<?php esc_html_e( 'Request Consultation', 'twentytwentyfive-child' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Bottom Consultation CTA -->
		<section class="cta-section glass-card" style="padding: clamp(2rem, 4vw, 3rem); text-align: center; border-radius: var(--radius-lg); margin-top: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
			<h2 style="font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 700; margin-bottom: var(--spacing-xs); font-family: var(--font-heading); color: var(--color-text);">
				<?php esc_html_e( 'Ready to explore coaching?', 'twentytwentyfive-child' ); ?>
			</h2>
			<p style="font-size: 1rem; color: var(--color-text-muted); max-width: 600px; margin: 0 auto var(--spacing-md) auto;">
				<?php esc_html_e( 'Discuss your goals with our team to find the right format for you or your organization.', 'twentytwentyfive-child' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary">
				<?php esc_html_e( 'Book Consultation', 'twentytwentyfive-child' ); ?>
			</a>
		</section>

	</div>
</main>

<?php get_footer(); ?>
