<!DOCTYPE html>
<html lang="en">

<?php
require_once '../UserTemplate/UserTemplateView.php';
require_once '../../controller/UserController.php';
$view = new UserTemplateView();
$controller = new UserController();
session_start();
?>

<head>
    <meta charset="UTF-8">
    <?php
    $view->showCSS();
    ?>
    <title>
        Healthy
    </title>
</head>


<body>
    <div>
        <?php
        $view->showHeader($_POST, $controller, $_SESSION);
        ?>
        <div class="boundary">
            <section class="section-padding bg-white">
                <?php
                if (isset($_GET['news']) && !empty($_GET['news'])) {
                    $news = $controller->getNewsById($_GET['news']);
                    ?>
                    <div class="news">
                        <div class="news-image-frame">
                            <img src="<?php echo "../..".$news['image']?>" alt="<?php echo "../..".$news['titre']?>">
                        </div>
                        <h3 class="mt-4 mb-1"><?php echo utf8_encode($news['titre'])?></h3>
                        <p class="mb-4"><?php 
                        $phpdate = strtotime( $news['date'] );
                        $mysqldate = date( ' d/m/Y H:m', $phpdate );
                        echo $mysqldate
                        ?></p>
                        <h6 class="mb-4">
                        <?php echo utf8_encode($news['corps'])?>
                    </h6>
                    <?php
                    if(isset($news['video']) && !empty($news['video']))
                        echo "<video src=\"../..".$news['video']."\" controls=\"controls\"></video>"
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <h3 class="mb-5">News</h3>
                    <div class="container mt-4">
                        <?php
                        $news = $controller->getNews();
                        $firstRow = true;
                        for ($i = 0; $i < count($news); $i++) {
                            if ($i % 3 == 0) {
                                if (!$firstRow)
                                    echo "</div>";
                                else
                                    $firstRow = false;
                                echo "<div class=\"row mb-4\">";
                            }
                            ?>
                            <div class="col-lg-4 col">
                                    <?php
                                    $view->showNewsCadre($news[$i]['idNews'], $news[$i]['date'], $news[$i]['image'], $news[$i]['titre'], $news[$i]['corps']);
                                    ?>
                            </div>
                            <?php
                        }
                        if (!$firstRow) {
                            echo "</div>";
                        }


                        ?>
                    </div>
                    <?php } ?>
            </section>
        </div>
    </div>
    <?php

    $view->showFooter();
    $view->showScripts();
    ?>
</body>

</html>