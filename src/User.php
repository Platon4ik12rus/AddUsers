<?php

declare(strict_types=1);

class User
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

        private function validate(string $name, string $email): array
{
    $errors = [];

    $name = trim($name);
    if ($name === '') {
        $errors[] = 'Имя не может быть пустым.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Имя не может быть длиннее 100 символов.';
    }

    $email = trim($email);
    if ($email === '') {
        $errors[] = 'Email не может быть пустым.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email.';
    } elseif ($this->db->emailExists($email)) {
        $errors[] = 'Пользователь с таким email уже существует.';
    }

    return $errors;
}


    public function addFromConsole(): void
    {
        echo "Добавление нового пользователя ";
        echo str_repeat("=", 40) . " ";

        $name  = $this->readLine("Введите имя: ");
        $email = $this->readLine("Введите email: ");

        $errors = $this->validate($name, $email);

        if (!empty($errors)) {
            echo "Ошибки валидации: ";
            foreach ($errors as $error) {
                echo " $error ";
            }
            echo " ";
            return;
        }

        try {
            $success = $this->db->addUser($name, $email);
            if ($success) {
                echo " ✓ Пользователь успешно добавлен!  ";
            } else {
                echo " ✗ Не удалось добавить пользователя.  ";
            }
        } catch (Exception $e) {
            echo " ✗ Ошибка базы данных: " . $e->getMessage() . "  ";
        }
    }

    public function listAll(): void
    {
        $users = $this->db->getAllUsers();

        echo "Список пользователей ";
        echo str_repeat("=", 60) . " ";

        if (empty($users)) {
            echo "Таблица пользователей пуста.  ";
            return;
        }

        printf("%-5s %-30s %-30s ", "ID", "Имя", "Email");
        echo str_repeat("-", 60) . " ";

        foreach ($users as $user) {
            printf("%-5d %-30s %-30s ", $user['id'], $user['name'], $user['email']);
        }
        echo " ";
    }


private function readLine(string $prompt): string
{
    echo $prompt;

    $input = readline();

    if ($input === false) {
        return '';
    }

    return trim($input);
}
}