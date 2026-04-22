<?php
require_once 'controleurs/ControleurVoirProduits.php';
require_once 'controleurs/ControleurAccueil.php';
require_once 'controleurs/ControleurGererPanier.php';
require_once 'controleurs/ControleurAdmin.php';
/**
 * @class Routeur
 * @brief gère les routes (actions à exécuter en fonction des urls)
 */
class Routeur{
    
    private $ctrlVoirProduits;
    private $ctrlAccueil;
    private $ctrlGererPanier;
    private $ctrlAdmin;
    
    public function __construct(){
        
        $this->ctrlVoirProduits=new ControleurVoirProduits();
        $this->ctrlAccueil=new ControleurAccueil();
        $this->ctrlGererPanier=new ControleurGererPanier();
        $this->ctrlAdmin=new ControleurAdmin();
    }
    /** recupère les paramètres de l'url et active les contrôleurs nécessaires
    */
    public function routerRequete()
    {
    // traitement des paramètres de l'url
    if(isset($_REQUEST['uc']))
    	$uc = $_REQUEST['uc'];
        else $uc='accueil';
    if(isset($_REQUEST['action']))
    	$action = $_REQUEST['action'];
    else $action=null;
    switch($uc)
    {
        case 'accueil':
            $this->ctrlAccueil->accueil();break;
        case 'voirProduits' :
            switch ($action)
            {
                case null :
                case 'voirCategories' : {$this->ctrlVoirProduits->voirCategories();break;}
                case 'voirProduits' : {$this->ctrlVoirProduits->voirProduits((isset($_REQUEST['categorie'])) ? $_REQUEST['categorie'] : "CH", (isset($_REQUEST['msgErreurs'])) ? $_REQUEST['msgErreurs'] : null);break;}
                case 'nosProduits' : {$this->ctrlVoirProduits->voirProduits();break;}
                case 'voirProduitDetail' : {$this->ctrlVoirProduits->voirProduitDetail($_REQUEST['produit']);break;}
            }; break;
        case 'gererPanier' :
            switch ($action)
            {
                case null :
                case 'voirPanier' : {$this->ctrlGererPanier->voirPanier();break;}
                case 'ajouterAuPanier' : {$this->ctrlGererPanier->ajouterAuPanier($_REQUEST['produit']);break;}
                case 'suprimerDuPanier': {$this->ctrlGererPanier->suprimerDuPanier($_REQUEST['produit']);break;}
                case 'supprimerPanier' : {$this->ctrlGererPanier->supprimerPanier();break;}
                case 'passerCommande' : $this->ctrlGererPanier->passerCommande();break;
                case 'confirmerCommande' : $this->ctrlGererPanier->confirmerCommande();break;
                default: {$this->ctrlGererPanier->voirPanier();break;}
            }; break;
        case 'admin' :
            {
                switch ($action)
                {
                    case null :
                    case 'connexion': {$this->ctrlAdmin->connexion();break;}
                    case 'confirmConnexion': {$this->ctrlAdmin->confirmConnexion();break;}
                    case 'deconnexion': {$this->ctrlAdmin->deconnexion();break;}
                    case 'changeOrAddProduit': {$this->ctrlAdmin->changeOrAddProduit((empty($_REQUEST["produit"])) ? null : $_REQUEST["produit"]);break;}
                    case 'supprimerProduit': {$this->ctrlAdmin->supprimerProduit($_REQUEST["id"], $_REQUEST["categorie"]);break;}
                    case "confirmchangeOrAddProduit": {$this->ctrlAdmin->confirmchangeOrAddProduit((empty($_REQUEST["produit"])) ? null : $_REQUEST["produit"]); break;}
                    case "produitsFaibleStock": {$this->ctrlAdmin->produitsFaibleStock(); break;}
                    case "ajouteCategorie": {$this->ctrlAdmin->ajouteCategorie(); break;}
                    case "modifierCategorie": {$this->ctrlAdmin->modifierCategorie($_REQUEST["categorie"]); break;}
                    case "confirmModifierCategorie": {$this->ctrlAdmin->confirmModifierCategorie(); break;}
                    case "ajouteOuEditeCategorie": {$this->ctrlAdmin->ajouteCategorie(); break;}
                    case "confirmAjouteCategorie": {$this->ctrlAdmin->confirmAjouteCategorie(); break;}
                    case "confirmSupprimerCategorie": {$this->ctrlAdmin->supprimerCategorie($_REQUEST["idCategorie"]); break;}
                    case "voirCommandes": {$this->ctrlAdmin->voirCommandes(); break;}
                    case "supprimerCommande": {$this->ctrlAdmin->supprimerCommande($_REQUEST["idCommande"]); break;}
                    case "supprimerAssociation": {$this->ctrlAdmin->supprimerAssociation($_REQUEST['idProduit'], $_REQUEST['idAssocier']); break;} # TODO faire la même mais pour rajouter une association entre produits
                }
            }
		break;
    }
}
}