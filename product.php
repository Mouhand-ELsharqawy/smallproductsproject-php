<?php
class Product {
  private $conn;
  private $table = 'products';

  public $id;
  public $name;
  public $weight;
  public $price;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function read() {
    $query = 'SELECT * FROM ' . $this->table;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
  }

  public function read_single() {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->name = $row['name'];
    $this->weight = $row['weight'];
    $this->price = $row['price'];
  
  }

  public function create() {
    $query = 'INSERT INTO ' . $this->table . ' SET name = :name, price = :price, weight = :weight';

    $stmt = $this->conn->prepare($query);

    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->weight = htmlspecialchars(strip_tags($this->weight));
    $this->price = htmlspecialchars(strip_tags($this->price));

    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':weight', $this->weight);
   

    if($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function update() {
    $query = 'UPDATE ' . $this->table . ' SET name = :name, description = :description, price = :price WHERE id = :id';

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->weight = htmlspecialchars(strip_tags($this->weight));
    $this->price = htmlspecialchars(strip_tags($this->price));

    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':weight', $this->weight);
    $stmt->bindParam(':price', $this->price);

    if($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function delete() {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':id', $this->id);

    if($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}

?>
