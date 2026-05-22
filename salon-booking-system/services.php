<?php
require_once __DIR__ . '/config/database.php';

$services = [];
$error = '';

try {
    $stmt = $pdo->query("SELECT service_id, service_name, category, duration_minutes, price FROM services WHERE status = 'active' ORDER BY category, service_name");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Services page error: ' . $e->getMessage());
    $error = 'Services could not be loaded at this time.';
}

$pageTitle = 'Services - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <h1>Salon Services</h1>
    <p>Browse hair, colour, nail, facial, waxing, makeup, and treatment services.</p>
</section>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo e($error); ?></div>
<?php endif; ?>

<?php if (empty($services) && !$error): ?>
    <div class="empty-state">No active services are available right now.</div>
<?php else: ?>
    <div class="service-grid">
        <?php foreach ($services as $service): ?>
            <article class="service-card">
                <div>
                    <p class="service-category"><?php echo e($service['category']); ?></p>
                    <h2><?php echo e($service['service_name']); ?></h2>
                    <p><?php echo e($service['duration_minutes']); ?> minutes</p>
                </div>
                <div class="service-footer">
                    <strong>$<?php echo e(number_format((float) $service['price'], 2)); ?></strong>
                    <a class="btn btn-small" href="book.php?service_id=<?php echo e($service['service_id']); ?>">Book</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
