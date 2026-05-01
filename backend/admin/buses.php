<?php
/**
 * Bus Management - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

$message = '';

// Handle Add Bus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bus'])) {
    $model = sanitize($conn, $_POST['model']);
    $capacity = intval($_POST['capacity']);
    $route_id = intval($_POST['route_id']);
    
    if ($capacity <= 0 || empty($model)) {
        $message = 'Please fill in all fields correctly.';
    } else {
        $sql = "INSERT INTO buses (model, capacity, route_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $model, $capacity, $route_id);
        if ($stmt->execute()) {
            $message = 'Bus added successfully!';
        } else {
            $message = 'Failed to add bus.';
        }
        $stmt->close();
    }
}

// Handle Delete Bus
if (isset($_GET['delete'])) {
    $bus_id = intval($_GET['delete']);
    $sql = "DELETE FROM buses WHERE bus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bus_id);
    if ($stmt->execute()) {
        $message = 'Bus deleted successfully!';
    }
    $stmt->close();
}

// Fetch all buses with route info
$buses = $conn->query("SELECT b.*, r.route_name FROM buses b LEFT JOIN routes r ON b.route_id = r.route_id ORDER BY b.bus_id");

// Fetch routes for dropdown
$routes = $conn->query("SELECT route_id, route_name FROM routes ORDER BY route_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management - Admin</title>
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
                <li><a href="buses.php" class="active"><i class="fas fa-bus"></i> Bus Management</a></li>
                <li><a href="routes.php"><i class="fas fa-route"></i> Route Management</a></li>
                <li><a href="focals.php"><i class="fas fa-user-tie"></i> Focal Persons</a></li>
                <li><a href="students.php"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-bus"></i> Bus Management</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-plus"></i> Add New Bus</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Bus Model</label>
                        <input type="text" name="model" placeholder="e.g., Daewoo 2022" required>
                    </div>
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" placeholder="Number of seats" required>
                    </div>
                    <div class="form-group">
                        <label>Assign to Route</label>
                        <select name="route_id">
                            <option value="0">Unassigned</option>
                            <?php while ($route = $routes->fetch_assoc()): ?>
                                <option value="<?php echo $route['route_id']; ?>"><?php echo htmlspecialchars($route['route_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_bus" class="btn-submit"><i class="fas fa-plus"></i> Add Bus</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> All Buses</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Model</th>
                            <th>Capacity</th>
                            <th>Assigned Route</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($bus = $buses->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $bus['bus_id']; ?></td>
                                <td><?php echo htmlspecialchars($bus['model']); ?></td>
                                <td><?php echo $bus['capacity']; ?> seats</td>
                                <td><?php echo htmlspecialchars($bus['route_name'] ?? 'Unassigned'); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $bus['bus_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
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
