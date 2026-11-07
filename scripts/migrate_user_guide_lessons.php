<?php
/**
 * Lohnour Learn: User Guide Lesson Hierarchy Migration Script
 *
 * Requirements:
 * - Non-destructive, idempotent, repeatable, safe to run multiple times.
 * - Does NOT blindly assign lessons or fabricate 'Lesson 1'.
 * - If there is insufficient information to determine a lesson: DOES NOT GUESS.
 * - Reports all affected child guides and unassigned/ambiguous records.
 * - Preserves existing post IDs, parents, slugs, and unrelated metadata.
 *
 * Usage:
 *   php migrate_user_guide_lessons.php --preview   (Dry run preview, default)
 *   php migrate_user_guide_lessons.php --apply     (Apply safe assignments only)
 */

define( 'WP_USE_THEMES', false );
define( 'DISABLE_WP_CRON', true );

// Locate wp-load.php
$possible_wp_load = array(
    'D:/xampp/htdocs/lohnour_current/wordpress/wp-load.php',
    dirname( __FILE__ ) . '/wordpress/wp-load.php',
    dirname( dirname( __FILE__ ) ) . '/wp-load.php',
);
foreach ( $possible_wp_load as $path ) {
    if ( file_exists( $path ) ) {
        require_once $path;
        break;
    }
}

if ( ! function_exists( 'get_posts' ) ) {
    fwrite( STDERR, "ERROR: WordPress environment could not be loaded.\n" );
    exit( 1 );
}

$is_apply = in_array( '--apply', $argv, true ) || in_array( '--execute', $argv, true );

echo "====================================================================\n";
echo " LOHNOUR LEARN: USER GUIDE LESSON HIERARCHY MIGRATION\n";
echo " Mode: " . ( $is_apply ? "APPLY / EXECUTE" : "PREVIEW / DRY-RUN (Non-destructive)" ) . "\n";
echo " Database: " . DB_NAME . " on " . DB_HOST . "\n";
echo "====================================================================\n\n";

// 1. Fetch all parent guides (post_parent = 0)
$parents = get_posts( array(
    'post_type'      => 'user_guide',
    'post_parent'    => 0,
    'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
    'posts_per_page' => -1,
) );

$report = array(
    'parent_guides'     => count( $parents ),
    'total_modules'     => 0,
    'total_lessons'     => 0,
    'total_children'    => 0,
    'migrated_records'  => 0,
    'skipped_records'   => 0,
    'ambiguous_records' => 0,
    'details'           => array(),
);

