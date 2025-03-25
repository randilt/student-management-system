<?php
class Users {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }

    // Register new user
    public function register() {
        // Check for POST
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
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'gender' => trim($_POST['gender']),
                'address' => trim($_POST['address']),
                'contact_number' => trim($_POST['contact_number']),
                'parent_name' => trim($_POST['parent_name']),
                'parent_contact' => trim($_POST['parent_contact']),
                'ol_results' => $_POST['ol_results'],
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
                'ol_results_err' => ''
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

            // Validate O/L results
            if(empty($data['ol_results'])) {
                $data['ol_results_err'] = 'Please enter O/L results';
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['email_err']) && 
               empty($data['password_err']) && empty($data['confirm_password_err']) &&
               empty($data['first_name_err']) && empty($data['last_name_err']) &&
               empty($data['date_of_birth_err']) && empty($data['gender_err']) &&
               empty($data['address_err']) && empty($data['contact_number_err']) &&
               empty($data['parent_name_err']) && empty($data['parent_contact_err']) &&
               empty($data['ol_results_err'])) {
                
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
                'ol_results' => [],
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
                'ol_results_err' => ''
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
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',      
            ];

            // Validate Username
            // if(empty($data  => '',      
            // ];

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

                if($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
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

