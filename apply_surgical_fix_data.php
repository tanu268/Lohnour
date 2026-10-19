<?php
require_once '/var/www/html/wp-load.php';

$post_id = 162;

// 1. Fetch current meta
$enrollment  = get_post_meta( $post_id, '_lohnour_program_enrollment', true );
$closing_cta = get_post_meta( $post_id, '_lohnour_program_closing_cta', true );
$overview    = get_post_meta( $post_id, '_lohnour_program_overview', true );

$backup = array(
    'enrollment'  => $enrollment,
    'closing_cta' => $closing_cta,
    'overview'    => $overview,
);

file_put_contents( '/tmp/post_162_pre_fix_backup.json', json_encode( $backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
echo "BACKUP SAVED to /tmp/post_162_pre_fix_backup.json\n";

// 2. Fix 1A: Enrollment CTA label
if ( is_array( $enrollment ) ) {
    $old_label = $enrollment['cta_label'] ?? '';
    $enrollment['cta_label'] = 'Enquire About the Next Cohort';
    update_post_meta( $post_id, '_lohnour_program_enrollment', $enrollment );
    echo "Enrollment CTA fixed: Old: [" . $old_label . "] -> New: [" . $enrollment['cta_label'] . "]\n";
} else {
    echo "ERROR: Enrollment meta is not an array!\n";
}

// 3. Fix 1A: Closing CTA label
if ( is_array( $closing_cta ) ) {
    $old_closing_label = $closing_cta['cta_label'] ?? '';
    $closing_cta['cta_label'] = 'Enquire About the Next Cohort';
    update_post_meta( $post_id, '_lohnour_program_closing_cta', $closing_cta );
    echo "Closing CTA fixed: Old: [" . $old_closing_label . "] -> New: [" . $closing_cta['cta_label'] . "]\n";
} else {
    echo "ERROR: Closing CTA meta is not an array!\n";
}

// 4. Fix 2A: Overview Highlights
if ( is_array( $overview ) ) {
    $overview['highlights_h3'] = ! empty( $overview['highlights_h3'] ) ? $overview['highlights_h3'] : 'Key Cohort Highlights';
    $overview['highlights'] = array(
        array(
            'title' => 'Build-First Pedagogy',
            'desc'  => 'Every concept connects directly to a live build—from single prompt chains to tool-calling agents.',
        ),
        array(
            'title' => 'Small Cohort Intimacy',
            'desc'  => 'Strictly capped at 5 participants to guarantee direct code review, real-time debugging, and deep technical discussion.',
        ),
        array(
            'title' => 'Framework-Agnostic Clarity',
            'desc'  => 'Master fundamental agent mechanics in pure Python and n8n before relying on complex abstraction layers.',
        ),
    );
    update_post_meta( $post_id, '_lohnour_program_overview', $overview );
    echo "Overview Highlights populated with " . count( $overview['highlights'] ) . " approved items.\n";
} else {
    echo "ERROR: Overview meta is not an array!\n";
}

echo "DATA UPDATE COMPLETE.\n";
