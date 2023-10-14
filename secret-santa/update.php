<?php
//update.php

$received_data = json_decode(file_get_contents("php://input"));

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_secsanta; charset=utf8", "arxalex_secsanta", "PLACEHOLDER");
		$data = array();
		$query = "
		UPDATE " . $received_data->table . "
		SET ";
		foreach ($received_data->query as $key => $value) {
			if ($key != "id" && $key != "pass") {
				$query .= "`" . $key . "` = '" . $value . "', ";
			}
		}
		$query = substr($query, 0, -2);
		$query .= "WHERE `id` = '" . $received_data->query->id . "' AND `pass` = '" . $received_data->query->pass . "'";
		$statement = $connect->prepare($query);
		$response = $statement->execute();
		$data = [
			'response' => $response,
		];
		echo json_encode($data);
	}
} else {
	http_response_code(404);
}

/*$crypted_pass = crypt($password);

//$pass_from_login is the user entered password
//$crypted_pass is the encryption
if(crypt($pass_from_login,$crypted_pass)) == $crypted_pass)
{
   echo("hello user!")
}*/