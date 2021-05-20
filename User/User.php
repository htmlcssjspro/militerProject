<?php

namespace User;

use Militer\mvcCore\User\aUser;
use Ramsey\Uuid\Uuid;

class User extends aUser
{

    public string $uuid    = 'guest';
    public string $name    = 'Гость';
    public string $status  = 'guest';
    public string $balance = '0';

    private $table = self::USERS_TABLE;


    public function __construct()
    {
        parent::__construct();
        $this->init();
    }


    private function init()
    {
        if (!empty($_SESSION['user_uuid'])) {
            $this->uuid = $_SESSION['user_uuid'];
            $this->setUserData();
        }
    }
    private function setUserData()
    {
        \extract($this->getUserData());
        $this->name    = $username;
        $this->status  = $status;
        $this->balance = $balance;
    }
    private function getUserData()
    {
        $sql = "UPDATE {$this->table} SET `last_visit`=CURRENT_DATE() WHERE `user_uuid`='{$this->uuid}'";
        self::$PDO::query($sql);

        $sql = "SELECT `username`, `status`, `balance` FROM {$this->table} WHERE `user_uuid`='{$this->uuid}'";
        return self::$PDO::queryFetch($sql);
    }

    public function checkEmail($email)
    {
        $table = self::USERS_TABLE;
        $sql = "SELECT 1 FROM {$table} WHERE `email`=?";
        return self::$PDO::prepFetchColumn($sql, $email);
    }

    public function login($loginData)
    {
        \extract($loginData);
        $loginData = $this->getLoginData($login);
        if (\password_verify($password, $loginData['password'])) {
            $_SESSION['user_uuid'] = $loginData['user_uuid'];
            $this->Response->sendResponse('login', true);
        }
        $this->Response->sendResponse('login', false);
    }
    private function getLoginData($email)
    {
        $sql = "SELECT `user_uuid`, `password` FROM `{$this->table}` WHERE `email`=?";
        return self::$PDO::prepFetch($sql, $email);
    }
    public function logout()
    {
        unset($_SESSION['user_uuid'], $_SESSION['status']);
        $this->Response->sendMessage('logout', true);
    }

    private function insertRegisterData($registerData)
    {
        $sql = "INSERT INTO {$this->table} (
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

        $params = [
            ':user_uuid' => $registerData['userUuid'],
            ':username'  => $registerData['login'],
            ':name'      => $registerData['name'],
            ':email'     => $registerData['email'],
            ':password'  => $registerData['password'],
            ':phone'     => $registerData['phone'],
        ];

        return self::$PDO::execute($sql, $params);
    }

    public function accessRestoreRequest($email)
    {
        $password = $this->generatePassword();
        $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET `restore_password`='$passwordHash' WHERE `email`=?";
        // $pdostmt = $this->PDO->prepare($sql);
        if (self::$PDO::execute($sql, $email)) {
            // if ($pdostmt->execute([$email])) {
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
        $sql = "SELECT `restore_password` FROM {$this->table} WHERE `email`=?";
        return self::$PDO::prepFetchColumn($sql, $email);
    }
    private function updatePasswordHash($email, $passwordHash)
    {
        $sql = "UPDATE {$this->table} SET `password`='{$passwordHash}', `restore_password`=NULL WHERE `email`=?";
        return self::$PDO::execute($sql, $email);

        // $pdostmt = $this->PDO->prepare($sql);
        // return $pdostmt->execute([$email]);
    }
}
