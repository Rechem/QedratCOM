<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();
if (isset($_POST['submit'])) {
    if (isset($_SESSION) && isset($_SESSION['id'])) {
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
        if(isset($_POST['fete'])){
            $fetesArr = $_POST['fete'];
        }

        $isHealthy = 0;
        if(isset($_POST['isHealthy'])){
            $isHealthy = 1;
        }

        $controller->ajouterRecette(
            $_SESSION['id'],
            $_POST['nom'], $_POST['description'], $_POST['categorie'], $_POST['difficulte'],
            $_POST['temps-preparation'], $_POST['temps-cuisson'], $_POST['temps-repos'], $_FILES['image'],
            $_FILES['video'],
            $ingredientsArr,
            $etapesArr, $fetesArr, $isHealthy
        );
    }
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
    <div>
        <?php

        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <h3>Ajouter une recette</h3>
                <?php
                $view->showAjouterRecetteForm($controller, $_SESSION['id'] ?? NULL,
                $_GET['q'] ?? NULL, $_GET['ignore'] ?? NULL);
                ?>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>