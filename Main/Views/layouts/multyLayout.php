<!DOCTYPE html>
<html lang="ru">

<head>
    <?php $this->getComponent('head'); ?>
</head>

<body class="body">

    <header id="header" class="header">
        <?php $this->getComponent('header'); ?>
    </header>

    <div class="content">
        <div class="content__wrapper">
            <main id="main" class="main">
                <?php $this->getMainContent(); ?>
            </main>

            <aside id="aside" class="aside">
                <?php $this->getComponent('aside'); ?>
            </aside>
        </div>
    </div>

    <footer id="footer" class="footer">
        <?php $this->getComponent('footer'); ?>
    </footer>


    <?= $this->getMainCSS(); ?>
    <?= $this->getLayoutJS(); ?>
    <?= $this->getMainJS(); ?>

</body>

</html>
