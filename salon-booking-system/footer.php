        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>&copy; <?php echo date('Y'); ?> Secure Salon Appointment Booking System. ICT312 Assignment 02.</p>
            <div class="footer-links">
                <a href="<?php echo e($basePath ?? ''); ?>services.php">Services</a>
                <a href="<?php echo e($basePath ?? ''); ?>book.php">Book</a>
                <a href="<?php echo e($basePath ?? ''); ?>login.php">Login</a>
            </div>
        </div>
    </footer>
    <script src="<?php echo e($basePath ?? ''); ?>assets/js/script.js"></script>
</body>
</html>
