/**
 * Guides Hub JavaScript - Lohnour Pro
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Accordion Functionality
    const accordions = document.querySelectorAll('.guides-accordion-item');
    accordions.forEach(item => {
        const header = item.querySelector('.guides-accordion-header');
        const content = item.querySelector('.guides-accordion-content');
        
        header.addEventListener('click', () => {
            const isOpen = item.classList.contains('active');
            
            // Close other accordions
            accordions.forEach(otherItem => {
                otherItem.classList.remove('active');
                otherItem.querySelector('.guides-accordion-content').style.maxHeight = null;
            });
            
            if (!isOpen) {
                item.classList.add('active');
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    });

    // 2. Dynamic Table of Contents (TOC) with Scroll-Spy
    const tocList = document.querySelector('.guides-toc-list');
    const contentArea = document.querySelector('.guide-content');
    
    if (tocList && contentArea) {
        const headers = contentArea.querySelectorAll('h2, h3');
        const tocItems = [];

        headers.forEach((header, index) => {
            // Generate id if not exists
            if (!header.id) {
                header.id = 'guide-heading-' + index;
            }
            
            // Create link
            const li = document.createElement('li');
            li.style.paddingLeft = (header.tagName === 'H3') ? '15px' : '0px';
            
            const link = document.createElement('a');
            link.href = '#' + header.id;
            link.textContent = header.textContent;
            
            li.appendChild(link);
            tocList.appendChild(li);
            
            tocItems.push({
                element: header,
                link: link
            });
        });

        // Scroll Spy logic
        function scrollSpy() {
            let activeSet = false;
            
            // Reverse loop to find the last header that has scrolled past the top
            for (let i = tocItems.length - 1; i >= 0; i--) {
                const item = tocItems[i];
                const rect = item.element.getBoundingClientRect();
                
                // Header is past the top threshold (using 160px buffer to account for sticky header clearance)
                if (rect.top <= 160) {
                    tocItems.forEach(el => el.link.classList.remove('active'));
                    item.link.classList.add('active');
                    activeSet = true;
                    break;
                }
            }
            
            // If none are active, highlight the first
            if (!activeSet && tocItems.length > 0) {
                tocItems.forEach(el => el.link.classList.remove('active'));
                tocItems[0].link.classList.add('active');
            }
        }

        window.addEventListener('scroll', scrollSpy);
        scrollSpy(); // Initial call
    }

    // 3. Left Outline Accordion & Mobile Toggle Logic
    const outlineModules = document.querySelectorAll('.outline-module');
    outlineModules.forEach(module => {
        const header = module.querySelector('.outline-module-header');
        const content = module.querySelector('.outline-module-content');
        const chevron = module.querySelector('.module-chevron');
        
        // Initial setup for max-height on expanded module (use none to avoid 0px height calculation on hidden mobile container)
        if (module.classList.contains('expanded')) {
            content.style.maxHeight = "none";
        }
        
        header.addEventListener('click', () => {
            const isExpanded = module.classList.contains('expanded');
            
            // If this is the active lesson module and it is already expanded, prevent collapsing it
            if (module.classList.contains('active-lesson-module') && isExpanded) {
                return;
            }
            
            // Collapse all modules EXCEPT the active lesson module
            outlineModules.forEach(otherMod => {
                if (otherMod.classList.contains('active-lesson-module')) {
                    return; // Keep active lesson module open
                }
                otherMod.classList.remove('expanded');
                otherMod.querySelector('.outline-module-content').style.maxHeight = null;
                const otherChevron = otherMod.querySelector('.module-chevron');
                if (otherChevron) otherChevron.style.transform = 'rotate(0deg)';
                const otherHeaderTitle = otherMod.querySelector('h5');
                if (otherHeaderTitle) otherHeaderTitle.style.color = 'var(--color-text-muted)';
            });
            
            if (!isExpanded) {
                module.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + "px";
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                const headerTitle = module.querySelector('h5');
                if (headerTitle) headerTitle.style.color = 'var(--color-accent)';
            } else {
                module.classList.remove('expanded');
                content.style.maxHeight = null;
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                const headerTitle = module.querySelector('h5');
                if (headerTitle) headerTitle.style.color = 'var(--color-text-muted)';
            }
        });
    });

    const mobileToggleBtn = document.getElementById('mobile-outline-toggle');
    const leftSidebar = document.querySelector('.guides-sidebar-left');
    if (mobileToggleBtn && leftSidebar) {
        mobileToggleBtn.addEventListener('click', () => {
            const isOpen = leftSidebar.classList.contains('mobile-open');
            if (isOpen) {
                leftSidebar.classList.remove('mobile-open');
                mobileToggleBtn.classList.remove('active');
                document.body.style.overflow = '';
            } else {
                leftSidebar.classList.add('mobile-open');
                mobileToggleBtn.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });

        // Close mobile drawer when clicking any link in outline
        const sidebarLinks = leftSidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                leftSidebar.classList.remove('mobile-open');
                mobileToggleBtn.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
});
