<?php
use App\Core\View;
use App\Entity\User;
/**
 * @var $this View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog'; ?></title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link type="text/css" rel="stylesheet" href="<?= VENDOR . 'fontawesome/css/all.min.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= VENDOR . 'highlight/styles/atom-one-dark.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'main.css'; ?>" />
    <?php if (!empty($this->css)) :
        foreach ($this->css as $style): ?>
            <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . $style; ?>" />
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<nav class="navigation">
    <div class="wrapper">
        <a class="logo" href="/">
            <img class="logo__img" src="<?= ASSETS_IMG . 'logo.png'; ?>" alt="Logo" />
        </a>
        <?php
            echo $this->form->create(null, [
                'action' => '/articles/search',
                'method' => 'get',
                'class' => 'navigation__search',
            ]);
            echo $this->form->input('search', [
                'type' => 'text',
                'placeholder' => 'Search articles',
            ]);
            echo $this->form->button('<i class="fas fa-search"></i>', [
                'type' => 'submit',
            ]);
            echo $this->form->end();
        ?>
        <ul class="navigation__menu">
            <?php if (!empty($this->user)) : ?>
                <li>
                    <a class="button button--gradient" href="/articles/write">
                        Write an article
                    </a>
                </li>
            <?php endif; ?>
            <li class="dropdown">
                <?php if (!empty($this->user)) : ?>
                    <div class="dropdown__toggler avatar">
                        <img src="<?= ASSETS_IMG . "{$this->user['image']}"; ?>" alt="profile" />
                    </div>
                    <div class="dropdown__content">
                        <ul id="js-navigation-user" class="navigation__user">
                            <div id="js-logout-loading-spinner" class="spinner hide"></div>
                            <?php if ($this->user['role'] === User::ROLE_ADMIN || User::ROLE_AUTHOR) : ?>
                                <li class="dropdown__item">
                                    <a href="/articles/write">
                                        ✍️ Write an article
                                    </a>
                                </li>
                                <?php if ($this->user['role'] === User::ROLE_ADMIN) : ?>
                                    <li class="dropdown__item">
                                        <a href="/admin">💪 Admin</a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <li class="dropdown__item">
                                <a href="/account/dashboard">🖥️ Dashboard</a>
                            </li>
                            <?php if ($this->user['role'] === User::ROLE_ADMIN || $this->user['role'] === User::ROLE_AUTHOR) : ?>
                                <li class="dropdown__item">
                                    <a href="/account/drafts">
                                        📋 Drafts
                                        <span class="navigation__user_counter" id="draftsCount" >
                                            <?= $this->user['drafts']; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="dropdown__item">
                                <a href="/account/reading-list">
                                    📚 Reading list
                                    <span class="navigation__user_counter" id="js-user-bookmarks-count">
                                        <?= $this->user['bookmarks']; ?>
                                    </span>
                                </a>
                            </li>
                            <li class="dropdown__item">
                                <a href="/account/settings">🛠️ Account Settings</a>
                            </li>
                            <li>
                                <?php
                                    echo $this->form->create(null, [
                                        'action' => 'auth/logout',
                                    ]);
                                    echo $this->form->button('Logout', [
                                        'class' => 'button button--secondary',
                                        'type' => 'submit',
                                    ]);
                                ?>
                            </li>
                        </ul>
                    </div>
                <?php else : ?>
                    <button class="button dropdown__toggler" id="js-login-button">
                        Login
                    </button>
                    <div class="dropdown__content">
                        <?php
                            echo $this->form->create(null, [
                                'class' => 'login',
                                'id' => 'js-login',
                                'autocomplete' => 'off',
                            ]);
                        ?>
                        <div id="js-login-loading-spinner" class="spinner hide"></div>
                        <div id="js-login-message" class="login__message">Hello there! 👋</div>
                        <?php
                            echo $this->form->input('username', [
                                'type' => 'text',
                                'placeholder' => 'Enter username',
                            ]);
                            echo $this->form->input('password', [
                                'type' => 'password',
                                'placeholder' => 'Enter password',
                            ]);
                        ?>
                        <div class="login__buttons">
                        <?php
                            echo $this->form->button('Login', [
                                'type' => 'submit',
                                'class' => 'button',
                            ]);
                        ?>
                            <a class="button button--secondary" href="/auth/register">
                                Register
                            </a>
                        </div>
                        <?php
                            echo $this->form->end();
                        ?>
                    </div>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>
<main class="wrapper">
    {{ content }}
</main>
<footer>
    <div class="wrapper">
        test footer
    </div>
</footer>
<div id="js-notification"  class="notification">
    <p id="js-notification-icon" class="notification__icon">
        <?= $this->notification['icon'] ?? null; ?>
    </p>
    <p id="js-notification-message" class="notification__message">
        <?= $this->notification['message'] ?? null; ?>
    </p>
</div>
<script type="module" src="<?= ASSETS_JS . 'app.js'; ?>"></script>
<script src="<?= VENDOR . 'highlight/highlight.pack.js'; ?>"></script>
<script>hljs.initHighlightingOnLoad();</script>
<?php if (!empty($this->javascript)) :
    foreach ($this->javascript as $script): ?>
        <script src="<?= ASSETS_JS . $script; ?>"> </script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
