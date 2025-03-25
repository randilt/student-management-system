<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Add New Subject</h2>
                <a href="<?php echo URL_ROOT; ?>/admins/manageStreams" class="btn btn-light">Back to Manage Streams</a>
            </div>
            <div class="card-body">
                <p class="card-text">Add a new subject to an existing stream</p>
                <form action="<?php echo URL_ROOT; ?>/admins/addSubject" method="post">
                    <div class="form-group">
                        <label for="name" class="form-label">Subject Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="name" value="<?php echo $data['name']; ?>">
                        <div class="invalid-feedback"><?php echo $data['name_err']; ?></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="stream_id" class="form-label">Stream</label>
                        <select name="stream_id" id="stream_id" class="form-select <?php echo (!empty($data['stream_id_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="" selected disabled>Select Stream</option>
                            <?php foreach($data['streams'] as $stream) : ?>
                                <option value="<?php echo $stream->id; ?>" <?php echo (isset($data['stream_id']) && $data['stream_id'] == $stream->id) ? 'selected' : ''; ?>>
                                    <?php echo $stream->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?php echo $data['stream_id_err']; ?></div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

