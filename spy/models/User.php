<?php

namespace models;

use framework\models\BaseModel;

class User extends BaseModel {
    public ?string $pass;
    public function __construct(
        ?int $id = null,
        ?string $pass = null
        )
    {
        parent::__construct($id);
        $this->pass = $pass;
    }
}