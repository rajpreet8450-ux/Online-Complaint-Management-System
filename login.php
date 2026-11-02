<?php
require "config/db.php";
session_start();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"]=(int)$user["id"];
        $_SESSION["name"]=$user["name"];
        $_SESSION["role"]=$user["role"];
        header("Location: " . ($user["role"] === "admin" ? "admin/dashboard.php" : "student/dashboard.php"));
        exit;
    }
    $error = "Incorrect email or password.";
}
$page_title="Login"; $asset_prefix="";
require "includes/header.php";
?>
<section class="card narrow">
<h1>Login</h1>
<?php if (isset($_GET["registered"])): ?><div class="notice">Registration successful. Please log in.</div><?php endif; ?>
<?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="form-row"><label>Email</label><input type="email" name="email" required></div>
<div class="form-row"><label>Password</label><input type="password" name="password" required></div>
<button class="btn">Login</button>
</form>
<p>New student? <a href="register.php">Create an account</a>.</p>
<p class="muted">Administrator accounts are created using setup_admin.php.</p>
</section>
<?php require "includes/footer.php"; ?>
