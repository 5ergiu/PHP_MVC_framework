<article class="article article--mini">
    <?php if (!empty($article['cover'])) : ?>
        <a href="<?= "/articles/read/{$article['slug']}" ;?>">
            <div class="article__cover"
                 style="background-image: url(<?= ASSETS_UPLOADS . "{$article['cover']}" ;?>)"
            ></div>
        </a>
    <?php endif; ?>
    <div class="article__container">
        <div class="article__info">
            <div class="avatar">
                <a href="/users/<?= $article['username']; ?>">
                    <img src="<?= ASSETS_IMG . "{$article['user_image']}"; ?>" alt="profile" />
                </a>
            </div>
            <div>
                <a href="/users/<?= $article['username']; ?>">
                    <?= $article['username']; ?>
                </a>
                <p class="muted">
                    <?= date("M jS, Y", strtotime($article['created_at'])); ?>
                </p>
            </div>
            <div>
                <button
                    class="button--like <?= $article['liked_by_current_user'] ? 'liked' : null; ?>"
                    type="button"
                    data-article-id="<?=$article['id'];?>"
                    title="<?= $article['liked_by_current_user'] ? 'Unlike article' : 'Like article'; ?>"
                >
                </button>
                <span class="button--like__count muted"><?= $article['likes'] ;?></span>
            </div>
        </div>
        <div class="article__title">
            <a href="<?="/articles/read/{$article['slug']}";?>"><?= $article['title']; ?></a>
        </div>
        <div class="article__misc">
            <?php if (!empty($article['tags'])) : ?>
                <div class="article__tags">
                    <?php foreach($article['tags'] as $tag) : ?>
                        <a class="tag" href="/tags/<?= $tag; ?>">
                            <span>#</span><?= $tag; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button
                class="button button--bookmark <?= $article['bookmarked_by_current_user'] ? 'bookmarked' : null; ?>"
                type="button"
                data-article-id="<?=$article['id'];?>"
                title="<?= $article['liked_by_current_user'] ? 'Remove bookmark' : 'Bookmark article'; ?>"
            >
                <?= $article['bookmarked_by_current_user'] ? 'SAVED' : 'SAVE'; ?>
            </button>
        </div>
        <?php if (!empty($article['description'])) : ?>
            <div class="article__description">
                <?= $article['description']; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
