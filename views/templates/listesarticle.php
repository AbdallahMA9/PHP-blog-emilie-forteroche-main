<?php 
    /** 
     * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun. 
     * Et un formulaire pour ajouter un article. 
     */
?>

<h2>Listes des articles</h2>
<div class="sorting">
    <i>filtré par:</i>
    <a href="index.php?action=listearticle&sortBy=title&order=<?= ($sortBy == 'title' && $order == 'asc') ? 'desc' : 'asc' ?>">Titre</a>
    <a href="index.php?action=listearticle&sortBy=views&order=<?= ($sortBy == 'views' && $order == 'asc') ? 'desc' : 'asc' ?>">Vues</a>
    <a href="index.php?action=listearticle&sortBy=date_creation&order=<?= ($sortBy == 'date_creation' && $order == 'asc') ? 'desc' : 'asc' ?>">Date</a>
    <a href="index.php?action=listearticle&sortBy=commentCount&order=<?= ($sortBy == 'commentCount' && $order == 'asc') ? 'desc' : 'asc' ?>">Commentaires</a>
</div>

<table class="adminArticle">
    <tr class="articleLine">
        <td class="title">Titre</td>
        <td class="title">Nombre de vue</td>
        <td class="title">Date de création</td>
        <td class="title">Nombre de commentaire</td>
    </tr>
    <?php foreach ($articles as $index => $article) { ?>
        <tr class="articleLine">
            <td class="title"><?= htmlspecialchars($article->getTitle()) ?></td>
            <td class="title"><?= htmlspecialchars($article->getViews()) ?></td>
            <td class="title"><?= htmlspecialchars($article->getDateCreation()->format('Y-m-d H:i:s')) ?></td>
            <td class="title"><?= htmlspecialchars($article->commentCount) ?></td>
        </tr>
    <?php } ?>
</table>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
