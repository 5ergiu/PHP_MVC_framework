<section class="article">
    <aside class="sticky">
        <div class="article__bookmark">-->
            <button
                class="button js-button-bookmark <?= $article['bookmarked_by_logged_user'] ? 'button--bookmarked' : null; ?>"
                type="button"
                data-article-id="<?=$article['id'];?>"
                title="<?= $article['bookmarked_by_logged_user'] ? 'Remove bookmark' : 'Bookmark article'; ?>"
            >
                <?= $article['bookmarked_by_logged_user'] ? 'UNSAVE' : 'SAVE'; ?>
            </button>
            <div class="js-bookmark-spinner spinner hide"></div>
        </div>
        <div class="article__like">
            <button
                class="button--like <?= $article['liked_by_logged_user'] ? 'button--liked' : null; ?>"
                type="button"
                data-article-id="<?=$article['id'];?>"
                title="<?= $article['liked_by_logged_user'] ? 'Unlike article' : 'Like article'; ?>"
            >
            </button>
            <div class="js-like-spinner spinner hide"></div>
        </div>
    </aside>
    <?php $this->element('article', $article, 'articles'); ;?>
    <aside class="sticky">
        <div class="article__info">
            <a class="avatar" href="/users/<?= $article['username']; ?>">
                <img src="<?= ASSETS_IMG . "{$article['avatar']}"; ?>" alt="profile" />
            </a>
            <div>
                <a href="/users/<?= $article['username']; ?>">
                    <?= $article['username']; ?>
                </a>
            </div>
            <p><?= $article['user_summary'] ;?></p>
            <p>Joined</p>
            <p><?= date("M jS, Y", strtotime($article['user_joined'])); ?></p>
        </div>
        <div>
            <h3>More from <a href="/users/<?= $article['username'] ;?>"><?= $article['username'] ;?></a></h3>
            <?php foreach ($article['short_articles'] as $shortArticle) : ?>
                <div>
                    <a href="<?= $shortArticle['slug'] ;?>"><?= $shortArticle['title'] ;?></a>
                    <div>
                        <?php if (!empty($shortArticle['tags'])) :
                            foreach ($shortArticle['tags'] as $tag) : ?>
                                <a class="tag" href="/tags/<?= $tag ;?>">
                                    <span>#</span><?= $tag; ?>
                                </a>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </aside>
</section>
