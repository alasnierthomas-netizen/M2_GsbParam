// test des méthodes de ModeleFront
<?php
require_once("modele/ModeleFront.php");
$modelFront = new ModeleFront();
var_dump($modelFront->getLesCategories());
var_dump($modelFront->getLesProduitsDeCategorie("CH"));
?>