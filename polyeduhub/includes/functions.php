<?php
/**
 * Common functions for PolyEduHub
 */

// Check if config file is included, if not include it
if (!defined('APP_NAME')) {
    require_once 'config.php';
}

/**
 * Sanitize input to prevent XSS
 * 
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 * 
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password using bcrypt
 * 
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password . SALT, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password against hash
 * 
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool True if match, false otherwise
 */
function verifyPassword($password, $hash) {
    return password_verify($password . SALT, $hash);
}

/**
 * Generate a random token
 * 
 * @param int $length Token length
 * @return string Random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is logged in
 * 
 * @return bool True if admin is logged in, false otherwise
 */
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Set flash message in session
 * 
 * @param string $type Message type (success, error, info, warning)
 * @param string $message Message content
 * @return void
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message from session
 * 
 * @return array|null Flash message or null if not set
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Display flash message HTML
 * 
 * @return string HTML for flash message or empty string if not set
 */
function displayFlashMessage() {
    $message = getFlashMessage();
    if ($message) {
        $type = $message['type'];
        $content = $message['message'];
        
        // Map message type to Bootstrap alert class
        $class = 'alert-info';
        if ($type === 'success') $class = 'alert-success';
        if ($type === 'error') $class = 'alert-danger';
        if ($type === 'warning') $class = 'alert-warning';
        
        return "<div class='alert $class alert-dismissible fade show' role='alert'>
                    $content
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    return '';
}

/**
 * Format date to a human-readable form
 * 
 * @param string $date Date string
 * @param string $format Output format
 * @return string Formatted date
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Truncate text to a specific length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $append String to append to truncated text
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= $append;
    }
    return $text;
}

/**
 * Get file extension
 * 
 * @param string $filename Filename
 * @return string File extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Check if file extension is allowed
 * 
 * @param string $filename Filename
 * @return bool True if allowed, false otherwise
 */
function isAllowedExtension($filename) {
    $extension = getFileExtension($filename);
    return in_array($extension, ALLOWED_EXTENSIONS);
}

/**
 * Format file size to human-readable format
 * 
 * @param int $bytes File size in bytes
 * @param int $precision Number of decimal places
 * @return string Formatted file size
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Get user profile picture URL
 * 
 * @param string|null $profile_image Profile image filename
 * @return string URL to profile image or default image
 */
function getProfileImageUrl($profile_image = null) {
    if ($profile_image && file_exists(dirname(__DIR__) . '/uploads/profile/' . $profile_image)) {
        return '../uploads/profile/' . $profile_image;
    }
    return '../assets/img/ui/default-profile.png';
}

/**
 * Get resource icon based on file extension
 * 
 * @param string $filename Filename
 * @return string FontAwesome icon class
 */
function getResourceIcon($filename) {
    $extension = getFileExtension($filename);
    
    switch ($extension) {
        case 'pdf':
            return 'fa-file-pdf';
        case 'doc':
        case 'docx':
            return 'fa-file-word';
        case 'xls':
        case 'xlsx':
            return 'fa-file-excel';
        case 'ppt':
        case 'pptx':
            return 'fa-file-powerpoint';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'fa-file-image';
        case 'zip':
        case 'rar':
            return 'fa-file-archive';
        default:
            return 'fa-file';
    }
}

/**
 * Calculate user level based on points
 * 
 * @param int $points User points
 * @return array Level information (level, name, next_level, points_needed)
 */
function calculateUserLevel($points) {
    $levels = [
        ['min_points' => 0, 'name' => 'Beginner'],
        ['min_points' => 100, 'name' => 'Bronze Contributor'],
        ['min_points' => 250, 'name' => 'Silver Contributor'],
        ['min_points' => 500, 'name' => 'Gold Contributor'],
        ['min_points' => 1000, 'name' => 'Platinum Contributor'],
        ['min_points' => 2500, 'name' => 'Diamond Contributor'],
        ['min_points' => 5000, 'name' => 'Master Contributor'],
    ];
    
    $current_level = 0;
    $level_name = $levels[0]['name'];
    
    // Find current level
    for ($i = count($levels) - 1; $i >= 0; $i--) {
        if ($points >= $levels[$i]['min_points']) {
            $current_level = $i;
            $level_name = $levels[$i]['name'];
            break;
        }
    }
    
    // Calculate next level and points needed
    $next_level = $current_level + 1;
    $points_needed = 0;
    
    if ($next_level < count($levels)) {
        $points_needed = $levels[$next_level]['min_points'] - $points;
        $next_level_name = $levels[$next_level]['name'];
    } else {
        $next_level_name = 'Maximum Level Reached';
    }
    
    return [
        'level' => $current_level + 1,
        'name' => $level_name,
        'next_level' => $next_level_name,
        'points_needed' => $points_needed
    ];
}

/**
 * Add user activity
 * 
 * @param int $user_id User ID
 * @param string $activity_type Activity type (upload, download, comment, etc.)
 * @param string $description Activity description
 * @param int|null $resource_id Related resource ID (if applicable)
 * @return int Activity ID
 */
function addUserActivity($user_id, $activity_type, $description, $resource_id = null) {
    $data = [
        'user_id' => $user_id,
        'activity_type' => $activity_type,
        'description' => $description,
        'resource_id' => $resource_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return dbInsert('user_activities', $data);
}

/**
 * Add user points
 * 
 * @param int $user_id User ID
 * @param int $points Points to add
 * @param string $reason Reason for adding points
 * @return int Points transaction ID
 */
function addUserPoints($user_id, $points, $reason) {
    $data = [
        'user_id' => $user_id,
        'points' => $points,
        'reason' => $reason,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Insert points transaction
    $transaction_id = dbInsert('points_transactions', $data);
    
    // Update user total points
    dbExecute("UPDATE users SET points = points + ? WHERE id = ?", [$points, $user_id]);
    
    return $transaction_id;
}

/**
 * Check if user has already downloaded a resource
 * 
 * @param int $user_id User ID
 * @param int $resource_id Resource ID
 * @return bool True if already downloaded, false otherwise
 */
function hasDownloadedResource($user_id, $resource_id) {
    $result = dbSelectOne("SELECT id FROM downloads WHERE user_id = ? AND resource_id = ?", [$user_id, $resource_id]);
    return $result !== false;
}

/**
 * Add download record
 * 
 * @param int $user_id User ID
 * @param int $resource_id Resource ID
 * @return int Download ID
 */
function addDownloadRecord($user_id, $resource_id) {
    $data = [
        'user_id' => $user_id,
        'resource_id' => $resource_id,
        'downloaded_at' => date('Y-m-d H:i:s')
    ];
    
    return dbInsert('downloads', $data);
}

/**
 * Increment resource download count
 * 
 * @param int $resource_id Resource ID
 * @return void
 */
function incrementResourceDownloads($resource_id) {
    dbExecute("UPDATE resources SET downloads = downloads + 1 WHERE id = ?", [$resource_id]);
}

/**
 * Get recent user activities
 * 
 * @param int $user_id User ID
 * @param int $limit Maximum number of activities to return
 * @return array Recent activities
 */
function getRecentActivities($user_id, $limit = 5) {
    return dbSelect(
        "SELECT * FROM user_activities 
         WHERE user_id = ? 
         ORDER BY created_at DESC 
         LIMIT ?",
        [$user_id, $limit]
    );
}

/**
 * Get recommended resources for user
 * 
 * @param int $user_id User ID
 * @param int $limit Maximum number of resources to return
 * @return array Recommended resources
 */
function getRecommendedResources($user_id, $limit = 3) {
    // This is a simplified version. In a real application, 
    // you would implement a recommendation algorithm based on user preferences,
    // department, previous downloads, etc.
    
    return dbSelect(
        "SELECT r.*, u.name as uploaded_by, 
                (SELECT AVG(rating) FROM resource_ratings WHERE resource_id = r.id) as rating
         FROM resources r
         JOIN users u ON r.user_id = u.id
         WHERE r.status = 'approved'
         ORDER BY r.created_at DESC
         LIMIT ?",
        [$limit]
    );
}

/**
 * Check if user is eligible for a new badge
 * 
 * @param int $user_id User ID
 * @return array|null New badge information or null if not eligible
 */
function checkForNewBadge($user_id) {
    // Get user points
    $user = dbSelectOne("SELECT points FROM users WHERE id = ?", [$user_id]);
    
    if (!$user) {
        return null;
    }
    
    $points = $user['points'];
    $level = calculateUserLevel($points);
    
    // Check if user already has the badge
    $existing_badge = dbSelectOne(
        "SELECT * FROM user_badges WHERE user_id = ? AND badge_name = ?", 
        [$user_id, $level['name']]
    );
    
    if (!$existing_badge && $level['level'] > 1) {
        // User is eligible for a new badge
        $badge_data = [
            'user_id' => $user_id,
            'badge_name' => $level['name'],
            'earned_at' => date('Y-m-d H:i:s')
        ];
        
        dbInsert('user_badges', $badge_data);
        
        return [
            'badge_name' => $level['name'],
            'level' => $level['level']
        ];
    }
    
    return null;
}

function awardPoints($user_id, $points, $action, $description) {
    // Record points history
    dbInsert('points_history', [
        'user_id' => $user_id,
        'points' => $points,
        'action' => $action,
        'description' => $description
    ]);

    // Update total user points
    $updateStmt = "UPDATE user_points SET points = points + ? WHERE user_id = ?";
    dbExecute($updateStmt, [$points, $user_id]);
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}