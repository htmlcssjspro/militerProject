<?php
?>

<div class="header__wrapper">
    <a class="header__logo" href="/"></a>
    <nav class="header__nav">
        <?php foreach ($Model->headerNav as $link) : ?>
            <a class="header__link" href="<?= $link['page_url'] ?>"><?= $link['label'] ?></a>
        <?php endforeach; ?>
    </nav>

    <?php if ($Model->guest) : ?>
        <button class="btn_enter" type="button" data-popup=".login">Войти</button>
    <?php else : ?>
        <div class="user">
            <span>Привет <strong><?= $Model->userData['username'] ?></strong>!</span>
            <div class="user__preferences">
                <span>Баланс: <?= $Model->userData['balance'] ?> р.</span>
                <a href="/user">Личный кабинет</a>
                <?php if ($Model->organizator) : ?>
                    <a href="/user#purchases">Мои закупки</a>
                <?php endif; ?>
                <a href="/user#orders">Мои заказы</a>
                <a href="/user#profile">Личная информация</a>
                <button class="btn_logout" type="button" data-href="/api/logout">Выйти</button>
            </div>
        </div>
    <?php endif; ?>
</div>
