<?php
//create.php

$received_data = json_decode(file_get_contents("php://input"));

function has_duplicate($received_data, $ignore, $connect)
{
	$data = array();
	$query = "SELECT * FROM `" . $received_data->table . "`";
	$i = 0;
	foreach ($received_data->query as $key => $value) {
		if (!in_array($key, $ignore)) {
			if ($i++ == 0) {
				$query .= "WHERE `" . $key . "` = '" . $value . "' ";
			} else {
				$query .= "AND `" . $key . "` = '" . $value . "' ";
			}
		}
	}
	$query .= "
	ORDER BY id DESC";
	$statement = $connect->prepare($query);
	$statement->execute();
	while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $row;
	}
	return count($data) > 0;
}

function insert($received_data, $ignore, $connect)
{
	$data = array();
	if (has_duplicate($received_data, $ignore, $connect)) {
		$data = [
			'response' => false,
		];
	} else {
		$query = "INSERT INTO " . $received_data->table;
		$keys = '';
		$values = '';
		foreach ($received_data->query as $key => $value) {
			$keys .= "`" . $key . "`, ";
			if ($value == "DEFAULT") {
				$values .= "DEFAULT, ";
			} else {
				$values .= "'" . $value . "', ";
			}
		}
		$query .= " (" . substr($keys, 0, -2) . ") VALUES (" . substr($values, 0, -2) . ")";
		$statement = $connect->prepare($query);
		$response = $statement->execute();

		$data = [
			"id" => $connect->lastInsertId(),
			"pass" => $received_data->query->pass,
			"response" => $response,
		];
	}
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_tiktak; charset=utf8", "arxalex_tiktak", "PLACEHOLDER");
		$data = array();
		if ($received_data->table == 'tt_link') {
			$ignore = ['pass', 'linkid', 'name', 'rate'];
		} elseif ($received_data->table == 'tt_members') {
			$ignore = ['id'];
		} elseif ($received_data->table == 'tt_sessions') {
			$ignore = [];
		}
		echo insert($received_data, $ignore, $connect);
	}
} else {
	http_response_code(404);
}
