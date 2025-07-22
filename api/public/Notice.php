<?php
class Notice {
    private $conn;
    private $table = 'notices';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($title, $description) {
        $query = "INSERT INTO $this->table (title, description) VALUES (:title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['title' => $title, 'description' => $description]);
        return $this->conn->lastInsertId(); // return new ID
    }

    public function update($id, $title, $description) {
        $query = "UPDATE $this->table SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id, 'title' => $title, 'description' => $description]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function getAll() {
        $query = "SELECT * FROM $this->table ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
