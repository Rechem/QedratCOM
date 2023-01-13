<?php
require_once 'Model.php';
class CategorieModel extends Model
{
    public function getCategories()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT * FROM `categorie`
                ORDER BY idCategorie ASC;";
        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getCategorieById($idCategorie)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT `nom` FROM `categorie` 
                WHERE `idCategorie` = :idCategorie"
        );
        $qtf->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetch();
        parent::deconnexion($pdo);
        return $result;
    }

    public function getRecetteByCategorie($idCategorie, $limit = PHP_INT_MAX)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT `idRecette`, `titre`,  SUBSTRING(description,1,255) as description, `image` FROM `recette` 
                WHERE `idCategorie` = :idCategorie AND idEtat = 1
                LIMIT :limitAmount"
        );
        $qtf->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $qtf->bindParam(':limitAmount', $limit, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetchAll();
        parent::deconnexion($pdo);
        return $result;
    }
}
?>