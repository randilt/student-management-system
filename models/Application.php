<?php
class Application {
  private $db;

  public function __construct() {
      $this->db = new Database;
  }

  // Submit application
  public function submit($data) {
      // Begin transaction
      $this->db->beginTransaction();

      try {
          // Insert application
          $this->db->query('INSERT INTO applications (student_id, stream_id) VALUES (:student_id, :stream_id)');
          $this->db->bind(':student_id', $data['student_id']);
          $this->db->bind(':stream_id', $data['stream_id']);
          
          $this->db->execute();
          $applicationId = $this->db->lastInsertId();

          // Insert selected subjects
          foreach($data['subjects'] as $subjectId) {
              $this->db->query('INSERT INTO application_subjects (application_id, subject_id) VALUES (:application_id, :subject_id)');
              $this->db->bind(':application_id', $applicationId);
              $this->db->bind(':subject_id', $subjectId);
              $this->db->execute();
          }

          // Commit transaction
          $this->db->endTransaction();
          return $applicationId;
      } catch (Exception $e) {
          // Rollback transaction on error
          $this->db->cancelTransaction();
          return false;
      }
  }

  // Get application by ID
  public function getApplicationById($id) {
      $this->db->query('SELECT a.*, s.name as stream_name, 
                      st.first_name, st.last_name, st.index_number, st.nic_number, st.ol_exam_year,
                      st.preferred_stream_id, ps.name as preferred_stream_name,
                      u.email, u.username
                      FROM applications a
                      JOIN streams s ON a.stream_id = s.id
                      JOIN students st ON a.student_id = st.id
                      JOIN users u ON st.user_id = u.id
                      LEFT JOIN streams ps ON st.preferred_stream_id = ps.id
                      WHERE a.id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
  }

  // Get applications by student ID
  public function getApplicationsByStudentId($studentId) {
      $this->db->query('SELECT a.*, s.name as stream_name, 
                      CASE 
                          WHEN a.status = "pending" THEN "Pending Review"
                          WHEN a.status = "approved" THEN "Approved"
                          WHEN a.status = "rejected" THEN "Rejected"
                      END as status_text
                      FROM applications a
                      JOIN streams s ON a.stream_id = s.id
                      WHERE a.student_id = :student_id
                      ORDER BY a.applied_at DESC');
      $this->db->bind(':student_id', $studentId);

      $results = $this->db->resultSet();

      return $results;
  }

  // Get applications by stream ID
  public function getApplicationsByStreamId($streamId) {
      $this->db->query('SELECT a.*, s.name as stream_name, 
                      st.first_name, st.last_name, st.index_number, st.nic_number, st.ol_exam_year,
                      u.email, u.username,
                      CASE 
                          WHEN a.status = "pending" THEN "Pending Review"
                          WHEN a.status = "approved" THEN "Approved"
                          WHEN a.status = "rejected" THEN "Rejected"
                      END as status_text
                      FROM applications a
                      JOIN streams s ON a.stream_id = s.id
                      JOIN students st ON a.student_id = st.id
                      JOIN users u ON st.user_id = u.id
                      WHERE a.stream_id = :stream_id
                      ORDER BY a.applied_at DESC');
      $this->db->bind(':stream_id', $streamId);

      $results = $this->db->resultSet();

      return $results;
  }

  // Get all applications (for principal)
  public function getAllApplications() {
      $this->db->query('SELECT a.*, s.name as stream_name, 
                      st.first_name, st.last_name, st.index_number, st.nic_number, st.ol_exam_year,
                      u.email, u.username,
                      CASE 
                          WHEN a.status = "pending" THEN "Pending Review"
                          WHEN a.status = "approved" THEN "Approved"
                          WHEN a.status = "rejected" THEN "Rejected"
                      END as status_text
                      FROM applications a
                      JOIN streams s ON a.stream_id = s.id
                      JOIN students st ON a.student_id = st.id
                      JOIN users u ON st.user_id = u.id
                      ORDER BY a.applied_at DESC');

      $results = $this->db->resultSet();

      return $results;
  }

  // Get selected subjects for an application
  public function getSelectedSubjects($applicationId) {
      $this->db->query('SELECT s.* FROM subjects s
                      JOIN application_subjects a_s ON s.id = a_s.subject_id
                      WHERE a_s.application_id = :application_id');
      $this->db->bind(':application_id', $applicationId);

      $results = $this->db->resultSet();

      return $results;
  }

  // Update application status
  public function updateStatus($applicationId, $status, $reviewerId, $comments) {
      $this->db->query('UPDATE applications 
                      SET status = :status, 
                          reviewed_by = :reviewer_id, 
                          reviewed_at = NOW(), 
                          comments = :comments 
                      WHERE id = :id');
      
      $this->db->bind(':status', $status);
      $this->db->bind(':reviewer_id', $reviewerId);
      $this->db->bind(':comments', $comments);
      $this->db->bind(':id', $applicationId);

      // Execute
      if($this->db->execute()) {
          return true;
      } else {
          return false;
      }
  }

  // Get O/L results for a student
  public function getOLResults($studentId) {
      $this->db->query('SELECT * FROM ol_results WHERE student_id = :student_id');
      $this->db->bind(':student_id', $studentId);

      $results = $this->db->resultSet();

      return $results;
  }

  // Check if student has already applied to a stream
  public function hasAppliedToStream($studentId, $streamId) {
      $this->db->query('SELECT * FROM applications 
                      WHERE student_id = :student_id 
                      AND stream_id = :stream_id
                      AND status != "rejected"');
      
      $this->db->bind(':student_id', $studentId);
      $this->db->bind(':stream_id', $streamId);

      $this->db->execute();

      return $this->db->rowCount() > 0;
  }

  // Get application count by stream
  public function getApplicationCountByStream() {
      $this->db->query('SELECT s.id, s.name, 
                      COUNT(a.id) as total_applications,
                      SUM(CASE WHEN a.status = "pending" THEN 1 ELSE 0 END) as pending_count,
                      SUM(CASE WHEN a.status = "approved" THEN 1 ELSE 0 END) as approved_count,
                      SUM(CASE WHEN a.status = "rejected" THEN 1 ELSE 0 END) as rejected_count
                      FROM streams s
                      LEFT JOIN applications a ON s.id = a.stream_id
                      GROUP BY s.id, s.name
                      ORDER BY total_applications DESC');

      $results = $this->db->resultSet();

      return $results;
  }

  // Get most selected subjects
  public function getMostSelectedSubjects() {
      $this->db->query('SELECT s.id, s.name, s.stream_id, st.name as stream_name, COUNT(a_s.subject_id) as selection_count
                      FROM subjects s
                      JOIN streams st ON s.stream_id = st.id
                      LEFT JOIN application_subjects a_s ON s.id = a_s.subject_id
                      GROUP BY s.id, s.name, s.stream_id, st.name
                      ORDER BY selection_count DESC
                      LIMIT 10');

      $results = $this->db->resultSet();

      return $results;
  }

  // Check if stream has applications
  public function streamHasApplications($streamId) {
      $this->db->query('SELECT COUNT(*) as count FROM applications WHERE stream_id = :stream_id');
      $this->db->bind(':stream_id', $streamId);
      
      $row = $this->db->single();
      
      return $row->count > 0;
  }

  // Check if subject has applications
  public function subjectHasApplications($subjectId) {
      $this->db->query('SELECT COUNT(*) as count FROM application_subjects WHERE subject_id = :subject_id');
      $this->db->bind(':subject_id', $subjectId);
      
      $row = $this->db->single();
      
      return $row->count > 0;
  }
}

