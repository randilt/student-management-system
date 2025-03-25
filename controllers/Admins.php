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

    // Add stream head form
    public function addStreamHead() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is principal
        if($_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get all streams
        $streams = $this->streamModel->getStreams();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'stream_id' => $_POST['stream_id'],
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Validate Username
            if(empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                // Check if username exists
                if($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username is already taken';
                }
            }

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check if email exists
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already registered';
                }
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Validate Stream
            if(empty($data['stream_id'])) {
                $data['stream_id_err'] = 'Please select a stream';
            } else {
                // Check if stream already has a head
                $stream = $this->streamModel->getStreamById($data['stream_id']);
                if($stream && $stream->head_user_id) {
                    // Get current stream head name
                    $currentHead = $this->userModel->getUserById($stream->head_user_id);
                    if($currentHead) {
                        $data['stream_id_err'] = 'This stream already has a head: ' . $currentHead->username;
                    } else {
                        $data['stream_id_err'] = 'This stream already has a head assigned';
                    }
                }
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['email_err']) && 
               empty($data['password_err']) && empty($data['confirm_password_err']) &&
               empty($data['stream_id_err'])) {
                
                // Register Stream Head
                if($this->userModel->registerStreamHead($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Stream head added successfully';
                    // Redirect to dashboard
                    header('location: ' . URL_ROOT . '/admins/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/add_stream_head.php';
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'stream_id' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/add_stream_head.php';
        }
    }
}

