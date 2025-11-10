<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('America/Mexico_City');

$DB_HOST = 'localhost';
$DB_PORT = '3306';
$DB_NAME = 'cotizador_msv';   // <-- sin .sql
$DB_USER = 'root';
$DB_PASS = '';                 // XAMPP: vacía

$dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
  http_response_code(500);
  die('Error de conexión a BD: ' . $e->getMessage());
}
