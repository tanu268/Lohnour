import os
import sys
import subprocess

sys.stdout.reconfigure(encoding='utf-8')

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()
WP_CONTAINER = "lohnour-wordpress-1"

# 1. SCP JSON data file to /tmp/
print("--- 1. UPLOADING DATA JSON ---", flush=True)
subprocess.run([
    "scp", "-i", SSH_KEY, r"d:/lohnourpro/agentic_ai_foundations_data.json", f"{HOST}:/tmp/agentic_data.json"
], check=True)

# 2. Create clean PHP migration script
php_code = '''<?php
require_once '/var/www/html/wp-load.php';

$json_file = '/tmp/agentic_data.json';
if ( ! file_exists( $json_file ) ) {
    die( "ERROR: $json_file does not exist!\\n" );
}

$data = json_decode( file_get_contents( $json_file ), true );
if ( ! $data ) {
    die( "ERROR: Failed to parse JSON!\\n" );
}

$slug = 'agentic-ai-foundations-dynamic';

// Check if post already exists
$existing = get_page_by_path( $slug, OBJECT, 'lohnour_program' );
$post_id = $existing ? $existing->ID : 0;

$post_data = array(
    'post_title'   => $data['title'],
    'post_name'    => $slug,
    'post_type'    => 'lohnour_program',
    'post_status'  => 'publish',
    'post_excerpt' => $data['hero_desc'],
);

if ( $post_id ) {
    $post_data['ID'] = $post_id;
    wp_update_post( $post_data );
    echo "Updated existing program post ID: $post_id\\n";
} else {
    $post_id = wp_insert_post( $post_data );
    echo "Created new program post ID: $post_id\\n";
}

if ( is_wp_error( $post_id ) ) {
    die( "WP_ERROR: " . $post_id->get_error_message() . "\\n" );
}

// Set Cover Image
set_post_thumbnail( $post_id, 161 );

// 1. Hero Description
update_post_meta( $post_id, '_lohnour_program_hero_desc', $data['hero_desc'] );

// 2. Facts
$facts = array(
    'duration_hours'   => $data['duration_hours'],
    'length_text'      => $data['length_text'],
    'max_participants' => $data['max_participants'],
    'delivery_style'   => $data['delivery_style'],
);
update_post_meta( $post_id, '_lohnour_program_facts', $facts );

// 3. Banners
$banners = array(
    'dark_id'  => 160,
    'light_id' => 161,
    'alt'      => $data['banner_alt'],
);
update_post_meta( $post_id, '_lohnour_program_banners', $banners );

// 4. CTAs
$ctas = array(
    'primary_label'   => $data['cta_label'],
    'primary_url'     => home_url( $data['cta_url'] ),
    'secondary_label' => $data['secondary_cta_label'],
    'secondary_url'   => $data['secondary_cta_url'],
);
update_post_meta( $post_id, '_lohnour_program_ctas', $ctas );

// 5. Overview
update_post_meta( $post_id, '_lohnour_program_overview', $data['overview'] );

// 6. Why
update_post_meta( $post_id, '_lohnour_program_why', $data['why_program'] );

// 7. Curriculum
update_post_meta( $post_id, '_lohnour_program_curriculum', $data['curriculum'] );

// 8. Build
update_post_meta( $post_id, '_lohnour_program_build', $data['what_youll_build'] );

// 9. Outcomes
update_post_meta( $post_id, '_lohnour_program_outcomes', $data['outcomes'] );

// 10. Skills
$skills = isset( $data['skills'] ) ? $data['skills'] : array(
    'badge'    => 'Toolbox & Disciplines',
    'title'    => "Skills You'll Build",
    'subtitle' => 'Direct competencies cultivated through hands-on exercises across the 8 modules.',
    'tags'     => array(
        'Agentic AI', 'Prompt Engineering', 'Generative AI', 'Python for AI',
        'AI Automation', 'n8n Workflows', 'API Integration', 'Workflow Design',
        'AI Agents', 'Tool Calling', 'Agent Evaluation', 'Multi-Agent Systems'
    )
);
update_post_meta( $post_id, '_lohnour_program_skills', $skills );

// 11. Career
update_post_meta( $post_id, '_lohnour_program_career', $data['career'] );

// 12. Format
update_post_meta( $post_id, '_lohnour_program_format', $data['format'] );

// 13. Scope
update_post_meta( $post_id, '_lohnour_program_scope', $data['scope'] );

// 14. Enrollment
update_post_meta( $post_id, '_lohnour_program_enrollment', $data['enrollment'] );

// 15. FAQs
update_post_meta( $post_id, '_lohnour_program_faqs', $data['faqs'] );

// 16. Closing CTA
update_post_meta( $post_id, '_lohnour_program_closing_cta', $data['closing_cta'] );

echo "SUCCESS: Program post $post_id fully populated!\\n";
echo "URL: " . get_permalink( $post_id ) . "\\n";
'''

with open(r'd:/lohnourpro/migrate_clean.php', 'w', encoding='utf-8') as f:
    f.write(php_code)

print("--- 2. UPLOADING MIGRATION PHP SCRIPT ---", flush=True)
subprocess.run([
    "scp", "-i", SSH_KEY, r"d:/lohnourpro/migrate_clean.php", f"{HOST}:/tmp/migrate_clean.php"
], check=True)

print("--- 3. EXECUTING MIGRATION INSIDE CONTAINER ---", flush=True)
cmd = (
    f"sudo docker cp /tmp/agentic_data.json {WP_CONTAINER}:/tmp/agentic_data.json && "
    f"sudo docker cp /tmp/migrate_clean.php {WP_CONTAINER}:/tmp/migrate_clean.php && "
    f"sudo docker exec {WP_CONTAINER} php /tmp/migrate_clean.php && "
    f"sudo docker exec {WP_CONTAINER} rm -f /tmp/agentic_data.json /tmp/migrate_clean.php && "
    f"rm -f /tmp/agentic_data.json /tmp/migrate_clean.php"
)

res = subprocess.run([
    "ssh", "-i", SSH_KEY, HOST, cmd
], capture_output=True, text=True)

print(res.stdout, flush=True)
if res.stderr:
    print("STDERR:\n" + res.stderr, flush=True)

print("Done!", flush=True)
