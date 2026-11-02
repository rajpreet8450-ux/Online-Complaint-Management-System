<?php
require "../config/db.php"; require "../includes/auth.php"; require_role("student");
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $title=trim($_POST["title"]??""); $category=$_POST["category"]??""; $description=trim($_POST["description"]??"");
    $allowed=["Classroom Facilities","Laboratory","Library","Hostel","Cleanliness","Internet/Wi-Fi","Other"];
    if($title==="" || !in_array($category,$allowed,true) || strlen($description)<10){
        $error="Enter a title, choose a category, and provide at least 10 characters of description.";
    }else{
        $stmt=$pdo->prepare("INSERT INTO complaints(user_id,title,category,description,status) VALUES(?,?,?,?,'Submitted')");
        $stmt->execute([$_SESSION["user_id"],$title,$category,$description]);
        $id=$pdo->lastInsertId();
        header("Location: view_complaint.php?id=".$id."&created=1"); exit;
    }
}
$page_title="Submit Complaint"; $asset_prefix="../"; $logout_link="../logout.php"; $home_link="dashboard.php";
require "../includes/header.php";
?>
<section class="card narrow"><h1>Submit a Complaint</h1>
<?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="form-row"><label>Complaint title</label><input name="title" maxlength="150" required></div>
<div class="form-row"><label>Category</label><select name="category" required><option value="">Choose category</option><?php foreach(["Classroom Facilities","Laboratory","Library","Hostel","Cleanliness","Internet/Wi-Fi","Other"] as $c): ?><option><?= htmlspecialchars($c) ?></option><?php endforeach; ?></select></div>
<div class="form-row"><label>Description (at least 10 characters)</label><textarea name="description" required minlength="10"></textarea></div>
<button class="btn">Submit Complaint</button> <a class="btn secondary" href="dashboard.php">Cancel</a>
</form></section>
<?php require "../includes/footer.php"; ?>
