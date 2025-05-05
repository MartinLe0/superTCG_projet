<?php
include('./config.php');

if (isset($_GET['id'])) {
    $carte_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM carte WHERE carte_id = :id");
    $stmt->bindParam(':id', $carte_id);
    $stmt->execute();
}

header("Location: gerercarte.php");
exit;
?>