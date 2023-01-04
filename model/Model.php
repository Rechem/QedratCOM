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

}

?>