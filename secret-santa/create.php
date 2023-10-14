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

function get_pass_by_id($id, $connect)
{
	$query = "SELECT * FROM `ss_members` WHERE `id` = '" . $id . "' ORDER BY id DESC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$data = $statement->fetch(PDO::FETCH_ASSOC);
	$data_t = $data['pass'];
	return $data_t;
}

function get_member_data($id, $connect)
{

	$query = "
	SELECT * FROM `ss_members` WHERE `id` = '" . $id . "' ORDER BY id DESC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$data = $statement->fetch(PDO::FETCH_ASSOC);
	$data_t = array();
	$data_t = [
		'id' => $data['id'],
		'email' => $data['email'],
		'phone' => $data['phone'],
		'first_name' => $data['first_name'],
		'last_name' => $data['last_name'],
		'address' => $data['address'],
        'wants'	=> $data['wants'],
	];
	return json_encode($data_t, JSON_UNESCAPED_UNICODE);
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
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_secsanta; charset=utf8", "arxalex_secsanta", "PLACEHOLDER");
		$data = array();
		if ($received_data->table == 'ss_link') {
			$ignore = ['pass', 'linkid', 'name'];
		} elseif ($received_data->table == 'ss_members') {
			$ignore = ['id'];
		} elseif ($received_data->table == 'ss_sessions') {
			$ignore = [];
		} elseif ($received_data->table == 'ss_random') {
			$ignore = ['randomid', 'data', 'pass'];
		} elseif ($received_data->table == 'ss_random') {
			$ignore = ['pass', 'memberid', 'data', 'randomid'];
		}

		if ($received_data->table == 'ss_random') {
			$rids = $received_data->randids;
			$rrids = array();
			$correct = false;
			while (!$correct) {
				for ($i = 0; $i < count($rids) && !$correct; $i++) {
					$rand = $rids[rand(0, count($rids) - 1)];
					while ($rand == $rids[$i] || in_array($rand, $rrids)) {
						$rand = $rids[rand(0, count($rids) - 1)];
					}
					$rrids[$i] = $rand;
				}
				$correct = true;
				for ($i = 0; $i < count($rids) && $correct; $i++) {
					$correct = $rrids[$i] != $rids[$i];
				}
			}
			for ($i = 0; $i < count($rids); $i++) {
				$data_to_i = new stdClass();
				$data_to_i = (object) [
					'table' => 'ss_random',
					'query' => [
						'id' => $rids[$i],
						'pass' => get_pass_by_id($rids[$i], $connect),
						'sessionid' => $received_data->query->sessionid,
						'memberid' => $rrids[$i],
						'data' => get_member_data($rrids[$i], $connect),
						'randomid' => 'DEFAULT',
					],
				];

				insert($data_to_i, $ignore, $connect);
			}
			echo true;
		} else {
			echo insert($received_data, $ignore, $connect);
		}
	}
} else {
	http_response_code(404);
}
