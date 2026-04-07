<?php
include_once("modele/ModeleFront.php");
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
class ControleurClient{
    
    private $modeleFront;
    public function __construct(){
        $this->modeleFront= new ModeleFront();
    }

    public function connexion(){
        if (empty($_SESSION["client"])){
            include_once("vues/connexionclient.php");
        }
        else{
            $message = "vous êtes déjas connecter";
            include_once("vues/v_message.php");
        }
    }

    public function confirmConnexion(){
        
        if (!empty($_REQUEST["login"]) && !empty($_REQUEST["mdp"]))
        {
            $reponse = $this->modeleFront->getClient($_REQUEST["login"],$_REQUEST["mdp"]);
            if ($reponse == false)
            {
                $msgErreurs = ["login ou mot de passe incorrect"];
                include_once("vues/v_erreurs.php");
                include_once("vues/connexionclient.php");
            }
            else{
                $_SESSION["client"] = $reponse;
                $message = "connexion réussie";
                header("Location: index.php?message=".$message);
            }
        }
        else{
            $msgErreurs = ["veiller rentrer un login et un mot de passe"];
            include_once("vues/v_erreurs.php");
            include_once("vues/connexionclient.php");
        }
    }

    public function deconnexion(): void{
        unset($_SESSION["client"]);
        header("Location: index.php");
    }
    public function confirmInscription() {
    // 1. Récupération des données du formulaire
    $login = $_POST['login'] ?? null;
    $password = $_POST['mdp'] ?? null;

    if ($login && $password) {
        // 2. Préparation des données automatiques
        $id = rand(100, 999); // Génération d'un ID numérique simple
        
        // 3. Appel de ta fonction insertClient
        $res = $this->modeleFront->insertClient($id, $login, $password);

        if ($res) {
            $message = "Compte créé avec succès !";
            header("Location: index.php?uc=client&action=connexion&msg=" . urlencode($message));
            exit();
        } else {
            $msgErreurs = ["Erreur lors de l'insertion en base de données."];
            include("vues/v_erreurs.php");
            include("vues/Inscription.php");
        }
    } else {
        $msgErreurs = ["Veuillez remplir tous les champs."];
        include("vues/v_erreurs.php");
        include("vues/Inscription.php");
    }
}

}
