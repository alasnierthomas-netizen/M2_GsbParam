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
    public function getAdmin(string $login, string $password) : int | false
    {
        $req = $this->executerRequete("SELECT id FROM administrateur WHERE nom = ? and mdp = ?", [$login, $password]);
        $rep = $req->fetch();
        if($rep != false){
            $rep = $rep["id"];
        }
        return $rep;
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

    public function editProduit(string $id, string $description, float $prix, string|null $image, string $idCategorie, string $nom, int $marque, int $stock, int $contenance, string $unite) 
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

    public function supprimerProduit(string $idProduit): void{
        $this->executerRequete("DELETE FROM produit WHERE id = ?", [$idProduit]);
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
    public function editCategorie(string $oldId, string $libelle, string $newId): void
    {
        $this->executerRequete("UPDATE categorie SET libelle = ?, id = ? WHERE id = ?", [$libelle, $newId, $oldId]);
    }

    public function supprimerCategorie(string $idCategorie): void{
        $this->executerRequete("DELETE FROM categorie WHERE id = ?", [$idCategorie]);
    }
}