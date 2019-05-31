<?php
/*屏蔽警告错误信息输出,调试可注释掉*/
//error_reporting(E_ALL^E_NOTICE^E_WARNING);
ini_set("session.cookie_httponly", 1);

use Zend\Mvc\Application;

header("Content-Type: text/html; charset=UTF-8");
//header('X-Frame-Options: deny');
header("X-Frame-Options:SAMEORIGIN");

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

chdir(dirname(__DIR__));

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

if (true) {//启用我们自定义的命令空间
    Zend\Loader\AutoloaderFactory::factory(array(
        'Zend\Loader\StandardAutoloader' => array(
            'namespaces' => array(
                'Model' => 'model',
            )
        )));
}

/*PHP 配置*/
$config['phpSettings'] = [
    'display_startup_errors' => true,
    'display_errors' => true,
    'date.timezone' => 'Asia/Shanghai'
];

foreach ($config['phpSettings'] as $key => $value) {
    ini_set($key, $value);
}

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';

// Run the application!
Application::init($appConfig)->run();
