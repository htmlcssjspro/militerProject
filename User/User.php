<?php

namespace User;

use Militer\mvcCore\User\aUser;

class User extends aUser
{

    public string $uuid;
    public string $name;
    public string $status;
    public string $balance;


    public function __construct()
    {
        parent::__construct();
        $this->init();
    }


    private function init()
    {
        $this->uuid = $_SESSION['user_uuid'] ?? 'guest';
    }

    public function checkEmail($email)
    {
        $sql = "SELECT 1 FROM {$this->usersTable} WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$email]);
        return $pdostmt->fetchColumn();
    }

    public function login($login, $password)
    {
        $loginData = $this->getLoginData($login);
        if (\password_verify($password, $loginData['password'])) {
            $_SESSION['user_uuid'] = $loginData['user_uuid'];
            return  $loginData['user_uuid'];
        }
        return false;
        return \password_verify($password, $loginData['password']) ? $loginData['user_uuid'] : false;
    }
    private function getLoginData($email)
    {
        $sql = "SELECT `user_uuid`, `password` FROM `{$this->usersTable}` WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$email]);
        return $pdostmt->fetch();
    }

    public function insertRegisterData($registerData)
    {
        $sql = "INSERT INTO {$this->usersTable} (
            `user_uuid`,
            `username`,
            `name`,
            `email`,
            `password`,
            `phone`,
            `last_visit`,
            `register_date`
            )
        VALUES (
            :user_uuid,
            :username,
            :name,
            :email,
            :password,
            :phone,
            CURRENT_DATE(),
            CURRENT_DATE()
            )";
        $pdostmt = $this->PDO->prepare($sql);
        return $pdostmt->execute([
            ':user_uuid' => $registerData['userUuid'],
            ':username'  => $registerData['login'],
            ':name'      => $registerData['name'],
            ':email'     => $registerData['email'],
            ':password'  => $registerData['password'],
            ':phone'     => $registerData['phone'],
        ]);
    }

    public function accessRestoreRequest($email)
    {
        $password = $this->generatePassword();
        $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->usersTable} SET `restore_password`='$passwordHash' WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        if ($pdostmt->execute([$email])) {
            $subject = 'Восстановление доступа';
            $message = "
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
                        <td>Логин:</td><td>$email</td>
                        </tr>
                        <tr>
                        <td>Пароль:</td><td>$password</td>
                        </tr>
                        <tr>
                        <td>
                        <form action=\"{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/api/access-restore\" method=\"POST\">
                            <input type=\"hidden\" name=\"csrf\" value=\"{$_SESSION['csrf_token']}\">
                            <input type=\"hidden\" name=\"email\" value=\"$email\">
                            <input type=\"hidden\" name=\"password\" value=\"$password\">
                            <button type=\"submit\">Восстановить доступ</button>
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
        ";
            $additional_headers = [
                'MIME-Version' => '1.0',
                'Content-type' => 'text/html;charset=UTF-8',
                'From'         => $this->config['email']['noreply'],
            ];

            return mail($email, $subject, $message, $additional_headers);
            // imap_mail($to, $subject, $message, $additional_headers, $cc, $bcc, $rpath);
            // imap_mail ( string $to , string $subject , string $message [, string $additional_headers = NULL [, string $cc = NULL [, string $bcc = NULL [, string $rpath = NULL ]]]] ) : bool
        } else {
            return false;
        }
    }


    public function accessRestore($email, $password)
    {
        $restorePasswordHash = $this->getRestorePasswordHash($email);
        if ($this->checkEmail($email) && \password_verify($password, $restorePasswordHash)) {
            $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
            return $this->updatePasswordHash($email, $passwordHash);
        }
        return false;
    }

    private function getRestorePasswordHash($email)
    {
        $sql = "SELECT `restore_password` FROM {$this->usersTable} WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$email]);
        return $pdostmt->fetchColumn();
    }
    private function updatePasswordHash($email, $passwordHash)
    {
        $sql = "UPDATE {$this->usersTable} SET `password`='$passwordHash', `restore_password`=NULL WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        return $pdostmt->execute([$email]);
    }


    public function setUserData()
    {
        $userData = $this->getUserData();
        $this->uuid    = $userData['user_uuid'];
        $this->name    = $userData['user_name'];
        $this->status  = $userData['status'];
        $this->balance = $userData['balance'];
    }
    private function getUserData()
    {
        $sql = "UPDATE {$this->usersTable} SET `last_visit`=CURRENT_DATE() WHERE `user_uuid`='{$this->uuid}'";
        $this->PDO->query($sql);

        $sql = "SELECT `user_uuid`, `username`, `status`, `balance` FROM {$this->usersTable} WHERE `user_uuid`='{$this->uuid}'";
        return $this->PDO->query($sql)->fetch();
    }
}
