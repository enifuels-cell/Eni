<?php
// Reset password for a user
$db = new PDO('sqlite:database/database.sqlite');

// Default password: "password123"
$hashedPassword = password_hash('password123', PASSWORD_DEFAULT);

// Update admin user password
$stmt = $db->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->execute([$hashedPassword, 'admin@eni.com']);

echo "Password reset for admin@eni.com\n";
echo "New password: password123\n";

// Update test user password
$stmt->execute([$hashedPassword, 'test@example.com']);
echo "Password reset for test@example.com\n";
echo "New password: password123\n";

echo "\nYou can now log in with either account using 'password123'\n";
