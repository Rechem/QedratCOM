<?php
class UserTemplateView
{
    public function showSocialLinks()
    {
        ?>
        <div id="social-links">
            <a href="#">
                <p>
                    Facebook
                </p>
            </a>
            <a href="#">
                <p>
                    Instagram
                </p>
            </a>
            <a href="#">
                <p>
                    Twitter
                </p>
            </a>
        </div>
    <?php
    }
    public function showHeader()
    {
        ?>
        <header>
            <div id="modal-container">
                <div id="modal">
                    <img src="../../public/icons/add_black.svg" alt="close-btn" id="close-icon">
                    <h3>Se connecter</h3>
                    <form action="POST">
                        <h6>
                            E-mail
                        </h6>
                        <input type="email" name="username">
                        <h6>
                            Mot de passe
                        </h6>
                        <input type="password" name="password">
                        <input type="submit" value="Connexion" class="cta-btn">
                    </form>
                    <button class="text-button" id="toggle-login-btn">
                        Nouveau utilisateur ? s'inscrire
                    </button>
                </div>
            </div>
            <div class="boundary">
                <div id="logo-login">
                    <div id="logo">
                        <h4>QedratCom</h4>
                    </div>
                    <button id="login-btn">
                        Se connecter
                    </button>
                </div>
                <nav>
                    <a href="../Home/Home.php">
                        <h6>
                            Accueil
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            News
                        </h6>
                    </a>
                    <a href="../IdeeRecette/IdeeRecette.php">
                        <h6>
                            Idées
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Healthy
                        </h6>
                    </a>
                    <a href="../Saison/Saison.php">
                        <h6>
                            Saisons
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Fetes
                        </h6>
                    </a>
                    <a href="../Nutrition/Nutrition.php">
                        <h6>
                            Nutritions
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Contact
                        </h6>
                    </a>
                </nav>
            </div>
        </header>
    <?php
    }

    public function showCadre($titre, $description, $image, $id)
    {
        ?>
        <div class="cadre">
            <a href=<?php echo "../Recette/Recette.php?id=" . $id ?>>
                <div class="recette-text-cadre">
                    <div class="recette-text">
                        <h5>
                            <?php echo utf8_encode($titre) ?>
                        </h5>
                        <p><?php echo utf8_encode($description) ?></p>
                    </div>
                </div>
                <div class="recette-cadre">
                    <img src=<?php echo "../.." . $image; ?> alt="image recette">
                </div>
            </a>
        </div>
    <?php
    }

    public function showFooter()
    {
        ?>
        <footer>
            <div class="boundary">asas</div>
        </footer>
    <?php
    }
    public function showScripts()
    {
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script src="../../jquery-3.6.1.min.js"></script>
        <script src="../script.js"></script>
    <?php
    }

    public function showCSS()
    {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="../style.css">
    <?php
    }

    public function formatTime(int $minutes)
    {
        if ($minutes == 0) {
            return "-";
        } else if ($minutes < 60) {
            return $minutes . " minute";
        } else {
            if ($minutes % 60 == 0)
                return (int) ($minutes / 60) . " heure(s)";
            else
                return (int) ($minutes / 60) . " heure(s) " . ($minutes % 60) . " minute";
        }
    }

