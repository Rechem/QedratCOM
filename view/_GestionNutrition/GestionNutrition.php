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

if (isset($_POST['delete'])) {
    $controller->deleteIngredient($_POST['id']);
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Gestion de la nutrition</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion']?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Nutrition</h4>
                    <h5>
                        <a href="AjouterIngredient.php" class="text-button">Ajouter un ingrédient</a>
                    </h5>
                </div>
                <table data-toggle="table" id="nutrition-table" data-search="true"> 
                    <thead>
                        <th data-sortable="true">Id</th>
                        <th data-sortable="true">Ingrédient</th>
                        <th data-sortable="true">Calories (100g)</th>
                        <th data-sortable="true">Glucides</th>
                        <th data-sortable="true">Lipides</th>
                        <th data-sortable="true">Minéreaux</th>
                        <th data-sortable="true">Vitamines</th>
                        <th data-sortable="true">Healthy</th>
                        <th data-sortable="true">Saison</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $ingredients = $controller->getIngredients();
                        foreach ($ingredients as $rows) {
                            echo "<tr>";

                            echo "<td>" . $rows['idIngredient'] . "</td>";
                            echo "<td>" . utf8_encode($rows['nomIngredient']) . "</td>";
                            echo "<td>" . $rows['calories'] . "</td>";
                            echo "<td>" . $rows['glucides'] . "</td>";
                            echo "<td>" . $rows['lipides'] . "</td>";
                            echo "<td>" . $rows['mineraux'] . "</td>";
                            echo "<td>" . utf8_encode(empty($rows['vitamines']) ? '-' : $rows['vitamines']) . "</td>";
                            echo "<td>" . ($rows['isHealthy'] == 1 ? 'Oui' : 'Non') . "</td>";
                            echo "<td>" . utf8_encode(empty($rows['nomSaison']) ? '-' : $rows['nomSaison']) . "</td>";
                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly" style="gap:0.5rem;">
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $rows['idIngredient']; ?>">
                                        <a href="AjouterIngredient.php?edit=<?php echo $rows['idIngredient']; ?>"
                                        class="text-decoration-none icon-btn">
                                            <ion-icon name="pencil"></ion-icon>
                                        </a>
                                        <button class="plain-submit-btn icon-btn" type="submit" name="delete">
                                            <ion-icon name="trash"></ion-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <?php

                            echo "</tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>