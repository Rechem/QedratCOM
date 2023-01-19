<?php
require_once 'Model.php';
class UserModel extends Model
{

    public function isFavorite($idRecette)
    {

        if (!isset($_SESSION) || !isset($_SESSION['id'])) {
            return;
        }

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT * FROM favoris
                WHERE idUser = :idUser
                AND idRecette = :idRecette");

        $qtf->bindParam(':idUser', $_SESSION['id']);
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);

        if ($result)
            return "true";
        else
            return "false";
    }

    public function addToFavorite($idRecette)
    {

        if (!isset($_SESSION) || !isset($_SESSION['id']) || $this->isFavorite($idRecette) == "true") {
            return;
        }

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("INSERT INTO favoris (idUser, idRecette)
                VALUES (:idUser, :idRecette);");

        $qtf->bindParam(':idUser', $_SESSION['id']);
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    public function removeFromFavorite($idRecette)
    {

        if (!isset($_SESSION) || !isset($_SESSION['id']) || $this->isFavorite($idRecette) == "false") {
            print_r("mel mo9bil ?");
            return;
        }

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("DELETE FROM favoris
        WHERE idRecette = :idRecette AND idUser = :idUser;");

        $qtf->bindParam(':idUser', $_SESSION['id']);
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    private function checkExists($mail)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT mail FROM utilisateur
                WHERE mail = :mail");

        $qtf->bindParam(':mail', $mail);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);

        if ($result)
            return true;
        else
            return false;
    }

    public function signup($nom, $prenom, $mail, $sexe, $dateNaissance, $password)
    {
        if (
            empty($nom) || empty($prenom) || empty($mail) ||
            empty($sexe) || empty($dateNaissance) || empty($password)
        ) {
            return 'Arguments insuffisants';
        }

        if ($this->checkExists($mail)) {
            return "Cet utilisateur existe déjà.";
        }

        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "INSERT INTO utilisateur (nom, prenom, mail, sexe, dateNaissance, motDePasse, idStatusUtilisateur, idRole)
            VALUES (:nom, :prenom, :mail, :sexe, :dateNaissance, :motDePasse, 2, 2);"
        );

        $qtf->bindParam(':nom', $nom);
        $qtf->bindParam(':prenom', $prenom);
        $qtf->bindParam(':mail', $mail);
        $qtf->bindParam(':sexe', $sexe);
        $qtf->bindParam(':dateNaissance', $dateNaissance);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $qtf->bindParam(':motDePasse', $hashedPassword);
        $isSuccess = $qtf->execute();

        if ($isSuccess) {
            parent::deconnexion($pdo);
            return "Veuillez attendre que l'administrateur confirme votre compte";
        } else {
            parent::deconnexion($pdo);
            return "Une erreur est survenue";
        }
    }

    public function login($mail, $password)
    {
        if (
            empty($mail) || empty($password)
        ) {
            return 'Arguments insuffisants';
        }

        if (!$this->checkExists($mail)) {
            return "Cet utilisateur n'existe pas.";
        }

        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT idUser, motDePasse, idStatusUtilisateur FROM utilisateur
            WHERE mail = :mail"
        );

        $qtf->bindParam(':mail', $mail);
        $qtf->execute();
        $result = $qtf->fetch();
        $isLegit = password_verify($password, $result['motDePasse']);

        if ($isLegit) {
            if($result['idStatusUtilisateur'] != 1 ){
                parent::deconnexion($pdo);
                return "Compte non comfirmé ou bannis";
            }
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['id'] = $result['idUser'];
            parent::deconnexion($pdo);
            header("Refresh:0");
        } else{
            parent::deconnexion($pdo);
            return "Mot de passe érroné";
        }
    }

    public function checkNotationExists($idRecette, $idUser)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT * FROM notation
                WHERE idRecette = :idRecette
                AND idUser = :idUser");

        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);

        if ($result)
            return true;
        else
            return false;
    }

    public function getNote($idRecette)
    {
        if (!isset($_SESSION) || !isset($_SESSION['id'])) {
            return -1;
        }
        if ($this->checkNotationExists($idRecette, $_SESSION['id'])) {

            $pdo = parent::connexion();

            $qtf = $pdo->prepare("SELECT note FROM notation
                WHERE idRecette = :idRecette
                AND idUser = :idUser");

            $qtf->bindParam(':idRecette', $idRecette);
            $qtf->bindParam(':idUser', $_SESSION['id']);
            $qtf->execute();
            $result = $qtf->fetch();

            parent::deconnexion($pdo);
            return $result[0];
        }
        return -1;
    }

    public function note($idRecette, $notation)
    {
        if (!($notation > 0 && $notation <= 5))
            return;

        if (!isset($_SESSION) || !isset($_SESSION['id'])) {
            return;
        } else if ($this->checkNotationExists($idRecette, $_SESSION['id'])) {
            $this->updateNotation($idRecette, $_SESSION['id'], $notation);
        } else {
            $this->addNotation($idRecette, $_SESSION['id'], $notation);
        }

    }

    private function addNotation($idRecette, $idUser, $notation)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("INSERT INTO notation (idRecette, idUser, note)
                VALUES (:idRecette, :idUser, :notation);");

        $qtf->bindParam(':notation', $notation);
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    private function updateNotation($idRecette, $idUser, $notation)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("UPDATE notation
                SET note = :notation
                WHERE idRecette = :idRecette
                AND idUser = :idUser");

        $qtf->bindParam(':notation', $notation);
        $qtf->bindParam(':idRecette', $idRecette);
        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    public function getFavoris($idUser)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT r.idRecette, titre, description, image
        FROM (SELECT idRecette FROM favoris WHERE idUser = :idUser) as n
        LEFT OUTER JOIN recette r on r.idRecette = n.idRecette;");

        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;

    }

    public function getRecettesNotes($idUser)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT r.idRecette, titre, description, image, note
        FROM (SELECT idRecette, note FROM notation WHERE idUser = :idUser) as n
        LEFT OUTER JOIN recette r on r.idRecette = n.idRecette;");

        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;

    }

    public function getUsers()
    {

        $pdo = parent::connexion();

        $qtf = "SELECT idUser, u.nom as nom, prenom, su.nom as nomStatus, nomRole, mail, sexe, u.idStatusUtilisateur FROM utilisateur u
                LEFT OUTER JOIN statusutilisateur su ON su.idStatusUtilisateur = u.idStatusUtilisateur
                LEFT OUTER JOIN role r ON r.idRole = u.idRole
                ORDER BY idUser ASC";
        $result = parent::requette($pdo, $qtf);

        parent::deconnexion($pdo);
        return $result;
    }

    public function getUser($idUser)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT
        u.nom,
        prenom,
        mail,
        idUser,
        su.idStatusUtilisateur,
        role.idRole,
        nomRole,
        su.nom as status
    FROM
        utilisateur u
    LEFT OUTER JOIN role ON role.idRole = u.idRole
    LEFT OUTER JOIN statusutilisateur su ON
    su.idStatusUtilisateur = u.idStatusUtilisateur
    WHERE
        idUser = :idUser "
        );

        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);

        return $result;
    }

    public function getRoleByEmail($mail)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT
            idRole
        FROM
            utilisateur
        WHERE
            mail = :mail "
        );

        $qtf->bindParam(':mail', $mail);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);

        return $result;
    }

    public function isAdmin($idUser)
    {
        $user = $this->getUser($idUser);

        if (!$user)
            return false;

        if ($user['idRole'] == 1)
            return true;
        else
            return false;
    }

    public function changeUserStatus($idUser, $newStatus)
    {
        if ($newStatus > 3 || $newStatus < 1)
            return;
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("UPDATE utilisateur
                SET idStatusUtilisateur = :newStatus
                WHERE idUser = :idUser");

        $qtf->bindParam(':newStatus', $newStatus);
        $qtf->bindParam(':idUser', $idUser);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

}
?>