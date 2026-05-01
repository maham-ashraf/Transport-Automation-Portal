<?php
/**
 * Mark Attendance - Focal Person
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('focal_person');

$focal_id = $_SESSION['focal_id'];
$route_id = $_SESSION['route_id'];
$message = '';
$error = '';

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_attendance'])) {
    $date = $_POST['attendance_date'] ?? date('Y-m-d');
    $attendance_data = $_POST['attendance'] ?? [];
    
    if (empty($attendance_data)) {
        $error = 'Please mark attendance for at least one student.';
    } else {
        $success_count = 0;
        
        foreach ($attendance_data as $student_id => $status) {
            // Check if attendance already marked for this date
            $check_sql = "SELECT attendance_id FROM attendance 
                         WHERE student_id = ? AND date = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("is", $student_id, $date);
            $check_stmt->execute();
            $existing = $check_stmt->get_result();
            
            if ($existing->num_rows > 0) {
                // Update existing record
                $update_sql = "UPDATE attendance SET status = ?, focal_id = ? 
                              WHERE student_id = ? AND date = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("siis", $status, $focal_id, $student_id, $date);
                if ($update_stmt->execute()) {
                    $success_count++;
                }
                $update_stmt->close();
            } else {
                // Insert new record
                $insert_sql = "INSERT INTO attendance (student_id, focal_id, date, status) 
                              VALUES (?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("iiss", $student_id, $focal_id, $date, $status);
                if ($insert_stmt->execute()) {
                    $success_count++;
                }
                $insert_stmt->close();
            }
            $check_stmt->close();
        }
        
        if ($success_count > 0) {
            $message = "Attendance marked successfully for {$success_count} student(s).";
        }
    }
}

// Get selected date (default today)
$selected_date = $_GET['date'] ?? date('Y-m-d');

// Fetch all students for this route with their attendance status for selected date
$students_sql = "SELECT s.*, 
                a.status as attendance_status,
                a.attendance_id
                FROM students s
                LEFT JOIN attendance a ON s.student_id = a.student_id AND a.date = ?
                WHERE s.route_id = ?
                ORDER BY s.name";
$students_stmt = $conn->prepare($students_sql);
$students_stmt->bind_param("si", $selected_date, $route_id);
$students_stmt->execute();
$students = $students_stmt->get_result();
$students_stmt->close();

// Count total students
$total_students = $students->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance - Focal Person</title>
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
        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }
        .date-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        .date-filter input[type="date"] {
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
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        .attendance-table th,
        .attendance-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .attendance-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
        }
        .attendance-radio {
            display: flex;
            gap: 20px;
        }
        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s;
        }
        .radio-label.present {
            background: #f0fff4;
            border: 2px solid #c6f6d5;
        }
        .radio-label.present:hover,
        .radio-label.present.active {
            background: #c6f6d5;
            border-color: #38a169;
        }
        .radio-label.absent {
            background: #fff5f5;
            border: 2px solid #fed7d7;
        }
        .radio-label.absent:hover,
        .radio-label.absent.active {
            background: #fed7d7;
            border-color: #e53e3e;
        }
        .radio-label input {
            display: none;
        }
        .radio-label i {
            font-size: 1.1rem;
        }
        .radio-label.present i { color: #38a169; }
        .radio-label.absent i { color: #e53e3e; }
        .btn-submit {
            padding: 15px 40px;
            background: #38a169;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background: #2f855a;
        }
        .stats-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }
        .stats-bar-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .stats-bar-item i {
            font-size: 1.2rem;
        }
        .stats-bar-item.present i { color: #38a169; }
        .stats-bar-item.absent i { color: #e53e3e; }
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
                <li><a href="mark_attendance.php" class="active"><i class="fas fa-clipboard-check"></i> Mark Attendance</a></li>
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
                <h1><i class="fas fa-clipboard-check"></i> Mark Attendance</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Date Filter -->
            <div class="content-card">
                <h2><i class="fas fa-calendar-alt"></i> Select Date</h2>
                <form method="GET" action="" class="date-filter">
                    <input type="date" name="date" value="<?php echo $selected_date; ?>" max="<?php echo date('Y-m-d'); ?>">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Load Students</button>
                </form>
            </div>

            <!-- Attendance Form -->
            <?php if ($students->num_rows > 0): ?>
                <div class="content-card">
                    <h2><i class="fas fa-users"></i> Student Attendance - <?php echo date('d M Y', strtotime($selected_date)); ?></h2>
                    
                    <div class="stats-bar">
                        <div class="stats-bar-item present">
                            <i class="fas fa-check-circle"></i>
                            <span id="present-count">0 Present</span>
                        </div>
                        <div class="stats-bar-item absent">
                            <i class="fas fa-times-circle"></i>
                            <span id="absent-count">0 Absent</span>
                        </div>
                        <div class="stats-bar-item">
                            <i class="fas fa-users"></i>
                            <span><?php echo $total_students; ?> Total</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="" id="attendanceForm">
                        <input type="hidden" name="attendance_date" value="<?php echo $selected_date; ?>">
                        
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Reg. Number</th>
                                    <th>Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 1;
                                while ($student = $students->fetch_assoc()): 
                                    $current_status = $student['attendance_status'] ?? '';
                                ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo $student['student_id']; ?></td>
                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['student_reg_no'] ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="attendance-radio">
                                                <label class="radio-label present <?php echo $current_status === 'Present' ? 'active' : ''; ?>">
                                                    <input type="radio" 
                                                           name="attendance[<?php echo $student['student_id']; ?>]" 
                                                           value="Present" 
                                                           <?php echo $current_status === 'Present' ? 'checked' : ''; ?>
                                                           onchange="updateCounts()">
                                                    <i class="fas fa-check-circle"></i> Present
                                                </label>
                                                <label class="radio-label absent <?php echo $current_status === 'Absent' ? 'active' : ''; ?>">
                                                    <input type="radio" 
                                                           name="attendance[<?php echo $student['student_id']; ?>]" 
                                                           value="Absent" 
                                                           <?php echo $current_status === 'Absent' ? 'checked' : ''; ?>
                                                           onchange="updateCounts()">
                                                    <i class="fas fa-times-circle"></i> Absent
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        
                        <div style="text-align: center;">
                            <button type="submit" name="mark_attendance" class="btn-submit">
                                <i class="fas fa-save"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="content-card">
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; color: #3182ce; margin-bottom: 15px;"></i>
                        <h3>No Students Found</h3>
                        <p style="color: #718096;">There are no students assigned to your route yet.</p>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function updateCounts() {
            const presentRadios = document.querySelectorAll('input[value="Present"]:checked');
            const absentRadios = document.querySelectorAll('input[value="Absent"]:checked');
            
            document.getElementById('present-count').textContent = presentRadios.length + ' Present';
            document.getElementById('absent-count').textContent = absentRadios.length + ' Absent';
            
            // Update label styles
            document.querySelectorAll('.radio-label').forEach(label => {
                label.classList.remove('active');
            });
            document.querySelectorAll('input:checked').forEach(radio => {
                radio.closest('.radio-label').classList.add('active');
            });
        }
        
        // Initialize counts
        updateCounts();
    </script>
</body>
</html>
