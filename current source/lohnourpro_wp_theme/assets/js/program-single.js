/**
 * Scoped Interactions for Agentic AI Foundations Program Page
 * Handles:
 * 1. Curriculum interactive accordion (single-open, smooth animation)
 * 2. FAQ preview accordion
 * 3. Sticky anchor nav scroll-spy and smooth scrolling
 */
document.addEventListener('DOMContentLoaded', function() {

	// 1. Module Primary Accordion (Single-Open, Smooth Height/Opacity Transition)
	const moduleBlocks = document.querySelectorAll('.curriculum-guide-module-block');

	function closeModule(mod) {
		const header = mod.querySelector('.module-accordion-header');
		const content = mod.querySelector('.module-accordion-content');
		if (!mod.classList.contains('active') || !content) return;

		// Set explicit measured height before animating down to 0
		content.style.maxHeight = content.scrollHeight + 'px';
		void content.offsetHeight; // Force reflow

		requestAnimationFrame(() => {
			content.style.maxHeight = '0px';
			mod.classList.remove('active');
			if (header) header.setAttribute('aria-expanded', 'false');
		});
	}

	function openModule(mod) {
		const header = mod.querySelector('.module-accordion-header');
		const content = mod.querySelector('.module-accordion-content');
		if (!content) return;

		mod.classList.add('active');
		if (header) header.setAttribute('aria-expanded', 'true');

		content.style.maxHeight = content.scrollHeight + 'px';

		function onEnd(e) {
			if (e && e.target !== content) return;
			content.removeEventListener('transitionend', onEnd);
			if (mod.classList.contains('active')) {
				content.style.maxHeight = 'none';
			}
		}
		content.addEventListener('transitionend', onEnd);
		setTimeout(onEnd, 380); // Safety fallback
	}

	moduleBlocks.forEach(mod => {
		const header = mod.querySelector('.module-accordion-header');
		if (!header) return;

		header.addEventListener('click', function(e) {
			e.preventDefault();
			const isOpen = mod.classList.contains('active');

			// Single-open logic: close all other open modules
			moduleBlocks.forEach(otherMod => {
				if (otherMod !== mod && otherMod.classList.contains('active')) {
					closeModule(otherMod);
				}
			});

			// Toggle target module
			if (isOpen) {
				closeModule(mod);
			} else {
				openModule(mod);
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});

	// 1b. Inner Disclosure Accordions (What You'll Learn, Practical, Outcome)
	const guideAccordionHeaders = document.querySelectorAll('.curriculum-guide-module-block .guides-accordion-header');
	guideAccordionHeaders.forEach(header => {
		const item = header.closest('.guides-accordion-item');
		const content = item ? item.querySelector('.guides-accordion-content') : null;
		const parentModuleContent = header.closest('.module-accordion-content');

		header.addEventListener('click', function(e) {
			e.preventDefault();
			if (!item || !content) return;

			const isOpen = item.classList.contains('active');
			if (isOpen) {
				item.classList.remove('active');
				header.setAttribute('aria-expanded', 'false');
				content.style.maxHeight = null;
			} else {
				item.classList.add('active');
				header.setAttribute('aria-expanded', 'true');
				content.style.maxHeight = content.scrollHeight + 'px';
			}

			// If parent module has a numeric max-height during animation, update it
			if (parentModuleContent && parentModuleContent.style.maxHeight && parentModuleContent.style.maxHeight !== 'none') {
				setTimeout(() => {
					if (parentModuleContent.style.maxHeight !== 'none') {
						parentModuleContent.style.maxHeight = parentModuleContent.scrollHeight + 'px';
					}
				}, 50);
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});

	// 2. FAQ Accordion Functionality
	const faqItems = document.querySelectorAll('.faq-item');
	faqItems.forEach(item => {
		const header = item.querySelector('.faq-header');
		const content = item.querySelector('.faq-content');

		header.addEventListener('click', function(e) {
			e.preventDefault();
			const isOpen = item.classList.contains('active');

			// Close all other FAQ accordion items
			faqItems.forEach(otherItem => {
				otherItem.classList.remove('active');
				const otherHeader = otherItem.querySelector('.faq-header');
				const otherContent = otherItem.querySelector('.faq-content');
				if (otherHeader) otherHeader.setAttribute('aria-expanded', 'false');
				if (otherContent) {
					otherContent.style.maxHeight = null;
					otherContent.setAttribute('hidden', '');
				}
			});

			if (!isOpen) {
				item.classList.add('active');
				header.setAttribute('aria-expanded', 'true');
				content.removeAttribute('hidden');
				content.style.maxHeight = content.scrollHeight + 'px';
			}
		});

		// Keyboard Accessibility (Enter & Space)
		header.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				header.click();
			}
		});
	});

	// 2. Anchor Navigation Smooth Scroll & Scroll-Spy
	const siteHeader = document.querySelector('.site-header');
	const anchorNav = document.querySelector('.program-anchor-nav');

	function updateHeaderHeightVar() {
		if (siteHeader) {
			const h = Math.round(siteHeader.getBoundingClientRect().height);
			document.documentElement.style.setProperty('--site-header-height', h + 'px');
		}
	}
	updateHeaderHeightVar();
	window.addEventListener('resize', updateHeaderHeightVar, { passive: true });

	const anchorLinks = document.querySelectorAll('.anchor-nav-list .anchor-link');
	const sections = [];

	anchorLinks.forEach(link => {
		const targetId = link.getAttribute('href');
		if (targetId && targetId.startsWith('#')) {
			const section = document.querySelector(targetId);
			if (section) {
				sections.push({ id: targetId, link: link, section: section });
			}
		}
	});

	// Handle all in-page anchor links including hero CTA buttons and anchor-nav links
	const inPageAnchors = document.querySelectorAll('a[href^="#"]:not([href="#"])');
	inPageAnchors.forEach(link => {
		link.addEventListener('click', function(e) {
			const targetId = link.getAttribute('href');
			if (targetId && targetId.length > 1) {
				const targetElem = document.querySelector(targetId);
				if (targetElem) {
					e.preventDefault();
					const headerH = siteHeader ? siteHeader.getBoundingClientRect().height : 81;
					const navH = anchorNav ? anchorNav.getBoundingClientRect().height : 46;
					const totalOffset = headerH + navH + 20; // 20px comfortable clearance below sticky nav
					const targetY = targetElem.getBoundingClientRect().top + window.pageYOffset - totalOffset;

					window.scrollTo({
						top: Math.max(0, targetY),
						behavior: 'smooth'
					});

					if (history.pushState) {
						history.pushState(null, null, targetId);
					}
				}
			}
		});
	});

	// Scroll-Spy for active anchor link
	if (sections.length > 0) {
		const onScroll = function() {
			const headerH = siteHeader ? siteHeader.getBoundingClientRect().height : 81;
			const navH = anchorNav ? anchorNav.getBoundingClientRect().height : 46;
			const scrollPos = window.scrollY + headerH + navH + 40;
			let currentSection = sections[0];

			for (let i = 0; i < sections.length; i++) {
				if (sections[i].section.offsetTop <= scrollPos) {
					currentSection = sections[i];
				}
			}

			anchorLinks.forEach(l => l.classList.remove('active'));
			if (currentSection && currentSection.link) {
				currentSection.link.classList.add('active');
			}
		};

		window.addEventListener('scroll', onScroll, { passive: true });
	}

});
