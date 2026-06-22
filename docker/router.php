<?php

$root = dirname(__DIR__);
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = realpath($root . $requestPath);

function is_in_root($path, $root) {
    $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $path = rtrim($path, DIRECTORY_SEPARATOR) . (is_dir($path) ? DIRECTORY_SEPARATOR : '');
    return strpos($path, $root) === 0;
}

function run_php_script($script) {
    chdir(dirname($script));
    require $script;
    return true;
}

if ($path !== false && is_in_root($path, $root) && is_file($path)) {
    if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        return run_php_script($path);
    }

    return false;
}

if ($path !== false && is_in_root($path, $root) && is_dir($path)) {
    $index = $path . DIRECTORY_SEPARATOR . 'index.php';

    if (is_file($index)) {
        return run_php_script($index);
    }
}

return false;
