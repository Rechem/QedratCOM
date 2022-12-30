<?php
require_once(__DIR__ . '/../model/RecetteModel.php');
require_once(__DIR__ . '/../model/IngredientModel.php');
class UserController
{
    public function getCategories()
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getCategories();
    }

    public function getRecetteByCategorie($idCategorie, $limit = 0)
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecetteByCategorie($idCategorie, $limit);
    }

    public function getRecetteById($idRecette){
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecetteById($idRecette);
    }

    public function getIngredientsByRecetteId($idRecette){
        $recetteModel = new RecetteModel();
        return $recetteModel->getIngredientsByRecetteId($idRecette);
    }

    public function getEtapesByRecetteId($idRecette){
        $recetteModel = new RecetteModel();
        return $recetteModel->getEtapesByRecetteId($idRecette);
    }
    public function getIngredientsByName($search = "", $ignore = "-1", $limit = 4){
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientsByName($search, $ignore, $limit);
    }
    public function getIngredientsByIds($ids){
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientsByIds($ids);
    }

    public function getRecettesIdsHavingIngredients($ingredients){
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecettesIdsHavingIngredients($ingredients);
    }
}

?>