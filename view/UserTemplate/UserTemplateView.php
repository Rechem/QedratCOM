<?php
class UserTemplateView
{
    public function showSocialLinks()
    {
        ?>
        <div id="social-links">
            <a href="#">
                <p>
                    Facebook
                </p>
            </a>
            <a href="#">
                <p>
                    Instagram
                </p>
            </a>
            <a href="#">
                <p>
                    Twitter
                </p>
            </a>
        </div>
    <?php
    }
    public function showHeader()
    {
        ?>
        <header>
            <div id="modal-container">
                <div id="modal">
                    <img src="../../public/icons/add_black.svg" alt="close-btn" id="close-icon">
                    <h3>Se connecter</h3>
                    <form action="POST">
                        <h6>
                            E-mail
                        </h6>
                        <input type="email" name="username">
                        <h6>
                            Mot de passe
                        </h6>
                        <input type="password" name="password">
                        <input type="submit" value="Connexion" class="cta-btn">
                    </form>
                    <button class="text-button" id="toggle-login-btn">
                        Nouveau utilisateur ? s'inscrire
                    </button>
                </div>
            </div>
            <div class="boundary">
                <div id="logo-login">
                    <div id="logo">
                        <h4>QedratCom</h4>
                    </div>
                    <button id="login-btn">
                        Se connecter
                    </button>
                </div>
                <nav>
                    <a href="../Home/Home.php">
                        <h6>
                            Accueil
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            News
                        </h6>
                    </a>
                    <a href="../IdeeRecette/IdeeRecette.php">
                        <h6>
                            Idées
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Healthy
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Saisons
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Fetes
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Nutritions
                        </h6>
                    </a>
                    <a href="#">
                        <h6>
                            Contact
                        </h6>
                    </a>
                </nav>
            </div>
        </header>
    <?php
    }

    public function showCadre($titre, $description, $image, $id)
    {
        ?>
        <div class="cadre">
            <a href=<?php echo "../Recette/Recette.php?id=" . $id ?>>
                <div class="recette-text-cadre">
                    <div class="recette-text">
                        <h5>
                            <?php echo utf8_encode($titre) ?>
                        </h5>
                        <p><?php echo utf8_encode($description) ?></p>
                    </div>
                </div>
                <div class="recette-cadre">
                    <img src=<?php echo "../.." . $image; ?> alt="image recette">
                </div>
            </a>
        </div>
    <?php
    }

    public function showFooter()
    {
        ?>
        <footer>
            <div class="boundary">asas</div>
        </footer>
    <?php
    }
    public function showScripts()
    {
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script src="../../jquery-3.6.1.min.js"></script>
        <script src="../script.js"></script>
    <?php
    }

    public function showCSS()
    {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                return (int) ($minutes / 60) . " heure(s)" . ($minutes % 60) . " minute";
        }
    }
}
?>