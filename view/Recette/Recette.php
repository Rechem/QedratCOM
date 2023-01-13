<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();

session_start();

if (!isset($_GET['id']))
    header('location: ../Home/Home.php');

if (isset($_GET['favorite'])) {
    if ($_GET['favorite'] == "true") {
        $controller->addToFavorite($_GET['id']);
    } else {
        $controller->removeFromFavorite($_GET['id']);
    }
}

if (isset($_GET['note'])) {
    $controller->note($_GET['id'], $_GET['note']);
}

$recette = $controller->getRecetteById($_GET['id']);

if ($recette['idEtat'] != 1) {
    if (!isset($_SESSION))
        header('location: ../Home/Home.php');
    else {
        $isAdmin = $controller->isAdmin($_SESSION['id']);
        if (!$isAdmin)
            header('location: ../Home/Home.php');
    }
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    echo "<title>" . utf8_encode($recette['titre']) . "</title>";
    ?>
</head>


<body>
    <div>
        <?php
        $view = new UserTemplateView();

        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section id="recette-hero">
                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <div id="recette-frame">
                                <img src=<?php echo "../.." . $recette['image']; ?>>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mt-2 me-5">
                                    <h3 class="me-2">
                                        <?php
                                        echo utf8_encode($recette['titre']);
                                        ?>
                                    </h3>
                                    <div role="button">
                                        <a class="text-decoration-none" href="?id=<?php
                                        $isFavorite = $controller->isFavorite($_GET['id']) == "true" ? "false" : "true";
                                        $nameIcon = $isFavorite == "false" ? "heart" : "heart-outline";
                                        echo $_GET['id'] . "&favorite=" . $isFavorite;
                                        ?>">
                                            <ion-icon class="favorite-icon text-danger"
                                                name="<?php echo $nameIcon ?>"></ion-icon>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-group notation-icon d-flex align-items-start">
                                    <?php echo $recette['note']; ?>
                                    <ion-icon name="star" style="margin-top: 3px; margin-left: -4px;"></ion-icon>
                                    <?php echo "(" . $recette['avis'] . " avis)"; ?>
                                </div>
                                <div class="text-group">
                                    <div class="icon-container">
                                        <ion-icon class="grey-icon" name="bar-chart-outline"></ion-icon>
                                    </div>
                                    <h5>
                                        Difficulté :
                                        <?php
                                        $className = $recette['idDifficulte'] == 1 ? "green" :
                                            ($recette['idDifficulte'] == 2 ? "orange " : "red");
                                        echo "<span class=\"" . $className . " fw-bolder\">" . utf8_encode($recette['nomDifficulte']) . "</span>";
                                        ?>
                                    </h5>
                                </div>
                                <div class="text-group">
                                    <div class="icon-container">
                                        <ion-icon class="grey-icon" name="time-outline"></ion-icon>
                                    </div>
                                    <div>
                                        <h5>
                                            Temps : <span class="primary-color">
                                                <?php
                                                echo $view->formatTime($recette['tempsTotal']);
                                                ?>
                                            </span>
                                        </h5>
                                        <p>
                                            Préparation :
                                            <?php
                                            echo $view->formatTime($recette['tempsPreparation']);
                                            ?>
                                        </p>
                                        <p>
                                            Cuisson :
                                            <?php
                                            echo $view->formatTime($recette['tempsCuisson']);
                                            ?>
                                        </p>
                                        <p>
                                            Repos :
                                            <?php
                                            echo $view->formatTime($recette['tempsRepos']);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-group">
                                    <div class="icon-container">
                                        <ion-icon class="grey-icon" name="flash-outline"></ion-icon>
                                    </div>
                                    <h5>
                                        Calories : <span class="primary-color">
                                            <?php
                                            echo number_format($recette['calories'], 1);
                                            ?> kca
                                        </span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="recette-description">
                <h4 class="mb-2">Description</h4>
                <p>
                    <?php
                    echo utf8_encode($recette['description'])
                        ?>
                </p>
            </section>
            <?php
            if (!empty(trim($recette['video']))) {
                ?>
                <section id="recette-video">
                    <video src=<?php echo "../.." . $recette['video'] ?> width="560" height="315"
                        controls="controls"></video>
                </section>
                <?php
            }
            ?>
            <section id="ingredients-etapes">
                <div class="tabs">
                    <h5 class="tab active" id="ingredient-tab">Ingrédients</h5>
                    <h5 class="tab" id="etape-tab">Etapes</h5>
                </div>
                <div class="tabs-content">
                    <div id="ingredients" class="active">
                        <div class="container">
                            <?php
                            $ingredients = $controller->getIngredientsByRecetteId($_GET['id']);
                            $firstRow = true;
                            for ($i = 0; $i < count($ingredients); $i++) {
                                if ($i % 3 == 0) {
                                    if (!$firstRow)
                                        echo "</div>";
                                    else
                                        $firstRow = false;
                                    echo "<div class=\"row\">";
                                }
                                ?>
                                <div class="col-sm-4 col p-2">
                                    <div class="ingredient">

                                        <h5>
                                            <?php
                                            echo utf8_encode($ingredients[$i]['quantite'] . " " . $ingredients[$i]['nomUnite'] . " " . $ingredients[$i]['nomIngredient'])
                                                ?>
                                    </div>
                                    </h5>
                                </div>
                                <?php
                            }
                            if (!$firstRow) {
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                    <div id="etapes" class="">
                        <?php
                        $etapes = $controller->getEtapesByRecetteId($_GET['id']);
                        foreach ($etapes as $row) {

                            ?>
                            <div class="etape">
                                <div class="numero-etape">
                                    <h4>
                                        <?php
                                        echo $row['idEtape'];
                                        ?>
                                    </h4>
                                </div>
                                <div>
                                    <h6>
                                        <?php
                                        echo utf8_encode($row['contenu']);
                                        ?>
                                    </h6>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
            <section id="notation" class="bg-white py-5">
                <h4 class="mb-4">
                    <?php
                    if (!isset($_SESSION) || !isset($_SESSION['id'])) {
                        echo "Connectez-vous pour noter cette recette.";
                    } else {
                        if ($controller->getNote($_GET['id']) < 1) {
                            echo "Notez cette recette !";
                        } else {
                            echo "Votre note : " . "<span class=\"primary-color\">" .
                                $controller->getNote($_GET['id']) . "</span>";
                        } ?>
                    </h4>
                    <form action="" method="get" id="form-notation">
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                        <div class="d-inline-block text-center">
                            <input required type="radio" id="note-1" class="me-1 fs-2" name="note" value="1">
                            <label for="note-1" class="d-block">
                                <h5>1</h5>
                            </label>
                        </div>
                        <div class="d-inline-block text-center">
                            <input required type="radio" id="note-2" class="me-1" name="note" value="2">
                            <label for="note-2" class="d-block">
                                <h5>2</h5>
                            </label>
                        </div>
                        <div class="d-inline-block text-center">
                            <input required type="radio" id="note-3" class="me-1" name="note" value="3">
                            <label for="note-3" class="d-block">
                                <h5>3</h5>
                            </label>
                        </div>
                        <div class="d-inline-block text-center">
                            <input required type="radio" id="note-4" class="me-1" name="note" value="4">
                            <label for="note-4" class="d-block">
                                <h5>4</h5>
                            </label>
                        </div>
                        <div class="d-inline-block text-center">
                            <input required type="radio" id="note-5" class="me-1" name="note" value="5">
                            <label for="note-5" class="d-block">
                                <h5>5</h5>
                            </label>
                        </div>
                        <button type="submit" class="cta-btn py-2 px-4 ms-3">Noter</button>
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