<?php
/**
 * Focal Person Dashboard
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('focal_person');

$focal_id = $_SESSION['focal_id'];
$route_id = $_SESSION['route_id'];

// Fetch assigned route info
$route_sql = "SELECT r.*, b.model as bus_model, b.capacity as bus_capacity,
              fp.name as focal_name
              FROM routes r
              LEFT JOIN buses b ON r.route_id = b.route_id
              LEFT JOIN focal_persons fp ON r.route_id = fp.route_id
              WHERE r.route_id = ? AND fp.focal_id = ?";
$route_stmt = $conn->prepare($route_sql);
$route_stmt->bind_param("ii", $route_id, $focal_id);
$route_stmt->execute();
$route_info = $route_stmt->get_result()->fetch_assoc();
$route_stmt->close();

// Count assigned students
$students_count_sql = "SELECT COUNT(*) as count FROM students WHERE route_id = ?";
$students_count_stmt = $conn->prepare($students_count_sql);
$students_count_stmt->bind_param("i", $route_id);
$students_count_stmt->execute();
$students_count = $students_count_stmt->get_result()->fetch_assoc()['count'];
$students_count_stmt->close();

// Count unpaid students
$unpaid_sql = "SELECT COUNT(DISTINCT s.student_id) as count 
               FROM students s
               JOIN payments p ON s.student_id = p.student_id
               WHERE s.route_id = ? AND p.status IN ('Unpaid', 'Pending')";
$unpaid_stmt = $conn->prepare($unpaid_sql);
$unpaid_stmt->bind_param("i", $route_id);
$unpaid_stmt->execute();
$unpaid_count = $unpaid_stmt->get_result()->fetch_assoc()['count'];
$unpaid_stmt->close();

// Today's attendance count
$today = date('Y-m-d');
$today_sql = "SELECT COUNT(*) as count FROM attendance 
              WHERE focal_id = ? AND date = ?";
$today_stmt = $conn->prepare($today_sql);
$today_stmt->bind_param("is", $focal_id, $today);
$today_stmt->execute();
$today_count = $today_stmt->get_result()->fetch_assoc()['count'];
$today_stmt->close();

// Recent students list
$students_sql = "SELECT s.*, 
                (SELECT status FROM payments WHERE student_id = s.student_id ORDER BY due_date DESC LIMIT 1) as latest_payment_status
                FROM students s 
                WHERE s.route_id = ? 
                ORDER BY s.name 
                LIMIT 10";
$students_stmt = $conn->prepare($students_sql);
$students_stmt->bind_param("i", $route_id);
$students_stmt->execute();
$students = $students_stmt->get_result();
$students_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focal Person Dashboard - University Transport Automation Portal</title>
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
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-icon.blue { background: #ebf8ff; color: #3182ce; }
        .stat-icon.green { background: #f0fff4; color: #38a169; }
        .stat-icon.yellow { background: #fffff0; color: #d69e2e; }
        .stat-icon.red { background: #fff5f5; color: #e53e3e; }
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a365d;
        }
        .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .content-card h2 {
            margin: 0 0 20px;
            color: #1a365d;
            font-size: 1.2rem;
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
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .action-btn {
            padding: 15px 20px;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .action-btn:hover {
            background: #2c5282;
        }
        .action-btn i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../download.jfif" alt="Logo">
                <h3><?php echo htmlspecialchars($_SESSION['focal_name']); ?></h3>
                <p>Focal Person Portal</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="mark_attendance.php"><i class="fas fa-clipboard-check"></i> Mark Attendance</a></li>
                <li><a href="view_students.php"><i class="fas fa-users"></i> View Students</a></li>
                <li><a href="attendance_history.php"><i class="fas fa-history"></i> Attendance History</a></li>
                <li><a href="unpaid_students.php"><i class="fas fa-exclamation-triangle"></i> Unpaid Students</a></li>
                <li><a href="send_notification.php"><i class="fas fa-bell"></i> Send Notification</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-tachometer-alt"></i> Focal Person Dashboard</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Stats Grid -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="stat-label">Total Students</div>
                            <div class="stat-value"><?php echo $students_count; ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">Today's Attendance</div>
                            <div class="stat-value"><?php echo $today_count; ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon yellow">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Unpaid Students</div>
                            <div class="stat-value"><?php echo $unpaid_count; ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon red">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div>
                            <div class="stat-label">Bus Capacity</div>
                            <div class="stat-value"><?php echo htmlspecialchars($route_info['bus_capacity'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Route Info -->
                <div class="content-card">
                    <h2><i class="fas fa-route"></i> My Route Information</h2>
                    <div class="info-row">
                        <div class="info-label">Route Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['route_name'] ?? 'Not Assigned'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Book Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['book_no'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bus Model</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['bus_model'] ?? 'Not Assigned'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['academic_year'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Yearly Fare</div>
                        <div class="info-value"><?php echo htmlspecialchars($route_info['yearly_fare'] ?? 'N/A'); ?></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    <div class="quick-actions">
                        <a href="mark_attendance.php" class="action-btn">
                            <i class="fas fa-clipboard-check"></i> Mark Attendance
                        </a>
                        <a href="view_students.php" class="action-btn">
                            <i class="fas fa-users"></i> View All Students
                        </a>
                        <a href="unpaid_students.php" class="action-btn">
                            <i class="fas fa-exclamation-triangle"></i> View Unpaid
                        </a>
                        <a href="send_notification.php" class="action-btn">
                            <i class="fas fa-bell"></i> Send Notification
                        </a>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="content-card" style="margin-top: 20px;">
                <h2><i class="fas fa-users"></i> Assigned Students</h2>
                <?php if ($students->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Reg. Number</th>
                                <th>Fee Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $student['student_id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($student['latest_payment_status'] ?? 'unpaid'); ?>">
                                            <?php echo $student['latest_payment_status'] ?? 'Unpaid'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <p style="text-align: center; margin-top: 15px;">
                        <a href="view_students.php" style="color: #3182ce; text-decoration: none;">View All Students <i class="fas fa-arrow-right"></i></a>
                    </p>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 20px;">
                        <i class="fas fa-info-circle"></i> No students assigned to this route yet.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
