<?php
/**
 * @todo document
 * @file
 * @ingroup MaintenanceLanguage
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
$messagesDir = dirname(__FILE__).'/../../languages/messages/';
$runTest = false;
$run = false;
$runMode = 'text';

// Check parameters
if ( isset( $options['lang'] ) && isset( $options['clang'] )) {
	if (!isset( $options['mode'] )) {
		$runMode = 'text';
	} else {
		if (!strcmp($options['mode'],'wiki')) {
			$runMode = 'wiki';
		} else if (!strcmp($options['mode'],'raw')) {
			$runMode = 'raw';
		} else {
		}
	}
	$runTest = true;
} else {
	echo <<<END
Run this script to print out the duplicates against a message array.
Parameters:
	* lang:  Language code to be checked.
	* clang: Language code to be compared.
Options:
	* mode:  Output format, can be either:
		* text:   Text output on the console (default)
		* wiki:   Wiki format, with * at beginning of each line
		* raw:    Raw output for duplicates
END;
}

// Check file exists
if ( $runTest ) {
	$langCode = ucfirst(strtolower(preg_replace('/-/','_',$options['lang'])));
	$langCodeC = ucfirst(strtolower(preg_replace('/-/','_',$options['clang'])));
	$messagesFile = $messagesDir.'Messages'.$langCode.'.php';
	$messagesFileC = $messagesDir.'Messages'.$langCodeC.'.php';
	if (file_exists($messagesFile) && file_exists($messagesFileC)) {
		$run = true;
	}
	else {
		echo "Messages file(s) could not be found.\nMake sure both files are exists.\n";
	}
}

// Run to check the dupes
if ( $run ) {
	if (!strcmp($runMode,'wiki')) {
		$runMode = 'wiki';
	} else if (!strcmp($runMode,'raw')) {
		$runMode = 'raw';
	}
	include( $messagesFile );
	$wgMessages[$langCode] = $messages;
	include( $messagesFileC );
	$wgMessages[$langCodeC] = $messages;
	$count = 0;

	foreach ($wgMessages[$langCodeC] as $key => $value) {
		foreach ($wgMessages[$langCode] as $ckey => $cvalue) {
			if (!strcmp($key,$ckey)) {
				if (!strcmp($value,$cvalue)) {
					if (!strcmp($runMode,'raw')) {
						print("$key\n");
					} else if (!strcmp($runMode,'wiki')) {
						$uKey = ucfirst($key);
						print("* MediaWiki:$uKey/$langCode\n");
					} else {
						print("* $key\n");
					}
					$count++;
				}
			}
		}
	}
	if (!strcmp($runMode,'text')) {
		if ($count == 1) {
			echo "\nThere are $count duplicated message in ".$options['lang'].", against to ".$options['clang'].".\n";
		} else {
			echo "\nThere are $count duplicated messages in ".$options['lang'].", against to ".$options['clang'].".\n";
		}
	}	
}
