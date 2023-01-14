<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();
$saison;
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        <?php
        if (isset($_GET['saison']) && !empty(trim($_GET['saison']))) {
            $saison = $controller->getSaisonById($_GET['saison']);
            echo utf8_encode($saison['nom']);
        } else {
            echo "Saisons";
        } ?>
    </title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">

            <section class="section-padding bg-white">
                <h3 class="mb-5">Saisons</h3>
                <?php
                if (isset($_GET['saison']) && !empty(trim($_GET['saison']))) {
                    ?>
                    <h4>Recettes de saison <?php echo utf8_encode($saison['nom']); ?>:
                    </h4>
                    <div class="container mt-4">
                        <?php
                        $recettes = $controller->getRecettesBySaison($_GET['saison']);
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
                    <?php
                } else {
                    $saisons = $controller->getSaisons();
                    ?>
                    <div class="saison-links-container mt-4 mb-4">
                        <div class="container ">
                            <div class="row">
                                <?php
                                foreach ($saisons as $row) {
                                    echo utf8_encode("
                                    <div class=\"col-lg-3 col p-2\">
                                        <a class=\"saison-link \" href=\"?saison=" . $row['idSaison']
                                        . "\"><div>" . $row['nom']
                                        . "</div>
                                        </a>
                                        </div>");
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter($controller);
    $view->showScripts();
    ?>
</body>

</html>