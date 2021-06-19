<!DOCTYPE html>
<html lang="ru">

<head>
    <?php $this->getComponent('head'); ?>
</head>

<body class="body">

    <main id="main" class="main">
        <?php $this->getMainContent(); ?>
    </main>

    <?= $this->getMainCSS(); ?>
    <?= $this->getLayoutJS(); ?>
    <?= $this->getMainJS(); ?>

</body>

</html>
