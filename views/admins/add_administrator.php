<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-8 mx-auto">
      <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h2 class="card-title">Add Administrator</h2>
              <a href="<?php echo URL_ROOT; ?>/admins/dashboard" class="btn btn-light">Back to Dashboard</a>
          </div>
          <div class="card-body">
              <p class="card-text">Create a new administrator account with full system access</p>
              <form action="<?php echo URL_ROOT; ?>/admins/addAdministrator" method="post">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="username" class="form-label">Username</label>
                              <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" id="username" value="<?php echo $data['username']; ?>">
                              <div class="invalid-feedback"><?php echo $data['username_err']; ?></div>
                          </div>
                          <div class="form-group">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" value="<?php echo $data['email']; ?>">
                              <div class="invalid-feedback"><?php echo $data['email_err']; ?></div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="password" class="form-label">Password</label>
                              <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password">
                              <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                          </div>
                          <div class="form-group">
                              <label for="confirm_password" class="form-label">Confirm Password</label>
                              <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirm_password">
                              <div class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="alert alert-info mt-3">
                      <p class="mb-0"><i class="fas fa-info-circle mr-2"></i> Administrators have full access to the system, including managing streams, subjects, stream heads, and other administrators.</p>
                  </div>
                  
                  <div class="form-group mt-4">
                      <button type="submit" class="btn btn-primary btn-block">Add Administrator</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

