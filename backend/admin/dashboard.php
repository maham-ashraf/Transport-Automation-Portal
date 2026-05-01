<?php
/**
 * Admin Dashboard
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

// Statistics
$stats = [];
$stats['students'] = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$stats['routes'] = $conn->query("SELECT COUNT(*) as count FROM routes")->fetch_assoc()['count'];
$stats['buses'] = $conn->query("SELECT COUNT(*) as count FROM buses")->fetch_assoc()['count'];
$stats['focal'] = $conn->query("SELECT COUNT(*) as count FROM focal_persons")->fetch_assoc()['count'];
$stats['users'] = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Payment stats
$payment_stats = $conn->query("SELECT 
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as collected,
    SUM(amount) as total
FROM payments")->fetch_assoc();

// Recent students
$recent_students = $conn->query("SELECT s.*, r.route_name FROM students s 
                                  LEFT JOIN routes r ON s.route_id = r.route_id 
                                  ORDER BY s.created_at DESC LIMIT 5");

// Recent complaints
$recent_complaints = $conn->query("SELECT c.*, s.name as student_name 
                                    FROM complaints c 
                                    LEFT JOIN students s ON c.student_id = s.student_id 
                                    ORDER BY c.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - University Transport Automation Portal</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #1a365d; color: white; padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar-header img { width: 60px; margin-bottom: 10px; }
        .sidebar-header h3 { font-size: 1rem; margin: 0; }
        .sidebar-header p { font-size: 0.8rem; opacity: 0.8; margin: 5px 0 0; }
        .nav-menu { list-style: none; padding: 20px 0; margin: 0; }
        .nav-menu li { margin: 5px 0; }
        .nav-menu a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { background: rgba(255,255,255,0.1); border-left-color: #63b3ed; }
        .nav-menu i { width: 24px; margin-right: 10px; }
        .main-content { flex: 1; margin-left: 260px; padding: 20px; background: #f7fafc; min-height: 100vh; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .top-bar h1 { margin: 0; font-size: 1.5rem; color: #1a365d; }
        .btn-logout { padding: 8px 16px; background: #e53e3e; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 0.9rem; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat-card-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .stat-icon.blue { background: #ebf8ff; color: #3182ce; }
        .stat-icon.green { background: #f0fff4; color: #38a169; }
        .stat-icon.yellow { background: #fffff0; color: #d69e2e; }
        .stat-icon.red { background: #fff5f5; color: #e53e3e; }
        .stat-icon.purple { background: #faf5ff; color: #805ad5; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #1a365d; }
        .stat-label { color: #718096; font-size: 0.9rem; }
        .content-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-card h2 { margin: 0 0 20px; color: #1a365d; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; color: #4a5568; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-Open { background: #fed7d7; color: #c53030; }
        .status-Resolved { background: #c6f6d5; color: #22543d; }
        .quick-actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .action-btn { padding: 15px; background: #3182ce; color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 10px; font-weight: 600; }
        .action-btn:hover { background: #2c5282; }
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
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="buses.php"><i class="fas fa-bus"></i> Bus Management</a></li>
                <li><a href="routes.php"><i class="fas fa-route"></i> Route Management</a></li>
                <li><a href="focals.php"><i class="fas fa-user-tie"></i> Focal Persons</a></li>
                <li><a href="students.php"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="payments.php"><i class="fas fa-money-bill"></i> All Payments</a></li>
                <li><a href="complaints.php"><i class="fas fa-comment-alt"></i> Complaints</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                        <div><div class="stat-label">Students</div><div class="stat-value"><?php echo $stats['students']; ?></div></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green"><i class="fas fa-route"></i></div>
                        <div><div class="stat-label">Routes</div><div class="stat-value"><?php echo $stats['routes']; ?></div></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon yellow"><i class="fas fa-bus"></i></div>
                        <div><div class="stat-label">Buses</div><div class="stat-value"><?php echo $stats['buses']; ?></div></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon red"><i class="fas fa-user-tie"></i></div>
                        <div><div class="stat-label">Focal Persons</div><div class="stat-value"><?php echo $stats['focal']; ?></div></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon purple"><i class="fas fa-money-bill-wave"></i></div>
                        <div><div class="stat-label">Collected</div><div class="stat-value">PKR <?php echo number_format($payment_stats['collected'] ?? 0, 0); ?></div></div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="quick-actions">
                    <a href="buses.php" class="action-btn"><i class="fas fa-bus"></i> Manage Buses</a>
                    <a href="routes.php" class="action-btn"><i class="fas fa-route"></i> Manage Routes</a>
                    <a href="focals.php" class="action-btn"><i class="fas fa-user-tie"></i> Add Focal Person</a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="content-card">
                    <h2><i class="fas fa-user-graduate"></i> Recent Students</h2>
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Route</th><th>Fee Status</th></tr></thead>
                        <tbody>
                            <?php while ($student = $recent_students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['route_name'] ?? 'Not Assigned'); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($student['fee_status']); ?>"><?php echo $student['fee_status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="content-card">
                    <h2><i class="fas fa-comment-alt"></i> Recent Complaints</h2>
                    <table class="data-table">
                        <thead><tr><th>Subject</th><th>Student</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php while ($complaint = $recent_complaints->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($complaint['student_name'] ?? 'Unknown'); ?></td>
                                    <td><span class="status-badge status-<?php echo str_replace(' ', '', $complaint['status']); ?>"><?php echo $complaint['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
