<?php
$passwords = ['', 'root', 'admin', 'password', '123456', '1234', 'mysql', 'lohnour', 'root123', 'admin123', 'xampp'];
$found = false;
foreach ($passwords as $pwd) {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;', 'root', $pwd, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "FOUND ROOT PASSWORD: '$pwd'\n";
        $found = true;
        
        // List databases
        $stmt = $pdo->query("SHOW DATABASES;");
        echo "Databases:\n";
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "  - " . $row[0] . "\n";
        }
        break;
    } catch (Exception $e) {
        // failed
    }
}
if (!$found) {
    echo "None of the common passwords matched.\n";
}
