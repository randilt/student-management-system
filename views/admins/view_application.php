<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Application Details</h2>
                <a href="<?php echo URL_ROOT; ?>/admins/dashboard" class="btn btn-light">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Student Information</h4>
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td><?php echo $data['application']->first_name . ' ' . $data['application']->last_name; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $data['application']->email; ?></td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td><?php echo $data['application']->username; ?></td>
                            </tr>
                            <tr>
                                <th>Index Number:</th>
                                <td><?php echo $data['application']->index_number; ?></td>
                            </tr>
                            <tr>
                                <th>NIC Number:</th>
                                <td><?php echo $data['application']->nic_number; ?></td>
                            </tr>
                            <tr>
                                <th>O/L Exam Year:</th>
                                <td><?php echo $data['application']->ol_exam_year; ?></td>
                            </tr>
                            <tr>
                                <th>Preferred Stream:</th>
                                <td><?php echo $data['application']->preferred_stream_name; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Application Information</h4>
                        <table class="table">
                            <tr>
                                <th>Stream:</th>
                                <td><?php echo $data['application']->stream_name; ?></td>
                            </tr>
                            <tr>
                                <th>Applied Date:</th>
                                <td><?php echo date('M d, Y', strtotime($data['application']->applied_at)); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <?php if($data['application']->status == 'pending') : ?>
                                        <span class="badge badge-warning">Pending Review</span>
                                    <?php elseif($data['application']->status == 'approved') : ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php elseif($data['application']->status == 'rejected') : ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if($data['application']->status != 'pending') : ?>
                                <tr>
                                    <th>Reviewed Date:</th>
                                    <td><?php echo date('M d, Y', strtotime($data['application']->reviewed_at)); ?></td>
                                </tr>
                                <?php if(!empty($data['application']->comments)) : ?>
                                    <tr>
                                        <th>Comments:</th>
                                        <td><?php echo $data['application']->comments; ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Selected Subjects</h4>
                        <ul class="list-group">
                            <?php foreach($data['selectedSubjects'] as $subject) : ?>
                                <li class="list-group-item"><?php echo $subject->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>O/L Results</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['olResults'] as $result) : ?>
                                        <tr>
                                            <td><?php echo $result->subject; ?></td>
                                            <td>
                                                <span class="badge <?php echo ($result->grade == 'A' || $result->grade == 'B') ? 'badge-success' : (($result->grade == 'C' || $result->grade == 'S') ? 'badge-warning' : 'badge-danger'); ?>">
                                                    <?php echo $result->grade; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <?php if($data['application']->status == 'pending') : ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Update Application Status</h4>
                        <form action="<?php echo URL_ROOT; ?>/admins/updateStatus" method="post">
                            <input type="hidden" name="application_id" value="<?php echo $data['application']->id; ?>">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Optional comments for the student"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

