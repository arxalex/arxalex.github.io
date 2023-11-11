<?php

namespace models;

use framework\models\BaseModel;

class Set extends BaseModel
{
    public ?string $name;
    public ?int $userid;

    public function __construct(
        ?int    $id = null,
        ?string $name = null,
        ?int    $userid = null
    )
    {
        parent::__construct($id);
        $this->name = $name;
        $this->userid = $userid;
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

    protected static array $keys = ['id', 'name', 'userid'];
}