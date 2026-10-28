<?php

/*
  Database Class
  Responsible only for establishing the connection.
 */

class Database
{
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    private $dbname     = "mydb";
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            // Set error mode to exceptions for better error handling
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Returns the PDO connection object to be used by other classes
    public function getConnection()
    {
        return $this->pdo;
    }
}