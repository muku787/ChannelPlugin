<?php
namespace plugin\form;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

use plugin\form\FormManager;

abstract class Form {
	
	const TYPE_FORM = 0;
	const TYPE_MODAL = 1;
	const TYPE_CUSTOM_FORM = 2;
	
	const MODAL_ID = 1212;
	
	public $player;
	public  $plugin;
	public $lastType;
	public $lastData;
	public $lastMode;
	public $lastSendData;
	public $lastId;
	
	public $cache = [];
	
	public function __construct($plugin, $player){
		$this->plugin = $plugin;
		$this->player = $player;
		$this->send(1);
	}
	
	public function close(){
		FormManager::close($this);
	}
	
	public function send(int $id){
	}
	
	public function sendModal($title, $content = "", $label1 = "", $label2 = "", $to1 = 0, $to2 = 0){
		$data = [
			"type" => "modal",
			"title" => $title,
			"content" => $content,
			"button1" => $label1,
			"button2" => $label2,
		];
		$this->cache = [0 => $to1, 1 => $to2];
		$this->show(self::MODAL_ID, $data);
	}
	
	public function response(int $id, $data){
		if(is_null($data)) return false;
		
		$this->lastId = $id;
		
		switch($this->lastSendData["type"]){
			case "form":
				$this->lastMode = self::TYPE_FORM;
				$this->lastData = $data;
				$this->send($this->cache[$data]);
				break;
			
			case "modal":
				$this->lastMode = self::TYPE_MODAL;
				$this->lastData = $data;
				$this->send($this->cache[abs($data-1)]);
				break;
			
			case "custom_form":
				$this->lastMode = self::TYPE_CUSTOM_FORM;
				$this->lastData = $data;
				$this->send($this->cache[0]);
				break;		
		}
	}
	
	public function getPlayer(){
		return $this->player;
	}
	
	public function show(int $id, array $data){
		$this->lastSendData = $data;
		$pk = new ModalFormRequestPacket;
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$this->player->dataPacket($pk);
	}
		
}
