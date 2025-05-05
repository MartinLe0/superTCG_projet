<?php
session_start();
include('common/navbar.php');
include('./config.php'); 

if (!isset($_GET['id'])) {
    echo "ID de la carte manquant.";
    exit;
}

$carte_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM carte WHERE carte_id = :id");
$stmt->bindParam(':id', $carte_id);
$stmt->execute();
$carte = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$carte) {
    echo "Carte introuvable.";
    exit;
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['description']) && !empty($_POST['quantite'])) {
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $quantite = $_POST['quantite'];
        $rarete = $_POST['rarete'];
        $chemin_image = $carte['chemin_image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext, $allowed_ext)) {
                $image_new_name = uniqid('', true) . '.' . $image_ext;
                $image_upload_path = 'images/' . $image_new_name;

                if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                    $chemin_image = $image_upload_path;
                } else {
                    echo "Erreur : upload de l'image.";
                }
            } else {
                echo "Erreur : extension d'image non autorisée.";
            }
        }

        $stmt = $conn->prepare("UPDATE carte SET nom = :nom, description = :description, chemin_image = :chemin_image, quantite = :quantite, rarete = :rarete WHERE carte_id = :id");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':chemin_image', $chemin_image);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':rarete', $rarete);
        $stmt->bindParam(':id', $carte_id);

        if ($stmt->execute()) {
            echo "Carte modifiée avec succès.";
            header("Location: gerercarte.php");
            exit;
        } else {
            echo "Erreur lors de la modification.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une carte</title>
</head>
<body class="container">
    <div>
        <h1 class="titre">Modifier la carte</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nom">Nom de la carte:</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($carte['nom']) ?>" required><br>

            <label for="description">Description:</label>
            <textarea name="description" required><?= htmlspecialchars($carte['description']) ?></textarea><br>

            <label for="image">Image de la carte (laisser vide pour ne pas changer) :</label>
            <input type="file" name="image"><br>
            <img src="<?= $carte['chemin_image'] ?>" width="100" alt="Aperçu"><br>

            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" value="<?= $carte['quantite'] ?>" required><br>

            <label for="rarete">Rareté:</label>
            <select name="rarete" required>
                <?php
                $raretes = ['commun', 'rare', 'epique', 'legendaire'];
                foreach ($raretes as $r) {
                    $selected = ($carte['rarete'] === $r) ? 'selected' : '';
                    echo "<option value='$r' $selected>" . ucfirst($r) . "</option>";
                }
                ?>
            </select><br>

            <input type="submit" name="submit" value="Modifier la carte">
        </form>
    </div>
</body>
</html>
