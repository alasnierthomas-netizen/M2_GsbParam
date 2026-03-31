<?php
/** 
 * Mission : architecture MVC GsbParam
 
 * @file ModeleFront.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details contient les fonctions d'accès BD pour le FrontEnd
 */
require_once 'modele/Modele.php';
/**
 * @class ModeleFront
 * @brief contient les fonctions d'accès aux infos de la BD pour les utilisateurs
 */
class ModeleFront extends Modele{


	/**
	 * Retourne les info de l'unité en fonction de sont id
	 *
	 * @return array les info de l'unité
	*/
	public function getUnite($idUnite)
	{
		try 
		{
	    $req='select unite from unite where id = ? ';
		$res = $this->executerRequete($req, [$idUnite]);
		$unite = $res->fetch();
		return $unite; 
		} 
		catch (PDOException $e) 
		{
		print "Erreur !: " . $e->getMessage();
		die();
		}
	}

	public function getMarques()
	{
		try 
		{
	    $req='select id, nom from marque';
		$res = $this->executerRequete($req);
		$marques = $res->fetchAll(PDO::FETCH_OBJ);
		return $marques; 
		} 
		catch (PDOException $e) 
		{
		print "Erreur !: " . $e->getMessage();
		die();
		}
	}
	

	/**
	 * Retourne toutes les unités 
	 *
	 * @return array $lesUnites le tableau des unités (tableau d'objets)
	*/
	public function getLesUnites()
	{
		try 
		{
	    $req='select id, libelle from unite';
		$res = $this->executerRequete($req);
		$lesUnites = $res->fetchAll(PDO::FETCH_OBJ);
		return $lesUnites; 
		} 
		catch (PDOException $e) 
		{
		print "Erreur !: " . $e->getMessage();
		die();
		}
	}

	/**
	 * Retourne toutes les catégories 
	 *
	 * @return array $lesLignes le tableau des catégories (tableau d'objets)
	*/
	public function getLesCategories()
	{
		try 
		{
		$req = 'select id, libelle from categorie';
		$res = $this->executerRequete($req);
		$lesLignes = $res->fetchAll(PDO::FETCH_OBJ);
		return $lesLignes;
		} 
		catch (PDOException $e) 
		{
			print "Erreur !: " . $e->getMessage();
			die();
		}
	}

