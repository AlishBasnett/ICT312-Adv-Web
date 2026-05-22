<?php
require_once __DIR__ . '/auth.php';

$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'Secure Salon Appointment Booking System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo e($basePath); ?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?php echo e($basePath); ?>index.php">Secure Salon Booking</a>
            <button class="nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false">Menu</button>
            <nav class="site-nav" id="siteNav">
                <a href="<?php echo e($basePath); ?>index.php">Home</a>
                <a href="<?php echo e($basePath); ?>services.php">Services</a>

                <?php if (is_logged_in() && is_customer()): ?>
                    <a href="<?php echo e($basePath); ?>book.php">Book Appointment</a>
                    <a href="<?php echo e($basePath); ?>my_appointments.php">My Appointments</a>
                <?php endif; ?>

                <?php if (is_logged_in() && is_staff_or_admin()): ?>
                    <a href="<?php echo e($basePath); ?>admin/dashboard.php">Dashboard</a>
                <?php endif; ?>

                <?php if (is_logged_in() && is_admin()): ?>
                    <a href="<?php echo e($basePath); ?>admin/manage_services.php">Manage Services</a>
                <?php endif; ?>

                <?php if (is_logged_in()): ?>
                    <span class="nav-user">Hi, <?php echo e($_SESSION['full_name'] ?? 'User'); ?></span>
                    <a href="<?php echo e($basePath); ?>logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo e($basePath); ?>register.php">Register</a>
                    <a href="<?php echo e($basePath); ?>login.php">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container">
