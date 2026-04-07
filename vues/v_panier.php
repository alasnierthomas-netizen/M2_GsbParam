<div class="alert alert-light" role="alert" id="panier">Votre panier :</div>
<a href="index.php?uc=gererPanier&action=supprimerPanier"><button type="button" class="btn btn-primary" onclick="return confirm('Voulez-vous vraiment vider le panier ?');">suprimer</button></a>

<div id="produits">
<form action="index.php?uc=gererPanier&action=passerCommande" method="post">
<?php
foreach( $lesProduitsDuPanier as $unProduit) 
{
	// récupération des données d'un produit
	$id = $unProduit->id;
	$description = $unProduit->description;
	$image = $unProduit->image;
	$prix = $unProduit->prix;
	$quantite = isset($_SESSION['produits'][$id]) ? $_SESSION['produits'][$id] : 1;
	// affichage
	?>
	<div id="card" class="mb-3" style="min-width:230px; min-height:300px;">
	<div>
	<div class="photoCard"><img src="<?= $image ?>" alt="image descriptive" /></div>
	<div class="descrCard"><?= $description ?></div>
	<div class="prixCard"><?= $prix."€" ?></div>
	<div class="d-flex align-items-center gap-2 mt-2">
		<label for="quantite_<?= $id ?>" class="col-form-label mb-0">quantité</label>
		<input name="quantite_<?= $id ?>" id="quantite_<?= $id ?>" class="form-control form-control-sm" style="width: 70px;" type="number" value='<?= $quantite ?>' min="1">
	</div>
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
<input type="submit" class="btn btn-primary" value="Commander">
</form>
</div>
