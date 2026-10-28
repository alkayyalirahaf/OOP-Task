<?php
session_start();
require_once '../config.php';     
require_once '../User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: dashboard.php"); 
    } else {
        header("Location: welcome.php");     
    }
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errorMessage = "Please fill all fields.";
    } else {
        // Initialize database connection
        $db = new Database();
        $pdo = $db->getConnection();

        // Fetch user by email
        $userObj = new User($pdo);
        $user = $userObj->getUserByEmail($email);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                
                // Store user data in session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: welcome.php");
                }
                exit;

            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "Email not found.";
        }
    }
}
?>

<?php include_once '../header.php'; ?>

<div class="mobile-screen">
    <a href="../index.php" class="back-arrow"><i class="bi bi-chevron-left"></i></a>

    <h2 class="page-title">Login</h2>
    <p class="page-subtitle">Welcome back ! Login with your credentials</p>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger" style="font-size: 13px; padding: 10px;">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="abc@gmail.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-blue">Login</button>

        <p class="bottom-text">
            Don't have an account? <a href="../index.php">Sign Up</a>
        </p>
    </form>
</div>