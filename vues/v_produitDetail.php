<?php
$nomMarque = $marque['nom'] ?? '';
$libelleCategorie = $categorie['libelle'] ?? '';


$produitNom = htmlspecialchars($produit->nom ?? '');
$produitDesc = nl2br(htmlspecialchars($produit->description ?? ''));
$produitPrix = htmlspecialchars($produit->prix ?? '');
$produitImage = htmlspecialchars($produit->image ?? '');
$produitStock = htmlspecialchars($produit->stock ?? '');
$produitId = htmlspecialchars($produit->id ?? '');
$produitCategorie = htmlspecialchars($produit->idCategorie ?? '');
?>

<div class="container py-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <img src="<?= $produitImage ?>" class="card-img-top img-fluid" alt="Photo du produit <?= $produitNom ?>">
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h1 class="card-title fs-2 mb-3"><?= $produitNom ?></h1>
                    <p class="text-muted mb-2">Catégorie : <strong><?= htmlspecialchars($libelleCategorie) ?></strong></p>
                    <p class="text-muted mb-3">Marque : <strong><?= htmlspecialchars($nomMarque) ?></strong></p>
                    <p class="card-text mb-4"><?= $produitDesc ?></p>
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <div class="fs-3 text-primary fw-bold"><?= $produitPrix ?> €</div>
                        <div class="badge bg-secondary">Stock : <?= $produitStock ?></div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 d-flex flex-wrap gap-2">
                    <a href="index.php?uc=gererPanier&produit=<?= $produitId ?>&action=ajouterAuPanier" class="btn btn-primary">Ajouter au panier</a>
                </div>
            </div>
        </div>
    </div>
    <?php 
		include_once("v_produits.php");
	?>
</div>
