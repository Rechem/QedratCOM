<!DOCTYPE html>
<?php
require_once "../_AdminTemplate/AdminTemplateView.php";
require_once "../../controller/AdminController.php";
$view = new AdminTemplateView();
$controller = new AdminController();
session_start();

if ($controller->isAdmin($_SESSION['id'] ?? -1)) {
    header('location: ../_GestionRecette/GestionRecette.php');
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Connexion admin</title>
</head>

<body>
    <div id="admin-login-div">
        <div id="admin-login-title" class="mb-5">
            <div id="logo" style="height: 4rem;" class="mb-4">
                <img src="../../public/images/logo.png" alt="logo">
            </div>
            <h4>Back office</h4>
        </div>
        <div class="text-danger d-flex justify-content-center mb-2">

            <?php
            if (isset($_POST['submit'])) {
                $role = $controller->getRoleByEmail($_POST['email']);
                if ($role['idRole'] != 1) {
                    echo "Coordonnées éronées";
                } else {
                    echo $controller->login($_POST['email'], $_POST['password']);
                }
            }
            ?>
        </div>
        <form action="" method="post" id="admin-login-form">
            <h5 class="mb-2">Email</h5>
            <input type="email" name="email" class="mb-2" required>
            <h5 class="mb-2">Password</h5>
            <input type="password" name="password" class="mb-4" required>
            <button type="submit" name="submit" class="cta-btn">Connexion</button>
        </form>
    </div>
    <?php
    $view->showScripts();
    ?>
</body>

</html>