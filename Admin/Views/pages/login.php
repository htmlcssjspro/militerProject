<h1><?= $this->h1 ?></h1>
<section class="login">
    <div class="form-section__wrapper">
        <form action="/admin/api/login" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <label><span>Логин</span><span><input name="login" type="text"></span></label>
            <label><span>Пароль</span><span><input name="password" type="password"></span></label>
            <br>
            <br>
            <button class="btn_login" type="submit">Войти</button>
        </form>
    </div>
</section>
