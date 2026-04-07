<?php
// Vue réservée aux administrateurs : affiche les produits avec le moins de stock
?>
<div class="container mt-4">
    <h2 class="mb-3">Produits à stock faible</h2>
    <?php if (empty($lesProduitsFaibleStock)) : ?>
        <div class="alert alert-info">Aucun produit trouvé.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Stock</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lesProduitsFaibleStock as $produit) : ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['id']) ?> <a href="index.php?uc=admin&produit=<?= $produit['id'] ?>&action=changeOrAddProduit">Modifier</a></td>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= htmlspecialchars($produit['idCategorie']) ?></td>
                        <td class="<?= ($produit['stock'] < 15) ? 'text-danger fw-bold' : '' ?>">
                            <?= intval($produit['stock']) ?>
                        </td>
                        <td><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
