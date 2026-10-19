/**
 * Lohnour Pro Theme Navigation Actions
 * Handles mobile hamburger overlay drawer toggles and light/dark theme toggling.
 */
console.log('Lohnour Pro navigation script initialized.');

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Functionality
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');

    console.log('Navigation DOM check:', { menuToggle: !!menuToggle, mainNavigation: !!mainNavigation });

    if (menuToggle && mainNavigation) {
        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            
            menuToggle.classList.toggle('active');
            mainNavigation.classList.toggle('active');

            console.log('Menu toggled. Active state:', mainNavigation.classList.contains('active'));

            // Prevent body scroll when menu is active
            document.body.style.overflow = mainNavigation.classList.contains('active') ? 'hidden' : '';
        });

        // Close menu when clicking navigation links (except dropdown toggles with href="#")
        const navLinks = mainNavigation.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.getAttribute('href') === '#') {
                    e.preventDefault();
                    const parentLi = link.closest('.menu-item-has-children');
                    if (parentLi) {
                        parentLi.classList.toggle('open');
                    }
                    return;
                }
                menuToggle.classList.remove('active');
                mainNavigation.classList.remove('active');
                menuToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            });
        });
    }

    // 2. Light / Dark Theme Switcher
    const themeToggle = document.querySelector('.theme-toggle');
    const moonIcon = document.querySelector('.theme-icon-moon');
    const sunIcon = document.querySelector('.theme-icon-sun');

    if (themeToggle && moonIcon && sunIcon) {
        const updateIcons = (isDark) => {
            if (isDark) {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'block';
            } else {
                moonIcon.style.display = 'block';
                sunIcon.style.display = 'none';
            }
        };

        // Initialize state of icons
        const isDarkInitial = document.documentElement.classList.contains('dark-theme');
        updateIcons(isDarkInitial);

        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isDark = document.documentElement.classList.toggle('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcons(isDark);
        });
    }
});
