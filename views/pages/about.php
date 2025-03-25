<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h1 class="display-4"><?php echo $data['title']; ?></h1>
        <p class="lead"><?php echo $data['description']; ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Our Mission</h2>
                <p class="card-text">
                    Our mission is to streamline the A/L admission process for students in Sri Lanka. 
                    We aim to eliminate inefficiencies, delays, and errors in the traditional paper-based system 
                    by providing a secure and user-friendly online platform.
                </p>
                
                <h2 class="card-title mt-4">Key Features</h2>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <i class="fas fa-user-plus mr-2 text-primary"></i> 
                        Online student registration
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-file-alt mr-2 text-primary"></i> 
                        Digital submission of O/L results
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-stream mr-2 text-primary"></i> 
                        Stream and subject selection
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-tasks mr-2 text-primary"></i> 
                        Application status tracking
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-user-shield mr-2 text-primary"></i> 
                        Secure admin panel for application management
                    </li>
                </ul>
                
                <h2 class="card-title">Contact Us</h2>
                <p class="card-text">
                    If you have any questions or need assistance, please contact us at:
                </p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope mr-2 text-primary"></i> Email: admin@school.edu</li>
                    <li><i class="fas fa-phone mr-2 text-primary"></i> Phone: +94 11 123 4567</li>
                    <li><i class="fas fa-map-marker-alt mr-2 text-primary"></i> Address: 123 School Road, Colombo, Sri Lanka</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

