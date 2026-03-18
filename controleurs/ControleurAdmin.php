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
        $this->modeleBack= new ModeleBack();
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

    public function changeOrAddProduit($idProduit): void{ #TODO : créer un nombre limiter de posibiliter pour le champ "unité" dans la base de donner, et évoluer le formulaire en conséquence.
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
                $id = $produit["id"];
                $description = $produit["description"];
                $prix = $produit["prix"];
                $image = $produit["image"];
                $idCategorie = $produit["idCategorie"];
                $nom = $produit["nom"];
                $marque = $produit["marque"];
                $stock = $produit["stock"];
                $contenance = $produit["contenance"];
                $unite = $produit["unite"];
                include_once("vues/v_modifProduit.php");
            }
        }
        else{
            header("Location: index.php");
        }
    }

public function confirmchangeOrAddProduit(): void { 
    if (!empty($_SESSION["admin"])) {

        $imageName = null;

        // Gestion de l'upload
        if (!empty($_FILES["image"]["name"])) {
            $imageName = basename($_FILES["image"]["name"]);
            $cheminDestination = "assets/images/" . $imageName;

            move_uploaded_file($_FILES["image"]["tmp_name"], $cheminDestination);
        }

        # test si les champs obligatoires sont remplis
        if (empty($_REQUEST["description"]) || empty($_REQUEST["prix"]) || empty($_REQUEST["idCategorie"] || empty($_REQUEST["nom"]) || empty($_REQUEST["marque"]) || empty($_REQUEST["contenance"]) || empty($_REQUEST["unite"]))) {
            $msgErreurs = ["veiller remplir les champs obligatoires"];
            include_once("vues/v_erreurs.php");
            include_once("vues/v_modifProduit.php");
            return;
        }

        if (empty($_REQUEST["idProduit"])) { // nouveau produit

            if (empty($imageName)) {
                $msgErreurs = ["veiller ajouter une image pour le produit"];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_modifProduit.php");
                return;
            }

            $this->modeleBack->creerProduit(
                $_REQUEST["description"],
                $_REQUEST["prix"],
                $imageName,
                $_REQUEST["idCategorie"],
                $_REQUEST["nom"],
                $_REQUEST["marque"],
                (empty($_REQUEST["stock"])) ? 0 : $_REQUEST["stock"],
                $_REQUEST["contenance"],
                $_REQUEST["unite"]
            );

        } else { // modifier produit

            $this->modeleBack->editProduit(
                $_REQUEST["idProduit"],
                $_REQUEST["description"],
                $_REQUEST["prix"],
                $imageName,
                $_REQUEST["idCategorie"],
                $_REQUEST["nom"],
                $_REQUEST["marque"],
                (empty($_REQUEST["stock"]))? 0 : $_REQUEST["stock"],
                $_REQUEST["contenance"],
                $_REQUEST["unite"]
            );
        }
        header("Location: index.php?uc=voirProduits&action=nosProduits&categorie=".$_REQUEST["idCategorie"]);
    } else {
        header("Location: index.php");
    }
}
}