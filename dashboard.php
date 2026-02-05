<?php
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->getById($_SESSION['user_id']);

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $user->updateProfile($_POST['name'], $_POST['email']);
        $success = 'Profile updated successfully!';
        $user->getById($_SESSION['user_id']); // Refresh
    } elseif (isset($_POST['change_password'])) {
        if ($user->changePassword($_POST['current_password'], $_POST['new_password'])) {
            $success = 'Password changed successfully!';
        } else {
            $error = 'Current password incorrect';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - User System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">User Dashboard</h1>
                <div>
                    <span class="text-gray-700 mr-4">Welcome, <?= htmlspecialchars($user->name) ?></span>
                    <a href="logout.php" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Profile</h2>
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded-lg mb-6"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-6"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user->name) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
                    <p><strong>Member since:</strong> <?= date('M j, Y', strtotime($user->created_at ?? 'now')) ?></p>
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Update Profile</h3>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user->name) ?>" required
                               class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required
                               class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <button type="submit" name="update_profile" 
                        class="bg-blue-600 text-white py-3 px-8 rounded-lg hover:bg-blue-700 font-semibold">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Change Password</h3>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">New Password</label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <button type="submit" name="change_password" 
                        class="mt-6 bg-green-600 text-white py-3 px-8 rounded-lg hover:bg-green-700 font-semibold">
                    Change Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>