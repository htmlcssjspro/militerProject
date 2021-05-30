<?php
?>

<div class="header__wrapper">
    <a class="header__logo" href="/"></a>
    <nav class="header__nav">
        <?php foreach ($Model->getHeaderNav() as $link) : ?>
            <a class="header__link" href="/<?= $link['page_uri'] ?>"><?= $link['label'] ?></a>
        <?php endforeach; ?>
    </nav>

    <?php if ($Model->User->uuid === 'guest') : ?>
        <button class="btn_enter" type="button" data-popup="login">Войти</button>
    <?php else : ?>
        <?php require '../sections/userNav.php'; ?>
    <?php endif; ?>
</div>
