<?php
/* Awesome IRC Bot v2
 * Created by Jack Harley
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */

// import
require_once(__DIR__ . "/lib/awesomeircbot/awesomeircbot.inc.php");
require_once(__DIR__ . "/modules/modules.inc.php");

use awesomeircbot\server\Server;
use awesomeircbot\module\ModuleManager;
use awesomeircbot\line\ReceivedLine;
use awesomeircbot\line\ReceivedLineTypes;
use awesomeircbot\command\Command;
use awesomeircbot\event\Event;
use awesomeircbot\trigger\Trigger;
use awesomeircbot\database\Database;
use awesomeircbot\config\Config;

// set up environment
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
passthru('clear');

// welcome the user
echo "Welcome to Awesome IRC Bot v2\n";
echo "http://github.com/JackHarley/AwesomeIRCBot\n";

// import all modules
ModuleManager::initialize();

// get a server instance
$server = Server::getInstance();

// setup database
$db = Database::getInstance();
$db->updateScriptArrays();

// configure
Config::initializeRequiredValues();

// update database
$db->updateDatabase();

echo "\n";

while (true) {
	
	// Connect
	$server->connect();
	
	// Identify
	$server->identify();
	
	// If we send anything else too soon after identification it could be
	// lost, so sleep for 2 seconds
	sleep(2);
	
	// NickServ
	if (Config::getValue("nickservPassword")) 
		$server->identifyWithNickServ();

	// Connect commands
	ModuleManager::runConnectCommands();
	
	// Loop through the channels in the config and join them
	$channels = Config::getValue("channels");
	foreach($channels as $channel) {
		$server->join($channel);
	}
	
	// Loop-edy-loop
	while($server->connected()) {
		$line = $server->getNextLine();
		
		$line = new ReceivedLine($line);
		$line->parse();
		
		if ($line->isMappedCommand()) {
			$command = new Command($line);
			$command->execute();
		}
		
		if ($line->isMappedEvent()) {
			$event = new Event($line);
			$event->execute();
		}
		
		if ($line->isMappedTrigger()) {
			$trigger = new Trigger($line);
			$trigger->execute();
		}
		
		$db->updateScriptArrays();
		$db->updateDatabase();
	}
	
	// Disconnected, Give the server 1 second before we attempt a reconnect
	sleep(1);
}
?>
