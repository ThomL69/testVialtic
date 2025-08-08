<?php
    session_start();

    require_once('./conf/configuration.php');
    require_once('./connexion.php');

    $db = getDB();

    function readAllDriver($start, $limit) {
        global $db;

        // Lecture de tous les chauffeurs (avec une pagination)
        $sql = "SELECT * FROM transexpressbase LIMIT $start, $limit";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resultats = $stmt->fetchAll();
        
        return $resultats;
    }

    function pagination($limit) {
        global $db;
        $sql = "SELECT count(nom) as nom FROM transexpressbase";
        $total = $db->prepare($sql);
        $total->execute();

        $count = $total->fetchColumn();
        $total_pages = ceil($count / $limit);

        return $total_pages;
    }

    // Creation d'un nouveau chauffeur
    function createDriver() {
        global $db;

        if(isset($_POST['create'])) {
            $statut = -1;
            if($_POST['statut'] == 'on')
                $statut = 1;
            else
                $statut = 0;    
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
            
            if($stmt) {
                $_SESSION['message'] = "Inserted Successfuly";
            } else {
                $_SESSION['message'] = "Not Inserted";
            }
        }
    }

    function updateDriver() {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $tel = $_POST['telephone'];
        $typeP = $_POST['typePermis'];
        $matri = $_POST['matricule'];
        $statut = $_POST['statut'];
        $id= $_POST['id'];

            if($statut == 'on')
                $statut = 1;
            else
                $statut = 0; 

            editDriver($id, $nom, $prenom, $tel, $typeP, $matri, $statut);
    }

    // fait la mise a jour d'une ligne de donnees
    function editDriver($id, $nom, $prenom, $tel, $typeP, $matri, $statut) {
        global $db;
        
        try {
            $sql = "UPDATE transexpressbase SET nom=:nom, prenom=:prenom, telephone=:telephone, typePermis=:typePermis, matricule=:matricule, statut=:statut WHERE id=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':telephone', $tel);
            $stmt->bindParam(':typePermis', $typeP);
            $stmt->bindParam(':matricule', $matri);
            $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $stmt->execute();

            if($result) {
                $_SESSION['message'] = "Updated Successfuly";
                header("Location: ./index.php?page=".$_GET['page']."");
                exit(0);
            } else {
                $_SESSION['message'] = "Not Updated";
                header("Location: ./index.php?page=".$_GET['page']."");
                exit(0);
            }
        } catch (PDOException $e) { 
            echo $e->getMessage();
        }
    }

?>