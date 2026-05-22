<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$fullName = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password and confirm password must match.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare(
                    'INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)'
                );
                $insert->execute([$fullName, $email, $phone, $passwordHash, 'customer']);

                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            error_log('Registration error: ' . $e->getMessage());
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

$pageTitle = 'Register - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="form-card">
    <h1>Create Customer Account</h1>
    <p class="muted">Register to book and manage your salon appointments.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php" class="form js-validate">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo e($fullName); ?>" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" value="<?php echo e($phone); ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="6" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>

        <button class="btn btn-primary" type="submit">Register</button>
    </form>

    <p class="form-note">Already have an account? <a href="login.php">Login here</a>.</p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
