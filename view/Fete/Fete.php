<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Fêtes</title>
</head>


<body>
    <div>
        <?php
        $view->showSocialLinks();
        $view->showHeader();
        ?>
        <div class="boundary">

            <section id="fete-search-section" class="section-padding bg-white">
                <h3>Fêtes et occasions</h3>
                <?php
                if (isset($_GET['fete']) && !empty(trim($_GET['fete']))) {
                    $fete = $controller->getFeteById($_GET['fete']);
                    ?>
                    <h4>
                        <?php
                        echo utf8_encode($fete['nom']);
                        ?>
                    </h4>
                    <div class="container mt-4">
                        <?php
                        $recettes = $controller->getRecetteByFeteId($_GET['fete']);
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
                    ?>
                    <form id="search-form" class="mb-5">
                        <div id="chips-container">
                        </div>
                        <input type="text" name="searchPhrase" autofocus placeholder="Rechercher fête" id="search-phrase"
                        autocomplete="false">
                        <ul id="results">
                            <?php
                            if (isset($_GET['q'])) {
                                $fetes = $controller->getFetesByName($_GET['q']);
                                foreach ($fetes as $row) {
                                    echo utf8_encode("<li class=\"result-item\" data-id=\"" . $row['idFete'] . "\">" . $row['nom'] . "</li>");
                                }
                            }
                            ?>
                        </ul>
                    </form>
                    <?php } ?>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>