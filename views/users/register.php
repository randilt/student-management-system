<?php require_once APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Student Registration</h2>
            </div>
            <div class="card-body">
                <p class="card-text">Please fill out this form to register</p>
                <form action="<?php echo URL_ROOT; ?>/users/register" method="post" id="registrationForm">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Account Information</h4>
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
                        <div class="col-md-6">
                            <h4>Personal Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>" id="first_name" value="<?php echo $data['first_name']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['first_name_err']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>" id="last_name" value="<?php echo $data['last_name']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['last_name_err']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control <?php echo (!empty($data['date_of_birth_err'])) ? 'is-invalid' : ''; ?>" id="date_of_birth" value="<?php echo $data['date_of_birth']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['date_of_birth_err']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select name="gender" class="form-select <?php echo (!empty($data['gender_err'])) ? 'is-invalid' : ''; ?>" id="gender">
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="male" <?php echo ($data['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo ($data['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="other" <?php echo ($data['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                        <div class="invalid-feedback"><?php echo $data['gender_err']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" class="form-control <?php echo (!empty($data['address_err'])) ? 'is-invalid' : ''; ?>" id="address" rows="2"><?php echo $data['address']; ?></textarea>
                                <div class="invalid-feedback"><?php echo $data['address_err']; ?></div>
                            </div>
                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control <?php echo (!empty($data['contact_number_err'])) ? 'is-invalid' : ''; ?>" id="contact_number" value="<?php echo $data['contact_number']; ?>">
                                <div class="invalid-feedback"><?php echo $data['contact_number_err']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                                        <input type="text" name="parent_name" class="form-control <?php echo (!empty($data['parent_name_err'])) ? 'is-invalid' : ''; ?>" id="parent_name" value="<?php echo $data['parent_name']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['parent_name_err']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parent_contact" class="form-label">Parent/Guardian Contact</label>
                                        <input type="text" name="parent_contact" class="form-control <?php echo (!empty($data['parent_contact_err'])) ? 'is-invalid' : ''; ?>" id="parent_contact" value="<?php echo $data['parent_contact']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['parent_contact_err']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <!-- New fields for additional information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="index_number" class="form-label">Index Number</label>
                                        <input type="text" name="index_number" class="form-control <?php echo (!empty($data['index_number_err'])) ? 'is-invalid' : ''; ?>" id="index_number" value="<?php echo $data['index_number']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['index_number_err']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nic_number" class="form-label">NIC Number</label>
                                        <input type="text" name="nic_number" class="form-control <?php echo (!empty($data['nic_number_err'])) ? 'is-invalid' : ''; ?>" id="nic_number" value="<?php echo $data['nic_number']; ?>">
                                        <div class="invalid-feedback"><?php echo $data['nic_number_err']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ol_exam_year" class="form-label">O/L Exam Year</label>
                                        <input type="number" name="ol_exam_year" class="form-control <?php echo (!empty($data['ol_exam_year_err'])) ? 'is-invalid' : ''; ?>" id="ol_exam_year" value="<?php echo $data['ol_exam_year']; ?>" min="2000" max="<?php echo date('Y'); ?>">
                                        <div class="invalid-feedback"><?php echo $data['ol_exam_year_err']; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_stream_id" class="form-label">Preferred A/L Stream</label>
                                        <select name="preferred_stream_id" class="form-select <?php echo (!empty($data['preferred_stream_id_err'])) ? 'is-invalid' : ''; ?>" id="preferred_stream_id">
                                            <option value="" selected disabled>Select Preferred Stream</option>
                                            <?php foreach($data['streams'] as $stream) : ?>
                                                <option value="<?php echo $stream->id; ?>" <?php echo ($data['preferred_stream_id'] == $stream->id) ? 'selected' : ''; ?>>
                                                    <?php echo $stream->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"><?php echo $data['preferred_stream_id_err']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
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
                                        <!-- Main Subjects -->
                                        <tr>
                                            <td>Sinhala</td>
                                            <td>
                                                <select name="ol_results[Sinhala]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mathematics</td>
                                            <td>
                                                <select name="ol_results[Mathematics]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Science</td>
                                            <td>
                                                <select name="ol_results[Science]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>English</td>
                                            <td>
                                                <select name="ol_results[English]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>History</td>
                                            <td>
                                                <select name="ol_results[History]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Religion</td>
                                            <td>
                                                <select name="ol_results[Religion]" class="form-select">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="S">S</option>
                                                    <option value="W">W</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="ol-results-error" class="d-none text-danger">Please select at least one subject grade</div>
                        </div>
                    </div>
                    
                    <!-- Basket Subjects Section -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Basket Subjects</h4>
                            <p class="text-muted">Please enter 3 basket subjects and grades</p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i = 1; $i <= 3; $i++) : ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="basket_subjects[<?php echo $i; ?>][name]" class="form-control <?php echo (!empty($data['basket_subjects_err'][$i]['name'])) ? 'is-invalid' : ''; ?>" placeholder="Basket Subject <?php echo $i; ?>" value="<?php echo isset($data['basket_subjects'][$i]['name']) ? $data['basket_subjects'][$i]['name'] : ''; ?>">
                                                <?php if(!empty($data['basket_subjects_err'][$i]['name'])) : ?>
                                                    <div class="invalid-feedback"><?php echo $data['basket_subjects_err'][$i]['name']; ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <select name="basket_subjects[<?php echo $i; ?>][grade]" class="form-select <?php echo (!empty($data['basket_subjects_err'][$i]['grade'])) ? 'is-invalid' : ''; ?>">
                                                    <option value="" selected disabled>Select Grade</option>
                                                    <option value="A" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'A') ? 'selected' : ''; ?>>A</option>
                                                    <option value="B" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'B') ? 'selected' : ''; ?>>B</option>
                                                    <option value="C" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'C') ? 'selected' : ''; ?>>C</option>
                                                    <option value="S" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'S') ? 'selected' : ''; ?>>S</option>
                                                    <option value="W" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'W') ? 'selected' : ''; ?>>W</option>
                                                    <option value="F" <?php echo (isset($data['basket_subjects'][$i]['grade']) && $data['basket_subjects'][$i]['grade'] == 'F') ? 'selected' : ''; ?>>F</option>
                                                </select>
                                                <?php if(!empty($data['basket_subjects_err'][$i]['grade'])) : ?>
                                                    <div class="invalid-feedback"><?php echo $data['basket_subjects_err'][$i]['grade']; ?></div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if(!empty($data['basket_subjects_err']['general'])) : ?>
                                <div class="text-danger"><?php echo $data['basket_subjects_err']['general']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="<?php echo URL_ROOT; ?>/users/login">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/inc/footer.php'; ?>

