<html>

<head>
    <title><?= $subject ?></title>
</head>

<body>
    <p>Здравствуйте, <?= $name ?>!</p>
    <p>Вам предоставлен доступ к панели администратора</p>
    <p>Ваши данные для доступа:</p>
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
            <td colspan="2">
                <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="email" value="<? $email ?>">
                    <input type="hidden" name="password" value="<?= $password ?>">
                    <input type="submit" value="Активировать учетную запись администратора">
                </form>
            </td>
        </tr>
    </table>
    <p>Сохраните эти данные</p>
    <p>Если Вы не отправляли запрос, то проигнорируйте это сообщение</p>
    <p>Успешной работы!</p>
</body>

</html>