	public function getCategorie($id)
	{
		try 
		{
	    $req='select id, libelle FROM categorie where id = ? ';
		$res = $this->executerRequete($req, [$id]);
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
	 * Retourne un id qui n'a pas encore été utilisé pour un produit de la catégorie passée en paramètre
	 *
	 * @param string $idCategorie l'id de la catégorie
	 * @return string un id de produit unique pour la catégorie passée en paramètre
	*/
	public function creerIdProduit($idCategorie)
	{
		try 
		{
			$num = -1;
			do{
				$num++;
				$res = $this->executerRequete("SELECT * FROM produit WHERE id = ?", [$idCategorie[0].$num]);
			}while($res->fetch() != false);
			return $idCategorie[0].$num;
		}
		catch (PDOException $e) 
		{
			print "Erreur !: " . $e->getMessage();
			die();
		}
	}

	/**
	 * Retourne toutes les informations d'une catégorie passée en paramètre
	 *
	 * @param string $idCategorie l'id de la catégorie
	 * @return object $laLigne la catégorie (objet)
	*/
	public function getLesInfosCategorie($idCategorie)
	{
		try 
		{
        $req = 'SELECT id, libelle FROM categorie WHERE id=? ';
		$res = $this->executerRequete($req, [$idCategorie]);
		$laLigne = $res->fetch(PDO::FETCH_OBJ);
		return $laLigne;
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
	}
/**
 * Retourne sous forme d'un tableau tous les produits de la
 * catégorie passée en argument
 * 
 * @param string $idCategorie  l'id de la catégorie dont on veut les produits
 * @return array $lesLignes un tableau des produits de la categ passée en paramètre (tableau d'objets)
*/

	public function getLesProduitsDeCategorie($idCategorie)
	{
		try 
		{
	    $req='select id, description, prix, image, idCategorie from produit where idCategorie =? ';
		$res = $this->executerRequete($req, [$idCategorie]);
		$lesLignes = $res->fetchAll(PDO::FETCH_OBJ);
		return $lesLignes; 
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
	}

	public function getQtProduitsDeCategorie($idCategorie): int
	{
		try
		{
			$res = $this->executerRequete("SELECT COUNT(*) FROM produit WHERE produit.idCategorie = ?", [$idCategorie]);
			$qtProduits = $res->fetch();
			return $qtProduits[0];
		}
		catch(PDOException $e)
		{
			print "Erreur !: " . $e->getMessage();
        	die();
		}
	}


	/**
 * Retourne les info d'un produit en fonction de sont id
 * catégorie passée en argument
 * 
 * @param string $id  l'id du produits
 * @return $produit information du produit
*/

	public function getProduits($id)
	{
		try 
		{
	    $req='select id, description, prix, image, idCategorie, nom, marque, stock, contenance, unite from produit where id = ? ';
		$res = $this->executerRequete($req, [$id]);
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
 * Retourne les produits concernés par le tableau des idProduits passé en argument (si null retourne tous les produits)
 *
 * @param array $desIdsProduit tableau d'idProduits
 * @return array $lesProduits un tableau contenant les infos des produits dont les id ont été passé en paramètre
*/
	public function getLesProduitsDuTableau($desIdsProduit=null)
	{
		try 
		{
		$lesProduits=array();
		if($desIdsProduit != null)
		{
			foreach($desIdsProduit as $unIdProduit)
			{
				$req = 'select id, description, prix, image, idCategorie from produit where id = ? ';
				$res = $this->executerRequete($req, [$unIdProduit]);
				$unProduit = $res->fetch(PDO::FETCH_OBJ);
				$lesProduits[] = $unProduit;
			}
		}
		else // on souhaite tous les produits
		{
			$req = 'select id, description, prix, image, idCategorie from produit;';
			$res = $this->executerRequete($req);
			$lesProduits = $res->fetchAll(PDO::FETCH_OBJ);
		}
		return $lesProduits;
		}
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
	}

/**
 * Retourne sous forme d'un tableau tous les produits
 * 
 * @return array $lesLignes un tableau des produits
*/

	public function getLesProduits()
	{
		try 
		{
	    $req='select id, description, prix, image from produit';
		$res = $this->executerRequete($req);
		$lesLignes = $res->fetchAll(PDO::FETCH_OBJ);
		return $lesLignes; 
		} 
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
	}

	/**
	 * Crée une commande 
	 *
	 * Crée une commande à partir des arguments validés passés en paramètre, l'identifiant est
	 * construit à partir du maximum existant ; crée les lignes de commandes dans la table contenir à partir du
	 * tableau d'idProduit passé en paramètre
	 * @param string $nom nom du client
	 * @param string $rue rue du client
	 * @param string $cp cp du client
	 * @param string $ville ville du client
	 * @param string $mail mail du client
	 * @param array $lesIdProduit tableau contenant les id des produits commandés	 
	*/
	public function creerCommande($nom,$rue,$cp,$ville,$mail, $lesIdProduit )
	{
		try 
		{
        // on récupère le dernier id de commande
		$req = 'select max(id) as maxi from commande';
		$res = $this->executerRequete($req);
		$laLigne = $res->fetch();
		$maxi = $laLigne['maxi'] ;// on place le dernier id de commande dans $maxi
		$idCommande = $maxi+1; // on augmente le dernier id de commande de 1 pour avoir le nouvel idCommande
		$date = date('Y/m/d'); // récupération de la date système
		$req = "insert into commande values (?, ?, ?, ?, ?, ?, ?)";
		$res = $this->executerRequete($req, [$idCommande, $date, $nom, $rue, $cp, $ville, $mail]);
		// insertion produits commandés
		foreach($lesIdProduit as $unIdProduit)
		{
			$req = "insert into contenir values (?, ?)";
			$res = $this->executerRequete($req, [$idCommande, $unIdProduit]);
		}
		return $res;
		}
		catch (PDOException $e) 
		{
        print "Erreur !: " . $e->getMessage();
        die();
		}
	}

}
?>