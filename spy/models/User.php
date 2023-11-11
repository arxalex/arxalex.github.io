<?php

namespace models;

use framework\models\BaseModel;

class User extends BaseModel
{
    public ?string $pass;

    public function __construct(
        ?int    $id = null,
        ?string $pass = null
    )
    {
        parent::__construct($id);
        $this->pass = $pass;
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

    protected static array $keys = ['id', 'pass'];
}