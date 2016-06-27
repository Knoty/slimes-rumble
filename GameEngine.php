<?php

class GameEngine
{
	/** @var BlobDB */
	private $db;

	public $hp_change;

	function setDB($db)
	{
		$this->db = $db;
	}

	function create()
	{
		return $this->db->createBlob(mt_rand(15, 26));
	}

	function look($blob_number)
	{
		return $this->db->lookBlob($blob_number);
	}

	function kick($blob_number)
	{
		$this->hp_change = -mt_rand(1, 11);
		$this->db->modifyBlob($blob_number, $this->hp_change);
	}

	function lastModify()
	{
		return abs($this->hp_change);
	}

	function heal($blob_number)
	{
		$this->hp_change = mt_rand(1, 11);
		$this->db->modifyBlob($blob_number, $this->hp_change);
	}

	function save()
	{
		$data = $this->db->massLook();
		$filename = "state.db";
		$file = fopen($filename, "w");
		fwrite($file, json_encode($data));
	}

	function restore()
	{
		$filename = "state.db";
		$restore_raw_data = file_get_contents($filename);
		$restore_data = json_decode($restore_raw_data);
		$db = new BlobDB();
		foreach($restore_data as $hp)
			$db->createBlob($hp);
		$this->db = $db;
	}

	function getWorldState()
	{
		return $this->db->massLook();
	}
}
