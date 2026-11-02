<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("admin");
$id=(int)($_GET["id"]??0);
$stmt=$pdo->prepare("SELECT c.*,u.name student_name,u.email FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.id=?");
$stmt->execute([$id]); $c=$stmt->fetch();
if(!$c){http_response_code(404);exit("Complaint not found.");}
$error="";
$statuses=["Submitted","Under Review","In Progress","Resolved"];
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $status=$_POST["status"]??""; $remarks=trim($_POST["admin_remarks"]??"");
    if(!in_array($status,$statuses,true)) $error="Choose a valid status.";
    else{
        $up=$pdo->prepare("UPDATE complaints SET status=?,admin_remarks=?,updated_at=CURRENT_TIMESTAMP WHERE id=?");
        $up->execute([$status,$remarks,$id]);
        header("Location: dashboard.php?updated=1"); exit;
    }
}
$page_title="Update Complaint"; $asset_prefix="../"; $logout_link="../logout.php"; $home_link="dashboard.php";
require "../includes/header.php";
?>
<section class="card narrow"><h1>Review Complaint</h1>
<p><strong>Student:</strong> <?= htmlspecialchars($c["student_name"]) ?> (<?= htmlspecialchars($c["email"]) ?>)</p>
<p><strong>Complaint:</strong> <?= htmlspecialchars($c["title"]) ?></p>
<p><strong>Category:</strong> <?= htmlspecialchars($c["category"]) ?></p>
<p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($c["description"])) ?></p>
<?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="form-row"><label>Status</label><select name="status" required><?php foreach($statuses as $s): ?><option value="<?= htmlspecialchars($s) ?>" <?= $c["status"]===$s?"selected":"" ?>><?= htmlspecialchars($s) ?></option><?php endforeach; ?></select></div>
<div class="form-row"><label>Administrator remarks</label><textarea name="admin_remarks"><?= htmlspecialchars($c["admin_remarks"]??"") ?></textarea></div>
<button class="btn">Save Update</button> <a class="btn secondary" href="dashboard.php">Back</a>
</form></section>
<?php require "../includes/footer.php"; ?>
