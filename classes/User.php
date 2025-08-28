<?php
require_once '../config/database.php';

/**
 * User CRUD Operations Class
 * Handles Create, Read, Update, Delete operations for users
 */
class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Create/Add a new user
     * @param array $data User data
     * @return bool|int Returns user ID on success, false on failure
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (name, email, phone, address) VALUES (:name, :email, :phone, :address)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Create user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Read/Get user by ID
     * @param int $id User ID
     * @return array|false User data or false if not found
     */
    public function read($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Read user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Read/Get all users
     * @return array|false Array of users or false on error
     */
    public function readAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Read all users error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update user data
     * @param int $id User ID
     * @param array $data Updated user data
     * @return bool True on success, false on failure
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete user
     * @param int $id User ID
     * @return bool True on success, false on failure
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email already exists (for validation)
     * @param string $email Email to check
     * @param int $excludeId ID to exclude from check (for updates)
     * @return bool True if exists, false if not
     */
    public function emailExists($email, $excludeId = null) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE email = :email";
            if ($excludeId) {
                $sql .= " AND id != :excludeId";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            if ($excludeId) {
                $stmt->bindParam(':excludeId', $excludeId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Email exists check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Search users by name or email
     * @param string $searchTerm Search term
     * @return array|false Array of matching users or false on error
     */
    public function search($searchTerm) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE name LIKE :search OR email LIKE :search ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $searchTerm = '%' . $searchTerm . '%';
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Search users error: " . $e->getMessage());
            return false;
        }
    }
}
?>