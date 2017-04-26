<?php


class Item
{

    public $id;
    public $type;
    public $owner;
    public $created;

    public function __construct($type, $owner, $created = null)
    {
        $this->id = uniqid();
        $this->type = $type;
        $this->owner = $owner;
        $this->created = ($created ? $created : time());
    }

    public function purge()
    {

    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function update($data)
    {
        foreach ($data as $key => $value) {
            if(isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
        if (array_key_exists('duration', $data)) {
            $this->duration = floatval($data['duration']);
        }
    }
} 