<ul id="categories">
<?php
foreach( $lesCategories as $uneCategorie) 
{
	$idCategorie = $uneCategorie->id;
	$libCategorie = $uneCategorie->libelle;
	?>
	<li>
		<a class="text-decoration-none text-light" href="index.php?uc=voirProduits&action=voirProduits&categorie=<?= $idCategorie ?>">
		<?= $libCategorie ?></a>
		<?php if (!empty($_SESSION["admin"])): ?>
			<a href="index.php?uc=admin&action=modifierCategorie&categorie=<?= $idCategorie ?>">
				<img src="assets/images/paramètres.png" title="modifier catégorie" alt="Modifier catégorie">
			</a>
		<?php endif; ?>
	</li>
<?php
}
?>

</ul>

