<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Apply for A/L Stream</h2>
            </div>
            <div class="card-body">
                <form action="<?php echo URL_ROOT; ?>/students/apply" method="post" id="applicationForm">
                    <div class="row">
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
                        <div class="col-md-6">
                            <h4>Select A/L Stream</h4>
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
                                <div class="invalid-feedback"><?php echo $data['stream_id_err'] ?? ''; ?></div>
                            </div>
                            
                            <div id="subjectsContainer" class="mt-4 <?php echo (empty($data['subjects_list'])) ? 'd-none' : ''; ?>">
                                <h4>Select Subjects</h4>
                                <div class="form-group">
                                    <div class="list-group">
                                        <?php if(!empty($data['subjects_list'])) : ?>
                                            <?php foreach($data['subjects_list'] as $subject) : ?>
                                                <label class="list-group-item">
                                                    <input class="form-check-input mr-1" type="checkbox" name="subjects[]" value="<?php echo $subject->id; ?>" <?php echo (isset($data['subjects']) && in_array($subject->id, $data['subjects'])) ? 'checked' : ''; ?>>
                                                    <?php echo $subject->name; ?>
                                                </label>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div id="subjects-error" class="<?php echo (!empty($data['subjects_err'])) ? '' : 'd-none'; ?> text-danger mt-2"><?php echo $data['subjects_err'] ?? ''; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
                        <a href="<?php echo URL_ROOT; ?>/students/dashboard" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const streamSelect = document.getElementById('stream_id');
        const subjectsContainer = document.getElementById('subjectsContainer');
        
        streamSelect.addEventListener('change', function() {
            const streamId = this.value;
            
            if(streamId) {
                // Fetch subjects for selected stream
                fetch('<?php echo URL_ROOT; ?>/students/getSubjects?stream_id=' + streamId, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Clear previous subjects
                        const listGroup = subjectsContainer.querySelector('.list-group');
                        listGroup.innerHTML = '';
                        
                        // Add new subjects
                        data.forEach(subject => {
                            const label = document.createElement('label');
                            label.className = 'list-group-item';
                            
                            const input = document.createElement('input');
                            input.className = 'form-check-input mr-1';
                            input.type = 'checkbox';
                            input.name = 'subjects[]';
                            input.value = subject.id;
                            
                            label.appendChild(input);
                            label.appendChild(document.createTextNode(' ' + subject.name));
                            
                            listGroup.appendChild(label);
                        });
                        
                        // Show subjects container
                        subjectsContainer.classList.remove('d-none');
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            } else {
                // Hide subjects container
                subjectsContainer.classList.add('d-none');
            }
        });
    });
</script>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

