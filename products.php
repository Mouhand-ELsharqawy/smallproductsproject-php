<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once 'database.php';
include_once 'product.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);

// Handle GET request
if($_SERVER['REQUEST_METHOD'] === 'GET') {
  if(isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->read_single();
    $response = [
      'id' => $product->id,
      'name' => $product->name,
      'weight' => $product->weight,
      'price' => $product->price
    ];
  } else {
    $stmt = $product->read();
    $num = $stmt->rowCount();

    if($num >0) {
      $products_arr = array();
      $products_arr['data'] = array();

      while($row =$stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
          'id' => $id,
          'name' => $name,
          'weight' => $weight,
          'price' => $price
        );

        array_push($products_arr['data'], $product_item);
      }

      $response = $products_arr;
    } else {
      $response = array('message' => 'No products found');
    }
  }
}

// Handle POST request
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents("php://input"));

  $product->name = $data->name;
  $product->weight = $data->weight;
  $product->price = $data->price;

  if($product->create()) {
    $response = array('message' => 'Product created');
  } else {
    $response = array('message' => 'Product not created');
  }
}

// Handle PUT request
if($_SERVER['REQUEST_METHOD'] === 'PUT') {
  $data = json_decode(file_get_contents("php://input"));

  $product->id = $data->id;
  $product->name = $data->name;
  $product->weight = $data->weight;
  $product->price = $data->price;

  if($product->update()) {
    $response = array('message' => 'Product updated');
  } else {
    $response = array('message' => 'Product not updated');
  }
}

// Handle DELETE request
if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  $data = json_decode(file_get_contents("php://input"));

  $product->id = $data->id;

  if($product->delete()) {
    $response = array('message' => 'Product deleted');
  } else {
    $response = array('message' => 'Product not deleted');
  }
}

echo json_encode($response);
?>