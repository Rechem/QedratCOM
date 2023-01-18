<!DOCTYPE html>
<html lang="en">
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if (!$controller->isAdmin($_SESSION['id'])) {
    header('location: ../Home/Home.php');
}

if (isset($_POST['edit'])) {
    // inchallah
} else if (isset($_POST['delete'])) {
    $controller->deleteRecetteById($_POST['id']);
} else if (isset($_POST['publish'])) {
    $controller->publishRecette($_POST['id']);
} else if (isset($_POST['hide'])) {
    $controller->hideRecette($_POST['id']);
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Gestion des recettes</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Recettes</h4>
                    <h5>
                        <a href="AjouterRecette.php" class="text-button">Ajouter une recette</a>
                    </h5>
                </div>
                <table id="admin-recette-table" data-toggle="table" data-search="true">
                    <thead>
                        <th data-sortable="true">Id</th>
                        <th data-sortable="true">User</th>
                        <th data-sortable="true">Catégorie</th>
                        <th data-sortable="true">Titre</th>
                        <th data-sortable="true">Difficulté</th>
                        <th data-sortable="true">Temps total</th>
                        <th data-sortable="true">Calories</th>
                        <th data-sortable="true">Healthy</th>
                        <th data-sortable="true">Saison</th>
                        <th data-sortable="true">Note</th>
                        <th data-sortable="true">Etat</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $recettes = $controller->getAllRecettes();
                        foreach ($recettes as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['idRecette'] . "</td>";
                            echo "<td>" . $row['idUser'] . "</td>";
                            echo "<td>" . utf8_encode($row['nomCategorie']) . "</td>";
                            echo "<td>" . utf8_encode($row['titre']) . "</td>";
                            echo "<td>" . utf8_encode($row['nomDifficulte']) . "</td>";
                            echo "<td>" . $view->formatTime($row['tempsTotal']) . "</td>";
                            echo "<td>" . floatval(preg_replace('/[^\d.]/', '', number_format($row['calories'], 1))) . "</td>";
                            echo "<td>" . ($row['isHealthy'] == 1 ? 'Oui' : 'Non') . "</td>";
                            echo "<td>" . utf8_encode(empty($row['nomSaison']) ? '-' : $row['nomSaison']) . "</td>";
                            echo "<td>" . $row['note'] . " (" . $row['avis'] . ")" . "</td>";
                            echo "<td>" . utf8_encode($row['etat']) . "</td>";
                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly" style="gap:0.5rem;">
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $row['idRecette']; ?>">
                                        <button class="plain-submit-btn icon-btn" type="submit" name="edit">
                                            <ion-icon name="pencil"></ion-icon>
                                        </button>
                                        <button class="plain-submit-btn icon-btn" type="submit" name="delete">
                                            <ion-icon name="trash"></ion-icon>
                                        </button>
                                        <?php if ($row['idEtat'] == 2) { ?>
                                            <button class="plain-submit-btn icon-btn" type="submit" name="publish">
                                                <ion-icon name="checkmark-circle"></ion-icon>
                                            </button>
                                        <?php } else if ($row['idEtat'] == 1) { ?>
                                                <button class="plain-submit-btn icon-btn" type="submit" name="hide">
                                                    <ion-icon name="lock-closed"></ion-icon>
                                                </button>
                                        <?php } else {
                                            ?>
                                                <button class="plain-submit-btn icon-btn" type="submit" name="publish">
                                                    <ion-icon name="lock-open"></ion-icon>
                                                </button>
                                            <?php
                                        } ?>
                                    </form>
                                    <a href="../Recette/Recette.php?id=<?php echo $row['idRecette'] ?>" target="_blank"
                                        class="text-decoration-none icon-btn">
                                        <ion-icon name="eye"></ion-icon>
                                    </a>
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