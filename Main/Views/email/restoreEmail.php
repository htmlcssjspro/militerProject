<html>

<head>
    <title>Восстановление доступа</title>
</head>

<body>
    <p>Здравствуйте!</p>
    <p>Получен запрос на восстановление доступа к системе</p>
    <p>Ваши новые данные для доступа:</p>
    <table>
        <tr>
            <td>Логин:</td>
            <td><?= $email ?></td>
        </tr>
        <tr>
            <td>Пароль:</td>
            <td><?= $password ?></td>
        </tr>
        <tr>
            <td>
                <form action="<?= "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/api/access-restore" ?>" method="POST">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="email" value="<? $email ?>">
                    <input type="hidden" name="password" value="<?= $password ?>">
                    <button type="submit">Восстановить доступ</button>
                </form>
            </td>
        </tr>
    </table>
    <p>Сохраните эти данные</p>
    <p>В случае их утери, или при подозрении в их утечке, воспользуйтесь системой восстановления доступа для создания нового пароля</p>
    <p>Если Вы не отправляли запрос, то проигнорируйте это сообщение</p>
    <p>Успешной работы!</p>
</body>

</html>
