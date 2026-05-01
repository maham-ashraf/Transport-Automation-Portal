<?php
/**
 * All Students - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

// Handle cancel registration
if (isset($_GET['cancel'])) {
    $student_id = intval($_GET['cancel']);
    $sql = "DELETE FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
    header("Location: students.php");
    exit();
}

// Search functionality
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $sql = "SELECT s.*, r.route_name, 
            (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND status = 'Present') as present_count,
            (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id) as total_attendance
            FROM students s 
            LEFT JOIN routes r ON s.route_id = r.route_id
            WHERE s.name LIKE ? OR s.student_reg_no LIKE ?
            ORDER BY s.student_id DESC";
    $stmt = $conn->prepare($sql);
    $search_param = "%{$search}%";
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $sql = "SELECT s.*, r.route_name,
            (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id AND status = 'Present') as present_count,
            (SELECT COUNT(*) FROM attendance WHERE student_id = s.student_id) as total_attendance
            FROM students s 
            LEFT JOIN routes r ON s.route_id = r.route_id
            ORDER BY s.student_id DESC";
    $students = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students - Admin</title>
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
        .search-bar { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-bar input { flex: 1; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px; }
        .btn-search { padding: 10px 20px; background: #3182ce; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .btn-delete { padding: 6px 12px; background: #e53e3e; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .attendance-rate { display: inline-block; padding: 4px 10px; border-radius: 4px; font-weight: 600; }
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
                <li><a href="focals.php"><i class="fas fa-user-tie"></i> Focal Persons</a></li>
                <li><a href="students.php" class="active"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-users"></i> All Students</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-search"></i> Search Students</h2>
                <form method="GET" action="" class="search-bar">
                    <input type="text" name="search" placeholder="Search by name or registration number..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> Student List</h2>
                <?php if ($students->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Reg. Number</th>
                                <th>Route</th>
                                <th>Fee Status</th>
                                <th>Attendance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = $students->fetch_assoc()): 
                                $attendance_rate = $student['total_attendance'] > 0 
                                    ? round(($student['present_count'] / $student['total_attendance']) * 100, 1) 
                                    : 0;
                            ?>
                                <tr>
                                    <td><?php echo $student['student_id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($student['route_name'] ?? 'Not Assigned'); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($student['fee_status']); ?>"><?php echo $student['fee_status']; ?></span></td>
                                    <td><span class="attendance-rate"><?php echo $attendance_rate; ?>%</span></td>
                                    <td>
                                        <a href="?cancel=<?php echo $student['student_id']; ?>" class="btn-delete" onclick="return confirm('Cancel this registration?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 40px;">No students found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
