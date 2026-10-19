<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
		(function() {
			const savedTheme = localStorage.getItem('theme');
			if (savedTheme === 'light' || (!savedTheme && window.matchMedia('(prefers-color-scheme: light)').matches)) {
				document.documentElement.classList.add('light-theme');
			}
		})();
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'twentytwentyfive-child' ); ?></a>

	<header id="masthead" class="site-header">
        <div class="container header-container">
            <div class="site-branding" style="display: flex; align-items: center; gap: 14px;">
                <?php
                $logo_white_path = LOHNOUR_THEME_DIR . '/assets/images/lohnour_pro_icon_WoTpBg_50x56.png';
                if ( file_exists( $logo_white_path ) ) {
                    $logo_white_url = LOHNOUR_THEME_URI . '/assets/images/lohnour_pro_icon_WoTpBg_50x56.png';
                    $logo_black_url = LOHNOUR_THEME_URI . '/assets/images/lohnour_pro_icon_BoTpBg_50x56.png';
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="display: flex; align-items: center;">
                        <img src="<?php echo esc_url( $logo_white_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-dark-theme" style="height: 40px; width: auto;">
                        <img src="<?php echo esc_url( $logo_black_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-light-theme" style="height: 40px; width: auto;">
                    </a>
                    <?php
                }
                ?>
                <h1 class="site-title" style="margin: 0; line-height: 1; display: inline-flex; align-items: center;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                        )
                    );
                } else {
                    // Fallback premium navigation
                    $is_services_active = ( is_post_type_archive( 'lohnour_service' ) || is_singular( 'lohnour_service' ) || is_page( 'coaching' ) );
                    ?>
                    <ul id="primary-menu">
                        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                        <li class="menu-item-has-children<?php echo $is_services_active ? ' current-menu-ancestor' : ''; ?>">
                            <a href="#" class="<?php echo $is_services_active ? 'active' : ''; ?>">Services <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;"><polyline points="6 9 12 15 18 9"></polyline></svg></a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo esc_url( home_url( '/services' ) ); ?>">Training</a></li>
                                <li><a href="<?php echo esc_url( home_url( '/coaching' ) ); ?>">Coaching</a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>">About</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/guides' ) ); ?>">Guides</a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav><!-- #site-navigation -->
            
            <div class="header-actions">
                <div class="header-cta">
                    <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary">Book Consultation</a>
                </div>

                <button class="theme-toggle" aria-label="Toggle theme" style="background: none; border: none; cursor: pointer; color: var(--color-accent); padding: 8px; display: flex; align-items: center; justify-content: center; z-index: 101;">
                    <svg class="theme-icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <svg class="theme-icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                </button>

                <button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="Toggle menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
	</header><!-- #masthead -->
