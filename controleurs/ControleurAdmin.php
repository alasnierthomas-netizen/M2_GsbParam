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

    public function produitsFaibleStock(): void
    {
        if (!empty($_SESSION["admin"])) {
            $lesProduitsFaibleStock = $this->modeleBack->getProduitsFaiblesStock();
            include_once("vues/v_produitsFaibleStock.php");
        } else {
            header("Location: index.php");
        }
    }

    public function changeOrAddProduit($idProduit): void{ 
        if (!empty($_SESSION["admin"]))
        {
            $categories = $this->modeleBack->getLesCategories();
            if ($idProduit == null) // nouveaux produit
            {
                $unites = $this->modeleBack->getLesUnites();
                $idUnite = 2;
                $idCategorie = 1;
                $marques = $this->modeleBack->getMarques();
                $idMarque = 1;
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
                $marques = $this->modeleBack->getMarques();
                $idMarque = $produit["marque"];
                $stock = $produit["stock"];
                $contenance = $produit["contenance"];
                $idUnite = $produit["unite"];
                $unites = $this->modeleBack->getLesUnites();
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
            if (empty($_REQUEST["description"]) || empty($_REQUEST["prix"]) || empty($_REQUEST["idCategorie"]) || empty($_REQUEST["nom"]) || empty($_REQUEST["marque"]) || empty($_REQUEST["contenance"]) || empty($_REQUEST["unite"])) {
                $msgErreurs = ["veiller remplir les champs obligatoires"];
                include_once("vues/v_erreurs.php");
                #si le formulaire n'est pas complais on renvois l'utilisateur sur le formulaire de modification avec les champs préremplis
                $categories = $this->modeleBack->getLesCategories();
                $prix = (!empty($_REQUEST["prix"])) ? $_REQUEST["prix"] : "";
                $nom = (!empty($_REQUEST["nom"])) ? $_REQUEST["nom"] : "";
                $contenance = (!empty($_REQUEST["contenance"])) ? $_REQUEST["contenance"] : "";
                $description = (!empty($_REQUEST["description"])) ? $_REQUEST["description"] : "";
                $unites = $this->modeleBack->getLesUnites();
                $idUnite = (!empty($_REQUEST["unite"])) ? $_REQUEST["unite"] : 2;
                $idCategorie = (!empty($_REQUEST["idCategorie"])) ? $_REQUEST["idCategorie"] : 1;
                $marques = $this->modeleBack->getMarques();
                $idMarque = (!empty($_REQUEST["marque"])) ? $_REQUEST["marque"] : 1;
                include_once("vues/v_modifProduit.php");
                return;
            }

            if (empty($_REQUEST["idProduit"])) { // nouveau produit

                if (empty($imageName)) { #test si il y a une image pour le produit
                    $msgErreurs = ["veiller ajouter une image pour le produit"];
                    include_once("vues/v_erreurs.php");
                    #si le formulaire n'est pas complais on renvois l'utilisateur sur le formulaire de modification avec les champs préremplis
                    $categories = $this->modeleBack->getLesCategories();
                    $prix = (!empty($_REQUEST["prix"])) ? $_REQUEST["prix"] : "";
                    $nom = (!empty($_REQUEST["nom"])) ? $_REQUEST["nom"] : "";
                    $contenance = (!empty($_REQUEST["contenance"])) ? $_REQUEST["contenance"] : "";
                    $description = (!empty($_REQUEST["description"])) ? $_REQUEST["description"] : "";
                    $unites = $this->modeleBack->getLesUnites();
                    $idUnite = (!empty($_REQUEST["unite"])) ? $_REQUEST["unite"] : 2;
                    $idCategorie = (!empty($_REQUEST["idCategorie"])) ? $_REQUEST["idCategorie"] : 1;
                    $marques = $this->modeleBack->getMarques();
                    $idMarque = (!empty($_REQUEST["marque"])) ? $_REQUEST["marque"] : 1;
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
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$_REQUEST["idCategorie"]);
        } else {
            header("Location: index.php");
        }
    }
    public function supprimerProduit(string $idProduit, string $idCategorie): void{
        if (!empty($_SESSION["admin"])) {
            $this->modeleBack->supprimerProduit($idProduit);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$idCategorie);
        } else {
            header("Location: index.php");
        }
    }

    public function ajouteCategorie(): void{
        if (!empty($_SESSION["admin"])) {
            include_once("vues/v_ajouteCategorie.php");
        } else {
            header("Location: index.php");
        }
    }

    public function modifierCategorie(string $idCategorie): void{
        if (!empty($_SESSION["admin"])) {
            $categorie = $this->modeleBack->getCategorie($idCategorie);
            if ($categorie == false) {
                $message = "Catégorie introuvable";
                include_once("vues/v_message.php");
            } else {
                $id = $categorie["id"];
                $libelle = $categorie["libelle"];
                include_once("vues/v_editeCategorie.php");
            }
        } else {
            header("Location: index.php");
        }
    }

    public function confirmModifierCategorie(): void{
        if (!empty($_SESSION["admin"])) {
            if (empty($_REQUEST["idCategorie"]) || empty($_REQUEST["libelle"])) {
                $msgErreurs = ["veiller remplir le champ libelle, ou la catégorie n'existe pas"];
                include_once("vues/v_erreurs.php");
                $id = (!empty($_REQUEST["idCategorie"])) ? $_REQUEST["idCategorie"] : "";
                $libelle = (!empty($_REQUEST["libelle"])) ? $_REQUEST["libelle"] : "";
                include_once("vues/v_editeCategorie.php");
                return;
            }
            $id = $this->modeleBack->getAbreviationsCategorie($_REQUEST["libelle"]);
            if ($id != $_REQUEST["idCategorie"] && $this->modeleBack->getCategorie($id) != false)
            {
                $msgErreurs = ["Les deux premières lettres de la catégorie sont également celles d’une autre catégorie, merci de les changer."];
                include_once("vues/v_erreurs.php");
                $libelle = (!empty($_REQUEST["libelle"])) ? $_REQUEST["libelle"] : "";
                $id = (!empty($_REQUEST["idCategorie"])) ? $_REQUEST["idCategorie"] : "";
                include_once("vues/v_editeCategorie.php");
                return;
            }
            $this->modeleBack->editCategorie($_REQUEST["idCategorie"], $_REQUEST["libelle"], $id);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$_REQUEST["idCategorie"]);
        } else {
            header("Location: index.php");
        }
    }

    public function ajouteOuEditeCategorie(): void{
        $this->ajouteCategorie();
    }

    public function confirmAjouteCategorie(): void{
        if (!empty($_SESSION["admin"])) {
            #test si les champs obligatoires sont remplis
            if (empty($_REQUEST["libelle"])) {
                $msgErreurs = ["veiller remplir le champ libelle"];
                include_once("vues/v_erreurs.php");
                $libelle = (!empty($_REQUEST["libelle"])) ? $_REQUEST["libelle"] : "";
                include_once("vues/v_ajouteCategorie.php");
                return;
            }
            $id = $this->modeleBack->getAbreviationsCategorie($_REQUEST["libelle"]);
            if ($this->modeleBack->getCategorie($id) != false)
            {
                $msgErreurs = ["Les deux premières lettres de la catégorie sont également celles d’une autre catégorie, merci de les changer."];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_ajouteOuEditeCategorie.php");
                return;
            }
            $this->modeleBack->ajouteCategorie($id, $_REQUEST["libelle"]);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$id);
        } else {
            header("Location: index.php");
        }
    }

    public function supprimerCategorie(string $idCategorie): void{
        if (!empty($_SESSION["admin"])) {
            if ($this->modeleBack->getLesProduitsDeCategorie($idCategorie) != false)
            {
                $msgErreurs = ["veiller d'abord supprimer les produits de cette catégorie"];
                include_once("vues/v_erreurs.php");
                $lesCategories = $this->modeleBack->getLesCategories();
                $lesProduits = $this->modeleBack->getLesProduitsDeCategorie($idCategorie);
                include_once("vues/v_choixCategorie.php");
                include_once("vues/v_produits.php");
                return;
            }

            $this->modeleBack->supprimerCategorie($idCategorie);
            header("Location: index.php?uc=voirProduits&action=voirProduits");
        } else {
            header("Location: index.php");
        }
    }
}