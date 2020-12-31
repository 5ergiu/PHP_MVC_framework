<article class="article article--mini">
    <?php if (!empty($data['cover'])) : ?>
        <div class="article__cover">
            <div class="article__cover_image"
                 style="background-image: url(<?= ASSETS_UPLOADS . "{$data['cover']}" ;?>)"
            >
            </div>
        </div>
    <?php endif; ?>
    <div class="article__container">
        <?php if ($data['likes'] > 0) : ?>
            <div class="article__likes">
                <p class="muted">
                    <?= $data['likes'] ;?>
                </p>
                <span>❤️</span>
            </div>
        <?php endif; ?>
        <div class="article__bookmark">
            <button
                class="button button--bookmark js-clickable js-button-bookmark <?= $data['bookmarked_by_logged_user'] ? 'button--bookmarked' : null; ?>"
                type="button"
                data-article-id="<?=$data['id'];?>"
                title="<?= $data['bookmarked_by_logged_user'] ? 'Remove bookmark' : 'Bookmark article'; ?>"
            >
                <?= $data['bookmarked_by_logged_user'] ? '📚' : '<i class="far fa-bookmark"></i>'; ?>
            </button>
            <div class="js-bookmark-spinner spinner hide"></div>
        </div>
        <div class="article__info">
            <a class="avatar js-clickable" href="/users/<?= $data['username']; ?>">
                <img src="<?= ASSETS_IMG . "{$data['avatar']}"; ?>" alt="profile" />
            </a>
            <div>
                <a class="js-clickable" href="/users/<?= $data['username']; ?>">
                    <?= $data['username']; ?>
                </a>
                <time class="muted">
                    <?= date("M jS, Y", strtotime($data['created_at'])); ?>
                </time>
            </div>
        </div>
        <div class="article__title">
            <a class="js-clickable js-main-article-link" href="<?="/articles/read/{$data['slug']}";?>"><?= $data['title']; ?></a>
        </div>
        <?php if (!empty($data['tags'])) : ?>
            <div class="article__tags">
                <?php foreach($data['tags'] as $tag) : ?>
                    <a class="tag <?= "tag--$tag" ;?> js-clickable" href="/tags/<?= $tag; ?>">
                        <span>#</span><?= $tag; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['description'])) : ?>
            <div class="article__description">
                <?= $data['description']; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
