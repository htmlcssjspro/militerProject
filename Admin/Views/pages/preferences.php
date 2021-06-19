<h1><?= $this->h1 ?></h1>
<section class="preferences">
    <form action="/admin/api/admin-password-change" method="POST">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
        <label>
            <span>Новый пароль администратора</span>
            <span><input type="password" name="new_password"></span>
        </label>
        <label>
            <span>Подтверждение</span>
            <span><input type="password" name="confirm_new_password"></span>
        </label>
        <label>
            <span>Текущий пароль администратора</span>
            <span><input type="password" name="password" data-required="required"></span>
        </label>
        <button type="submit">Обновить</button>
    </form>

    <?php if ($this->User->adminStatus === 'superadmin') : ?>
        <form action="/admin/api/add-new-admin" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <label>
                <span>Email нового администратора</span>
                <span><input type="email" name="email"></span>
            </label>
            <label>
                <span>Имя нового администратора</span>
                <span><input type="text" name="name"></span>
            </label>
            <label>
                <span>Текущий пароль суперадмина</span>
                <span><input type="password" name="password" data-required="required"></span>
            </label>
            <button type="submit">Добавить нового администратора</button>
        </form>
    <?php endif; ?>

</section>
