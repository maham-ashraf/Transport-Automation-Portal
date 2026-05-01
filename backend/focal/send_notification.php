<?php
/**
 * Send Notification - Focal Person
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('focal_person');

$focal_id = $_SESSION['focal_id'];
$user_id = $_SESSION['user_id'];
$route_id = $_SESSION['route_id'];
$message = '';
$error = '';

// Handle notification submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($conn, $_POST['title'] ?? '');
    $message_text = sanitize($conn, $_POST['message'] ?? '');
    $type = sanitize($conn, $_POST['type'] ?? 'info');
    
    if (empty($title) || empty($message_text)) {
        $error = 'Please fill in all required fields.';
    } else {
        $sql = "INSERT INTO notifications (title, message, type, route_id, created_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $message_text, $type, $route_id, $user_id);
        
        if ($stmt->execute()) {
            $message = 'Notification sent successfully to all students on your route!';
        } else {
            $error = 'Failed to send notification. Please try again.';
        }
        $stmt->close();
    }
}

// Fetch previous notifications
$history_sql = "SELECT * FROM notifications WHERE route_id = ? OR route_id IS NULL ORDER BY created_at DESC LIMIT 10";
$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $route_id);
$history_stmt->execute();
$notifications = $history_stmt->get_result();
$history_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification - Focal Person</title>
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
        .content-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-card h2 { margin: 0 0 20px; color: #1a365d; font-size: 1.3rem; display: flex; align-items: center; gap: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #3182ce; }
        .btn-submit { padding: 12px 30px; background: #3182ce; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #2c5282; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-error { background: #fed7d7; color: #c53030; border: 1px solid #fc8181; }
        .notification-item { padding: 15px; background: #f7fafc; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #3182ce; }
        .notification-item.alert { border-left-color: #e53e3e; }
        .notification-item.delay { border-left-color: #d69e2e; }
        .notification-title { font-weight: 600; color: #1a365d; margin-bottom: 5px; }
        .notification-time { font-size: 0.85rem; color: #718096; }
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
                <li><a href="attendance_history.php"><i class="fas fa-history"></i> Attendance History</a></li>
                <li><a href="unpaid_students.php"><i class="fas fa-exclamation-triangle"></i> Unpaid Students</a></li>
                <li><a href="send_notification.php" class="active"><i class="fas fa-bell"></i> Send Notification</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-bell"></i> Send Notification</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-edit"></i> New Notification</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="type"><i class="fas fa-tag"></i> Notification Type</label>
                        <select name="type" id="type" required>
                            <option value="info">General Info</option>
                            <option value="delay">Bus Delay</option>
                            <option value="alert">Important Alert</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title"><i class="fas fa-heading"></i> Title *</label>
                        <input type="text" name="title" id="title" placeholder="Enter notification title" required>
                    </div>
                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment"></i> Message *</label>
                        <textarea name="message" id="message" rows="5" placeholder="Enter your message..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Send Notification</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-history"></i> Recent Notifications</h2>
                <?php if ($notifications->num_rows > 0): ?>
                    <?php while ($notif = $notifications->fetch_assoc()): ?>
                        <div class="notification-item <?php echo $notif['type']; ?>">
                            <div class="notification-title">
                                <i class="fas fa-<?php echo $notif['type'] === 'alert' ? 'exclamation-circle' : ($notif['type'] === 'delay' ? 'clock' : 'info-circle'); ?>"></i>
                                <?php echo htmlspecialchars($notif['title']); ?>
                            </div>
                            <div><?php echo htmlspecialchars($notif['message']); ?></div>
                            <div class="notification-time"><i class="fas fa-clock"></i> <?php echo date('d M Y, h:i A', strtotime($notif['created_at'])); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 20px;">No notifications sent yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
