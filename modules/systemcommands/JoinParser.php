<?php
/**
 * JoinParser Module
 * Deals with adding users to the channel
 * users' list when they join a channel
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\systemcommands;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\channel\ChannelManager;
use awesomeircbot\data\DataManager;

class JoinParser extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		$channel = ChannelManager::get($this->channel);
		$channel->addConnectedNick($this->senderNick);
		ChannelManager::store($this->channel, $channel);
	}
}
?>