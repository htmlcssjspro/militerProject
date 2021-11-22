<!DOCTYPE html>
<html lang="ru">

<head>
    <?php $this->getHead(); ?>
    <?= $this->getLayoutCSS() ?>
    <?= $this->getMainCSS(true) ?>
    <?= $this->getLayoutJS(true) ?>
    <?= $this->getMainJS(true) ?>
</head>

<body class="body">

    <header id="header" class="header">
        <?php $this->getHeader(); ?>
    </header>

    <div class="content">
        <aside id="aside" class="aside">
            <?php $this->getAside(); ?>
        </aside>

        <main id="main" class="main">
            <?php $this->getMainContent(); ?>
        </main>
    </div>

    <footer id="footer" class="footer">
        <?php $this->getFooter(); ?>
    </footer>


    <?= $this->getMainCSS(); ?>
    <?= $this->getLayoutJS(); ?>
    <?= $this->getMainJS(); ?>


    <!-- Test -->
    <?php require _ROOT_ . '/Admin/Views/components/test.php'  ?>
    <!-- Test -->

</body>

</html>
