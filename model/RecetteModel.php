<?php
require_once 'Model.php';
require_once 'IngredientModel.php';
class RecetteModel extends Model
{

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

    public function getRecettesIdsHavingIngredients($ingredients, $percentage = 0.7)
    {
        $selectedIngredientsArray = explode(',', $ingredients);
        $ingredientModel = new IngredientModel();
        $recettes = $ingredientModel->getIngredientsGroupByRecette();
        $savedRecettes = array();

        for ($i = 1; $i <= count($recettes); $i++) {
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

        if (count($savedRecettes) > 0) {
            $pdo = parent::connexion();

            $recettesArr = implode(',', $savedRecettes);
            $qtf = $pdo->prepare("SELECT idRecette FROM `recette`
                WHERE FIND_IN_SET(idRecette, :savedRecettes)");
            $qtf->bindParam(':savedRecettes', $recettesArr);
            $qtf->execute();
            $result = $qtf->fetchAll();

            return array_map(function ($o) {
                return $o['idRecette'];
            }, $result);
        }

        return $savedRecettes;

    }


    public function getDifficultes()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idDifficulte, nom FROM difficulte
                ORDER BY idDifficulte ASC";

        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function filterSortRecettes($getObject, $fixedCategorie = -1, $ingredients = "")
    {
        $recetteIds = $this->getRecettesIdsHavingIngredients($ingredients);

        $saisons = isset($getObject['saison']) ? implode(',', $getObject['saison']) : -1;
        $isHealthy = $getObject['healthy'] ?? -1;
        $tempsTotal = isset($getObject['total']) ? explode(',', $getObject['total']) : -1;
        $difficulte = isset($getObject['difficulte']) ? implode(',', $getObject['difficulte']) : -1;
        $sortBy = isset($getObject['sortBy']) && !empty(trim($getObject['sortBy'])) ? $getObject['sortBy'] : "idRecette";
        $orderBy = isset($getObject['orderBy']) && !empty(trim($getObject['orderBy'])) ? $getObject['orderBy'] : "asc";

        $pdo = parent::connexion();

        //TODO include notation
        $qtf = $pdo->prepare(
            "SELECT idRecette, titre, SUBSTRING(description,1,255) as description, image from (SELECT
            recette.idRecette,
            recette.titre,
            recette.description,
            recette.image,
            recette.tempsPreparation,
            recette.tempsCuisson,
            (recette.tempsPreparation + recette.tempsCuisson + recette.tempsRepos) AS tempsTotal,
            recette.idDifficulte,
            recette.idCategorie,
            recette.isHealthy,
            T2.idSaison,
            calories
        FROM
            recette
        LEFT OUTER JOIN(
            SELECT
                T1.idRecette,
                T1.idSaison
            FROM
                (
                SELECT
                    idSaison,
                    COUNT(i.idSaison) AS COUNT,
                    r.idRecette
                FROM
                    recetteingredient ri
                LEFT OUTER JOIN ingredient i ON
                    ri.idIngredient = i.idIngredient
                LEFT OUTER JOIN recette r ON
                    r.idRecette = ri.idRecette
                LEFT OUTER JOIN unite u ON
                    u.idUnite = ri.idUnite
                GROUP BY
                    r.idRecette,
                    i.idSaison
                HAVING
                    ! ISNULL(i.idSaison)
            ) AS t1
        GROUP BY
            T1.idRecette
        ) AS T2
        ON
            recette.idRecette = T2.idRecette
        LEFT OUTER JOIN(
            SELECT r.idRecette,
                SUM(
                    i.calories * u.coefficientCalorie * ri.quantite
                ) AS calories
            FROM
                recetteingredient ri
            LEFT OUTER JOIN ingredient i ON
                ri.idIngredient = i.idIngredient
            LEFT OUTER JOIN unite u ON
                u.idUnite = ri.idUnite
            LEFT OUTER JOIN recette r ON
                r.idRecette = ri.idRecette
            GROUP BY
                ri.idRecette
        ) AS T3
        ON
            T3.idRecette = recette.idRecette ) as T4
            WHERE
            (CASE WHEN :useRecetteIds = 0 THEN TRUE ELSE FIND_IN_SET(idRecette, :recetteIds) END)
            AND (CASE WHEN :saisons = -1 THEN TRUE ELSE FIND_IN_SET(idSaison, :saisons)END)
            AND (CASE WHEN :categorie = -1 THEN TRUE ELSE FIND_IN_SET(idCategorie, :categorie) END)
            AND (CASE WHEN :healthy = -1 THEN TRUE ELSE isHealthy = :healthy END)
            AND (CASE WHEN :tempsTotal = -1 THEN TRUE ELSE tempsTotal >= :tempsTotalMin AND tempsTotal <= :tempsTotalMax END)
            AND (CASE WHEN :difficulte = -1 THEN TRUE ELSE FIND_IN_SET(idDifficulte, :difficulte)END)
            ORDER BY CASE
            WHEN
                :orderBy = 'asc'
            THEN
                (CASE
                    WHEN :sortBy = 'calories' THEN `calories`
                    WHEN :sortBy = 'tempsTotal' THEN `tempsTotal`
                    WHEN :sortBy = 'tempsPreparation' THEN `tempsPreparation`
                    WHEN :sortBy = 'tempsCuisson' THEN `tempsCuisson`
                    Else idRecette
                END)
        END ASC , CASE
            WHEN
                :orderBy = 'desc'
            THEN
                (CASE
                    WHEN :sortBy = 'calories' THEN `calories`
                    WHEN :sortBy = 'tempsTotal' THEN `tempsTotal`
                    WHEN :sortBy = 'tempsPreparation' THEN `tempsPreparation`
                    WHEN :sortBy = 'tempsCuisson' THEN `tempsCuisson`
                    Else idRecette
                END)
        END DESC;"
        );

        $useRecetteIds = !empty(trim($ingredients));

        $qtf->bindParam(':useRecetteIds', $useRecetteIds);
        if ($useRecetteIds) {
            $implodedRecetteIds = implode(',', $recetteIds);
            $qtf->bindParam(':recetteIds', $implodedRecetteIds);
        } else {
            $empty = "";
            $qtf->bindParam(':recetteIds', $empty);
        }

        $qtf->bindParam(':saisons', $saisons);

        if ($fixedCategorie != -1) {
            $qtf->bindParam(':categorie', $fixedCategorie);
        } else {
            $categorie = isset($getObject['categorie']) ? implode(',', $getObject['categorie']) : -1;
            $qtf->bindParam(':categorie', $categorie);
        }

        $qtf->bindParam(':healthy', $isHealthy);

        if ($tempsTotal != -1) {
            $qtf->bindParam(':tempsTotal', $getObject['tempsTotal']);
            $qtf->bindParam(':tempsTotalMin', $tempsTotal[0]);
            $qtf->bindParam(':tempsTotalMax', $tempsTotal[1]);
        } else {
            $qtf->bindParam(':tempsTotal', $tempsTotal);
            $qtf->bindParam(':tempsTotalMin', $tempsTotal);
            $qtf->bindParam(':tempsTotalMax', $tempsTotal);
        }

        $qtf->bindParam(':difficulte', $difficulte);

        $qtf->bindParam(':sortBy', $sortBy);
        $qtf->bindParam(':orderBy', $orderBy);
        
        $qtf->execute();
        $result = $qtf->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    public function getHealthyRecettes()
    {
        $pdo = parent::connexion();
        
        $qtf = $pdo->prepare(
            "SELECT `idRecette`, `titre`, SUBSTRING(`description`,1,255) as description, `image`
            FROM `recette`
            WHERE `isHealthy` = 1
            ORDER BY `idRecette` ASC"
        );
        
        $qtf->execute();
        $result = $qtf->fetchAll();
        
        parent::deconnexion($pdo);
        return $result;
    }

}
?>