<?php
require_once(__DIR__ . '/../model/RecetteModel.php');
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
}

?>