    public function showFilterMenu($getObject, $controller, $fixedCategorie = -1){

        ?>
<div class="accordion accordion-flush" id="accordionFlush">
                    <div id="filtrer-trier" class="accordion-item">
                        <div class="accordion-header collapsed p-3 cursor-pointer d-flex justify-content-between align-items-center"
                            id="accordion-title" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                            aria-expanded="false" aria-controls="flush-collapseOne" role="button">
                            <h5 style="font-weight: 500;">
                                Filtrer et trier
                            </h5>
                            <ion-icon name="chevron-down-outline"></ion-icon>
                        </div>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                            aria-labelledby="accordion-title" data-bs-parent="#accordionFlush">
                            <form method="get" id="filter-sort-form">
                                <div id="filtrer-trier-content" class="accordion-body">
                                    <div id="filtrer">
                                        <?php
                                        if ($fixedCategorie != -1) {
                                            echo "<input type=\"hidden\" name=\"categorie\" value=\"$fixedCategorie\">";
                                        } else {
                                            echo "<input type=\"hidden\" name=\"ingredients\" value=\"".$_GET['ingredients']."\">";
                                            ?>
                                            <div>
                                                <h5 class="mb-2">Catégorie</h5>
                                                <?php
                                                $categories = $controller->getCategories();
                                                foreach ($categories as $row) {
                                                    ?>
                                                    <input type="checkbox" id="<?php echo 'categorie-' . $row['idCategorie']."\"";
                                                    if (isset($getObject['categorie']) && in_array($row['idCategorie'], $getObject['categorie']))
                                                    echo "checked";
                                                        ?>
                                                        class="me-1" name="categorie[]" value=<?php echo $row['idCategorie'] ?>>
                                                    <label for=<?php echo 'categorie-' . $row['idCategorie'] ?>>
                                                        <?php echo utf8_encode($row['nom']) ?>
                                                    </label><br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php } ?>
                                        <div>
                                            <h5 class="mb-2">Saison</h5>
                                            <?php
                                            $saisons = $controller->getSaisons();
                                            foreach ($saisons as $row) {
                                                ?>
                                                <input type="checkbox" id="<?php echo 'saison-' . $row['idSaison']. "\"";
                                                if (isset($getObject['saison']) && in_array($row['idSaison'], $getObject['saison']))
                                                    echo "checked";
                                                        ?> class="me-1" name="saison[]"
                                                    value=<?php echo $row['idSaison'] ?>>
                                                <label for=<?php echo 'saison-' . $row['idSaison'] ?>>
                                                    <?php echo utf8_encode($row['nom']) ?>
                                                </label><br>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Temps total</h5>
                                            <input type="radio" id="total-2heure" class="me-1" name="total"
                                            <?php if (isset($getObject['total']) && $getObject['total'] == "120,9999")
                                                    echo "checked"; ?>
                                                value="120,9999">
                                            <label for="total-2heure">Plus de 2 heures</label><br>
                                            <input type="radio" id="total-1-2heure" class="me-1" name="total"
                                            <?php if (isset($getObject['total']) && $getObject['total'] == "60,120")
                                                    echo "checked"; ?>
                                                value="60,120">
                                            <label for="total-1-2heure">1 heure - 2 heures</label><br>
                                            <input type="radio" id="total-30-1heure" class="me-1" name="total"
                                            <?php if (isset($getObject['total']) && $getObject['total'] == "30,60")
                                                    echo "checked"; ?>
                                                value="30,60">
                                            <label for="total-30-1heure">30 min - 1 heure</label><br>
                                            <input type="radio" id="total-30min" class="me-1" name="total"
                                            <?php if (isset($getObject['total']) && $getObject['total'] == "0,30")
                                                    echo "checked"; ?>
                                                    value="0,30">
                                            <label for="total-30min">Moin de 30 min</label>
                                            <button class="text-button mt-2" id="clear-total">
                                                <h6>
                                                    Effacer la sélection
                                                </h6>
                                </button>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Difficulté</h5>
                                            <?php
                                            $difficultes = $controller->getDifficultes();
                                            foreach ($difficultes as $row) {
                                                ?>
                                                <input type="checkbox" id="<?php echo 'difficulte-' . $row['idDifficulte']."\"" ?>
                                                <?php if (isset($getObject['difficulte']) && in_array($row['idDifficulte'], $getObject['difficulte']))
                                                    echo "checked";
                                                        ?>
                                                    class="me-1" name="difficulte[]" value=<?php echo $row['idDifficulte'] ?>>
                                                <label for=<?php echo 'difficulte-' . $row['idDifficulte'] ?>>
                                                    <?php echo utf8_encode($row['nom']) ?>
                                                </label><br>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">Notation</h5>
                                            <input type="checkbox" id="notation-4-5" class="me-1" name="notation[]"
                                            <?php if (isset($getObject['notation']) && in_array("4,5", $getObject['notation']))
                                                    echo "checked"; ?>
                                                value="4,5">
                                            <label for="notation-4-5">≥4</label><br>
                                            <input type="checkbox" id="notation-3-4" class="me-1" name="notation[]"
                                            <?php if (isset($getObject['notation']) && in_array("3,4", $getObject['notation']))
                                                    echo "checked"; ?>
                                                value="3,4">
                                            <label for="notation-3-4">3-4</label><br>
                                            <input type="checkbox" id="notation-2-3" class="me-1" name="notation[]"
                                            <?php if (isset($getObject['notation']) && in_array("2,3", $getObject['notation']))
                                                    echo "checked"; ?>
                                                value="2,3">
                                            <label for="notation-2-3">2-3</label><br>
                                            <input type="checkbox" id="notation-1-2" class="me-1" name="notation[]"
                                            <?php if (isset($getObject['notation']) && in_array("1,2", $getObject['notation']))
                                                    echo "checked"; ?>
                                                value="1,2">
                                            <label for="notation-1-2">1-2</label><br>
                                            <input type="checkbox" id="notation-0-1" class="me-1" name="notation[]"
                                            <?php if (isset($getObject['notation']) && in_array("0,1", $getObject['notation']))
                                                    echo "checked"; ?>
                                                value="0,1">
                                            <label for="notation-0-1">&lt;1</label>
                                        </div>
                                        <div>
                                            <h5 class="mb-3">Healthy</h5>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" id="healthy-checkbox" class="me-2" name="healthy"
                                                <?php if (isset($getObject['healthy']))
                                                    echo "checked"; ?>
                                                    value="1">
                                                <label for="healthy-checkbox">Plats Healthy uniquement</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trier">
                                        <label for="sortBy">Trier par :</label> <br>
                                        <select name="sortBy" id="sortBy" form="filter-sort-form" class="mb-3">
                                            <option hidden value
                                            <?php if (!isset($getObject['sortBy']))
                                                    echo "selected"; ?>
                                            > Aucun </option>
                                            <option value="notation"
                                            <?php if (isset($getObject['sortBy']) && $getObject['sortBy'] == 'notation')
                                                    echo "selected"; ?>
                                                    >Notation</option>
                                            <option value="calories"
                                            <?php if (isset($getObject['sortBy']) && $getObject['sortBy'] == 'calories')
                                                    echo "selected"; ?>
                                                    >Calories</option>
                                            <option value="tempsTotal"
                                            <?php if (isset($getObject['sortBy']) && $getObject['sortBy'] == 'tempsTotal')
                                                    echo "selected"; ?>
                                                    >Temps total</option>
                                            <option value="tempsPreparation"
                                            <?php if (isset($getObject['sortBy']) && $getObject['sortBy'] == 'tempsPreparation')
                                                    echo "selected"; ?>
                                                    >Temps de préparation</option>
                                            <option value="tempsCuisson"
                                            <?php if (isset($getObject['sortBy']) && $getObject['sortBy'] == 'tempsCuisson')
                                                    echo "selected"; ?>
                                                    >Temps de cuisson</option>
                                        </select>

                                        <label for="orderBy">Ordre :</label> <br>
                                        <select name="orderBy" id="orderBy" form="filter-sort-form">
                                            <option hidden value
                                            <?php if (!isset($getObject['orderBy']))
                                                    echo "selected"; ?>> Aucun </option>
                                            <option value="asc"
                                            <?php if (isset($getObject['orderBy']) && $getObject['orderBy'] == 'asc')
                                                    echo "selected"; ?>
                                            >Croissant</option>
                                            <option value="desc"
                                            <?php if (isset($getObject['orderBy']) && $getObject['orderBy'] == 'desc')
                                                    echo "selected"; ?>
                                            >Décroissant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="btns">
                                    <div class="me-3">
                                        <button class="text-button mt-1" id="clear-form">
                                            Réinitialiser
                                        </button>
                                    </div>
                                    <input type="submit" value="Appliquer" class="cta-btn py-2">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        <?php

    }
}
?>