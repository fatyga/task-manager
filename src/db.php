<?php

class Database
{
    private string $dsn;
    private string $username;
    private string $password;


    private PDO $conn;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        $this->conn = new PDO($dsn, $username, $password, $options);
    }
}