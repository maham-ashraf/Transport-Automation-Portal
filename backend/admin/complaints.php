<?php
/**
 * Complaints Management - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

$message = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $status = sanitize($conn, $_POST['status']);
    
    $sql = "UPDATE complaints SET status = ? WHERE complaint_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $complaint_id);
    if ($stmt->execute()) {
        $message = 'Status updated successfully!';
    }
    $stmt->close();
}

// Fetch all complaints
$complaints = $conn->query("SELECT c.*, s.name as student_name, s.student_reg_no
                            FROM complaints c
                            LEFT JOIN students s ON c.student_id = s.student_id
                            ORDER BY c.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints - Admin</title>
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
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 15px; background: #c6f6d5; color: #22543d; }
        .complaint-item { padding: 20px; background: #f7fafc; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #3182ce; }
        .complaint-item.resolved { border-left-color: #38a169; }
        .complaint-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .complaint-subject { font-weight: 600; color: #1a365d; font-size: 1.1rem; }
        .complaint-message { color: #4a5568; margin-bottom: 15px; }
        .complaint-meta { display: flex; justify-content: space-between; align-items: center; }
        .complaint-student { font-size: 0.9rem; color: #718096; }
        .status-form { display: flex; gap: 10px; }
        .status-form select { padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 4px; }
        .btn-update { padding: 6px 15px; background: #3182ce; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-Open { background: #fed7d7; color: #c53030; }
        .status-InProgress { background: #fefcbf; color: #975a16; }
        .status-Resolved { background: #c6f6d5; color: #22543d; }
        .status-Closed { background: #e2e8f0; color: #4a5568; }
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
                <li><a href="students.php"><i class="fas fa-users"></i> All Students</a></li>
                <li><a href="payments.php"><i class="fas fa-money-bill"></i> All Payments</a></li>
                <li><a href="complaints.php" class="active"><i class="fas fa-comment-alt"></i> Complaints</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-comment-alt"></i> Complaints Management</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> All Complaints</h2>
                <?php if ($complaints->num_rows > 0): ?>
                    <?php while ($complaint = $complaints->fetch_assoc()): ?>
                        <div class="complaint-item <?php echo strtolower(str_replace(' ', '', $complaint['status'])); ?>">
                            <div class="complaint-header">
                                <div class="complaint-subject"><?php echo htmlspecialchars($complaint['subject']); ?></div>
                                <span class="status-badge status-<?php echo str_replace(' ', '', $complaint['status']); ?>"><?php echo $complaint['status']; ?></span>
                            </div>
                            <div class="complaint-message"><?php echo nl2br(htmlspecialchars($complaint['message'])); ?></div>
                            <div class="complaint-meta">
                                <div class="complaint-student">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($complaint['student_name'] ?? 'Unknown'); ?> 
                                    (<?php echo htmlspecialchars($complaint['student_reg_no'] ?? 'N/A'); ?>) | 
                                    <i class="fas fa-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($complaint['created_at'])); ?>
                                </div>
                                <form method="POST" action="" class="status-form">
                                    <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaint_id']; ?>">
                                    <select name="status">
                                        <option value="Open" <?php echo $complaint['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                                        <option value="In Progress" <?php echo $complaint['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="Resolved" <?php echo $complaint['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                        <option value="Closed" <?php echo $complaint['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update"><i class="fas fa-sync"></i> Update</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 40px;"><i class="fas fa-check-circle" style="font-size: 3rem; color: #38a169; margin-bottom: 15px; display: block;"></i>No complaints submitted yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
