<?php
namespace plugin\data;

class ChannelData extends DataFile {

	const DATAFILE_ID = "channel";
	const FILE_NAME = "channel";
	const DEFAULT_DATA = ["channel" => array("defaultchannel")];
	
	public function getAllChannel(){
		$channels = null;
		$channels = $this->data["channel"];
		return $channels;
	}
	
	public function setChannel($channels){
		$this->data["channel"] = $channels;
	}

}
