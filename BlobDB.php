<?php

class BlobDB
{
    private $blob_db = [];

    public function createBlob($blob_hp)
    {
        $new_count = array_push($this->blob_db, $blob_hp);
        return $new_count - 1;
    }

    public function modifyBlob($blob_number, $hp_change)
    {
        if(!is_numeric($blob_number))
            throw new InvalidArgumentException('$blob_number must be integer');
        if(!is_numeric($hp_change))
            throw new InvalidArgumentException('$hp_change must be integer');
        $this->blob_db[$blob_number] += $hp_change;
    }


    public function lookBlob($blob_number)
    {
        return $this->blob_db[$blob_number];
    }

    public function massLook()
    {
        return $this->blob_db;
    }
}