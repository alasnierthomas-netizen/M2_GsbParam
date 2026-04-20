
<div class="container mt-5">
    <h2>Liste des Commandes</h2>
    
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Commande</th>
                <th>Date de Commande</th>
                <th>Nom et Prénom du Client</th>
                <th>Adresse</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($commandes as $commande) {
                echo '<tr class="table-info">';
                echo '<td>' . htmlspecialchars($commande['id']) . '</td>';
                echo '<td>' . htmlspecialchars($commande['dateCommande']) . '</td>';
                echo '<td>' . htmlspecialchars($commande['nomPrenom']) . '</td>';
                echo '<td>' . htmlspecialchars($commande['adresseRue']) . '</td>';
                echo '<td><a href="index.php?uc=admin&action=supprimerCommande&idCommande=' . $commande['id'] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette commande ?\');" class="btn btn-sm btn-danger">supprimer</a></td>';
                echo '</tr>';
                foreach ($contenirs as $contenir) {
                    if ($contenir['idCommande'] == $commande['id']) {
                        echo '<tr>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td><strong>Produit:</strong> ' . htmlspecialchars($contenir['nom']) . '</td>';
                        echo '<td><strong>Quantité:</strong> ' . htmlspecialchars($contenir['qt']) . '</td>';
                        echo '</tr>';
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>