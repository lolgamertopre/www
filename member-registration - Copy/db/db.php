<?php


$host = 'localhost';
$dbname = 'membership';
$username = 'root';
$password = 'root';
$port = '3306';
$charset = 'utf8mb4';

$options = [
    PDO ::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    
];

$dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=$charset";

try {
    $db = new PDO($dsn, $username, $password, $options);
    echo "Connected to the database server.";
} catch (PDOException $error) {
    echo "Error: " . $error -> getMessage();
}
