<div class="contenuCentre">
    <div class="card p-4 shadow-sm mx-auto" style="max-width:860px;">
        <h2 class="text-center mb-1">Éditer un produit</h2>
        <p class="text-center text-muted mb-4">
            Produit N°<?php echo (!empty($id)) ? htmlspecialchars($id) : "-"; ?>
        </p>

        <form action="index.php?uc=admin&action=confirmchangeOrAddProduit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idProduit" value="<?php echo (!empty($idProduit)) ? $idProduit : "" ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du produit</label>
                        <input id="nom" name="nom" required class="form-control" type="text" value="<?php echo (!empty($nom)) ? htmlspecialchars($nom) : "" ?>">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description du produit</label>
                        <textarea id="description" name="description" required class="form-control" rows="4"><?php echo (!empty($description)) ? htmlspecialchars($description) : "" ?></textarea>
                    </div>

                    <div class="mb-3"> 
                        <label for="marque" class="form-label">Marque du produit</label>
                        <select id="marque" name="marque" class="form-select">
                            <?php foreach ($marques as $marque) { ?>
                                <option value="<?php echo $marque->id ?>" <?php echo ($marque->id == $idMarque) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($marque->nom) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="categorie" class="form-label">Catégorie du produit</label>
                        <select id="categorie" name="idCategorie" class="form-select">
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category->id ?>" <?php echo ($category->id == $idCategorie) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($category->libelle) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label for="prix" class="form-label">Prix du produit</label>
                            <div class="input-group">
                                <input id="prix" name="prix" required class="form-control" type="number" step="0.01" value="<?php echo (!empty($prix)) ? htmlspecialchars($prix) : "" ?>">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="stock" class="form-label">Stock (facultatif)</label>
                            <input id="stock" name="stock" class="form-control" type="number" min="0" value="<?php echo (!empty($stock)) ? intval($stock) : 0 ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="unite" class="form-label">Unité</label>
                        <select id="unite" name="unite" class="form-select">
                            <?php foreach ($unites as $unite) { ?>
                                <option value="<?php echo $unite->id ?>" <?php echo ($unite->id == $idUnite) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($unite->libelle) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="contenance" class="form-label">Contenance</label>
                        <input id="contenance" name="contenance" required class="form-control" type="number" min="0" value="<?php echo (!empty($contenance)) ? htmlspecialchars($contenance) : 0 ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Nouvelle image <?php echo (!empty($image)) ? "(facultatif)" : "(requis)"; ?></label>
                <input id="image" name="image" class="form-control" <?php echo (!empty($image)) ? "" : "required"; ?> type="file">
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-success me-2" type="submit" name="valider">Modifier le produit</button>
                <a class="btn btn-secondary" href="index.php?uc=accueil">Retour</a> <?php #TODO :trouver un moyen de renvoiller a la page d'avent ?>
            </div>
        </form>
    </div>
</div>


