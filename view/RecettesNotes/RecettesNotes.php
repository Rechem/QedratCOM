<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();

if(!isset($_SESSION) || !isset($_SESSION['id'])){
    header("location: ../..");
}

$idUser = $_SESSION['id'];

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        Favoris
    </title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <h3 class="mb-4">Recettes notées</h3>
                <div class="container mt-4">
                    <?php
                    $recettes = $controller->getRecettesNotes($idUser);
                    $firstRow = true;
                    for ($i = 0; $i < count($recettes); $i++) {
                        if ($i % 4 == 0) {
                            if (!$firstRow)
                                echo "</div>";
                            else
                                $firstRow = false;
                            echo "<div class=\"row\">";
                        }
                        ?>
                        <div class="col-lg-3 col p-2">
                            <div>
                                <?php
                                $view->showCadre($recettes[$i]['titre'], $recettes[$i]['description'], $recettes[$i]['image'], $recettes[$i]['idRecette'], $recettes[$i]['note']);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    if (!$firstRow) {
                        echo "</div>";
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>