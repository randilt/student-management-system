<?php
class Students {
    private $userModel;
    private $streamModel;
    private $applicationModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->streamModel = new Stream();
        $this->applicationModel = new Application();
    }

    // Student dashboard
    public function dashboard() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is student
        if($_SESSION['user_role'] != 'student') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get student profile
        $student = $this->userModel->getStudentByUserId($_SESSION['user_id']);
        
        // Get applications
        $applications = $this->applicationModel->getApplicationsByStudentId($student->id);

        $data = [
            'student' => $student,
            'applications' => $applications
        ];

        require_once APP_ROOT . '/views/students/dashboard.php';
    }

    // Apply for A/L stream
    public function apply() {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is student
        if($_SESSION['user_role'] != 'student') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get student profile
        $student = $this->userModel->getStudentByUserId($_SESSION['user_id']);
        
        // Get O/L results
        $olResults = $this->applicationModel->getOLResults($student->id);

        // Get streams
        $streams = $this->streamModel->getStreams();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'student_id' => $student->id,
                'stream_id' => $_POST['stream_id'],
                'subjects' => isset($_POST['subjects']) ? $_POST['subjects'] : [],
                'stream_id_err' => '',
                'subjects_err' => ''
            ];

            // Validate Stream
            if(empty($data['stream_id'])) {
                $data['stream_id_err'] = 'Please select a stream';
            } else {
                // Check if already applied to this stream
                if($this->applicationModel->hasAppliedToStream($student->id, $data['stream_id'])) {
                    $data['stream_id_err'] = 'You have already applied to this stream';
                }
            }

            // Validate Subjects
            if(empty($data['subjects'])) {
                $data['subjects_err'] = 'Please select at least one subject';
            }

            // Make sure errors are empty
            if(empty($data['stream_id_err']) && empty($data['subjects_err'])) {
                // Submit application
                $applicationId = $this->applicationModel->submit($data);

                if($applicationId) {
                    // Set flash message
                    $_SESSION['success_message'] = 'Your application has been submitted successfully';
                    // Redirect to dashboard
                    header('location: ' . URL_ROOT . '/students/dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['student'] = $student;
                $data['olResults'] = $olResults;
                $data['streams'] = $streams;
                
                // Get subjects for selected stream
                if(!empty($data['stream_id'])) {
                    $data['subjects_list'] = $this->streamModel->getSubjectsByStreamId($data['stream_id']);
                } else {
                    $data['subjects_list'] = [];
                }
                
                require_once APP_ROOT . '/views/students/apply.php';
            }
        } else {
            $data = [
                'student' => $student,
                'olResults' => $olResults,
                'streams' => $streams,
                'subjects_list' => [],
                'stream_id_err' => '',
                'subjects_err' => ''
            ];

            // Load view
            require_once APP_ROOT . '/views/students/apply.php';
        }
    }

    // Get subjects by stream
    public function getSubjects() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            $streamId = $_GET['stream_id'];
            
            $subjects = $this->streamModel->getSubjectsByStreamId($streamId);
            
            header('Content-Type: application/json');
            echo json_encode($subjects);
        } else {
            header('location: ' . URL_ROOT . '/students/apply');
        }
    }

    // View application details
    public function viewApplication($id) {
        // Check if logged in
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Check if role is student
        if($_SESSION['user_role'] != 'student') {
            header('location: ' . URL_ROOT . '/users/login');
        }

        // Get student profile
        $student = $this->userModel->getStudentByUserId($_SESSION['user_id']);
        
        // Get application
        $application = $this->applicationModel->getApplicationById($id);
        
        // Check if application belongs to student
        if($application->student_id != $student->id) {
            header('location: ' . URL_ROOT . '/students/dashboard');
        }
        
        // Get selected subjects
        $selectedSubjects = $this->applicationModel->getSelectedSubjects($id);
        
        // Get O/L results
        $olResults = $this->applicationModel->getOLResults($student->id);
        
        // Get basket subjects
        $basketSubjects = $this->applicationModel->getBasketSubjects($student->id);

        $data = [
            'student' => $student,
            'application' => $application,
            'selectedSubjects' => $selectedSubjects,
            'olResults' => $olResults,
            'basketSubjects' => $basketSubjects,
            'applicationModel' => $this->applicationModel
        ];

        require_once APP_ROOT . '/views/students/view_application.php';
    }
}

