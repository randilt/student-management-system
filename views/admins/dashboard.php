<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row mb-4">
  <div class="col-md-12">
      <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h2 class="card-title">Admin Dashboard</h2>
              <?php if($_SESSION['user_role'] == 'administrator') : ?>
                  <span class="badge badge-light text-primary">Administrator</span>
              <?php elseif($_SESSION['user_role'] == 'principal') : ?>
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
                          <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'principal') : ?>
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

<?php if(($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'principal') && isset($data['streamStats'])) : ?>
<div class="row mb-4">
  <div class="col-md-6">
      <div class="card">
          <div class="card-header">
              <h3 class="card-title">Application Statistics by Stream</h3>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>Stream</th>
                              <th>Total</th>
                              <th>Pending</th>
                              <th>Approved</th>
                              <th>Rejected</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach($data['streamStats'] as $stat) : ?>
                              <tr>
                                  <td><?php echo $stat->name; ?></td>
                                  <td><?php echo $stat->total_applications; ?></td>
                                  <td><?php echo $stat->pending_count; ?></td>
                                  <td><?php echo $stat->approved_count; ?></td>
                                  <td><?php echo $stat->rejected_count; ?></td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-6">
      <div class="card">
          <div class="card-header">
              <h3 class="card-title">Most Selected Subjects</h3>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>Subject</th>
                              <th>Stream</th>
                              <th>Selections</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach($data['subjectStats'] as $stat) : ?>
                              <tr>
                                  <td><?php echo $stat->name; ?></td>
                                  <td><?php echo $stat->stream_name; ?></td>
                                  <td><?php echo $stat->selection_count; ?></td>
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

<div class="row">
  <div class="col-md-12">
      <div class="card">
          <div class="card-header">
              <h3 class="card-title">
                  <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'principal') : ?>
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
                                  <th>Index Number</th>
                                  <th>NIC Number</th>
                                  <th>O/L Year</th>
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
                                      <td><?php echo $application->index_number; ?></td>
                                      <td><?php echo $application->nic_number; ?></td>
                                      <td><?php echo $application->ol_exam_year; ?></td>
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

<?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'principal') : ?>
<div class="row mt-4">
  <div class="col-md-12">
      <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Stream Heads</h3>
              <div>
                  <a href="<?php echo URL_ROOT; ?>/admins/addStreamHead" class="btn btn-primary">Add Stream Head</a>
                  <a href="<?php echo URL_ROOT; ?>/admins/manageStreams" class="btn btn-secondary">Manage Streams & Subjects</a>
              </div>
          </div>
          <div class="card-body">
              <?php if(isset($data['streamHeads']) && !empty($data['streamHeads'])) : ?>
                  <div class="table-responsive">
                      <table class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Username</th>
                                  <th>Email</th>
                                  <th>Stream</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach($data['streamHeads'] as $streamHead) : ?>
                                  <tr>
                                      <td><?php echo $streamHead->username; ?></td>
                                      <td><?php echo $streamHead->email; ?></td>
                                      <td><?php echo $streamHead->stream_name; ?></td>
                                      <td>
                                          <form action="<?php echo URL_ROOT; ?>/admins/removeStreamHead" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this stream head?');">
                                              <input type="hidden" name="stream_head_id" value="<?php echo $streamHead->id; ?>">
                                              <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                          </form>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              <?php else : ?>
                  <div class="alert alert-warning">
                      <p class="mb-0">No stream heads found. <a href="<?php echo URL_ROOT; ?>/admins/addStreamHead">Add a stream head</a>.</p>
                  </div>
              <?php endif; ?>
          </div>
      </div>
  </div>
</div>
<?php endif; ?>

<?php if($_SESSION['user_role'] == 'administrator' or $_SESSION['user_role'] == 'principal') : ?>
<div class="row mt-4">
  <div class="col-md-12">
      <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Administrators</h3>
              <div>
                  <a href="<?php echo URL_ROOT; ?>/admins/addAdministrator" class="btn btn-primary">Add Administrator</a>
              </div>
          </div>
          <div class="card-body">
              <?php if(isset($data['administrators']) && !empty($data['administrators'])) : ?>
                  <div class="table-responsive">
                      <table class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Username</th>
                                  <th>Email</th>
                                  <th>Status</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach($data['administrators'] as $admin) : ?>
                                  <tr <?php echo ($admin->account_status == 'deactivated') ? 'class="table-secondary"' : ''; ?>>
                                      <td><?php echo $admin->username; ?></td>
                                      <td><?php echo $admin->email; ?></td>
                                      <td>
                                          <?php if($admin->account_status == 'active') : ?>
                                              <span class="badge badge-success">Active</span>
                                          <?php else : ?>
                                              <span class="badge badge-secondary">Deactivated</span>
                                          <?php endif; ?>
                                      </td>
                                      <td>
                                          <?php if($admin->id != $_SESSION['user_id']) : ?>
                                              <?php if($admin->account_status == 'active') : ?>
                                                  <form action="<?php echo URL_ROOT; ?>/admins/removeAdministrator" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to deactivate this administrator?');">
                                                      <input type="hidden" name="admin_id" value="<?php echo $admin->id; ?>">
                                                      <button type="submit" class="btn btn-sm btn-warning">Deactivate</button>
                                                  </form>
                                              <?php else : ?>
                                                  <form action="<?php echo URL_ROOT; ?>/admins/activateAdministrator" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to activate this administrator?');">
                                                      <input type="hidden" name="admin_id" value="<?php echo $admin->id; ?>">
                                                      <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                                  </form>
                                              <?php endif; ?>
                                          <?php else : ?>
                                              <span class="badge badge-secondary">Current User</span>
                                          <?php endif; ?>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              <?php else : ?>
                  <div class="alert alert-warning">
                      <p class="mb-0">No other administrators found. <a href="<?php echo URL_ROOT; ?>/admins/addAdministrator">Add an administrator</a>.</p>
                  </div>
              <?php endif; ?>
          </div>
      </div>
  </div>
</div>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

