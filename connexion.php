<?php

function getDB() {
    try {
    $db = new PDO('mysql:host='.BD_HOST.'; dbname='.BD_DBNAME.'; charset=utf8', BD_USER, BD_PWD);
  } catch(Exception $e)
  {
    die('Erreur :'.$e->getMessage());
  }

  return $db;

}

?>