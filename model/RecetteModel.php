<?php
require_once 'Model.php';
require_once 'IngredientModel.php';
class RecetteModel extends Model
{

    public function publishRecette($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("UPDATE recette
                SET idEtat = 1
                WHERE idRecette = :idRecette");

        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    public function hideRecette($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("UPDATE recette
                SET idEtat = 4
                WHERE idRecette = :idRecette");

        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    public function deleteRecetteById($idRecette){

        $recette = $this->getRecetteById($idRecette);
        if (!$recette) {
            return;
        }

        $imageLink = $recette["image"];
        $videoLink = $recette["video"];

        if (!empty($imageLink))
            unlink(__DIR__ . '/..' . $imageLink);


        if (!empty($videoLink))
            unlink(__DIR__ . '/..' . $videoLink);

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("DELETE FROM `recette` WHERE `recette`.`idRecette` = :idRecette");
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();

        $qtf2 = $pdo->prepare("DELETE FROM `recetteingredient` WHERE `idRecette` = :idRecette");
        $qtf2->bindParam(':idRecette', $idRecette);
        $qtf2->execute();

        $qtf3 = $pdo->prepare("DELETE FROM `recettefete` WHERE `idRecette` = :idRecette");
        $qtf3->bindParam(':idRecette', $idRecette);
        $qtf3->execute();

        $qtf4 = $pdo->prepare("DELETE FROM `etape` WHERE `idRecette` = :idRecette");
        $qtf4->bindParam(':idRecette', $idRecette);
        $qtf4->execute();

        parent::deconnexion($pdo);
    }

    //admin only
    public function getAllRecettes(){
        $pdo = parent::connexion();
    
        $qtf = (
            "SELECT
            recette.idUser,
            recette.idEtat,
            recette.idRecette,
            recette.titre,
            (
                recette.tempsPreparation + recette.tempsCuisson + recette.tempsRepos
            ) AS tempsTotal,
            c.nom as nomCategorie,
            d.nom as nomDifficulte,
            recette.isHealthy,
            s.nom as nomSaison,
            e.nom as etat,
            calories,
            ifnull(note,0) as note, ifnull(avis,0) as avis
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
        SELECT
            r.idRecette,
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
        T3.idRecette = recette.idRecette
    LEFT OUTER JOIN(
        SELECT idRecette,
            AVG(note) as note,
            COUNT(*) AS avis
        FROM
            notation
        GROUP BY
            idRecette
    ) AS T5
    ON
        T5.idRecette = recette.idRecette
        LEFT OUTER JOIN difficulte d on d.idDifficulte = recette.idDifficulte
        LEFT OUTER JOIN categorie c on c.idCategorie = recette.idCategorie
        LEFT OUTER JOIN saison s on s.idSaison = T2.idSaison
        LEFT OUTER JOIN etat e on e.idEtat = recette.idEtat
        ORDER BY recette.idRecette ASC"
        );

        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getRecetteById($idRecette)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT
            titre,
            description,
            image,
            idEtat,
            tempsPreparation,
            tempsCuisson,
            tempsRepos,
            tempsTotal,
            video,
            calories,
            idDifficulte,
            nomDifficulte,
            ifnull(note,0) as note,
            ifnull(avis,0) as avis
        FROM
            (
            SELECT
                r.idRecette,
                r.titre,
                r.description,
                r.idEtat,
                r.image,
                r.tempsPreparation,
                r.tempsCuisson,
                r.tempsRepos,
                r.tempsPreparation + r.tempsCuisson + r.tempsRepos AS tempsTotal,
                r.video,
                d.idDifficulte,
                SUM(
                    i.calories * u.coefficientCalorie * ri.quantite
                ) AS calories,
                d.nom AS nomDifficulte
            FROM
                recetteingredient ri
            LEFT OUTER JOIN ingredient i ON
                ri.idIngredient = i.idIngredient
            LEFT OUTER JOIN unite u ON
                u.idUnite = ri.idUnite
            LEFT OUTER JOIN recette r ON
                r.idRecette = ri.idRecette
            LEFT OUTER JOIN difficulte d ON
                d.idDifficulte = r.idDifficulte
            WHERE
                ri.idRecette = :idRecette
            GROUP BY
                ri.idRecette
        ) AS T1
        LEFT OUTER JOIN(
            SELECT
                SUM(note) / COUNT(*) AS note,
                COUNT(*) AS avis,
                idRecette
            FROM
                notation
            WHERE
                idRecette = :idRecette
        ) AS T2
        ON
            T2.idRecette = T1.idRecette"
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

    public function getRecettesIdsHavingIngredients($ingredients)
    {
        $percentage = parent::getPercentage();

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
        $note = isset($getObject['notation']) ? explode(',', $getObject['notation']) : -1;
        $difficulte = isset($getObject['difficulte']) ? implode(',', $getObject['difficulte']) : -1;
        $sortBy = isset($getObject['sortBy']) && !empty(trim($getObject['sortBy'])) ? $getObject['sortBy'] : "idRecette";
        $orderBy = isset($getObject['orderBy']) && !empty(trim($getObject['orderBy'])) ? $getObject['orderBy'] : "asc";

        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT
            idRecette,
            titre,
            SUBSTRING(description, 1, 255) AS description,
            image,
            idEtat
        FROM
            (
            SELECT
                recette.idRecette,
                recette.titre,
                recette.description,
                recette.image,
                recette.tempsPreparation,
                recette.tempsCuisson,
                recette.idEtat,
                (
                    recette.tempsPreparation + recette.tempsCuisson + recette.tempsRepos
                ) AS tempsTotal,
                recette.idDifficulte,
                recette.idCategorie,
                recette.isHealthy,
                T2.idSaison,
                calories,
                ifnull(note,0) as note, ifnull(avis,0) as avis
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
            SELECT
                r.idRecette,
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
            T3.idRecette = recette.idRecette
        LEFT OUTER JOIN(
            SELECT idRecette,
                SUM(note) / COUNT(*) AS note,
                COUNT(*) AS avis
            FROM
                notation
            GROUP BY
                idRecette
        ) AS T5
        ON
            T5.idRecette = recette.idRecette
        ) AS T4
            WHERE
            idEtat = 1
            AND (CASE WHEN :useRecetteIds = 0 THEN TRUE ELSE FIND_IN_SET(idRecette, :recetteIds) END)
            AND (CASE WHEN :saisons = -1 THEN TRUE ELSE FIND_IN_SET(idSaison, :saisons)END)
            AND (CASE WHEN :categorie = -1 THEN TRUE ELSE FIND_IN_SET(idCategorie, :categorie) END)
            AND (CASE WHEN :healthy = -1 THEN TRUE ELSE isHealthy = :healthy END)
            AND (CASE WHEN :note = -1 THEN TRUE ELSE note >= :noteMin AND note <= :noteMax END)
            AND (CASE WHEN :tempsTotal = -1 THEN TRUE ELSE tempsTotal >= :tempsTotalMin AND tempsTotal <= :tempsTotalMax END)
            AND (CASE WHEN :difficulte = -1 THEN TRUE ELSE FIND_IN_SET(idDifficulte, :difficulte)END)
            ORDER BY CASE
            WHEN
                :orderBy = 'asc'
            THEN
                (CASE
                    WHEN :sortBy = 'calories' THEN `calories`
                    WHEN :sortBy = 'notation' THEN `note`
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
                    WHEN :sortBy = 'notation' THEN `note`
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

        if ($note != -1) {
            $qtf->bindParam(':note', $getObject['notation']);
            $qtf->bindParam(':noteMin', $note[0]);
            $qtf->bindParam(':noteMax', $note[1]);
        } else {
            $qtf->bindParam(':note', $note);
            $qtf->bindParam(':noteMin', $note);
            $qtf->bindParam(':noteMax', $note);
        }

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
            WHERE `isHealthy` = 1 AND `idEtat` = 1
            ORDER BY `idRecette` ASC"
        );

        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }


    public function ajouterRecette(
        $idUser,
        $nom,
        $description,
        $idCategorie,
        $idDifficulte,
        $tempsPreparation,
        $tempsCuisson,
        $tempsRepos,
        $image,
        $video,
        $ingredients,
        $etapes,
        $fetes,
        $isHealthy
    )
    {

        $imageLink = parent::ajouterImage($image);
        if (empty($imageLink)) {
            return;
        }

        $videoLink = "";

        if (isset($video) && !empty($video)) {
            $videoLink = parent::ajouterVideo($video);
        }

        $pdo = parent::connexion();

        try {
            $pdo->beginTransaction();

            $qtf = $pdo->prepare(
                "INSERT INTO Recette(idUser, titre, image, description, tempsPreparation, tempsCuisson, tempsRepos, video, isHealthy, idEtat, idCategorie, idDifficulte)
                VALUES (:idUser, :titre, :image, :description, :tempsPreparation, :tempsCuisson, :tempsRepos, :video, :isHealthy, 2, :idCategorie, :idDifficulte);"
            );

            $qtf->bindParam(':idUser', $idUser);
            $qtf->bindParam(':titre', $nom);
            $qtf->bindParam(':image', $imageLink);
            $qtf->bindParam(':idCategorie', $idCategorie);
            $qtf->bindParam(':idDifficulte', $idDifficulte);
            $qtf->bindParam(':description', $description);
            $qtf->bindParam(':tempsPreparation', $tempsPreparation);
            $qtf->bindParam(':tempsCuisson', $tempsCuisson);
            $qtf->bindParam(':tempsRepos', $tempsRepos);
            $qtf->bindParam(':video', $videoLink);
            $qtf->bindParam(':isHealthy', $isHealthy);

            $qtf->execute();

            $recetteId = $pdo->lastInsertId();

            if ($recetteId <= 0)
                throw new ErrorException('something went wrong');

            foreach ($ingredients as $ing) {
                $qtf2 = $pdo->prepare(
                    "INSERT INTO recetteingredient(idRecette, idIngredient, quantite, idUnite)
                    VALUES (:idRecette, :idIngredient, :quantite, :idUnite);"
                );

                $qtf2->bindParam(':idRecette', $recetteId);
                $qtf2->bindParam(':idIngredient', $ing['idIngredient']);
                $qtf2->bindParam(':quantite', $ing['quantite']);
                $qtf2->bindParam(':idUnite', $ing['idUnite']);

                $qtf2->execute();
            }

            $numeroEtape = 1;


            foreach ($etapes as $etape) {

                $qtf3 = $pdo->prepare(
                    "INSERT INTO etape(idRecette, idEtape, contenu)
                    VALUES (:idRecette, :idEtape, :contenu);"
                );

                $qtf3->bindParam(':idRecette', $recetteId);
                $qtf3->bindParam(':idEtape', $numeroEtape);
                $qtf3->bindParam(':contenu', $etape);

                $qtf3->execute();

                $numeroEtape++;

            }


            foreach ($fetes as $fete) {
                $qtf4 = $pdo->prepare(
                    "INSERT INTO recettefete(idRecette, idFete)
                    VALUES (:idRecette, :idFete);"
                );

                $qtf4->bindParam(':idRecette', $recetteId);
                $qtf4->bindParam(':idFete', $fete);

                $qtf4->execute();
            }

            $pdo->commit();
        } catch (PDOException $e) {
            // rollback the transaction
            $pdo->rollBack();

            // show the error message
            echo "Erreur lors de l'insertion de la recette";
        } finally {
            parent::deconnexion($pdo);
        }
    }

    public function getRecetteByUser($idUser)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT idRecette,
            titre,
            SUBSTRING(description, 1, 255) AS description,
            image
            FROM recette
            Where idUser = :idUser"
        );
        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

}
?>