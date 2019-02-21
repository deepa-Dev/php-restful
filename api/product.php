<?php 
  
  
  $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  
  if($action == 'create'){
  	header('Access-Control-Allow-Methods: POST');
  	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  } else if($action == 'update'){
  	header('Access-Control-Allow-Methods: PUT');
  	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  } else if($action == 'delete'){
  	header('Access-Control-Allow-Methods: DELETE');
  	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  }

  include_once '../classes/class.Database.php';
  include_once '../classes/class.Product.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate product object
  $product = new Product($db);

  
 
  switch ($action) {
	case "read":
		// Product read query
		$result = $product->read();

		// Get row count
		$num = $result->rowCount();
		// Check if any categories
		if($num > 0) {
		    // Product array
		    $prod_arr = array();
		    $prod_arr['data'] = array();
		    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		      extract($row);
		      $cat_item = array(
		        'id' => $id,
		        'pname' => $pname
		      );
		      // Push to "data"
		      array_push($prod_arr['data'], $cat_item);
		    }
		    // Turn to JSON & output
		    echo json_encode($prod_arr);
		} else {
		    // No Products
		    echo json_encode(
		      array('message' => 'No Products Found')
		    );
		}

		break;
	case "single":		
		$product->id = isset($_GET['id']) ? $_GET['id'] : die();		
		$product->read_single();

		// Create array
		$product_arr = array(
			'id' 			=> $product->id,
			'product_name'  => $product->pname,
			'category_name' => $product->category_name,
			'descp' 		=> $product->descp,
			'barcode' 		=> $product->barcode,
			'price'			=> $product->price,
		);
		// Make JSON
		print_r(json_encode($product_arr));

		break;
	case "create":		
	  	$product->pname 		= $_GET['pname'];
	  	$product->descp 		= $_GET['descp'];
	  	$product->barcode 	  	= $_GET['barcode'];
	  	$product->price 	  	= $_GET['price'];
	  	$product->category_id 	= $_GET['category_id'];

	  	// Create product
	  	if($product->create()) {
	   	 	echo json_encode(array('message' => 'Product Created'));
	  	} else {
	    	echo json_encode(array('message' => 'Product Not Created'));
	  	}

		break;
	case "update":		
  		// Set ID to UPDATE
  		$product->id 			= $_GET['id']; 
 		$product->pname 		= $_GET['pname'];
	  	$product->descp 		= $_GET['descp'];
	  	$product->barcode 	  	= $_GET['barcode'];
	  	$product->price 	  	= $_GET['price'];
	  	$product->category_id 	= $_GET['category_id'];

  		// Update product
  		if($product->update()) {
    		echo json_encode(array('message' => 'Product Updated'));
  		} else {
    		echo json_encode(array('message' => 'Product not updated'));
  		}
		break;

	case "delete":		
	  	$product->id = $_GET['id'];
	  	
	  	if($product->delete()) {
	    	echo json_encode(array('message' => 'Product deleted'));
	  	} else {
	    	echo json_encode(array('message' => 'Product not deleted'));
	  	}

		break;
	default:		
		echo json_encode(array('message' => 'No actions found'));
}  