<?php
/**
 * Database Configuration
 * 
 * Establishes PDO connection to MySQL database.
 * Uses environment variables when available (production),
 * falls back to localhost defaults (development).
 */

$host     = getenv('DB_HOST')     ?: 'localhost';
$port     = getenv('DB_PORT')     ?: '3306';
$dbname   = getenv('DB_DATABASE') ?: 'ligue1';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];

try {
    $bdd = new PDO($dsn, $username, $password, $options);
    $pdo = $bdd;
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
