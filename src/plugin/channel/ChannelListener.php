<?php
namespace plugin\channel;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;

use plugin\data\PlayerData;
use plugin\form\FormManager;
use plugin\form\ChannelSettingForm;
use plugin\channel\Channel;

class ChannelListener implements Listener {
	
	private $plugin;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if(!PlayerData::get()->isRegistration($player)) PlayerData::get()->Register($player);
		Channel::get()->leaveChannel($player);
		Channel::get()->joinGlobalChannel($player);
	}
	
	public function onInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		if($player->getInventory()->getItemInHand()->getId() === 280)
			FormManager::register(new ChannelSettingForm($this->plugin, $player));
	}
	
	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$channel = PlayerData::get()->getPlayerChannel($player);
		$sendlist = [];
		if($channel === Channel::CHANNEL_GLOBAL){
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player2){
				if(PlayerData::get()->getPlayerChannel($player2) === Channel::CHANNEL_GLOBAL){
					$sendlist[] = $player2;
				}else{
					if(PlayerData::get()->getShowGlobal($player2)) $sendlist[] = $player2;
				}
			}
		}else{
			$sendlist = Channel::get()->getParticipation($channel);
		}
		$event->setRecipients($sendlist);
	}
	
	public function onQuit(PlayerQuitEvent $event){
		Channel::get()->leaveChannel($event->getPlayer());
	}
}
