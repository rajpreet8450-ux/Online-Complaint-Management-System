<?php
final class DatabaseSessionHandler implements SessionHandlerInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $statement = $this->pdo->prepare("SELECT payload, last_activity FROM sessions WHERE id = ?");
        $statement->execute([$id]);
        $session = $statement->fetch();
        $statement->closeCursor();
        if (!$session || (int)$session["last_activity"] < time() - (int)ini_get("session.gc_maxlifetime")) {
            return "";
        }
        return $session["payload"];
    }

    public function write(string $id, string $data): bool
    {
        $statement = $this->pdo->prepare(
            "REPLACE INTO sessions (id, payload, last_activity) VALUES (?, ?, ?)"
        );
        $saved = $statement->execute([$id, $data, time()]);
        $statement->closeCursor();
        return $saved;
    }

    public function destroy(string $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
        $deleted = $statement->execute([$id]);
        $statement->closeCursor();
        return $deleted;
    }

    public function gc(int $max_lifetime): int|false
    {
        $statement = $this->pdo->prepare("DELETE FROM sessions WHERE last_activity < ?");
        $statement->execute([time() - $max_lifetime]);
        $deleted = $statement->rowCount();
        $statement->closeCursor();
        return $deleted;
    }
}
