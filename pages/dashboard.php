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

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    if ($adminObj->deleteUser($delete_id)) {
        header("Location: dashboard.php?msg=deleted");
        exit;
    } else {
        $error = "Failed to delete user.";
    }
}

$users = $adminObj->getAllUsers();
?>

<?php include_once '../header.php'; ?>

<div class="mobile-screen" style="max-width: 900px;">
    
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="page-title" style="margin: 0;">Admin Dashboard</h2>
        <a href="logout.php" class="btn-red" style="width: auto; padding: 8px 20px; text-decoration: none;">Logout</a>
    </div>
    
    <p class="page-subtitle" style="text-align: left; margin-bottom: 30px;">Manage all users here</p>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success" style="font-size: 14px;">User deleted successfully!</div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger" style="font-size: 14px;"><?= $error ?></div>
    <?php endif; ?>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th> 
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                <?= $user['role'] ?>
                            </span>
                        </td>
                        <td><?= $user['created_at'] ?></td>
                        
                        <td style="white-space: nowrap;">
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary" style="font-size: 12px;">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="dashboard.php?delete_id=<?= $user['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   style="font-size: 12px;" 
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>