<?php
/**
 * Student Transport Feedback
 * University Transport Automation Portal
 */

session_start();
require_once '../connection.php';
requireRole('student');

$student_id = $_SESSION['student_id'];
$message = '';
$error = '';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating'] ?? 0);
    $feedback_text = sanitize($conn, $_POST['feedback'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        $error = 'Please select a rating between 1 and 5 stars.';
    } elseif (empty($feedback_text)) {
        $error = 'Please provide your feedback.';
    } else {
        $sql = "INSERT INTO transport_ratings (student_id, rating, feedback) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $student_id, $rating, $feedback_text);
        
        if ($stmt->execute()) {
            $message = 'Thank you for your feedback! Your input helps us improve our transport services.';
        } else {
            $error = 'Failed to submit feedback. Please try again.';
        }
        $stmt->close();
    }
}

// Fetch student's previous feedback
$history_sql = "SELECT * FROM transport_ratings WHERE student_id = ? ORDER BY created_at DESC";
$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $student_id);
$history_stmt->execute();
$feedbacks = $history_stmt->get_result();
$history_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Feedback - Student Portal</title>
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
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-group textarea:focus {
            outline: none;
            border-color: #3182ce;
        }
        .btn-submit {
            padding: 12px 30px;
            background: #d69e2e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #b7791f;
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
        .rating-container {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }
        .rating-star {
            font-size: 2.5rem;
            color: #e2e8f0;
            cursor: pointer;
            transition: color 0.2s;
        }
        .rating-star:hover,
        .rating-star.active {
            color: #d69e2e;
        }
        .rating-labels {
            display: flex;
            justify-content: space-between;
            max-width: 300px;
            font-size: 0.85rem;
            color: #718096;
            margin-top: 5px;
        }
        .feedback-item {
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .feedback-rating {
            color: #d69e2e;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .feedback-text {
            color: #4a5568;
            margin-bottom: 10px;
        }
        .feedback-date {
            font-size: 0.85rem;
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
                <li><a href="fees.php"><i class="fas fa-money-bill"></i> Fee Status</a></li>
                <li><a href="complaint.php"><i class="fas fa-comment-alt"></i> Complaint</a></li>
                <li><a href="feedback.php" class="active"><i class="fas fa-star"></i> Feedback</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-star"></i> Transport Feedback</h1>
                <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Feedback Form -->
            <div class="content-card">
                <h2><i class="fas fa-edit"></i> Rate Your Transport Experience</h2>
                <form method="POST" action="" id="feedbackForm">
                    <div class="form-group">
                        <label><i class="fas fa-star"></i> How would you rate our transport service? *</label>
                        <div class="rating-container">
                            <i class="fas fa-star rating-star" data-rating="1"></i>
                            <i class="fas fa-star rating-star" data-rating="2"></i>
                            <i class="fas fa-star rating-star" data-rating="3"></i>
                            <i class="fas fa-star rating-star" data-rating="4"></i>
                            <i class="fas fa-star rating-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0">
                        <div class="rating-labels">
                            <span>Poor</span>
                            <span>Average</span>
                            <span>Excellent</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="feedback"><i class="fas fa-comment"></i> Your Feedback *</label>
                        <textarea id="feedback" name="feedback" rows="5" placeholder="Share your experience, suggestions, or concerns about the transport service..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Feedback
                    </button>
                </form>
            </div>

            <!-- Previous Feedback -->
            <div class="content-card">
                <h2><i class="fas fa-history"></i> Your Previous Feedback</h2>
                <?php if ($feedbacks->num_rows > 0): ?>
                    <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
                        <div class="feedback-item">
                            <div class="feedback-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $feedback['rating'] ? '' : '-empty'; ?>"></i>
                                <?php endfor; ?>
                                <span style="color: #718096; font-size: 0.9rem;">(<?php echo $feedback['rating']; ?>/5)</span>
                            </div>
                            <div class="feedback-text"><?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?></div>
                            <div class="feedback-date">
                                <i class="fas fa-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($feedback['created_at'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #718096; padding: 30px;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                        You haven't submitted any feedback yet.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Rating star interaction
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('ratingInput');
        
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.dataset.rating;
                ratingInput.value = rating;
                
                stars.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
            
            star.addEventListener('mouseenter', () => {
                const rating = star.dataset.rating;
                stars.forEach(s => {
                    if (s.dataset.rating <= rating) {
                        s.style.color = '#d69e2e';
                    } else {
                        s.style.color = '#e2e8f0';
                    }
                });
            });
        });
        
        document.querySelector('.rating-container').addEventListener('mouseleave', () => {
            const currentRating = ratingInput.value;
            stars.forEach(s => {
                if (s.dataset.rating <= currentRating) {
                    s.style.color = '#d69e2e';
                } else {
                    s.style.color = '#e2e8f0';
                }
            });
        });
        
        // Form validation
        document.getElementById('feedbackForm').addEventListener('submit', (e) => {
            if (ratingInput.value === '0') {
                e.preventDefault();
                alert('Please select a rating before submitting.');
            }
        });
    </script>
</body>
</html>
