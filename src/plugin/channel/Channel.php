<?php
namespace plugin\channel;

use pocketmine\Player;
use pocketmine\PluginBase;

use plugin\data\ChannelData;
use plugin\data\PlayerData;

class Channel {
	
	const CHANNEL_GLOBAL = "global";
	
	public $plugin;
	public $channels;
	public $participation;
	public $object;
	
	
	public function __construct($plugin){
		$this->plugin = $plugin;
		$this->channels = ChannelData::get()->getAllChannel();
		foreach($this->channels as $channel){
			$this->participation[$channel] = [];
		}
		$this->participation[self::CHANNEL_GLOBAL] = [];
	}
	
	public function makeChannel(String $name){
		$this->channels[] = $name;
		ChannelData::get()->setChannel($this->channels);
		$this->participation[$name] = [];
	}
	
	public function deleteChannel(String $name){
		foreach($this->participation[$name] as $player){
			$this->leaveChannel($player);
			$this->joinGlobalChannel($player);
		}
		$channels = array_diff($this->channels, array($name));
		$this->channels = array_values($channels);
		ChannelData::get()->setChannel($this->channels);
	}
	
	public function joinChannel(Player $player, String $channelname){
		$this->participation[$channelname][] = $player;
		PlayerData::get()->setPlayerChannel($player, $channelname);
		$player->setDisplayName("[".$channelname."]".$player->getName());
	}

	public function leaveChannel(Player $player){
		if(!isset($this->participation[PlayerData::get()->getPlayerChannel($player)])) return false;
		$part = array_diff($this->participation[PlayerData::get()->getPlayerChannel($player)], array($player));
		$this->participation[PlayerData::get()->getPlayerChannel($player)] = array_values($part);
	}
	
	public function joinGlobalChannel(Player $player){
		$this->participation[self::CHANNEL_GLOBAL][] = $player;
		PlayerData::get()->setPlayerChannel($player, self::CHANNEL_GLOBAL);
		$player->setDisplayName("[".self::CHANNEL_GLOBAL."]".$player->getName());
	}
	
	public function getChannelName($index){
		$channel = null;
		if(isset($this->channels[$index])){
			$channel = $this->channels[$index];
		}
		return $channel;
	}
	
	public function getAllChannels(){
		return $this->channels;
	}
	
	public function getParticipation($channelname){
		return $this->participation[$channelname];
	}
	
	public static function get(){
		return ChannelManager::get();
	}
}
