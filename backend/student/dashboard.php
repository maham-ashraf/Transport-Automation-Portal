<?php
/**
 * Student Dashboard
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('student');

$student_id = $_SESSION['student_id'];

// Fetch student details with route and bus info
$sql = "SELECT s.*, r.route_name, r.yearly_fare, b.model as bus_model, b.capacity as bus_capacity
        FROM students s
        LEFT JOIN routes r ON s.route_id = r.route_id
        LEFT JOIN buses b ON r.route_id = b.route_id
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch payment summary
$payment_sql = "SELECT 
    COUNT(*) as total_installments,
    SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) as paid_count,
    SUM(CASE WHEN status = 'Unpaid' THEN 1 ELSE 0 END) as unpaid_count,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_paid,
    SUM(amount) as total_amount
FROM payments WHERE student_id = ?";
$payment_stmt = $conn->prepare($payment_sql);
$payment_stmt->bind_param("i", $student_id);
$payment_stmt->execute();
$payment_summary = $payment_stmt->get_result()->fetch_assoc();
$payment_stmt->close();

// Fetch recent attendance
$attendance_sql = "SELECT a.*, fp.name as marked_by 
                  FROM attendance a 
                  LEFT JOIN focal_persons fp ON a.focal_id = fp.focal_id
                  WHERE a.student_id = ? 
                  ORDER BY a.date DESC 
                  LIMIT 10";
$attendance_stmt = $conn->prepare($attendance_sql);
$attendance_stmt->bind_param("i", $student_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
$attendance_stmt->close();

// Calculate attendance statistics
$stats_sql = "SELECT 
    COUNT(*) as total_days,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_days,
    SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_days
FROM attendance WHERE student_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $student_id);
$stats_stmt->execute();
$attendance_stats = $stats_stmt->get_result()->fetch_assoc();
$stats_stmt->close();

$attendance_rate = $attendance_stats['total_days'] > 0 
    ? round(($attendance_stats['present_days'] / $attendance_stats['total_days']) * 100, 1) 
    : 0;

// Fetch notifications
$notif_sql = "SELECT * FROM notifications 
              WHERE route_id IS NULL OR route_id = ? 
              ORDER BY created_at DESC 
              LIMIT 5";
$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param("i", $student['route_id']);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result();
$notif_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - University Transport Automation Portal</title>
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
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-menu span {
            color: #4a5568;
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
        .btn-logout:hover {
            background: #c53030;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
        .status-present { background: #c6f6d5; color: #22543d; }
        .status-absent { background: #fed7d7; color: #c53030; }
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
        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill {
            height: 100%;
            background: #38a169;
            transition: width 0.3s;
        }
        .notification-item {
            padding: 15px;
            border-left: 4px solid #3182ce;
            background: #f7fafc;
            border-radius: 0 8px 8px 0;
            margin-bottom: 10px;
        }
        .notification-item.alert { border-left-color: #e53e3e; }
        .notification-item.info { border-left-color: #3182ce; }
        .notification-item.warning { border-left-color: #d69e2e; }
        .notification-title {
            font-weight: 600;
            color: #1a365d;
            margin-bottom: 5px;
        }
        .notification-time {
            font-size: 0.8rem;
            color: #718096;
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
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="route.php"><i class="fas fa-route"></i> My Route</a></li>
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
                <h1><i class="fas fa-tachometer-alt"></i> Student Dashboard</h1>
                <div class="user-menu">
                    <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon blue">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div>
                            <div class="stat-label">My Route</div>
                            <div class="stat-value"><?php echo htmlspecialchars($student['route_name'] ?? 'Not Assigned'); ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-label">Fee Status</div>
                            <div class="stat-value"><?php echo $payment_summary['paid_count']; ?>/<?php echo $payment_summary['total_installments']; ?></div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo ($payment_summary['paid_count'] / max($payment_summary['total_installments'], 1)) * 100; ?>%"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon yellow">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="stat-label">Attendance (30 days)</div>
                            <div class="stat-value"><?php echo $attendance_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $attendance_rate; ?>%"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon <?php echo $student['fee_status'] === 'Paid' ? 'green' : 'red'; ?>">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="stat-label">Outstanding</div>
                            <div class="stat-value">PKR <?php echo number_format($payment_summary['total_amount'] - $payment_summary['total_paid'], 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Student Info -->
                <div class="content-card">
                    <h2><i class="fas fa-user-graduate"></i> Student Information</h2>
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['name']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Registration No</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['student_reg_no'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Route</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['route_name'] ?? 'Not Assigned'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bus Model</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['bus_model'] ?? 'Not Assigned'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bus Capacity</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['bus_capacity'] ?? 'N/A'); ?> seats</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Yearly Fee</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['yearly_fare'] ?? 'N/A'); ?></div>
                    </div>
                </div>

                <!-- Recent Attendance -->
                <div class="content-card">
                    <h2><i class="fas fa-clipboard-list"></i> Recent Attendance</h2>
                    <?php if ($attendance_result->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Marked By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($att = $attendance_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo date('d M Y', strtotime($att['date'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($att['status']); ?>">
                                                <?php echo $att['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($att['marked_by'] ?? 'System'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <p style="text-align: center; margin-top: 15px;">
                            <a href="attendance.php" style="color: #3182ce; text-decoration: none;">View All Attendance <i class="fas fa-arrow-right"></i></a>
                        </p>
                    <?php else: ?>
                        <p style="text-align: center; color: #718096; padding: 20px;">
                            <i class="fas fa-info-circle"></i> No attendance records found.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifications -->
            <div class="content-card" style="margin-top: 20px;">
                <h2><i class="fas fa-bell"></i> Recent Notifications</h2>
                <?php if ($notifications->num_rows > 0): ?>
                    <?php while ($notif = $notifications->fetch_assoc()): ?>
                        <div class="notification-item <?php echo $notif['type']; ?>">
                            <div class="notification-title">
                                <i class="fas fa-<?php 
                                    echo $notif['type'] === 'alert' ? 'exclamation-circle' : 
                                         ($notif['type'] === 'delay' ? 'clock' : 'info-circle'); 
                                ?>"></i>
                                <?php echo htmlspecialchars($notif['title']); ?>
                            </div>
                            <div><?php echo htmlspecialchars($notif['message']); ?></div>
                            <div class="notification-time">
                                <i class="fas fa-clock"></i> <?php echo date('d M Y, h:i A', strtotime($notif['created_at'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 20px;">
                        <i class="fas fa-check-circle"></i> No new notifications.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
