<?php
require_once 'auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Shiva Industries CMS</title>
    <link rel="icon" href="assets/img/favicon.png" type="image/png">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-logo {
            max-height: 60px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="assets/img/logo.png" alt="Shiva Industries" class="login-logo mb-3">
                    <h4 class="fw-bold">Welcome Back!</h4>
                    <p class="text-muted">Login to your CMS account</p>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="material-icons-outlined align-middle me-1">error</i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="material-icons-outlined align-middle me-1">check_circle</i>
                    Registration successful! Please login.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">email</i></span>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" 
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">lock</i></span>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="material-icons-outlined align-middle me-1">login</i> Login
                        </button>
                    </div>
                </form>
                
                <!-- <div class="text-center mt-4">
                    <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold">Register</a></p>
                </div> -->
            </div>
        </div>
    </div>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

