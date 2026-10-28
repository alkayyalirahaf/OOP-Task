<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_role'] === 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>

<?php include_once '../header.php'; ?>

<div class="mobile-screen text-center" style="padding-top: 100px;">
    <h1 class="page-title">Welcome!</h1>
    
    <p class="page-subtitle" style="font-size: 18px;">
        Hello, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong>
    </p>
    
    <div style="margin-top: 40px;">
        <i class="bi bi-person-circle" style="font-size: 80px; color: #4a67ff;"></i>
    </div>

    <div style="margin-top: 50px;">
        <a href="logout.php" class="btn-red" style="text-decoration: none; display: inline-block;">Logout</a>
    </div>
</div>

</body>
</html>