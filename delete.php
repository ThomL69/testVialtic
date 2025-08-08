<?php

require_once('./conf/configuration.php');
require_once('./connexion.php');

$db = getDB();

 // Suppression d'un chauffeur existant
$id = $_GET['id']; 
$sql = "DELETE FROM transexpressbase WHERE id=:id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $_GET['id']);

$result = $stmt->execute();

if($result) {
	$_SESSION['message'] = "Success to delete the driver";
	header("Location: ./index.php");
	exit(0);
} else {
	$_SESSION['message'] = "Not Deleted";
	header("Location: ./index.php");
	exit(0);
}
?>