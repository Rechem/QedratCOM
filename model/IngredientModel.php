<?php
require_once 'Model.php';
class IngredientModel extends Model
{

    public function ajouterIngredient($nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison){
        $pdo = parent::connexion();
        $qtf = $pdo->prepare(
            "INSERT INTO Ingredient(nom, calories, glucides, lipides, mineraux, vitamines, isHealthy, idSaison)
            VALUES (:nom, :calories, :glucides, :lipides, :mineraux, :vitamines, :isHealthy, :idSaison);"
        );
        $qtf->bindParam(':nom', $nom);
        $qtf->bindParam(':calories', $calories);
        $qtf->bindParam(':glucides', $glucides);
        $qtf->bindParam(':lipides', $lipides);
        $qtf->bindParam(':mineraux', $mineraux);
        $qtf->bindParam(':vitamines', $vitamines);
        $qtf->bindParam(':isHealthy', $isHealthy);
        $qtf->bindParam(':idSaison', $idSaison);
        $qtf->execute();
        
        parent::deconnexion($pdo);
    }

    public function modifierIngredient($idIngredient, $nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison){
        $pdo = parent::connexion();
        $qtf = $pdo->prepare(
            "UPDATE Ingredient SET
            nom = :nom,
            calories = :calories,
            glucides = :glucides,
            lipides = :lipides,
            mineraux = :mineraux,
            vitamines = :vitamines,
            isHealthy = :isHealthy,
            idSaison = :idSaison
            WHERE idIngredient = :idIngredient"
        );
        $qtf->bindParam(':idIngredient', $idIngredient);
        $qtf->bindParam(':nom', $nom);
        $qtf->bindParam(':calories', $calories);
        $qtf->bindParam(':glucides', $glucides);
        $qtf->bindParam(':lipides', $lipides);
        $qtf->bindParam(':mineraux', $mineraux);
        $qtf->bindParam(':vitamines', $vitamines);
        $qtf->bindParam(':isHealthy', $isHealthy);
        $qtf->bindParam(':idSaison', $idSaison);
        $qtf->execute();
        
        parent::deconnexion($pdo);
    }

    public function deleteIngredient($idIngredient){
        $isBeingUsed = $this->isBeingUsed($idIngredient);
        if ($isBeingUsed)
            return;

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("DELETE FROM `ingredient` WHERE idIngredient = :idIngredient");
        $qtf->bindParam(':idIngredient', $idIngredient);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    private function isBeingUsed($idIngredient){
        $pdo = parent::connexion();
        $qtf = $pdo->prepare(
            "SELECT COUNT(*) AS count FROM recetteingredient WHERE idIngredient = :idIngredient"
        );
        $qtf->bindParam(':idIngredient', $idIngredient);
        $qtf->execute();
        $result = $qtf->fetch();

        if ($result['count'] > 0)
            $result = true;
        else
            $result = false;
        
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

        $qtf = $pdo->prepare("SELECT nom, idIngredient FROM `ingredient`
                WHERE FIND_IN_SET(idIngredient, :ids)");
        $qtf->bindParam(':ids', $ids);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getIngredientById($id)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT * FROM `ingredient`
                WHERE idIngredient = :id");
        $qtf->bindParam(':id', $id);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }
    
    public function getIngredientsGroupByRecette()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT r.idRecette, idIngredient FROM recette r
            LEFT OUTER JOIN recetteingredient ri
            ON r.idRecette = ri.idRecette
            WHERE idEtat = 1
            ORDER BY r.idRecette ASC";

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

    public function getUnites()
    {
        $pdo = parent::connexion();

        $qtf = "SELECT idUnite, nom FROM `unite`";
        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }
}
?>