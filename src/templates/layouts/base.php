<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Title'; ?></title>
    <link type="text/css" rel="stylesheet" href="<?= VENDOR . 'fontawesome/css/all.min.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'main.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'login.css'; ?>" />
    <link type="text/css" rel="stylesheet" href="<?= ASSETS_CSS . 'articles.css'; ?>" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
</head>
<body>
<nav class="navigation">
    <a class="logo" href="/">
        <img class="logo__img" src="<?= ASSETS_IMG . 'logo.svg'; ?>" alt="Logo" />
        <p>Blog</p>
    </a>
    <!--    ADD SEARCH!! -->
    <!--    <form class="d-flex mb-0">-->
    <!--        <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">-->
    <!--        <button class="btn btn-success" type="submit">Search</button>-->
    <!--    </form>-->
    <ul class="navigation__menu">
        <li>
            <button class="button button--gradient" onclick="location.href='/articles/write'" type="button">
                Write an article
            </button>
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
                        <button class="button dropdown__toggler" role="button">
                            Login
                        </button>
                        <div class="dropdown__content">
                            <?php
                                echo $this->form->create(null, [
                                    'id' => 'login',
                                    'autocomplete' => 'off',
                                ]);
                                echo $this->form->input('username');
                                echo $this->form->input('password', [
                                    'type' => 'password',
                                ]);
                                echo $this->form->submit();
                                echo $this->form->end();
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </li>
    </ul>
</nav>
<main>
    {{ content }}
</main>
<footer>test footer</footer>
</body>
</html>