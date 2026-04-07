<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-3">Modifier la catégorie</h2>

                    <form action="index.php?uc=admin&action=confirmModifierCategorie" method="post">
                        <input type="hidden" name="idCategorie" value="<?= htmlspecialchars($id) ?>">
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Nom de la catégorie</label>
                            <input id="libelle" name="libelle" type="text" class="form-control form-control-lg" value="<?= htmlspecialchars($libelle) ?>" required>
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php?uc=voirProduits&action=nosProduits" class="btn btn-outline-secondary btn-lg">Annuler</a> <?php #TODO :trouver un moyen de renvoiller a la page d'avent ?>
                            <a href="index.php?uc=admin&action=confirmSupprimerCategorie&idCategorie=<?= htmlspecialchars($id) ?>" class="btn btn-outline-danger btn-lg">Supprimer</a>
                            <button type="submit" class="btn btn-primary btn-lg">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
