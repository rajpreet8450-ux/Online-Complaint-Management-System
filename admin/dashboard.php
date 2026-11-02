<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("admin");
$status=$_GET["status"]??"";
$allowed=["Submitted","Under Review","In Progress","Resolved"];
if($status && in_array($status,$allowed,true)){
    $stmt=$pdo->prepare("SELECT c.*,u.name student_name FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.status=? ORDER BY c.created_at DESC");
    $stmt->execute([$status]);
}else{
    $stmt=$pdo->query("SELECT c.*,u.name student_name FROM complaints c JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC");
}
$rows=$stmt->fetchAll();
$counts=$pdo->query("SELECT status,COUNT(*) total FROM complaints GROUP BY status")->fetchAll();
$summary=array_column($counts,"total","status");
$page_title="Admin Dashboard"; $asset_prefix="../"; $logout_link="../logout.php"; $home_link="dashboard.php";
require "../includes/header.php";
?>
<h1>Administrator Dashboard</h1>
<div class="grid">
<?php foreach($allowed as $s): ?><div class="stat"><?= htmlspecialchars($s) ?><strong><?= (int)($summary[$s]??0) ?></strong></div><?php endforeach; ?>
</div>
<section class="card"><h2>All Complaints</h2>
<p class="actions"><a class="btn secondary" href="dashboard.php">All</a><?php foreach($allowed as $s): ?><a class="btn secondary" href="?status=<?= urlencode($s) ?>"><?= htmlspecialchars($s) ?></a><?php endforeach; ?></p>
<?php if(!$rows): ?><p class="muted">No complaints found.</p><?php else: ?>
<table><tr><th>ID</th><th>Student</th><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Action</th></tr>
<?php foreach($rows as $r): ?><tr>
<td>CMP<?= str_pad((string)$r["id"],5,"0",STR_PAD_LEFT) ?></td><td><?= htmlspecialchars($r["student_name"]) ?></td>
<td><?= htmlspecialchars($r["title"]) ?></td><td><?= htmlspecialchars($r["category"]) ?></td>
<td><span class="badge"><?= htmlspecialchars($r["status"]) ?></span></td><td><?= htmlspecialchars($r["created_at"]) ?></td>
<td><a href="update_complaint.php?id=<?= (int)$r["id"] ?>">Review / Update</a></td></tr><?php endforeach; ?>
</table><?php endif; ?></section>
<?php require "../includes/footer.php"; ?>
