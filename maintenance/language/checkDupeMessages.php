<?php
/**
 * Print out duplicates in message array
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup MaintenanceLanguage
 */

require_once( __DIR__ . '/../commandLine.inc' );
$messagesDir = __DIR__ . '/../../languages/messages/';
$runTest = false;
$run = false;
$runMode = 'text';

// Check parameters
if ( isset( $options['lang'] ) && isset( $options['clang'] ) ) {
	if ( !isset( $options['mode'] ) ) {
		$runMode = 'text';
	} else {
		if ( !strcmp( $options['mode'], 'wiki' ) ) {
			$runMode = 'wiki';
		} elseif ( !strcmp( $options['mode'], 'php' ) ) {
			$runMode = 'php';
		} elseif ( !strcmp( $options['mode'], 'raw' ) ) {
			$runMode = 'raw';
		} else {
		}
	}
	$runTest = true;
} else {
	echo <<<TEXT
Run this script to print out the duplicates against a message array.
Parameters:
	* lang:  Language code to be checked.
	* clang: Language code to be compared.
Options:
	* mode:  Output format, can be either:
		* text:   Text output on the console (default)
		* wiki:   Wiki format, with * at beginning of each line
		* php:    Output text as PHP syntax in a array $dupeMessages
		* raw:    Raw output for duplicates
TEXT;
}

// Check file exists
if ( $runTest ) {
	$langCode = $options['lang'];
	$langCodeC = $options['clang'];
	$langCodeF = ucfirst( strtolower( preg_replace( '/-/', '_', $langCode ) ) );
	$langCodeFC = ucfirst( strtolower( preg_replace( '/-/', '_', $langCodeC ) ) );
	$messagesFile = $messagesDir . 'Messages' . $langCodeF . '.php';
	$messagesFileC = $messagesDir . 'Messages' . $langCodeFC . '.php';
	if ( file_exists( $messagesFile ) && file_exists( $messagesFileC ) ) {
		$run = true;
	}
	else {
		echo "Messages file(s) could not be found.\nMake sure both files are exists.\n";
	}
}

// Run to check the dupes
if ( $run ) {
	if ( !strcmp( $runMode, 'wiki' ) ) {
		$runMode = 'wiki';
	} elseif ( !strcmp( $runMode, 'raw' ) ) {
		$runMode = 'raw';
	}
	include( $messagesFile );
	$messageExist = isset( $messages );
	if ( $messageExist )
		$wgMessages[$langCode] = $messages;
	include( $messagesFileC );
	$messageCExist = isset( $messages );
	if ( $messageCExist )
		$wgMessages[$langCodeC] = $messages;
	$count = 0;

	if ( ( $messageExist ) && ( $messageCExist ) ) {

		if ( !strcmp( $runMode, 'php' ) ) {
			print( "<?php\n" );
			print( '$dupeMessages = array(' . "\n" );
		}
		foreach ( $wgMessages[$langCodeC] as $key => $value ) {
			foreach ( $wgMessages[$langCode] as $ckey => $cvalue ) {
				if ( !strcmp( $key, $ckey ) ) {
					if ( ( !strcmp( $key, $ckey ) ) && ( !strcmp( $value, $cvalue ) ) ) {
						if ( !strcmp( $runMode, 'raw' ) ) {
							print( "$key\n" );
						} elseif ( !strcmp( $runMode, 'php' ) ) {
							print( "'$key' => '',\n" );
						} elseif ( !strcmp( $runMode, 'wiki' ) ) {
							$uKey = ucfirst( $key );
							print( "* MediaWiki:$uKey/$langCode\n" );
						} else {
							print( "* $key\n" );
						}
						$count++;
					}
				}
			}
		}
		if ( !strcmp( $runMode, 'php' ) ) {
			print( ");\n" );
		}
		if ( !strcmp( $runMode, 'text' ) ) {
			if ( $count == 1 ) {
				echo "\nThere are $count duplicated message in $langCode, against to $langCodeC.\n";
			} else {
				echo "\nThere are $count duplicated messages in $langCode, against to $langCodeC.\n";
			}
		}
	} else {
		if ( !$messageExist )
			echo "There are no messages defined in $langCode.\n";
		if ( !$messageCExist )
			echo "There are no messages defined in $langCodeC.\n";
	}
}
