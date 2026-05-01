<?php
/**
 * Payment Reports - Accounts
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('accounts');

// Get filter parameters
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Monthly summary
$summary_sql = "SELECT 
    COUNT(*) as total_transactions,
    SUM(CASE WHEN status = 'Paid' THEN 1 ELSE 0 END) as paid_count,
    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_collected
FROM payments 
WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ? AND status = 'Paid'";
$summary_stmt = $conn->prepare($summary_sql);
$summary_stmt->bind_param("ii", $month, $year);
$summary_stmt->execute();
$summary = $summary_stmt->get_result()->fetch_assoc();
$summary_stmt->close();

// Route-wise collection
$route_sql = "SELECT r.route_name, 
              COUNT(p.payment_id) as payments_count,
              SUM(CASE WHEN p.status = 'Paid' THEN p.amount ELSE 0 END) as total_collected
              FROM routes r
              LEFT JOIN students s ON r.route_id = s.route_id
              LEFT JOIN payments p ON s.student_id = p.student_id
              GROUP BY r.route_id, r.route_name
              ORDER BY total_collected DESC";
$route_stats = $conn->query($route_sql);

// Recent transactions
$transactions_sql = "SELECT p.*, s.name as student_name, r.route_name
                     FROM payments p
                     JOIN students s ON p.student_id = s.student_id
                     LEFT JOIN routes r ON s.route_id = r.route_id
                     WHERE p.status = 'Paid' AND MONTH(p.payment_date) = ? AND YEAR(p.payment_date) = ?
                     ORDER BY p.payment_date DESC
                     LIMIT 20";
$trans_stmt = $conn->prepare($transactions_sql);
$trans_stmt->bind_param("ii", $month, $year);
$trans_stmt->execute();
$transactions = $trans_stmt->get_result();
$trans_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports - Accounts</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-box { background: white; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat-box i { font-size: 2rem; margin-bottom: 10px; color: #3182ce; }
        .stat-number { font-size: 2rem; font-weight: 700; color: #1a365d; }
        .stat-label { color: #718096; font-size: 0.9rem; }
        .filter-bar { display: flex; gap: 15px; margin-bottom: 20px; }
        .filter-bar select { padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
        .btn-filter { padding: 10px 20px; background: #3182ce; color: white; border: none; border-radius: 8px; cursor: pointer; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f7fafc; font-weight: 600; color: #4a5568; }
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
                <li><a href="payments.php"><i class="fas fa-money-bill"></i> Process Payments</a></li>
                <li><a href="reports.php" class="active"><i class="fas fa-chart-bar"></i> Payment Reports</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-chart-bar"></i> Payment Reports</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-file-invoice"></i>
                    <div class="stat-number"><?php echo $summary['total_transactions']; ?></div>
                    <div class="stat-label">Transactions</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $summary['paid_count']; ?></div>
                    <div class="stat-label">Paid Installments</div>
                </div>
                <div class="stat-box">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="stat-number">PKR <?php echo number_format($summary['total_collected'], 0); ?></div>
                    <div class="stat-label">Total Collected</div>
                </div>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-filter"></i> Filter Reports</h2>
                <form method="GET" action="" class="filter-bar">
                    <select name="month">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $month ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="year">
                        <?php for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $year ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                </form>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-route"></i> Collection by Route</h2>
                <?php if ($route_stats->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Route</th>
                                <th>Payments</th>
                                <th>Total Collected</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($route = $route_stats->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($route['route_name'] ?? 'Unassigned'); ?></td>
                                    <td><?php echo $route['payments_count']; ?></td>
                                    <td>PKR <?php echo number_format($route['total_collected'] ?? 0, 0); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="content-card">
                <h2><i class="fas fa-list"></i> Recent Transactions</h2>
                <?php if ($transactions->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Route</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($trans = $transactions->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($trans['payment_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($trans['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($trans['route_name'] ?? 'N/A'); ?></td>
                                    <td>PKR <?php echo number_format($trans['amount'], 0); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 40px;">No transactions found for the selected period.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
