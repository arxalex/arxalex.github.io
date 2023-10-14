<?php

//get.php

$received_data = json_decode(file_get_contents("php://input"));

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_tiktak; charset=utf8", "arxalex_tiktak", "PLACEHOLDER");
		$data = array();
		$query = "SELECT * FROM `". $received_data->table ."`";
		$query .= "WHERE `id` = '". $received_data->query->id ."' ";
		$query .= "AND `pass` = '". $received_data->query->pass ."' ";
		foreach ($received_data->query as $key => $value) {
			if($key != 'id' && $key != 'pass'){
				$query .= "AND `" . $key . "` = '" . $value . "' ";
			}
		}
		$query .= "
		ORDER BY id DESC";
		$statement = $connect->prepare($query);
		$statement->execute();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
		echo json_encode($data);
	}
} else {
	http_response_code(404);
}