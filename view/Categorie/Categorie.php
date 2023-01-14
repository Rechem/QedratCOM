<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();

if (!isset($_GET['categorie']))
    header('location: ../Home/Home.php');

session_start();

$fixedCategorie = $_GET['categorie'];

$categorie = $controller->getCategorieById($_GET['categorie']);

if(!$categorie){
    header('location: ../Home/Home.php');
}

$recettes = $controller->filterSortRecettes($_GET, $fixedCategorie);


?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        <?php
        echo utf8_encode($categorie['nom'])
            ?>
    </title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <h3>
                    <?php echo utf8_encode($categorie['nom']) ?>
                </h3>
                <?php
                $view->showFilterMenu($_GET, $controller, $fixedCategorie)
                ?>
                <div class="container mt-3">
                    <?php
                    // $recettes = $controller->getRecetteByCategorie($_GET['categorie']);
                    $firstRow = true;
                    for ($i = 0; $i < count($recettes); $i++) {
                        if ($i % 4 == 0) {
                            if (!$firstRow)
                                echo "</div>";
                            else
                                $firstRow = false;
                            echo "<div class=\"row g-0 mb-2\">";
                        }
                        ?>
                        <div class="col-lg-3 col p-2">
                            <?php
                            $view->showCadre($recettes[$i]['titre'], $recettes[$i]['description'], $recettes[$i]['image'], $recettes[$i]['idRecette']);
                            ?>
                        </div>
                        <?php
                    }
                    if (!$firstRow) {
                        echo "</div>";
                    }
                    ?>
                </div>
        </div>
        </section>
    </div>
    <?php
    $view->showFooter($controller);
    $view->showScripts();
    ?>
</body>

</html>