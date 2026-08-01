<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle adding habit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_habit'])) {
    $habit_name = sanitize($_POST['habit_name']);
    $created_date = date('Y-m-d');
    
    if (!empty($habit_name)) {
        $sql = "INSERT INTO habits (user_id, habit_name, created_date) VALUES ($user_id, '$habit_name', '$created_date')";
        $conn->query($sql);
        header('Location: dashboard.php');
        exit();
    }
}

// Handle deleting habit
if (isset($_GET['delete'])) {
    $habit_id = (int)$_GET['delete'];
    $sql = "DELETE FROM habits WHERE id = $habit_id AND user_id = $user_id";
    $conn->query($sql);
    header('Location: dashboard.php');
    exit();
}

// Handle toggling habit completion
if (isset($_GET['toggle']) && isset($_GET['date'])) {
    $habit_id = (int)$_GET['toggle'];
    $date = sanitize($_GET['date']);
    
    // Check if already completed
    $check_sql = "SELECT * FROM habit_completions WHERE habit_id = $habit_id AND completion_date = '$date'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Remove completion
        $sql = "DELETE FROM habit_completions WHERE habit_id = $habit_id AND completion_date = '$date'";
    } else {
        // Add completion
        $sql = "INSERT INTO habit_completions (habit_id, completion_date) VALUES ($habit_id, '$date')";
    }
    $conn->query($sql);
    header('Location: dashboard.php');
    exit();
}

// Get all habits for the user
$habits_sql = "SELECT * FROM habits WHERE user_id = $user_id ORDER BY created_date DESC";
$habits_result = $conn->query($habits_sql);

// Calculate stats
$total_habits = $habits_result->num_rows;

// Get today's completions
$today = date('Y-m-d');
$today_completions_sql = "SELECT COUNT(DISTINCT habit_id) as completed FROM habit_completions 
                          WHERE completion_date = '$today' AND habit_id IN (SELECT id FROM habits WHERE user_id = $user_id)";
$today_result = $conn->query($today_completions_sql);
$today_completed = $today_result->fetch_assoc()['completed'];

// Get total completions
$total_completions_sql = "SELECT COUNT(*) as total FROM habit_completions 
                          WHERE habit_id IN (SELECT id FROM habits WHERE user_id = $user_id)";
$total_result = $conn->query($total_completions_sql);
$total_completions = $total_result->fetch_assoc()['total'];

// Get current streak
$streak = 0;
$date = date('Y-m-d');
while (true) {
    $streak_sql = "SELECT COUNT(*) as count FROM habit_completions 
                   WHERE completion_date = '$date' AND habit_id IN (SELECT id FROM habits WHERE user_id = $user_id)";
    $streak_result = $conn->query($streak_sql);
    $streak_data = $streak_result->fetch_assoc();
    
    // Check if all habits completed on this date (at least one completion needed)
    if ($streak_data['count'] > 0 && $streak_data['count'] >= $total_habits) {
        $streak++;
        $date = date('Y-m-d', strtotime($date . ' -1 day'));
    } else {
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Habit Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <span class="brand-icon">✅</span>
                <span class="brand-text">Habit Tracker</span>
            </div>
            <div class="nav-links">
                <span class="welcome-text">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-info">
                    <h3><?php echo $total_habits; ?></h3>
                    <p>Total Habits</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?php echo $today_completed; ?>/<?php echo $total_habits; ?></h3>
                    <p>Today's Progress</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔥</div>
                <div class="stat-info">
                    <h3><?php echo $streak; ?></h3>
                    <p>Day Streak</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-info">
                    <h3><?php echo $total_completions; ?></h3>
                    <p>Total Completions</p>
                </div>
            </div>
        </div>

        <!-- Add Habit Form -->
        <div class="card">
            <div class="card-header">
                <h2>Add New Habit</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="" class="add-habit-form">
                    <input type="text" name="habit_name" placeholder="Enter habit name..." required>
                    <button type="submit" name="add_habit" class="btn btn-primary">Add Habit</button>
                </form>
            </div>
        </div>

        <!-- Habits List -->
        <div class="card">
            <div class="card-header">
                <h2>Your Habits</h2>
            </div>
            <div class="card-body">
                <?php if ($habits_result->num_rows > 0): ?>
                    <div class="habits-grid">
                        <?php while ($habit = $habits_result->fetch_assoc()): 
                            $habit_id = $habit['id'];
                            
                            // Get last 7 days
                            $week_days = [];
                            for ($i = 6; $i >= 0; $i--) {
                                $date = date('Y-m-d', strtotime("-$i days"));
                                $week_days[] = $date;
                            }
                            
                            // Get completions for this habit
                            $completions = [];
                            foreach ($week_days as $date) {
                                $check_sql = "SELECT * FROM habit_completions WHERE habit_id = $habit_id AND completion_date = '$date'";
                                $check_result = $conn->query($check_sql);
                                $completions[$date] = $check_result->num_rows > 0;
                            }
                            
                            // Calculate habit streak
                            $habit_streak = 0;
                            $date = date('Y-m-d');
                            while (true) {
                                $check_sql = "SELECT * FROM habit_completions WHERE habit_id = $habit_id AND completion_date = '$date'";
                                $check_result = $conn->query($check_sql);
                                if ($check_result->num_rows > 0) {
                                    $habit_streak++;
                                    $date = date('Y-m-d', strtotime($date . ' -1 day'));
                                } else {
                                    break;
                                }
                            }
                        ?>
                            <div class="habit-card">
                                <div class="habit-header">
                                    <h3><?php echo htmlspecialchars($habit['habit_name']); ?></h3>
                                    <div class="habit-actions">
                                        <a href="?delete=<?php echo $habit_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this habit?')">Delete</a>
                                    </div>
                                </div>
                                
                                <div class="habit-streak">
                                    <span>🔥 <?php echo $habit_streak; ?> day streak</span>
                                </div>
                                
                                <div class="habit-week">
                                    <?php foreach ($week_days as $date): 
                                        $is_today = $date === date('Y-m-d');
                                        $day_name = date('D', strtotime($date));
                                        $day_num = date('d', strtotime($date));
                                        $completed = $completions[$date];
                                    ?>
                                        <a href="?toggle=<?php echo $habit_id; ?>&date=<?php echo $date; ?>" class="day <?php echo $completed ? 'completed' : ''; ?> <?php echo $is_today ? 'today' : ''; ?>">
                                            <span class="day-name"><?php echo $day_name; ?></span>
                                            <span class="day-number"><?php echo $day_num; ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>You haven't created any habits yet. Start by adding one above!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>