<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function is_admin()
{
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_staff()
{
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

function is_staff_or_admin()
{
    return is_admin() || is_staff();
}

function is_customer()
{
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

function require_login($loginPath = 'login.php')
{
    if (!is_logged_in()) {
        header('Location: ' . $loginPath);
        exit;
    }
}

function require_customer($loginPath = 'login.php')
{
    require_login($loginPath);

    if (!is_customer()) {
        header('Location: admin/dashboard.php');
        exit;
    }
}

function require_staff_or_admin($loginPath = '../login.php')
{
    require_login($loginPath);

    if (!is_staff_or_admin()) {
        header('Location: ' . $loginPath);
        exit;
    }
}

function require_admin($loginPath = '../login.php', $fallbackPath = null)
{
    require_login($loginPath);

    if (!is_admin()) {
        header('Location: ' . ($fallbackPath ?? $loginPath));
        exit;
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
