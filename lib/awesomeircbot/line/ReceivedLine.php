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

class ReceivedLine {

	public $line;
	
	public $type;
	public $message;
	
	public function __construct($line) {
		$this->line = $line;
	}
	
	public function parse() {
	}		
}

?>