<?php
    require_once('./conf/configuration.php');
    require_once('./connexion.php');
    require_once('./requetes.php');

    $db = getDB()
;
    // Lecture de tous les chauffeurs (avec une pagination)
    $limit = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;
    $resultats = readAllDriver($start, $limit);

    //gestion de la pagination
    $total_pages = pagination($limit);
    $previous = $page - 1;
    if($previous == 0)
      $previous = $page;

    $next = $page + 1;
    
    if($page < $total_pages) {
      $next = $page + 1;
    } else {
      $next = $total_pages;
    }

    // Creation d'un nouveau chauffeur
    createDriver();


    // Lecture d'un chauffeur existant
    if(isset($_POST['selected'])) {
      $sql = "SELECT * FROM transexpressbase WHERE id=:id";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $_POST['id']);
      $stmt->execute();
      $result = $stmt->fetch();
    }



    // Mettre à jour les informations d'un chauffeur existant
    if(isset($_POST['update']))
      updateDriver();

    // Suppression d'un chauffeur existant
    if(isset($_POST['delete'])) {         
    $sql = "DELETE FROM transexpressbase WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->execute();
    header("Location: ./");
  }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>TransExpress CRUD</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/script.js"></script>
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-4">
        <?php if(isset($_SESSION['message'])) : ?>
          <h5 class="alert alert-success"><?= $_SESSION['message']; ?></h5>
          <?php 
            unset($_SESSION['message']);
            endif
            ?>
      </div>
    </div>
  </div>
    <h1 class="text-center">Listing des chauffeurs</h1>
    <br>
    <table align="center">
      <thead  scope="col-md-4">
        <tr >
          <th >Nom</th>
          <th >Prénom</th>
          <th >Matricule</th>
          <th >Téléphone</th>
          <th >Type de permis</th>
          <th >Statut</th>
          <th >Actions</th>
        </tr>
      </thead>
      <tbody class="align-middle">
        <?php foreach($resultats as $resultat): ?>
          <tr id="Elements">
            <td><?= $resultat['nom']; ?></td>
            <td><?= $resultat['prenom']; ?></td>
            <td><?= $resultat['matricule']; ?></td>
            <td><?= $resultat['telephone']; ?></td>
            <td><?= $resultat['typePermis']; ?></td>
            <td ><?php echo ($resultat['statut'] == 0) ? "inactif" : "actif"; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="id" value="<?= $resultat['id'] ?? ''; ?>">
                <button type="submit" name="selected">Mettre à jour</button>
                <button type="submit" name="delete">Supprimer</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          
      </tbody>
    </table>
    <br>
    <nav class="nav justify-content-center">
      <ul class="pagination">
        <li class="page-item ">
          <a class="page-link" href="index.php?page=<?= $previous?>" tabindex="-1">Précédent</a>
        </li>
    <?php
    for($i=1; $i <= $total_pages; $i++) : ?>
            <li  class="page-item"><a class="page-link" href="index.php?page=<?= $i?>"><?= $i ?></a></li>
            <!-- <li  class="page-item"><a class="page-link" href="#">2</a></li>
            <li  class="page-item"><a class="page-link" href="#">3</a></li> -->
    <?php endfor?>
        <li class="page-item active">
          <a class="page-link" href="index.php?page=<?= $next?>">Suivant</a>
        </li>
      </ul>

    </nav>

    <br><br>

    <!-- <h1 class="text-center">Ajout d'un chauffeur</h1>
    
    <form method="POST" class="text-center" >
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom"> 
        <br>
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
        <label for="statut" class="form-check-label">Statut Actif ?</label>
        <input type="checkbox" name="statut" id="statut" class="form-check-input"><br><br>

        <button type="submit" name="create">Créer</button>
    </form> -->

    <br><br>
    <!-- <h2 class="text-center">Mise à jour d'un chauffeur existant</h2>
    <form method="POST" class="text-center">
      <?php var_dump($result['id'] ?? ''); ?>
      <input type="hidden" name="id" value="<?php echo $result['id'] ?? ''; ?>" />
      <label>Nom:</label>
      <input type="text" name="nom" value="<?php echo $result['nom'] ?? ''; ?>"> <br>
      <label>Prenom:</label>
      <input type="text" name="prenom" value="<?php echo $result['prenom'] ?? ''; ?>"> <br>
      <label>Téléphone:</label>
      <input type="text" name="telephone" value="<?php echo $result['telephone'] ?? ''; ?>"> <br><br>
      <label for="matricule">Matricule:</label>
      <input type="text" name="matricule" value="<?php echo $result['matricule'] ?? ''; ?>"> <br><br>
      <label for="typePermis">Type de permis</label>
      <select name="typePermis" >
            <option value=""><?php echo $result['typePermis'] ?? ''; ?></option>
            <option value="B">Permis B</option>
            <option value="C">Permis C</option>
            <option value="CE">Permis CE</option>
        </select><br>
      <label for="statut">Statut Actif ?</label>
      <input type="checkbox" name="statut" id="statut" class="form-check-input"
      <?php 
        if(isset($result['statut'])) {
          {if($result['statut'] == 1) { echo "checked ='checked'"; } else { echo ''; }}   
        }
      ?>> <br>
      
      <button type="submit" name="update">Mettre à jour</button>
    </form> -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"></script>
</body>
</html>