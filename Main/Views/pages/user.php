<?php if ($Model->User->uuid === 'guest') : ?>
    <h1>Личный кабинет</h1>
    <span>Недостаточно прав для просмотра данной страницы.</span>
    <span>Войдите или зарегистрируйтесь.</span>
<?php else : ?>
    <div class="user-header__wrapper">
        <nav class="header__nav">
            <a class="header__link" href="#orders">Мои заказы</a>
            <a class="header__link" href="#profile">Личная информация</a>
            <a class="header__link" href="#balance">
                <span>Баланс: <?= $Model->User->balance ?> р.</span>
            </a>
        </nav>
    </div>
    <h1>Личный кабинет</h1>
<?php endif; ?>
