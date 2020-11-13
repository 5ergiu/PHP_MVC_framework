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
<nav>test nav</nav>
{{ content }}
<footer>test footer</footer>
</body>
</html>