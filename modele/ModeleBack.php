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

    public function getProduitsFaiblesStock()
    {
        $req = "SELECT id, nom, stock, idCategorie, prix FROM produit ORDER BY stock ASC LIMIT 10";
        $res = $this->executerRequete($req);
        $lesProduits = $res->fetchAll(PDO::FETCH_ASSOC);
        return $lesProduits;
    }

    #TODO: demander a join comment marche la surcharge en php (y en a probablement pas)
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

    public function contenir(string $idProduit): bool
    {
        $res = $this->executerRequete("SELECT COUNT(*) AS count FROM contenir WHERE idProduit = ?", [$idProduit]);
        $count = $res->fetch(PDO::FETCH_ASSOC)['count'];
        return $count > 0;
    }

    public function getAbreviationsCategorie(string $libelle)
    {
        $result = $libelle[0].$libelle[1];
        return strtoupper($result);
    }

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
    public function editCategorie(string $oldId, string $libelle): void
    {
        $this->executerRequete("UPDATE categorie SET libelle = ? WHERE id = ?", [$libelle, $oldId]);
    }

    public function supprimerCategorie(string $idCategorie): void{
        $this->executerRequete("DELETE FROM categorie WHERE id = ?", [$idCategorie]);
    }

    public function getCommandes(): array{
        $res = $this->executerRequete("SELECT c.id, c.dateCommande, cl.nomPrenom , cl.adresseRue
        FROM commande c 
        JOIN client cl ON c.idClient = cl.id 
        ORDER BY c.dateCommande DESC");
        $commandes = $res->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }

    public function getContenir(): array{
        $res = $this->executerRequete("SELECT contenir.idCommande, produit.nom, contenir.qt
                FROM contenir
                JOIN produit ON contenir.idProduit = produit.id
                ORDER BY idCommande, idProduit");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerCommande(string $idCommande): void{
        $this->executerRequete("DELETE FROM contenir WHERE idCommande = ?", [$idCommande]);
        $this->executerRequete("DELETE FROM commande WHERE id = ?", [$idCommande]);
    }

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