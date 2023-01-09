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
    <title>Ajouter une recette</title>
</head>


<body>
    <div>
        <?php
        if (isset($_POST['submit'])) {
            if (!isset($_SESSION) || !isset($_SESSION['id']))
                return;
            $ingredientsArr = [];
            for ($i = 0; $i < count($_POST['ingredients']); $i++) {
                array_push(
                    $ingredientsArr,
                    [
                        'idIngredient' => $_POST['ingredients'][$i],
                        'quantite' => $_POST['quantite'][$i],
                        'idUnite' => $_POST['unite'][$i]
                    ]
                );
            }

            $etapesArr = preg_split('/\n\s*\n/', $_POST['etapes']);
            $etapesArr = array_filter($etapesArr, function ($e) {
                return trim($e) != '\n' && !empty(trim($e));
            });

            $etapesArr = array_map(function ($e) {
                return str_replace("\r\n", "", $e);
            }, $etapesArr);

            $controller->ajouterRecette(
                $_SESSION['id'],
                $_POST['nom'], $_POST['description'], $_POST['difficulte'],
                $_POST['temps-preparation'], $_POST['temps-cuisson'], $_POST['temps-repos'], $_FILES['image'],
                $_FILES['video'],
                $ingredientsArr,
                $etapesArr, $_POST['isHealthy']
            );
        }

        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <h3>Ajouter une recette</h3>
                <form action="" method="post" id="add-recette-form" enctype="multipart/form-data">
                    <h5 class="mb-1">Nom recette</h5>
                    <input type="text" name="nom" class="mb-3" required>
                    <h5 class="mb-1">Description</h5>
                    <textarea name="description" rows="3" class="mb-2" required></textarea>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5>Difficulté</h5>
                        <select name="difficulte" required>
                            <option value hidden>Choisir difficulté</option>
                            <?php
                            $difficultes = $controller->getDifficultes();
                            foreach ($difficultes as $row) {
                                ?>
                                <option value="<?php echo $row['idDifficulte'] ?>">
                                    <?php echo $row['nom'] ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <hr>
                    <h5 class="">Temps</h5>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-4 col p-2">
                                <div class="temps">
                                    <p class="mb-1">Préparation</p>
                                    <input type="number" name="temps-preparation" required>
                                </div>
                            </div>
                            <div class="col-sm-4 col p-2">
                                <div class="temps">
                                    <p class="mb-1">Cuisson</p>
                                    <input type="number" name="temps-cuisson" required>
                                </div>
                            </div>
                            <div class="col-sm-4 col p-2">
                                <div class="temps">
                                    <p class="mb-1">Repos</p>
                                    <input type="number" name="temps-repos" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between my-3">
                        <h5>Image</h5>
                        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5>Vidéo</h5>
                        <input type="file" name="video" accept="video/">
                    </div>
                    <hr>
                    <h5 class="my-2">Ingrédients</h5>
                    <input type="text" placeholder="Rechercher ingrédient" id="search-phrase">
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
                    <div class="ingredients mt-2">
                        <div class="container">
                            <?php if (isset($_GET['ingredient'])) {
                                $ingredient = $controller->getIngredientsByIds($_GET['ingredient'])
                                    ?>
                                <div class="d-flex align-items-center justify-content-between ingredient"
                                    data-id="<?php echo $ingredient[0]['idIngredient'] ?>">
                                    <input type="hidden" name="ingredients[]"
                                        value="<?php echo utf8_encode($ingredient[0]['idIngredient']) ?>">
                                    <div class="row" style="flex-grow: 1;">
                                        <div class="col-sm-4 col p-2 d-flex align-items-center">
                                            <h6>
                                                <?php echo utf8_encode($ingredient[0]['nom']) ?>
                                            </h6>
                                        </div>
                                        <div class="col-sm-4 col p-2">
                                            <input type="number" name="quantite[]" placeholder="Quantité" required>
                                        </div>
                                        <div class="col-sm-4 col p-2 d-flex align-items-center">
                                            <select name="unite[]" style="width: 100%; height: 100%;" required>
                                                <option value hidden>Unité</option>
                                                <?php
                                                $unites = $controller->getUnites();
                                                foreach ($unites as $row) {
                                                    ?>
                                                    <option value="<?php echo $row['idUnite'] ?>">
                                                        <?php echo empty($row['nom']) ? 'u' : utf8_encode($row['nom']) ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div role="button" style="width: 2rem; margin-left: 1rem; font-size: 1.5rem;"
                                        class="d-flex justify-content-center remove-ig-recette">
                                        <ion-icon name="close-outline"></ion-icon>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <hr>
                    <h5 class="my-2">Etapes</h5>
                    <h6 class="my-2">Séparer les étapes par un double saut</h6>
                    <textarea name="etapes" rows="3" class="mb-2" required></textarea>
                    <h5 class="my-2">Aperçu des étapes</h5>
                    <h6 id="apercu-etapes" class="lh-base"></h6>
                    <hr>
                    <div>
                        <input type="checkbox" name="isHealthy" id="isHealthy">
                        <label for="isHealthy">Recette healthy</label>
                    </div>
                    <?php
                    if (!isset($_SESSION) || !isset($_SESSION['id'])) {
                        ?>
                        <h4 class="mt-5 text-center">Connectez-vous pour ajouter cette recette</h4>
                        <?php
                    } else {
                        ?>
                        <button type="submit" class="cta-btn trouver-recette" name="submit">
                            Ajouter
                        </button>
                        <?php

                    }
                    ?>
                </form>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>