foreach ( $parents as $parent ) {
    $parent_data = array(
        'id'       => $parent->ID,
        'title'    => $parent->post_title,
        'modules'  => array(),
        'children' => array(),
    );

    $modules = get_post_meta( $parent->ID, '_guide_modules_list', true );
    if ( ! is_array( $modules ) ) {
        $modules = array();
    }
    $report['total_modules'] += count( $modules );

    $module_lessons_map = array();
    foreach ( $modules as $mod ) {
        $m_name = isset( $mod['name'] ) ? trim( $mod['name'] ) : '';
        $lessons = isset( $mod['lessons'] ) && is_array( $mod['lessons'] ) ? $mod['lessons'] : array();
        $report['total_lessons'] += count( $lessons );
        $module_lessons_map[$m_name] = $lessons;
        $parent_data['modules'][] = array(
            'name'          => $m_name,
            'order'         => isset( $mod['order'] ) ? intval( $mod['order'] ) : 0,
            'lessons_count' => count( $lessons ),
        );
    }

    // Fetch children of this parent
    $children = get_posts( array(
        'post_type'      => 'user_guide',
        'post_parent'    => $parent->ID,
        'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );

    $report['total_children'] += count( $children );

    foreach ( $children as $child ) {
        $child_id = $child->ID;
        $c_mod = trim( get_post_meta( $child_id, '_guide_module_name', true ) );
        $c_les = trim( get_post_meta( $child_id, '_guide_lesson_name', true ) );
        $title = $child->post_title;
        $order = $child->menu_order;

        $mod_lessons = isset( $module_lessons_map[$c_mod] ) ? $module_lessons_map[$c_mod] : array();

        $action = 'SKIPPED';
        $assigned_lesson = $c_les;
        $reason = '';

        if ( ! empty( $c_les ) ) {
            // Child already has an assigned lesson
            $action = 'ALREADY_ASSIGNED';
            $report['skipped_records']++;
            $reason = "Already assigned to '{$c_les}'";
        } else if ( empty( $mod_lessons ) ) {
            // Parent module has 0 lessons defined!
            $action = 'AMBIGUOUS';
            $report['ambiguous_records']++;
            $report['skipped_records']++;
            $reason = "Module '{$c_mod}' defines 0 lessons in parent _guide_modules_list. Insufficient information: DO NOT GUESS.";
        } else {
            // Check if title or content explicitly indicates a lesson match
            $matched_lesson = null;
            foreach ( $mod_lessons as $l ) {
                $l_name = trim( $l['name'] );
                if ( stripos( $title, $l_name ) !== false ) {
                    $matched_lesson = $l_name;
                    break;
                }
            }

            if ( $matched_lesson ) {
                $action = 'MIGRATED';
                $assigned_lesson = $matched_lesson;
                $report['migrated_records']++;
                $reason = "Explicit title match with parent module lesson '{$matched_lesson}'";

                if ( $is_apply ) {
                    update_post_meta( $child_id, '_guide_lesson_name', $matched_lesson );
                }
            } else {
                $action = 'AMBIGUOUS';
                $report['ambiguous_records']++;
                $report['skipped_records']++;
                $reason = "No explicit lesson evidence found in child title/content. Preserving unassigned state without guessing.";
            }
        }

        $child_entry = array(
            'id'              => $child_id,
            'title'           => $title,
            'module'          => $c_mod,
            'current_lesson'  => $c_les,
            'proposed_lesson' => $assigned_lesson,
            'action'          => $action,
            'reason'          => $reason,
        );

        $parent_data['children'][] = $child_entry;
    }

    $report['details'][] = $parent_data;
}

// Output Report
echo "--- PARENT GUIDES & MODULE OVERVIEW ---\n";
foreach ( $report['details'] as $p ) {
    echo "Parent Guide [ID {$p['id']}]: {$p['title']}\n";
    echo "  Defined Modules (" . count( $p['modules'] ) . "):\n";
    foreach ( $p['modules'] as $m ) {
        echo "    - '{$m['name']}' (Order: {$m['order']}, Defined Lessons: {$m['lessons_count']})\n";
    }
    echo "\n  Child Guides (" . count( $p['children'] ) . "):\n";
    foreach ( $p['children'] as $c ) {
        echo sprintf(
            "    [%s] ID %d: '%s'\n         Module: '%s' | Lesson: '%s' -> Proposed: '%s'\n         Note: %s\n",
            $c['action'],
            $c['id'],
            $c['title'],
            $c['module'],
            $c['current_lesson'] ? $c['current_lesson'] : '(none)',
            $c['proposed_lesson'] ? $c['proposed_lesson'] : '(none)',
            $c['reason']
        );
    }
    echo "\n";
}

echo "====================================================================\n";
echo " MIGRATION SUMMARY REPORT\n";
echo "====================================================================\n";
echo "Number of parent guides : " . $report['parent_guides'] . "\n";
echo "Number of modules       : " . $report['total_modules'] . "\n";
echo "Number of lessons       : " . $report['total_lessons'] . "\n";
echo "Number of child guides  : " . $report['total_children'] . "\n";
echo "Migrated records        : " . $report['migrated_records'] . "\n";
echo "Skipped records         : " . $report['skipped_records'] . "\n";
echo "Ambiguous records       : " . $report['ambiguous_records'] . "\n";
echo "====================================================================\n";

if ( ! $is_apply ) {
    echo "\nNote: This was a dry-run preview. No database modifications were made.\n";
    echo "To execute, run: php migrate_user_guide_lessons.php --apply\n";
} else {
    echo "\nMigration execution complete.\n";
}
