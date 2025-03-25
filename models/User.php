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
          $this->db->query('INSERT INTO students (user_id, first_name, last_name, date_of_birth, gender, address, contact_number, parent_name, parent_contact, index_number, nic_number, ol_exam_year, preferred_stream_id) 
                          VALUES (:user_id, :first_name, :last_name, :date_of_birth, :gender, :address, :contact_number, :parent_name, :parent_contact, :index_number, :nic_number, :ol_exam_year, :preferred_stream_id)');
          
          $this->db->bind(':user_id', $userId);
          $this->db->bind(':first_name', $data['first_name']);
          $this->db->bind(':last_name', $data['last_name']);
          $this->db->bind(':date_of_birth', $data['date_of_birth']);
          $this->db->bind(':gender', $data['gender']);
          $this->db->bind(':address', $data['address']);
          $this->db->bind(':contact_number', $data['contact_number']);
          $this->db->bind(':parent_name', $data['parent_name']);
          $this->db->bind(':parent_contact', $data['parent_contact']);
          $this->db->bind(':index_number', $data['index_number']);
          $this->db->bind(':nic_number', $data['nic_number']);
          $this->db->bind(':ol_exam_year', $data['ol_exam_year']);
          $this->db->bind(':preferred_stream_id', $data['preferred_stream_id']);
          
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

  // Register stream head
  public function registerStreamHead($data) {
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
          $this->db->bind(':role', 'stream_head');
          
          $this->db->execute();
          $userId = $this->db->lastInsertId();

          // Update stream with new head
          $this->db->query('UPDATE streams SET head_user_id = :head_user_id WHERE id = :stream_id');
          $this->db->bind(':head_user_id', $userId);
          $this->db->bind(':stream_id', $data['stream_id']);
          
          $this->db->execute();

          // Commit transaction
          $this->db->endTransaction();
          return true;
      } catch (Exception $e) {
          // Rollback transaction on error
          $this->db->cancelTransaction();
          return false;
      }
  }

  // Register administrator
  public function registerAdministrator($data) {
      // Hash password
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

      try {
          // Insert user
          $this->db->query('INSERT INTO users (username, password, email, role, account_status) VALUES (:username, :password, :email, :role, :account_status)');
          $this->db->bind(':username', $data['username']);
          $this->db->bind(':password', $data['password']);
          $this->db->bind(':email', $data['email']);
          $this->db->bind(':role', 'administrator');
          $this->db->bind(':account_status', 'active');
        
          return $this->db->execute();
      } catch (Exception $e) {
          return false;
      }
  }

  // Remove stream head
  public function removeStreamHead($userId) {
      // Begin transaction
      $this->db->beginTransaction();

      try {
          // Get streams associated with this user
          $this->db->query('SELECT id FROM streams WHERE head_user_id = :head_user_id');
          $this->db->bind(':head_user_id', $userId);
          $streams = $this->db->resultSet();

          // Remove head_user_id from streams
          foreach($streams as $stream) {
              $this->db->query('UPDATE streams SET head_user_id = NULL WHERE id = :id');
              $this->db->bind(':id', $stream->id);
              $this->db->execute();
          }

          // Delete user
          $this->db->query('DELETE FROM users WHERE id = :id AND role = :role');
          $this->db->bind(':id', $userId);
          $this->db->bind(':role', 'stream_head');
          $this->db->execute();

          // Commit transaction
          $this->db->endTransaction();
          return true;
      } catch (Exception $e) {
          // Rollback transaction on error
          $this->db->cancelTransaction();
          return false;
      }
  }

  // Remove administrator
  public function removeAdministrator($userId) {
    try {
        // Update user status to deactivated
        $this->db->query('UPDATE users SET account_status = :status WHERE id = :id AND role = :role');
        $this->db->bind(':status', 'deactivated');
        $this->db->bind(':id', $userId);
        $this->db->bind(':role', 'administrator');
        
        return $this->db->execute();
    } catch (Exception $e) {
        return false;
    }
}

// Add a new method to activate an administrator
public function activateAdministrator($userId) {
    try {
        // Update user status to active
        $this->db->query('UPDATE users SET account_status = :status WHERE id = :id AND role = :role');
        $this->db->bind(':status', 'active');
        $this->db->bind(':id', $userId);
        $this->db->bind(':role', 'administrator');
        
        return $this->db->execute();
    } catch (Exception $e) {
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
              // Check if account is active
              if($row->account_status == 'deactivated') {
                  return 'deactivated';
              }
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
      $this->db->query('SELECT s.*, st.name as preferred_stream_name 
                      FROM students s
                      LEFT JOIN streams st ON s.preferred_stream_id = st.id
                      WHERE s.user_id = :user_id');
      $this->db->bind(':user_id', $userId);

      $row = $this->db->single();

      return $row;
  }

  // Get all stream heads
  public function getStreamHeads() {
      $this->db->query('SELECT users.*, streams.name as stream_name, streams.id as stream_id 
                      FROM users 
                      JOIN streams ON users.id = streams.head_user_id 
                      WHERE users.role = "stream_head"');
      
      $results = $this->db->resultSet();

      return $results;
  }

  // Get all administrators
  public function getAdministrators() {
      $this->db->query('SELECT * FROM users WHERE role = "administrator" ORDER BY account_status ASC');
    
      $results = $this->db->resultSet();

      return $results;
  }
}

