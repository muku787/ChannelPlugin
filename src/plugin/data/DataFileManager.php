<?php
namespace plugin\data;

class DataFileManager {
	
	private static $datafiles;
	
	public static function init($plugin){
		self::register(new ChannelData($plugin));
		self::register(new PlayerData($plugin));
	}
	
	public static function register($dataclass){
		self::$datafiles[$dataclass->getId()] = $dataclass;
	}
	
	public static function close(){
		foreach(self::$datafiles as $datafile){
			$datafile->save();
		}
	}	
	
	public static function get($id){
		return isset(self::$datafiles[$id]) ? self::$datafiles[$id] : null;
	}
	
}
