<?php
session_start();
include('common/navbar.php'); 
include('./config.php'); 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Accès refusé. Vous devez être administrateur pour voir cette page.";
    exit;
}

$stmt = $conn->query("SELECT user_id, username, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="css/gererUS.css">
</head>
<body class="container">
    <div>
        <h1 class="titre">Liste des utilisateurs inscrits</h1>

        <?php if (empty($users)): ?>
            <p>Aucun utilisateur inscrit.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Rôle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>