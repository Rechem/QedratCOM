<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();



?>
<?php
if(isset($_POST['submit'])){
$controller->ajouterMessage($_POST['nomprenom'], $_POST['email'], $_POST['message']);
}
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        Contact
    </title>
</head>


<body>
    <div>
        <?php
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white" >
            <h3 class="mb-5">Contact</h3>
                <form action="" method="post" id="contact-form">
                    <h6 class="mb-1">
                        Nom et prénom
                    </h6>
                    <input type="text" name="nomprenom" class="mb-3" required>
                    <h6 class="mb-1">
                        Email
                    </h6>
                    <input type="email" name="email" class="mb-3" required>
                    <h6 class="mb-1">
                        Message
                    </h6>
                    <textarea name="message" id="" rows="3" class="mb-3" required></textarea>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="submit" class="cta-btn" style="padding-block: 0.5rem;">Envoyer</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter($controller);
    $view->showScripts();
    ?>
</body>

</html>