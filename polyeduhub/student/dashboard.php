<?php
// Include configuration and database connection
require_once '../includes/config.php';
require_once '../includes/db-connection.php';
require_once '../includes/functions.php';

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
// In a real application, you would fetch user data from the database
// $user = dbSelectOne("SELECT * FROM users WHERE id = ?", [$user_id]);

// For demonstration, we'll use placeholder data
$user = [
    'id' => $user_id,
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'department' => 'Information Technology',
    'points' => 250,
    'badge' => 'Gold Contributor',
    'profile_image' => '../assets/img/ui/default-profile.png'
];

// Placeholder data for dashboard statistics
$stats = [
    'uploaded_resources' => 12,
    'downloaded_resources' => 45,
    'points_earned' => 250,
    'rank' => 5
];

// Recent activities placeholder data
$recent_activities = [
    [
        'type' => 'upload',
        'title' => 'Database Systems Notes',
        'timestamp' => '2025-02-25 14:30:00',
        'icon' => 'fa-file-upload'
    ],
    [
        'type' => 'download',
        'title' => 'Java Programming Assignment',
        'timestamp' => '2025-02-24 10:15:00',
        'icon' => 'fa-file-download'
    ],
    [
        'type' => 'comment',
        'title' => 'Comment on Web Development Guide',
        'timestamp' => '2025-02-23 16:45:00',
        'icon' => 'fa-comment'
    ],
    [
        'type' => 'badge',
        'title' => 'Earned Gold Contributor Badge',
        'timestamp' => '2025-02-22 09:30:00',
        'icon' => 'fa-award'
    ]
];

// Recommended resources placeholder data
$recommended_resources = [
    [
        'id' => 1,
        'title' => 'Advanced Database Management',
        'category' => 'Notes',
        'uploaded_by' => 'Prof. Smith',
        'rating' => 4.8,
        'downloads' => 125
    ],
    [
        'id' => 2,
        'title' => 'Network Security Fundamentals',
        'category' => 'Notes',
        'uploaded_by' => 'Dr. Johnson',
        'rating' => 4.5,
        'downloads' => 98
    ],
    [
        'id' => 3,
        'title' => 'Web Development Project',
        'category' => 'Assignment',
        'uploaded_by' => 'Mr. Williams',
        'rating' => 4.7,
        'downloads' => 112
    ]
];

