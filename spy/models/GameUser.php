<?php

namespace models;

use framework\models\BaseModel;

class GameUser extends BaseModel
{
    public ?int $userid = null;
    public ?int $gameid = null;
    public ?bool $spy = null;
    public ?bool $owner = null;

    public function __construct(
        ?int  $id = null,
        ?int  $userid = null,
        ?int  $gameid = null,
        ?bool $spy = null,
        ?bool $owner = null
    )
    {
        parent::__construct($id);
        $this->userid = $userid;
        $this->gameid = $gameid;
        $this->spy = $spy;
        $this->owner = $owner;
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

    protected static array $keys = ['id', 'userid', 'gameid', 'spy', 'owner'];
}