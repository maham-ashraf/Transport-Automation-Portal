<?php
/**
 * Student Fees Status
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('student');

$student_id = $_SESSION['student_id'];

// Fetch all payments for the student
$sql = "SELECT * FROM payments WHERE student_id = ? ORDER BY installment_number";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$payments = $stmt->get_result();
$stmt->close();

// Calculate summary
$summary_sql = "SELECT 
    COUNT(*) as total_installments,
    SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) as paid_count,
    SUM(CASE WHEN status = 'Unpaid' THEN 1 ELSE 0 END) as unpaid_count,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_paid,
    SUM(amount) as total_amount
FROM payments WHERE student_id = ?";
$summary_stmt = $conn->prepare($summary_sql);
$summary_stmt->bind_param("i", $student_id);
$summary_stmt->execute();
$summary = $summary_stmt->get_result()->fetch_assoc();
$summary_stmt->close();

// Handle fee request submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $installment_id = intval($_POST['installment_id']);
    
    // Update payment status to pending/request for payment
    $update_sql = "UPDATE payments SET status = 'Pending' WHERE payment_id = ? AND student_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $installment_id, $student_id);
    
    if ($update_stmt->execute()) {
        $message = 'Payment request submitted successfully! Please visit the accounts office to complete your payment.';
    } else {
        $message = 'Failed to submit request. Please try again.';
    }
    $update_stmt->close();
}

// Fetch student route info for fee details
$student_sql = "SELECT s.*, r.yearly_fare FROM students s 
                LEFT JOIN routes r ON s.route_id = r.route_id 
                WHERE s.student_id = ?";
$student_stmt = $conn->prepare($student_sql);
$student_stmt->bind_param("i", $student_id);
$student_stmt->execute();
$student_info = $student_stmt->get_result()->fetch_assoc();
$student_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Status - Student Portal</title>
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
            grid-template-columns: repeat(4, 1fr);
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
        .stat-box.yellow i { color: #d69e2e; }
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a365d;
        }
        .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
        }
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
        .btn-action {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .btn-pay {
            background: #3182ce;
            color: white;
        }
        .btn-pay:hover {
            background: #2c5282;
        }
        .btn-paid {
            background: #c6f6d5;
            color: #22543d;
            cursor: default;
        }
        .progress-bar {
            height: 20px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #38a169 0%, #48bb78 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
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
        .summary-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a365d;
        }
        .summary-label {
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="route.php"><i class="fas fa-route"></i> My Route</a></li>
                <li><a href="attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                <li><a href="fees.php" class="active"><i class="fas fa-money-bill"></i> Fee Status</a></li>
                <li><a href="complaint.php"><i class="fas fa-comment-alt"></i> Complaint</a></li>
                <li><a href="feedback.php"><i class="fas fa-star"></i> Feedback</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-money-bill-wave"></i> Fee Status & Payment</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Fee Summary -->
            <div class="content-card">
                <h2><i class="fas fa-chart-pie"></i> Fee Summary</h2>
                <div class="summary-box">
                    <div class="summary-item">
                        <div class="summary-value">PKR <?php echo number_format($summary['total_amount'], 0); ?></div>
                        <div class="summary-label">Total Fee</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value">PKR <?php echo number_format($summary['total_paid'], 0); ?></div>
                        <div class="summary-label">Total Paid</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value">PKR <?php echo number_format($summary['total_amount'] - $summary['total_paid'], 0); ?></div>
                        <div class="summary-label">Outstanding</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value"><?php echo round(($summary['total_paid'] / max($summary['total_amount'], 1)) * 100, 1); ?>%</div>
                        <div class="summary-label">Paid Percentage</div>
                    </div>
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo ($summary['total_paid'] / max($summary['total_amount'], 1)) * 100; ?>%">
                        <?php echo round(($summary['total_paid'] / max($summary['total_amount'], 1)) * 100, 1); ?>% Paid
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-box blue">
                    <i class="fas fa-file-invoice"></i>
                    <div class="stat-number"><?php echo $summary['total_installments']; ?></div>
                    <div class="stat-label">Total Installments</div>
                </div>
                <div class="stat-box green">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $summary['paid_count']; ?></div>
                    <div class="stat-label">Paid</div>
                </div>
                <div class="stat-box yellow">
                    <i class="fas fa-clock"></i>
                    <div class="stat-number"><?php echo $summary['pending_count']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-box red">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="stat-number"><?php echo $summary['unpaid_count']; ?></div>
                    <div class="stat-label">Unpaid</div>
                </div>
            </div>

            <!-- Payment Schedule -->
            <div class="content-card">
                <h2><i class="fas fa-list-alt"></i> Payment Schedule</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Installment #</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($payment = $payments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $payment['installment_number']; ?></td>
                                <td>PKR <?php echo number_format($payment['amount'], 0); ?></td>
                                <td><?php echo date('d M Y', strtotime($payment['due_date'])); ?></td>
                                <td>
                                    <?php echo $payment['payment_date'] ? date('d M Y', strtotime($payment['payment_date'])) : '-'; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($payment['status']); ?>">
                                        <?php echo $payment['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($payment['status'] === 'Unpaid' || $payment['status'] === 'Pending'): ?>
                                        <form method="POST" action="" style="display: inline;">
                                            <input type="hidden" name="installment_id" value="<?php echo $payment['payment_id']; ?>">
                                            <button type="submit" name="submit_request" class="btn-action btn-pay">
                                                <i class="fas fa-paper-plane"></i> Submit Request
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn-action btn-paid" disabled>
                                            <i class="fas fa-check"></i> Paid
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Payment Instructions -->
            <div class="content-card">
                <h2><i class="fas fa-info-circle"></i> Payment Instructions</h2>
                <ul style="line-height: 2; color: #4a5568;">
                    <li><i class="fas fa-check" style="color: #38a169;"></i> Submit your payment request using the "Submit Request" button</li>
                    <li><i class="fas fa-check" style="color: #38a169;"></i> Visit the Accounts Office within 24 hours of submitting your request</li>
                    <li><i class="fas fa-check" style="color: #38a169;"></i> Bring your Student ID card for verification</li>
                    <li><i class="fas fa-check" style="color: #38a169;"></i> Payments can be made via Cash, Bank Transfer, or Online Banking</li>
                    <li><i class="fas fa-check" style="color: #38a169;"></i> Late payments may incur additional charges</li>
                    <li><i class="fas fa-check" style="color: #38a169;"></i> For queries, contact: <strong>+92-41-1234570</strong> or <strong>fees.transport@superior.edu.pk</strong></li>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>
