<?php
/**
 * Student Route Information
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('student');

$student_id = $_SESSION['student_id'];
$user_id = $_SESSION['user_id'];

// Fetch student route info
$sql = "SELECT s.*, r.route_name, r.book_no, r.catalog_title, r.yearly_fare, r.academic_year,
        b.bus_id, b.model as bus_model, b.capacity as bus_capacity
        FROM students s
        LEFT JOIN routes r ON s.route_id = r.route_id
        LEFT JOIN buses b ON r.route_id = b.route_id
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$route_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch route stops
$stops = [];
if ($route_info && $route_info['route_id']) {
    $stops_sql = "SELECT * FROM route_stops WHERE route_id = ? ORDER BY stop_order";
    $stops_stmt = $conn->prepare($stops_sql);
    $stops_stmt->bind_param("i", $route_info['route_id']);
    $stops_stmt->execute();
    $stops = $stops_stmt->get_result();
    $stops_stmt->close();
}

// Fetch available routes for change
$all_routes_sql = "SELECT route_id, route_name, yearly_fare FROM routes ORDER BY route_id";
$all_routes = $conn->query($all_routes_sql);

// Handle route change request
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_route'])) {
    $new_route_id = intval($_POST['new_route_id']);
    
    if ($new_route_id > 0 && $new_route_id != $route_info['route_id']) {
        // Update student route
        $update_sql = "UPDATE students SET route_id = ? WHERE student_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_route_id, $student_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['route_id'] = $new_route_id;
            $message = 'Route changed successfully!';
            // Refresh page to show updated info
            header("Location: route.php?msg=success");
            exit();
        } else {
            $message = 'Failed to change route. Please try again.';
        }
        $update_stmt->close();
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $message = 'Route changed successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Route - Student Portal</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #1a365d;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar-header img {
            width: 60px;
            margin-bottom: 10px;
        }
        .sidebar-header h3 {
            font-size: 1rem;
            margin: 0;
        }
        .sidebar-header p {
            font-size: 0.8rem;
            opacity: 0.8;
            margin: 5px 0 0;
        }
        .nav-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }
        .nav-menu li {
            margin: 5px 0;
        }
        .nav-menu a {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .nav-menu a:hover,
        .nav-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #63b3ed;
        }
        .nav-menu i {
            width: 24px;
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            background: #f7fafc;
            min-height: 100vh;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .top-bar h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #1a365d;
        }
        .btn-logout {
            padding: 8px 16px;
            background: #e53e3e;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .content-card h2 {
            margin: 0 0 20px;
            color: #1a365d;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            width: 150px;
            color: #718096;
            font-weight: 500;
        }
        .info-value {
            flex: 1;
            color: #1a365d;
            font-weight: 600;
        }
        .stops-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .stop-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #3182ce;
        }
        .stop-number {
            width: 36px;
            height: 36px;
            background: #3182ce;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
        }
        .stop-name {
            flex: 1;
            font-weight: 500;
            color: #2d3748;
        }
        .stop-icon {
            color: #3182ce;
            margin-right: 10px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
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
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-primary {
            padding: 12px 24px;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #2c5282;
        }
        .route-visual {
            position: relative;
            padding-left: 30px;
        }
        .route-line {
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3182ce;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../download.jfif" alt="Logo">
                <h3><?php echo htmlspecialchars($_SESSION['student_name']); ?></h3>
                <p>Student Portal</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="route.php" class="active"><i class="fas fa-route"></i> My Route</a></li>
                <li><a href="attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                <li><a href="fees.php"><i class="fas fa-money-bill"></i> Fee Status</a></li>
                <li><a href="complaint.php"><i class="fas fa-comment-alt"></i> Complaint</a></li>
                <li><a href="feedback.php"><i class="fas fa-star"></i> Feedback</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-route"></i> My Route</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($route_info && $route_info['route_id']): ?>
                <!-- Route Info Card -->
                <div class="content-card">
                    <h2><i class="fas fa-info-circle"></i> Route Information</h2>
                    <div class="info-row">
                        <div class="info-label">Route Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['route_name']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Book Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['book_no']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Catalog Title</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['catalog_title']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['academic_year']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Yearly Fare</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['yearly_fare']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Assigned Bus</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['bus_model'] ?? 'Not Assigned'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bus Capacity</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['bus_capacity'] ?? 'N/A'); ?> seats</div>
                    </div>
                </div>

                <!-- Route Stops -->
                <div class="content-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Route Stops</h2>
                    <div class="route-visual">
                        <div class="route-line"></div>
                        <ul class="stops-list">
                            <?php while ($stop = $stops->fetch_assoc()): ?>
                                <li class="stop-item">
                                    <div class="stop-number"><?php echo $stop['stop_order']; ?></div>
                                    <i class="fas fa-map-pin stop-icon"></i>
                                    <div class="stop-name"><?php echo htmlspecialchars($stop['stop_name']); ?></div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <!-- Change Route -->
                <div class="content-card">
                    <h2><i class="fas fa-exchange-alt"></i> Change Route</h2>
                    <p style="color: #718096; margin-bottom: 20px;">
                        Select a different route from the available options below. Note: Changing route may affect your fee structure.
                    </p>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="new_route_id"><i class="fas fa-route"></i> Select New Route</label>
                            <select name="new_route_id" id="new_route_id" required>
                                <option value="">-- Select Route --</option>
                                <?php 
                                $all_routes->data_seek(0);
                                while ($route = $all_routes->fetch_assoc()): 
                                    $selected = ($route['route_id'] == $route_info['route_id']) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $route['route_id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($route['route_name'] . ' - ' . $route['yearly_fare']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" name="change_route" class="btn-primary">
                            <i class="fas fa-save"></i> Change Route
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="content-card">
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: #e53e3e; margin-bottom: 15px;"></i>
                        <h2>No Route Assigned</h2>
                        <p style="color: #718096;">You currently don't have any route assigned. Please contact the transport office.</p>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
