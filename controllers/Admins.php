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

        // Check if role is admin (administrator, principal or stream_head)
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get user
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        // Get applications based on role
        if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'principal') {
            // Administrator and Principal see all applications
            $applications = $this->applicationModel->getAllApplications();
            $streamHeads = $this->userModel->getStreamHeads();
            $administrators = $this->userModel->getAdministrators();
            
            // Get analytics data
            $streamStats = $this->applicationModel->getApplicationCountByStream();
            $subjectStats = $this->applicationModel->getMostSelectedSubjects();
            
            $data = [
                'user' => $user,
                'applications' => $applications,
                'streamHeads' => $streamHeads,
                'administrators' => $administrators,
                'streamStats' => $streamStats,
                'subjectStats' => $subjectStats
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

        // Check if role is admin (administrator, principal or stream_head)
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
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

        // Check if role is admin (administrator, principal or stream_head)
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal' && $_SESSION['user_role'] != 'stream_head') {
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

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
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

    // Add administrator form
    public function addAdministrator() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator
        if($_SESSION['user_role'] == 'administrator' or $_SESSION['user_role'] == 'principal') {
            
            
        }else{
            header('location: ' . URL_ROOT . '/users/login');
        }

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
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
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

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['email_err']) && 
               empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Register Administrator
                if($this->userModel->registerAdministrator($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Administrator added successfully';
                    // Redirect to dashboard
                    header('location: ' . URL_ROOT . '/admins/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/add_administrator.php';
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/add_administrator.php';
        }
    }

    // Remove stream head
    public function removeStreamHead($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get stream head ID
            $streamHeadId = isset($_POST['stream_head_id']) ? $_POST['stream_head_id'] : $id;

            if(empty($streamHeadId)) {
                $_SESSION['error_message'] = 'Invalid stream head ID';
                header('location: ' . URL_ROOT . '/admins/dashboard');
                return;
            }

            // Remove stream head
            if($this->userModel->removeStreamHead($streamHeadId)) {
                // Set flash message
                $_SESSION['success_message'] = 'Stream head removed successfully';
            } else {
                $_SESSION['error_message'] = 'Failed to remove stream head';
            }

            // Redirect to dashboard
            header('location: ' . URL_ROOT . '/admins/dashboard');
        } else {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        }
    }

    // Remove administrator
    public function removeAdministrator($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator
        if($_SESSION['user_role'] != 'administrator') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get administrator ID
            $adminId = isset($_POST['admin_id']) ? $_POST['admin_id'] : $id;

            if(empty($adminId)) {
                $_SESSION['error_message'] = 'Invalid administrator ID';
                header('location: ' . URL_ROOT . '/admins/dashboard');
                return;
            }

            // Prevent removing yourself
            if($adminId == $_SESSION['user_id']) {
                $_SESSION['error_message'] = 'You cannot remove yourself';
                header('location: ' . URL_ROOT . '/admins/dashboard');
                return;
            }

            // Remove administrator
            if($this->userModel->removeAdministrator($adminId)) {
                // Set flash message
                $_SESSION['success_message'] = 'Administrator removed successfully';
            } else {
                $_SESSION['error_message'] = 'Failed to remove administrator';
            }

            // Redirect to dashboard
            header('location: ' . URL_ROOT . '/admins/dashboard');
        } else {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        }
    }

    // Add new stream form
    public function addStream() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter stream name';
            } else {
                // Check if stream name exists
                if($this->streamModel->findStreamByName($data['name'])) {
                    $data['name_err'] = 'Stream name already exists';
                }
            }

            // Validate Description
            if(empty($data['description'])) {
                $data['description_err'] = 'Please enter stream description';
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['description_err'])) {
                
                // Add Stream
                if($this->streamModel->addStream($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Stream added successfully';
                    // Redirect to dashboard
                    header('location: ' . URL_ROOT . '/admins/manageStreams');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/add_stream.php';
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => '',
                'description_err' => ''
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/add_stream.php';
        }
    }

    // Edit stream form
    public function editStream($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if(empty($id)) {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
            return;
        }

        // Get stream
        $stream = $this->streamModel->getStreamById($id);
        if(!$stream) {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter stream name';
            } else {
                // Check if stream name exists (excluding this stream)
                if($this->streamModel->findStreamByNameExcept($data['name'], $id)) {
                    $data['name_err'] = 'Stream name already exists';
                }
            }

            // Validate Description
            if(empty($data['description'])) {
                $data['description_err'] = 'Please enter stream description';
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['description_err'])) {
                
                // Update Stream
                if($this->streamModel->updateStream($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Stream updated successfully';
                    // Redirect to manage streams
                    header('location: ' . URL_ROOT . '/admins/manageStreams');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/edit_stream.php';
            }
        } else {
            // Init data
            $data = [
                'id' => $id,
                'name' => $stream->name,
                'description' => $stream->description,
                'name_err' => '',
                'description_err' => ''
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/edit_stream.php';
        }
    }

    // Delete stream
    public function deleteStream($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get stream ID
            $streamId = isset($_POST['stream_id']) ? $_POST['stream_id'] : $id;

            if(empty($streamId)) {
                $_SESSION['error_message'] = 'Invalid stream ID';
                header('location: ' . URL_ROOT . '/admins/manageStreams');
                return;
            }

            // Check if stream has applications
            if($this->applicationModel->streamHasApplications($streamId)) {
                $_SESSION['error_message'] = 'Cannot delete stream with existing applications';
                header('location: ' . URL_ROOT . '/admins/manageStreams');
                return;
            }

            // Delete stream
            if($this->streamModel->deleteStream($streamId)) {
                // Set flash message
                $_SESSION['success_message'] = 'Stream deleted successfully';
            } else {
                $_SESSION['error_message'] = 'Failed to delete stream';
            }

            // Redirect to manage streams
            header('location: ' . URL_ROOT . '/admins/manageStreams');
        } else {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
        }
    }

    // Add new subject form
    public function addSubject() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
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
                'name' => trim($_POST['name']),
                'stream_id' => $_POST['stream_id'],
                'name_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter subject name';
            } else {
                // Check if subject name exists in this stream
                if($this->streamModel->findSubjectByNameAndStream($data['name'], $data['stream_id'])) {
                    $data['name_err'] = 'Subject already exists in this stream';
                }
            }

            // Validate Stream
            if(empty($data['stream_id'])) {
                $data['stream_id_err'] = 'Please select a stream';
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['stream_id_err'])) {
                
                // Add Subject
                if($this->streamModel->addSubject($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Subject added successfully';
                    // Redirect to manage streams
                    header('location: ' . URL_ROOT . '/admins/manageStreams');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/add_subject.php';
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'stream_id' => '',
                'name_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/add_subject.php';
        }
    }

    // Edit subject form
    public function editSubject($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if(empty($id)) {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
            return;
        }

        // Get subject
        $subject = $this->streamModel->getSubjectById($id);
        if(!$subject) {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
            return;
        }

        // Get all streams
        $streams = $this->streamModel->getStreams();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'stream_id' => $_POST['stream_id'],
                'name_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter subject name';
            } else {
                // Check if subject name exists in this stream (excluding this subject)
                if($this->streamModel->findSubjectByNameAndStreamExcept($data['name'], $data['stream_id'], $id)) {
                    $data['name_err'] = 'Subject already exists in this stream';
                }
            }

            // Validate Stream
            if(empty($data['stream_id'])) {
                $data['stream_id_err'] = 'Please select a stream';
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['stream_id_err'])) {
                
                // Update Subject
                if($this->streamModel->updateSubject($data)) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Subject updated successfully';
                    // Redirect to manage streams
                    header('location: ' . URL_ROOT . '/admins/manageStreams');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/admins/edit_subject.php';
            }
        } else {
            // Init data
            $data = [
                'id' => $id,
                'name' => $subject->name,
                'stream_id' => $subject->stream_id,
                'name_err' => '',
                'stream_id_err' => '',
                'streams' => $streams
            ];

            // Load view
            require_once APP_ROOT . '/views/admins/edit_subject.php';
        }
    }

    // Delete subject
    public function deleteSubject($id = null) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get subject ID
            $subjectId = isset($_POST['subject_id']) ? $_POST['subject_id'] : $id;

            if(empty($subjectId)) {
                $_SESSION['error_message'] = 'Invalid subject ID';
                header('location: ' . URL_ROOT . '/admins/manageStreams');
                return;
            }

            // Check if subject is used in applications
            if($this->applicationModel->subjectHasApplications($subjectId)) {
                $_SESSION['error_message'] = 'Cannot delete subject that is used in applications';
                header('location: ' . URL_ROOT . '/admins/manageStreams');
                return;
            }

            // Delete subject
            if($this->streamModel->deleteSubject($subjectId)) {
                // Set flash message
                $_SESSION['success_message'] = 'Subject deleted successfully';
            } else {
                $_SESSION['error_message'] = 'Failed to delete subject';
            }

            // Redirect to manage streams
            header('location: ' . URL_ROOT . '/admins/manageStreams');
        } else {
            header('location: ' . URL_ROOT . '/admins/manageStreams');
        }
    }

    // Manage streams and subjects
    public function manageStreams() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is administrator or principal
        if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'principal') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get all streams with subjects
        $streams = $this->streamModel->getStreamsWithSubjects();

        $data = [
            'streams' => $streams
        ];

        require_once APP_ROOT . '/views/admins/manage_streams.php';
    }
}

