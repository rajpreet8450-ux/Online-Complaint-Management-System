<?php
require "config/db.php";
$message = "";
$count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
if ($_SERVER["REQUEST_METHOD"] === "POST" && $count === 0) {
    $name = trim($_POST["name"] ?? "Administrator");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    if ($name && filter_var($email,FILTER_VALIDATE_EMAIL) && strlen($password)>=8) {
        $stmt=$pdo->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,'admin')");
        $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
        $message="Admin account created. For security, now delete setup_admin.php from your project folder.";
        $count=1;
    } else $message="Enter a valid name, email, and password of at least 8 characters.";
}
$page_title="Create Administrator"; $asset_prefix="";
require "includes/header.php";
?>
<section class="card narrow">
<h1>One-time Admin Setup</h1>
<?php if ($message): ?><div class="notice"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<?php if ($count===0): ?>
<p>Create the first administrator account. This page works only while no admin exists.</p>
<form method="post">
<div class="form-row"><label>Admin name</label><input name="name" required></div>
<div class="form-row"><label>Admin email</label><input type="email" name="email" required></div>
<div class="form-row"><label>Password (minimum 8 characters)</label><input type="password" name="password" minlength="8" required></div>
<button class="btn">Create admin</button>
</form>
<?php else: ?><p>An administrator account already exists. Delete this setup file if you have not already done so.</p><?php endif; ?>
</section>
<?php require "includes/footer.php"; ?>
