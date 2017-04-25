<?php


class Youtube extends Item
{

    public $id;
    public $url;
    public $volumeLevel;

    public function __construct($url, $owner, $volumeLevel, $created = null)
    {
        parent::__construct('youtube', $owner, $created);
        $this->url = $url;
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->id = $query['v'];
        $this->volumeLevel = $volumeLevel;
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