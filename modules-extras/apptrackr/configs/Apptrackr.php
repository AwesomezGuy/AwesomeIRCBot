<?php
/**
 * Apptrackr Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
 
namespace modules\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class Apptrackr implements ModuleConfig {
	
	public static $mappedCommands = array(
		"getlink" => "modules\ApptrackrGetLinkFromiTunesURL",
	);
	
	public static $mappedEvents = array(
	);
	
	public static $mappedTriggers = array(
	);
	
	public static $help = array(
		"getlink" => array(
			"BASE" => array(
				"description" => "Gets a download link for an iTunes URL from Apptrackr", 
				"parameters" => "<iTunes URL>"
			)
		),
	);
}
?>