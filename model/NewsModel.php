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
            "SELECT idNews, titre, corps, image, video, date FROM news
            WHERE idNews = :idNews"
        );
        $qtf->bindParam(':idNews', $idNews);
        $qtf->execute();
        $result = $qtf->fetch();

        parent::deconnexion($pdo);
        return $result;
    }

    public function ajouterNews($titre, $corps, $image, $video)
    {

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

    public function modifierNews($idNews, $titre, $corps, $image, $video)
    {
        $news = $this->getNewsById($idNews);
        if (!$news) {
            return;
        }

        $imageLink = $news["image"];
        $videoLink = $news["video"];

        if (isset($image) && !empty($image)) {
            if (!empty($imageLink))
                unlink(__DIR__ . '/..' . $imageLink);
            $tmpLink = parent::ajouterImage($image);
            if (!empty($tmpLink))
                $imageLink = $tmpLink;
        }


        if (isset($video) && !empty($video)) {
            if (!empty($videoLink))
                unlink(__DIR__ . '/..' . $videoLink);
            $tmpLink = parent::ajouterVideo($video);
            if (!empty($tmpLink))
                $videoLink = $tmpLink;
        }

        $pdo = parent::connexion();
        $qtf = $pdo->prepare(
            "UPDATE news
            SET titre = :titre,
            corps = :corps,
            image= :image,
            video= :video
            WHERE idNews = :idNews;"
        );
        $qtf->bindParam(':titre', $titre);
        $qtf->bindParam(':corps', $corps);
        $qtf->bindParam(':image', $imageLink);
        $qtf->bindParam(':video', $videoLink);
        $qtf->bindParam(':idNews', $idNews);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

    public function deleteNews($idNews)
    {

        $news = $this->getNewsById($idNews);
        if (!$news) {
            return;
        }

        $imageLink = $news["image"];
        $videoLink = $news["video"];

        if (!empty($imageLink))
            unlink(__DIR__ . '/..' . $imageLink);


        if (!empty($videoLink))
            unlink(__DIR__ . '/..' . $videoLink);

        $pdo = parent::connexion();

        $qtf = $pdo->prepare("DELETE FROM `news` WHERE idNews = :idNews");
        $qtf->bindParam(':idNews', $idNews);
        $qtf->execute();

        parent::deconnexion($pdo);
    }

}
?>