<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$email = '';
$successMessage = isset($_GET['registered']) ? 'Registration successful. Please login.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                    header('Location: admin/dashboard.php');
                    exit;
                }

                header('Location: my_appointments.php');
                exit;
            }

            $errors[] = 'Invalid email or password.';
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $errors[] = 'Login failed. Please try again.';
        }
    }
}

$pageTitle = 'Login - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="form-card">
    <h1>Login</h1>
    <p class="muted">Customers and admin users can login from this page.</p>

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

    <form method="post" action="login.php" class="form js-validate">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button class="btn btn-primary" type="submit">Login</button>
    </form>

    <p class="form-note">New customer? <a href="register.php">Create an account</a>.</p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
