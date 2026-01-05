<?php
session_start();
include 'config/baseConfig.php';
include 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password';
    } else {
        $query = "SELECT u.*, r.name as role_name FROM users u 
                  JOIN roles r ON u.role_id = r.id 
                  WHERE u.username = ? AND u.status = 1 AND u.is_deleted = 0";
        $stmt = $con_main->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['role_name'] = $user['role_name'];
                $_SESSION['company_id'] = $user['company_id'];
                
                // Update last login
                $update = "UPDATE users SET last_login = NOW(), login_count = login_count + 1 WHERE id = ?";
                $stmt_update = $con_main->prepare($update);
                $stmt_update->bind_param("i", $user['id']);
                $stmt_update->execute();
                
                // Redirect based on role
                if ($user['role_id'] == 3) {
                    header('Location: Admin/dashboard.php');
                } elseif ($user['role_id'] == 2) {
                    header('Location: Employer/dashboard.php');
                } else {
                    header('Location: home.php');
                }
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CareerBridge</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="logo-header">
               <img src="<?php echo BaseConfig::$BASE_URL; ?>assets/images/logo.png" width="180px" />
                <p>Login to your account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="links">
                <a href="Employer/register.php">Register as Employer</a>
                <a href="home.php">Browse Jobs</a>
            </div>
        </div>
    </div>
</body>
</html>
