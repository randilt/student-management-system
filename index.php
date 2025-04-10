<?php
// Load Config
require_once 'config/config.php';

// Load Database
require_once 'config/database.php';

// Load Models
require_once 'models/User.php';
require_once 'models/Stream.php';
require_once 'models/Application.php';

// Load Controllers
require_once 'controllers/Users.php';
require_once 'controllers/Students.php';
require_once 'controllers/Admins.php';
require_once 'controllers/Pages.php';

// Initialize Controllers
$usersController = new Users();
$studentsController = new Students();
$adminsController = new Admins();
$pagesController = new Pages();

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Remove empty values
$uri = array_filter($uri);

// Reindex array
$uri = array_values($uri);

// Check if we're in a subfolder
$subfolder = trim(URL_SUBFOLDER, '/');
if(!empty($subfolder)) {
  // Remove subfolder from URI
  if(isset($uri[0]) && $uri[0] == $subfolder) {
      array_shift($uri);
  }
}

// Default controller and method
$controller = 'pages';
$method = 'index';
$params = [];

// Get controller
if(isset($uri[0])) {
  $controller = $uri[0];
  unset($uri[0]);
}

// Get method
if(isset($uri[1])) {
  $method = $uri[1];
  unset($uri[1]);
}

// Get params
if(!empty($uri)) {
  $params = array_values($uri);
}

// Route the request
switch($controller) {
  case 'users':
      switch($method) {
          case 'register':
              $usersController->register();
              break;
          case 'login':
              $usersController->login();
              break;
          case 'logout':
              $usersController->logout();
              break;
          default:
              $pagesController->index();
              break;
      }
      break;
  case 'students':
      switch($method) {
          case 'dashboard':
              $studentsController->dashboard();
              break;
          case 'apply':
              $studentsController->apply();
              break;
          case 'getSubjects':
              $studentsController->getSubjects();
              break;
          case 'viewApplication':
              if(isset($params[0])) {
                  $studentsController->viewApplication($params[0]);
              } else {
                  $pagesController->index();
              }
              break;
          default:
              $pagesController->index();
              break;
      }
      break;
  case 'admins':
      switch($method) {
          case 'dashboard':
              $adminsController->dashboard();
              break;
          case 'viewApplication':
              if(isset($params[0])) {
                  $adminsController->viewApplication($params[0]);
              } else {
                  $pagesController->index();
              }
              break;
          case 'updateStatus':
              $adminsController->updateStatus();
              break;
          case 'addStreamHead':
              $adminsController->addStreamHead();
              break;
          case 'removeStreamHead':
              if(isset($params[0])) {
                  $adminsController->removeStreamHead($params[0]);
              } else {
                  $adminsController->removeStreamHead();
              }
              break;
          case 'addAdministrator':
              $adminsController->addAdministrator();
              break;
          case 'removeAdministrator':
              if(isset($params[0])) {
                  $adminsController->removeAdministrator($params[0]);
              } else {
                  $adminsController->removeAdministrator();
              }
              break;
          case 'activateAdministrator':
              if(isset($params[0])) {
                  $adminsController->activateAdministrator($params[0]);
              } else {
                  $adminsController->activateAdministrator();
              }
              break;
          case 'addStream':
              $adminsController->addStream();
              break;
          case 'editStream':
              if(isset($params[0])) {
                  $adminsController->editStream($params[0]);
              } else {
                  $pagesController->index();
              }
              break;
          case 'deleteStream':
              if(isset($params[0])) {
                  $adminsController->deleteStream($params[0]);
              } else {
                  $adminsController->deleteStream();
              }
              break;
          case 'addSubject':
              $adminsController->addSubject();
              break;
          case 'editSubject':
              if(isset($params[0])) {
                  $adminsController->editSubject($params[0]);
              } else {
                  $pagesController->index();
              }
              break;
          case 'deleteSubject':
              if(isset($params[0])) {
                  $adminsController->deleteSubject($params[0]);
              } else {
                  $adminsController->deleteSubject();
              }
              break;
          case 'manageStreams':
              $adminsController->manageStreams();
              break;
          default:
              $pagesController->index();
              break;
      }
      break;
  case 'pages':
      switch($method) {
          case 'about':
              $pagesController->about();
              break;
          default:
              $pagesController->index();
              break;
      }
      break;
  default:
      $pagesController->index();
      break;
}

