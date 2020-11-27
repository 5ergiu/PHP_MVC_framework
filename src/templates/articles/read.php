<section class="read">
    <aside class="read__actions sticky">
        <div class="article__bookmark">
            <button
                class="button button--bookmark js-button-bookmark <?= $article['bookmarked_by_logged_user'] ? 'button--bookmarked' : null; ?>"
                type="button"
                data-article-id="<?=$article['id'];?>"
                title="<?= $article['bookmarked_by_logged_user'] ? 'Remove bookmark' : 'Bookmark article'; ?>"
            >
                <?= $article['bookmarked_by_logged_user'] ? '📚' : '<i class="far fa-bookmark"></i>'; ?>
            </button>
            <p class="muted" id="js-bookmarks-count">
                <?= $article['bookmarks'] ;?>
            </p>
            <div class="js-bookmark-spinner spinner hide"></div>
        </div>
        <div class="article__like">
            <button
                class="button button--like <?= $article['liked_by_logged_user'] ? 'button--liked' : null; ?>"
                type="button"
                data-article-id="<?=$article['id'];?>"
                title="<?= $article['liked_by_logged_user'] ? 'Unlike article' : 'Like article'; ?>"
            >
                <?= $article['liked_by_logged_user'] ? '❤️' : '<i class="far fa-heart"></i>'; ?>
            </button>
            <p class="muted" id="js-likes-count">
                <?= $article['likes'] ;?>
            </p>
            <div class="js-like-spinner spinner hide"></div>
        </div>
    </aside>
    <?php $this->element('article', $article, 'articles'); ?>
    <aside class="read__author sticky">
        <div>
            <div>
                <div class="avatar">
                    <a href="/users/<?= $article['username']; ?>">
                        <img src="<?= ASSETS_IMG . "{$article['avatar']}"; ?>" alt="profile" />
                    </a>
                </div>
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
            <div>
                <?php foreach ($article['suggestions'] as $suggestion) : ?>
                    <div class="article__tags">
                        <a href="<?= $suggestion['title'] ;?>"><?= $suggestion['title'] ;?></a>
                        <div>
                            <?php if (!empty($suggestion['tags'])) :
                                foreach ($suggestion['tags'] as $tag) : ?>
                                    <a class="tag" href="/tags/<?= $tag ;?>">
                                        <span>#</span><?= $tag; ?>
                                    </a>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>
</section>
