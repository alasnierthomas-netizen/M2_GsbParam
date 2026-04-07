<div id="contenu">
    <h2>Inscription Nouveau Client</h2>
    <form method="POST" action="index.php?uc=client&action=confirmInscription">
        <div class="mb-3">
            <label for="login" class="form-label">Identifiant (Login)</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-primary" name="valider">Créer mon compte</button>
    </form>
</div>