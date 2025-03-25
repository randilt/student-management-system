<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Register user
    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Begin transaction
        $this->db->beginTransaction();

        try {
            // Insert user
            $this->db->query('INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)');
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':role', 'student'); // Default role for registration
            
            $this->db->execute();
            $userId = $this->db->lastInsertId();

            // Insert student profile
            $this->db->query('INSERT INTO students (user_id, first_name, last_name, date_of_birth, gender, address, contact_number, parent_name, parent_contact) 
                            VALUES (:user_id, :first_name, :last_name, :date_of_birth, :gender, :address, :contact_number, :parent_name, :parent_contact)');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':date_of_birth', $data['date_of_birth']);
            $this->db->bind(':gender', $data['gender']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':contact_number', $data['contact_number']);
            $this->db->bind(':parent_name', $data['parent_name']);
            $this->db->bind(':parent_contact', $data['parent_contact']);
            
            $this->db->execute();
            $studentId = $this->db->lastInsertId();

            // Insert O/L results
            foreach($data['ol_results'] as $subject => $grade) {
                $this->db->query('INSERT INTO ol_results (student_id, subject, grade) VALUES (:student_id, :subject, :grade)');
                $this->db->bind(':student_id', $studentId);
                $this->db->bind(':subject', $subject);
                $this->db->bind(':grade', $grade);
                $this->db->execute();
            }

            // Commit transaction
            $this->db->endTransaction();
            return $studentId;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->cancelTransaction();
            return false;
        }
    }

    // Login user
    public function login($username, $password) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Get student profile by user ID
    public function getStudentByUserId($userId) {
        $this->db->query('SELECT * FROM students WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);

        $row = $this->db->single();

        return $row;
    }

    // Get all stream heads
    public function getStreamHeads() {
        $this->db->query('SELECT users.*, streams.name as stream_name 
                        FROM users 
                        JOIN streams ON users.id = streams.head_user_id 
                        WHERE users.role = "stream_head"');
        
        $results = $this->db->resultSet();

        return $results;
    }
}

