<?php

try {
    $dbPath = __DIR__ . '/../database/database.sqlite';
    if (!file_exists($dbPath)) {
        echo "Database file not found: $dbPath\n";
        exit(1);
    }
    $db = new PDO('sqlite:' . $dbPath);
    $stm = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name IN ('notifications','user_notifications')");
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "-- Table: " . $r['name'] . "\n";
        echo $r['sql'] . "\n\n";
    }
} catch (Throwable $e) {
    echo 'ERR: ' . $e->getMessage() . "\n";
}
