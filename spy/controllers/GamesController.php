<?php

namespace controllers;

use framework\endpoints\BaseEndpoint;
use services\GamesService;

class GamesController extends BaseEndpoint
{
    private GamesService $_gamesService;

    public function __construct()
    {
        parent::__construct();
        $this->_gamesService = new GamesService($this->systemPath);
    }

    public function defaultParams()
    {
        return [
            'method' => "",
            'user' => null,
            'game' => null,
            'userId' => null
        ];
    }

    public function build()
    {
        $game = $this->getParam('game');
        $user = $this->getParam('user');
        $userId = $this->getParam('userId');
        $method = $this->getParam('method');
        if ($method == "getGameInfo") {
            return $this->_gamesService->getGameInfo($game, $user);
        } elseif ($method == "generateGame") {
            return $this->_gamesService->generateGame();
        } elseif ($method == "joinGame") {
            return $this->_gamesService->joinGame($game, $user);
        } elseif ($method == "quitFromGame") {
            return $this->_gamesService->quitFromGame($game, $user);
        } elseif ($method == "startGame") {
            return $this->_gamesService->startGame($game, $user);
        } elseif ($method == "stopGame") {
            return $this->_gamesService->stopGame($game, $user);
        } elseif ($method == "kickUser") {
            return $this->_gamesService->kickUser($game, $user, $userId);
        } elseif ($method == "changeSet") {
            return $this->_gamesService->changeSet($game, $user);
        }

        return null;
    }
}
