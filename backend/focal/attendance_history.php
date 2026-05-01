<?php
/**
 * Attendance History - Focal Person
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('focal_person');

$focal_id = $_SESSION['focal_id'];
$route_id = $_SESSION['route_id'];

// Get filter parameters
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Fetch attendance records for students on this route
$sql = "SELECT a.*, s.name as student_name, s.student_reg_no
        FROM attendance a
        JOIN students s ON a.student_id = s.student_id
        WHERE s.route_id = ? AND MONTH(a.date) = ? AND YEAR(a.date) = ?
        ORDER BY a.date DESC, s.name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $route_id, $month, $year);
$stmt->execute();
$attendance = $stmt->get_result();
$stmt->close();

// Calculate statistics
$stats_sql = "SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) as present_count,
    SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) as absent_count
FROM attendance a
JOIN students s ON a.student_id = s.student_id
WHERE s.route_id = ? AND MONTH(a.date) = ? AND YEAR(a.date) = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("iii", $route_id, $month, $year);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();
$stats_stmt->close();

$present_rate = $stats['total_records'] > 0 
    ? round(($stats['present_count'] / $stats['total_records']) * 100, 1) 
    : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance History - Focal Person</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-box i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-box.blue i { color: #3182ce; }
        .stat-box.green i { color: #38a169; }
        .stat-box.red i { color: #e53e3e; }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1a365d;
        }
        .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }
        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-bar select {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-filter {
            padding: 10px 20px;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
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
        .status-present { background: #c6f6d5; color: #22543d; }
        .status-absent { background: #fed7d7; color: #c53030; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../download.jfif" alt="Logo">
                <h3><?php echo htmlspecialchars($_SESSION['focal_name']); ?></h3>
                <p>Focal Person Portal</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="mark_attendance.php"><i class="fas fa-clipboard-check"></i> Mark Attendance</a></li>
                <li><a href="view_students.php"><i class="fas fa-users"></i> View Students</a></li>
                <li><a href="attendance_history.php" class="active"><i class="fas fa-history"></i> Attendance History</a></li>
                <li><a href="unpaid_students.php"><i class="fas fa-exclamation-triangle"></i> Unpaid Students</a></li>
                <li><a href="send_notification.php"><i class="fas fa-bell"></i> Send Notification</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-history"></i> Attendance History</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="stats-grid">
                <div class="stat-box blue">
                    <i class="fas fa-clipboard-list"></i>
                    <div class="stat-number"><?php echo $stats['total_records']; ?></div>
                    <div class="stat-label">Total Records</div>
                </div>
                <div class="stat-box green">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $stats['present_count']; ?></div>
                    <div class="stat-label">Present</div>
                </div>
                <div class="stat-box red">
                    <i class="fas fa-times-circle"></i>
                    <div class="stat-number"><?php echo $stats['absent_count']; ?></div>
                    <div class="stat-label">Absent</div>
                </div>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-filter"></i> Filter Records</h2>
                <form method="GET" action="" class="filter-bar">
                    <select name="month">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $month ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <select name="year">
                        <?php for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $year ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> Attendance Records - <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
                <?php if ($attendance->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Reg. Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($record = $attendance->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($record['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($record['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($record['status']); ?>">
                                            <i class="fas fa-<?php echo $record['status'] === 'Present' ? 'check' : 'times'; ?>"></i>
                                            <?php echo $record['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; color: #3182ce; margin-bottom: 15px;"></i>
                        <h3>No Records Found</h3>
                        <p style="color: #718096;">No attendance records found for the selected period.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
