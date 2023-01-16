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

    protected function ajouterImage($image)
    {

        if (!is_uploaded_file($image['tmp_name'])) {
            echo "Image non fournie";
            return"";
        }

        $path_parts = pathinfo(basename($image["name"]));
        $tempname = $image["tmp_name"];

        $imageFileType = strtolower($path_parts['extension']);
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        ) {
            echo "Format incorrect : jpg, jpeg, png seulement";
            return "";
        }

        $checkImage = getimagesize($tempname);
        if (!$checkImage) {
            echo "Veuillez ajouter une image";
            return "";
        }

        $folder = '/public/images/';

        $target_dir = "../.." . $folder;
        $now = new DateTime();
        $unixTimeStamp = $now->getTimestamp();
        $newFileName = str_replace(' ', '_', $path_parts['filename']) . "-" . $unixTimeStamp . "." . $path_parts['extension'];
        $target_file = $target_dir . $newFileName;

        if (!move_uploaded_file($tempname, $target_file)) {
            echo "Erreur lors de l'upload de l'image";
            return "";
        }

        return $folder . $newFileName;
    }

    protected function ajouterVideo($video)
    {

        if (!is_uploaded_file($video['tmp_name'])) {
            return "";
        }

        $path_parts = pathinfo(basename($video["name"]));
        $tempname = $video["tmp_name"];

        $imageFileType = strtolower($path_parts['extension']);
        if (
            $imageFileType != "mp4" && $imageFileType != "mkv" && $imageFileType != "avi"
        ) {
            echo "Format incorrect : mp4, mkv, avi seulement";
            return "";
        }

        $folder = '/public/videos/';

        $target_dir = "../.." . $folder;
        $now = new DateTime();
        $unixTimeStamp = $now->getTimestamp();
        $newFileName = str_replace(' ', '_', $path_parts['filename']) . "-" . $unixTimeStamp . "." . $path_parts['extension'];
        $target_file = $target_dir . $newFileName;

        if (!move_uploaded_file($tempname, $target_file)) {
            echo "Erreur lors de l'upload de la vidéo";
            return "";
        }

        return $folder . $newFileName;
    }

    public function getMenuItems(){
        $pdo = $this->connexion();

        $qtf = "SELECT * from menu";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    public function getDiapos(){
        $pdo = $this->connexion();

        $qtf = "SELECT * from diaporama";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    public function ajouterDiapo($lien, $image){
        $imageLink = $this->ajouterImage($image);
        if (empty($imageLink)) {
            return;
        }

        $pdo = $this->connexion();
        $qtf = $pdo->prepare(
            "INSERT INTO diaporama(image, lien)
            VALUES (:image, :lien);"
        );
        $qtf->bindParam(':image', $imageLink);
        $qtf->bindParam(':lien', $lien);
        $qtf->execute();
        
        $this->deconnexion($pdo);
    }

    public function supprimerDiapo($idDiaporama){
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("DELETE FROM `diaporama` WHERE `diaporama`.`idDiaporama` = :idDiaporama");
        $qtf->bindParam(':idDiaporama', $idDiaporama);
        $qtf->execute();

        $this->deconnexion($pdo);
    }

    public function ajouterMessage($nomprenom, $mail, $message){

        $pdo = $this->connexion();
        $qtf = $pdo->prepare(
            "INSERT INTO `message` (`idMessage`, `nomprenom`, `email`, `message`)
            VALUES (NULL, :nomprenom, :email, :message);"
        );
        $qtf->bindParam(':nomprenom', $nomprenom);
        $qtf->bindParam(':email', $mail);
        $qtf->bindParam(':message', $message);
        $qtf->execute();
        
        $this->deconnexion($pdo);
    }

    public function getMessages(){
        $pdo = $this->connexion();

        $qtf = "SELECT * from message";
        $result = $this->requette($pdo, $qtf);

        $this->deconnexion($pdo);
        return $result;
    }

    public function supprimerMessage($idMessage){
        $pdo = $this->connexion();

        $qtf = $pdo->prepare("DELETE FROM `message` WHERE `message`.`idMessage` = :idMessage");
        $qtf->bindParam(':idMessage', $idMessage);
        $qtf->execute();

        $this->deconnexion($pdo);
    }

}

?>