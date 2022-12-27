<?php
require_once 'Model.php';
class RecetteModel extends Model
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

    public function getRecetteByCategorie($idCategorie, $limit = 0)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT `idRecette`, `titre`, `description`, `image` FROM `recette` 
                WHERE `idCategorie` = :idCategorie
                LIMIT :limitAmount"
        );
        $qtf->bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $qtf->bindParam(':limitAmount', $limit, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getRecetteById($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT r.titre, r.description, r.image, r.tempsPreparation, r.tempsCuisson, r.tempsRepos,
            r.tempsPreparation + r.tempsCuisson + r.tempsRepos as tempsTotal, r.video, d.idDifficulte,
            SUM(i.calories*u.coefficientCalorie*ri.quantite) as calories, d.nom as nomDifficulte FROM recetteingredient ri
            LEFT OUTER JOIN ingredient i
            ON ri.idIngredient = i.idIngredient
            LEFT OUTER JOIN unite u
            ON u.idUnite = ri.idUnite
            LEFT OUTER JOIN recette r
            ON r.idRecette = ri.idRecette
            LEFT OUTER JOIN difficulte d
            ON d.idDifficulte = r.idDifficulte
            WHERE ri.idRecette = :idRecette
            GROUP BY ri.idRecette"
        );
        $qtf->bindParam(':idRecette', $idRecette, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }
}
?>