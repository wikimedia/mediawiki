<?php
/**
 * cdb inspector tool
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
 * @todo document
 * @ingroup Maintenance
 */
use \Cdb\Exception as CdbException;
use \Cdb\Reader as CdbReader;

require_once __DIR__ . '/commandLine.inc';

function cdbShowHelp( $command ) {
	$commandList = [
		'load' => 'load a cdb file for reading',
		'get' => 'get a value for a key',
		'exit' => 'exit cdb',
		'quit' => 'exit cdb',
		'help' => 'help about a command',
	];
	if ( !$command ) {
		$command = 'fullhelp';
	}
	if ( $command === 'fullhelp' ) {
		$max_cmd_len = max( array_map( 'strlen', array_keys( $commandList ) ) );
		foreach ( $commandList as $cmd => $desc ) {
			printf( "%-{$max_cmd_len}s: %s\n", $cmd, $desc );
		}
	} elseif ( isset( $commandList[$command] ) ) {
		print "$command: $commandList[$command]\n";
	} else {
		print "$command: command does not exist or no help for it\n";
	}
}

do {
	$bad = false;
	$showhelp = false;
	$quit = false;
	static $fileHandle = false;

	$line = Maintenance::readconsole();
	if ( $line === false ) {
		exit;
	}

	$args = explode( ' ', $line, 2 );
	$command = array_shift( $args );

	// process command
	switch ( $command ) {
		case 'help':
			// show an help message
			cdbShowHelp( array_shift( $args ) );
			break;
		case 'load':
			if ( !isset( $args[0] ) ) {
				print "Need a filename there buddy\n";
				break;
			}
			$file = $args[0];
			print "Loading cdb file $file...";
			try {
				$fileHandle = CdbReader::open( $file );
			} catch ( CdbException $e ) {
			}

			if ( !$fileHandle ) {
				print "not a cdb file or unable to read it\n";
			} else {
				print "ok\n";
			}
			break;
		case 'get':
			if ( !$fileHandle ) {
				print "Need to load a cdb file first\n";
				break;
			}
			if ( !isset( $args[0] ) ) {
				print "Need to specify a key, Luke\n";
				break;
			}
			try {
				$res = $fileHandle->get( $args[0] );
			} catch ( CdbException $e ) {
				print "Unable to read key from file\n";
				break;
			}
			if ( $res === false ) {
				print "No such key/value pair\n";
			} elseif ( is_string( $res ) ) {
				print "$res\n";
			} else {
				var_dump( $res );
			}
			break;
		case 'quit':
		case 'exit':
			$quit = true;
			break;

		default:
			$bad = true;
	} // switch() end

	if ( $bad ) {
		if ( $command ) {
			print "Bad command\n";
		}
	} else {
		if ( function_exists( 'readline_add_history' ) ) {
			readline_add_history( $line );
		}
	}
} while ( !$quit );
