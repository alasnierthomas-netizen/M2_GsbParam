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
class ControleurAdmin {
    
    private $modeleBack;

    public function __construct() {
        $this->modeleBack = new ModeleBack();
    }

    public function connexion() {
        if (empty($_SESSION["admin"])) {
            include_once("vues/v_connexion.php");
        } else {
            $message = "vous êtes déjà connecté";
            include_once("vues/v_message.php");
        }
    }

    public function confirmConnexion() {
        if (!empty($_REQUEST["login"]) && !empty($_REQUEST["mdp"])) {
            $reponse = $this->modeleBack->getAdmin($_REQUEST["login"], $_REQUEST["mdp"]);
            if ($reponse == false) {
                $msgErreurs = ["login ou mot de passe incorrect"];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_connexion.php");
            } else {
                $_SESSION["admin"] = $reponse;
                $message = "connexion réussie";
                header("Location: index.php?message=" . $message);
            }
        } else {
            $msgErreurs = ["veuillez entrer un login et un mot de passe"];
            include_once("vues/v_erreurs.php");
            include_once("vues/v_connexion.php");
        }
    }

    public function deconnexion(): void {
        unset($_SESSION["admin"]);
        header("Location: index.php");
    }

    public function changeOrAddProduit($idProduit): void { 
        if (!empty($_SESSION["admin"])) {
            $categories = $this->modeleBack->getLesCategories();
            if ($idProduit == null) { // Nouveau produit
                $unites = $this->modeleBack->getLesUnites();
                $idUnite = 2;
                $idCategorie = 1;
                $marques = $this->modeleBack->getMarques();
                $idMarque = 1;
                include_once("vues/v_modifProduit.php");
            } else { // Modifier produit
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
        } else {
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

            // Test si les champs obligatoires sont remplis
            if (empty($_REQUEST["description"]) || empty($_REQUEST["prix"]) || empty($_REQUEST["idCategorie"]) || empty($_REQUEST["nom"]) || empty($_REQUEST["marque"]) || empty($_REQUEST["contenance"]) || empty($_REQUEST["unite"])) {
                $msgErreurs = ["veuillez remplir les champs obligatoires"];
                include_once("vues/v_erreurs.php");
                
                $categories = $this->modeleBack->getLesCategories();
                $prix = $_REQUEST["prix"] ?? "";
                $nom = $_REQUEST["nom"] ?? "";
                $contenance = $_REQUEST["contenance"] ?? "";
                $description = $_REQUEST["description"] ?? "";
                $unites = $this->modeleBack->getLesUnites();
                $idUnite = $_REQUEST["unite"] ?? 2;
                $idCategorie = $_REQUEST["idCategorie"] ?? 1;
                $marques = $this->modeleBack->getMarques();
                $idMarque = $_REQUEST["marque"] ?? 1;
                
                include_once("vues/v_modifProduit.php");
                return;
            }

            if (empty($_REQUEST["idProduit"])) { // Nouveau
                if (empty($imageName)) {
                    $msgErreurs = ["veuillez ajouter une image pour le produit"];
                    include_once("vues/v_erreurs.php");
                    // ... rechargement des variables pour la vue ...
                    include_once("vues/v_modifProduit.php");
                    return;
                }
                $this->modeleBack->creerProduit($_REQUEST["description"], $_REQUEST["prix"], $imageName, $_REQUEST["idCategorie"], $_REQUEST["nom"], $_REQUEST["marque"], ($_REQUEST["stock"] ?? 0), $_REQUEST["contenance"], $_REQUEST["unite"]);
            } else { // Modification
                $this->modeleBack->editProduit($_REQUEST["idProduit"], $_REQUEST["description"], $_REQUEST["prix"], $imageName, $_REQUEST["idCategorie"], $_REQUEST["nom"], $_REQUEST["marque"], ($_REQUEST["stock"] ?? 0), $_REQUEST["contenance"], $_REQUEST["unite"]);
            }
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$_REQUEST["idCategorie"]);
        } else {
            header("Location: index.php");
        }
    }

    public function supprimerProduit(string $idProduit, string $idCategorie): void {
        if (!empty($_SESSION["admin"])) {
            $this->modeleBack->supprimerProduit($idProduit);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$idCategorie);
        } else {
            header("Location: index.php");
        }
    }

    public function ajouteCategorie(): void {
        if (!empty($_SESSION["admin"])) {
            include_once("vues/v_ajouteCategorie.php");
        } else {
            header("Location: index.php");
        }
    }

    public function modifierCategorie(string $idCategorie): void {
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

    public function confirmModifierCategorie(): void {
        if (!empty($_SESSION["admin"])) {
            if (empty($_REQUEST["idCategorie"]) || empty($_REQUEST["libelle"])) {
                $msgErreurs = ["veillez remplir le champ libelle"];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_editeCategorie.php");
                return;
            }
            $id = $this->modeleBack->getAbreviationsCategorie($_REQUEST["libelle"]);
            $this->modeleBack->editCategorie($_REQUEST["idCategorie"], $_REQUEST["libelle"], $id);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$_REQUEST["idCategorie"]);
        } else {
            header("Location: index.php");
        }
    }

    public function confirmAjouteCategorie(): void {
        if (!empty($_SESSION["admin"])) {
            if (empty($_REQUEST["libelle"])) {
                $msgErreurs = ["veuillez remplir le champ libelle"];
                include_once("vues/v_erreurs.php");
                include_once("vues/v_ajouteCategorie.php");
                return;
            }
            $id = $this->modeleBack->getAbreviationsCategorie($_REQUEST["libelle"]);
            $this->modeleBack->ajouteCategorie($id, $_REQUEST["libelle"]);
            header("Location: index.php?uc=voirProduits&action=voirProduits&categorie=".$id);
        } else {
            header("Location: index.php");
        }
    }

    public function supprimerCategorie(string $idCategorie): void {
        if (!empty($_SESSION["admin"])) {
            if ($this->modeleBack->getLesProduitsDeCategorie($idCategorie) != false) {
                $msgErreurs = ["veuillez d'abord supprimer les produits de cette catégorie"];
                include_once("vues/v_erreurs.php");
                return;
            }
            $this->modeleBack->supprimerCategorie($idCategorie);
            header("Location: index.php?uc=voirProduits&action=voirProduits");
        } else {
            header("Location: index.php");
        }
    }
}