<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h1 class="display-4">Student Management System</h1>
        <p class="lead">A/L Stream Admission Portal</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">For Students</h2>
                <p class="card-text">Register and apply for A/L streams online. Track your application status and get notified when your application is approved.</p>
                <?php if(!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-primary">Register Now</a>
                    <a href="<?php echo URL_ROOT; ?>/users/login" class="btn btn-outline-primary">Login</a>
                <?php elseif($_SESSION['user_role'] == 'student') : ?>
                    <a href="<?php echo URL_ROOT; ?>/students/dashboard" class="btn btn-primary">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Available A/L Streams</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach($data['streams'] as $stream) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $stream->name; ?></h4>
                                    <p class="card-text"><?php echo $stream->description; ?></p>
                                </div>
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'student') : ?>
                                    <div class="card-footer">
                                        <a href="<?php echo URL_ROOT; ?>/students/apply" class="btn btn-sm btn-outline-primary">Apply Now</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

