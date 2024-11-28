<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "templates/header.php"; ?>

    <title>Login - Barangay Online System</title>

    <style>
        label.placeholder {
            background: transparent;
            cursor: auto;
        }
    </style>
    <!-- Include reCAPTCHA v3 script -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LfA0IwqAAAAAMZPVDvQB9di5qG6BJvR7IA3JUS6"></script>
</head>

<body class="login">
    <?php include "templates/loading_screen.php"; ?>
    <div class="wrapper wrapper-login">

        <div class="alert-box" style="display:none;">
            <?php include "templates/alert.php"; ?>
        </div>

        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Sign In Here</h3>
            <div class="login-form">
                <form method="POST" action="model/login.php" autocomplete="off" id="loginForm">
                    <div class="form-group form-floating-label">
                        <input id="username" name="username" type="text" class="form-control input-border-bottom" required>
                        <label for="username" class="placeholder">Username</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="password" name="password" type="password" class="form-control input-border-bottom" required>
                        <label for="password" class="placeholder">Password</label>
                        <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                    
                    <!-- No need for v2 reCAPTCHA div -->
                    <div class="form-action mb-3 d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary btn-block fw-bold">Sign In</button>
                        <a href="resident-register.php" class="btn btn-success btn-block text-white fw-bold">Create New Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "templates/footer.php"; ?>

    <!-- Add a script to trigger reCAPTCHA v3 token before form submission -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            grecaptcha.ready(function() {
                grecaptcha.execute('6LfA0IwqAAAAAMZPVDvQB9di5qG6BJvR7IA3JUS6', { action: 'login' }).then(function(token) {
                    // Create a hidden input field for the token
                    var tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'g-recaptcha-response';
                    tokenInput.value = token;
                    document.getElementById('loginForm').appendChild(tokenInput);
                    document.getElementById('loginForm').submit(); // Submit the form after adding the token
                });
            });
        });
    </script>
</body>

</html>
