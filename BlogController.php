<?php
class BlogController {
    private $blogModel;

    public function __construct($db) {
        $this->blogModel = new Blog($db);
    }

    
    public function addNew() {
        $data = json_decode(file_get_contents("php://input"), true);

        

        if(!empty($data['title']) && !empty($data['content'])&& !empty($data['author'])) {

           $ins_id= $this->blogModel->create($data['title'], $data['content'],$data['author']);

           
            if($ins_id) {
                $stmt = $this->blogModel->get($ins_id);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);

                http_response_code(201); 
                $result["message"]='Blog post created successfully.';
                $result["data"]=$post;

                echo json_encode($result);
            } else {
                http_response_code(500); 
                echo json_encode(['message' => 'Failed to create blog post.']);
            }
        } else {
            http_response_code(400); 
            echo json_encode(['message' => 'Title, content and author are required.']);
        }
    }

    // Retrieve all blog posts
    public function getAll() {
         
        $stmt = $this->blogModel->getAll();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($posts) {
            http_response_code(200); // OK
            echo json_encode($posts);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'No blog posts found.']);
        }
    }

    // Retrieve a single blog post by ID
    public function get($id) {
        $stmt = $this->blogModel->get($id);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if($post) {
            http_response_code(200); // OK
            echo json_encode($post);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Blog post not found.']);
        }
    }

    // Update a blog post
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        if(!empty($data['title']) && !empty($data['content'])) {
            // Check if the post exists
            $stmt = $this->blogModel->get($id);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$post) {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Blog post not found.']);
                return;
            }

            if($this->blogModel->update($id, $data['title'], $data['content'], $data['author'])) {
                $stmt = $this->blogModel->get($id);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);

                http_response_code(200); // OK 
                $result["message"]='Blog post updated successfully.';
                $result["data"]=$post;
                echo json_encode($result);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['message' => 'Failed to update blog post.']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Title and content are required.']);
        }
    }

    // Delete a blog post
    public function delete($id) {
        // Check if the post exists
        $stmt = $this->blogModel->get($id);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$post) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Blog post not found.']);
            return;
        }

        if($this->blogModel->delete($id)) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Blog post deleted successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to delete blog post.']);
        }
    }
}
?>
