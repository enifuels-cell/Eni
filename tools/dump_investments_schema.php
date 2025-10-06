<?php
try {
    $dbPath = __DIR__ . '/../database/database.sqlite';
    $db = new PDO('sqlite:' . $dbPath);
    $stm = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name='investments'");
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "-- Table: " . $r['name'] . "\n";
        echo $r['sql'] . "\n";
    }
} catch (Throwable $e) {
    echo 'ERR: ' . $e->getMessage() . "\n";
}
