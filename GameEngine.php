<?php

require_once ('./RandomDamageAmountGenerator.php');
require_once ('./BlobDB.php');
require_once ('./Blob.php');
require_once ('./NonRandomNameGenerator.php');
require_once ('./RandomNameGenerator.php');

class GameEngine
{
	public $hp_change;

	/** @var BlobDB */
	private $db;

	/** @var DamageAmountGenerator */
	private $damage_amount_generator;

	private $name_generator;

	function setDB($db)
	{
		$this->db = $db;
	}

	function setDamageAmountGenerator($generator)
	{
		$this->damage_amount_generator = $generator;
	}

	function setNameGenerator($generator)
	{
		$this->name_generator = $generator;
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
		$blob_name = $this->makeBlobName();
		return $this->db->createBlob(mt_rand(15, 26), $blob_name);
	}

	function look($blob_number)
	{
		list($name, $hp) = $this->db->lookBlob($blob_number);
		return new Blob($name, $hp);
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

		$rawnames = $this->db->massLookNames();

		$blobs = [];
		foreach ($raw as $key=>$hp)
		{
			$blobs[] = new Blob($rawnames[$key], $hp);
		}

		return $blobs;
	}

	/** @return string */
	private function makeBlobName()
	{
		$name_generator = $this->getNameGenerator();
		return $name_generator->getName();
	}

	/** @return NonRandomNameGenerator */
	private function getNameGenerator()
	{
		if ($this->name_generator == null)
			$this->name_generator = new RandomNameGenerator();

		return $this->name_generator;
	}
}
