<?php
/** 
 * Mission : architecture MVC GsbParam
 
 * @file ModeleBack.php
 * @author Thomas Alasnier <alasnierthomas@gmail.com>
 * @version    3.0
 * @details contient les fonctions d'accès BD pour le BackEnd
 */
require_once 'modele/Modele.php';
/**
 * @class ModeleBack
 * @brief contient les fonctions d'accès aux infos de la BD pour les admin
 */
class ModeleBack extends Modele{

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

}