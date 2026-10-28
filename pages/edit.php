<?php
session_start();
require_once '../config.php';
require_once '../Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$db = new Database();
$pdo = $db->getConnection();
$adminObj = new Admin($pdo);

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$user = $adminObj->getUserById($id);

if (!$user) {
    die("User not found!");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($adminObj->updateUser($id, $name, $email)) {
        $message = "<div class='alert alert-success'>User updated successfully!</div>";
        $user['name'] = $name;
        $user['email'] = $email;
    } else {
        $message = "<div class='alert alert-danger'>Failed to update user.</div>";
    }
}
?>

<?php include_once '../header.php'; ?>

<div class="mobile-screen">
    <a href="dashboard.php" class="back-arrow"><i class="bi bi-chevron-left"></i></a>
    <h2 class="page-title">Edit User</h2>
    <p class="page-subtitle">Update user details</p>

    <?= $message ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <button type="submit" class="btn-blue">Save Changes</button>
    </form>
</div>

</body>
</html>