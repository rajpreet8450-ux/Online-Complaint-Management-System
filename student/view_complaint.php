<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("student");
$id=(int)($_GET["id"]??0);
$stmt=$pdo->prepare("SELECT * FROM complaints WHERE id=? AND user_id=?");
$stmt->execute([$id,$_SESSION["user_id"]]); $c=$stmt->fetch();
if(!$c){http_response_code(404);exit("Complaint not found.");}
$page_title="Complaint Details"; $asset_prefix="../"; $logout_link="../logout.php"; $home_link="dashboard.php";
require "../includes/header.php";
?>
<section class="card"><h1>Complaint Details</h1>
<?php if(isset($_GET["created"])): ?><div class="notice">Complaint submitted successfully. Save your complaint ID for tracking.</div><?php endif; ?>
<p><strong>Complaint ID:</strong> CMP<?= str_pad((string)$c["id"],5,"0",STR_PAD_LEFT) ?></p>
<p><strong>Title:</strong> <?= htmlspecialchars($c["title"]) ?></p>
<p><strong>Category:</strong> <?= htmlspecialchars($c["category"]) ?></p>
<p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($c["description"])) ?></p>
<p><strong>Status:</strong> <span class="badge"><?= htmlspecialchars($c["status"]) ?></span></p>
<p><strong>Administrator remarks:</strong><br><?= $c["admin_remarks"] ? nl2br(htmlspecialchars($c["admin_remarks"])) : "No remarks added yet." ?></p>
<p><strong>Submitted:</strong> <?= htmlspecialchars($c["created_at"]) ?></p>
<a class="btn" href="dashboard.php">Back to Dashboard</a></section>
<?php require "../includes/footer.php"; ?>
