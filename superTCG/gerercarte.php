<?php
session_start();
include('common/navbar.php');
include('./config.php'); 

if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['description']) && !empty($_POST['quantite']) && isset($_FILES['image'])) {

        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $quantite = $_POST['quantite'];
        $rarete = $_POST['rarete'];

        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_error = $_FILES['image']['error'];

        if ($image_error === 0) {
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_ext = strtolower($image_ext);

            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($image_ext, $allowed_ext)) {
                $image_new_name = uniqid('', true) . '.' . $image_ext;
                $image_upload_path = 'images/' . $image_new_name;

                if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                    $chemin_image = $image_upload_path; 

                    $stmt = $conn->prepare("INSERT INTO carte (nom, description, chemin_image, quantite, rarete) 
                                            VALUES (:nom, :description, :chemin_image, :quantite, :rarete)");
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':chemin_image', $chemin_image);
                    $stmt->bindParam(':quantite', $quantite);
                    $stmt->bindParam(':rarete', $rarete);

                    if ($stmt->execute()) {
                        echo "Carte : ajoutée avec succès!";
                    } else {
                        echo "Erreur : ajout de la carte.";
                    }
                } else {
                    echo "Erreur : upload de l'image.";
                }
            } else {
                echo "Erreur : extension de l'image";
            }
        } else {
            echo "Erreur : upload de l'image";
        }
    } else {
        echo "Veuillez remplir tous les champs et sélectionner une image.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/gererCS.css">
    <title>Ajouter une nouvelle carte</title>
</head>
<body class="container">
    <div>
        <h1 class="titre">Ajouter une nouvelle carte</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nom">Nom de la carte:</label>
            <input type="text" name="nom" placeholder="Nom de la carte" required><br>

            <label for="description">Description:</label>
            <textarea name="description" placeholder="Description de la carte" required></textarea><br>

            <label for="image">Image de la carte:</label>
            <input type="file" name="image" required><br>

            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" value="0" required><br>

            <label for="rarete">Rareté:</label>
            <select name="rarete" required>
                <option value="commun">Commun</option>
                <option value="rare">Rare</option>
                <option value="epique">Epique</option>
                <option value="legendaire">Légendaire</option>
            </select><br>

            <input type="submit" name="submit" value="Ajouter la carte">
        </form>
    </div>

    <div>
        <?php
            $stmt = $conn->query("SELECT * FROM carte");
            $cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h1>Liste des cartes</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Image</th>
                <th>Quantité</th>
                <th>Rareté</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartes as $carte): ?>
                <tr>
                    <td><?= $carte['carte_id'] ?></td>
                    <td><?= htmlspecialchars($carte['nom']) ?></td>
                    <td><?= htmlspecialchars($carte['description']) ?></td>
                    <td><img src="<?= $carte['chemin_image'] ?>" alt="" width="50"></td>
                    <td><?= $carte['quantite'] ?></td>
                    <td><?= $carte['rarete'] ?></td>
                    <td>
                        <a href="modifiercarte.php?id=<?= $carte['carte_id'] ?>">Modifier</a> |
                        <a href="supprimercarte.php?id=<?= $carte['carte_id'] ?>" onclick="return confirm('Supprimer cette carte ?');">Supprimer</a> |
                        <a href="possedeurcarte.php?id=<?= $carte['carte_id'] ?>" >Voir les possedeur de cette carte</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    
</body>
</html>
