<article class="article article--mini">
    <?php if (!empty($data['cover'])) : ?>
        <a href="<?= "/articles/read/{$data['slug']}" ;?>">
            <div class="article__cover"
                 style="background-image: url(<?= ASSETS_UPLOADS . "{$data['cover']}" ;?>)"
            ></div>
        </a>
    <?php endif; ?>
    <div class="article__container">
        <div class="article__info">
            <div class="avatar">
                <a href="/users/<?= $data['username']; ?>">
                    <img src="<?= ASSETS_IMG . "{$data['avatar']}"; ?>" alt="profile" />
                </a>
            </div>
            <div>
                <a href="/users/<?= $data['username']; ?>">
                    <?= $data['username']; ?>
                </a>
                <p class="muted">
                    <?= date("M jS, Y", strtotime($data['created_at'])); ?>
                </p>
            </div>
            <div>
                <button
                    class="button--like <?= $data['liked_by_logged_user'] ? 'liked' : null; ?>"
                    type="button"
                    data-article-id="<?=$data['id'];?>"
                    title="<?= $data['liked_by_logged_user'] ? 'Unlike article' : 'Like article'; ?>"
                >
                </button>
                <span class="button--like__count muted"><?= $data['likes'] ;?></span>
            </div>
        </div>
        <div class="article__title">
            <a href="<?="/articles/read/{$data['slug']}";?>"><?= $data['title']; ?></a>
        </div>
        <div class="article__misc">
            <?php if (!empty($data['tags'])) : ?>
                <div class="article__tags">
                    <?php foreach($data['tags'] as $tag) : ?>
                        <a class="tag" href="/tags/<?= $tag; ?>">
                            <span>#</span><?= $tag; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <button
                class="button button--bookmark <?= $data['bookmarked_by_logged_user'] ? 'bookmarked' : null; ?>"
                type="button"
                data-article-id="<?=$data['id'];?>"
                title="<?= $data['bookmarked_by_logged_user'] ? 'Remove bookmark' : 'Bookmark article'; ?>"
            >
                <?= $data['bookmarked_by_logged_user'] ? 'UNSAVE' : 'SAVE'; ?>
            </button>
        </div>
        <?php if (!empty($data['description'])) : ?>
            <div class="article__description">
                <?= $data['description']; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
