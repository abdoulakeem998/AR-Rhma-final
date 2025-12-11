<?php
// ============================================
// HELPER FUNCTIONS FOR AR-RAHMA WEBSITE
// ============================================

// Authentication Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /ar-rahma-website/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: /ar-rahma-website/admin/login.php');
        exit;
    }
}

// Input Sanitization
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Validation Functions
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPhone($phone) {
    return preg_match('/^[0-9+\-\s()]+$/', $phone);
}

// Password Functions
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// File Upload Function
function uploadFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
    $response = ['success' => false, 'message' => '', 'filename' => ''];
    
    if (!isset($file['error']) || is_array($file['error'])) {
        $response['message'] = 'Invalid file upload';
        return $response;
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Upload error occurred';
        return $response;
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedTypes)) {
        $response['message'] = 'File type not allowed. Allowed: ' . implode(', ', $allowedTypes);
        return $response;
    }
    
    if ($file['size'] > 5242880) { // 5MB
        $response['message'] = 'File too large (max 5MB)';
        return $response;
    }
    
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $newFilename = uniqid() . '_' . time() . '.' . $fileExtension;
    $targetPath = $targetDir . '/' . $newFilename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $response['success'] = true;
        $response['filename'] = $newFilename;
        $response['path'] = $targetPath;
        $response['message'] = 'File uploaded successfully';
    } else {
        $response['message'] = 'Failed to move uploaded file';
    }
    
    return $response;
}

// Date/Time Functions
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) return 'just now';
    elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    }
    elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    }
    elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
    return date('M j, Y', $timestamp);
}

// Text Functions
function truncateText($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// Database Functions
function getSiteSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

function updateSiteSetting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        return $stmt->execute([$value, $key]);
    } catch (PDOException $e) {
        return false;
    }
}

function logAdminActivity($admin_id, $action, $entity_type = null, $entity_id = null, $description = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs (admin_id, action, entity_type, entity_id, description, ip_address) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $admin_id,
            $action,
            $entity_type,
            $entity_id,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Flash Message Functions
function setFlashMessage($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'type' => $_SESSION['flash_type'] ?? 'info',
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $alertClass = [
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info'
        ];
        $class = $alertClass[$flash['type']] ?? 'alert-info';
        
        echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($flash['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

// Pagination Functions
function paginate($totalItems, $itemsPerPage, $currentPage) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'offset' => $offset,
        'limit' => $itemsPerPage,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages
    ];
}

function generatePaginationHTML($totalPages, $currentPage, $baseUrl) {
    if ($totalPages <= 1) return '';
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    $prevDisabled = $currentPage <= 1 ? 'disabled' : '';
    $prevPage = max(1, $currentPage - 1);
    $html .= "<li class='page-item $prevDisabled'><a class='page-link' href='$baseUrl?page=$prevPage'>Previous</a></li>";
    
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= "<li class='page-item'><a class='page-link' href='$baseUrl?page=1'>1</a></li>";
        if ($start > 2) $html .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $active = $i == $currentPage ? 'active' : '';
        $html .= "<li class='page-item $active'><a class='page-link' href='$baseUrl?page=$i'>$i</a></li>";
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) $html .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
        $html .= "<li class='page-item'><a class='page-link' href='$baseUrl?page=$totalPages'>$totalPages</a></li>";
    }
    
    $nextDisabled = $currentPage >= $totalPages ? 'disabled' : '';
    $nextPage = min($totalPages, $currentPage + 1);
    $html .= "<li class='page-item $nextDisabled'><a class='page-link' href='$baseUrl?page=$nextPage'>Next</a></li>";
    
    $html .= '</ul></nav>';
    return $html;
}

// Application Status Functions
function hasApplied($user_id, $role_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ? AND role_id = ?");
        $stmt->execute([$user_id, $role_id]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
        'reviewed' => '<span class="badge bg-info">Under Review</span>',
        'accepted' => '<span class="badge bg-success">Accepted</span>',
        'rejected' => '<span class="badge bg-danger">Rejected</span>',
        'withdrawn' => '<span class="badge bg-secondary">Withdrawn</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}

// Email Function (basic - enhance for production)
function sendEmail($to, $subject, $message, $from = null) {
    if ($from === null) {
        $from = getSiteSetting('contact_email', 'noreply@ar-rahma.org');
    }
    
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Redirect Function
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit;
    } else {
        echo '<script>window.location.href="' . $url . '";</script>';
        exit;
    }
}

// Get base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    return $protocol . "://" . $host . $path;
}
?>
