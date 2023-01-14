<?php

require_once(__DIR__ . '/../model/RecetteModel.php');
require_once(__DIR__ . '/../model/IngredientModel.php');
require_once(__DIR__ . '/../model/SaisonModel.php');
require_once(__DIR__ . '/../model/CategorieModel.php');
require_once(__DIR__ . '/../model/FeteModel.php');
require_once(__DIR__ . '/../model/NewsModel.php');
require_once(__DIR__ . '/../model/UserModel.php');
abstract class SuperController {
    public function getCategories()
    {
        $categorieModel = new CategorieModel();
        return $categorieModel->getCategories();
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

    public function getAllRecettes(){
        $recettesModel = new RecetteModel();
        return $recettesModel->getAllRecettes();
    }

    public function getNews(){
        $newsModel = new NewsModel();
        return $newsModel->getNews();
    }

    public function getIngredients()
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredients();
    }

    public function getFetes()
    {
        $feteModel = new FeteModel();
        return $feteModel->getFetes();
    }

    public function getUnites()
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getUnites();
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

        $recetteModel = new RecetteModel();
        $recetteModel->ajouterRecette(
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
        );
        return;
    }

    public function isAdmin($idUser)
    {
        $userModel = new UserModel();
        return $userModel->isAdmin($idUser);
    }

    public function login($mail, $password)
    {
        $userModel = new UserModel();
        return $userModel->login($mail, $password);
    }

    public function getFavoris($idUser)
    {
        $userModel = new UserModel();
        return $userModel->getFavoris($idUser);
    }

    public function getRecettesNotes($idUser)
    {
        $userModel = new UserModel();
        return $userModel->getRecettesNotes($idUser);
    }
}
?>