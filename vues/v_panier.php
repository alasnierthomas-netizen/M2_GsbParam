<div class="alert alert-light" role="alert" id="panier">Votre panier :</div>
<a href="index.php?uc=gererPanier&action=supprimerPanier"><button type="button" class="btn btn-primary" onclick="return confirm('Voulez-vous vraiment vider le panier ?');">suprimer</button></a>

<div id="produits">
<?php
foreach( $lesProduitsDuPanier as $unProduit) 
{
	// récupération des données d'un produit
	$id = $unProduit->id;
	$description = $unProduit->description;
	$image = $unProduit->image;
	$prix = $unProduit->prix;
	// affichage
	?>
	<div id="card">
	<div>
	<div class="photoCard"><img src="<?= $image ?>" alt="image descriptive" /></div>
	<div class="descrCard"><?= $description ?></div>
	<div class="prixCard"><?= $prix."€" ?></div>
	</div>
	<div class="imgCard"><a href="index.php?uc=gererPanier&produit=<?= $id ?>&action=suprimerDuPanier" onclick="return confirm('Voulez-vous vraiment retirer cet article ?');">
	<img src="assets/images/retirerpanier.png" title="Retirer du panier" alt="retirer du panier"></a>
	<?php if (!empty($_SESSION["admin"])){ ?>
		<a href="index.php?uc=admin&produit=<?= $id ?>&action=changeOrAddProduit">
		<img src="assets/images/paramètres.png" title="modifier produit" alt="Modifier produit"></a>
	<?php } ?>
	</div>
	</div>
	<?php
}
?>
</div>
<div class="contenuCentre">
<a href="index.php?uc=gererPanier&action=passerCommande"><button type="button" class="btn btn-primary">Commander</button></a>
</div>
