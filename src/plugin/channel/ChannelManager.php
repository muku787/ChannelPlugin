<?php
namespace plugin\channel;

class ChannelManager {

	private static $channel;
	
	public static function init($plugin){
		self::$channel = new Channel($plugin);
		$plugin->getServer()->getPluginManager()->registerEvents(new ChannelListener($plugin), $plugin);
	}
	
	public static function get(){
		return self::$channel;
		
	}
	
	
	
}
