<?php
require_once "SuperController.php";
require_once(__DIR__ . '/../model/UserModel.php');
require_once(__DIR__ . '/../model/RecetteModel.php');
class AdminController extends SuperController
{

    public function publishRecette($idRecette){
        $recetteModel = new RecetteModel();
        return $recetteModel->publishRecette($idRecette);
    }

    public function hideRecette($idRecette){
        $recetteModel = new RecetteModel();
        return $recetteModel->hideRecette($idRecette);
    }

    public function getUsers()
    {
        $userModel = new UserModel();
        return $userModel->getUsers();
    }

    public function getUser($idUser)
    {
        $userModel = new UserModel();
        return $userModel->getUser($idUser);
    }

    public function getRoleByEmail($mail){
        $userModel = new UserModel();
        return $userModel->getRoleByEmail($mail);
    }

    public function deleteRecetteById($idRecette)
    {
        $recetteModel = new RecetteModel();
        return $recetteModel->deleteRecetteById($idRecette);
    }

    public function ajouterIngredient($nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison){
        $ingredientModel = new IngredientModel();
        return $ingredientModel->ajouterIngredient($nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison);
    }

    public function modifierIngredient($idIngredient, $nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison)
    {
        $ingredientModel = new IngredientModel();
        return $ingredientModel->modifierIngredient($idIngredient, $nom, $calories, $glucides, $lipides, $mineraux, $vitamines, $isHealthy, $idSaison);
    }

    public function deleteIngredient($ingredient){
        $ingredientModel = new IngredientModel();
        return $ingredientModel->deleteIngredient($ingredient);
    }

    public function ajouterNews($titre, $corps, $image, $video){
        $newsModel = new NewsModel();
        return $newsModel->ajouterNews($titre, $corps, $image, $video);
    }

    public function modifierNews($idNews, $titre, $corps, $image, $video){
        $newsModel = new NewsModel();
        return $newsModel->modifierNews($idNews, $titre, $corps, $image, $video);
    }

    public function deleteNews($idNews){
        $newsModel = new NewsModel();
        return $newsModel->deleteNews($idNews);
    }

    public function changeUserStatus($idUser, $newStatus){
        $userModel = new UserModel();
        return $userModel->changeUserStatus($idUser, $newStatus);
    }

    public function getIngredientById($id){
        $ingredientModel = new IngredientModel();
        return $ingredientModel->getIngredientById($id);
    }

    public function supprimerDiapo($idDiaporama){
        $userModel = new UserModel();
        return $userModel->supprimerDiapo($idDiaporama);

    }

    public function ajouterDiapo($lien, $image){
        $userModel = new UserModel();
        return $userModel->ajouterDiapo($lien, $image);
    }
    
    public function supprimerMessage($idMessage){
        $userModel = new UserModel();
        return $userModel->supprimerMessage($idMessage);
    }

    public function getMessages(){
        $userModel = new UserModel();
        return $userModel->getMessages();
    }

    public function getRecetteByUser($idUser){
        $recetteModel = new RecetteModel();
        return $recetteModel->getRecetteByUser($idUser);
    }

    public function getPercentage(){
        $model = new Model();
        return $model->getPercentage();

    }

    public function setPercentage($perc){
        $model = new Model();
        return $model->setPercentage($perc);

    }

}

?>