<?php

/*
  User Class
 Represents a user entity with properties and behaviors.
 */

class User
{
    // 1. Properties (Data)
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $role;
    
    // Database connection dependency
    protected $pdo;

    // Constructor receives the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --- Getters & Setters ---
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; }
    
    // Hash the password before storing it
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // --- Behavior (Methods) ---

    // Register a new user in the database
    public function register()
    {
        $sql = "INSERT INTO users(name, email, password, role) 
                VALUES(:name, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);

        try {
            return $stmt->execute([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password,
                'role'     => $this->role ?? 'user' // Default to 'user' if role is null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Check if an email already exists (Used for validation)
    public function isEmailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->rowCount() > 0;
    }

    // Fetch user data by email (Used for Login)
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch user data by ID (Used for Dashboard)
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}