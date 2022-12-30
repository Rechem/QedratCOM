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
    <title>Idée recettes</title>
</head>


<body>
    <div>
        <?php
        $view->showSocialLinks();
        $view->showHeader();
        ?>
        <div class="boundary">
            <?php
            if (isset($_GET['ingredients']) && !empty(trim($_GET['ingredients']))) {
                $ingredients = $controller->getIngredientsByIds($_GET['ingredients']);
                ?>
                <section id="search-section" class="section-padding">
                    <h4>Recettes incluants les ingrédients :
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
                        $recettes = $controller->getRecettesIdsHavingIngredients($_GET['ingredients']);
                        print_r($recettes);
                        ?>
                    </h4>
                    <?php
            } else {
                ?>
                    <section id="search-section" class="section-padding">
                        <h3>Idées recettes</h3>
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
                        </form>
                        <button type="button" class="cta-btn trouver-recette">
                            Trouver des recettes
                        </button>
                    </section>
                    <?php } ?>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>