<?php

namespace models;

use framework\models\BaseModel;

class Game extends BaseModel
{
    public ?string $pass;
    public ?int $setid;
    public ?bool $started;
    public ?int $wordid;

    public function __construct(
        ?int    $id = null,
        ?string $pass = null,
        ?int    $setid = null,
        ?bool   $started = null,
        ?int    $wordid = null
    )
    {
        parent::__construct($id);
        $this->pass = $pass;
        $this->setid = $setid;
        $this->started = $started;
        $this->wordid = $wordid;
    }

    public static function arrayToObject(array $DTO): self
    {
        $object = new self();
        foreach ($DTO as $key => $value) {
            if (key_exists($key, self::$keys)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    protected static array $keys = ['id', 'pass', 'setid', 'started', 'wordid'];
}