// Page title
$page_title = "Student Dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $page_title ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="sidebar-brand-text mx-3">PolyEduHub</div>
        </div>
        
        <hr class="sidebar-divider">
        
        <div class="sidebar-heading">
            Navigation
        </div>
        
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">
                Resources
            </div>
            
            <li class="nav-item">
                <a class="nav-link" href="resources/index.php">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Browse Resources</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="resources/upload.php">
                    <i class="fas fa-fw fa-file-upload"></i>
                    <span>Upload Resource</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="resources/my-resources.php">
                    <i class="fas fa-fw fa-list"></i>
                    <span>My Resources</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="resources/favorites.php">
                    <i class="fas fa-fw fa-star"></i>
                    <span>Favorites</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">
                Community
            </div>
            
            <li class="nav-item">
                <a class="nav-link" href="chat/index.php">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Chat Rooms</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="leaderboard/index.php">
                    <i class="fas fa-fw fa-trophy"></i>
                    <span>Leaderboard</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">
                Account
            </div>
            
            <li class="nav-item">
                <a class="nav-link" href="profile/index.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="profile/badges.php">
                    <i class="fas fa-fw fa-award"></i>
                    <span>My Badges</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="notifications/index.php">
                    <i class="fas fa-fw fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <button class="btn toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Search -->
                <form class="navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for resources, notes, assignments..." aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <div class="navbar-nav ms-auto">
                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="notification-counter">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            <li><h6 class="dropdown-header">Notifications Center</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">February 25, 2025</div>
                                    <span>Your resource "Database Systems Notes" has been approved!</span>
                                </div>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-success text-white">
                                        <i class="fas fa-award"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">February 22, 2025</div>
                                    <span>Congratulations! You've earned the Gold Contributor badge!</span>
                                </div>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-warning text-white">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">February 20, 2025</div>
                                    <span>Jane Smith commented on your resource "Java Programming Assignment"</span>
                                </div>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center small text-muted" href="notifications/index.php">Show All Notifications</a></li>
                        </ul>
                    </div>
                    
                    <!-- User Information -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-info" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-lg-inline text-gray-600 small me-2"><?= htmlspecialchars($user['name']) ?></span>
                            <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile/index.php"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="profile/edit.php"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings</a></li>
                            <li><a class="dropdown-item" href="profile/badges.php"><i class="fas fa-award fa-sm fa-fw me-2 text-gray-400"></i> Badges</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a href="resources/upload.php" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                    <i class="fas fa-upload fa-sm text-white-50 me-1"></i> Upload New Resource
                </a>
            </div>
            
            <!-- Stats Cards Row -->
            <div class="row">
                <!-- Uploaded Resources Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card stat-card-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="stat-label">Uploaded Resources</div>
                                    <div class="stat-value"><?= $stats['uploaded_resources'] ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-upload stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Downloaded Resources Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card stat-card-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="stat-label">Downloaded Resources</div>
                                    <div class="stat-value"><?= $stats['downloaded_resources'] ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-download stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Points Earned Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card stat-card-info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="stat-label">Points Earned</div>
                                    <div class="stat-value"><?= $stats['points_earned'] ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Leaderboard Rank Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card stat-card-warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="stat-label">Leaderboard Rank</div>
                                    <div class="stat-value">#<?= $stats['rank'] ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-trophy stat-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Recent Activities</h6>
                            <a href="#" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php foreach ($recent_activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas <?= $activity['icon'] ?> fa-lg"></i>
                                </div>
                                <div class="activity-content">
                                    <h6><?= htmlspecialchars($activity['title']) ?></h6>
                                    <div class="activity-time">
                                        <i class="far fa-clock me-1"></i>
                                        <?= date('M d, Y \a\t h:i A', strtotime($activity['timestamp'])) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Recommended Resources -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Recommended Resources</h6>
                            <a href="resources/index.php" class="btn btn-sm btn-primary">Browse All</a>
                        </div>
                        <div class="card-body">
                            <?php foreach ($recommended_resources as $resource): ?>
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fas fa-file-alt fa-lg"></i>
                                </div>
                                <div class="resource-details">
                                    <h6><?= htmlspecialchars($resource['title']) ?></h6>
                                    <div class="resource-meta">
                                        <span><i class="fas fa-folder"></i> <?= htmlspecialchars($resource['category']) ?></span>
                                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($resource['uploaded_by']) ?></span>
                                        <span><i class="fas fa-star"></i> <?= $resource['rating'] ?></span>
                                        <span><i class="fas fa-download"></i> <?= $resource['downloads'] ?></span>
                                    </div>
                                </div>
                                <div class="resource-actions">
                                    <a href="resources/download.php?id=<?= $resource['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Badge Card -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Your Achievements</h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-award fa-5x text-warning mb-3"></i>
                                        <h4><?= htmlspecialchars($user['badge']) ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="mb-4">You're making great progress! Keep sharing resources to reach the Platinum Contributor badge. You need 50 more points to reach the next level.</p>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-file-upload text-primary mb-2"></i>
                                                    <h6>Upload 3 more resources</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-comment text-primary mb-2"></i>
                                                    <h6>Comment on 5 resources</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-thumbs-up text-primary mb-2"></i>
                                                    <h6>Rate 10 resources</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Upcoming Events</h6>
                            <a href="#" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Final Project Submission</td>
                                            <td>March 15, 2025</td>
                                            <td>Information Technology</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Details</a>
                                                <a href="#" class="btn btn-sm btn-success">Add to Calendar</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Python Workshop</td>
                                            <td>March 10, 2025</td>
                                            <td>Computer Science</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Details</a>
                                                <a href="#" class="btn btn-sm btn-success">Add to Calendar</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Career Fair</td>
                                            <td>March 20, 2025</td>
                                            <td>All Departments</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Details</a>
                                                <a href="#" class="btn btn-sm btn-success">Add to Calendar</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Footer -->
        <footer class="sticky-footer bg-white mt-4">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; <?= date('Y') ?> PolyEduHub. All rights reserved.</span>
                </div>
            </div>
        </footer>
        
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/scripts.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.content').classList.toggle('pushed');
        });
        
        // Automatically close sidebar when clicking on a link (mobile only)
        if (window.innerWidth < 768) {
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.remove('show');
                    document.querySelector('.content').classList.remove('pushed');
                });
            });
        }
    </script>
</body>
</html>