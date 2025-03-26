<?php
class Users {
    private $userModel;
    private $streamModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->streamModel = new Stream();
    }

    // Register new user
    public function register() {
        // Get streams for dropdown
        $streams = $this->streamModel->getStreams();
        
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'gender' => trim($_POST['gender']),
                'address' => trim($_POST['address']),
                'contact_number' => trim($_POST['contact_number']),
                'parent_name' => trim($_POST['parent_name']),
                'parent_contact' => trim($_POST['parent_contact']),
                'index_number' => trim($_POST['index_number']),
                'nic_number' => trim($_POST['nic_number']),
                'ol_exam_year' => trim($_POST['ol_exam_year']),
                'preferred_stream_id' => $_POST['preferred_stream_id'],
                'ol_results' => $_POST['ol_results'],
                'basket_subjects' => isset($_POST['basket_subjects']) ? $_POST['basket_subjects'] : [],
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'date_of_birth_err' => '',
                'gender_err' => '',
                'address_err' => '',
                'contact_number_err' => '',
                'parent_name_err' => '',
                'parent_contact_err' => '',
                'index_number_err' => '',
                'nic_number_err' => '',
                'ol_exam_year_err' => '',
                'preferred_stream_id_err' => '',
                'ol_results_err' => '',
                'basket_subjects_err' => [],
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

            // Validate first name
            if(empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter first name';
            }

            // Validate last name
            if(empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter last name';
            }

            // Validate date of birth
            if(empty($data['date_of_birth'])) {
                $data['date_of_birth_err'] = 'Please enter date of birth';
            }

            // Validate gender
            if(empty($data['gender'])) {
                $data['gender_err'] = 'Please select gender';
            }

            // Validate address
            if(empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }

            // Validate contact number
            if(empty($data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter contact number';
            }

            // Validate parent name
            if(empty($data['parent_name'])) {
                $data['parent_name_err'] = 'Please enter parent name';
            }

            // Validate parent contact
            if(empty($data['parent_contact'])) {
                $data['parent_contact_err'] = 'Please enter parent contact';
            }
            
            // Validate index number
            if(empty($data['index_number'])) {
                $data['index_number_err'] = 'Please enter index number';
            }
            
            // Validate NIC number
            if(empty($data['nic_number'])) {
                $data['nic_number_err'] = 'Please enter NIC number';
            }
            
            // Validate O/L exam year
            if(empty($data['ol_exam_year'])) {
                $data['ol_exam_year_err'] = 'Please enter O/L exam year';
            } elseif(!is_numeric($data['ol_exam_year']) || strlen($data['ol_exam_year']) != 4) {
                $data['ol_exam_year_err'] = 'Please enter a valid year';
            }
            
            // Validate preferred stream
            if(empty($data['preferred_stream_id'])) {
                $data['preferred_stream_id_err'] = 'Please select preferred A/L stream';
            }

            // Validate O/L results
            if(empty($data['ol_results'])) {
                $data['ol_results_err'] = 'Please enter O/L results';
            }
            
            // Validate basket subjects
            $basketSubjectsValid = true;
            $basketSubjectsCount = 0;
            
            foreach($data['basket_subjects'] as $key => $basketSubject) {
                if(!empty($basketSubject['name']) || !empty($basketSubject['grade'])) {
                    $basketSubjectsCount++;
                    
                    if(empty($basketSubject['name'])) {
                        $data['basket_subjects_err'][$key]['name'] = 'Please enter subject name';
                        $basketSubjectsValid = false;
                    }
                    
                    if(empty($basketSubject['grade'])) {
                        $data['basket_subjects_err'][$key]['grade'] = 'Please select a grade';
                        $basketSubjectsValid = false;
                    }
                }
            }
            
            if($basketSubjectsCount < 3) {
                $data['basket_subjects_err']['general'] = 'Please enter all 3 basket subjects';
                $basketSubjectsValid = false;
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['email_err']) && 
               empty($data['password_err']) && empty($data['confirm_password_err']) &&
               empty($data['first_name_err']) && empty($data['last_name_err']) &&
               empty($data['date_of_birth_err']) && empty($data['gender_err']) &&
               empty($data['address_err']) && empty($data['contact_number_err']) &&
               empty($data['parent_name_err']) && empty($data['parent_contact_err']) &&
               empty($data['index_number_err']) && empty($data['nic_number_err']) &&
               empty($data['ol_exam_year_err']) && empty($data['preferred_stream_id_err']) &&
               empty($data['ol_results_err']) && $basketSubjectsValid) {
                
                // Register User
                $studentId = $this->userModel->register($data);

                if($studentId) {
                    // Set flash message
                    $_SESSION['success_message'] = 'You are registered and can now log in';
                    // Redirect to login
                    header('location: ' . URL_ROOT . '/users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/users/register.php';
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name' => '',
                'last_name' => '',
                'date_of_birth' => '',
                'gender' => '',
                'address' => '',
                'contact_number' => '',
                'parent_name' => '',
                'parent_contact' => '',
                'index_number' => '',
                'nic_number' => '',
                'ol_exam_year' => '',
                'preferred_stream_id' => '',
                'ol_results' => [],
                'basket_subjects' => [],
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'date_of_birth_err' => '',
                'gender_err' => '',
                'address_err' => '',
                'contact_number_err' => '',
                'parent_name_err' => '',
                'parent_contact_err' => '',
                'index_number_err' => '',
                'nic_number_err' => '',
                'ol_exam_year_err' => '',
                'preferred_stream_id_err' => '',
                'ol_results_err' => '',
                'basket_subjects_err' => [],
                'streams' => $streams
            ];

            // Load view
            require_once APP_ROOT . '/views/users/register.php';
        }
    }

    // Login user
    public function login() {
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',
                'account_err' => '' 
            ];

            // Validate Username
            if(empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByUsername($data['username'])) {
                // User found
            } else {
                // User not found
                $data['username_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if($loggedInUser === 'deactivated') {
                    $data['account_err'] = 'Your account has been deactivated. Please contact the administrator.';
                    // Load view with errors
                    require_once APP_ROOT . '/views/users/login.php';
                } elseif($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    // Load view with errors
                    require_once APP_ROOT . '/views/users/login.php';
                }
            } else {
                // Load view with errors
                require_once APP_ROOT . '/views/users/login.php';
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => '',
                'account_err' => ''        
            ];

            // Load view
            require_once APP_ROOT . '/views/users/login.php';
        }
    }

    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        
        // Redirect based on role
        if($user->role == 'student') {
            header('location: ' . URL_ROOT . '/students/dashboard');
        } elseif($user->role == 'principal') {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        } elseif($user->role == 'stream_head') {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        }elseif($user->role == 'administrator') {
            header('location: ' . URL_ROOT . '/admins/dashboard');
        } else {
            die('Something went wrong');
        }
    }

    // Logout user
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('location: ' . URL_ROOT . '/users/login');
    }

    // Check if user is logged in
    public function isLoggedIn() {
        if(isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}

