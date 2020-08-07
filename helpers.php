<?php

function autoloadHandler($class)
{
    $path = str_replace('\\', '/', $class . '.php');
    if (file_exists($path)) {
        require $path;
    }
}

function dd($param)
{
    var_dump($param);
    die();
}

define('CYRILLIC', [
    'а', 'ый', 'ые', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж',
    'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
    'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь',
    'ы', 'ъ', 'э', 'ю', 'я', 'йо', 'ї', 'і', 'є', 'ґ'
]);

define('LATYN', [
    'a', 'iy', 'ie', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh',
    'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
    's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'shch', '',
    'y', '', 'e', 'yu', 'ya', 'yo', 'yi', 'i', 'ye', 'g'
]);

function convertUrl($url)
{
    $url = mb_strtolower(trim($url));
    $url = preg_replace('/[!@%&()=,`+?:\*\.\#\$\^\s]/', '-', $url);
    $url = preg_replace('/\-{2,}/', '-', $url);
    $url = str_replace(CYRILLIC, LATYN, $url);
    return $url;
}
