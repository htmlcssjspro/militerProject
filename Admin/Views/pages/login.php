<h1><?= $Model->h1 ?></h1>
<section class="login">
    <div class="form-section__wrapper">
        <form action="/admin/api/login" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <label>Логин<input name="login" type="text"></label>
            <label>Пароль<input name="password" type="password"></label>
            <br>
            <br>
            <button class="btn_login" type="submit">Войти</button>
        </form>
    </div>
</section>
