<?php
//update.php

$received_data = json_decode(file_get_contents("php://input"));

function mysql_escape_mimic($inp)
{
    if (is_array($inp))
        return array_map(__METHOD__, $inp);

    if (!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}

if ($received_data->table != '') {
	if ($received_data->query != '') {
		$connect = new PDO("mysql:host=localhost; dbname=arxalex_tiktak; charset=utf8", "arxalex_tiktak", "PLACEHOLDER");
		$data = array();
		$query = "
		UPDATE " . mysql_escape_mimic($received_data->table) . "
		SET ";
		foreach ($received_data->query as $key => $value) {
			if ($key != "id" && $key != "pass") {
				$query .= "`" . mysql_escape_mimic($key) . "` = '" . mysql_escape_mimic($value) . "', ";
			}
		}
		$query = substr($query, 0, -2);
		$query .= "WHERE `id` = '" . mysql_escape_mimic($received_data->query->id) . "' AND `pass` = '" . mysql_escape_mimic($received_data->query->pass) . "'";
		if($received_data->table == "tt_link"){
			$query .= " AND `memberid` = '". mysql_escape_mimic($received_data->query->memberid) ."'";
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