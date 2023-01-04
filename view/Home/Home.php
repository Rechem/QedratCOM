<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$controller = new UserController();
$view = new UserTemplateView();

session_start();

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Accueil</title>
</head>


<body>
    <div>
        <?php
        
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section>
                <div id="carousselControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="../../public/images/image1.jpg" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../../public/images/image2.jpg" alt="Second slide">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousselControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousselControls"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>
            <section id="categories">
                <div class="title-container">
                    <h2>Nos catégories</h2>
                </div>
                <?php
                $categories = $controller->getCategories();
                foreach ($categories as $row) {
                ?>
                <div class="categorie">
                    <div class="categorie-header">
                        <h4><?php echo utf8_encode($row['nom']) ?></h4>
                        <p>
                            <a href=<?php
                    echo "../Categorie/Categorie.php?categorie=" . $row['idCategorie'];
                            ?>
                            >
                                afficher plus
                            </a>
                        </p>
                    </div>
                    <div class="cadres-container">
                        <div class="left-swipe-btn swipe-buttons">
                            <ion-icon name="chevron-back-outline"></ion-icon>
                        </div>
                        <div class="right-swipe-btn swipe-buttons">
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </div>
                        <div class="horizental-scroll">
                            <?php
                    $recettes = $controller->getRecetteByCategorie($row['idCategorie'], 10);
                    foreach ($recettes as $row) {
                        $view->showCadre($row['titre'], $row['description'], $row['image'], $row['idRecette']);
                    }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </section>
        </div>
    </div>
    <?php
    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>