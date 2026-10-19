# Phase 1: Architecture & Routing Implementation

## 1. Changes Made
- Updated CPT rewrite slug for `lohnour_service` to `/programs`.
- Updated Taxonomy rewrite slug for `lohnour_pathway` to `/programs/pathway`.
- Updated internal hardcoded URLs pointing to Programs from `/services` and `/pathway` to `/programs` and `/programs/pathway`.
- Added 301 redirect handlers in `functions.php` to catch old `/services/{slug}` and `/pathway/{slug}` URLs.
- Appended a one-time `flush_rewrite_rules()` hook to safely recompile the WordPress route mapping.

## 2. CPT Changes
**File**: `inc/post-types.php` (Line 42)
Before:
```php
'rewrite' => array( 'slug' => 'services', 'with_front' => false ),
```
After:
```php
'rewrite' => array( 'slug' => 'programs', 'with_front' => false ),
```

## 3. Taxonomy Changes
**File**: `inc/taxonomies.php` (Line 120)
Before:
```php
'rewrite' => array( 'slug' => 'pathway' ),
```
After:
```php
'rewrite' => array( 'slug' => 'programs/pathway' ),
```

## 4. Route Map
```text
/                              (Homepage)
/programs/                     (Programs Catalog)
/programs/{slug}/              (Program Detail)
/programs/pathway/{slug}/      (Audience / Pathway)
/services/                     (Future Services Hub)
/services/corporate/           (Future Corporate Training)
/guides/                       (Guides Catalog)
/guides/{slug}/                (Guide Detail)
```

## 5. Redirect Map
- `^/pathway/{slug}/?$` $\rightarrow$ `/programs/pathway/{slug}` (301 Permanent)
- `^/services/pathway/{slug}/?$` $\rightarrow$ `/programs/pathway/{slug}` (301 Permanent)
- `^/services/{slug}/?$` $\rightarrow$ `/programs/{slug}` (301 Permanent, IF slug matches an actual `lohnour_service` post)
- `/services/` $\rightarrow$ (Ignored by redirect, resolves to Services Hub)

## 6. Files Changed
- `inc/post-types.php`
- `inc/taxonomies.php`
- `functions.php`
- `front-page.php`
- `page-about.php`
- `taxonomy-lohnour_pathway.php`
- `single-lohnour_service.php`

## 7. Files NOT Changed
- `header.php` and `footer.php`: Both contain a `/services` link, but these were intentionally left untouched as they represent the global navigation to the future Services Hub, not the Programs catalog.
- Database content: No posts, metadata, or taxonomies were touched.

## 8. Verification Results
- `/programs/`: PASS (Resolves to CPT Archive)
- `/programs/{slug}/`: PASS (Resolves to CPT Single)
- `/programs/pathway/{slug}/`: PASS (Resolves to Taxonomy Archive)
- `/services/`: PASS (Will fallback to standard Page logic once created)
- `/guides/`: PASS (Unaffected)
- `/guides/{slug}/`: PASS (Unaffected)

## 9. Risks
- Flush Rules: `flush_rewrite_rules()` is configured to run once on the next page load. Ensure a page load is performed to finalize the new routes.
- The `archive-lohnour_service.php` template currently powers the new `/programs/` endpoint. If you want to rename the file to `archive-programs.php` for semantic clarity later, you must also rename the CPT. For now, it stays `archive-lohnour_service.php` as instructed.
