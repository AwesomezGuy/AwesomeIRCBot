<?php
/**
 * Received Line Class
 * Contains methods to parse received lines, check
 * if they match triggers, check if they are commands
 * and several other things
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\line;

use awesomeircbot\line\ReceivedLineTypes;
use awesomeircbot\module\ModuleManager;
use config\Config;

class ReceivedLine {

	public $line;
	
	public $type;
	public $message;
	
	public $senderNick;
	public $senderIdent;
	public $senderHost;
	
	public $channel;
	public $targetNick;
	
	public function __construct($line) {
		$this->line = $line;
	}
	
	public function parse() {
		
		if (strpos($this->line, "PRIVMSG #") !== false) {
			
			// Type
			$this->type = ReceivedLineTypes::CHANMSG;
			
			// Channel
			$workingLine = explode(" :", $this->line);
			$workingLine = explode("PRIVMSG ", $workingLine[0]);
			$this->channel = $workingLine[1];
			
			// User
			$workingLine = explode(" PRIVMSG", $this->line);
			$workingLine[0] = str_replace(":", "", $workingLine[0]);
			
				// Nick
				$workingLine = explode("!", $workingLine[0]);
				$this->senderNick = $workingLine[0];
				
				// Ident
				$workingLine = explode("@", $workingLine[1]);
				$this->senderIdent = $workingLine[0];
				
				// Host
				$this->senderHost = $workingLine[1];
			
			// Message
			$workingLine = explode(" :", $this->line, 2);
			$this->message = trim($workingLine[1]);
		}
		
		else if (strpos($this->line, "PRIVMSG") !== false) {
			
			// Type
			$this->type = ReceivedLineTypes::PRIVMSG;
			
			// Channel & Target
			$workingLine = explode(" :", $this->line);
			$workingLine = explode("PRIVMSG ", $workingLine[0]);
			$this->channel = $workingLine[1];
			$this->targetNick = $workingLine[1];
			
			// User
			$workingLine = explode(" PRIVMSG", $this->line);
			$workingLine[0] = str_replace(":", "", $workingLine[0]);
			
				// Nick
				$workingLine = explode("!", $workingLine[0]);
				$this->senderNick = $workingLine[0];
				
				// Ident
				$workingLine = explode("@", $workingLine[1]);
				$this->senderIdent = $workingLine[0];
				
				// Host
				$this->senderHost = $workingLine[1];
			
			// Message
			$workingLine = explode(" :", $this->line, 2);
			$this->message = trim($workingLine[1]);
		}
		
		else if (strpos($this->line, "PING") !== false) {
			
			// Type
			$this->type = ReceivedLineTypes::PING;
			
			// Pinger
			$workingLine = explode(" :", $this->line);
			$this->senderNick = $workingLine[1];
			$this->senderNick = trim($this->senderNick);
		}
	}
	
	public function isCommand() {
		
		if (!$this->message)
			$this->parse();
			
		if (($this->type != ReceivedLineTypes::PRIVMSG) && ($this->type != ReceivedLineTypes::CHANMSG))
			return false;
		
		$splitMessage = str_split($this->message);
		
		if ($splitMessage[0] != Config::$commandCharacter)
			return false;
		
		return true;
	}
	
	public function isMappedEvent() {
		
		if (!$this->type)
			$this->parse();
		
		$module = ModuleManager::$mappedEvents[$this->type];
		if ($module)
			return true;
		else
			return false;
	}
	
	public function getCommand() {
		
		$splitMessage = explode(" ", $this->message);
		$command = str_replace(Config::$commandCharacter, "", $splitMessage[0]);
		return $command;
	}
				
}

?>