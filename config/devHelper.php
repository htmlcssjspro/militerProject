<?php


function pr($exp, ?string $name = '')
{
    pre('print_r', $exp, $name);
}

function prd($exp, ?string $name = '')
{
    pr($exp, $name);
    showTime();
    exit;
}

function vd($exp, ?string $name = '')
{
    pre('var_dump', $exp, $name);
}

function vdd($exp, ?string $name = '')
{
    vd($exp, $name);
    showTime();
    exit;
}

function eco($exp = null, ?string $name = '')
{
    echo '<br><pre>';
    if ($name) {
        echo "<strong>### $name: ###</strong>   ";
    }
    echo $exp;
    echo '</pre><br>';
}


function pre(string $func, $exp = null, ?string $name = '')
{
    echo '<br><pre>';
    if ($name) {
        echo "<strong>### $name: ###</strong><br>";
    }
    call_user_func($func, $exp);
    echo '<br>';
    debug_print_backtrace();
    echo '</pre><br>';
}

function showTime()
{
    $time = round((microtime(true) - MILITER_START) * 1000, 3);
    echo "<p>Время выполнения скрипта: <strong>{$time}</strong> мс.</p>";
}

function method($method)
{
    echo '<br>Метод <strong>' . $method . '</strong>';
}

function whoAmI($file)
{
    echo '<br>Я файл <strong>' . basename($file) . '</strong> подключился из папки <strong>' . dirname($file) . '</strong><br>';
}

function newClassInstance($class)
{
    echo '<br>Cоздан объект класса <strong>' . $class . '</strong><br>';
}

function showRelPath($file)
{
    $absolute_path = dirname($file);
    $relative_path = str_replace(_ROOT_, '', $absolute_path);
    echo '<br>Относительный путь: ' . $relative_path . '<br>';
}

function server()
{
    $server_arr = [
        "DOCUMENT_ROOT",
        "REQUEST_SCHEME",
        "SERVER_NAME",
        "HTTP_HOST",
        "HTTP_USER_AGENT",
        "PHP_SELF",
        "SCRIPT_NAME",
        "SCRIPT_FILENAME",
        "REQUEST_METHOD",
        "REDIRECT_URL",
        "REQUEST_URI",
        "QUERY_STRING",
    ];

    echo '<br><table>';
    foreach ($server_arr as $value) {
        echo "<tr><td>\$_SERVER[$value]</td><td> ==> </td><td>$_SERVER[$value]</td></tr>";
    }
    echo '</table><br>';
}
