<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

require_customer('login.php');

$errors = [];
$successMessage = '';
$services = [];
$selectedServiceId = isset($_GET['service_id']) ? (int) $_GET['service_id'] : 0;
$appointmentDate = '';
$appointmentTime = '';
$notes = '';

try {
    $stmt = $pdo->query("SELECT service_id, service_name, category, duration_minutes, price FROM services WHERE status = 'active' ORDER BY category, service_name");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Book services load error: ' . $e->getMessage());
    $errors[] = 'Services could not be loaded.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedServiceId = (int) ($_POST['service_id'] ?? 0);
    $appointmentDate = trim($_POST['appointment_date'] ?? '');
    $appointmentTime = trim($_POST['appointment_time'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $today = date('Y-m-d');

    if ($selectedServiceId <= 0) {
        $errors[] = 'Please select a service.';
    }

    if ($appointmentDate === '') {
        $errors[] = 'Appointment date is required.';
    } elseif ($appointmentDate < $today) {
        $errors[] = 'Past dates cannot be booked.';
    }

    if ($appointmentTime === '') {
        $errors[] = 'Appointment time is required.';
    }

    if (empty($errors)) {
        try {
            $check = $pdo->prepare("SELECT service_id FROM services WHERE service_id = ? AND status = 'active' LIMIT 1");
            $check->execute([$selectedServiceId]);

            if (!$check->fetch()) {
                $errors[] = 'Selected service is not available.';
            } else {
                $duplicate = $pdo->prepare(
                    "SELECT appointment_id
                     FROM appointments
                     WHERE service_id = ?
                     AND appointment_date = ?
                     AND appointment_time = ?
                     AND status IN ('Pending', 'Confirmed')
                     LIMIT 1"
                );
                $duplicate->execute([$selectedServiceId, $appointmentDate, $appointmentTime]);

                if ($duplicate->fetch()) {
                    $errors[] = 'This service already has a pending or confirmed booking at the selected date and time.';
                } else {
                    $insert = $pdo->prepare(
                        'INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time, status, notes) VALUES (?, ?, ?, ?, ?, ?)'
                    );
                    $insert->execute([
                        $_SESSION['user_id'],
                        $selectedServiceId,
                        $appointmentDate,
                        $appointmentTime,
                        'Pending',
                        $notes,
                    ]);

                    $successMessage = 'Appointment request submitted successfully. Status: Pending.';
                    $selectedServiceId = 0;
                    $appointmentDate = '';
                    $appointmentTime = '';
                    $notes = '';
                }
            }
        } catch (PDOException $e) {
            error_log('Booking error: ' . $e->getMessage());
            $errors[] = 'Appointment could not be saved. Please try again.';
        }
    }
}

$pageTitle = 'Book Appointment - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="form-card wide-form">
    <h1>Book Appointment</h1>
    <p class="muted">Select an active service, date, and time. New bookings are saved as Pending.</p>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?php echo e($successMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="book.php" class="form js-validate">
        <label for="service_id">Service</label>
        <select id="service_id" name="service_id" required>
            <option value="">Select a service</option>
            <?php foreach ($services as $service): ?>
                <option value="<?php echo e($service['service_id']); ?>" <?php echo $selectedServiceId === (int) $service['service_id'] ? 'selected' : ''; ?>>
                    <?php echo e($service['service_name']); ?> - $<?php echo e(number_format((float) $service['price'], 2)); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="appointment_date">Appointment Date</label>
        <input type="date" id="appointment_date" name="appointment_date" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e($appointmentDate); ?>" required>

        <label for="appointment_time">Appointment Time</label>
        <input type="time" id="appointment_time" name="appointment_time" value="<?php echo e($appointmentTime); ?>" required>

        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="4" placeholder="Optional notes for salon staff"><?php echo e($notes); ?></textarea>

        <button class="btn btn-primary" type="submit">Submit Booking</button>
    </form>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
