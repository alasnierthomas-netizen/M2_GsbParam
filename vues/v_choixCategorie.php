<div id="choixC" >
<p>Choisir une catégorie :</p>
<?php if (!empty($_SESSION["admin"]))
{
	?>
	<a class="btn btn-success" href="index.php?uc=admin&action=changeOrAddProduit">ajouter un produit</a>
	<a class="btn btn-success" href="index.php?uc=admin&action=ajouteCategorie">ajouter une catégorie</a>

<?php } ?>
	<?php include_once('v_message.php');?>
<?php
include ('v_categories.php');
?>
</div>