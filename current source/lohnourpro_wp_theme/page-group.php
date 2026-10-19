<?php
/**
 * Template Name: Group Coaching
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header(); ?>

<main id="primary" class="site-main coaching-detail-page" style="padding-top: var(--header-clearance); padding-bottom: 0;">

	<!-- HERO SECTION -->
	<header class="coaching-hero bg-alt" style="padding: var(--spacing-xxl) 0; border-bottom: 1px solid var(--color-border-subtle); margin-bottom: var(--spacing-xl);">
		<div class="container" style="max-width: 1300px; text-align: center;">
			


			<div style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-accent); background: rgba(var(--color-accent-rgb, 0, 112, 243), 0.1); border: 1px solid rgba(var(--color-accent-rgb, 0, 112, 243), 0.2); border-radius: 4px; margin-bottom: var(--spacing-md);">
				GROUP COACHING
			</div>
			
			<h1 class="headline-lg" style="font-family: var(--font-heading); line-height: 1.15; font-weight: 700; letter-spacing: -0.02em; max-width: 900px; margin: 0 auto var(--spacing-md) auto;">
				<?php
				$hero_title = get_post_meta( get_the_ID(), '_lohnour_hero_title', true );
				echo ! empty( $hero_title ) ? wp_kses_post( $hero_title ) : 'Learn, discuss, and grow together.';
				?>
			</h1>
			
			<p class="section-subtitle" style="max-width: 760px; font-size: 1.25rem; line-height: 1.6; color: var(--color-text-secondary); margin: 0 auto var(--spacing-xl) auto; font-weight: 400;">
				<?php
				$hero_subtitle = get_post_meta( get_the_ID(), '_lohnour_hero_subtitle', true );
				echo ! empty( $hero_subtitle ) ? wp_kses_post( $hero_subtitle ) : 'Learn AI alongside a small cohort of driven peers guided by an expert.';
				?>
			</p>
			
			<div style="display: flex; justify-content: center;">
				<?php
				$cta_label = get_post_meta( get_the_ID(), '_lohnour_cta_label', true );
				$cta_label = ! empty( $cta_label ) ? esc_html( $cta_label ) : 'Request Group Coaching';
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
					<h2 style="font-size: 2rem; color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em; margin-bottom: var(--spacing-md);">What Group Coaching Is</h2>
					<p style="margin-bottom: 1.5em;">Group coaching offers a collaborative learning format where participants gain not only from expert guidance but also from the shared experiences of their peers.</p>
					<p>It emphasizes collaborative problem solving, peer perspectives, and guided development in a supportive community setting.</p>
				<?php } ?>
			</div>
		</div>
	</section>

	<!-- THE LEARNING EXPERIENCE -->
	<section class="coaching-experience bg-alt" style="padding: var(--spacing-xxl) 0; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
		<div class="container" style="max-width: 1000px;">
			<h2 style="font-size: 2rem; text-align: center; color: var(--color-text-graphite); font-weight: 700; letter-spacing: -0.01em; margin-bottom: var(--spacing-xl);">The Learning Experience</h2>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 16px;">
					<div style="color: var(--color-accent); margin-top: 2px;">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
					</div>
					<div>
						<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Guided Discussions</h3>
						<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Engage in structured conversations led by an expert facilitator.</p>
					</div>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 16px;">
					<div style="color: var(--color-accent); margin-top: 2px;">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
					</div>
					<div>
						<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Practical Exercises</h3>
						<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Apply concepts immediately through hands-on group activities.</p>
					</div>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 16px;">
					<div style="color: var(--color-accent); margin-top: 2px;">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
					</div>
					<div>
						<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Shared Problem Solving</h3>
						<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Tackle complex AI integration challenges as a collective group.</p>
					</div>
				</div>
				<div style="padding: 24px; background: var(--color-bg-surface); border: 1px solid var(--color-border-subtle); border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 16px;">
					<div style="color: var(--color-accent); margin-top: 2px;">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
					</div>
					<div>
						<h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: 8px;">Peer Learning & Reflection</h3>
						<p style="font-size: 1rem; color: var(--color-text-secondary); line-height: 1.5; margin: 0;">Gain insights from diverse perspectives and reflect on collective growth.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- WHO IT'S FOR -->
	<section class="coaching-audience" style="padding: var(--spacing-xl) 0;">
		<div class="container" style="max-width: 900px; text-align: center;">
			<h2 style="font-size: 1.75rem; color: var(--color-text-graphite); font-weight: 700; margin-bottom: var(--spacing-md);">Who It's For</h2>
			<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px;">
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Teams</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Professionals</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Educators</span>
				<span style="display: inline-block; padding: 8px 16px; font-size: 0.95rem; background: var(--color-bg-alt); color: var(--color-text-secondary); border-radius: var(--radius-sm); border: 1px solid var(--color-border-subtle);">Groups seeking collaborative AI learning</span>
			</div>
		</div>
	</section>

	<!-- FINAL CTA -->
	<section class="cta-section" style="padding: var(--spacing-xxl) 0; background: var(--color-bg-surface); border-top: 1px solid var(--color-border-subtle);">
		<div class="container text-center" style="max-width: 800px;">
			<h2 style="font-size: 2.25rem; font-weight: 700; color: var(--color-text-graphite); margin-bottom: var(--spacing-lg); font-family: var(--font-heading);">Explore whether group coaching is right for you.</h2>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.05rem; font-weight: 600; border-radius: var(--radius-md);">Request Information</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
