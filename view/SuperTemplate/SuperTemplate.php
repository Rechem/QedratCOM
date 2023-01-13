<?php

abstract class SuperTemplateView
{

    public function showAjouterRecetteForm($controller, $idUser = -1, $rechercher, $ignorer)
    {
        ?>

        <form action="" method="post" id="add-recette-form"
        enctype="multipart/form-data">
            <h5 class="mb-1">Nom recette</h5>
            <input type="text" name="nom" class="mb-3" required>
            <h5 class="mb-1">Description</h5>
            <textarea name="description" rows="3" class="mb-2" required></textarea>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5>Catégorie</h5>
                <select name="categorie" required>
                    <option value hidden>Choisir catégorie</option>
                    <?php
                    $categories = $controller->getCategories();
                    foreach ($categories as $row) {
                        ?>
                        <option value="<?php echo $row['idCategorie'] ?>">
                            <?php echo utf8_encode($row['nom']) ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <hr>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5>Difficulté</h5>
                <select name="difficulte" required>
                    <option value hidden>Choisir difficulté</option>
                    <?php
                    $difficultes = $controller->getDifficultes();
                    foreach ($difficultes as $row) {
                        ?>
                        <option value="<?php echo $row['idDifficulte'] ?>">
                            <?php echo utf8_encode($row['nom']) ?>
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
                if (isset($rechercher)) {
                    if (isset($ignorer))
                        $ingredients = $controller->getIngredientsByName($rechercher, $ignorer);
                    else
                        $ingredients = $controller->getIngredientsByName($rechercher);
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
            <h5 class="my-2">Fêtes</h5>
            <div class="d-flex flex-wrap" style="gap : 1rem">
                <?php
                $fetes = $controller->getFetes();
                foreach ($fetes as $row) {
                    ?>
                    <div class="d-flex align-items-center">
                        <input type="checkbox" id="<?php echo 'fete-' . $row['idFete']; ?>" ; class="me-1" name="fete[]" value=<?php echo $row['idFete'] ?>>
                        <label for=<?php echo 'fete-' . $row['idFete'] ?>>
                            <?php echo utf8_encode($row['nom']) ?>
                        </label>
                    </div>
                    <?php
                }
                ?>
            </div>
            <hr>
            <div class="d-flex align-items-center" style="gap: 0.25rem;">
                <input type="checkbox" name="isHealthy" id="isHealthy">
                <label for="isHealthy">Recette healthy</label>
            </div>
            <?php
            if ($idUser == -1) {
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
        <?php
    }
}

?>