<?php
require_once 'config.php';
require_once 'User.php';

// Initialize database connection
$db = new Database();
$pdo = $db->getConnection();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Create a new User object
    $user = new User($pdo);

    if (empty($name) || empty($email) || empty($password)) {
        $message = "<div class='alert error'>Please fill all inputs</div>";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert error'>Please fill a valid email</div>";
    } 
    else {
        if ($user->isEmailExists($email)) {
            $message = "<div class='alert error'>This email is already used</div>";
        } else {
            // Set user properties
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($password);

            // Execute registration
            if ($user->register()) {
                $message = "<div class='alert success'>Great job! User registered.</div>";
            } else {
                $message = "<div class='alert error'>Error, try again later</div>";
            }
        }
    }
}

include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

<?php echo $message; ?>   
 
    <form action="index.php" method="POST">
        <label for="Name">Name :</label>  
        <input type="text" name="name" required placeholder="Ex: Ayham Zaid">

        <label for="Email">Email :</label>  
        <input type="email" name="email" required placeholder="Ex: yourEmail@gmail.com">

        <label for="Password">Password :</label>  
        <input type="password" name="password" required placeholder="***********">

        <button type="submit">Create User</button>
    </form>
</body>
</html>