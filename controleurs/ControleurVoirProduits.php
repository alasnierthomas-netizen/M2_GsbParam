<?php
/**
 * @file ControleurVoirProduits.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details contient les fonctions pour voir les produits

 * regroupe les fonctions pour voir les produits
 */
/**
 * @class ControleurVoirProduits
 * @brief contient les fonctions pour gérer l'affichage des produits
 */
class ControleurVoirProduits{
    private $modeleFront;

    public function __construct()
    {
        $this->modeleFront=new ModeleFront();
    }
	/**
	 * Affiche les produits
	 *
	 * si $categ contient un idCategorie affiche les produits d'une catégorie
	 * @param $categ un identifiant de la catégorie de produits à afficher
	*/
    public function voirProduits($categ = null, $msgErreurs = null){
        if (empty($msgErreurs) && !empty($_REQUEST['msgErreurs'])) {
            $msgErreurs = $_REQUEST['msgErreurs'];
        }
        if($categ == null)
        {
            $lesProduits=$this->modeleFront->getLesProduits();
        }
        else{
		    $lesProduits=$this->modeleFront->getLesProduitsDeCategorie($categ);
            $lesCategories=$this->modeleFront->getLesCategories();
            $lesInfoCateg=$this->modeleFront->getLesInfosCategorie($categ);
            if($lesInfoCateg != false){
                $message = 'Produits de la catégorie '.$lesInfoCateg->libelle ;
                
            }
            else{
                $message = 'categorie inexistant';
            }
            include("vues/v_choixCategorie.php");
        }
        include("vues/v_produits.php");
    }

    /**
     * affiche une vue du detail d'un produit spécifique
     * @param mixed $idProduit
     * @return void
     */
    public function voirProduitDetail($idProduit){
        $produit = $this->modeleFront->getLesInfosProduit($idProduit);
        if($produit != false){
            $marque = $this->modeleFront->getMarque($produit->marque);
            $categorie = $this->modeleFront->getCategorie($produit->idCategorie);
            $lesProduits = $this->modeleFront->getProduitsAssocies($produit->id);
            include("vues/v_produitDetail.php");
        }
        else{
            $msgErreurs = "produit inexistant";
            $this->voirProduits(null, $msgErreurs);
        }
    }

	/**
	 * Affiche le menu à gauche contenant les catégories
	*/
    public function voirCategories(){
		$lesCategories=$this->modeleFront->getLesCategories();
        include("vues/v_choixCategorie.php");
	}
}

?>

