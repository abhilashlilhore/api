<?php
class Blog {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function create($title, $content,$author) {
        $query = 'INSERT INTO ' . $this->table . ' (title, content,author) VALUES (:title, :content, :author)';
        $stmt = $this->conn->prepare($query);

    
        $title = htmlspecialchars(strip_tags($title));
        $content = htmlspecialchars(strip_tags($content));

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);

        if ($stmt->execute()) {
            
            return $this->conn->lastInsertId();
        }
    
        return false;
    }

    // Retrieve all blog posts
    public function getAll() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Retrieve a single blog post by ID
    public function get($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    // Update a blog post
    public function update($id, $title, $content,$author) {
        $query = 'UPDATE ' . $this->table . ' SET title = :title, content = :content, author = :author WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $title = htmlspecialchars(strip_tags($title));
        $content = htmlspecialchars(strip_tags($content));

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Delete a blog post
    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
