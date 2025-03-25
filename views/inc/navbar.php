<nav class="navbar navbar-expand-md">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URL_ROOT; ?>"><?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" id="navbarToggler">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navbar-nav-left">
                <li>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>">Home</a>
                </li>
                <li>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/pages/about">About</a>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <?php if($_SESSION['user_role'] == 'student') : ?>
                        <li>
                            <a class="nav-link" href="<?php echo URL_ROOT; ?>/students/dashboard">Dashboard</a>
                        </li>
                    <?php elseif($_SESSION['user_role'] == 'principal' || $_SESSION['user_role'] == 'stream_head') : ?>
                        <li>
                            <a class="nav-link" href="<?php echo URL_ROOT; ?>/admins/dashboard">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a class="nav-link" href="#">Welcome, <?php echo $_SESSION['user_username']; ?></a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo URL_ROOT; ?>/users/logout">Logout</a>
                    </li>
                <?php else : ?>
                    <li>
                        <a class="nav-link" href="<?php echo URL_ROOT; ?>/users/register">Register</a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo URL_ROOT; ?>/users/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

