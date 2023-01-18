<?php
require_once(__DIR__ . '/../model/RecetteModel.php');
require_once(__DIR__ . '/../model/IngredientModel.php');
require_once(__DIR__ . '/../model/SaisonModel.php');
require_once(__DIR__ . '/../model/CategorieModel.php');
require_once(__DIR__ . '/../model/FeteModel.php');
require_once(__DIR__ . '/../model/NewsModel.php');
require_once(__DIR__ . '/../model/UserModel.php');
require_once "SuperController.php";
class UserController extends SuperController
{
    public function getCategories()
    {
        $categorieModel = new CategorieModel();
        return $categorieModel->getCategories();
    }

    public function getCategorieById($idCategorie)
    {
        $categorieModel = new CategorieModel();
        return $categorieModel->getCategorieById($idCategorie);
    }

    public function getRecetteByCategorie($idCategorie, $limit = PHP_INT_MAX)
    {
        $categorieModel = new CategorieModel();
        return $categorieModel->getRecetteByCategorie($idCategorie, $limit);
    }

    public function getRecetteById($idRecette)
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecetteById($idRecette);
    }

    public function getIngredientsByRecetteId($idRecette)
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientsByRecetteId($idRecette);
    }

    public function getEtapesByRecetteId($idRecette)
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getEtapesByRecetteId($idRecette);
    }
    public function getIngredientsByName($search = "", $ignore = "-1", $limit = 4)
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientsByName($search, $ignore, $limit);
    }
    public function getIngredientsByIds($ids)
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientsByIds($ids);
    }
    public function filterSortRecettes($getObject, $fixedCategorie = -1, $ingredients = "")
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->filterSortRecettes($getObject, $fixedCategorie, $ingredients);
    }

    public function getRecettesIdsHavingIngredients($ingredients)
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecettesIdsHavingIngredients($ingredients);
    }

    public function getSaisons()
    {
        $saisonModel = new SaisonModel();
        return $saisonModel->getSaisons();
    }

    public function getDifficultes()
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getDifficultes();
    }

    public function getRecettesBySaison($idSaison)
    {
        $saisonModel = new SaisonModel();
        return $saisonModel->getRecettesBySaison($idSaison);
    }

    public function getSaisonById($idSaison)
    {
        $saisonModel = new SaisonModel();
        return $saisonModel->getSaisonById($idSaison);
    }
    public function getIngredients()
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredients();
    }

    public function getHealthyRecettes()
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->getHealthyRecettes();
    }

    public function getRecetteByFeteId($idFete)
    {
        $feteModel = new FeteModel();
        return $feteModel->getRecetteByFeteId($idFete);
    }

    public function getFetes()
    {
        $feteModel = new FeteModel();
        return $feteModel->getFetes();
    }

    public function getFeteById($idFete)
    {
        $feteModel = new FeteModel();
        return $feteModel->getFeteById($idFete);
    }

    public function getNews()
    {
        $newsModel = new NewsModel();
        return $newsModel->getNews();
    }

    
    public function getFetesByName($search = "", $limit = 4)
    {
        $feteModel = new FeteModel();
        return $feteModel->getFetesByName($search, $limit);
    }


    public function signUp($nom, $prenom, $mail, $sexe, $dateNaissance, $password)
    {
        $userModel = new UserModel();
        return $userModel->signup($nom, $prenom, $mail, $sexe, $dateNaissance, $password);
    }

    public function isFavorite($idRecette)
    {
        $userModel = new UserModel();
        return $userModel->isFavorite($idRecette);
    }

    public function addToFavorite($idRecette)
    {
        $userModel = new UserModel();
        $userModel->addToFavorite($idRecette);
    }

    public function removeFromFavorite($idRecette)
    {
        $userModel = new UserModel();
        $userModel->removeFromFavorite($idRecette);
    }

    public function note($idRecette, $notation)
    {
        $userModel = new UserModel();
        $userModel->note($idRecette, $notation);
    }

    public function getNote($idRecette)
    {
        $userModel = new UserModel();
        return $userModel->getNote($idRecette);
    }

    public function getUnites()
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getUnites();
    }

    public function getMenuItems(){
        $model = new Model();
        return $model->getMenuItems();
    }

}

?>