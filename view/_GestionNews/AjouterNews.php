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

$news;

if (isset($_GET['edit'])) {
    $news = $controller->getNewsById($_GET['edit']);
    if (!$news)
        header('location: ./GestionNews.php');
}

if (isset($_POST['submit'])) {
    $controller->ajouterNews(
        $_POST['titre'], $_POST['corps'],
        $_FILES['image'], $_FILES['video']
    );

    header('location: ./GestionNews.php');
}else if (isset($_POST['update'])) {
    $controller->modifierNews($_POST['id'],
        $_POST['titre'], $_POST['corps'],
        $_FILES['image'], $_FILES['video']
    );
    header('location: ./GestionNews.php');
}

?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        <?php if (isset($_GET['edit']))
                            echo "Modifier";
                        else
                            echo "Ajouter"; ?> une NEWS</title>
</head>

<body>
    <section class="d-flex">
        <?php
        $view->showSidebar($_POST['deconnexion'] ?? NULL);
        ?>
        <div class="containerZ">
            <div class="content">
                <h4 class="mb-5">
                <?php if (isset($_GET['edit']))
                            echo "Modifier";
                        else
                            echo "Ajouter"; ?> une NEWS</h4>
                <form action="" method="post" id="add-ingredient-form" enctype="multipart/form-data">
                    <?php if (isset($_GET['edit'])) {
                        echo "<input type=\"hidden\" name=\"id\" value=\"" . $news['idNews'] . "\"";
                    } ?>
                    <h5 class="mb-1">Titre</h5>
                    <input type="text" name="titre" class="mb-3" required <?php if (isset($_GET['edit'])) {
                        echo "value=\"" . utf8_encode($news['titre']) . "\"";
                    } ?>>
                    <h5 class="mb-1">Corps</h5>
                    <textarea name="corps" rows="3" class="mb-2" required><?php if (isset($_GET['edit'])) echo utf8_encode($news['corps']); ?></textarea>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between my-3">
                        <h5>Image</h5>
                        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png"
                        <?php if (!isset($_GET['edit'])) {
                        echo "required";
                    } ?>>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h5>Vidéo</h5>
                        <input type="file" name="video" accept="video/">
                    </div>
                    <button type="submit" class="cta-btn trouver-recette" <?php if (isset($_GET['edit']))
                        echo "name=\"update\"";
                    else
                        echo "name=\"submit\""; ?>>
                        <?php if (isset($_GET['edit']))
                            echo "Modifier";
                        else
                            echo "Ajouter"; ?>
                    </button>
                </form>
            </div>
        </div>
    </section>
    <?php
    $view->showScripts();
    ?>
</body>

</html>