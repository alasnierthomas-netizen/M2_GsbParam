<?php
/**
 * Vue générique pour afficher un article/produit
 * 
 * Variables attendues:
 * $article: l'objet produit avec propriété (id, description, image, prix)
 * $lienActionPrincipale: lien complet de l'action principale
 * $imagePrincipale: chemin de l'image de l'action principale
 * $titleAction: titre de l'action principale
 * $altAction: texte alternatif de l'image de l'action principale
 */
?>
<div id="card" class="mb-3" style="min-width:230px;">
	<div>
		<div class="photoCard"><img src="<?= $article->image ?>" alt="image descriptive" /></div>
		<div class="descrCard"><?= $article->description ?></div>
		<div class="prixCard"><?= $article->prix."€" ?></div>
	</div>
	<div class="imgCard">
		<a href="<?= $lienActionPrincipale ?>" <?php if(isset($confirmMessage)) echo "onclick=\"return confirm('{$confirmMessage}');\""; ?>>
			<img src="<?= $imagePrincipale ?>" title="<?= $titleAction ?>" alt="<?= $altAction ?>">
		</a>
		<?php if (!empty($_SESSION["admin"])): ?>
			<a href="index.php?uc=admin&produit=<?= $article->id ?>&action=changeOrAddProduit">
				<img src="assets/images/paramètres.png" title="modifier produit" alt="Modifier produit">
			</a>
		<?php endif; ?>
	</div>
</div>
