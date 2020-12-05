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
    <div class="write__actions">
        <button class="button button--outline button--outline--active" id="js-button-edit">Edit</button>
        <button class="button button--outline" id="js-button-preview">Preview</button>
    </div>
    <div class="write__edit">
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
                    <label class="write__tags_label"
                           style="background-color: <?= $tag['color']; ?>"
                    >#<?= $tag['name']; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
            echo $this->form->textarea('content', [
                'id' => 'js-article-content',
                'class' => 'write__textarea',
                'rows' => '10',
                'placeholder' => 'Write your article here...',
            ]);
            echo $this->form->end();
        ?>
    </div>
    <div class="write__buttons">
        <button class="button" type="submit">Submit for review</button>
        <button id="js-button-draft" class="button button--secondary" type="submit">Save draft</button>
    </div>
    <div class="write__preview hide">
        testing preview
    </div>
</section>
