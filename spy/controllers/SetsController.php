<?php

namespace controllers;

use framework\endpoints\BaseEndpoint;
use services\SetsService;

class SetsController extends BaseEndpoint
{
    private SetsService $_setsService;
    public function __construct()
    {
        parent::__construct();
        $this->_setsService = new SetsService($this->systemPath);
    }
    public function defaultParams()
    {
        return [
            'method' => "",
            'set' => null, // Set
            'user' => null, // User
            'words' => [] // array of Word
        ];
    }
    public function build()
    {
        $set = $this->getParam('set');
        $words = $this->getParam('words');
        $user = $this->getParam('user');
        $method = $this->getParam('method');

        if ($method == "getSet") {
            return $this->_setsService->getSet($set->id);
        } elseif ($method == "createSet") {
            return $this->_setsService->createSet($set->name, $user);
        } elseif ($method == "updateSet") {
            return $this->_setsService->updateSet($set, $words, $user);
        } elseif ($method == "deleteSet") {
            return $this->_setsService->deleteSet($set->id, $user);
        }

        return null;

    }
}
