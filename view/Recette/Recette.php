<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$controller = new UserController();
$view = new UserTemplateView();

if (!isset($_GET['id']))
    header('location: ../Home/Home.php');

$recette = $controller->getRecetteById($_GET['id']);
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
        $view->showSocialLinks();
        $view->showHeader();
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
                                <h3><?php
                                echo utf8_encode($recette['titre']);
                                ?>
                                </h3>
                                <div class="text-group">
                                    <ion-icon class="notation-icon" name="star"></ion-icon>
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
                                            echo "<b><span class=\"".$className."\">" .utf8_encode($recette['nomDifficulte'])."</span></b>";
                                            
                                        
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
                <h4>Description</h4>
                <p>
                    <?php
                    echo utf8_encode($recette['description'])
                    ?>
                </p>
            </section>
        </div>
    </div>
    <?php
    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>