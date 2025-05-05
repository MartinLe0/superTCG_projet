<?php
session_start();
include('./config.php');
include('common/navbar.php');

$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM carte WHERE nom LIKE :search");
    $like = "%$search%";
    $stmt->bindValue(':search', $like, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->query("SELECT * FROM carte");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher une carte</title>
    <link rel="stylesheet" href="css/rechercheS.css">
</head>
<body class="container">
    <h1>Recherche de cartes</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Rechercher une carte..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (count($result) > 0): ?>
        <?php foreach ($result as $carte): ?>
            <div class="carte">
            <?php if (!empty($carte['chemin_image'])): ?>
                <img src="<?= htmlspecialchars($carte['chemin_image']) ?>" alt="Image de la carte">
            <?php else: ?>
                <img src="images/default.jpg" alt="Image par défaut">
            <?php endif; ?>
                <div class="carte-details">
                    <h2><?= htmlspecialchars($carte['nom']) ?></h2>
                    <p><strong>Description :</strong> <?= htmlspecialchars($carte['description'] ?? 'Aucune') ?></p>
                    <p><strong>Rareté :</strong> <?= htmlspecialchars($carte['rarete'] ?? 'Non spécifiée') ?></p>
                    <p><strong>Nombre disponible :</strong> <?= htmlspecialchars($carte['quantite'] ?? '0') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune carte trouvée.</p>
    <?php endif; ?>
</body>
</html>

