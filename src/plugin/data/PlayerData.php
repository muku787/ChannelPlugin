<?php
namespace plugin\data;

use pocketmine\IPlayer;

use plugin\channel\Channel;

class PlayerData extends DataFile{
	
	const DATAFILE_ID = "playerdata";
	const FILE_NAME = "playerdata";
	const KEY = "channel";
	
	public function isRegistration(IPlayer $player){
		return isset($this->data[$player->getName()]) ? true : false;
	}
	
	public function Register(IPlayer $player){
		$this->data[$player->getName()][Channel::CHANNEL_GLOBAL] = true;
		$this->data[$player->getName()][self::KEY] = Channel::CHANNEL_GLOBAL;
	}
		
	public function setShowGlobal(IPlayer $player, bool $bool){
		$this->data[$player->getName()][Channel::CHANNEL_GLOBAL] = $bool;
	}
	
	public function getShowGlobal(IPlayer $player){
		if(is_null($this->data[$player->getName()][Channel::CHANNEL_GLOBAL])) return true;
		return $this->data[$player->getName()][Channel::CHANNEL_GLOBAL];
	}
	
	public function setPlayerChannel(IPlayer $player, String $channelname){
		$this->data[$player->getName()][self::KEY] = $channelname;
	}
	
	public function getPlayerChannel(IPlayer $player){
		if(is_null($this->data[$player->getName()][self::KEY])) return Channel::CHANNEL_GLOBAL;
		return $this->data[$player->getName()][self::KEY];
	}
		
}
