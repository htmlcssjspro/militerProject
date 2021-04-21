<section class="preferences">
    <h1>Настройки</h1>
    <form action="/admin/api/preferences" method="POST">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
        <label>
            <span>Адрес страницы ввода пароля администратора</span>
            <span><input type="text" name="login-url" value="<?= $Model->loginUrl ?>"></span>
        </label>
        <label>
            <span>Новый логин администратора</span>
            <span><input type="text" name="new-login"></span>
        </label>
        <label>
            <span>Новый пароль администратора</span>
            <span><input type="password" name="new-password"></span>
        </label>
        <label>
            <span>Подтверждение</span>
            <span><input type="password" name="confirm-new-password"></span>
        </label>
        <label>
            <span>Текущий пароль администратора</span>
            <span><input type="password" name="password" data-required="required"></span>
        </label>
        <button type="submit">Обновить</button>
    </form>

</section>
