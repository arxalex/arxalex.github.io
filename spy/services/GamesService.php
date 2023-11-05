<?php

namespace services;

use framework\utils\StringHelper;
use models\Game;
use models\GameUser;
use models\User;
use repository\GamesRepository;
use repository\GameUsersRepository;

class GamesService
{
    private GamesRepository $_gamesRepository;
    private GameUsersRepository $_gameUsersRepository;
    private UsersService $_usersService;
    private SetsService $_setsService;

    public function __construct(string $systemPath)
    {
        $this->_gamesRepository = new GamesRepository();
        $this->_gamesRepository->systemPath = $systemPath;
        $this->_gameUsersRepository = new GameUsersRepository();
        $this->_gameUsersRepository->systemPath = $systemPath;
        $this->_usersService = new UsersService($systemPath);
        $this->_setsService = new SetsService($systemPath);
    }

    public function generateGame(): Game
    {
        $user = new Game(null, StringHelper::generateRsndomString(6), null, null, null);
        $this->_gamesRepository->insertItemToDB($user);
        return $this->_gamesRepository->getLastInsertedItem();
    }

    public function getGameInfo(Game $game, User $user): ?Game
    {
        if (!$this->isUserInGame($game->id, $user)) {
            $gameFromDb = $this->_gamesRepository->getItemFromDB($game->id);
            if ($this->isSpy($game, $user)) {
                $gameFromDb->wordid = null;
            }

            return $gameFromDb;
        }

        return null;
    }

    public function joinGame(Game $game, User $user): bool
    {
        if (!$this->isUserInGame($game->id, $user)) {
            if (!$this->isGameStarted($game)) {
                $gameUser = new GameUser(null, $user->id, $game->id, null, null);
                $this->_gameUsersRepository->insertItemToDB($gameUser);
                return true;
            }
            return false;
        } else {
            return true;
        }
    }

    public function quitFromGame(Game $game, User $user): bool
    {
        if ($this->isUserInGame($game->id, $user) && !$this->isGameStarted($game)) {
            $gameUser = $this->getGameUser($game->id, $user->id);
            $this->_gameUsersRepository->deleteItem($gameUser);
            return true;
        }
        return false;
    }

    public function startGame(Game $game, User $user): bool
    {
        if (!$this->isGameStarted($game) && $this->isUserGameOwner($game->id, $user)) {
            $gameFromDb = $this->_gamesRepository->getItemFromDB($game->id);
            $gameFromDb->started = true;
            $setAndWords = $this->_setsService->getSet($gameFromDb->setid);
            $gameFromDb->wordid = $this->_setsService->getRandomWord($setAndWords)->id;
            $this->_gamesRepository->updateItemInDB($gameFromDb);
            return true;
        }
        return false;
    }

    public function stopGame(Game $game, User $user): bool
    {
        if ($this->isGameStarted($game) && $this->isUserGameOwner($game->id, $user)) {
            $game = $this->_gamesRepository->getItemFromDB($game->id);
            $game->started = false;
            $this->_gamesRepository->updateItemInDB($game);
            return true;
        }
        return false;
    }

    public function kickUser(Game $game, User $owner, int $userId): bool
    {
        if ($this->isUserGameOwner($game->id, $owner)) {
            $user = $this->_usersService->getUser($userId);
            return $this->quitFromGame($game, $user);
        }
        return false;
    }

    public function changeSet(Game $game, User $user): bool
    {
        if (!$this->isGameStarted($game) && $this->isUserGameOwner($game->id, $user)) {
            $gameFromDb = $this->_gamesRepository->getItemFromDB($game->id);
            $gameFromDb->setid = $game->setid;
            $this->_gamesRepository->updateItemInDB($gameFromDb);
            return true;
        }

        return false;
    }

    private function isGameExists(Game $game): bool
    {
        $gameFromDb = $this->_gamesRepository->getItemFromDB($game->id);
        return $gameFromDb != null && $gameFromDb->pass == $game->pass;
    }

    private function isGameStarted(Game $game): bool
    {
        $gameFromDb = $this->_gamesRepository->getItemFromDB($game->id);
        return $gameFromDb != null && $gameFromDb->pass == $game->pass && $gameFromDb->started == true;
    }

    private function isUserInGame(int $gameId, User $user): bool
    {
        if ($this->_usersService->isUserExists($user)) {
            $gameUser = $this->getGameUser($gameId, $user->id);
            return $gameUser != null;
        }
        return false;
    }

    private function isUserGameOwner(int $gameId, User $user): bool
    {
        if ($this->_usersService->isUserExists($user)) {
            $gameUser = $this->getGameUser($gameId, $user->id);
            return $gameUser != null && $gameUser[0]->owner == true;
        }
        return false;
    }

    private function getGameUser(int $gameId, int $userId): ?GameUser
    {
        $gameUsers = $this->_gameUsersRepository->getItemsFromDB(['userid' => [$userId], 'gameid' => [$gameId]]);
        return count($gameUsers) == 1 ? $gameUsers[0] : null;
    }

    private function isSpy(Game $game, User $user): bool
    {
        if ($this->isUserInGame($game->id, $user) && $this->isGameStarted($game)) {
            $gameUser = $this->getGameUser($game->id, $user->id);
            return $gameUser->spy;
        }
        return false;
    }
}
