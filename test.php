<?php
require_once 'config.php';
require_once 'classes/Database.php';

echo "✅ Config OK<br>";
$database = new Database();
$db = $database->getConnection();
echo "✅ Database Connected<br>";

$stmt = $db->query("SELECT COUNT(*) as count FROM users");
echo "✅ Users: " . $stmt->fetch()['count'];

//test:wilsontest@example.com, password:wilson123

?>