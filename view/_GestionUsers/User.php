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

if (!isset($_GET['id'])) {
    header('location: ./GestionUsers.php');
}


if (isset($_POST['confirm'])){
    $controller->changeUserStatus($_POST['id'], 1);
} else if (isset($_POST['ban'])) {
    $controller->changeUserStatus($_POST['id'], 3);
}

$user = $controller->getUser($_GET['id']);

if (!$user) {
    header('location: ./GestionUsers.php');
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Profil: <?php echo $user['prenom'] . " " . $user['nom']; ?></title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion']?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-3">Profil de <?php echo $user['prenom'] . " " . $user['nom']; ?></h4>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 col p-2">
                            <div class="p-3" id="user-card">
                                <div class="mt-3 mb-4 d-flex justify-content-center">
                                    <ion-icon name="person-circle-outline" style="font-size: 4rem;"></ion-icon>
                                </div>
                                <h6 class="mb-2"><b>Id : </b>
                                    <?php echo $user['idUser'] ?>
                                </h6>
                                <h6 class="mb-2"><b>Prénom : </b><?php echo utf8_encode($user['prenom']) ?></h6>
                                <h6 class="mb-2"><b>Nom : </b>
                                    <?php echo utf8_encode($user['nom']) ?>
                                </h6>
                                <h6 class="mb-2"><b>E-ail : </b>
                                    <?php echo $user['mail'] ?>
                                </h6>
                                <h6 class="mb-2"><b>Status : </b>
                                    <?php echo utf8_encode($user['status']) ?>
                                </h6>
                                <h6 class="mb-2"><b>Role : </b><?php echo utf8_encode($user['nomRole']) ?></h6>
                                <form action="" method="post" class="d-flex" style="gap:0.5rem;">
                                    <input type="hidden" name="id" value="<?php echo $user['idUser']; ?>">

                                    <?php if ($user['idStatusUtilisateur'] != 1) { ?>
                                        <button class="cta-btn p-2" type="submit" name="confirm">
                                            Confirmer
                                        </button>
                                    <?php } else { ?>
                                        <button class="cta-btn p-2" type="submit" name="ban">
                                            Bannir
                                        </button>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-9 col p-2">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                        aria-selected="true">Favoris</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Notations</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab" tabindex="0">
                                    <div class="container">
                                        <?php
                                        $recettes = $controller->getFavoris($user['idUser']);
                                        $firstRow = true;
                                        for ($i = 0; $i < count($recettes); $i++) {
                                            if ($i % 4 == 0) {
                                                if (!$firstRow)
                                                    echo "</div>";
                                                else
                                                    $firstRow = false;
                                                echo "<div class=\"row\">";
                                            }
                                            ?>
                                            <div class="col-lg-4 col p-3">
                                                <div>
                                                    <?php
                                                    $view->showCadre($recettes[$i]['titre'], $recettes[$i]['description'], $recettes[$i]['image'], $recettes[$i]['idRecette']);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if (!$firstRow) {
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                                    tabindex="0">
                                    <div class="container">
                                        <?php
                                        $recettes = $controller->getRecettesNotes($user['idUser']);
                                        $firstRow = true;
                                        for ($i = 0; $i < count($recettes); $i++) {
                                            if ($i % 4 == 0) {
                                                if (!$firstRow)
                                                    echo "</div>";
                                                else
                                                    $firstRow = false;
                                                echo "<div class=\"row\">";
                                            }
                                            ?>
                                            <div class="col-lg-4 col p-3">
                                                <div>
                                                    <?php
                                                    $view->showCadre($recettes[$i]['titre'], $recettes[$i]['description'], $recettes[$i]['image'], $recettes[$i]['idRecette'], $recettes[$i]['note']);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if (!$firstRow) {
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>