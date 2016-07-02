<?php

class BlobDB
{
    private $blob_name_db = [];
    private $blob_db = [];

    public function createBlob($blob_hp, $blob_name = 'name')
    {
        array_push($this->blob_name_db, $blob_name);
        $new_count = array_push($this->blob_db, $blob_hp);
        return $new_count - 1;
    }

    public function modifyBlob($blob_number, $hp_change)
    {
        if(!is_numeric($blob_number))
            throw new InvalidArgumentException('$blob_number must be integer');

        if(!is_numeric($hp_change))
            throw new InvalidArgumentException('$hp_change must be integer');

        if(!array_key_exists($blob_number, $this->blob_db))
            throw new LogicException('no such blob:' . $blob_number);

        $this->blob_db[$blob_number] += $hp_change;
    }


    public function lookBlob($blob_number)
    {
        return [
            $this->blob_name_db[$blob_number],
            $this->blob_db[$blob_number]
        ];
    }

    public function massLook()
    {
        return $this->blob_db;
    }

//    public function massLookNames()
//    {
//        return $this->blob_name_db;
//    }
}