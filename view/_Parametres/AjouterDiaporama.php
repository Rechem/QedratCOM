<!DOCTYPE html>
<html lang="en">
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if (!$controller->isAdmin($_SESSION['id'] ?? -1)) {
    header('location: ../Home/Home.php');
}

if (isset($_POST['submit'])) {

    $controller->ajouterDiapo($_POST['lien'], $_FILES['image']);

    header('location: ./Parametres.php');
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Ajouter une diaporama</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Ajouter une diaporama</h4>
                <form action="" method="post" id="add-ingredient-form" enctype="multipart/form-data">
                    <h5 class="mb-1">Lien (ex : /Recettes/Recettes?id=2)</h5>
                    <input type="text" name="lien" class="mb-3" required>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between my-3">
                        <h5>Image</h5>
                        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required>
                    </div>
                    <button type="submit" class="cta-btn trouver-recette" name="submit">
                        Ajouter
                    </button>
                </form>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>