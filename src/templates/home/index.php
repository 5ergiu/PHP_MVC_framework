<?php
    $this->includeCss(['articles']);
?>
<section>
    <h1>Articles</h1>
    <?php foreach ($articles as $article) {
        $this->element('article_mini', $article);
    } ?>
</section>
