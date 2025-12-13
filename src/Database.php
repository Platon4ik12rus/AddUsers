<?php

declare(strict_types=1);

class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, email FROM users ORDER BY id");
        return $stmt->fetchAll();
    }

    public function addUser(string $name, string $email): bool
    {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':name'  => $name,
            ':email' => $email
        ]);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->rowCount() > 0;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}