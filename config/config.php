<?php
session_start();

define('APP_NAME', 'ARSIPKU');
define('APP_FULL_NAME', 'Archive Management System');
define('APP_VERSION', '1.0.0');
define('BASE_URL', '/Archive_Management_System/');

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'login.php');
        exit();
    }
}

function getUserRole() {
    return $_SESSION['user_role'] ?? 'Viewer';
}

function hasAccess($requiredRole) {
    $userRole = getUserRole();
    $roles = ['Admin' => 3, 'Operator' => 2, 'Viewer' => 1];
    return ($roles[$userRole] ?? 0) >= ($roles[$requiredRole] ?? 0);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

function formatDateTime($datetime) {
    if (empty($datetime)) return '-';
    return date('d/m/Y H:i', strtotime($datetime));
}

function alert($message, $type = 'success') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

function showAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        $bgClass = $alert['type'] === 'success' ? 'alert-success' : ($alert['type'] === 'error' ? 'alert-danger' : 'alert-warning');
        return '<div class="alert ' . $bgClass . ' alert-dismissible fade show" role="alert">
            ' . $alert['message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
    return '';
}
?>
