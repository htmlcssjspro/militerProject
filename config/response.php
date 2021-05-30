<?php

return [

    'login' => [
        'success' => [
            'message' => 'Успешный вход в систему',
            'reload'  => true,
        ],
        'error' => [
            'message' => 'Неправильный логин или пароль',
        ],
    ],

    'logout' => [
        'success' => [
            'message' => 'Вы вышли из системы',
            'reload'  => true,
        ],
    ],

    'userVerify' => [
        'error' => [
            'message' => 'Неверный пароль'
        ],
    ],

    'register' => [
        'success' => [
            'message' => 'Успешная регистрация',
            'success' => true,
        ],
        'error' => [
            'message' => 'Ошибка регистрации',
        ],
        'exists' => [
            'message' => 'На указанный email уже была зарегистрирована учетная запись пользователя данной системы.<br>Пожалуйста, воспользуйтесь службой восстановления доступа или зарегистрируйте новую учетную запись на другой email.<br>Зарегистрировать более одного аккаунта на один email невозможно.'
        ],
    ],

    'accessRestore' => [
        'success' => [
            'message' => 'На Ваш email был выслан пароль для входа в систему',
            'success' => true,
        ],
        'error' => [
            'message' => 'Ошибка восстановления доступа. Обратитесь к администратору сайта'
        ],
        'noUser' => [
            'message' => 'Учетная запись на указанный email не зарегистрирована'
        ],
    ],

    'userPasswordChange' => [
        'success' => [
            'message' => 'Пароль успешно обновлен',
            'reload' => true,
        ],
        'error' => [
            'message' => 'Ошибка обновления пароля'
        ],
    ],

    'adminLogin' => [
        'success' => [
            'message' => 'Успешный вход в систему',
            'reload'  => true,
        ],
        'error' => [
            'message' => 'Неправильный логин или пароль'
        ],
    ],

    'adminLogout' => [
        'success' => [
            'message' => 'Вы вышли из системы',
            'reload'  => true,
        ],
    ],

    'adminVerify' => [
        'error' => [
            'message' => 'Неверный пароль администратора'
        ],
    ],

    'adminPreferences' => [
        'success' => [
            'message' => 'Настройки успешно обновлены',
        ],
        'error' => [
            'message' => 'Ошибка обновления настроек'
        ],
    ],

    'adminPasswordChange' => [
        'success' => [
            'message' => 'Пароль администратора успешно обновлен',
            'reload' => true,
        ],
        'error' => [
            'message' => 'Ошибка обновления пароля администратора'
        ],
    ],

    'adminAdd' => [
        'success' => [
            'message' => 'Новый администратор успешно добавлен',
        ],
        'error' => [
            'message' => 'Ошибка добавления нового администратора'
        ],
        'exists' => [
            'message' => 'Администратор с указанным email уже существует'
        ],
    ],

];
