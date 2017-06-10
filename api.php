<?php

// #######################
// Setup Mysqli Connection
// I'm sorry for the super global D:
$GLOBALS['mysql'] = new mysqli("mondanzo.de", "monda_postit", "!postit1", "mondanzo_post-it");

// If Connection Error: Return Connection Error Code and Error Description.
if($GLOBALS['mysql']->connect_errno){
	echo '{"code":' . $GLOBALS['mysql']->connect_errno . ', "description": "MySQL Error: ' . $GLOBALS['mysql']->connect_error . '"}';
	leave();
}


// ###############################################
// Special Functions to make my life a bit easier.

// Safe end.
function leave(){
	$GLOBALS['mysql']->close();	
	die();
}

// Test if session is valid.
function test_hash(){
	$quesult = $GLOBALS['mysql']->query("SELECT * FROM `postit_user` WHERE `sessionid` = $_COOKIE[sid]");
	if(True){
		return True;
	}
	return False;
}


// change $_POST to $result bc it's fancier
$result = $_POST;



// If post empty: return 200 success code. I mean, why not?
if(empty($result['resource'])){
	echo "{ \"code\": 200, \"description\": \"Everything is fine.\"}";
	leave();
}


// Put the single variables onto unique variables
$res = $result['resource'];
$action = $result['action'];
$args = Array();
if(!empty($result['payload'])){
	$args = $result['payload'];
}

switch ($res){



	// Access Point Users
	case "users":
		switch($action){

			// Add User to Database if not exist
			case "put":
				if(!test_hash()){
					echo '{"code": 401, "description": "You\'re not permitted!"}';
					leave();
				}
				if(count($args) >= 2){

					// Check if user already exist
					$quesult = $GLOBALS['mysql']->query("SELECT `name` FROM `postit_user` WHERE `name` = $args[name]");
					if($quesult->field_count > 0){
						// If exist: return 409 error.
						echo '{"code": 409, "description":"An user with this name already exist."}';
						leave();
					}
					// If not exist: Add User and return 200 success.
					$quesult->free();
					$GLOBALS['mysql']->query("INSERT INTO `postit_user`(`name`, `password`) VALUES ($args[name], " . password_hash($args['password'], PASSWORD_DEFAULT) . ")");
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
		switch($action){

			// LogIn NOT SignUp!
			case "put":
				// Test if session is still alive.
				if(!test_hash()){
					echo '{"code": 200, "description": "Session still alive!", "sid": $_COOKIE[sid]}';
					leave();
				}

				// If too fews parameters: Return 400 error.
				if($args)

				// If Session is not still alive: Try to LogIn
				$quesult = $GLOBALS['mysql']->query("SELECT `user`,`password` WHERE `user` = $args[user]");
				if(!$quesult){
					echo '{"code": 409, "description":"There is no user with this name."}';
					leave();
				}
			default:
				echo "{\"code\": 404, \"description\": \"Not found\" }";
				leave();
		}
		leave();


	// Access Point Todos
	case "todos":
		switch($action){

			//Add Todo entry.
			case "put":
				if(!test_hash()){
					echo '{"code": 401, "description": "You\'re not permitted!"}';
					leave();
				}
				if(count($args) >= 1){
					$quesult = $GLOBALS['mysql']->query("INSERT INTO `postit_todo`(`text`) VALUES ($args[0])");
					echo '{"code": 200, "description": "Action was successful.", "id": $quesult[id]}';
				} else {
					echo '{"code": 400, "description": "Too few parameters."}';
					leave();
				}

			// Update Todo entry
			case "post":
				if(!test_hash()){
					echo '{"code": 401, "description": "You\'re not permitted!"}';
					leave();
				}
				if(count($args) >= 3){
					//Check if entry still exist.
					$quesult = $GLOBALS['mysql']->query("SELECT `id` FROM `postit_todo` WHERE `id` = $args[id]");
					if($quesult->field_count > 0){
						// If exist: return 409 error.
						echo '{"code": 409, "description":"There isn\'t any entry with this id."}';
						leave();
					}
					//If still exist: Change it!
					$quesult->free();
					$quesult->query("UPDATE `postit_todo` SET `text`=$args[text],`todo`=$args[todo] WHERE `id` = $args[id]");
					echo '{"code": 200, "description": "Action was successful."}';
					leave();
				}

			// Delete an entry in the Todo list.
			case "delete":
				if(!test_hash()){
					echo '{"code": 401, "description": "You\'re not permitted!"}';
					leave();
				}
				if(count($args) >= 1){	
					//Check if entry still exist.
					$quesult = $GLOBALS['mysql']->query("SELECT `id` FROM `postit_todo` WHERE `id` = $args[id]");
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
				if(!test_hash()){
					echo '{"code": 401, "description": "You\'re not permitted!"}';
					leave();
				}
				$quesult = $GLOBALS['mysql']->query("SELECT * FROM `postit_todo`");
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