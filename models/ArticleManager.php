<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{

    /**
     * Récupère tous les articles avec tri.
     * @param string $sortBy : le champ par lequel trier (views, comments, date, title).
     * @param string $order : l'ordre de tri (asc ou desc).
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(string $sortBy = 'date_creation', string $order = 'asc') : array
    {
        // Valider et sécuriser les valeurs des paramètres
        $validSortBy = ['views', 'comments', 'date_creation', 'title'];
        $validOrder = ['asc', 'desc'];
        
        if (!in_array($sortBy, $validSortBy)) {
            $sortBy = 'date_creation';
        }

        if (!in_array($order, $validOrder)) {
            $order = 'asc';
        }

        // Préparer la requête SQL
        $sql = "SELECT a.*, (SELECT COUNT(*) FROM comment c WHERE c.id_article = a.id) as comment_count 
                FROM article a 
                ORDER BY $sortBy $order";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }

    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void 
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }


    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function addView(int $id) : ?Article
    {
        $sql = "UPDATE article SET views = views + 1 WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Supprime un commentaire.
     * @param int $id : l'id du commentaire à supprimer.
     * @return void
     */
    public function deleteComment(int $id) : void
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Récupère le nombre de commentaires pour un article donné.
     * @param int $articleId : l'id de l'article.
     * @return int : le nombre de commentaires.
     */
    public function getCommentCountForArticle(int $articleId) : int
    {
        $sql = "SELECT COUNT(*) as comment_count FROM comment WHERE id_article = :article_id";
        $result = $this->db->query($sql, ['article_id' => $articleId]);
        $data = $result->fetch();
        return $data['comment_count'] ?? 0;
    }



    
}