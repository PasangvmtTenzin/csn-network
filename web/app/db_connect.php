<?php
$db_host = 'db.csn.local'; // Or use 10.10.10.7
$db_name = 'my_app_db';
$db_user = 'root'; // For simplicity, using root. For production, create a dedicated user.
$db_pass = 'root_password_secret'; // Same as MYSQL_ROOT_PASSWORD in docker-compose

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // For a real app, log this error and show a user-friendly message
    die("Database connection failed: " . $e->getMessage() . " (Host: $db_host)");
}
?>