<?php
/**
 * All Payments - Admin
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('admin');

// Fetch all payments
$payments = $conn->query("SELECT p.*, s.name as student_name, s.student_reg_no, r.route_name
                          FROM payments p
                          JOIN students s ON p.student_id = s.student_id
                          LEFT JOIN routes r ON s.route_id = r.route_id
                          ORDER BY p.created_at DESC");

// Calculate totals
$stats = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) as paid,
    SUM(CASE WHEN status = 'Unpaid' THEN 1 ELSE 0 END) as unpaid,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as collected
FROM payments")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Payments - Admin</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-box { background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat-box i { font-size: 2rem; margin-bottom: 10px; color: #3182ce; }
        .stat-number { font-size: 1.5rem; font-weight: 700; color: #1a365d; }
        .stat-label { color: #718096; font-size: 0.9rem; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
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
                <li><a href="payments.php" class="active"><i class="fas fa-money-bill"></i> All Payments</a></li>
                <li><a href="complaints.php"><i class="fas fa-comment-alt"></i> Complaints</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-money-bill"></i> All Payments</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-file-invoice"></i>
                    <div class="stat-number"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Payments</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $stats['paid']; ?></div>
                    <div class="stat-label">Paid</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-clock"></i>
                    <div class="stat-number"><?php echo $stats['pending']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="stat-number">PKR <?php echo number_format($stats['collected'], 0); ?></div>
                    <div class="stat-label">Collected</div>
                </div>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> Payment Records</h2>
                <?php if ($payments->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Reg. Number</th>
                                <th>Route</th>
                                <th>Installment</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($payment = $payments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $payment['payment_id']; ?></td>
                                    <td><?php echo htmlspecialchars($payment['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['student_reg_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($payment['route_name'] ?? 'N/A'); ?></td>
                                    <td>#<?php echo $payment['installment_number']; ?></td>
                                    <td>PKR <?php echo number_format($payment['amount'], 0); ?></td>
                                    <td><?php echo date('d M Y', strtotime($payment['due_date'])); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($payment['status']); ?>"><?php echo $payment['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 40px;">No payment records found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
