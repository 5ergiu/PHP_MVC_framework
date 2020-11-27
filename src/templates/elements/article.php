<article class="article">
    <?php if (!empty($data['cover'])) : ?>
        <div class="article__cover">
            <div class="article__cover_image"
                 style="background-image: url(<?= ASSETS_UPLOADS . "{$data['cover']}" ;?>)"
            >
            </div>
        </div>
    <?php endif; ?>
    <div class="article__container">
        <div class="article__title">
            <a href="<?="/articles/read/{$data['slug']}";?>"><?= $data['title']; ?></a>
        </div>
        <?php if (!empty($data['tags'])) : ?>
            <div class="article__tags">
                <?php foreach($data['tags'] as $tag) : ?>
                    <a class="tag" href="/tags/<?= $tag; ?>">
                        <span>#</span><?= $tag; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="article__content">
            <?= $data['content']; ?>
        </div>
    </div>
</article>