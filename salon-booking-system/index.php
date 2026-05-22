<?php
$pageTitle = 'Home - Secure Salon Booking';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-text">
        <p class="eyebrow">Modern appointment booking for a small salon</p>
        <h1>Book salon services without phone calls or paper diaries.</h1>
        <p>
            Secure Salon Booking helps customers browse services, request appointments,
            and track booking status while staff manage appointments from one protected dashboard.
        </p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="services.php">View Services</a>
            <a class="btn btn-secondary" href="book.php">Book Appointment</a>
        </div>
        <div class="stat-strip" aria-label="System highlights">
            <div>
                <strong>20+</strong>
                <span>Salon services</span>
            </div>
            <div>
                <strong>3</strong>
                <span>User roles</span>
            </div>
            <div>
                <strong>PDO</strong>
                <span>Secure queries</span>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <img src="assets/img/salon-hero.svg" alt="Illustration of a salon reception desk with appointment cards">
    </div>
</section>

<section class="section-grid">
    <article class="info-card">
        <h2>For Customers</h2>
        <p>Customers can register, log in, view active salon services, book appointments, and cancel their own pending or confirmed bookings.</p>
    </article>
    <article class="info-card">
        <h2>For Staff</h2>
        <p>Staff can view all bookings and update appointment statuses from a protected role-based dashboard.</p>
    </article>
    <article class="info-card">
        <h2>For Admin</h2>
        <p>Admin users can manage services, deactivate unavailable services, and monitor the full appointment workflow.</p>
    </article>
</section>

<section class="feature-band">
    <div>
        <h2>Business Problems Solved</h2>
        <p>Designed for a small salon that wants fewer booking mistakes and better customer records.</p>
    </div>
    <ul class="check-list">
        <li>Reduces manual phone, message, and notebook bookings</li>
        <li>Improves appointment record keeping</li>
        <li>Helps prevent duplicate time-slot bookings</li>
        <li>Protects customer information with role-based access</li>
    </ul>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
