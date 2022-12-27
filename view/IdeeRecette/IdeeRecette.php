<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
$view = new UserTemplateView();
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
            <section id="search-section" class="section-padding">
                <h3>Idées recettes</h3>
                <form autocomplete="off" id="search-form">
                    <div id="chips-container">
                    </div>
                    <input type="text" name="searchPhrase" placeholder="Rechercher ingrédient" id="search-phrase">
                    <ul id="results"></ul>
                </form>
                <button class="cta-btn trouver-recette">
                    Trouver des recettes
                </button>
            </section>
        </div>
    </div>
    <?php
    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>