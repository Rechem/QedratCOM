<?php
require_once 'Model.php';
class IngredientModel extends Model
{
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
}
?>