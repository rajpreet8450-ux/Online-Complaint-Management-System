<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("student");
$stmt=$pdo->prepare("SELECT id,title,category,status,created_at FROM complaints WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]); $rows=$stmt->fetchAll();
$page_title="Student Dashboard"; $asset_prefix="../"; $logout_link="../logout.php"; $home_link="dashboard.php";
require "../includes/header.php";
?>
<h1>Student Dashboard</h1>
<section class="card">
<h2>Submit a Complaint</h2>
<form method="post" action="new_complaint.php">
<div class="form-row"><label for="title">Complaint title</label><input id="title" name="title" maxlength="150" required></div>
<div class="form-row"><label for="category">Category</label><select id="category" name="category" required><option value="">Choose category</option><?php foreach(["Classroom Facilities","Laboratory","Library","Hostel","Cleanliness","Internet/Wi-Fi","Other"] as $category): ?><option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option><?php endforeach; ?></select></div>
<div class="form-row"><label for="description">Description (at least 10 characters)</label><textarea id="description" name="description" required minlength="10"></textarea></div>
<button class="btn">Submit Complaint</button>
</form>
</section>
<section class="card">
<h2>All My Complaints</h2>
<?php if (!$rows): ?><p class="muted">You have not submitted any complaints yet.</p><?php else: ?>
<table><tr><th>ID</th><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th></th></tr>
<?php foreach($rows as $r): ?><tr>
<td><?= (int)$r["id"] ?></td><td><?= htmlspecialchars($r["title"]) ?></td><td><?= htmlspecialchars($r["category"]) ?></td>
<td><span class="badge"><?= htmlspecialchars($r["status"]) ?></span></td><td><?= htmlspecialchars($r["created_at"]) ?></td>
<td><a href="view_complaint.php?id=<?= (int)$r["id"] ?>">View</a></td>
</tr><?php endforeach; ?></table><?php endif; ?>
</section>
<?php require "../includes/footer.php"; ?>
