<?php
/**
 * Template Name: Individual Coaching
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main coaching-detail-page" style="padding-top: var(--header-clearance); padding-bottom: 0;">

	<!-- HERO SECTION -->
	<header class="coaching-hero bg-alt" style="padding: var(--spacing-xxl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
		<div class="container" style="max-width: 1300px; text-align: center;">
			


			<div style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-accent); background: rgba(var(--color-accent-rgb, 0, 112, 243), 0.1); border: 1px solid rgba(var(--color-accent-rgb, 0, 112, 243), 0.2); border-radius: 4px; margin-bottom: var(--spacing-md);">
				INDIVIDUAL COACHING
			</div>
			
			<h1 class="headline-lg" style="font-family: var(--font-heading); line-height: 1.15; font-weight: 700; letter-spacing: -0.02em; max-width: 900px; margin: 0 auto var(--spacing-md) auto;">
				<?php
				$hero_title = get_post_meta( get_the_ID(), '_lohnour_hero_title', true );
				echo ! empty( $hero_title ) ? wp_kses_post( $hero_title ) : 'Focused guidance, built around your goals.';
				?>
			</h1>
			
			<p class="section-subtitle" style="max-width: 760px; font-size: 1.25rem; line-height: 1.6; color: var(--color-text-secondary); margin: 0 auto var(--spacing-xl) auto; font-weight: 400;">
				<?php
				$hero_subtitle = get_post_meta( get_the_ID(), '_lohnour_hero_subtitle', true );
				echo ! empty( $hero_subtitle ) ? wp_kses_post( $hero_subtitle ) : '1:1 AI guidance tailored exclusively to your career goals and workflow.';
				?>
			</p>
			
			<div style="display: flex; justify-content: center;">
				<?php
				$cta_label = get_post_meta( get_the_ID(), '_lohnour_cta_label', true );
				$cta_label = ! empty( $cta_label ) ? esc_html( $cta_label ) : 'Request Individual Coaching';
				?>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.05rem; font-weight: 600; border-radius: var(--radius-md);"><?php echo $cta_label; ?></a>
			</div>
		</div>
	</header>

	<!-- WHAT IT IS -->
	<section class="coaching-about" style="padding: var(--spacing-xl) 0;">
		<div class="container" style="max-width: 800px;">
			<div class="prose-content" style="font-size: 1.15rem; line-height: 1.8; color: var(--color-text-secondary);">
				<?php
				$content = get_the_content();
				if ( ! empty( $content ) ) {
					echo apply_filters( 'the_content', $content );
				} else {
				?>
					<h2 style="font-size: 2rem; color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em; margin-bottom: var(--spacing-md);">What Individual Coaching Is</h2>
					<p style="margin-bottom: 1.5em;">Individual coaching is a premium, bespoke learning experience. Rather than following a generalized curriculum, we focus entirely on your professional context, solving your specific challenges with practical AI applications.</p>
					<p>It involves personalized attention, focused problem solving, and guided development to ensure you integrate AI effectively into your daily work.</p>
				<?php } ?>
			</div>
		</div>
	</section>

	<!-- AREAS OF SUPPORT -->
	<section class="coaching-areas bg-alt" style="padding: var(--spacing-xxl) 0; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
		<div class="container" style="max-width: 1000px;">
			<h2 style="font-size: 2rem; text-align: center; color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em; margin-bottom: var(--spacing-xl);">Areas of Support</h2>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">AI Workflow Improvement</h3>
					<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Streamline tasks and optimize your daily operations using the right AI tools.</p>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Prompt Engineering</h3>
					<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Master the art of communicating with LLMs to get exactly what you need.</p>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Agentic AI Concepts</h3>
					<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Understand and implement autonomous agents into complex business processes.</p>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md);">
					<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Professional AI Upskilling</h3>
					<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">A guided path to becoming the AI leader within your team or organization.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- HOW IT WORKS -->
	<section class="coaching-process" style="padding: var(--spacing-xxl) 0;">
		<div class="container" style="max-width: 1300px;">
			<div class="section-header text-center" style="margin-bottom: var(--spacing-xl);">
				<h2 style="font-size: 2rem; color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em;">How It Works</h2>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-lg);">
				<div style="padding: var(--spacing-lg); text-align: center;">
					<div style="font-size: 1.25rem; font-weight: 700; color: var(--color-accent); margin-bottom: 8px; font-family: var(--font-heading);">01</div>
					<h4 style="font-size: 1.15rem; font-weight: 600; color: var(--color-text-graphite); margin-bottom: 8px;">Share your goals</h4>
				</div>
				<div style="padding: var(--spacing-lg); text-align: center; border-left: 1px solid var(--color-border-subtle);">
					<div style="font-size: 1.25rem; font-weight: 700; color: var(--color-accent); margin-bottom: 8px; font-family: var(--font-heading);">02</div>
					<h4 style="font-size: 1.15rem; font-weight: 600; color: var(--color-text-graphite); margin-bottom: 8px;">Discuss the right area of support</h4>
				</div>
				<div style="padding: var(--spacing-lg); text-align: center; border-left: 1px solid var(--color-border-subtle);">
					<div style="font-size: 1.25rem; font-weight: 700; color: var(--color-accent); margin-bottom: 8px; font-family: var(--font-heading);">03</div>
					<h4 style="font-size: 1.15rem; font-weight: 600; color: var(--color-text-graphite); margin-bottom: 8px;">Define a focused coaching approach</h4>
				</div>
				<div style="padding: var(--spacing-lg); text-align: center; border-left: 1px solid var(--color-border-subtle);">
					<div style="font-size: 1.25rem; font-weight: 700; color: var(--color-accent); margin-bottom: 8px; font-family: var(--font-heading);">04</div>
					<h4 style="font-size: 1.15rem; font-weight: 600; color: var(--color-text-graphite); margin-bottom: 8px;">Begin guided development</h4>
				</div>
			</div>
		</div>
	</section>

	<!-- WHO IT'S FOR -->
	<section class="coaching-audience bg-alt" style="padding: var(--spacing-xl) 0; border-top: 1px solid var(--color-border-subtle);">
		<div class="container" style="max-width: 900px; text-align: center;">
			<h2 style="font-size: 1.75rem; color: var(--color-text-graphite); font-weight: 700; margin-bottom: var(--spacing-md);">Who It's For</h2>
			<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px;">
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-surface); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Students</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-surface); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Professionals</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-surface); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Educators</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-surface); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Individuals adopting AI in their work</span>
			</div>
		</div>
	</section>

	<!-- FINAL CTA -->
	<section class="cta-section" style="padding: var(--spacing-xxl) 0; background: var(--color-bg-surface); border-top: 1px solid var(--color-border-subtle);">
		<div class="container text-center" style="max-width: 800px;">
			<h2 style="font-size: 2.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: var(--spacing-lg); font-family: var(--font-heading);">Let's talk about what you're working toward.</h2>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.05rem; font-weight: 600; border-radius: var(--radius-md);">Talk to Our Team</a>
		</div>
	</section>

</main>

<style>
	@media (max-width: 768px) {
		.coaching-process .container > div:last-child {
			grid-template-columns: 1fr;
		}
		.coaching-process .container > div:last-child > div {
			border-left: none !important;
			border-top: 1px solid var(--color-border-subtle);
		}
		.coaching-process .container > div:last-child > div:first-child {
			border-top: none;
		}
	}
</style>

<?php get_footer(); ?>
