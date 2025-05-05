<?php

$user = 'root';
$password = "";
try {
    $conn = new PDO('mysql:host=localhost;dbname=supertcg', $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>