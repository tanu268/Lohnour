# LOHNOUR PRO — PHASE 8.1 GUIDE RESTORATION FORENSIC

## 1. Current Guide Architecture (Phase 8)
In Phase 8, the Guide experience was flattened into a single-column, blog-style editorial layout. Parent and child relationships were visually diminished in favor of a standard article reading experience without structured navigation, previous/next buttons, or clear module groupings.

## 2. Previous Guide Architecture
Prior to Phase 8, the Guide system acted as a structured mini-LMS utilizing native WordPress hierarchical post relationships. Parent posts acted as the overall Guide (e.g., "Agentic AI Mastery"). Child posts acted as individual Articles (e.g., "What is an AI Agent?"). The UI utilized a 3-column layout: Left Curriculum Outline, Center Content, Right "On This Page" TOC.

## 3. What Phase 8 Removed/Changed
- **Left Curriculum Navigation**: Removed the sidebar that displayed all modules and their respective articles with an active state.
- **Right TOC Sidebar**: Removed the dynamically generated "On this page" anchor navigation.
- **Previous/Next Pagination**: Removed the structured sequence navigation at the bottom of articles.
- **Module Groupings**: Flattened the visual representation of modules.

## 4. Existing Module Structure
Modules are NOT a separate post type or taxonomy. They are defined using custom post meta:
- `_guide_module_name`: Stored on child articles to define which module they belong to (e.g., "Module 1: Foundations").
- `_guide_modules_list`: Stored on the parent guide to define the sequential order of the modules.

## 5. Existing Article Structure
Articles are natively implemented as child posts of the main Guide post using the `post_parent` column in the WordPress database. Their specific order within a module is determined by the native `menu_order` field.

## 6. Existing Progress Capability
There is **no real user progress tracking** (no user completion states, backend LMS data, or auth-based progress tracking). The only truthful progress that can be derived is structural (e.g., "Article 3 of 12"). Any percentage displayed would be artificially generated based purely on the current page's index in the total article list, not actual user completion. 

## 7. Existing Parent/Child Relationships
Managed via standard WordPress `post_parent`. The Parent acts as the root curriculum node, and Children act as the reading material.

## 8. Existing Data Limitations
- No authenticated user state for tracking completions.
- Modules only exist as string metadata, not as discrete queryable entities.
- "On this page" TOC must rely on parsing the `post_content` for `<h2>`/`<h3>` tags, as there is no structured field for TOC items.

## 9. Exact Files That Need Modification
- `archive-user_guide.php`
- `single-user_guide.php`

## 10. Exact Files That Must Remain Untouched
- `single-lohnour_service.php`
- `archive-lohnour_service.php`
- `page-services.php`
- `page-corporate.php`
- `front-page.php`
- `taxonomy-lohnour_pathway.php`
- `inc/post-types.php`
- `inc/taxonomies.php`
- `header.php`
- `footer.php`
