<?php
require_once "../SuperTemplate/SuperTemplate.php";
class AdminTemplateView extends SuperTemplateView
{

    public function showSidebar()
    {
        ?>
        <div class="sidebar">
            <div class="d-flex align-items-center justify-content-between">
                <h4>QedratCOM</h4>
            </div>
            <a href="../_GestionRecette/GestionRecette.php" class="text-decoration-none">
                <div class="menu-item">
                    <ion-icon name="fast-food-outline" class="menu-icon"></ion-icon>
                    <h5>Recettes</h5>
                </div>
            </a>
            <a href="../_GestionNutrition/GestionNutrition.php" class="text-decoration-none">
                <div class="menu-item" role="button">
                    <ion-icon name="nutrition-outline" class="menu-icon"></ion-icon>
                    <h5>Nutrition</h5>
                </div>
            </a>
            <a href="../_GestionNews/GestionNews.php" class="text-decoration-none">
                <div class="menu-item" role="button">
                    <ion-icon name="newspaper-outline" class="menu-icon"></ion-icon>
                    <h5>NEWS</h5>
                </div>
            </a>
            <a href="../_GestionUsers/GestionUsers.php" class="text-decoration-none">
                <div class="menu-item" role="button">
                    <ion-icon name="people-outline" class="menu-icon"></ion-icon>
                    <h5>Utilisateurs</h5>
                </div>
            </a>
            <a href="../_Parametres/Parametres.php" class="text-decoration-none">
                <div class="menu-item" role="button">
                    <ion-icon name="settings-outline" class="menu-icon"></ion-icon>
                    <h5>Paramètres</h5>
                </div>
            </a>
        </div>
        <?php
    }
    public function showScripts()
    {
        ?>

        <script src="../../jquery-3.6.1.min.js"></script>
        <script src="../script.js"></script>

        <!-- bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>

        <!-- ionicons -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <!-- table -->
        <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>

        <?php
    }

    public function showCSS()
    {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

        <link href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style.css">

        <?php
    }

    public function formatTime(int $minutes)
    {
        if ($minutes == 0) {
            return "-";
        } else if ($minutes < 60) {
            return $minutes . " minute";
        } else {
            if ($minutes % 60 == 0)
                return (int) ($minutes / 60) . " heure(s)";
            else
                return (int) ($minutes / 60) . " heure(s) " . ($minutes % 60) . " minute";
        }
    }
}
?>