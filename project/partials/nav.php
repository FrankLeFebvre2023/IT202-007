
<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
    <ul class="nav">
        <li><a href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
            <li><a href="surveys.php">View Surveys</a></li
        <?php if (is_logged_in()): ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
			<li><a href="create_survey.php">Create Survey</a></li>
			<li><a href="list_results.php">View Results</a></li>
			<li><a href="my_surveys.php">My Surveys</a></li>
        <?php endif; ?>
    </ul>
</nav>