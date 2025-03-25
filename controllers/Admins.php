<?php
class Admins {
    private $userModel;
    private $streamModel;
    private $applicationModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->streamModel = new Stream();
        $this->applicationModel = new Application();
    }

    // Admin dashboard
    public function dashboard() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is admin (principal or stream_head)
        if($_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get user
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        // Get applications based on role
        if($_SESSION['user_role'] == 'principal') {
            // Principal sees all applications
            $applications = $this->applicationModel->getAllApplications();
            $streamHeads = $this->userModel->getStreamHeads();
            
            $data = [
                'user' => $user,
                'applications' => $applications,
                'streamHeads' => $streamHeads
            ];
        } else {
            // Stream head sees only applications for their stream
            // Get stream ID for this stream head
            $this->db = new Database;
            $this->db->query('SELECT id FROM streams WHERE head_user_id = :head_user_id');
            $this->db->bind(':head_user_id', $_SESSION['user_id']);
            $stream = $this->db->single();
            
            if($stream) {
                $applications = $this->applicationModel->getApplicationsByStreamId($stream->id);
                
                $data = [
                    'user' => $user,
                    'applications' => $applications,
                    'stream' => $stream
                ];
            } else {
                // Stream head not assigned to any stream
                $applications = [];
                
                $data = [
                    'user' => $user,
                    'applications' => $applications,
                    'stream' => null
                ];
            }
        }

        require_once APP_ROOT . '/views/admins/dashboard.php';
    }

    // View application details
    public function viewApplication($id) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is admin (principal or stream_head)
        if($_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get application
        $application = $this->applicationModel->getApplicationById($id);
        
        // If stream head, check if application is for their stream
        if($_SESSION['user_role'] == 'stream_head') {
            // Get stream ID for this stream head
            $this->db = new Database;
            $this->db->query('SELECT id FROM streams WHERE head_user_id = :head_user_id');
            $this->db->bind(':head_user_id', $_SESSION['user_id']);
            $stream = $this->db->single();
            
            if(!$stream || $application->stream_id != $stream->id) {
                header('location: ' . URL_ROOT . '/admins/dashboard');
            }
        }
        
        // Get selected subjects
        $selectedSubjects = $this->applicationModel->getSelectedSubjects($id);
        
        // Get O/L results
        $olResults = $this->applicationModel->getOLResults($application->student_id);

        $data = [
            'application' => $application,
            'selectedSubjects' => $selectedSubjects,
            'olResults' => $olResults
        ];

        require_once APP_ROOT . '/views/admins/view_application.php';
    }

    // Update application status
    public function updateStatus() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is admin (principal or stream_head)
        if($_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'application_id' => $_POST['application_id'],
                'status' => $_POST['status'],
                'comments' => trim($_POST['comments'])
            ];

            // If stream head, check if application is for their stream
            if($_SESSION['user_role'] == 'stream_head') {
                // Get application
                $application = $this->applicationModel->getApplicationById($data['application_id']);
                
                // Get stream ID for this stream head
                $this->db = new Database;
                $this->db->query('SELECT id FROM streams WHERE head_user_id = :head_user_id');
                $this->db->bind(':head_user_id', $_SESSION['user_id']);
                $stream = $this->db->single();
                
                if(!$stream || $application->stream_id != $stream->id) {
                    header('location: ' . URL_ROOT . '/admins/dashboard');
                }
            }

            // Update status
            if($this->applicationModel->updateStatus($data['application_id'], $data['status'], $_SESSION['user_id'], $data['comments'])) {
                // Set flash message
                $_SESSION['success_message'] = 'Application status updated successfully';
                // Redirect to dashboard
                header('location: ' . URL_ROOT . '/admins/dashboard');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        }
    }
}

