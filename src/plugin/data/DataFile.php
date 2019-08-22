<?php
namespace plugin\data;

use pocketmine\utils\Config;

abstract class DataFile {
	
	const DATAFILE_ID = "";
	const FILE_NAME = "";
	const DEFAULT_DATA =[];
	
	protected $plugin;
	protected $data;
	protected $config;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
		$this->open();
	}
	
	public function open(){
		$this->config = new Config($this->plugin->getDataFolder() . static::FILE_NAME . ".yml", Config::YAML, ["data" => static::DEFAULT_DATA]);
		$this->data = $this->config->get("data");
	}
	
	public function save(){
		$this->config->set("data", $this->data);
		$this->config->save();
	}
	
	public static function get(){
		return DataFileManager::get(static::DATAFILE_ID);
	}
	
	public static function getId(){
		return static::DATAFILE_ID;
	}
}
