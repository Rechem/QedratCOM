<?php
require_once 'Model.php';
class IngredientModel extends Model
{

    
    public function getIngredientsByRecetteId($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT quantite, u.nom as nomUnite, t.nom as nomIngredient FROM
            (SELECT idIngredient, quantite, idUnite FROM `recetteingredient`
            WHERE idRecette = :idRecette) T1
            JOIN unite u ON T1.idUnite = u.idUnite
            JOIN ingredient t on T1.idIngredient = t.idIngredient"
        );
        $qtf->bindParam(':idRecette', $idRecette, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getIngredientsByName($search = "", $ignore = "-1", $limit = 0)
    {
        $pdo = parent::connexion();

        // remember king, FIND_IN_SET doesnt support indexes
        $qtf = $pdo->prepare("SELECT idIngredient, nom FROM `ingredient`
                WHERE `nom` LIKE :search
                AND NOT FIND_IN_SET(idIngredient, :ignore)
                ORDER BY  `nom` ASC
                LIMIT :limitAmount");
        $searchPercent = $search . "%";
        $qtf->bindParam(':search', $searchPercent);
        $qtf->bindParam(':limitAmount', $limit, PDO::PARAM_INT);
        $qtf->bindParam(':ignore', $ignore);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getIngredientsByIds($ids)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT nom FROM `ingredient`
                WHERE FIND_IN_SET(idIngredient, :ids)");
        $qtf->bindParam(':ids', $ids);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }
    
    private function getIngredientsGroupByRecette()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idRecette, idIngredient FROM recetteingredient
                ORDER BY idRecette ASC";

        $tmp = parent::requette($pdo, $qtf);
        $result = $tmp->fetchAll(PDO::FETCH_GROUP);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getIngredients()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idIngredient, i.nom as nomIngredient,
                calories, glucides, lipides, mineraux, vitamines,
                isHealthy, s.nom as nomSaison FROM `ingredient` i
                LEFT OUTER JOIN saison s ON
                s.idSaison = i.Idsaison
                ORDER BY nomIngredient ASC;";
        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }
}
?>