<?php

//get.php

$received_data = json_decode(file_get_contents("php://input"));

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_poipoker; charset=utf8", "arxalex_poipoker", "PLACEHOLDER");
		$query = "DELETE FROM `". $received_data->table ."`";
		$query .= "WHERE `id` = '". $received_data->query->id ."' ";
		$query .= "AND `pass` = '". $received_data->query->pass ."' ";
		foreach ($received_data->query as $key => $value) {
			if($key != 'id' && $key != 'pass'){
				$query .= "AND `" . $key . "` = '" . $value . "' ";
			}
		}
		$statement = $connect->prepare($query);
		$response = $statement->execute();
		$data = [
			'response' => $response
		];
		echo json_encode($data);
	}
} else {
	http_response_code(404);
}
