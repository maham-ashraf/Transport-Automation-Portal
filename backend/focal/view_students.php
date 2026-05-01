<?php
/**
 * View Students - Focal Person
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('focal_person');

$focal_id = $_SESSION['focal_id'];
$route_id = $_SESSION['route_id'];

// Fetch all students for this route with payment info
$sql = "SELECT s.*, 
        (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND status = 'Present') as present_count,
        (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id) as total_attendance,
        (SELECT status FROM payments WHERE student_id = s.student_id ORDER BY due_date DESC LIMIT 1) as latest_payment_status
        FROM students s
        WHERE s.route_id = ?
        ORDER BY s.name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$students = $stmt->get_result();
$stmt->close();

// Search functionality
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $search_sql = "SELECT s.*, 
        (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND status = 'Present') as present_count,
        (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id) as total_attendance,
        (SELECT status FROM payments WHERE student_id = s.student_id ORDER BY due_date DESC LIMIT 1) as latest_payment_status
        FROM students s
        WHERE s.route_id = ? AND (s.name LIKE ? OR s.student_reg_no LIKE ?)
        ORDER BY s.name";
    $search_stmt = $conn->prepare($search_sql);
    $search_param = "%{$search}%";
    $search_stmt->bind_param("iss", $route_id, $search_param, $search_param);
    $search_stmt->execute();
    $students = $search_stmt->get_result();
    $search_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Focal Person</title>
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
        .search-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .search-bar input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-search {
            padding: 12px 25px;
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
        .data-table tr:hover {
            background: #f7fafc;
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
        .attendance-rate {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
        }
        .attendance-high { background: #c6f6d5; color: #22543d; }
        .attendance-medium { background: #fefcbf; color: #975a16; }
        .attendance-low { background: #fed7d7; color: #c53030; }
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="mark_attendance.php"><i class="fas fa-clipboard-check"></i> Mark Attendance</a></li>
                <li><a href="view_students.php" class="active"><i class="fas fa-users"></i> View Students</a></li>
                <li><a href="attendance_history.php"><i class="fas fa-history"></i> Attendance History</a></li>
                <li><a href="unpaid_students.php"><i class="fas fa-exclamation-triangle"></i> Unpaid Students</a></li>
                <li><a href="send_notification.php"><i class="fas fa-bell"></i> Send Notification</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-users"></i> View Students</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <!-- Search -->
            <div class="content-card">
                <h2><i class="fas fa-search"></i> Search Students</h2>
                <form method="GET" action="" class="search-bar">
                    <input type="text" name="search" placeholder="Search by name or registration number..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <!-- Students List -->
            <div class="content-card">
                <h2><i class="fas fa-list"></i> Students on Your Route</h2>
                <?php if ($students->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Reg. Number</th>
                                <th>Attendance Rate</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $counter = 1;
                            while ($student = $students->fetch_assoc()): 
                                $attendance_rate = $student['total_attendance'] > 0 
                                    ? round(($student['present_count'] / $student['total_attendance']) * 100, 1) 
                                    : 0;
                                $rate_class = $attendance_rate >= 80 ? 'high' : ($attendance_rate >= 60 ? 'medium' : 'low');
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo $student['student_id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="attendance-rate attendance-<?php echo $rate_class; ?>">
                                            <?php echo $attendance_rate; ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($student['latest_payment_status'] ?? 'unpaid'); ?>">
                                            <?php echo $student['latest_payment_status'] ?? 'Unpaid'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; color: #3182ce; margin-bottom: 15px;"></i>
                        <h3>No Students Found</h3>
                        <p style="color: #718096;">
                            <?php echo empty($search) ? 'There are no students assigned to your route yet.' : 'No students match your search criteria.'; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
