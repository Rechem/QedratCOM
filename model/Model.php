<?php
class Model 
{
    private $dbname = "9edra";
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    protected function connexion()
    {
        $dsn = "mysql:dbname=$this->dbname; host=$this->host;";
        try {
            $pdo = new PDO($dsn, $this->user, $this->password);
            return $pdo;
        } catch (PDOException $ex) {
            printf("erreur de connexion à la base de donnée", $ex->getMessage());
            exit();
        }
    }

    protected function requette($pdo, $requette)
    {
        $result = $pdo->query($requette);

        return $result;
    }

    protected function deconnexion(&$pdo)
    {
        $pdo = null;
    }

    private function login($username, $password)
    {

        $pdo = $this->connexion();

        $qtf = $pdo->prepare("select hash_pwd, id_user from utilisateur where name_user=:username");
        $qtf->bindParam(':username', $username);
        $qtf->execute();
        $row = $qtf->fetch();
        $fetchedPassword = $row['hash_pwd'];
        if (!$fetchedPassword) {
            $message = 'Utilisateur introuvable';
        } else {
            if ($fetchedPassword == $password) {
                session_start();
                $_SESSION['id'] = $row['id_user'];
                header('Location: ../products/');
                die();
            } else {
                $message = 'Mot de passe incorrect';
            }
        }
        echo $message;

        $this->deconnexion($pdo);
    }

    private function getMenu()
    {
        $pdo = $this->connexion();

        $qtf = "SELECT * FROM `menu`";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    private function getTypesAgriculture()
    {
        $pdo = $this->connexion();

        $qtf = "SELECT * FROM `type_agriculture`";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    private function getTableauCulture()
    {
        $pdo = $this->connexion();
        $qtf = "SELECT * FROM `culture` ORDER BY Nom_culture";
        $result = $this->requette($pdo, $qtf);
        $this->deconnexion($pdo);

        return $result;

    }

    private function getTableauEspece()
    {
        $pdo = $this->connexion();
        $qtf = "SELECT * FROM `elevage` ORDER BY Nom_animal";
        $result = $this->requette($pdo, $qtf);
        $this->deconnexion($pdo);

        return $result;

    }

    private function addCulture($culture, $superficie, $production)
    {
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("INSERT INTO `culture`(`Nom_culture`, `Superficie`, `Production`) VALUES (:nomCulture, :superficie, :production);");
        $qtf->bindParam(':nomCulture', $culture);
        $qtf->bindParam(':superficie', $superficie);
        $qtf->bindParam(':production', $production);
        $qtf->execute();

        $this->deconnexion($pdo);

        header('location: ./index.php');
    }

    private function editCulture($idCulture, $culture, $superficie, $production)
    {
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("UPDATE `culture` SET `Nom_culture`=:nomCulture,`Superficie`=:superficie,`Production`=:production WHERE `Id_culture`=:idCulture;");
        $qtf->bindParam(':nomCulture', $culture);
        $qtf->bindParam(':superficie', $superficie);
        $qtf->bindParam(':production', $production);
        $qtf->bindParam(':idCulture', $id);
        $qtf->execute();

        $this->deconnexion($pdo);

        header('location: ./index.php');
    }

    private function deleteCulture($idCulture)
    {
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("DELETE FROM `culture` WHERE `id_culture`=:idCulture");
        $qtf->bindParam(':idCulture', $id);
        $qtf->execute();

        $this->deconnexion($pdo);

        header('location: ./index.php');
    }

    private function addEspece($elevage, $nombreTete)
    {
        $pdo = $this->connexion();
        $qtf = $pdo->prepare("INSERT INTO `elevage`(`Nom_animal`, `Nombre_tete`) VALUES (:nomAnimal, :nombreTete);");
        $qtf->bindParam(':nomAnimal', $elevage);
        $qtf->bindParam(':nombreTete', $nombreTete);
        $qtf->execute();

        $this->deconnexion($pdo);

        header('location: ./index.php');
    }

    private function editEspece($idElevage, $elevage, $nombreTete)
    {
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("UPDATE `elevage` SET `Nom_animal`=:elevage,`Nombre_tete`=:nombreTete WHERE `Id_elevage`=:idElevage;");
        $qtf->bindParam(':elevage', $elevage);
        $qtf->bindParam(':nombreTete', $nombreTete);
        $qtf->bindParam(':idElevage', $idElevage);
        $qtf->execute();

        $this->deconnexion($pdo);

        header('location: ./index.php');
    }

    private function deleteEspece($idElevage)
    {
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("DELETE FROM `elevage` WHERE `id_elevage`=:idElevage");
        $qtf->bindParam(':idElevage', $idElevage);
        $qtf->execute();

        

        header('location: ./index.php');
    }

    private function fetchElevageTableau()
    {
        $pdo = $this->connexion();

        $qtf = "SELECT * FROM `elevage` ORDER BY Nom_animal";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    private function fetchCultureTableau()
    {
        $pdo = $this->connexion();

        $qtf = "SELECT * FROM `culture` ORDER BY Nom_culture";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

}

?>