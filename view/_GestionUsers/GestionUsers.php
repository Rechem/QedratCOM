<!DOCTYPE html>
<html lang="en">
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if(!$controller->isAdmin($_SESSION['id'] ?? -1)){
    header('location: ../Home/Home.php');
}

if (isset($_POST['confirm'])){
    $controller->changeUserStatus($_POST['id'], 1);
} else if (isset($_POST['ban'])) {
    $controller->changeUserStatus($_POST['id'], 3);
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Gestion des utilisateurs</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion']?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Utilisateurs</h4>
                <table data-toggle="table" id="nutrition-table">
                    <thead>
                        <th data-sortable="true">idUser</th>
                        <th data-sortable="true">Nom</th>
                        <th data-sortable="true">Prénom</th>
                        <th data-sortable="true">Mail</th>
                        <th data-sortable="true">Sexe</th>
                        <th data-sortable="true">Status</th>
                        <th data-sortable="true">Role</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $users = $controller->getUsers();
                        foreach ($users as $row) {
                            echo "<tr>";

                            echo "<td>" . $row['idUser'] . "</td>";
                            echo "<td>" . utf8_encode($row['nom']) . "</td>";
                            echo "<td>" . utf8_encode($row['prenom']) . "</td>";
                            echo "<td>" . utf8_encode($row['mail']) . "</td>";
                            echo "<td>" . utf8_encode($row['sexe']) . "</td>";
                            echo "<td>" . utf8_encode($row['nomStatus']) . "</td>";
                            echo "<td>" . utf8_encode($row['nomRole']) . "</td>";

                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly">
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                    <input type="hidden" name="id" value="<?php echo $row['idUser']; ?>">
                                        <button class="plain-submit-btn icon-btn" type="submit"
                                        name="ban">
                                            <ion-icon name="ban-outline"></ion-icon>
                                        </button>
                                        <?php if ($row['idStatusUtilisateur'] != 1) { ?>
                                            <button class="plain-submit-btn icon-btn" type="submit"
                                            name="confirm">
                                                <ion-icon name="checkmark"></ion-icon>
                                            </button>
                                        <?php } ?>
                                    </form>
                                    <a href="User.php?id=<?php echo $row['idUser'] ?>"
                                        target="_blank" class="text-decoration-none icon-btn">
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