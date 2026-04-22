<div id="produits">
<?php
// parcours du tableau contenant les produits à afficher
if (!empty($boutonsSupplémentaires))
{
	$lienSansId = $boutonsSupplémentaires['lien'];
}
foreach( $lesProduits as $unProduit) 
{ 	
	$article = $unProduit;
	$lienActionPrincipale = "index.php?uc=gererPanier&produit=".$article->id."&action=ajouterAuPanier";
	$imagePrincipale = "assets/images/mettrepanier.png";
	$titleAction = "Ajouter au panier";
	$altAction = "Mettre au panier";
	if (!empty($boutonsSupplémentaires))
	{
		$boutonsSupplémentaires['lien'] = $lienSansId."&idProduit=".$article->id;
	}
	include('v_article.php');
}
?>
</div>
