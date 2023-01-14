<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        Healthy
    </title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <h3 class="mb-4">Recettes Healthy</h3>
                <div class="container mt-4">
                    <?php
                    $recettes = $controller->getHealthyRecettes();
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
                                $view->showCadre($recettes[$i]['titre'], $recettes[$i]['description'], $recettes[$i]['image'], $recettes[$i]['idRecette']);
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

    $view->showFooter($controller);
    $view->showScripts();
    ?>
</body>

</html>