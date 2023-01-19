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

    $controller->setPercentage($_POST['perc']);

    header('location: ./Parametres.php');
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Modifier pourcentage</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Modifier pourcentage</h4>
                <form action="" method="post" id="add-ingredient-form" enctype="multipart/form-data">
                    <h5 class="mb-1">Pourcentage</h5>
                    <input type="number" step="0.01" max="1" min="0.01" name="perc" required
                    value="<?php echo $controller->getPercentage(); ?>">                    
                    <button type="submit" class="cta-btn trouver-recette" name="submit">
                        Modifier
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