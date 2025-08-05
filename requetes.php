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

    function createDriver() {
        global $db;

        // Creation d'un nouveau chauffeur
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
    // function updateDriver($nom, $prenom, $tel, $typeP, $matri, $statut) {
    function updateDriver() {
        if(isset($_POST['update'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $tel = $_POST['telephone'];

            // $result = editDriver($nom, $prenom, $tel, $typeP, $matri, $statut);
            $result = editDriver($nom, $prenom, $tel);
            if($result) {
                $_SESSION['message'] = "Updated Successfuly";
                header("Location: ./");
            } else {
                $_SESSION['message'] = "Not Updated";
            }
        } else {
            echo 'error';
        }
    }

    // function editDriver($nom, $prenom, $tel, $typeP, $matri, $statut) {
    function editDriver($nom, $prenom, $tel) {
        global $db;
        try {
            // $sql = "UPDATE transexpressbase SET nom = :nom, prenom=:prenom, telephone=:telephone, typePermis=:typePermis, statut=:statut, matricule=:matricule WHERE matricule=:matricule";
            $sql = "UPDATE transexpressbase SET nom = :nom, prenom=:prenom, telephone=:telephone WHERE matricule=:matricule";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':telephone', $tel);
            $stmt->execute();
            return true;
        } catch (PDOException $e) { 
            echo $e->getMessage();
        }
    }


?>