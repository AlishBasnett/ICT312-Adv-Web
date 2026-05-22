<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin('../login.php', 'dashboard.php');

$basePath = '../';
$message = '';
$errors = [];

function validate_service_input($name, $duration, $price)
{
    $errors = [];

    if ($name === '') {
        $errors[] = 'Service name is required.';
    }

    if ($duration <= 0) {
        $errors[] = 'Duration must be greater than zero.';
    }

    if ($price < 0) {
        $errors[] = 'Price cannot be negative.';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $serviceName = trim($_POST['service_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $duration = (int) ($_POST['duration_minutes'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';

    $errors = validate_service_input($serviceName, $duration, $price);

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO services (service_name, category, duration_minutes, price, status) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$serviceName, $category, $duration, $price, $status]);
            $message = 'Service added successfully.';
        } catch (PDOException $e) {
            error_log('Add service error: ' . $e->getMessage());
            $errors[] = 'Service could not be added.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $serviceName = trim($_POST['service_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $duration = (int) ($_POST['duration_minutes'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';

    $errors = validate_service_input($serviceName, $duration, $price);

    if ($serviceId <= 0) {
        $errors[] = 'Invalid service selected.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE services
                 SET service_name = ?, category = ?, duration_minutes = ?, price = ?, status = ?
                 WHERE service_id = ?'
            );
            $stmt->execute([$serviceName, $category, $duration, $price, $status, $serviceId]);
            $message = 'Service updated successfully.';
        } catch (PDOException $e) {
            error_log('Update service error: ' . $e->getMessage());
            $errors[] = 'Service could not be updated.';
        }
    }
}

$services = [];

try {
    $stmt = $pdo->query('SELECT service_id, service_name, category, duration_minutes, price, status FROM services ORDER BY status, category, service_name');
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Manage services load error: ' . $e->getMessage());
    $errors[] = 'Services could not be loaded.';
}

$pageTitle = 'Manage Services - Secure Salon Booking';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-heading">
    <h1>Manage Services</h1>
    <p>Add, edit, activate, or deactivate salon services.</p>
</section>

<div class="admin-actions">
    <a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a>
</div>

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

<section class="form-card wide-form">
    <h2>Add New Service</h2>
    <form method="post" action="manage_services.php" class="form service-add-form js-validate">
        <div class="form-row">
            <div>
                <label for="service_name">Service Name</label>
                <input type="text" id="service_name" name="service_name" required>
            </div>
            <div>
                <label for="category">Category</label>
                <input type="text" id="category" name="category" placeholder="Hair, Beauty, Nails">
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="duration_minutes">Duration Minutes</label>
                <input type="number" id="duration_minutes" name="duration_minutes" min="1" required>
            </div>
            <div>
                <label for="price">Price</label>
                <input type="number" id="price" name="price" min="0" step="0.01" required>
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary" type="submit" name="add_service">Add Service</button>
    </form>
</section>

<section class="content-card">
    <h2>Existing Services</h2>

    <?php if (empty($services)): ?>
        <div class="empty-state">No services found.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <?php $formId = 'service-form-' . (int) $service['service_id']; ?>
                        <tr>
                            <td>
                                <form method="post" action="manage_services.php" id="<?php echo e($formId); ?>" class="service-edit-form"></form>
                                <input form="<?php echo e($formId); ?>" type="hidden" name="service_id" value="<?php echo e($service['service_id']); ?>">
                                <input form="<?php echo e($formId); ?>" type="text" name="service_name" value="<?php echo e($service['service_name']); ?>" required>
                            </td>
                            <td><input form="<?php echo e($formId); ?>" type="text" name="category" value="<?php echo e($service['category']); ?>"></td>
                            <td><input form="<?php echo e($formId); ?>" type="number" name="duration_minutes" min="1" value="<?php echo e($service['duration_minutes']); ?>" required></td>
                            <td><input form="<?php echo e($formId); ?>" type="number" name="price" min="0" step="0.01" value="<?php echo e($service['price']); ?>" required></td>
                            <td>
                                <select form="<?php echo e($formId); ?>" name="status">
                                    <option value="active" <?php echo $service['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $service['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button form="<?php echo e($formId); ?>" class="btn btn-small" type="submit" name="update_service">Save</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
