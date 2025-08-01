
 <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>TransExpress CRUD</title>
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/script.js"></script>
</head>
<body>
	<p> Confirmation de suppression de l'enregistrement</p>
<?php
 
if(isset($_POST['']) && $_POST['']!='')
{
	echo '
	Voulez-vous vraiment supprimer cet enregistrement ?<br />
	<form method="post" action="suppr1.php">
	<input type="hidden" name="cdec"  value="'.$_POST[''].'">
	<input type="submit" name="Supprimer" value="Supprimer"> &nbsp;&nbsp;
	<a href="../interfaces/tab.php">Annuler</a>
	</form>
	';
}
?>

        
 
</body>
</html>