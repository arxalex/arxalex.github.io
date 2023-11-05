<?php

namespace models;

use framework\models\BaseModel;

class Word extends BaseModel {
    public ?int $setid;
    public ?string $word;
    public function __construct(
        ?int $id = null,
        ?int $setid = null,
        ?string $word = null
        )
    {
        parent::__construct($id);
        $this->setid = $setid;
        $this->word = $word;
    }
}