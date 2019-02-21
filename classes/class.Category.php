<?php
  class Category {

    // DB Stuff
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category_name;
    public $created_at;
    
    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get categories
    public function read() {
      // Create query
      $query = 'SELECT id, category_name, created_at
                FROM ' . $this->table . '
                ORDER BY created_at DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get Single Category
    public function read_single(){
    
      $query = 'SELECT id, category_name
              FROM ' . $this->table . '
              WHERE id = ?
              LIMIT 0,1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);
      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // set properties
      $this->id            = $row['id'];
      $this->category_name = $row['category_name'];
    }

    // Create Category
    public function create() {
      
      $query = 'INSERT INTO ' .
        $this->table . '
      SET
        category_name = :category_name';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->category_name = htmlspecialchars(strip_tags($this->category_name));

      // Bind data
      $stmt-> bindParam(':category_name', $this->category_name);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: $s.\n", $stmt->error);
      return false;
    }

    // Update Category
    public function update() {
     
      $query = 'UPDATE ' .
        $this->table . '
      SET
        category_name = :category_name
        WHERE
        id = :id';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->category_name = htmlspecialchars(strip_tags($this->category_name));
      $this->id            = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt-> bindParam(':category_name', $this->category_name);
      $stmt-> bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: $s.\n", $stmt->error);
      return false;
    }
    // Delete Category
    public function delete() {
     
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';      
      $stmt  = $this->conn->prepare($query);

      // clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind Data
      $stmt-> bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: $s.\n", $stmt->error);
      return false;
    }

  }