<?php
class Pages {
    private $streamModel;
    
    public function __construct() {
        $this->streamModel = new Stream();
    }

    // Index page
    public function index() {
        // Get streams
        $streams = $this->streamModel->getStreams();

        $data = [
            'streams' => $streams
        ];

        require_once APP_ROOT . '/views/pages/index.php';
    }

    // About page
    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'Student Management System for A/L Admissions'
        ];

        require_once APP_ROOT . '/views/pages/about.php';
    }
}

