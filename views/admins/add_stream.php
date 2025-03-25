<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Add New Stream</h2>
                <a href="<?php echo URL_ROOT; ?>/admins/manageStreams" class="btn btn-light">Back to Manage Streams</a>
            </div>
            <div class="card-body">
                <p class="card-text">Create a new A/L stream for student applications</p>
                <form action="<?php echo URL_ROOT; ?>/admins/addStream" method="post">
                    <div class="form-group">
                        <label for="name" class="form-label">Stream Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="name" value="<?php echo $data['name']; ?>">
                        <div class="invalid-feedback"><?php echo $data['name_err']; ?></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" id="description" rows="3"><?php echo $data['description']; ?></textarea>
                        <div class="invalid-feedback"><?php echo $data['description_err']; ?></div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Add Stream</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

