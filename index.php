<?php
require_once 'config.php';

// If user is logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker - Build Better Habits</title>
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
                <a href="index.php" class="active">Home</a>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Build Better Habits,<br>
                    <span class="highlight">One Day at a Time</span>
                </h1>
                <p class="hero-description">
                    Track your daily habits, maintain streaks, and transform your life
                    with our simple yet powerful habit tracking tool.
                </p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary btn-large">Start Free Trial</a>
                    <a href="login.php" class="btn btn-secondary btn-large">Login</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="feature-cards">
                    <div class="feature-card">
                        <span class="feature-icon">📊</span>
                        <h3>Track Progress</h3>
                        <p>Visualize your habit streaks</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🎯</span>
                        <h3>Stay Consistent</h3>
                        <p>Build daily routines</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🏆</span>
                        <h3>Earn Streaks</h3>
                        <p>Celebrate achievements</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Habit Tracker?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">📱</div>
                    <h3>Simple & Easy</h3>
                    <p>Intuitive interface makes tracking habits effortless</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📈</div>
                    <h3>Visual Progress</h3>
                    <p>See your improvement with clear visual indicators</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔥</div>
                    <h3>Streak Tracking</h3>
                    <p>Stay motivated with daily streak counters</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure & Private</h3>
                    <p>Your data is safe with encrypted storage</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Habit Tracker. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>