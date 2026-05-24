<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_staff_or_admin('../login.php');

$basePath = '../';
$message = '';
$errors = [];
$allowedStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($appointmentId <= 0 || !in_array($status, $allowedStatuses, true)) {
        $errors[] = 'Invalid appointment status update.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE appointments SET status = ? WHERE appointment_id = ?');
            $stmt->execute([$status, $appointmentId]);
            $message = 'Appointment status updated.';
        } catch (PDOException $e) {
            error_log('Admin status update error: ' . $e->getMessage());
            $errors[] = 'Appointment status could not be updated.';
        }
    }
}

$appointments = [];
$statusCounts = array_fill_keys($allowedStatuses, 0);

try {
    $stmt = $pdo->query(
        'SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, a.notes, a.created_at,
                u.full_name, u.phone, u.email,
                s.service_name
         FROM appointments a
         INNER JOIN users u ON a.user_id = u.user_id
         INNER JOIN services s ON a.service_id = s.service_id
         ORDER BY a.appointment_date DESC, a.appointment_time DESC'
    );
    $appointments = $stmt->fetchAll();
    foreach ($appointments as $appointment) {
        if (isset($statusCounts[$appointment['status']])) {
            $statusCounts[$appointment['status']]++;
        }
    }
} catch (PDOException $e) {
    error_log('Admin dashboard error: ' . $e->getMessage());
    $errors[] = 'Appointments could not be loaded.';
}

$pageTitle = 'Appointment Dashboard - Secure Salon Booking';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-heading">
    <h1>Appointment Dashboard</h1>
    <p>View customer appointments and update their status. Service management is available to admin users only.</p>
</section>

<div class="dashboard-summary" aria-label="Appointment summary">
    <div class="summary-card">
        <span>Total</span>
        <strong><?php echo count($appointments); ?></strong>
    </div>
    <?php foreach ($statusCounts as $statusName => $statusTotal): ?>
        <div class="summary-card">
            <span><?php echo e($statusName); ?></span>
            <strong><?php echo e($statusTotal); ?></strong>
        </div>
    <?php endforeach; ?>
</div>

<div class="dashboard-tools">
    <label for="appointmentSearch">Search appointments</label>
    <input type="search" id="appointmentSearch" class="js-table-search" data-table="appointmentsTable" placeholder="Search by customer, phone, service, date or status">
</div>

<?php if (is_admin()): ?>
    <div class="admin-actions">
        <a class="btn btn-secondary" href="manage_services.php">Manage Services</a>
    </div>
<?php endif; ?>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo e($message); ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <p><?php echo e($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (empty($appointments)): ?>
    <div class="empty-state">No appointments have been created yet.</div>
<?php else: ?>
    <div class="table-wrap">
        <table id="appointmentsTable">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td>
                            <strong><?php echo e($appointment['full_name']); ?></strong><br>
                            <span class="muted"><?php echo e($appointment['email']); ?></span>
                        </td>
                        <td><?php echo e($appointment['phone']); ?></td>
                        <td><?php echo e($appointment['service_name']); ?></td>
                        <td><?php echo e($appointment['appointment_date']); ?></td>
                        <td><?php echo e(date('h:i A', strtotime($appointment['appointment_time']))); ?></td>
                        <td><?php echo e($appointment['notes']); ?></td>
                        <td><span class="status status-<?php echo e(strtolower($appointment['status'])); ?>"><?php echo e($appointment['status']); ?></span></td>
                        <td>
                            <form method="post" action="dashboard.php" class="inline-form status-form js-confirm" data-confirm="Update appointment status?">
                                <input type="hidden" name="appointment_id" value="<?php echo e($appointment['appointment_id']); ?>">
                                <select name="status" aria-label="Appointment status">
                                    <?php foreach ($allowedStatuses as $status): ?>
                                        <option value="<?php echo e($status); ?>" <?php echo $appointment['status'] === $status ? 'selected' : ''; ?>>
                                            <?php echo e($status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-small" type="submit" name="update_status">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
