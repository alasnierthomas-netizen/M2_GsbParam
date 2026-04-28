<?php
/** 
 * Mission : architecture MVC GsbParam
 
 * @file ModeleBack.php
 * @author Thomas Alasnier <alasnierthomas@gmail.com>
 * @version    3.0
 * @details contient les fonctions d'accès BD pour le BackEnd
 */
require_once 'modele/Modele.php';
require_once 'ModeleFront.php';
/**
 * @class ModeleBack
 * @brief contient les fonctions d'accès aux infos de la BD pour les admin
 */
class ModeleBack extends ModeleFront{
    private $modeleFront;

    /**
     * Constructeur de la classe ModeleBack
     *
     * Initialise une instance de ModeleFront pour accéder aux méthodes du modèle front
    */
    public function __construct(){
        $this->modeleFront=new ModeleFront();
    }


    /**
	 * Retourne l’ID de l’administrateur dont le login et le mot de passe correspondent, sinon renvoie false.
	 *
	 * @param string $login le login de l'admin
     * @param string $password le mdp de l'admin
	 * @return int | false l'Id de l'admin ou false
	*/
    public function getAdmin(string $login, string $password)
    {
        $req = $this->executerRequete("SELECT id, mdp FROM administrateur WHERE nom = ?", [$login]);
        $rep = $req->fetch();
        if($rep != false){
            if(password_verify($password, $rep["mdp"])){
                $rep = $rep["id"];
            } else {
                $rep = false;
            }
        }
        return $rep;
    }

    /**
     * Retourne les produits avec un stock faible
     *
     * Retourne les 10 produits ayant le stock le plus faible, triés par ordre croissant
     *
     * @return array un tableau associatif contenant les produits et leurs informations
    */
    public function getProduitsFaiblesStock()
    {
        $req = "SELECT id, nom, stock, idCategorie, prix FROM produit ORDER BY stock ASC LIMIT 10";
        $res = $this->executerRequete($req);
        $lesProduits = $res->fetchAll(PDO::FETCH_ASSOC);
        return $lesProduits;
    }

    /**
     * Crée un nouveau produit dans la base de données
     *
     * @param string $description la description du produit
     * @param float $prix le prix du produit
     * @param string $image le nom du fichier image du produit
     * @param string $idCategorie l'id de la catégorie du produit
     * @param string $nom le nom du produit
     * @param int $marque l'id de la marque du produit
     * @param int $stock la quantité en stock du produit
     * @param int $contenance la contenance du produit
     * @param string $unite l'unité de contenance (ml, g, etc.)
     * @return mixed le résultat de la requête
    */
    public function creerProduit(string $description, float $prix, string $image, string $idCategorie, string $nom, int $marque, int $stock, int $contenance, string $unite) 
    {
        $res = $this->executerRequete("INSERT INTO produit(id, description, prix, image, idCategorie, nom, marque, stock, contenance, unite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
        [$this->modeleFront->creerIdProduit($idCategorie)
        , $description
        , $prix
        , "assets/images/".$image
        , $idCategorie
        , $nom
        , $marque
        , $stock
        , $contenance
        , $unite]);
        return $res->fetch();
  
    }

    /**
     * Modifie les informations d'un produit existant
     *
     * @param string $id l'id du produit à modifier
     * @param string $description la nouvelle description du produit
     * @param float $prix le nouveau prix du produit
     * @param string $image le nouveau nom du fichier image (optionnel)
     * @param string $idCategorie l'id de la catégorie du produit
     * @param string $nom le nouveau nom du produit
     * @param int $marque l'id de la marque du produit
     * @param int $stock la nouvelle quantité en stock
     * @param int $contenance la nouvelle contenance
     * @param string $unite la nouvelle unité de contenance
    */
    public function editProduit(string $id, string $description, float $prix, string $image, string $idCategorie, string $nom, int $marque, int $stock, int $contenance, string $unite) 
    {
        if (!empty($image)) {
            $res = $this->executerRequete(
                "UPDATE produit SET description = ?, prix = ?, image = ?, idCategorie = ?, nom = ?, marque = ?, stock = ?, contenance = ?, unite = ? WHERE id = ?",
                [$description, $prix, "assets/images/".$image, $idCategorie, $nom, $marque, $stock, $contenance, $unite, $id]
            );
        } else {
            $res = $this->executerRequete(
                "UPDATE produit SET description = ?, prix = ?, idCategorie = ?, nom = ?, marque = ?, stock = ?, contenance = ?, unite = ? WHERE id = ?",
                [$description, $prix, $idCategorie, $nom, $marque, $stock, $contenance, $unite, $id]
            );
        }
    }

    /**
     * Supprime un produit de la base de données
     *
     * Supprime d'abord les associations du produit, puis le produit lui-même.
     * Ne peut pas être supprimé s'il est présent dans une commande.
     *
     * @param string $idProduit l'id du produit à supprimer
     * @return array|true retourne un tableau d'erreurs si le produit est dans une commande, true sinon
    */
    public function supprimerProduit(string $idProduit){
        if($this->contenir($idProduit))
        {
            $msgErreurs[] = "Impossible de supprimer le produit $idProduit car il est présent dans au moins une commande.";
            include("vues/v_erreurs.php");
            return $msgErreurs;
        }
        else
        {        
            $this->executerRequete("DELETE FROM associe WHERE idProduit1 = ? OR idProduit2 = ?", [$idProduit, $idProduit]);
            $_SESSION['produits'] = array_diff_key($_SESSION['produits'], [$idProduit => ""]);
            $this->executerRequete("DELETE FROM produit WHERE id = ?", [$idProduit]);
            return true;
        }

    }

