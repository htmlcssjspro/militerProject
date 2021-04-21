<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <meta name="description" content="Login">

    <meta name="author" content="Sergei MILITER Tarasov https://htmlcssjs.pro">

    <style>
        .main{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login{
            width: 200px;
        }
    </style>
</head>

<body>

    <main id="main" class="main">
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
    </main>

    <script>
        'use strict';
        document.querySelector('.btn_login').addEventListener('click', event=>{
            event.preventDefault();
            const $form = document.querySelector('form');
            fetch($form.action, {method: $form.method, body: new FormData($form)});
        });
    </script>

</body>

</html>
