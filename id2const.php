<?php

/**
 * Convert NPC View IDs to Constants.
 *	@author     Kisuka <kisuka@kisuka.com>
 *  @version    1.0.0
 *  @copyright  (c) 2013 Taylor Locke
 *  @license    MIT License
 */

echo " __ _  ____   ___    __  ____       __      ___  __   __ _  ____  ____ \n";
echo "(  ( \(  _ \ / __)  (  )(    \   ___\ \    / __)/  \ (  ( \/ ___)(_  _)\n";
echo "/    / ) __/( (__    )(  ) D (  (___)) )  ( (__(  O )/    /\___ \  )(  \n";
echo "\_)__)(__)   \___)  (__)(____/      /_/    \___)\__/ \_)__)(____/ (__) \n";
echo "                                                                       \n";
echo "                                                                Kisuka \n";

require('consts.php');		// NPC Constants
$path = realpath('npc/');	// Location of scripts

// Gather all files recursively.
$objects = new RecursiveIteratorIterator(
               new RecursiveDirectoryIterator($path), 
               RecursiveIteratorIterator::SELF_FIRST);

foreach($objects as $name => $object){
	if(!$object->isDir())
	{
		// Get full path of script file.
		$filein = file($object->getPathname()) or exit("Unable to open file!");

		$result = '';

		echo "Changing IDs -> Constants in ".$object->getPathname()."...\n";

		// Loop through each line of the script file looking for IDs.
		foreach($filein as $line) {
			// Search for NPC header for normal NPCs and shops.
			if (preg_match('/script.*?\t([0-9]+)/', $line, $parts) OR preg_match('/shop.*?\t([0-9]+)/', $line, $parts)) {
				// Check if NPC is in consts.php, if not, don't change ID to Const.
				if(array_key_exists($parts[1], $npcs))
					$line = str_replace("\t".$parts[1], "\t".$npcs[$parts[1]], $line);	// Replace NPC View ID with sprite name constant.
			}

			// Search for NPC header of duplcate NPCs.
			else if (preg_match('/duplicate((.*))\t(.*)\t([0-9]+)/', $line, $parts)) {
				// Check if NPC is in consts.php, if not, don't change ID to Const.
				if(array_key_exists($parts[1], $npcs))
					$line = str_replace("\t".$parts[4], "\t".$npcs[$parts[4]], $line);	// Replace NPC View ID with sprite name constant.
			}

			$result .= $line;
		}

		// Save npc file back to it's original file.
		file_put_contents($object->getPathname(), $result);
	}    
}

echo "All Done~! <3";

?>