<?php

declare(strict_types=1);
$rootPath = dirname(__DIR__); 
require_once $rootPath . '/config/database.php';
require_once $rootPath . '/src/Database.php';
require_once $rootPath . '/src/User.php';

try {
    $config = include $rootPath . '/config/database.php';

    $db = new Database($config);
    $userManager = new User($db);

    while (true) {
        echo "Меню управления пользователями";
        echo "1. Показать всех пользователей";
        echo "2. Добавить нового пользователя";
        echo "3. Выход";
        echo "Выберите действие (1-3): ";

        $choice = trim(fgets(STDIN));

        switch ($choice) {
            case '1':
                $userManager->listAll();
                break;
            case '2':
                $userManager->addFromConsole();
                $userManager->listAll();
                break;
            case '3':
                echo "До свидания! ";
                exit(0);
            default:
                echo "Неверный выбор. Попробуйте ещё раз.  ";
        }
    }
} catch (Exception $e) {
    echo "Критическая ошибка: " . $e->getMessage() . PHP_EOL;
}