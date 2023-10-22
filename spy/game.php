<?php

$received_data = json_decode(file_get_contents("php://input"));

$usersTable = 'sg_users';
$gamesTable = 'sg_games';
$gamesUsersTable = 'sg_users_games';

function generateRsndomString(int $length = 6): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function mformat($inp)
{
    if (is_array($inp))
        return array_map(__METHOD__, $inp);

    if (!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}

function isUserExists(int $id, string $pass, PDO $connect, string $usersTable): bool
{
    $query = "SELECT count(*) FROM `$usersTable`";
    $query .= "WHERE `id` = '" . mformat($id) . "'";
    $query .= "AND `pass` = '" . mformat($pass) . "'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response;
}

function isGameExists(int $id, string $pass, PDO $connect, string $gamesTable): bool
{
    $query = "SELECT count(*) FROM `$gamesTable`";
    $query .= "WHERE `id` = '" . mformat($id) . "'";
    $query .= "AND `pass` = '" . mformat($pass) . "'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response == 1;
}

function isGameStarted(int $id, string $pass, PDO $connect, string $gamesTable): bool
{
    $query = "SELECT count(*) FROM `$gamesTable`";
    $query .= "WHERE `id` = '" . mformat($id) . "'";
    $query .= "AND `pass` = '" . mformat($pass) . "'";
    $query .= "AND `started` = '1'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response == 1;
}

function isUserInGame(int $userId, string $gameId, PDO $connect, string $gamesUsersTable): bool
{
    $query = "SELECT count(*) FROM `$gamesUsersTable`";
    $query .= "WHERE `gameid` = '" . mformat($gameId) . "'";
    $query .= "AND `userid` = '" . mformat($userId) . "'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response == 1;
}

function joinGame(int $userId, string $gameId, PDO $connect, string $gamesUsersTable, bool $isOwner = false) : int
{
    if (!isUserInGame($userId, $gameId, $connect, $gamesUsersTable)) {
        $query = "INSERT INTO `$gamesUsersTable` VALUES";
        $owner = $isOwner ? "'1'" : "DEFAULT";
        $query .= "(DEFAULT, '" . mformat($userId) . "', '" . mformat($gameId) . "', DEFAULT, $owner)";

        $statement = $connect->prepare($query);
        $response = $statement->execute();
    } else {
        $response = 1;
    }

    return $response;
}

function isOwner(int $userId, string $gameId, PDO $connect, string $gamesUsersTable): bool
{
    $query = "SELECT count(*) FROM `$gamesUsersTable`";
    $query .= "WHERE `gameid` = '" . mformat($gameId) . "'";
    $query .= "AND `userid` = '" . mformat($userId) . "'";
    $query .= "AND `owner` = '1'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response == 1;
}

function startStopGame(string $gameId, bool $start, PDO $connect, string $gamesTable): int
{
    $query = "UPDATE `$gamesTable`";
    $startGame = $start ? "'1'" : "'0'"; 
    $query .= "SET `started` = $startGame";
    $query .= "WHERE `gameid` = '" . mformat($gameId) . "'";

    $statement = $connect->prepare($query);
    $response = $statement->execute();

    return $response;
}

if ($received_data->method != '') {
    $connect = new PDO("mysql:host=localhost; dbname=arxalex_spygame; charset=utf8", "arxalex_spygame", "PLACEHOLDER");

    if ($received_data->method == 'generateUser') {
        $query = "INSERT INTO `$usersTable` VALUES";
        $pass = generateRsndomString(6);
        $query .= "(DEFAULT, '$pass')";

        $statement = $connect->prepare($query);
        $response = $statement->execute();

        $data = [
            "user" => [
                "id" => $connect->lastInsertId(),
                "pass" => $pass
            ],
            "response" => $response
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } else if ($received_data->method == 'generateGame') {
        $query = "INSERT INTO `$gamesTable` VALUES";
        $pass = generateRsndomString(6);
        $query .= "(DEFAULT, '$pass', DEFAULT, DEFAULT)";

        $statement = $connect->prepare($query);
        $responseCreate = $statement->execute();

        $responseJoin = joinGame($received_data->user->id, $received_data->game->id, $connect, $gamesUsersTable, true);

        $data = [
            "game" => [
                "id" => $connect->lastInsertId(),
                "pass" => $pass
            ],
            "responseCreate" => $responseCreate,
            "responseJoin" => $responseJoin
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } else if ($received_data->method == 'isUserExists') {
        $response = isUserExists($received_data->id, $received_data->pass, $connect, $usersTable);

        $data = [
            "response" => $response
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } else if ($received_data->method == 'joinGame') {
        if (
            isGameExists($received_data->game->id, $received_data->game->pass, $connect, $gamesTable) &&
            isUserExists($received_data->user->id, $received_data->user->pass, $connect, $usersTable)
        ) {
            $response = joinGame($received_data->user->id, $received_data->game->id, $connect, $gamesUsersTable);

            $data = [
                "response" => $response
            ];

            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
        }
    } else if ($received_data->method == 'quitFromGame') {
        if (
            isGameExists($received_data->game->id, $received_data->game->pass, $connect, $gamesTable) &&
            isUserExists($received_data->user->id, $received_data->user->pass, $connect, $usersTable)
        ) {
            if (isUserInGame($received_data->user->id, $received_data->game->id, $connect, $gamesUsersTable)) {
                $query = "DELETE FROM `$gamesUsersTable`";
                $query .= "WHERE `gameid` = '" . mformat($gameId) . "'";
                $query .= "AND `userid` = '" . mformat($userId) . "'";

                $statement = $connect->prepare($query);
                $response = $statement->execute();
            } else {
                $response = 1;
            }

            $data = [
                "response" => $response
            ];

            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
        }
    } else if ($received_data->method == 'startGame') {
        if (
            isUserExists($received_data->user->id, $received_data->user->pass, $connect, $usersTable) &&
            isGameExists($received_data->game->id, $received_data->game->pass, $connect, $gamesTable) &&
            isOwner($received_data->user->id, $received_data->game->id, $connect, $gamesUsersTable)
        ) {
            $response = startStopGame($received_data->game->id, true, $connect, $gamesTable);

            $data = [
                "response" => $response
            ];

            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
        }
    } else if ($received_data->method == 'stopGame') {
        if (
            isUserExists($received_data->user->id, $received_data->user->pass, $connect, $usersTable) &&
            isGameExists($received_data->game->id, $received_data->game->pass, $connect, $gamesTable) &&
            isOwner($received_data->user->id, $received_data->game->id, $connect, $gamesUsersTable)
        ) {
            $response = startStopGame($received_data->game->id, false, $connect, $gamesTable);

            $data = [
                "response" => $response
            ];

            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
        }
    } else if ($received_data->method == 'isGameStarted') {
        $response = isGameStarted($received_data->game->id, $received_data->game->pass, $connect, $gamesTable);
        $data = [
            "response" => $response
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(404);
}
