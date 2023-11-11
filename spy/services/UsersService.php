<?php

namespace services;

use framework\utils\StringHelper;
use models\User;
use repository\UsersRepository;

class UsersService
{
    private UsersRepository $_usersRepository;
    public function __construct()
    {
        $this->_usersRepository = new UsersRepository();
    }

    public function generateUser(): User
    {
        $user = new User(null, StringHelper::generateRsndomString(6));
        $this->_usersRepository->insertItemToDB($user);
        return $this->_usersRepository->getLastInsertedItem();
    }

    public function isUserExists(User $user): bool
    {
        $userFromDb = $this->_usersRepository->getItemFromDB($user->id);
        return $userFromDb != null && $userFromDb->pass == $user->pass;
    }

    public function getUser(int $id) : User {
        return $this->_usersRepository->getItemFromDB($id);
    }
}
