<?php
// Simple database query to get users
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query('SELECT id, name, email, role FROM users LIMIT 5');

echo "Available Users:\n";
echo "================\n";

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . "\n";
    echo "Email: " . $row['email'] . "\n";
    echo "Name: " . $row['name'] . "\n";
    echo "Role: " . ($row['role'] ?? 'user') . "\n";
    echo "---\n";
}
