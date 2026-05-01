<?php
/**
 * Process Payments - Accounts
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('accounts');

$user_id = $_SESSION['user_id'];
$message = '';

// Handle payment status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $payment_id = intval($_POST['payment_id']);
    $status = sanitize($conn, $_POST['status']);
    $payment_date = ($status === 'Paid') ? date('Y-m-d') : null;
    
    $update_sql = "UPDATE payments SET status = ?, payment_date = ?, processed_by = ? WHERE payment_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssii", $status, $payment_date, $user_id, $payment_id);
    
    if ($update_stmt->execute()) {
        // Update student's overall fee status
        $student_sql = "SELECT student_id FROM payments WHERE payment_id = ?";
        $student_stmt = $conn->prepare($student_sql);
        $student_stmt->bind_param("i", $payment_id);
        $student_stmt->execute();
        $student_id = $student_stmt->get_result()->fetch_assoc()['student_id'];
        $student_stmt->close();
        
        // Check if all installments are paid
        $check_sql = "SELECT COUNT(*) as unpaid FROM payments WHERE student_id = ? AND status != 'Paid'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $student_id);
        $check_stmt->execute();
        $unpaid = $check_stmt->get_result()->fetch_assoc()['unpaid'];
        $check_stmt->close();
        
        $fee_status = ($unpaid == 0) ? 'Paid' : (($unpaid == 1 && $status === 'Paid') ? 'Paid' : 'Unpaid');
        
        $fee_update_sql = "UPDATE students SET fee_status = ?, last_payment_date = ? WHERE student_id = ?";
        $fee_update_stmt = $conn->prepare($fee_update_sql);
        $fee_update_stmt->bind_param("ssi", $fee_status, $payment_date, $student_id);
        $fee_update_stmt->execute();
        $fee_update_stmt->close();
        
        $message = 'Payment status updated successfully!';
    } else {
        $message = 'Failed to update payment status.';
    }
    $update_stmt->close();
}

// Fetch all payments with student info
$sql = "SELECT p.*, s.name as student_name, s.student_reg_no, s.fee_status, r.route_name
        FROM payments p
        JOIN students s ON p.student_id = s.student_id
        LEFT JOIN routes r ON s.route_id = r.route_id
        ORDER BY p.created_at DESC";
$payments = $conn->query($sql);

// Filter by status if specified
$filter = $_GET['filter'] ?? 'all';
if ($filter !== 'all') {
    $filter_sql = "SELECT p.*, s.name as student_name, s.student_reg_no, s.fee_status, r.route_name
                   FROM payments p
                   JOIN students s ON p.student_id = s.student_id
                   LEFT JOIN routes r ON s.route_id = r.route_id
                   WHERE p.status = ?
                   ORDER BY p.created_at DESC";
    $filter_stmt = $conn->prepare($filter_sql);
    $filter_stmt->bind_param("s", $filter);
    $filter_stmt->execute();
    $payments = $filter_stmt->get_result();
    $filter_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payments - Accounts</title>
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
        .content-card h2 { margin: 0 0 20px; color: #1a365d; font-size: 1.3rem; display: flex; align-items: center; gap: 10px; }
        .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; }
        .filter-btn { padding: 8px 16px; background: #e2e8f0; color: #4a5568; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; }
        .filter-btn.active { background: #3182ce; color: white; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; color: #4a5568; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .status-paid { background: #c6f6d5; color: #22543d; }
        .status-unpaid { background: #fed7d7; color: #c53030; }
        .status-pending { background: #fefcbf; color: #975a16; }
        .btn-action { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        .btn-approve { background: #38a169; color: white; }
        .btn-reject { background: #e53e3e; color: white; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; background: #c6f6d5; color: #22543d; }
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="payments.php" class="active"><i class="fas fa-money-bill"></i> Process Payments</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Payment Reports</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-money-bill"></i> Process Payments</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <h2><i class="fas fa-filter"></i> Filter by Status</h2>
                <div class="filter-bar">
                    <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
                    <a href="?filter=Pending" class="filter-btn <?php echo $filter === 'Pending' ? 'active' : ''; ?>">Pending</a>
                    <a href="?filter=Paid" class="filter-btn <?php echo $filter === 'Paid' ? 'active' : ''; ?>">Paid</a>
                    <a href="?filter=Unpaid" class="filter-btn <?php echo $filter === 'Unpaid' ? 'active' : ''; ?>">Unpaid</a>
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
                                <th>Action</th>
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
                                    <td>
                                        <?php if ($payment['status'] !== 'Paid'): ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                                                <input type="hidden" name="status" value="Paid">
                                                <button type="submit" name="update_payment" class="btn-action btn-approve"><i class="fas fa-check"></i> Mark Paid</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #38a169;"><i class="fas fa-check-circle"></i> Completed</span>
                                        <?php endif; ?>
                                    </td>
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
