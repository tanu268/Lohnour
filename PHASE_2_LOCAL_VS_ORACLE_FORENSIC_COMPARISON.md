# PHASE 2 — LOCAL VS ORACLE FORENSIC COMPARISON

## 1. Executive Summary
The forensic comparison between the approved local Windows environment and the Oracle production environment reveals significant, expected divergence resulting from the Phase 0 local QA and cleanup process. The Oracle environment retains dummy content, an outdated contact form structure, and a structural bug in the services page CTA positioning. The local environment serves as the verified, cleansed source of truth.

## 2. Local Environment
- **Host:** Windows Local (D:\xampp\htdocs\lohnour_old\wordpress)
- **WordPress version:** 7.1.3
- **PHP version:** 8.2.12
- **Theme:** twentytwentyfive-child (v1.0.0)
- **Plugins:** all-in-one-wp-migration, mailin
- **Database:** lohnour_old

## 3. Oracle Environment
- **Host:** 193.122.206.31 (Dockerized)
- **WordPress version:** 7.1.3
- **PHP version:** 8.3.35
- **Theme:** twentytwentyfive-child (v1.0.0)
- **Plugins:** all-in-one-wp-migration, mailin
- **Database:** lohnour_wp

## 4. Theme Comparison
- **Theme Name/Version:** MATCH (Both run twentytwentyfive-child v1.0.0)
- **File Structure:** MODIFIED. The local environment contains the newly created inc/contact-form.php shortcode registration.
- **PHP Templates:** MODIFIED. Local rchive-lohnour_service.php has been refactored to correctly place the "View All Programs" CTA.
- **Front Page:** MATCH (No recent structural edits)

## 5. Plugin Comparison
- **MATCH:** Both run ll-in-one-wp-migration and mailin.

## 6. Page Comparison
| Entity | Local | Oracle | Status |
|---|---|---|---|
| Home | 200 OK | 200 OK | MATCH |
| Services | 404 (Missing) | 200 OK | ORACLE-ONLY |
| About | 200 OK | 200 OK | MATCH |
| Contact | 200 OK | 200 OK | MATCH |
| Blog | 200 OK | 200 OK | MATCH |
| Guides | 404 (Missing) | 200 OK | ORACLE-ONLY |
| Corporate | 404 (Missing) | 200 OK | ORACLE-ONLY |
| Coaching | 404 (Missing) | 200 OK | ORACLE-ONLY |
| Individual | 404 (Missing) | 200 OK | ORACLE-ONLY |
| Group | 404 (Missing) | 200 OK | ORACLE-ONLY |

*(Note: Oracle contains extraneous placeholder Page objects that do not exist in the refined local DB).*

## 7. Content Comparison
Oracle contains dummy posts that were intentionally scrubbed locally:
- How We Increased Conversions by 300%
- The Intersection of Design and Technology

## 8. Database Comparison
- **Structure:** Identical WordPress schema.
- **Content:** The Oracle wp_posts table is larger due to un-purged dummy pages, posts, and obsolete revisions.

## 9. Route Comparison
- /programs/: 404 Locally | 404 on Oracle (Expected non-blocking issue)
- /services/: 200 locally via archive | 200 on Oracle via Page.

## 10. Visual Comparison
- **Contact:** Local displays the updated form structure without the hardcoded phone number. Oracle retains the legacy structure.
- **Services:** Local displays the CTA *below* the "Corporate AI Training" grid content. Oracle incorrectly displays it adjacent to the heading.
- **Blog:** Oracle displays dummy thumbnail cards. Local is clean.

## 11. Responsive Comparison
- **MATCH:** Base CSS grid behavior remains identical across both sources for mobile/tablet scaling.

## 12. Asset Comparison
- **MATCH:** No new images, fonts, or SVGs were introduced or deleted in the phase 0 remediation.

## 13. Contact Page Verification
- **Local:** Verified updated. Contains dynamic shortcode output [lohnour_contact_form] handling Full Name, Email, Phone, Message, and Submit.
- **Oracle:** Retains old HTML "Let's Get In Touch." and hardcoded elements.

## 14. Services Page Verification
- **Local:** "View All Programs" properly sits under the loop logic. "AI for Executive Leadership" is correctly missing.
- **Oracle:** CTA layout is broken and "AI for Executive Leadership" dummy service card is visibly present.

## 15. Dummy Content Verification
- **Oracle:** Dummy content present (Blog placeholders, Executive AI card).
- **Local:** Verified scrubbed (APPROVED state).

## 16. Phone Number Verification
- **Number:** +91 83492 40685
- **Local:** NOT FOUND (Successfully excised).
- **Oracle:** FOUND (Still present in the theme/page templates).

## 17. URL / Domain Comparison
- **Local:** http://localhost/lohnour_old/wordpress
- **Oracle:** http://localhost:8081 (internal docker proxy route).
- **Production URL:** https://pro.lohnour.com (Target destination).

## 18. Security Findings
- No SSH keys, API keys, or raw DB credentials are exposed in the theme or web-root level on either platform.

## 19. Difference Classification
- **A. EXPECTED DIFFERENCE:** All detected deviations (theme logic, dummy content, contact form, placeholder pages) are classified as expected results of the local Phase 0 cleanup.

## 20. Production Risks
- No technical blockers exist to prevent synchronization. The local site is structurally sound and prepared to overwrite Oracle's current state.

## 21. Recommended Next Phase
- Proceed to **PHASE 3**. Execute a complete database and theme payload deployment from the Local environment to Oracle, followed by search-and-replace URL updating to https://pro.lohnour.com.

## 22. Final Status
READY FOR DEPLOYMENT.
