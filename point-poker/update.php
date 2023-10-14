<?php
//update.php

$received_data = json_decode(file_get_contents("php://input"));

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_poipoker; charset=utf8", "arxalex_poipoker", "PLACEHOLDER");
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
		if($received_data->table == "pp_link"){
			$query .= " AND `memberid` = '". $received_data->query->memberid ."'";
		}
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