<?php
namespace plugin;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use plugin\channel\ChannelManager;
use plugin\data\DataFileManager;
use plugin\form\FormManager;

class Main extends PluginBase {
	
	public function onEnable(){
		DataFileManager::init($this);
		ChannelManager::init($this);
		FormManager::init($this);
		
	}
	
	public function onDisable(){
		DataFileManager::close();
	}
	
	
	
	
	
}
