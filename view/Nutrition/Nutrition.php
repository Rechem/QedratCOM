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
                <table>
                    <thead>
                        <th>Ingrédient</th>
                        <th>Calories (/100g)</th>
                        <th>Glucides</th>
                        <th>Lipides</th>
                        <th>Minéreaux</th>
                        <th>Vitamines</th>
                        <th>Healthy</th>
                        <th>Saison</th>
                    </thead>
                    <tbody>
                        <?php
                        $ingredients = $controller->getIngredients();
                        foreach($ingredients as $rows){
                            echo "<tr>";
                            
                            echo "<td>".utf8_encode($rows['nomIngredient'])."</td>";
                            echo "<td>".$rows['calories']."</td>";
                            echo "<td>".$rows['glucides']."</td>";
                            echo "<td>".$rows['lipides']."</td>";
                            echo "<td>".$rows['mineraux']."</td>";
                            echo "<td>".utf8_encode(empty($rows['vitamines']) ?'-' : $rows['vitamines'])."</td>";
                            echo "<td>".($rows['isHealthy'] == 1 ? 'Oui' : 'Non')."</td>";
                            echo "<td>".utf8_encode(empty($rows['nomSaison']) ?'-' : $rows['nomSaison'])."</td>";
                            
                            echo "</tr>";
                        } ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
<?php

$view->showFooter();
$view->showScripts();
?>

</html>