<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

require_customer('login.php');

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment'])) {
    $appointmentId = (int) ($_POST['appointment_id'] ?? 0);

    if ($appointmentId <= 0) {
        $errors[] = 'Invalid appointment selected.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE appointments
                 SET status = 'Cancelled'
                 WHERE appointment_id = ?
                 AND user_id = ?
                 AND status IN ('Pending', 'Confirmed')"
            );
            $stmt->execute([$appointmentId, $_SESSION['user_id']]);

            if ($stmt->rowCount() > 0) {
                $message = 'Appointment cancelled successfully.';
            } else {
                $errors[] = 'Appointment could not be cancelled. It may already be completed or cancelled.';
            }
        } catch (PDOException $e) {
            error_log('Cancel appointment error: ' . $e->getMessage());
            $errors[] = 'Appointment could not be cancelled. Please try again.';
        }
    }
}

$appointments = [];

try {
    $stmt = $pdo->prepare(
        'SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, a.notes,
                s.service_name, s.category
         FROM appointments a
         INNER JOIN services s ON a.service_id = s.service_id
         WHERE a.user_id = ?
         ORDER BY a.appointment_date DESC, a.appointment_time DESC'
    );
    $stmt->execute([$_SESSION['user_id']]);
    $appointments = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('My appointments error: ' . $e->getMessage());
    $errors[] = 'Appointments could not be loaded.';
}

$pageTitle = 'My Appointments - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <h1>My Appointments</h1>
    <p>View your own bookings and cancel appointments that are Pending or Confirmed.</p>
</section>

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
    <div class="empty-state">
        <p>You have no appointments yet.</p>
        <a class="btn btn-primary" href="book.php">Book Appointment</a>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td>
                            <strong><?php echo e($appointment['service_name']); ?></strong><br>
                            <span class="muted"><?php echo e($appointment['category']); ?></span>
                        </td>
                        <td><?php echo e($appointment['appointment_date']); ?></td>
                        <td><?php echo e(date('h:i A', strtotime($appointment['appointment_time']))); ?></td>
                        <td><span class="status status-<?php echo e(strtolower($appointment['status'])); ?>"><?php echo e($appointment['status']); ?></span></td>
                        <td><?php echo e($appointment['notes']); ?></td>
                        <td>
                            <?php if (in_array($appointment['status'], ['Pending', 'Confirmed'], true)): ?>
                                <form method="post" action="my_appointments.php" class="inline-form js-confirm" data-confirm="Cancel this appointment?">
                                    <input type="hidden" name="appointment_id" value="<?php echo e($appointment['appointment_id']); ?>">
                                    <button class="btn btn-danger btn-small" type="submit" name="cancel_appointment">Cancel</button>
                                </form>
                            <?php else: ?>
                                <span class="muted">No action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
