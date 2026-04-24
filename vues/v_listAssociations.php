

<div class="container mt-5">
    <h1 class="mb-4">Associer un produit</h1>

    <div class="row">
        <?php foreach ($lesProduits as $article): ?>
            <?php if ($article->id != $idProduit): ?>
                <div id="card" class="mb-3" style="min-width:230px;">
                    <div>
                        <a href="index.php?uc=voirProduits&action=voirProduitDetail&produit=<?= $article->id ?>">
                            <div class="photoCard"><img src="<?= $article->image ?>" alt="image descriptive" /></div>
                        </a>
                        <div class="descrCard"><?= $article->description ?></div>
                        <div class="prixCard"><?= $article->prix."€" ?></div>
                        <div class="qtCard">quantité: <?= $article->stock ?></div>
                    </div>
                    <div class="imgCard">
                        <?php if (!empty($_SESSION["admin"])): ?>
                            <a href="index.php?uc=admin&idProduit=<?= $article->id ?>&idAssocier=<?= $idProduit ?>&action=ajouterAssociation">
                                <img src="assets/images/lien.png" title="modifier produit" alt="Modifier produit">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
