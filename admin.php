<?php

require_once 'User.php';

/**
 * Admin Class
 * Inherits all properties and methods from User.
 * Adds specific administrative privileges.
 */
class Admin extends User {
    
    // Fetch all users from the database
    public function getAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update any user's data
    public function updateUser($id, $name, $email)
    {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        try {
            return $stmt->execute([
                'name'  => $name,
                'email' => $email,
                'id'    => $id
            ]);
        } catch(PDOException $e){
            return false;
        }
    }

    // Delete a user by ID
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        try {
            $stmt->execute(['id' => $id]);
            return true;
        } catch(PDOException $e){
            return false;
        }
    }
}