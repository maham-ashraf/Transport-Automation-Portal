<?php
/**
 * Focal Person Management - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

$message = '';

// Handle Add Focal Person
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_focal'])) {
    $name = sanitize($conn, $_POST['name']);
    $username = sanitize($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $route_id = intval($_POST['route_id']);
    
    if (empty($name) || empty($username) || empty($_POST['password'])) {
        $message = 'Please fill in all required fields.';
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $message = 'Username already exists.';
        } else {
            $conn->begin_transaction();
            try {
                // Create user
                $user_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, 'focal_person')";
                $user_stmt = $conn->prepare($user_sql);
                $user_stmt->bind_param("ss", $username, $password);
                $user_stmt->execute();
                $user_id = $conn->insert_id;
                
                // Create focal person record
                $focal_sql = "INSERT INTO focal_persons (name, user_id, route_id) VALUES (?, ?, ?)";
                $focal_stmt = $conn->prepare($focal_sql);
                $focal_stmt->bind_param("sii", $name, $user_id, $route_id);
                $focal_stmt->execute();
                
                $conn->commit();
                $message = 'Focal person added successfully!';
            } catch (Exception $e) {
                $conn->rollback();
                $message = 'Failed to add focal person.';
            }
        }
        $check->close();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $focal_id = intval($_GET['delete']);
    $get_user = $conn->prepare("SELECT user_id FROM focal_persons WHERE focal_id = ?");
    $get_user->bind_param("i", $focal_id);
    $get_user->execute();
    $user_id = $get_user->get_result()->fetch_assoc()['user_id'];
    $get_user->close();
    
    $conn->query("DELETE FROM focal_persons WHERE focal_id = $focal_id");
    $conn->query("DELETE FROM users WHERE id = $user_id");
    $message = 'Focal person deleted successfully!';
}

// Fetch all focal persons
$focals = $conn->query("SELECT fp.*, r.route_name, u.username 
                        FROM focal_persons fp 
                        LEFT JOIN routes r ON fp.route_id = r.route_id
                        LEFT JOIN users u ON fp.user_id = u.id
                        ORDER BY fp.focal_id");

// Fetch routes for dropdown
$routes = $conn->query("SELECT route_id, route_name FROM routes ORDER BY route_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focal Person Management - Admin</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #1a365d; color: white; padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar-header img { width: 60px; margin-bottom: 10px; }
        .sidebar-header h3 { font-size: 1rem; margin: 0; }
        .nav-menu { list-style: none; padding: 20px 0; margin: 0; }
        .nav-menu a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { background: rgba(255,255,255,0.1); border-left-color: #63b3ed; }
        .nav-menu i { width: 24px; margin-right: 10px; }
        .main-content { flex: 1; margin-left: 260px; padding: 20px; background: #f7fafc; min-height: 100vh; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .top-bar h1 { margin: 0; font-size: 1.5rem; color: #1a365d; }
        .btn-logout { padding: 8px 16px; background: #e53e3e; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 0.9rem; }
        .content-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-card h2 { margin: 0 0 20px; color: #1a365d; font-size: 1.3rem; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; }
        .btn-submit { padding: 10px 25px; background: #3182ce; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .btn-delete { padding: 6px 12px; background: #e53e3e; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        .alert-success { background: #c6f6d5; color: #22543d; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../download.jfif" alt="Logo">
                <h3>Administrator</h3>
                <p>Admin Portal</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="buses.php"><i class="fas fa-bus"></i> Bus Management</a></li>
                <li><a href="routes.php"><i class="fas fa-route"></i> Route Management</a></li>
                <li><a href="focals.php" class="active"><i class="fas fa-user-tie"></i> Focal Persons</a></li>
                <li><a href="students.php"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-user-tie"></i> Focal Person Management</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-plus"></i> Add Focal Person</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Choose username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="form-group">
                        <label>Assign to Route</label>
                        <select name="route_id">
                            <option value="0">Select Route</option>
                            <?php while ($route = $routes->fetch_assoc()): ?>
                                <option value="<?php echo $route['route_id']; ?>"><?php echo htmlspecialchars($route['route_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_focal" class="btn-submit"><i class="fas fa-plus"></i> Add Focal Person</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> All Focal Persons</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Assigned Route</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($focal = $focals->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $focal['focal_id']; ?></td>
                                <td><?php echo htmlspecialchars($focal['name']); ?></td>
                                <td><?php echo htmlspecialchars($focal['username']); ?></td>
                                <td><?php echo htmlspecialchars($focal['route_name'] ?? 'Not Assigned'); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $focal['focal_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
