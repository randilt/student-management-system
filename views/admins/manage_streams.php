<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">Manage Streams and Subjects</h2>
                <div>
                    <a href="<?php echo URL_ROOT; ?>/admins/addStream" class="btn btn-primary">Add New Stream</a>
                    <a href="<?php echo URL_ROOT; ?>/admins/addSubject" class="btn btn-primary">Add New Subject</a>
                    <a href="<?php echo URL_ROOT; ?>/admins/dashboard" class="btn btn-light">Back to Dashboard</a>
                </div>
            </div>
            <div class="card-body">
                <p>Manage A/L streams and their subjects for student applications.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php if(empty($data['streams'])) : ?>
        <div class="col-md-12">
            <div class="alert alert-warning">
                <p class="mb-0">No streams found. <a href="<?php echo URL_ROOT; ?>/admins/addStream">Add a new stream</a>.</p>
            </div>
        </div>
    <?php else : ?>
        <?php foreach($data['streams'] as $stream) : ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $stream->name; ?></h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Description:</strong> <?php echo $stream->description; ?></p>
                        
                        <?php if($stream->head) : ?>
                            <p><strong>Stream Head:</strong> <?php echo $stream->head->username; ?> (<?php echo $stream->head->email; ?>)</p>
                        <?php else : ?>
                            <p><strong>Stream Head:</strong> <span class="text-danger">Not assigned</span> - <a href="<?php echo URL_ROOT; ?>/admins/addStreamHead">Assign a head</a></p>
                        <?php endif; ?>
                        
                        <h4 class="mt-3">Subjects</h4>
                        <?php if(!empty($stream->subjects)) : ?>
                            <ul class="list-group">
                                <?php foreach($stream->subjects as $subject) : ?>
                                    <li class="list-group-item"><?php echo $subject->name; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <div class="alert alert-warning">
                                <p class="mb-0">No subjects found for this stream. <a href="<?php echo URL_ROOT; ?>/admins/addSubject">Add a subject</a>.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

