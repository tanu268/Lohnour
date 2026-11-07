import json
import os
import sys
import subprocess

sys.stdout.reconfigure(encoding='utf-8')

from deploy_config import get_ssh_config
SSH_KEY, HOST = get_ssh_config()
WP_CONTAINER = "lohnour-wordpress-1"

# Read extracted data
with open(r'd:/lohnourpro/agentic_ai_foundations_data.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

# Build PHP script to create post and set meta
php_script = f"""<?php
require_once '/var/www/html/wp-load.php';

$slug = 'agentic-ai-foundations-dynamic';

// Check if already exists
$existing = get_page_by_path( $slug, OBJECT, 'lohnour_program' );
$post_id = $existing ? $existing->ID : 0;

$post_data = array(
    'post_title'   => '{data["title"]}',
    'post_name'    => $slug,
    'post_type'    => 'lohnour_program',
    'post_status'  => 'publish',
    'post_excerpt' => '{data["hero_desc"]}',
);

if ( $post_id ) {{
    $post_data['ID'] = $post_id;
    wp_update_post( $post_data );
    echo "Updated existing program post ID: $post_id\\n";
}} else {{
    $post_id = wp_insert_post( $post_data );
    echo "Created new program post ID: $post_id\\n";
}}

if ( is_wp_error( $post_id ) ) {{
    echo "ERROR: " . $post_id->get_error_message() . "\\n";
    exit(1);
}}

// Set post thumbnail to light banner if exists
if ( 161 ) {{
    set_post_thumbnail( $post_id, 161 );
}}

// Meta Data
update_post_meta( $post_id, '_lohnour_program_hero_desc', '{data["hero_desc"]}' );

$facts = array(
    'duration_hours'   => '{data["duration_hours"]}',
    'length_text'      => '{data["length_text"]}',
    'max_participants' => '{data["max_participants"]}',
    'delivery_style'   => '{data["delivery_style"]}',
);
update_post_meta( $post_id, '_lohnour_program_facts', $facts );

$banners = array(
    'dark_id'  => 160,
    'light_id' => 161,
    'alt'      => '{data["banner_alt"]}',
);
update_post_meta( $post_id, '_lohnour_program_banners', $banners );

$ctas = array(
    'primary_label'   => '{data["cta_label"]}',
    'primary_url'     => home_url( '{data["cta_url"]}' ),
    'secondary_label' => '{data["secondary_cta_label"]}',
    'secondary_url'   => '{data["secondary_cta_url"]}',
);
update_post_meta( $post_id, '_lohnour_program_ctas', $ctas );

// Overview
$overview_json = {json.dumps(data["overview"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_overview', $overview_json );

// Why
$why_json = {json.dumps(data["why_program"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_why', $why_json );

// Curriculum
$curriculum_json = {json.dumps(data["curriculum"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_curriculum', $curriculum_json );

// Build
$build_json = {json.dumps(data["what_youll_build"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_build', $build_json );

// Outcomes
$outcomes_json = {json.dumps(data["outcomes"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_outcomes', $outcomes_json );

// Skills
$skills_json = {json.dumps(data.get("skills", {
    "badge": "Toolbox & Disciplines",
    "title": "Skills You'll Build",
    "subtitle": "Direct competencies cultivated through hands-on exercises across the 8 modules.",
    "tags": [
        "Agentic AI", "Prompt Engineering", "Generative AI", "Python for AI",
        "AI Automation", "n8n Workflows", "API Integration", "Workflow Design",
        "AI Agents", "Tool Calling", "Agent Evaluation", "Multi-Agent Systems"
    ]
}), ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_skills', $skills_json );

// Career
$career_json = {json.dumps(data["career"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_career', $career_json );

// Format
$format_json = {json.dumps(data["format"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_format', $format_json );

// Scope
$scope_json = {json.dumps(data["scope"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_scope', $scope_json );

// Enrollment
$enrollment_json = {json.dumps(data["enrollment"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_enrollment', $enrollment_json );

// FAQs
$faqs_json = {json.dumps(data["faqs"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_faqs', $faqs_json );

// Closing CTA
$closing_json = {json.dumps(data["closing_cta"], ensure_ascii=False)};
update_post_meta( $post_id, '_lohnour_program_closing_cta', $closing_json );

echo "Successfully migrated all structured fields to post ID $post_id!\\n";
echo "Permalink: " . get_permalink( $post_id ) . "\\n";
"""

with open(r'd:/lohnourpro/migrate_script.php', 'w', encoding='utf-8') as f:
    f.write(php_script)

print("Created migrate_script.php. Copying to server...", flush=True)

# SCP script to /tmp/
subprocess.run([
    "scp", "-i", SSH_KEY, r"d:/lohnourpro/migrate_script.php", f"{HOST}:/tmp/migrate_script.php"
], check=True)

# Execute via docker exec
res = subprocess.run([
    "ssh", "-i", SSH_KEY, HOST,
    f"sudo docker cp /tmp/migrate_script.php {WP_CONTAINER}:/tmp/migrate_script.php && "
    f"sudo docker exec {WP_CONTAINER} php /tmp/migrate_script.php && "
    f"sudo docker exec {WP_CONTAINER} rm -f /tmp/migrate_script.php && "
    f"rm -f /tmp/migrate_script.php"
], capture_output=True, text=True)

print(res.stdout, flush=True)
if res.stderr:
    print("STDERR:\n" + res.stderr, flush=True)

print("Migration completed!", flush=True)
