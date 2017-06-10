<?php

// #######################
// Setup Mysqli Connection

$mysql = new mysqli("mondanzo.de", "monda_postit", "!postit1", "mondanzo_post-it");


// Safe end.
function leave(){
	$mysql->close();
	if(!empty($quesult)){
		$quesult->free();
	}	
	die();
}

// change $_POST to $result bc it's fancier
$result = $_POST;


// If Connection Error: Return Connection Error Code and Error Description.
if($mysql->connect_errno){
	echo '{"code":' . $mysql->connect_errno . ', "description": "MySQL Error: ' . $mysql->connect_error . '"}';
	leave();
}


if(empty($result['resource'])){
	echo "{ \"code\": 200, \"description\": \"Everything is fine.\"}";
	leave();
}

$res = $result['resource'];
$action = $result['action'];
if(!empty($result['payload'])){
	$args = $result['payload'];
}

switch ($res){



	// Access Point Users
	case "users":
		switch($action){

			// Add User to Database if not exist
			case "put":
				if(count($args) >= 2){

					// Check if user already exist
					$quesult = $mysql->query("SELECT `name` FROM `postit_user` WHERE `name` = $args[name]");
					if($quesult->field_count > 0){
						// If exist: return 409 error.
						echo '{"code": 409, "description":"An user with this name already exist."}';
						leave();
					}
					// If not exist: Add User and return 200 success.
					$quesult->free();
					$mysql->query("INSERT INTO `postit_user`(`name`, `password`) VALUES ($args[name], " . password_hash($args['password'], PASSWORD_DEFAULT) . ")");
					echo '{"code": 200, "description": "Action was successful."}';
					leave();
				} else {
					// If too fews parameters: Return 400 error.
					echo '{"code": 400, "description": "Too few parameters."}';
					leave();
				}

				// If not found: Return 404 error.
			default:
				echo "{\"code\": 404, \"description\": \"Not found\" }";
				leave();

		}


	case "sessions":
		echo 'Source: sessions';
		leave();


	// Access Point Todos
	case "todos":
		switch($action){

			//Add Todo point.
			case "put":
				if(count($args) >= 1){
					$quesult = $mysql->query("INSERT INTO `postit_todo`(`text`) VALUES ($args[0])");
					echo '{"code": 200, "description": "Action was successful.", "id": $quesult[id]}';
				} else {
					echo '{"code": 400, "description": "Too few parameters."}';
					leave();
				}

				// Delete an entry in the Todo list.
			case "delete":
				if(count($args) >= 1){
					//Check if entry still exist.
					$quesult = $mysql->query("SELECT `id` FROM `postit_todo` WHERE `id` = $args[id]");
					if($quesult->field_count > 0){
						// If exist: return 409 error.
						echo '{"code": 409, "description":"There isn\'t any entry with this id."}';
						leave();
					}
					//If still exist: Delete it!
					$quesult->free();
					$quesult->query("DELETE FROM `postit_todo` WHERE `id` = $args[id]");
					echo '{"code": 200, "description": "Action was successful."}';
					leave();
				} else {
					// If too fews parameters: Return 400 error.
					echo '{"code": 400, "description": "Too few parameters."}';
					leave();
				}

			// Get all Entries in the Todo list and return it as json
			case "get":
				$quesult = $mysql->query("SELECT * FROM `postit_todo`");
				$entries = array();
				foreach($quesult->fetch_all(MYSQLI_NUM) as $entry){
					$e['id'] = $entry[0];
					$e['text'] = $entry[1];
					$e['todo'] = $entry[2];
					$entries[] = $e;
				}
				echo '{"code": 200, "description": "Action was successful.", "entries":'. json_encode($entries) .' }';
				leave();

			default:
				echo "{\"code\": 404, \"description\": \"Not found\" }";
				leave();
		}
	default:
		// If resource not exist.
		echo "{\"code\": 404, \"description\": \"Not found\" }";
		leave();
}

?>