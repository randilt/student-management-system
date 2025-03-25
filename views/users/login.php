<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Login</h2>
            </div>
            <div class="card-body">
                <p class="card-text">Please fill in your credentials to log in</p>
                <form action="<?php echo URL_ROOT; ?>/users/login" method="post">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" id="username" value="<?php echo $data['username']; ?>">
                        <div class="invalid-feedback"><?php echo $data['username_err']; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password">
                        <div class="invalid-feedback"><?php echo $data['password_err']; ?></div>
                    </div>
                    <?php if(!empty($data['account_err'])) : ?>
                        <div class="alert alert-danger">
                            <?php echo $data['account_err']; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <p>No account? <a href="<?php echo URL_ROOT; ?>/users/register">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

