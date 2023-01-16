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
    $controller->supprimerMessage($_POST['id']);
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Messages</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">Messages</h4>
                <table data-toggle="table" id="nutrition-table" data-search="true">
                    <thead>
                        <th data-sortable="true">Nom et prénom</th>
                        <th data-sortable="true">E-mail</th>
                        <th data-sortable="true">Message</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $messages = $controller->getMessages();
                        foreach ($messages as $row) {
                            echo "<tr>";

                            echo "<td>" . utf8_encode($row['nomprenom']) . "</td>";
                            echo "<td>" . utf8_encode($row['email']) . "</td>";
                            echo "<td>" . utf8_encode($row['message']) . "</td>";
                            ;

                            ?>
                            <td style="font-size: 1.2rem">
                                <div class="d-flex justify-content-evenly">
                                    <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                        <input type="hidden" name="id" value="<?php echo $row['idMessage']; ?>">
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