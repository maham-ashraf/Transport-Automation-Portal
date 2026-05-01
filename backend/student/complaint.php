<?php
/**
 * Student Complaint Form
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('student');

$student_id = $_SESSION['student_id'];
$message = '';
$error = '';

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = sanitize($conn, $_POST['subject'] ?? '');
    $message_text = sanitize($conn, $_POST['message'] ?? '');
    
    if (empty($subject) || empty($message_text)) {
        $error = 'Please fill in all fields.';
    } else {
        $sql = "INSERT INTO complaints (student_id, subject, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $student_id, $subject, $message_text);
        
        if ($stmt->execute()) {
            $message = 'Your complaint has been submitted successfully. We will review it shortly.';
        } else {
            $error = 'Failed to submit complaint. Please try again.';
        }
        $stmt->close();
    }
}

// Fetch student's complaints history
$history_sql = "SELECT * FROM complaints WHERE student_id = ? ORDER BY created_at DESC";
$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $student_id);
$history_stmt->execute();
$complaints = $history_stmt->get_result();
$history_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Student Portal</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3182ce;
        }
        .btn-submit {
            padding: 12px 30px;
            background: #e53e3e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #c53030;
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
        .complaint-item {
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #3182ce;
        }
        .complaint-subject {
            font-weight: 600;
            color: #1a365d;
            margin-bottom: 8px;
        }
        .complaint-message {
            color: #4a5568;
            margin-bottom: 10px;
        }
        .complaint-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #718096;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-Open { background: #fed7d7; color: #c53030; }
        .status-In\sProgress { background: #fefcbf; color: #975a16; }
        .status-Resolved { background: #c6f6d5; color: #22543d; }
        .status-Closed { background: #e2e8f0; color: #4a5568; }
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
                <li><a href="route.php"><i class="fas fa-route"></i> My Route</a></li>
                <li><a href="attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                <li><a href="fees.php"><i class="fas fa-money-bill"></i> Fee Status</a></li>
                <li><a href="complaint.php" class="active"><i class="fas fa-comment-alt"></i> Complaint</a></li>
                <li><a href="feedback.php"><i class="fas fa-star"></i> Feedback</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-comment-alt"></i> Submit Complaint</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Complaint Form -->
            <div class="content-card">
                <h2><i class="fas fa-edit"></i> New Complaint</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="subject"><i class="fas fa-heading"></i> Subject *</label>
                        <input type="text" id="subject" name="subject" placeholder="Enter complaint subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment"></i> Message *</label>
                        <textarea id="message" name="message" rows="6" placeholder="Describe your complaint in detail..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Complaint
                    </button>
                </form>
            </div>

            <!-- Complaint History -->
            <div class="content-card">
                <h2><i class="fas fa-history"></i> Complaint History</h2>
                <?php if ($complaints->num_rows > 0): ?>
                    <?php while ($complaint = $complaints->fetch_assoc()): ?>
                        <div class="complaint-item">
                            <div class="complaint-subject"><?php echo htmlspecialchars($complaint['subject']); ?></div>
                            <div class="complaint-message"><?php echo nl2br(htmlspecialchars($complaint['message'])); ?></div>
                            <div class="complaint-meta">
                                <span><i class="fas fa-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($complaint['created_at'])); ?></span>
                                <span class="status-badge status-<?php echo str_replace(' ', '\\s', $complaint['status']); ?>">
                                    <?php echo $complaint['status']; ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 30px;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                        No complaints submitted yet.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
