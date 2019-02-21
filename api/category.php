<?php 
  
  /*
  if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
  	$action = $_REQUEST['action'];
  } else {
  	$action = '';
  }
  */

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
  include_once '../classes/class.Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $category = new Category($db);

  
 
  switch ($action) {
	case "read":
		// Category read query
		$result = $category->read();

		// Get row count
		$num = $result->rowCount();
		// Check if any categories
		if($num > 0) {
		    // Category array
		    $cat_arr = array();
		    $cat_arr['data'] = array();
		    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		      extract($row);
		      $cat_item = array(
		        'id' => $id,
		        'category_name' => $category_name
		      );
		      // Push to "data"
		      array_push($cat_arr['data'], $cat_item);
		    }
		    // Turn to JSON & output
		    echo json_encode($cat_arr);
		} else {
		    // No Categories
		    echo json_encode(
		      array('message' => 'No Categories Found')
		    );
		}

		break;
	case "single":
		// Get ID
		$category->id = isset($_GET['id']) ? $_GET['id'] : die();

		// Get post
		$category->read_single();

		// Create array
		$category_arr = array(
			'id' => $category->id,
			'category_name' => $category->category_name
		);
		// Make JSON
		print_r(json_encode($category_arr));

		break;
	case "create":		
	  	$category->category_name = $_GET['category_name'];

	  	// Create Category
	  	if($category->create()) {
	   	 	echo json_encode(array('message' => 'Category Created'));
	  	} else {
	    	echo json_encode(array('message' => 'Category Not Created'));
	  	}

		break;
	case "update":		
  		// Set ID to UPDATE
  		$category->id 			 = $_GET['id']; 
 		$category->category_name = $_GET['category_name']; 

  		// Update category
  		if($category->update()) {
    		echo json_encode(array('message' => 'Category Updated'));
  		} else {
    		echo json_encode(array('message' => 'Category not updated'));
  		}

		break;
	case "delete":		
	  	$category->id = $_GET['id'];

	  	// Delete category
	  	if($category->delete()) {
	    	echo json_encode(array('message' => 'Category deleted'));
	  	} else {
	    	echo json_encode(array('message' => 'Category not deleted'));
	  	}

		break;
	default:
		echo json_encode(array('message' => 'No actions found'));
}



  