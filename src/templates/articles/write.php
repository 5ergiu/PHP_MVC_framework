<?php

use App\Core\View;
use App\Entity\Article;
/**
 * @var View $this
 * @var Article $Article
 */
$this->includeCss(['articles']);
?>
<section class="write">
    <div id="js-article-spinner" class="spinner hide"></div>
    <div class="write__actions">
        <button class="button button--outline button--outline--active" id="js-button-edit">Edit</button>
        <button class="button button--outline" id="js-button-preview">Preview</button>
    </div>
    <div class="write__edit" id="js-article-edit">
        <?php
            echo $this->form->create($Article, [
                'class' => 'write__form',
                'autocomplete' => 'off',
            ]);
            echo $this->form->input('cover', [
                'type' => 'hidden',
                'id' => 'js-article-cover',
            ]);
            echo $this->form->input('title', [
                'type' => 'text',
                'class' => 'write__input',
                'placeholder' => 'New article title here...',
            ]);
        ?>
        <div class="write__tags">
            <p>Tag your article:</p>
            <?php foreach ($tags as $key => $tag) : ?>
                <div>
                    <input
                        class="write__tags_input"
                        type="checkbox"
                        value="<?= $tag['id']; ?>"
                        name="tags[<?= $key ;?>]"
                        title="<?= $tag['description']; ?>"
                    />
                    <label class="tag <?= "tag--{$tag['name']}"; ?>">
                        #<?= $tag['name']; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
            echo $this->form->textarea('content', [
                'id' => 'js-markdown',
                'class' => 'write__textarea',
                'rows' => '10',
                'placeholder' => 'Write your article here...',
            ]);
            echo $this->form->end();
        ?>
    </div>
    <div class="write__preview hide" id="js-article-preview">
        <article class="article">
            <div class="article__cover">
                <div class="article__cover_image"></div>
            </div>
            <div class="article__container">
                <div class="article__title">
                    Your article's title
                </div>
                <div class="article__tags">
<!--                    <a class="tag" href="/tags/needtomodifyandaddtag">-->
<!--                        <span>#</span>needtomodifyandaddtag-->
<!--                    </a> -->
                    <a class="tag" href="#">
                        <span>#</span>tags
                    </a>
                </div>
                <div class="article__content" id="js-article-content">
                    Your article's content
                </div>
            </div>
        </article>
    </div>
    <div class="write__buttons">
        <button class="button" type="submit">Submit for review</button>
        <button id="js-button-draft" class="button button--secondary" type="submit">Save draft</button>
    </div>
</section>
