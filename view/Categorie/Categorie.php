<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
$view = new UserTemplateView();
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>Catégorie</title>
</head>


<body>
    <div>
        <?php
        $view->showSocialLinks();
        $view->showHeader();
        ?>
        <div class="boundary">
        </div>
    </div>
    <?php
    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>