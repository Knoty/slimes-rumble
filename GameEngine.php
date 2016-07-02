<?php

require_once ('./RandomDamageAmountGenerator.php');
require_once ('./BlobDB.php');
require_once ('./Blob.php');

class GameEngine
{
	public $hp_change;

	/** @var BlobDB */
	private $db;

	/** @var DamageAmountGenerator */
	private $damage_amount_generator;

	function setDB($db)
	{
		$this->db = $db;
	}

	function setDamageAmountGenerator($generator)
	{
		$this->damage_amount_generator = $generator;
	}

	/** @return DamageAmountGenerator */
	private function getDamageAmountGenerator()
	{
		if ($this->damage_amount_generator == null)
			$this->damage_amount_generator = new RandomDamageAmountGenerator();

		return $this->damage_amount_generator;
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
		$this->hp_change = -$this->getDamageAmount();
		$this->db->modifyBlob($blob_number, $this->hp_change);
	}

	private function getDamageAmount()
	{
		return $this->getDamageAmountGenerator()->getDamageAmount();
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

	function save($filename = 'state.db')
	{
        file_put_contents($filename, json_encode($this->db->massLook()));
	}

	function restore($filename = "state.db")
	{
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

	/** @return Blob[] */
	public function getFullData()
	{
		$raw = $this->db->massLook();
		if ($raw == [])
			return [];

		$blobs = [];
		foreach ($raw as $record)
		{
			$blobs[] = new Blob('name', $record);
		}

		return $blobs;
	}
}
