<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Student Dashboard</h2>
                <a href="<?php echo URL_ROOT; ?>/students/apply" class="btn btn-light">Apply for A/L Stream</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Welcome, <?php echo $_SESSION['user_username']; ?></h4>
                        <p>Manage your A/L stream applications and track their status.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle mr-2"></i> Quick Info</h5>
                            <p class="mb-0">You can apply for multiple streams, but you can only be accepted to one.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Applications</h3>
            </div>
            <div class="card-body">
                <?php if(empty($data['applications'])) : ?>
                    <div class="alert alert-warning">
                        <p class="mb-0">You haven't applied to any A/L streams yet. <a href="<?php echo URL_ROOT; ?>/students/apply">Apply now</a>.</p>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Stream</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['applications'] as $application) : ?>
                                    <tr>
                                        <td><?php echo $application->stream_name; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($application->applied_at)); ?></td>
                                        <td>
                                            <?php if($application->status == 'pending') : ?>
                                                <span class="badge badge-warning"><?php echo $application->status_text; ?></span>
                                            <?php elseif($application->status == 'approved') : ?>
                                                <span class="badge badge-success"><?php echo $application->status_text; ?></span>
                                            <?php elseif($application->status == 'rejected') : ?>
                                                <span class="badge badge-danger"><?php echo $application->status_text; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo URL_ROOT; ?>/students/viewApplication/<?php echo $application->id; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

