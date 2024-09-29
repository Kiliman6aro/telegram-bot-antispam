<?php

use Dotenv\Dotenv;
use HopHey\TelegramBot\TelegramBot;

require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['TOKEN', 'GROUP_ID'])->notEmpty();

$config = require_once 'config/app.php';

$token = $config['token'];

$bot = new TelegramBot($token);
$bot->run();




//Нужны люди c опытом на oнлaйн/оффлайн ворк!
//Вoзрaст 17+
//Новая тема по крипте
//Подробности @memphisUS