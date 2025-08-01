<?php
    require_once('./conf/configuration.php');
    require_once('./connexion.php');

    $db = getDB();


    // Lecture de tous les chauffeurs
    $sql = "SELECT * FROM transexpressbase";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll();

    // Creation d'un nouveau chauffeur
    if(isset($_POST['create'])) {   
        $statut = -1;
        if($_POST['statut'] == 'on')
            $statut = 1;
        else
            $statut = 0; 
        var_dump($statut);
        $sql = "INSERT INTO transexpressbase (nom, prenom, telephone, typePermis, matricule, statut) 
        VALUES (:nom, :prenom, :telephone, :typePermis, :matricule, :statut)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':prenom', $_POST['prenom']);
        $stmt->bindParam(':telephone', $_POST['telephone']);
        $stmt->bindParam(':typePermis', $_POST['typePermis']);
        $stmt->bindParam(':matricule', $_POST['matricule']);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ./");

    }

    // Lecture d'un chauffeur existant
    if(isset($_POST['selected'])) {
        $sql = "SELECT * FROM transexpressbase WHERE matricule=:matricule";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':matricule', $_POST['matricule']);
        $stmt->execute();
        $result = $stmt->fetch();
    }

    // Mettre à jour les informations d'un chauffeur existant
    if(isset($_POST['update'])) {
        $sql = "UPDATE transexpressbase SET nom = :nom, prenom=:prenom, telephone=:telephone, typePermis=:typePermis, statut=:statut WHERE matricule=:matricule";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':prenom', $_POST['prenom']);
        $stmt->bindParam(':telephone', $_POST['telephone']);
        $stmt->bindParam(':typePermis', $_POST['typePermis']);
        $stmt->bindParam(':matricule', $_POST['matricule']);
        $stmt->bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ./");
    }

    // Suppression d'un chauffeur existant
    if(isset($_POST['delete'])) {         
    $sql = "DELETE FROM transexpressbase WHERE matricule = :matricule";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':matricule', $_POST['matricule']);
    $stmt->execute();
    header("Location: ./");
  }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>TransExpress CRUD</title>
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/script.js"></script>
</head>
<body>
    <h1>Listing des chauffeurs</h1>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>matricule</th>
          <th>téléphone</th>
          <th>type de permis</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($resultats as $resultat): ?>
          <tr id="Elements">
            <td><?= $resultat['nom']; ?></td>
            <td><?= $resultat['prenom']; ?></td>
            <td><?= $resultat['matricule']; ?></td>
            <td><?= $resultat['telephone']; ?></td>
            <td><?= $resultat['typePermis']; ?></td>
            <td><?php echo ($resultat['statut'] == 0) ? "inactif" : "actif"; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="matricule" value="<?= $resultat['matricule'] ?? ''; ?>">
                <button type="submit" name="selected">Mettre à jour</button>
                <button type="submit" name="delete">Supprimer</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          
      </tbody>
    </table>

    <br><br>

    <h1>Ajout d'un chauffeur</h1>
    
    <form method="POST">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom"> <br>
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom"> <br>
        <label for="telephone">Téléphone</label>
        <input type="text" name="telephone" id="telephone"> <br>
        <label for="typePermis">Type de permis</label>
        <select name="typePermis" >
            <option value="">--Please choose an option--</option>
            <option value="B">Permis B</option>
            <option value="C">Permis C</option>
            <option value="CE">Permis CE</option>
        </select><br>
        <label for="matricule">Matricule</label>
        <input type="number" name="matricule" id="matricule"><br>
        <label for="statut">Statut Actif ?</label>
        <input type="checkbox" name="statut" id="statut"><br><br>

        <button type="submit" name="create">Créer</button>
    </form>

    <br><br>
    <h2>Mise à jour d'un chauffeur existant</h2>
    <form method="POST">
      <label>Nom:</label>
      <input type="text" name="title" value="<?php echo $result['nom'] ?? '' ; ?>"> <br>
      <label>Prenom:</label>
      <input type="text" name="description" value="<?php echo $result['prenom'] ?? '' ; ?>"> <br>
      <label>Téléphone:</label>
      <input type="text" name="telephone" value="<?php echo $result['telephone'] ?? '' ; ?>"> <br><br>
      <label for="typePermis">Type de permis</label>
      <select name="typePermis" >
            <option value=""><?php echo $result['typePermis'] ?? '' ; ?></option>
            <option value="B">Permis B</option>
            <option value="C">Permis C</option>
            <option value="CE">Permis CE</option>
        </select><br>
      <label for="statut">Statut Actif ?</label>
      <input type="checkbox" name="statut" id="statut"> <br><br>
      <button type="submit" name="update">Mettre à jour</button>
    </form>
</body>
</html>