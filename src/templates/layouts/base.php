<?php
use App\Core\Renderer;
use App\Entity\User;
/**
 * @var $this Renderer
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog'; ?></title>
    <link type="text/css" rel="stylesheet" href="<?= VENDOR . 'fontawesome/css/all.min.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'main.css'; ?>" />
    <?php if (!empty($this->customCss)) :
        foreach ($this->customCss as $css) : ?>
            <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . $css; ?>" />
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
</head>
<body>
<nav class="navigation">
    <div class="wrapper">
        <a class="logo" href="/">
            <img class="logo__img" src="<?= ASSETS_IMG . 'logo.png'; ?>" alt="Logo" />
        </a>
        <!--    ADD SEARCH!! -->
        <!--    <form class="d-flex mb-0">-->
        <!--        <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">-->
        <!--        <button class="btn btn-success" type="submit">Search</button>-->
        <!--    </form>-->
        <ul class="navigation__menu">
            <li>
                <a class="button button--gradient" href="/articles/write">
                    Write an article
                </a>
            </li>
            <li class="dropdown">
                <?php if (!empty($this->user)) : ?>
                    <button class="dropdown__toggler avatar">
                        <img src="<?= ASSETS_IMG . "{$this->user['image']}"; ?>" alt="profile" />
                    </button>
                    <div class="dropdown__content">
                        <ul id="js-navigation-user" class="navigation__user">
                            <div id="js-logout-loading-spinner" class="spinner hide"></div>
                            <?php if ($this->user['role'] === User::ROLE_ADMIN) : ?>
                                <li class="dropdown__item navigation__user__admin">
                                    <a href="/admin">💪 Admin</a>
                                </li>
                            <?php endif; ?>
                            <li class="dropdown__item">
                                <a href="/account/dashboard">🖥️ Dashboard</a>
                            </li>
                            <?php if ($this->user['role'] === User::ROLE_ADMIN || $this->user['role'] === User::ROLE_AUTHOR) : ?>
                                <li class="dropdown__item">
                                    <a href="/account/drafts">
                                        📋 Drafts
                                        <span class="navigation__user_counter" id="draftsCount" >
                                            <?= isset($data['user']['drafts_count']) ? $data['user']['drafts_count'] : 0; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="dropdown__item">
                                <a href="/account/reading-list">
                                    📚 Reading list
                                    <span class="navigation__user_counter" id="bookmarksCount">
                                        <?= isset($data['user']['bookmarks_count']) ? $data['user']['bookmarks_count'] : 0; ?>
                                    </span>
                                </a>
                            </li>
                            <li class="dropdown__item">
                                <a href="/account/settings">🛠️ Account Settings</a>
                            </li>
                            <li>
                                <button class="button button--secondary" id="js-logout">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                <?php else : ?>
                    <button class="button dropdown__toggler">
                        Login
                    </button>
                    <div class="dropdown__content">
                        <?php
                            echo $this->form->create(null, [
                                'class' => 'login',
                                'id' => 'js-login',
                                'autocomplete' => 'off',
                                'action' => 'auth/login',
                            ]);
                        ?>
                        <div id="js-login-loading-spinner" class="spinner hide"></div>
                        <div id="js-login-message" class="login__message">Hello there! 👋</div>
                        <?php
                            echo $this->form->input('username', [
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
    <img id="js-notification-icon" class="notification__icon"
     <?php if (isset($this->notification['icon'])) : ?>
         src="<?= ASSETS_IMG . "{$this->notification['icon']}"; ?>"
     <?php endif; ?>
         alt="Notice: "
    />
    <p id="js-notification-message" class="notification__message">
        <?= $this->notification['message'] ?? null; ?>
    </p>
</div>
<script type="module" src="<?= ASSETS_JS . 'app.js'; ?>"></script>
</body>
</html>
