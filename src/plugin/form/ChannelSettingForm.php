<?php
namespace plugin\form;

use plugin\data\PlayerData;
use plugin\channel\Channel;

class ChannelSettingForm extends Form {
	
	public function send(int $id){
		$cache = [];
		switch($id){
			case 1:
				$buttons = [
					["text" => "グローバルチャットについての設定をする。"],
					["text" => "チャンネルを選択する。"]
				];
				
				$cache = [2,4];
				if($this->player->isOp()){
					$buttons[] = ["text" => "チャンネルを追加する"];
					$cache[] = 6;
					$buttons[] = ["text" => "チャンネルを削除する"];
					$cache[] = 8;
				}
				
				$data = [
					'type' => 'form',
					'title' => 'ChannelSystem',
					'content' => "チャンネルについての設定ができます。\n現在の設定\nグローバルチャット:".(PlayerData::get()->getShowGlobal($this->player) ? "見る" : "見ない")."\n現在のチャンネル : ".PlayerData::get()->getPlayerChannel($this->player),
					'buttons' => $buttons
				];
				break;
				
			case 2:
				$this->sendModal(
				"ChannelSystem",
				"グローバルチャットを受け取りますか？",
				"はい",
				"いいえ",
				3,
				3);
				break;
			
			case 3:
				PlayerData::get()->setShowGlobal($this->player, $this->lastData);
				$content = $this->lastData ? "見る" : "見ない";
				$this->sendModal(
				"ChannelSystem",
				"グローバルチャットを".$content."に設定しました。",
				"続ける",
				"終わる",
				1,
				0);
				break;
			
			case 4:
				$buttons = [];
				$buttons[] = ["text" => Channel::CHANNEL_GLOBAL];
				$cache[] = 5;
				$channels = Channel::get()->getAllChannels();
				foreach($channels as $channel){
					$buttons[] = ["text" => $channel];
					$cache[] = 5;
				}
				$data = [
					"type" => 'form',
					"title" => 'ChannelSystem',
					"content" => 'チャンネルを選択してください。',
					"buttons" => $buttons
				];
				break;
				
			case 5:
				if($this->lastData === 0){
					Channel::get()->leaveChannel($this->player);
					Channel::get()->joinGlobalChannel($this->player);
					$content = "グローバルチャットに参加しました。";
				}else{
					Channel::get()->leaveChannel($this->player);
					Channel::get()->joinChannel($this->player, Channel::get()->getChannelName($this->lastData - 1));
					$content = "チャンネル|".Channel::get()->getChannelName($this->lastData - 1)."|に参加しました。";
				}
				$this->sendModal(
				"ChannelSystem",
				$content,
				"続ける",
				"終わる",
				1,
				0);
				break;
				
			case 6:
				$content = ["type" => "input", "text" => "追加するチャンネル名を入力してください。", "placeholder" => "チャンネル名を入力"];
				$cache[] = 7;
				$data = [
					"type" => 'custom_form',
					"title" => 'チャンネルを追加。',
					"content" => array($content)
				];
				break;
			
			case 7:
				if(in_array($this->lastData[0], Channel::get()->getAllChannels())){
					$content = "そのチャンネルはすでに存在します。";
				}else{
					Channel::get()->makeChannel($this->lastData[0]);
					$content = "チャンネル|".$this->lastData[0]."|を追加しました。";
				}
				$this->sendModal(
				"ChannelSystem",
				$content,
				"続ける",
				"終わる",
				1,
				0);
				break;
			
			case 8:
				$buttons = [];
				$channels = Channel::get()->getAllChannels();
				foreach($channels as $channel){
					$buttons[] = ["text" => $channel];
					$cache[] = 9;
				}
				$data = [
					"type" => 'form',
					"title" => 'ChannelSystem',
					"content" => '削除するチャンネルを選択してください。',
					"buttons" => $buttons
				];
				break;
			
			case 9:
				$name = Channel::get()->getChannelName($this->lastData);
				Channel::get()->deleteChannel($name);
				$this->sendModal(
				"ChannelSystem",
				"チャンネル|".$name."|を削除しました。",
				"続ける",
				"終わる",
				1,
				0);
				break;
				
			default:
				$this->close();
				return true;
		}

		if($cache !== []){
			$this->lastSendData = $data;
			$this->cache = $cache;
			$this->show($id, $data);
		}
		
	}
				
}
