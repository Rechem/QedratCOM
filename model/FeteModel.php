<?php
require_once 'Model.php';
class FeteModel extends Model
{

    public function getFetes()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idFete, nom FROM fete
                ORDER BY idFete ASC";

        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getRecetteByFeteId($idFete)
    {
        $pdo = parent::connexion();
        
        $qtf = $pdo->prepare(
            "SELECT r.`idRecette`, `titre`,  SUBSTRING(description,1,255) as description, `image`
            FROM `recettefete` rf
            LEFT Outer JOIN `recette` r
            ON rf.idRecette = r.idRecette
            where idfete = :idfete"
        );
        
        $qtf->bindParam(':idfete', $idFete);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getFeteById($idFete)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT idFete, nom FROM `fete`
                WHERE idFete = :idFete");
        $qtf->bindParam(':idFete', $idFete);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getFetesByName($search = "", $limit = 0)
    {
        $pdo = parent::connexion();

        // remember king, FIND_IN_SET doesnt support indexes
        $qtf = $pdo->prepare("SELECT idFete, nom FROM `fete`
                WHERE `nom` LIKE :search
                ORDER BY  `nom` ASC
                LIMIT :limitAmount");
        $searchPercent = $search . "%";
        $qtf->bindParam(':search', $searchPercent);
        $qtf->bindParam(':limitAmount', $limit, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

}
?>