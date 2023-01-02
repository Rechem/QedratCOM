<?php
require_once 'Model.php';
class SaisonModel extends Model
{

    public function getSaisons()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idSaison, nom FROM saison
                ORDER BY idSaison ASC";

        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getRecettesBySaison($idSaison)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT
            recette.idRecette,
            recette.titre,
            SUBSTRING(recette.description,1,255) as description,
            recette.image,
            T2.idSaison
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
            WHERE idSaison = :idSaison");

        $qtf->bindParam(':idSaison', $idSaison);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }
    public function getSaisonById($idSaison)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT idSaison, nom FROM `saison`
                WHERE idSaison = :idSaison");
        $qtf->bindParam(':idSaison', $idSaison);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }

}
?>