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

if(isset($_POST['edit'])){
    // inchallah
} else if (isset($_POST['delete'])) {
    $controller->deleteNews($_POST['id']);
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Gestion des NEWS</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar();
        ?>
        <div class="containerZ">
            <div class="content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>NEWS</h4>
                    <h5>
                        <a href="AjouterNews.php" class="text-button">Ajouter une NEWS</a>
                    </h5>
                </div>
                <table id="admin-recette-table" data-toggle="table">
                    <thead>
                        <th data-sortable="true">Id</th>
                        <th data-sortable="true">Titre</th>
                        <th data-sortable="true">Contenu</th>
                        <th data-sortable="true">Date et heure</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $news = $controller->getNews();
                        foreach ($news as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['idNews'] . "</td>";
                            echo "<td>" . utf8_encode($row['titre']) . "</td>";
                            echo "<td>" . utf8_encode($row['corps']) . "...</td>";
                            $phpdate = strtotime($row['date']);
                            $mysqldate = date(' d/m/Y H:i', $phpdate);
                            echo "<td>" . $mysqldate . "</td>";
                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly" style="gap:0.5rem;">
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $row['idNews']; ?>">
                                        <button class="plain-submit-btn icon-btn" type="submit" name="edit">
                                            <ion-icon name="pencil"></ion-icon>
                                        </button>
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