<?php
/**
 * Contact Form Shortcode & AJAX Handler
 */
add_shortcode( 'lohnour_contact_form', 'lohnour_contact_form_shortcode' );
function lohnour_contact_form_shortcode() {
    ob_start();
    ?>
    <div class="lohnour-contact-form-container" style="margin-bottom: var(--spacing-xl);">
        <form id="lohnour-contact-form" class="lohnour-form" action="#" method="POST">
            <div class="form-group" style="margin-bottom: var(--spacing-md);">
                <label for="contact_name" style="display:block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text);">Full Name <span style="color:var(--color-accent);">*</span></label>
                <input type="text" id="contact_name" name="contact_name" class="lohnour-form-input" required />
            </div>
            <div class="form-group" style="margin-bottom: var(--spacing-md);">
                <label for="contact_email" style="display:block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text);">Email Address <span style="color:var(--color-accent);">*</span></label>
                <input type="email" id="contact_email" name="contact_email" class="lohnour-form-input" required />
            </div>
            <div class="form-group" style="margin-bottom: var(--spacing-md);">
                <label for="contact_phone" style="display:block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text);">Phone Number</label>
                <input type="tel" id="contact_phone" name="contact_phone" class="lohnour-form-input" />
            </div>
            <div class="form-group" style="margin-bottom: var(--spacing-md);">
                <label for="contact_message" style="display:block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-text);">Message <span style="color:var(--color-accent);">*</span></label>
                <textarea id="contact_message" name="contact_message" class="lohnour-form-input" style="border-radius: 16px; min-height: 120px; resize: vertical;" required></textarea>
            </div>
            <div class="form-group" style="margin-top: var(--spacing-lg);">
                <button type="submit" class="btn btn-primary" id="contact_submit_btn" style="width: 100%;">
                    <span class="btn-text">Submit Form</span>
                </button>
            </div>
            <div id="contact_form_response" style="margin-top: 15px; display: none; font-size: 0.9rem; padding: 12px; border-radius: 8px;"></div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('lohnour-contact-form');
        if(!form) return;
        const responseDiv = document.getElementById('contact_form_response');
        const submitBtn = document.getElementById('contact_submit_btn');
        const btnText = submitBtn.querySelector('.btn-text');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('contact_name').value.trim();
            const email = document.getElementById('contact_email').value.trim();
            const phone = document.getElementById('contact_phone').value.trim();
            const message = document.getElementById('contact_message').value.trim();
            
            if (!name || !email || !message) {
                responseDiv.style.display = 'block';
                responseDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
                responseDiv.style.color = 'var(--color-text)';
                responseDiv.style.border = '1px solid rgba(255,0,0,0.3)';
                responseDiv.innerText = 'Please fill out all required fields.';
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                responseDiv.style.display = 'block';
                responseDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
                responseDiv.style.color = 'var(--color-text)';
                responseDiv.style.border = '1px solid rgba(255,0,0,0.3)';
                responseDiv.innerText = 'Please enter a valid email address.';
                return;
            }

            btnText.innerText = 'Submitting...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            responseDiv.style.display = 'none';

            const formData = new FormData();
            formData.append('action', 'lohnour_submit_contact');
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('message', message);

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                responseDiv.style.display = 'block';
                if (data.success) {
                    responseDiv.style.backgroundColor = 'rgba(0, 255, 0, 0.1)';
                    responseDiv.style.border = '1px solid rgba(0,255,0,0.3)';
                    responseDiv.style.color = 'var(--color-text)';
                    responseDiv.innerText = 'Thank you! Your message has been sent.';
                    form.reset();
                } else {
                    responseDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
                    responseDiv.style.border = '1px solid rgba(255,0,0,0.3)';
                    responseDiv.style.color = 'var(--color-text)';
                    responseDiv.innerText = data.data || 'An error occurred. Please try again.';
                }
            })
            .catch(err => {
                responseDiv.style.display = 'block';
                responseDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
                responseDiv.style.border = '1px solid rgba(255,0,0,0.3)';
                responseDiv.style.color = 'var(--color-text)';
                responseDiv.innerText = 'A network error occurred. Please try again.';
            })
            .finally(() => {
                btnText.innerText = 'Submit Form';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

add_action( 'wp_ajax_lohnour_submit_contact', 'lohnour_handle_contact_submission' );
add_action( 'wp_ajax_nopriv_lohnour_submit_contact', 'lohnour_handle_contact_submission' );
function lohnour_handle_contact_submission() {
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('Missing fields.');
    }

    if (!is_email($email)) {
        wp_send_json_error('Invalid email.');
    }

    $to = 'hello@pro.lohnour.com';
    $subject = 'New Contact Form Submission from ' . $name;
    $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
    $headers = array('Reply-To: ' . $name . ' <' . $email . '>');

    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success('Sent successfully.');
    } else {
        wp_send_json_error('Mail delivery failed.');
    }
}
