<?php
/**
 * Accounts Dashboard
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('accounts');

// Payment statistics
$stats_sql = "SELECT 
    COUNT(*) as total_payments,
    SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) as paid_count,
    SUM(CASE WHEN status = 'Unpaid' THEN 1 ELSE 0 END) as unpaid_count,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_collected,
    SUM(amount) as total_amount
FROM payments";
$stats = $conn->query($stats_sql)->fetch_assoc();

// Today's transactions
$today = date('Y-m-d');
$today_sql = "SELECT COUNT(*) as count, SUM(amount) as amount FROM payments WHERE payment_date = ? AND status = 'Paid'";
$today_stmt = $conn->prepare($today_sql);
$today_stmt->bind_param("s", $today);
$today_stmt->execute();
$today_stats = $today_stmt->get_result()->fetch_assoc();
$today_stmt->close();

// Recent payments
$recent_sql = "SELECT p.*, s.name as student_name, s.student_reg_no, r.route_name
               FROM payments p
               JOIN students s ON p.student_id = s.student_id
               LEFT JOIN routes r ON s.route_id = r.route_id
               WHERE p.status = 'Pending'
               ORDER BY p.created_at DESC
               LIMIT 10";
$recent_payments = $conn->query($recent_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Dashboard - University Transport Automation Portal</title>
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
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat-card-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .stat-icon.blue { background: #ebf8ff; color: #3182ce; }
        .stat-icon.green { background: #f0fff4; color: #38a169; }
        .stat-icon.yellow { background: #fffff0; color: #d69e2e; }
        .stat-icon.red { background: #fff5f5; color: #e53e3e; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #1a365d; }
        .stat-label { color: #718096; font-size: 0.9rem; }
        .content-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .content-card h2 { margin: 0 0 20px; color: #1a365d; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; color: #4a5568; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
        .quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .action-btn { padding: 15px 20px; background: #3182ce; color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 10px; font-weight: 600; }
        .action-btn:hover { background: #2c5282; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../download.jfif" alt="Logo">
                <h3>Accounts Staff</h3>
                <p>Accounts Portal</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="payments.php"><i class="fas fa-money-bill"></i> Process Payments</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Payment Reports</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-tachometer-alt"></i> Accounts Dashboard</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon blue"><i class="fas fa-file-invoice"></i></div>
                        <div>
                            <div class="stat-label">Total Payments</div>
                            <div class="stat-value"><?php echo $stats['total_payments']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-label">Paid</div>
                            <div class="stat-value"><?php echo $stats['paid_count']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-label">Pending</div>
                            <div class="stat-value"><?php echo $stats['pending_count']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
                        <div>
                            <div class="stat-label">Unpaid</div>
                            <div class="stat-value"><?php echo $stats['unpaid_count']; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="content-card">
                    <h2><i class="fas fa-money-bill-wave"></i> Financial Overview</h2>
                    <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                        <div style="width: 150px; color: #718096;">Total Expected</div>
                        <div style="flex: 1; color: #1a365d; font-weight: 600;">PKR <?php echo number_format($stats['total_amount'], 0); ?></div>
                    </div>
                    <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                        <div style="width: 150px; color: #718096;">Total Collected</div>
                        <div style="flex: 1; color: #38a169; font-weight: 600;">PKR <?php echo number_format($stats['total_collected'], 0); ?></div>
                    </div>
                    <div class="info-row" style="display: flex; padding: 12px 0;">
                        <div style="width: 150px; color: #718096;">Outstanding</div>
                        <div style="flex: 1; color: #e53e3e; font-weight: 600;">PKR <?php echo number_format($stats['total_amount'] - $stats['total_collected'], 0); ?></div>
                    </div>
                </div>

                <div class="content-card">
                    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    <div class="quick-actions">
                        <a href="payments.php" class="action-btn"><i class="fas fa-money-bill"></i> Process Payments</a>
                        <a href="reports.php" class="action-btn"><i class="fas fa-chart-bar"></i> View Reports</a>
                    </div>
                </div>
            </div>

            <div class="content-card" style="margin-top: 20px;">
                <h2><i class="fas fa-list"></i> Pending Payment Requests</h2>
                <?php if ($recent_payments->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Reg. Number</th>
                                <th>Route</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($payment = $recent_payments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($payment['route_name'] ?? 'N/A'); ?></td>
                                    <td>PKR <?php echo number_format($payment['amount'], 0); ?></td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <p style="text-align: center; margin-top: 15px;">
                        <a href="payments.php" style="color: #3182ce; text-decoration: none;">View All Payments <i class="fas fa-arrow-right"></i></a>
                    </p>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 20px;"><i class="fas fa-check-circle"></i> No pending payment requests.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
