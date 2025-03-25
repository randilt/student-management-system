<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Admin Dashboard</h2>
                <?php if($_SESSION['user_role'] == 'principal') : ?>
                    <span class="badge badge-light text-primary">Principal</span>
                <?php else : ?>
                    <span class="badge badge-light text-primary">Stream Head</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Welcome, <?php echo $_SESSION['user_username']; ?></h4>
                        <p>Manage student applications for A/L streams.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle mr-2"></i> Quick Info</h5>
                            <?php if($_SESSION['user_role'] == 'principal') : ?>
                                <p class="mb-0">You can view and manage all applications across all streams.</p>
                            <?php else : ?>
                                <p class="mb-0">You can view and manage applications for your assigned stream.</p>
                            <?php endif; ?>
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
                <h3 class="card-title">
                    <?php if($_SESSION['user_role'] == 'principal') : ?>
                        All Applications
                    <?php else : ?>
                        Applications for <?php echo isset($data['stream']) ? $data['stream']->name : 'Your Stream'; ?>
                    <?php endif; ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if(empty($data['applications'])) : ?>
                    <div class="alert alert-warning">
                        <p class="mb-0">No applications found.</p>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Stream</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['applications'] as $application) : ?>
                                    <tr>
                                        <td><?php echo $application->first_name . ' ' . $application->last_name; ?></td>
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
                                            <a href="<?php echo URL_ROOT; ?>/admins/viewApplication/<?php echo $application->id; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
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

<?php if($_SESSION['user_role'] == 'principal' && isset($data['streamHeads']) && !empty($data['streamHeads'])) : ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stream Heads</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Stream</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['streamHeads'] as $streamHead) : ?>
                                <tr>
                                    <td><?php echo $streamHead->username; ?></td>
                                    <td><?php echo $streamHead->email; ?></td>
                                    <td><?php echo $streamHead->stream_name; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

