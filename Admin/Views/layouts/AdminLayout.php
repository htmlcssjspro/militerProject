<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $Model->title ?></title>
    <meta name="description" content="<?= $Model->description ?>">

    <meta name="author" content="Sergei MILITER Tarasov https://htmlcssjs.pro">

    <link rel="preload" href="<?= $Model->mainCSS ?>" as="style">
    <link rel="preload" href="<?= $Model->mainJS ?>" as="script">

    <link rel="stylesheet" href="<?= $Model->mainCSS ?>">

</head>

<body>

    <header id="header" class="header">
        <?php require $Model->header; ?>
    </header>

    <div class="content">
        <aside id="aside" class="aside">
            <?php require $Model->aside; ?>
        </aside>

        <main id="main" class="main">
            <?php require $Model->mainContent; ?>
        </main>
    </div>

    <footer id="footer" class="footer">
        <?php require $Model->footer; ?>
    </footer>


    <?php if ($Model->layoutPopups) {
        foreach ($Model->layoutPopups as $layoutPopup) {
            require $layoutPopup;
        }
    } ?>

    <?php if ($Model->pageCSS) : ?>
        <?php foreach ($Model->pageCSS as $pageCSS) : ?>
            <link rel="stylesheet" href="<?= $pageCSS ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <script defer src="<?= $Model->mainJS ?>"></script>

    <?php if ($Model->pageJS) : ?>
        <?php foreach ($Model->pageJS as $pageJS) : ?>
            <script defer src="<?= $pageJS ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>
