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
    $controller->supprimerDiapo($_POST['id']);
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Paramètres</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Diaporama</h4>
                    <h5>
                        <a href="AjouterDiaporama.php" class="text-button">Ajouter une diaporama</a>
                    </h5>
                </div>
                <table data-toggle="table" id="nutrition-table" data-search="true">
                    <thead>
                        <th data-sortable="true">Lien</th>
                        <th data-sortable="true">Image</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $diapos = $controller->getDiapos();
                        foreach ($diapos as $row) {
                            echo "<tr>";

                            echo "<td>" . utf8_encode($row['lien']) . "</td>";
                            echo "<td>" . utf8_encode($row['image']) . "</td>";
                            ;

                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly">
                                    <a href="../../view<?php echo $row['lien'] ?>" target="_blank"
                                        class="text-decoration-none icon-btn">
                                        <ion-icon name="eye"></ion-icon>
                                    </a>
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $row['idDiaporama']; ?>">
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
                <hr>
                <div class="d-flex justify-content-between align-items-center my-4">
                    <?php
                    $percentage = $controller->getPercentage();
                    ?>
                    <h4>Pourcentage idee recette : <?php echo $percentage; ?> </h4>
                    <h5>
                        <a href="ModifierPourcentage.php" class="text-button">Modifier</a>
                    </h5>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>