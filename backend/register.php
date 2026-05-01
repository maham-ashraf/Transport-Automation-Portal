<?php
/**
 * Registration Page
 * University Transport Automation Portal
 */

session_start();
require_once 'connection.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $name = sanitize($conn, $_POST['name'] ?? '');
    $role = sanitize($conn, $_POST['role'] ?? 'student');
    $student_reg_no = sanitize($conn, $_POST['student_reg_no'] ?? '');
    $route_id = intval($_POST['route_id'] ?? 0);
    
    // Validation
    if (empty($username) || empty($password) || empty($confirm_password) || empty($name)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif ($role === 'student' && (empty($student_reg_no) || $route_id === 0)) {
        $error = 'Please provide student registration number and select a route.';
    } else {
        // Check if username already exists
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Username already exists. Please choose a different username.';
        } else {
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into users table
                $user_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
                $user_stmt = $conn->prepare($user_sql);
                $user_stmt->bind_param("sss", $username, $hashed_password, $role);
                $user_stmt->execute();
                $user_id = $conn->insert_id;
                
                // Insert role-specific data
                switch ($role) {
                    case 'student':
                        $student_sql = "INSERT INTO students (name, student_reg_no, route_id, user_id, fee_amount) VALUES (?, ?, ?, ?, 55000)";
                        $student_stmt = $conn->prepare($student_sql);
                        $student_stmt->bind_param("ssii", $name, $student_reg_no, $route_id, $user_id);
                        $student_stmt->execute();
                        
                        // Create initial payment records (4 installments)
                        $student_id = $conn->insert_id;
                        $installments = [
                            ['2025-09-15', 1],
                            ['2025-12-15', 2],
                            ['2026-03-15', 3],
                            ['2026-06-15', 4]
                        ];
                        
                        $payment_sql = "INSERT INTO payments (student_id, amount, due_date, installment_number, status) VALUES (?, 13750, ?, ?, 'Pending')";
                        $payment_stmt = $conn->prepare($payment_sql);
                        
                        foreach ($installments as $inst) {
                            $payment_stmt->bind_param("isi", $student_id, $inst[0], $inst[1]);
                            $payment_stmt->execute();
                        }
                        break;
                        
                    case 'focal_person':
                        $focal_sql = "INSERT INTO focal_persons (name, user_id, route_id) VALUES (?, ?, ?)";
                        $focal_stmt = $conn->prepare($focal_sql);
                        $focal_stmt->bind_param("sii", $name, $user_id, $route_id);
                        $focal_stmt->execute();
                        break;
                }
                
                // Commit transaction
                $conn->commit();
                
                $success = 'Registration successful! Please login with your credentials.';
                
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                $error = 'Registration failed. Please try again.';
            }
        }
        $check_stmt->close();
    }
}

// Fetch available routes for dropdown
$routes_sql = "SELECT route_id, route_name FROM routes ORDER BY route_id";
$routes_result = $conn->query($routes_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - University Transport Automation Portal</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 480px;
            padding: 40px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header img {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .register-header h1 {
            font-size: 1.5rem;
            color: #1a365d;
            margin-bottom: 5px;
        }
        .register-header p {
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
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3182ce;
        }
        .btn-register {
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
        .btn-register:hover {
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
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        .register-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .register-footer a {
            color: #3182ce;
            text-decoration: none;
            font-weight: 500;
        }
        .register-footer a:hover {
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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <img src="../download.jfif" alt="Superior University Logo">
                <h1>Superior University</h1>
                <p>Transport Automation Portal - Registration</p>
            </div>
            
            <?php if ($error): ?>
                <?php echo showError($error); ?>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <?php echo showSuccess($success); ?>
            <?php endif; ?>
            
            <form method="POST" action="" id="registerForm">
                <div class="form-group">
                    <label for="role"><i class="fas fa-user-tag"></i> Register As *</label>
                    <select id="role" name="role" required onchange="toggleFields()">
                        <option value="student">Student</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="name"><i class="fas fa-id-card"></i> Full Name *</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group" id="studentRegField">
                    <label for="student_reg_no"><i class="fas fa-graduation-cap"></i> Student Registration Number *</label>
                    <input type="text" id="student_reg_no" name="student_reg_no" placeholder="e.g., ST-001">
                </div>
                
                <div class="form-group" id="routeField">
                    <label for="route_id"><i class="fas fa-route"></i> Select Route *</label>
                    <select id="route_id" name="route_id">
                        <option value="">-- Select Route --</option>
                        <?php while ($route = $routes_result->fetch_assoc()): ?>
                            <option value="<?php echo $route['route_id']; ?>">
                                <?php echo htmlspecialchars($route['route_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username *</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password *</label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
            
            <div class="register-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <a href="../index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
    
    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            const studentRegField = document.getElementById('studentRegField');
            const routeField = document.getElementById('routeField');
            
            if (role === 'student') {
                studentRegField.classList.remove('hidden');
                routeField.classList.remove('hidden');
                document.getElementById('student_reg_no').required = true;
                document.getElementById('route_id').required = true;
            } else if (role === 'focal_person') {
                studentRegField.classList.add('hidden');
                routeField.classList.remove('hidden');
                document.getElementById('student_reg_no').required = false;
                document.getElementById('route_id').required = true;
            } else {
                studentRegField.classList.add('hidden');
                routeField.classList.add('hidden');
                document.getElementById('student_reg_no').required = false;
                document.getElementById('route_id').required = false;
            }
        }
        
        // Initialize on page load
        toggleFields();
    </script>
</body>
</html>
