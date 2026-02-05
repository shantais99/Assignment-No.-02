<?php
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

$errors = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    $user->name = trim($_POST['name']);
    $user->email = trim($_POST['email']);
    $user->password = $_POST['password'];
    
    // VALIDATION
    $errors = [];
    if (empty($user->name) || empty($user->email) || empty($user->password)) {
        $errors[] = 'All fields are required';
    }
    if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (strlen($user->password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if (empty($errors)) {
        if ($user->register()) {
            $success = 'Registration successful! Please <a href="login.php">login</a>.';
        } else {
            $errors[] = 'Registration failed. Email may already exist.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - User System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-400 to-purple-500 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Register</h1>
        
        <?php if ($errors): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6">
                <?php foreach($errors as $error): ?>
                    <p>• <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded-lg mb-6">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-8">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                Register
            </button>
        </form>
        
        <p class="text-center mt-6 text-gray-600">
            Already have account? <a href="login.php" class="text-blue-600 font-semibold">Login</a>
        </p>
    </div>
</body>
</html>