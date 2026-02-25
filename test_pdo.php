<?php
$host = 'localhost';
$db   = 'abhishek';
$user = 'postgres';
$pass = '123456qw';
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->query("SELECT id FROM patient_registration WHERE name ILIKE '%Thomas%' AND name ILIKE '%Koshy%' LIMIT 1");
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient) {
        $patientId = $patient['id'];
        echo "Found patient ID: $patientId for Thomas Koshy\n";
    } else {
        echo "Patient Thomas Koshy not found.\n";
    }
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage() . "\n";
}
