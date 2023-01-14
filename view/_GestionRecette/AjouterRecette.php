<!DOCTYPE html>
<html lang="en">
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if (!$controller->isAdmin($_SESSION['id'])) {
    header('location: ../Home/Home.php');
}

if (isset($_POST['submit'])) {
    $ingredientsArr = [];
    for ($i = 0; $i < count($_POST['ingredients']); $i++) {
        array_push(
            $ingredientsArr,
            [
                'idIngredient' => $_POST['ingredients'][$i],
                'quantite' => $_POST['quantite'][$i],
                'idUnite' => $_POST['unite'][$i]
            ]
        );
    }

    $etapesArr = preg_split('/\n\s*\n/', $_POST['etapes']);
    $etapesArr = array_filter($etapesArr, function ($e) {
        return trim($e) != '\n' && !empty(trim($e));
    });

    $etapesArr = array_map(function ($e) {
        return str_replace("\r\n", "", $e);
    }, $etapesArr);

    $fetesArr = [];
    if (isset($_POST['fete'])) {
        $fetesArr = $_POST['fete'];
    }

    $isHealthy = 0;
    if (isset($_POST['isHealthy'])) {
        $isHealthy = 1;
    }

    $controller->ajouterRecette(
        $_SESSION['id'],
        $_POST['nom'], $_POST['description'], $_POST['categorie'], $_POST['difficulte'],
        $_POST['temps-preparation'], $_POST['temps-cuisson'], $_POST['temps-repos'], $_FILES['image'],
        $_FILES['video'],
        $ingredientsArr,
        $etapesArr,
        $fetesArr,
        $isHealthy
    );

    header('location: ./GestionRecette.php');
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Ajouter une recette</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion']?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Ajouter une recette</h4>
                <?php
                $view->showAjouterRecetteForm(
                    $controller, $_SESSION['id'] ?? NULL,
                    $_GET['q'] ?? NULL, $_GET['ignore'] ?? NULL
                );
                ?>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>