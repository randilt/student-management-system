</div>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">For Administrators</h2>
                <p class="card-text">Manage student applications, approve or reject applications, and view student records.</p>
                <?php if(!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo URL_ROOT; ?>/users/login" class="btn btn-primary">Admin Login</a>
                <?php elseif($_SESSION['user_role'] == 'principal' || $_SESSION['user_role'] == 'stream_head') : ?>
                    <a href="<?php echo URL_ROOT; ?>/admins/dashboard" class="btn btn-primary">Go to Admin Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="<?php echo URL_ROOT; ?>/js/main.js"></script>
</body>
</html>

