<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Add Stream Head</h2>
                <a href="<?php echo URL_ROOT; ?>/admins/dashboard" class="btn btn-light">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <p class="card-text">Create a new stream head account and assign to a stream</p>
                <form action="<?php echo URL_ROOT; ?>/admins/addStreamHead" method="post">
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
                    
                    <div class="form-group">
                        <label for="stream_id" class="form-label">Assign to Stream</label>
                        <select name="stream_id" id="stream_id" class="form-select <?php echo (!empty($data['stream_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="" selected disabled>Select Stream</option>
                            <?php foreach($data['streams'] as $stream) : ?>
                                <option value="<?php echo $stream->id; ?>" <?php echo (isset($data['stream_id']) && $data['stream_id'] == $stream->id) ? 'selected' : ''; ?>>
                                    <?php echo $stream->name; ?>
                                    <?php if($stream->head_user_id) : ?>
                                        (Already has a head)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?php echo $data['stream_id_err']; ?></div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Add Stream Head</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

