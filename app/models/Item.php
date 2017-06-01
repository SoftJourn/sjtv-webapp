<?php

namespace App\Models;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator;

class Item
{

    public $id;
    public $type;
    public $owner;
    public $created;
    public $startTime;
    public $endTime;
    public $startDate;
    public $endDate;
    public $enabled;

    public function __construct($type, $owner, $enabled = true, $created = null)
    {
        $this->id = uniqid();
        $this->type = $type;
        $this->owner = $owner;
        $this->enabled = $enabled;
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
            if(property_exists(get_class($this), $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function setDateAndTime($data)
    {
        $this->startDate = $data['startDate'] ?? Null;
        $this->endDate = $data['endDate'] ?? Null;
        $this->startTime = $data['startTime'] ?? Null;
        $this->endTime = $data['endTime'] ?? Null;
    }
} 