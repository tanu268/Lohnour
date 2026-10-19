<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lohnour_old;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "SUCCESS: Connected to local MySQL database 'lohnour_old'\n\n";

    echo "=== WP OPTIONS ===\n";
    $stmt = $pdo->query("SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl', 'home', 'permalink_structure', 'template', 'stylesheet')");
    while ($row = $stmt->fetch()) {
        echo "  " . $row['option_name'] . ": " . $row['option_value'] . "\n";
    }
    echo "\n";

    echo "=== ALL POSTS / PAGES / CPTS ===\n";
    $stmt = $pdo->query("
        SELECT ID, post_name, post_title, post_type, post_status, post_parent, LENGTH(post_content) as content_len
        FROM wp_posts 
        WHERE post_type IN ('page', 'post', 'lohnour_service', 'lohnour_workshop', 'user_guide')
        ORDER BY post_type, post_parent, ID
    ");
    printf("%-6s | %-18s | %-6s | %-25s | %-8s | %-6s | %s\n", "ID", "Type", "Parent", "Slug", "Status", "Len", "Title");
    echo str_repeat("-", 100) . "\n";
    while ($row = $stmt->fetch()) {
        printf("%-6d | %-18s | %-6d | %-25s | %-8s | %-6d | %s\n",
            $row['ID'], $row['post_type'], $row['post_parent'], $row['post_name'], $row['post_status'], $row['content_len'], $row['post_title']
        );
    }
    echo "\n";

    echo "=== PAGE TEMPLATES (_wp_page_template) ===\n";
    $stmt = $pdo->query("
        SELECT post_id, meta_key, meta_value 
        FROM wp_postmeta 
        WHERE meta_key = '_wp_page_template'
    ");
    while ($row = $stmt->fetch()) {
        echo "  Page ID " . $row['post_id'] . ": " . $row['meta_value'] . "\n";
    }
    echo "\n";

    echo "=== REWRITE RULES OPTION ===\n";
    $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name = 'rewrite_rules'");
    $rules = $stmt->fetchColumn();
    if ($rules) {
        $unserialized = @unserialize($rules);
        if ($unserialized && is_array($unserialized)) {
            echo "Total rewrite rules: " . count($unserialized) . "\n";
            echo "Sample matching service/coaching/about:\n";
            foreach ($unserialized as $match => $query) {
                if (preg_match('/service|coach|about|contact|guide/i', $match)) {
                    echo "  Pattern: " . str_pad($match, 35) . " => " . $query . "\n";
                }
            }
        }
    }

} catch (PDOException $e) {
    echo "PDO ERROR: " . $e->getMessage() . "\n";
}
