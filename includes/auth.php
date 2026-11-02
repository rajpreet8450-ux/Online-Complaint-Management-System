<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function require_login(): void {
    if (empty($_SESSION["user_id"])) {
        header("Location: ../login.php");
        exit;
    }
}
function require_role(string $role): void {
    require_login();
    if (($_SESSION["role"] ?? "") !== $role) {
        http_response_code(403);
        exit("Access denied.");
    }
}
