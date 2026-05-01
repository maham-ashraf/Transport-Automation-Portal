<?php
/**
 * Route Management - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

$message = '';

// Handle Add Route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_route'])) {
    $route_name = sanitize($conn, $_POST['route_name']);
    $book_no = intval($_POST['book_no']);
    $yearly_fare = sanitize($conn, $_POST['yearly_fare']);
    $academic_year = sanitize($conn, $_POST['academic_year']);
    
    if (empty($route_name) || empty($yearly_fare)) {
        $message = 'Please fill in all required fields.';
    } else {
        $sql = "INSERT INTO routes (route_name, book_no, catalog_title, yearly_fare, academic_year) 
                VALUES (?, ?, ?, ?, ?)";
        $catalog_title = "BUS ROUTE " . strtoupper($route_name);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $route_name, $book_no, $catalog_title, $yearly_fare, $academic_year);
        if ($stmt->execute()) {
            $message = 'Route added successfully!';
        } else {
            $message = 'Failed to add route.';
        }
        $stmt->close();
    }
}

// Handle Delete Route
if (isset($_GET['delete'])) {
    $route_id = intval($_GET['delete']);
    $sql = "DELETE FROM routes WHERE route_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $route_id);
    if ($stmt->execute()) {
        $message = 'Route deleted successfully!';
    }
    $stmt->close();
}

// Fetch all routes
$routes = $conn->query("SELECT r.*, COUNT(s.student_id) as student_count, b.model as bus_model 
                       FROM routes r 
                       LEFT JOIN students s ON r.route_id = s.route_id
                       LEFT JOIN buses b ON r.route_id = b.route_id
                       GROUP BY r.route_id 
                       ORDER BY r.route_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Management - Admin</title>
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
        .form-group input { width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; }
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
                <li><a href="routes.php" class="active"><i class="fas fa-route"></i> Route Management</a></li>
                <li><a href="focals.php"><i class="fas fa-user-tie"></i> Focal Persons</a></li>
                <li><a href="students.php"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-route"></i> Route Management</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-plus"></i> Add New Route</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Route Name</label>
                        <input type="text" name="route_name" placeholder="e.g., Samundri Road" required>
                    </div>
                    <div class="form-group">
                        <label>Book Number</label>
                        <input type="number" name="book_no" placeholder="Route book number">
                    </div>
                    <div class="form-group">
                        <label>Yearly Fare</label>
                        <input type="text" name="yearly_fare" placeholder="e.g., 13,750 x 4 = PKR 55,000" required>
                    </div>
                    <div class="form-group">
                        <label>Academic Year</label>
                        <input type="text" name="academic_year" placeholder="e.g., 2025-2026" value="2025-2026">
                    </div>
                    <button type="submit" name="add_route" class="btn-submit"><i class="fas fa-plus"></i> Add Route</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> All Routes</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Book No</th>
                            <th>Route Name</th>
                            <th>Yearly Fare</th>
                            <th>Students</th>
                            <th>Bus</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($route = $routes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $route['route_id']; ?></td>
                                <td><?php echo $route['book_no']; ?></td>
                                <td><?php echo htmlspecialchars($route['route_name']); ?></td>
                                <td><?php echo htmlspecialchars($route['yearly_fare']); ?></td>
                                <td><?php echo $route['student_count']; ?></td>
                                <td><?php echo htmlspecialchars($route['bus_model'] ?? 'None'); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $route['route_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
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
