<!DOCTYPE html>
<html lang="ru">

<head>
    <?php require $Model->head; ?>
</head>

<body>

    <main id="main" class="main">
        <?php require $Model->mainContent; ?>
    </main>

    <?php if ($Model->pageCSS) : ?>
        <link rel="stylesheet" href="<?= $Model->pageCSS ?>">
    <?php endif; ?>

    <script defer src="<?= $Model->mainJS ?>"></script>

    <?php if ($Model->pageJS) : ?>
        <script defer src="<?= $Model->pageJS ?>"></script>
    <?php endif; ?>

</body>

</html>
