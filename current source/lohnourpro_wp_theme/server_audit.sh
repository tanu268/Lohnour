#!/bin/bash

BASE_URL="http://localhost"
ROUTES=(
    "/"
    "/programs/"
    "/services/"
    "/services/corporate/"
    "/corporate/"
    "/coaching/"
    "/coaching/individual/"
    "/coaching/group/"
    "/individual/"
    "/group/"
    "/guides/"
    "/about/"
    "/contact/"
    "/blog/"
    "/privacy-policy/"
    "/programs/ai-for-executive-leadership/"
    "/programs/team-workflow-automation/"
    "/programs/agentic-ai-developer-track/"
    "/programs/generative-ai-productivity/"
    "/programs/practical-prompt-engineering/"
    "/programs/ai-in-academic-curricula/"
    "/guides/introduction-to-crewai/"
    "/guides/designing-agent-communications/"
    "/guides/deploying-agents-to-production/"
    "/guides/monitoring-agent-decisions/"
    "/guides/test-parent-guide/"
    "/guides/agentic-ai-mastery/"
    "/guides/what-is-an-ai-agent/"
    "/guides/setting-up-langchain/"
    "/guides/giving-agents-browser-access/"
    "/programs/pathway/corporate-ai-training/"
    "/programs/pathway/faculty-development/"
    "/programs/pathway/professional-ai-upskilling/"
    "/programs/pathway/student-ai-programs/"
)

echo "=== 1. ROUTE MATRIX ==="
for route in "${ROUTES[@]}"; do
    output=$(curl -s -L -w "\nHTTP_STATUS:%{http_code}\nFINAL_URL:%{url_effective}\n" "${BASE_URL}${route}")
    status=$(echo "$output" | grep "HTTP_STATUS" | cut -d':' -f2)
    final=$(echo "$output" | grep "FINAL_URL" | cut -d':' -f2-)
    
    if echo "$output" | grep -q "Warning:"; then php_err="Yes"; else php_err="No"; fi
    if echo "$output" | grep -q "Fatal error"; then php_fatal="Yes"; else php_fatal="No"; fi
    if echo "$output" | grep -q "WordPress database error"; then wp_err="Yes"; else wp_err="No"; fi
    if echo "$output" | grep -q "<main id=\"primary\""; then main="Yes"; else main="No"; fi
    
    echo "Route: $route"
    echo "  Status: $status"
    echo "  Final URL: $final"
    echo "  Main Content: $main"
    echo "  PHP Err: $php_err, Fatal: $php_fatal, WP Err: $wp_err"
    echo "---"
done

echo "=== 2. LINK & ASSET AUDIT ==="
for route in "/" "/programs/" "/services/" "/coaching/" "/guides/"; do
    html=$(curl -s -L "${BASE_URL}${route}")
    if echo "$html" | grep -q "127.0.0.1"; then
        echo "WARNING: 127.0.0.1 found on ${route}"
    fi
    if echo "$html" | grep -q "localhost:8080"; then
        echo "WARNING: localhost:8080 found on ${route}"
    fi
done
echo "DONE"
