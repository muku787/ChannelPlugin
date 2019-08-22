<?php
namespace plugin\form;

use pocketmine\Player;

class FormManager {

	private static $forms;
	
	public static function init($plugin){
		$plugin->getServer()->getPluginManager()->registerEvents(new FormListener($plugin), $plugin);
	}
	
	public static function register($form){
		self::$forms[$form->getPlayer()->getName()] = $form;
	}
	
	public static function close($form){
		if(!isset($form)) return false;
		unset(self::$forms[$form->getPlayer()->getName()]);
	}
	
	public static function getForm(Player $player){
		return isset(self::$forms[$player->getName()]) ? self::$forms[$player->getName()] : null;
	}
	
}
