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

    public function getEtapesByRecetteId($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT idEtape, contenu
            FROM etape e 
            WHERE idRecette = :idRecette
            ORDER BY idEtape ASC"
        );
        $qtf->bindParam(':idRecette', $idRecette, PDO::PARAM_INT);
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

    public function getRecettesIdsHavingIngredients($ingredients, $percentage = 0.7)
    {
        $selectedIngredientsArray = explode(',', $ingredients);
        $recettes = $this->getIngredientsGroupByRecette();
        $savedRecettes = array();

        for ($i = 1; $i < count($recettes); $i++) {
            $counter = 0;

            foreach ($recettes[$i] as $ingredient) {
                if (in_array($ingredient['idIngredient'], $selectedIngredientsArray)) {
                    $counter++;
                }
            }

            if ($counter / (float) count($selectedIngredientsArray) >= $percentage) {
                array_push($savedRecettes, $i);
            }

        }

        return $savedRecettes;

    }

/*
SELECT titre, idCategorie from recette r
where (CASE WHEN -1 = -1 THEN TRUE
ELSE FIND_IN_SET(idCategorie, -1)
END)
*/

}
?>