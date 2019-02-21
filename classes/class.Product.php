<?php 
  class Product {
    // DB stuff
    private $conn;
    private $table = 'products';
    // Post Properties
    public $id;
    public $category_id;
    public $category_name;
    public $pname;
    public $descp;
    public $barcode;
    public $price;
    public $created_at;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get All Products
    public function read() {
      
      $query = 'SELECT c.category_name, p.id, p.category_id, p.pname, p.descp, p.barcode, p.price, p.created_at
                FROM ' . $this->table . ' p
                  LEFT JOIN
                    categories c ON p.category_id = c.id
                  ORDER BY
                    p.created_at DESC';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get Single Product
    public function read_single() {
      // Create query
      $query = 'SELECT c.category_name, p.id, p.category_id, p.pname, p.descp, p.barcode, p.price, p.created_at
                                FROM ' . $this->table . ' p
                                LEFT JOIN
                                  categories c ON p.category_id = c.id
                                WHERE
                                  p.id = ?
                                LIMIT 0,1';
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties
      $this->pname          = $row['pname'];
      $this->descp          = $row['descp'];
      $this->barcode        = $row['barcode'];
      $this->price          = $row['price'];
      $this->category_id    = $row['category_id'];
      $this->category_name  = $row['category_name'];
    }

    // Create Product
    public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . ' SET pname = :pname, descp = :descp, barcode = :barcode, price = :price, category_id = :category_id';
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Clean data
      $this->pname        = htmlspecialchars(strip_tags($this->pname));
      $this->descp        = htmlspecialchars(strip_tags($this->descp));
      $this->barcode      = htmlspecialchars(strip_tags($this->barcode));
      $this->price        = htmlspecialchars(strip_tags($this->price));
      $this->category_id  = htmlspecialchars(strip_tags($this->category_id));

      // Bind data
      $stmt->bindParam(':pname', $this->pname);
      $stmt->bindParam(':descp', $this->descp);
      $stmt->bindParam(':barcode', $this->barcode);
      $stmt->bindParam(':price', $this->price); 
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);
      return false;
    }

    // Update Product
    public function update() {
      // Create query
      $query = 'UPDATE ' . $this->table . '
                            SET pname = :pname, descp = :descp, barcode = :barcode, price = :price, category_id = :category_id
                            WHERE id = :id';
      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Clean data
      $this->pname        = htmlspecialchars(strip_tags($this->pname));
      $this->descp        = htmlspecialchars(strip_tags($this->descp));
      $this->barcode      = htmlspecialchars(strip_tags($this->barcode));
      $this->price        = htmlspecialchars(strip_tags($this->price));
      $this->category_id  = htmlspecialchars(strip_tags($this->category_id));
      $this->id           = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':pname', $this->pname);
      $stmt->bindParam(':descp', $this->descp);
      $stmt->bindParam(':barcode', $this->barcode);
      $stmt->bindParam(':price', $this->price); 
      $stmt->bindParam(':category_id', $this->category_id);
      $stmt->bindParam(':id', $this->id);
      // Execute query
      if($stmt->execute()) {
        return true;
      }
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);
      return false;
    }

    // Delete Product
    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';     
      $stmt  = $this->conn->prepare($query);
     
      $this->id = htmlspecialchars(strip_tags($this->id));
      
      $stmt->bindParam(':id', $this->id);
     
      if($stmt->execute()) {
        return true;
      }
     
      printf("Error: %s.\n", $stmt->error);
      return false;
    }
    
  }