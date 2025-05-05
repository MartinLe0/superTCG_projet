<?php
session_start();
include('common/navbar.php');
include('./config.php');

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    if (isset($_COOKIE['derniere_recompense'])) {
        $lastRewardTime = strtotime($_COOKIE['derniere_recompense']);
    } else {
        $lastRewardTime = 0;
    }

    $now = time();
    $canClaim = ($now - $lastRewardTime) >= 3600;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canClaim) {
        $cartes = $conn->query("SELECT carte_id FROM carte")->fetchAll(PDO::FETCH_COLUMN);
        shuffle($cartes);
        $cartesReçues = array_slice($cartes, 0, 2);

        foreach ($cartesReçues as $carte_id) {
            $check = $conn->prepare("SELECT quantite FROM collection WHERE user_id = :user_id AND carte_id = :carte_id");
            $check->execute(['user_id' => $user_id, 'carte_id' => $carte_id]);

            if ($check->rowCount() > 0) {
                $conn->prepare("UPDATE collection SET quantite = quantite + 1 WHERE user_id = :user_id AND carte_id = :carte_id")
                    ->execute(['user_id' => $user_id, 'carte_id' => $carte_id]);
            } else {
                $conn->prepare("INSERT INTO collection (user_id, carte_id, quantite) VALUES (:user_id, :carte_id, 1)")
                    ->execute(['user_id' => $user_id, 'carte_id' => $carte_id]);
            }
            $conn->prepare("UPDATE carte SET quantite = quantite - 1 WHERE carte_id = :carte_id")
            ->execute(['carte_id' => $carte_id]);
        }

        setcookie('derniere_recompense', date('Y-m-d H:i:s', $now), time() + 3600, "/");

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="css/indexs.css">
</head>
<body>

<div style="text-align: center; padding-top: 100px;">
    <h1>Obtiens 2 cartes toutes les heures</h1>

    <?php if (isset($_SESSION['id'])): ?>
        <form method="POST">
            <button type="submit" <?= isset($canClaim) && !$canClaim ? 'disabled' : '' ?>>
                <?= isset($canClaim) && !$canClaim ? 'Déjà utilisé - réessaie plus tard' : 'Tirer 2 cartes' ?>
            </button>
        </form>

        <?php if (isset($canClaim) && !$canClaim): ?>
            <p>Dernière tentative : <?= date('H:i:s', $lastRewardTime) ?> | Prochaine dans environ <?= 60 - round(($now - $lastRewardTime) / 60) ?> min</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Veuillez vous connecter pour obtenir des cartes.</p>
    <?php endif; ?>
</div>

</body>
</html>
