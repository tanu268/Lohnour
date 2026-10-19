/**
 * Lohnour Pro Core JS Bootstrap
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('Lohnour Pro Child Theme main core script loaded.');
    
    // Custom UI Handler for Brevo Forms
    const setupBrevoCustomUI = () => {
        const msgContainers = document.querySelectorAll('.sib_msg_disp');
        
        msgContainers.forEach(msgDisp => {
            // Find the closest wrapper (widget or form block)
            const formWrapper = msgDisp.closest('.widget') || msgDisp.parentElement;
            if (!formWrapper) return;
            
            // Find our custom input container
            const customContainer = formWrapper.querySelector('.lohnour-form-container');
            const formElement = formWrapper.querySelector('form');
            if (!customContainer || !formElement) return;

            const input = customContainer.querySelector('.lohnour-form-input');
            const submitBtn = customContainer.querySelector('.lohnour-form-submit');
            const originalBtnText = submitBtn ? submitBtn.value : 'Subscribe';

            // Handle the loading state on submit
            formElement.addEventListener('submit', () => {
                // Defer the UI lock by a few milliseconds so Brevo's JS can read the form first!
                setTimeout(() => {
                    if (input) {
                        input.readOnly = true; // Use readOnly instead of disabled so it doesn't break serialization
                        input.style.opacity = '0.7';
                    }
                    if (submitBtn) {
                        // Hide original visually but don't disable it
                        submitBtn.style.display = 'none';
                        
                        let tempBtn = customContainer.querySelector('.temp-loading-btn');
                        if (!tempBtn) {
                            tempBtn = document.createElement('button'); // Use button type="button" to perfectly match input styling
                            tempBtn.type = 'button';
                            tempBtn.className = 'lohnour-form-submit temp-loading-btn';
                            tempBtn.style.cursor = 'wait';
                            tempBtn.style.display = 'flex';
                            tempBtn.style.alignItems = 'center';
                            tempBtn.style.justifyContent = 'center';
                            tempBtn.style.gap = '8px';
                            customContainer.appendChild(tempBtn);
                        }
                        tempBtn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Subscribing...`;
                    }
                }, 10);
            });
            
            // Watch the Brevo message container for changes
            const observer = new MutationObserver(() => {
                // Brevo often adds weird spaces before punctuation (e.g. "registered !"). This strips them.
                let messageText = msgDisp.textContent.trim().replace(/\s+([!?.])/g, '$1');
                
                // Only trigger if there is an actual message injected by Brevo
                if (messageText !== '') {
                    const isError = msgDisp.innerHTML.toLowerCase().includes('error') || msgDisp.classList.contains('sib_msg_disp_error');
                    
                    // Premium UI: subtle inline text with an icon, no borders or pill shape
                    const icon = isError ? 
                        `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>` : 
                        `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                    
                    const textColor = isError ? '#ff6b6b' : '#28a745';

                    // Hide original inputs safely instead of destroying them
                    if (input) input.style.display = 'none';
                    if (submitBtn) submitBtn.style.display = 'none';
                    const tempBtn = customContainer.querySelector('.temp-loading-btn');
                    if (tempBtn) tempBtn.remove();

                    // Create or update message block
                    let msgBlock = customContainer.querySelector('.lohnour-inline-msg');
                    if (!msgBlock) {
                        msgBlock = document.createElement('div');
                        msgBlock.className = 'lohnour-inline-msg';
                        customContainer.appendChild(msgBlock);
                    }
                    
                    msgBlock.innerHTML = `
                        <div style="
                            color: ${textColor};
                            padding: 12px 0;
                            text-align: left;
                            font-size: 0.95rem;
                            font-weight: 500;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            animation: fadeIn 0.3s ease-out;
                        ">
                            ${icon}
                            <span>${messageText}</span>
                        </div>
                    `;
                    
                    // Revert back to the input fields after 5 seconds
                    setTimeout(() => {
                        msgBlock.remove();
                        if (input) {
                            input.style.display = '';
                            input.readOnly = false; // Restore readOnly
                            input.style.opacity = '1';
                            input.value = ''; // clear input
                        }
                        if (submitBtn) {
                            submitBtn.style.display = '';
                            submitBtn.style.cursor = 'pointer';
                            submitBtn.style.opacity = '1';
                        }
                        msgDisp.innerHTML = ''; // clear out Brevo's invisible message
                    }, 5000);
                }
            });

            // Start observing the invisible Brevo container
            observer.observe(msgDisp, { childList: true, subtree: true, characterData: true });
        });
    };
    
    // Run setup after a short delay to ensure Brevo's DOM is loaded
    setTimeout(setupBrevoCustomUI, 500);
});
