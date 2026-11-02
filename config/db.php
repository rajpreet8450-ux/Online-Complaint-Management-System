<?php
$dbDir = dirname(__DIR__) . "/data";
$dbDriver = getenv("APP_DB_DRIVER") ?: "auto";
$isProduction = getenv("APP_ENV") === "production";
$mysqlHost = getenv("DB_HOST") ?: "localhost";
$mysqlPort = getenv("DB_PORT") ?: "3306";
$mysqlDb = getenv("DB_NAME") ?: "complaint_management";
$mysqlUser = getenv("DB_USER") ?: "root";
$mysqlPassword = getenv("DB_PASS") ?: "";

try {
    if ($dbDriver === "mysql" || ($dbDriver === "auto" && !$isProduction)) {
        if (!extension_loaded("pdo_mysql") || !in_array("mysql", PDO::getAvailableDrivers(), true)) {
            if ($dbDriver === "mysql" || $isProduction) {
                throw new RuntimeException("The PDO MySQL extension is not available.");
            }
        } else {
            try {
                $pdo = new PDO(
                    "mysql:host=$mysqlHost;port=$mysqlPort;dbname=$mysqlDb;charset=utf8mb4",
                    $mysqlUser,
                    $mysqlPassword,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                if ($dbDriver === "mysql" || $isProduction) {
                    throw $e;
                }
            }
        }
    }

    if (!isset($pdo)) {
        if ($dbDriver === "mysql" || $isProduction || $dbDriver !== "auto" && $dbDriver !== "sqlite") {
            throw new RuntimeException("A MySQL connection is required but could not be established.");
        }
        if (!is_dir($dbDir) && !mkdir($dbDir, 0777, true) && !is_dir($dbDir)) {
            throw new RuntimeException("Could not create the local database directory.");
        }
        $sqlitePath = $dbDir . "/complaint_management.sqlite";
        $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec("PRAGMA foreign_keys = ON");
        $pdo->exec("PRAGMA busy_timeout = 5000");
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('student', 'admin')),
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
        $pdo->exec("CREATE TABLE IF NOT EXISTS complaints (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            category TEXT NOT NULL,
            description TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'Submitted' CHECK(status IN ('Submitted', 'Under Review', 'In Progress', 'Resolved')),
            admin_remarks TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
        $pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
            id TEXT PRIMARY KEY,
            payload BLOB NOT NULL,
            last_activity INTEGER NOT NULL
        )");
    }
} catch (PDOException $e) {
    exit("Database connection failed. Check the database environment variables and confirm the database is running.");
} catch (RuntimeException $e) {
    exit("Database configuration failed. Check the database environment variables and PHP database extensions.");
}

require_once __DIR__ . "/../includes/database_session_handler.php";
session_set_save_handler(new DatabaseSessionHandler($pdo), true);