    /**
     * Vérifie si un produit est contenu dans au moins une commande
     *
     * @param string $idProduit l'id du produit à vérifier
     * @return bool true si le produit est dans une commande, false sinon
    */
    public function contenir(string $idProduit): bool
    {
        $res = $this->executerRequete("SELECT COUNT(*) AS count FROM contenir WHERE idProduit = ?", [$idProduit]);
        $count = $res->fetch(PDO::FETCH_ASSOC)['count'];
        return $count > 0;
    }

    /**
     * Génère une abréviation pour une catégorie
     *
     * Crée une abréviation à partir des deux premières lettres du libellé en majuscules
     *
     * @param string $libelle le libellé de la catégorie
     * @return string l'abréviation de la catégorie (2 premières lettres en majuscules)
    */
    public function getAbreviationsCategorie(string $libelle)
    {
        $result = $libelle[0].$libelle[1];
        return strtoupper($result);
    }

    /**
     * Ajoute une nouvelle catégorie dans la base de données
     *
     * @param string $id l'id de la catégorie à ajouter
     * @param string $libelle le libellé de la catégorie
     * @return mixed le résultat de la requête
    */
    public function ajouteCategorie($id, $libelle)
    {
        try 
		{
	    $req='INSERT INTO categorie (id, libelle) VALUES (?, ?)';
		$res = $this->executerRequete($req, [$id, $libelle]);
		$produit = $res->fetch();
		return $produit; 
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
    }
    /**
     * Modifie le libellé d'une catégorie existante
     *
     * @param string $oldId l'id de la catégorie à modifier
     * @param string $libelle le nouveau libellé de la catégorie
    */
    public function editCategorie(string $oldId, string $libelle): void
    {
        $this->executerRequete("UPDATE categorie SET libelle = ? WHERE id = ?", [$libelle, $oldId]);
    }

    /**
     * Supprime une catégorie de la base de données
     *
     * @param string $idCategorie l'id de la catégorie à supprimer
    */
    public function supprimerCategorie(string $idCategorie): void{
        $this->executerRequete("DELETE FROM categorie WHERE id = ?", [$idCategorie]);
    }

    /**
     * Retourne toutes les commandes avec les informations clients
     *
     * Retourne la liste de toutes les commandes triées par date décroissante,
     * avec les informations du client associé
     *
     * @return array un tableau associatif contenant les commandes et leurs clients
    */
    public function getCommandes(): array{
        $res = $this->executerRequete("SELECT c.id, c.dateCommande, cl.nomPrenom , cl.adresseRue
        FROM commande c 
        JOIN client cl ON c.idClient = cl.id 
        ORDER BY c.dateCommande DESC");
        $commandes = $res->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }

    /**
     * Retourne toutes les lignes de commande avec les noms de produits
     *
     * Retourne le contenu de toutes les commandes avec les noms des produits
     *
     * @return array un tableau associatif contenant les lignes de commande
    */
    public function getContenir(): array{
        $res = $this->executerRequete("SELECT contenir.idCommande, produit.nom, contenir.qt
                FROM contenir
                JOIN produit ON contenir.idProduit = produit.id
                ORDER BY idCommande, idProduit");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une commande et ses lignes associées
     *
     * Supprime d'abord toutes les lignes de commande dans la table contenir,
     * puis la commande elle-même
     *
     * @param string $idCommande l'id de la commande à supprimer
    */
    public function supprimerCommande(string $idCommande): void{
        $this->executerRequete("DELETE FROM contenir WHERE idCommande = ?", [$idCommande]);
        $this->executerRequete("DELETE FROM commande WHERE id = ?", [$idCommande]);
    }

    /**
     * Supprime l'association entre deux produits
     *
     * Supprime la relation bidirectionnelle entre les deux produits dans la table associe
     *
     * @param string $idProduit l'id du premier produit
     * @param string $idAssocier l'id du second produit
    */
    public function supprimerAssociation(string $idProduit, string $idAssocier): void
    {
        try 
		{
	    $req='DELETE FROM associe WHERE (associe.idProduit1 = :idProduit AND associe.idProduit2 = :idAssocier) OR (associe.idProduit1 = :idAssocier AND associe.idProduit2 = :idProduit)';
		$res = $this->executerRequete($req, ['idProduit' => $idProduit, 'idAssocier' => $idAssocier]);
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
    }

    /**
     * Ajoute une association entre deux produits
     *
     * Crée une relation bidirectionnelle entre les deux produits dans la table associe
     *
     * @param string $idProduit l'id du premier produit
     * @param string $idAssocier l'id du second produit à associer
    */
    public function ajouterAssociation(string $idProduit, string $idAssocier): void
    {
        try 
		{
	    $req='INSERT INTO associe (idProduit1, idProduit2) VALUES (?, ?)';
		$res = $this->executerRequete($req, [$idProduit, $idAssocier]);
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
    }
}