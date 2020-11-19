<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog'; ?></title>
    <link type="text/css" rel="stylesheet" href="<?= VENDOR . 'fontawesome/css/all.min.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'main.css'; ?>" />
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
            <li>
                <div class="dropdown">
                    <?php if (isset($data['user'])) : ?>
                        <div>
                            <button class="dropdown__toggler profile-image" role="button">
                                <img src="<?= ASSETS_IMG . "{$data['user']['image']}"; ?>" alt="profile" />
                            </button>
                            <ul class="dropdown__content">
                                <?php if ($data['user']['role'] === User::ADMIN) : ?>
                                    <li class="dropdown__item navigation__user__admin"><a href="/admin">Admin</a></li>
                                <?php endif; ?>
                                <li class="dropdown__item">
                                    <a href="/account/reading-list">
                                        Reading list
                                    </a>
                                    <span class="counter" id="bookmarksCount" >
                                    <?= isset($data['user']['bookmarks_count']) ? $data['user']['bookmarks_count'] : 0; ?>
                                </span>
                                </li>
                                <li class="dropdown__item"><a href="/account/dashboard">Dashboard</a></li>
                                <li class="dropdown__item">
                                    <a href="/account/drafts">
                                        Drafs
                                    </a>
                                    <span class="counter" id="draftsCount" >
                                    <?= isset($data['user']['drafts_count']) ? $data['user']['drafts_count'] : 0; ?>
                                </span>
                                </li>
                                <li class="dropdown__item"><a href="/account/settings">Account Settings</a></li>
                                <li>
                                    <button class="button button--secondary" onclick="location.href='/auth/logout'" type="button">
                                        Logout
                                    </button>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <div>
                            <button class="button dropdown__toggler">
                                Login
                            </button>
                            <div class="dropdown__content">
                                <?php
                                    echo $this->form->create(null, [
                                        'id' => 'js-login',
                                        'class' => 'login',
                                        'autocomplete' => 'off',
                                    ]);
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
                                        'class' => 'button'
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
                        </div>
                    <?php endif; ?>
                </div>
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
<!--<div id="main-spinner" class="spinner hide" role="status"><div></div><div></div><div></div><div></div></div>-->
<!--<div class="notification">-->
<!--    <img class="notification__image"-->
<!--        --><?php //if (isset($data['response']['icon'])) : ?>
<!--            src="--><?//= ASSETS_IMG . "{$data['response']['icon']}"; ?><!--"-->
<!--        --><?php //endif; ?>
<!--         alt="img" />-->
<!--    <p class="notification__message">-->
<!--        --><?//= $data['response']['message'] ?? null; ?>
<!--    </p>-->
<!--</div>-->
<script type="module" src="<?= ASSETS_JS . 'app.js'; ?>"></script>
</body>
</html>
