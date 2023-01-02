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

}
?>