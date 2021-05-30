<!DOCTYPE html>
<html lang="ru">

<head>
    <?php require $Model->head; ?>
</head>

<body class="body">

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
