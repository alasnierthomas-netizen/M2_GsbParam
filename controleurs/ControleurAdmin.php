<?php
include_once("modele/ModeleBack.php");
/**
 * Mission GsbParam PHP Objet
 * 
 * @file ControleurAdmin.php
 * @author Thomas Alasnier <alasnierthomas@gmail.com>
 * @version    3.0
 * @brief contient les fonctions pour gérer les action d'administration

 * regroupe les fonctions pour gérer la connexion des admin et leur action
*/
/**
 * @class ControleurGererPanier
 * @brief contient les fonctions pour gérer le panier
 */
class ControleurAdmin{
    
    private $modeleBack;
    public function __construct(){
        $this->modeleBack=new ModeleBack();
    }

    public function connexion(){
        if (empty($_SESSION["admin"])){
            include_once("vues/v_connexion.php");
        }
        else{
            $message = "vous êtes déjas connecter";
            include_once("vues/v_message.php");
        }
    }

    public function confirmConnexion(){
        if (!empty($_REQUEST["login"] && !empty($_REQUEST["mdp"])))
        {
            $reponse = $this->modeleBack->getAdmin($_REQUEST["login"],$_REQUEST["mdp"]);
            if ($reponse == false)
            {
                $msgErreurs = ["login ou mot de passe incorrect"];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_connexion.php");
            }
            else{
                $_SESSION["admin"] = $reponse;
                $message = "connexion réussie";
                header("Location: index.php?message=".$message);
            }
        }
        else{
            $msgErreurs = ["veiller rentrer un login et un mot de passe"];
            include_once("vues/v_erreurs.php");
            include_once("vues/v_connexion.php");
        }
    }

    public function deconnexion(): void{
        unset($_SESSION["admin"]);
        header("Location: index.php");
    }

    public function changeOrAddProduit($idProduit): void{
        if (!empty($_SESSION["admin"]))
        {
            $categories = $this->modeleBack->getLesCategories();
            if ($idProduit == null) // nouveaux produit
            {
                include_once("vues/v_modifProduit.php");
            }
            else // modifier produit
            {
                $produit = $this->modeleBack->getProduits($idProduit);
                $idProduit = $_REQUEST["produit"];
                $description = $produit["description"];
                $prix = $produit["prix"];
                $image = $produit["image"];
                $idCategorie = $produit["idCategorie"];
                include_once("vues/v_modifProduit.php");
            }
        }
        else{
            header("Location: index.php");
        }
    }

    public function confirmchangeOrAddProduit(): void{ #TODO compléter la fonction pour ajouter des images
        if (!empty($_SESSION["admin"]))
        {
            if (empty($_REQUEST["idProduit"])) // nouveaux produit
            {
                if (empty($_REQUEST["description"]) || empty($_REQUEST["prix"]) || empty($_REQUEST["idCategorie"]))
                {
                    $msgErreurs = ["veiller remplir tous les champs"];
                    include_once("vues/v_erreurs.php");
                    include_once("vues/v_modifProduit.php");
                    return;
                }
                $this->modeleBack->creerProduit($_REQUEST["description"], $_REQUEST["prix"], $_REQUEST["image"], $_REQUEST["idCategorie"]);
            }
            else // modifier produit
            {
                if (empty($_REQUEST["description"]) || empty($_REQUEST["prix"]) || empty($_REQUEST["idCategorie"]))
                {
                    $msgErreurs = ["veiller remplir tous les champs"];
                    include_once("vues/v_erreurs.php");
                    include_once("vues/v_modifProduit.php");
                    return;
                }
                $this->modeleBack->editProduit($_REQUEST["idProduit"], $_REQUEST["description"], $_REQUEST["prix"], $_REQUEST["image"], $_REQUEST["idCategorie"]);
            }
        }
        else {
            header("Location: index.php");
        }
    }
}
?>