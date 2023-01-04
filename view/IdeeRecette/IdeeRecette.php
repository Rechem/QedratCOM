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
    <title>Idée recettes</title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">

            <section id="idee-section" class="section-padding bg-white">
                <h3>Idées recettes</h3>
                <?php
                if (isset($_GET['ingredients']) && !empty(trim($_GET['ingredients']))) {
                    $ingredients = $controller->getIngredientsByIds($_GET['ingredients']);
                    ?>
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <h4 >Recettes incluants les ingrédients :
                            <?php
                            $fist = true;
                            foreach ($ingredients as $row) {
                                if ($fist)
                                    $fist = false;
                                else
                                    echo ', ';
                                echo utf8_encode($row['nom']);
                            }
                            echo '.';
                            ?>
                        </h4>
                        <div>
                            <a href="<?php ?>IdeeRecette.php" class="text-button">Réinitialiser</a>
                        </div>
                    </div>
                    <?php $view->showFilterMenu($_GET, $controller) ?>
                    <div class="container mt-4">
                        <?php
                        $recettes = $controller->filterSortRecettes($_GET, -1, $_GET['ingredients']);
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
                    <form id="search-form">
                        <div id="chips-container">
                        </div>
                        <input type="text" name="searchPhrase" autofocus placeholder="Rechercher ingrédient"
                            id="search-phrase">
                        <ul id="results">
                            <?php
                            if (isset($_GET['q'])) {
                                if (isset($_GET['ignore']))
                                    $ingredients = $controller->getIngredientsByName($_GET['q'], $_GET['ignore']);
                                else
                                    $ingredients = $controller->getIngredientsByName($_GET['q']);
                                foreach ($ingredients as $row) {
                                    echo utf8_encode("<li class=\"result-item\" data-id=\"" . $row['idIngredient'] . "\">" . $row['nom'] . "</li>");
                                }
                            }
                            ?>
                        </ul>
                        <button type="button" class="cta-btn trouver-recette">
                            Trouver des recettes
                        </button>
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