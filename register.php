<?php
require "config/db.php";
session_start();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = "Enter your name, a valid email, and a password of at least 8 characters.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = "This email is already registered.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'student')");
            $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
            header("Location: login.php?registered=1"); exit;
        }
    }
}
$page_title="Student Registration"; $asset_prefix="";
require "includes/header.php";
?>
<section class="card narrow">
<h1>Student Registration</h1>
<?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="form-row"><label>Full name</label><input name="name" required maxlength="100" value="<?= htmlspecialchars($_POST["name"] ?? "") ?>"></div>
<div class="form-row"><label>Email</label><input type="email" name="email" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"></div>
<div class="form-row"><label>Password (minimum 8 characters)</label><input type="password" name="password" minlength="8" required></div>
<button class="btn">Create account</button>
</form>
<p>Already registered? <a href="login.php">Login here</a>.</p>
</section>
<?php require "includes/footer.php"; ?>
