<?php
/**
 * Template Name: Contact Page
 * The template for displaying the Contact page.
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main" style="padding-top: var(--header-clearance); padding-bottom: var(--spacing-lg);">
	<div class="container">
		<!-- Visually Consistent Header -->
		<?php
		get_template_part( 'template-parts/page-header', null, array(
			'title'       => __( 'Contact Us', 'twentytwentyfive-child' ),
			'subtitle'    => __( "Have a question, custom training inquiry, or partnership request? We're here to help.", 'twentytwentyfive-child' ),
			'breadcrumbs' => array(
				array( 'label' => __( 'Home', 'twentytwentyfive-child' ), 'url' => home_url( '/' ) ),
				array( 'label' => __( 'Contact', 'twentytwentyfive-child' ) ),
			),
		) );
		?>

		<!-- The Bento Box Contact Section -->
		<section class="contact-bento-section" style="margin-bottom: var(--spacing-xxl);">
			<div class="bento-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); grid-auto-rows: minmax(180px, auto); gap: var(--spacing-md);">
				
				<!-- Left Column: Contact Channels (Spans 4 columns) -->
				<div style="grid-column: span 4; display: flex; flex-direction: column; gap: var(--spacing-md);">
					
					<!-- Direct Email Tile -->
					<div class="bento-tile glass-card hover-lift" style="flex: 1; padding: clamp(1.25rem, 2vw, 2rem); display: flex; flex-direction: column; justify-content: center; border-top: 4px solid color-mix(in srgb, var(--color-accent) 40%, transparent);">
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-sm);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
						</div>
						<h3 style="font-size: 1.1rem; margin-bottom: 4px; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 1px;">Direct Email</h3>
						<a href="mailto:hello@pro.lohnour.com" style="text-decoration: none; color: var(--color-text); font-size: 1.25rem; font-weight: 500;">hello@pro.lohnour.com</a>
					</div>
					
					<!-- Encrypted Line Tile -->
					<div class="bento-tile glass-card hover-lift" style="flex: 1; padding: clamp(1.25rem, 2vw, 2rem); display: flex; flex-direction: column; justify-content: center; border-top: 4px solid color-mix(in srgb, var(--color-text) 10%, transparent);">
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-sm);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
						</div>
						<h3 style="font-size: 1.1rem; margin-bottom: 4px; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 1px;">Phone / WhatsApp</h3>
						<a href="tel:+918349240685" style="text-decoration: none; color: var(--color-text); font-size: 1.25rem; font-weight: 500;">+91 83492 40685</a>
					</div>
					
					<!-- Social Media Tile -->
					<div class="bento-tile glass-card hover-lift" style="flex: 1; padding: clamp(1.25rem, 2vw, 2rem); display: flex; flex-direction: column; justify-content: center; border-top: 4px solid color-mix(in srgb, var(--color-text) 5%, transparent);">
						<div class="icon-wrapper" style="width: 48px; height: 48px; background: color-mix(in srgb, var(--color-text) 5%, transparent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-text); margin-bottom: var(--spacing-sm);">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
						</div>
						<h3 style="font-size: 1.1rem; margin-bottom: 8px; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 1px;">Connect With Us</h3>
						<div style="display: flex; align-items: center; gap: 16px;">
							<a href="https://www.youtube.com/@lohnourpro" target="_blank" rel="noopener" class="social-link" style="color: var(--color-text); transition: all 0.3s;" aria-label="YouTube">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>
							</a>
							<a href="https://www.instagram.com/lohnourpro/" target="_blank" rel="noopener" class="social-link" style="color: var(--color-text); transition: all 0.3s;" aria-label="Instagram">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
							</a>
							<a href="https://www.linkedin.com/company/lohnourpro" target="_blank" rel="noopener" class="social-link" style="color: var(--color-text); transition: all 0.3s;" aria-label="LinkedIn">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
							</a>
							<a href="https://x.com/lohnourpro" target="_blank" rel="noopener" class="social-link" style="color: var(--color-text); transition: all 0.3s;" aria-label="Twitter">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
							</a>
						</div>
					</div>

				</div>

				<!-- Right Column: Transmission Form (Spans 8 columns) -->
				<div class="bento-tile glass-card hover-lift" style="grid-column: span 8; padding: clamp(2rem, 4vw, 4rem); display: flex; flex-direction: column; position: relative; overflow: hidden;">
					<!-- Decorative glowing accents -->
					<div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: var(--color-accent); filter: blur(120px); opacity: 0.15; border-radius: 50%; pointer-events: none;"></div>
					
					<h2 style="font-size: 2rem; font-weight: 300; margin-bottom: 8px; color: var(--color-text);">Send an Inquiry</h2>
					<p style="color: var(--color-text-muted); font-size: 1.05rem; margin-bottom: var(--spacing-xl);">Provide your details and requirements below. Our team will get back to you promptly.</p>
					
					<div class="transmission-form-area styled-form-container" style="position: relative; z-index: 1;">
						<?php
						if ( have_posts() ) :
							while ( have_posts() ) :
								the_post();
								$content = get_the_content();
								if ( empty( trim( strip_tags( $content ) ) ) && !str_contains($content, '[') ) {
									echo '<div style="padding: 4rem 2rem; text-align: center; color: var(--color-text-secondary);"><p style="font-size: 1.1rem;">Please reach out to us directly via email or phone using the contact details provided.</p></div>';
								} else {
									the_content();
								}
							endwhile;
						endif;
						?>
					</div>
				</div>

			</div>
		</section>
	</div>
</main>

<style>
/* Make bento grid responsive on Contact Page */
@media (max-width: 992px) {
	.contact-bento-section .bento-grid {
		display: flex !important;
		flex-direction: column-reverse !important;
	}
	.contact-bento-section .bento-grid > div {
		width: 100% !important;
	}
}
.social-link:hover {
	color: var(--color-accent) !important;
	transform: translateY(-2px);
}
</style>

<?php get_footer(); ?>
