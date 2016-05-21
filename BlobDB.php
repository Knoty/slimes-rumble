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