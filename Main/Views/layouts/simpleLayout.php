<!DOCTYPE html>
<html lang="ru">

<head>
    <?php require $this->head; ?>
</head>

<body class="body">

    <main id="main" class="main">
        <?php require $this->mainContent; ?>
    </main>


    <?php if ($this->pageCSS) : ?>
        <link rel="stylesheet" href="<?= $this->pageCSS ?>">
    <?php endif; ?>

    <script defer src="<?= $this->mainJS ?>"></script>

    <?php if ($this->pageJS) : ?>
        <script defer src="<?= $this->pageJS ?>"></script>
    <?php endif; ?>

</body>

</html>
