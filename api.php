<?php

// #######################
// Setup Mysqli Connection

function leave(){
	$mysql->close();
	if(!empty($quesult)){
		$quesult->free();
	}
	die();
}

$mysql = new mysqli("mondanzo.de", "monda_postit", "!postit1", "mondanzo_post-it");

$result = json_decode($_POST);



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
		swtich($action){

			// Add User to Database if not exist
			case "put":
				if(count($args) < 2){
					$quesult = $mysql->query("SELECT `name` FROM `postit_user` WHERE `name` = $args[0]");
					if($quesult->field_count > 0){
						echo '{"code": 409, "description":"An user with this name already exist."}';
						leave();
					}
					$quesult->free();
					$mysql->query("INSERT INTO `postit_user`(`name`, `password`) VALUES ($args[0], " . password_hash($args[1], PASSWORD_DEFAULT) . ")");
					echo '{"code": 200, "description": "Action was successful."}';
				}

		}


	case "sessions":
		echo 'Source: sessions';
	case "todos":
		echo 'Source: todos';
	default:
		echo "{\"code\": 404, \"description\": \"Not found\" }";
		leave();
}

?>