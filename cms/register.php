<?php
require_once 'auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$errors = [];

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Name must be between 2 and 100 characters';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        $result = registerUser($name, $email, $password);
        if ($result['success']) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Shiva Industries CMS</title>
    <link rel="icon" href="assets/img/favicon.png" type="image/png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <style>
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .register-card {
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-logo {
            max-height: 60px;
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-card card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="assets/img/logo.png" alt="Shiva Industries" class="login-logo mb-3">
                    <h4 class="fw-bold">Create Account</h4>
                    <p class="text-muted">Register for CMS access</p>
                </div>
                
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">person</i></span>
                            <input type="text" class="form-control" name="name" placeholder="Enter your name" 
                                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">email</i></span>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" 
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">lock</i></span>
                            <input type="password" class="form-control" name="password" placeholder="Min 6 characters" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">lock</i></span>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="material-icons-outlined align-middle me-1">person_add</i> Register
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Already have an account? <a href="login.php" class="text-primary fw-bold">Login</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

