	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-brand">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: var(--spacing-sm);">
                        <?php
                        $logo_white_path = LOHNOUR_THEME_DIR . '/assets/images/lohnour_pro_icon_WoTpBg_50x56.png';
                        if ( file_exists( $logo_white_path ) ) {
                            $logo_white_url = LOHNOUR_THEME_URI . '/assets/images/lohnour_pro_icon_WoTpBg_50x56.png';
                            $logo_black_url = LOHNOUR_THEME_URI . '/assets/images/lohnour_pro_icon_BoTpBg_50x56.png';
                            ?>
                            <img src="<?php echo esc_url( $logo_white_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-dark-theme" style="height: 36px; width: auto;">
                            <img src="<?php echo esc_url( $logo_black_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-light-theme" style="height: 36px; width: auto;">
                            <?php
                        }
                        ?>
                        <h3 class="footer-title" style="margin: 0; line-height: 1; display: inline-flex; align-items: center;"><?php bloginfo( 'name' ); ?></h3>
                    </div>
					<p class="footer-desc">Helping organizations and professionals adopt AI through practical training, consulting, and implementation.</p>
					<div class="footer-social">
						<!-- SVG icons for social links -->
						<a href="https://www.youtube.com/@lohnourpro" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>
						</a>
						<a href="https://www.instagram.com/lohnourpro/" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
						</a>
						<a href="https://www.linkedin.com/company/lohnourpro" target="_blank" rel="noopener" aria-label="LinkedIn" class="social-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
						</a>
						<a href="https://x.com/lohnourpro" target="_blank" rel="noopener" aria-label="Twitter" class="social-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
						</a>
					</div>
				</div>
				
				<div class="footer-nav">
					<h4 class="footer-heading">Navigation</h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>">Programs</a></li>
						<li><a href="<?php echo esc_url( home_url( '/coaching/' ) ); ?>">Coaching</a></li>
						<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
						<li><a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">Guides</a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
						<li style="margin-top: 8px;"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
					</ul>
				</div>
				
				<div class="footer-nav">
					<?php if ( is_active_sidebar( 'footer-newsletter' ) ) : ?>
						<?php dynamic_sidebar( 'footer-newsletter' ); ?>
					<?php else : ?>
						<h4 class="footer-heading">Newsletter</h4>
						<p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 10px;">Subscribe to get the latest updates.</p>
						<p style="font-size: 0.8rem; opacity: 0.5;">[Add your Brevo Widget here via WP Admin > Appearance > Widgets]</p>
					<?php endif; ?>
				</div>
				
				<div class="footer-nav">
					<h4 class="footer-heading">Contact</h4>
					<ul>
						<li><a href="mailto:hello@pro.lohnour.com" style="color: inherit; text-decoration: none;">hello@pro.lohnour.com</a></li>
						<li><a href="tel:+918349240685" style="color: inherit; text-decoration: none;">+91 83492 40685</a></li>
						<li>Indore, India</li>
					</ul>
				</div>
			</div>
			
			<div class="footer-bottom">
				<div class="site-info">
					&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.
				</div>
				<div class="footer-legal">
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const currentPath = window.location.pathname;
			const normalize = p => p.replace(/\/$/, '') || '/';
			const normalizedCurrent = normalize(currentPath);

			const navLinks = document.querySelectorAll('.main-navigation a, .footer-nav a');
			navLinks.forEach(link => {
				try {
					const linkPath = new URL(link.href, window.location.origin).pathname;
					if (normalizedCurrent === normalize(linkPath)) {
						link.classList.add('active');
						const parentLi = link.closest('li');
						if (parentLi) {
							parentLi.classList.add('current-menu-item', 'active');
						}
					}
				} catch(e) {}
			});
		});
	</script>

<?php wp_footer(); ?>
</body>
</html>
