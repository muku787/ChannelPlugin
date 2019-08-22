<?php
namespace plugin\form;

use pocketmine\event\Listener;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class FormListener implements Listener{
	
	private $plugin;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
	}
	
	public function onReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		$player = $event->getPlayer();
		if($pk instanceof ModalFormResponsePacket){
			$form = FormManager::getForm($player);
			if(!is_null($form)) $form->response($pk->formId, json_decode($pk->formData, true));
		}
	}
	
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		FormManager::close(FormManager::getForm($player));
	}
		
}
