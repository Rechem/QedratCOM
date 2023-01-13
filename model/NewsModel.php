<?php
require_once 'Model.php';
class NewsModel extends Model
{

    public function getNews()
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("SELECT idNews, titre, SUBSTRING(corps, 1,255) as corps, image, date FROM News
        ORDER BY idNews ASC");

        $qtf->execute();
        $result = $qtf->fetchAll();

        parent::deconnexion($pdo);
        return $result;
    }

    public function getNewsById($idNews)
    {
        $pdo = parent::connexion();

        $qtf = $pdo->prepare(
            "SELECT `idNews`, `titre`, `corps`, `image`, `video`, `date` FROM news
            WHERE `idNews` = :idNews"
        );
        $qtf->bindParam(':idNews', $idNews, PDO::PARAM_INT);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }

    public function ajouterNews($titre, $corps, $image, $video){

        $imageLink = parent::ajouterImage($image);
        if (empty($imageLink)) {
            return;
        }

        $videoLink = "";

        if (isset($video) && !empty($video)) {
            $videoLink = parent::ajouterVideo($video);
        }

        $pdo = parent::connexion();
        $qtf = $pdo->prepare(
            "INSERT INTO news(titre, corps, image, video, date)
            VALUES (:titre, :corps, :image, :video, now());"
        );
        $qtf->bindParam(':titre', $titre);
        $qtf->bindParam(':corps', $corps);
        $qtf->bindParam(':image', $imageLink);
        $qtf->bindParam(':video', $videoLink);
        $qtf->execute();
        
        parent::deconnexion($pdo);
    }

    public function deleteNews($idNews){
        $pdo = parent::connexion();

        $qtf = $pdo->prepare("DELETE FROM `news` WHERE idNews = :idNews");
        $qtf->bindParam(':idNews', $idNews);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

}
?>