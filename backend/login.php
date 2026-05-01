<?php
/**
 * Login Page
 * University Transport Automation Portal
 */

session_start();
require_once 'connection.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    switch ($role) {
        case 'student':
            header("Location: student/dashboard.php");
            break;
        case 'focal_person':
            header("Location: focal/dashboard.php");
            break;
        case 'accounts':
            header("Location: accounts/dashboard.php");
            break;
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        default:
            header("Location: ../index.html");
    }
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Query to check user credentials
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Get additional user details based on role
                switch ($user['role']) {
                    case 'student':
                        $sql = "SELECT student_id, name, route_id FROM students WHERE user_id = ?";
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param("i", $user['id']);
                        $stmt2->execute();
                        $studentResult = $stmt2->get_result();
                        if ($studentResult->num_rows === 1) {
                            $student = $studentResult->fetch_assoc();
                            $_SESSION['student_id'] = $student['student_id'];
                            $_SESSION['student_name'] = $student['name'];
                            $_SESSION['route_id'] = $student['route_id'];
                        }
                        $stmt2->close();
                        header("Location: student/dashboard.php");
                        break;
                        
                    case 'focal_person':
                        $sql = "SELECT focal_id, name, route_id FROM focal_persons WHERE user_id = ?";
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param("i", $user['id']);
                        $stmt2->execute();
                        $focalResult = $stmt2->get_result();
                        if ($focalResult->num_rows === 1) {
                            $focal = $focalResult->fetch_assoc();
                            $_SESSION['focal_id'] = $focal['focal_id'];
                            $_SESSION['focal_name'] = $focal['name'];
                            $_SESSION['route_id'] = $focal['route_id'];
                        }
                        $stmt2->close();
                        header("Location: focal/dashboard.php");
                        break;
                        
                    case 'accounts':
                        $_SESSION['accounts_name'] = $user['username'];
                        header("Location: accounts/dashboard.php");
                        break;
                        
                    case 'admin':
                        $_SESSION['admin_name'] = $user['username'];
                        header("Location: admin/dashboard.php");
                        break;
                }
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Transport Automation Portal</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header img {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .login-header h1 {
            font-size: 1.5rem;
            color: #1a365d;
            margin-bottom: 5px;
        }
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3182ce;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #2c5282;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }
        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .login-footer a {
            color: #3182ce;
            text-decoration: none;
            font-weight: 500;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            color: #3182ce;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="../download.jfif" alt="Superior University Logo">
                <h1>Superior University</h1>
                <p>Transport Automation Portal</p>
            </div>
            
            <?php if ($error): ?>
                <?php echo showError($error); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['message'])): ?>
                <?php 
                echo $_SESSION['message_type'] === 'success' ? showSuccess($_SESSION['message']) : showError($_SESSION['message']);
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <a href="../index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
