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
    <title>Nutritions</title>
</head>

<body>
    <div>
        <?php

        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section id="nutrition-section" class="section-padding bg-white">
                <h3 class="mb-5">Nutritions</h3>
                <table data-toggle="table" id="nutrition-table" data-search="true">
                    <thead>
                        <th data-sortable="true">Ingrédient</th>
                        <th data-sortable="true">Calories (100g)</th>
                        <th data-sortable="true">Glucides</th>
                        <th data-sortable="true">Lipides</th>
                        <th data-sortable="true">Minéreaux</th>
                        <th data-sortable="true">Vitamines</th>
                        <th data-sortable="true">Healthy</th>
                        <th data-sortable="true">Saison</th>
                    </thead>
                    <tbody>
                        <?php
                        $ingredients = $controller->getIngredients();
                        foreach ($ingredients as $rows) {
                            echo "<tr>";

                            echo "<td>" . utf8_encode($rows['nomIngredient']) . "</td>";
                            echo "<td>" . $rows['calories'] . "</td>";
                            echo "<td>" . $rows['glucides'] . "</td>";
                            echo "<td>" . $rows['lipides'] . "</td>";
                            echo "<td>" . $rows['mineraux'] . "</td>";
                            echo "<td>" . utf8_encode(empty($rows['vitamines']) ? '-' : $rows['vitamines']) . "</td>";
                            echo "<td>" . ($rows['isHealthy'] == 1 ? 'Oui' : 'Non') . "</td>";
                            echo "<td>" . utf8_encode(empty($rows['nomSaison']) ? '-' : $rows['nomSaison']) . "</td>";

                            echo "</tr>";
                        } ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter($controller);
    $view->showScripts();
    ?>
</body>

</html>