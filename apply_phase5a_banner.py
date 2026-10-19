import re
import sys

filepath = 'current source/lohnourpro_wp_theme/page-agentic-ai-foundations.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. HTML Insertion Target: inside .program-hero-grid after .program-hero-content
target_html = """\t\t\t\t\t<!-- Program CTA Actions -->
					<div class="program-hero-actions">
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg hero-btn-primary">
							<?php esc_html_e( 'Enquire About Next Cohort', 'twentytwentyfive-child' ); ?>
						</a>
						<a href="#curriculum" class="btn btn-outline btn-lg hero-btn-secondary">
							<?php esc_html_e( 'Explore 30-Hour Curriculum ↓', 'twentytwentyfive-child' ); ?>
						</a>
					</div>

				</div>
			</div>"""

replacement_html = """\t\t\t\t\t<!-- Program CTA Actions -->
					<div class="program-hero-actions">
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary btn-lg hero-btn-primary">
							<?php esc_html_e( 'Enquire About Next Cohort', 'twentytwentyfive-child' ); ?>
						</a>
						<a href="#curriculum" class="btn btn-outline btn-lg hero-btn-secondary">
							<?php esc_html_e( 'Explore 30-Hour Curriculum ↓', 'twentytwentyfive-child' ); ?>
						</a>
					</div>

				</div>

				<!-- 1.6 Theme-Aware Responsive Program Banner (Phase 5A) -->
				<div class="program-hero-banner" aria-label="<?php esc_attr_e( 'Program Banner', 'twentytwentyfive-child' ); ?>">
					<img 
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-dark.png' ); ?>" 
						alt="<?php esc_attr_e( 'Agentic AI Foundations participants building AI agent systems', 'twentytwentyfive-child' ); ?>" 
						class="banner-theme-dark" 
						width="1774" 
						height="887" 
						decoding="async" 
						fetchpriority="high"
					>
					<img 
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/agentic-ai-foundations-light.jpeg' ); ?>" 
						alt="<?php esc_attr_e( 'Agentic AI Foundations participants building AI agent systems', 'twentytwentyfive-child' ); ?>" 
						class="banner-theme-light" 
						width="1600" 
						height="800" 
						decoding="async" 
						fetchpriority="high"
					>
				</div>
			</div>"""

if target_html not in content:
    print("Error: Target HTML block not found!")
    sys.exit(1)

content = content.replace(target_html, replacement_html, 1)

# 2. CSS Insertion Target: in style block after hero action buttons
target_css = """.program-page .hero-btn-primary:focus-visible,
.program-page .hero-btn-secondary:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: 3px;
}"""

replacement_css = """.program-page .hero-btn-primary:focus-visible,
.program-page .hero-btn-secondary:focus-visible {
	outline: 2px solid var(--color-accent);
	outline-offset: 3px;
}

/* 1.6 Theme-Aware Program Hero Banner (Phase 5A) */
.program-page .program-hero-banner {
	width: 100%;
	border-radius: var(--radius-lg, 12px);
	overflow: hidden;
	border: 1px solid var(--color-border-subtle);
	background: var(--color-bg-surface);
	box-shadow: var(--shadow-subtle, 0 4px 20px rgba(0, 0, 0, 0.04));
	margin-top: var(--spacing-sm);
}

.program-page .program-hero-banner img {
	width: 100%;
	height: auto;
	aspect-ratio: 2 / 1;
	display: block;
	object-fit: cover;
	object-position: center;
	border-radius: inherit;
}

/* Theme Switching Mechanism */
.program-page .program-hero-banner .banner-theme-dark {
	display: block;
}
.program-page .program-hero-banner .banner-theme-light {
	display: none;
}

html.light-theme .program-page .program-hero-banner .banner-theme-dark {
	display: none !important;
}
html.light-theme .program-page .program-hero-banner .banner-theme-light {
	display: block !important;
}

html.dark-theme .program-page .program-hero-banner .banner-theme-dark {
	display: block !important;
}
html.dark-theme .program-page .program-hero-banner .banner-theme-light {
	display: none !important;
}"""

if target_css not in content:
    print("Error: Target CSS block not found!")
    sys.exit(1)

content = content.replace(target_css, replacement_css, 1)

# 3. Responsive CSS override in @media (max-width: 768px)
target_media = """@media (max-width: 768px) {
	.program-page .program-body-container {"""

replacement_media = """@media (max-width: 768px) {
	.program-page .program-hero-banner {
		margin-top: var(--spacing-xs);
		border-radius: var(--radius-md, 8px);
	}

	.program-page .program-body-container {"""

if target_media not in content:
    print("Error: Target Media block not found!")
    sys.exit(1)

content = content.replace(target_media, replacement_media, 1)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Successfully applied Phase 5A banner changes to page-agentic-ai-foundations.php")
