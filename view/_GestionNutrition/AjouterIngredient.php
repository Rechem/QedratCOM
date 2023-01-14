<!DOCTYPE html>
<html lang="en">
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if (!$controller->isAdmin($_SESSION['id'] ?? -1)) {
    header('location: ../Home/Home.php');
}

$ingredient;

if (isset($_GET['edit'])) {
    $ingredient = $controller->getIngredientById($_GET['edit']);
    if (!$ingredient)
        header('location: ./GestionNutrition.php');
}

if (isset($_POST['submit'])) {
    $isHealthy = 0;
    if (isset($_POST['isHealthy'])) {
        $isHealthy = 1;
    }
    $controller->ajouterIngredient(
        $_POST['nom'], $_POST['calories'],
        empty($_POST['glucides']) ? 0 : $_POST['glucides'],
        empty($_POST['lipides']) ? 0 : $_POST['lipides'],
        empty($_POST['mineraux']) ? 0 : $_POST['mineraux'],
        empty($_POST['vitamines']) ? 0 : $_POST['vitamines'],
        $isHealthy,
        $_POST['saison']
    );

    header('location: ./GestionNutrition.php');
}else if (isset($_POST['update'])){
    $isHealthy = 0;

    if (isset($_POST['isHealthy'])) {
        $isHealthy = 1;
    }

    $controller->modifierIngredient($ingredient['idIngredient'],
        $_POST['nom'], $_POST['calories'],
        empty($_POST['glucides']) ? 0 : $_POST['glucides'],
        empty($_POST['lipides']) ? 0 : $_POST['lipides'],
        empty($_POST['mineraux']) ? 0 : $_POST['mineraux'],
        empty($_POST['vitamines']) ? 0 : $_POST['vitamines'],
        $isHealthy,
        $_POST['saison']
    );
    header('location: ./GestionNutrition.php');
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Ajouter un ingrédient</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Ajouter un ingrédient</h4>
                <form action="" method="post" id="add-ingredient-form">
                    <?php if (isset($_GET['edit'])) {
                        echo "<input type=\"hidden\" name=\"id\" value=\"" . $ingredient['idIngredient'] . "\"";
                    } ?>
                    <h5 class="mb-1">Nom</h5>
                    <input type="text" name="nom" class="mb-3" required <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['nom']) . "\"";
                    } ?>>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5>Saison</h5>
                        <select name="saison" required>
                            <option value hidden>Choisir saison</option>
                            <option value="NULL">aucune</option>
                            <?php
                            $saisons = $controller->getSaisons();
                            foreach ($saisons as $row) {
                                ?>
                                <option value="<?php echo $row['idSaison'] ?>" <?php if (
                                       isset($ingredient['idSaison'])
                                       && $ingredient['idSaison'] == $row['idSaison']
                                   )
                                       echo "selected"; ?>>
                                    <?php echo utf8_encode($row['nom']) ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <hr>
                    <h5 class="mb-1">Calories (100g)</h5>
                    <input type="number" name="calories" class="mb-3" required <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['calories']) . "\"";
                    } ?>>
                    <h5 class="mb-1">Glucides</h5>
                    <input type="number" name="glucides" class="mb-3" <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['glucides']) . "\"";
                    } ?>>
                    <h5 class="mb-1">Lipides</h5>
                    <input type="number" name="lipides" class="mb-3" <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['lipides']) . "\"";
                    } ?>>
                    <h5 class="mb-1">Minéreaux</h5>
                    <input type="number" name="mineraux" class="mb-3" <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['mineraux']) . "\"";
                    } ?>>
                    <h5 class="mb-1">Vitamines</h5>
                    <input type="text" name="vitamines" <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($ingredient['vitamines']) . "\"";
                    } ?>>
                    <hr>
                    <div class="d-flex align-items-center" style="gap: 0.25rem;">
                        <input type="checkbox" name="isHealthy" id="isHealthy" <?php if ($ingredient['isHealthy'])
                            echo "checked"; ?>>
                        <label for="isHealthy">Ingrédient healthy</label>
                    </div>
                    <button type="submit" class="cta-btn trouver-recette" 
                    <?php if (isset($_GET['edit']))
                        echo "name=\"update\"";
                    else
                        echo "name=\"submit\""; ?>>
                        Ajouter
                    </button>
                </form>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>