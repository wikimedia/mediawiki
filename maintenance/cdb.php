<?php
/**
 * CDB inspector tool
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

use Cdb\Exception as CdbException;
use Cdb\Reader as CdbReader;

require_once __DIR__ . '/Maintenance.php';

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
class CdbInspector extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Inspects a CDB file' );
		$this->addArg( 'filename', 'File to open (optional)', false );
	}

	private function showInternalHelp( ?string $command ) : void {
		$commandList = [
			'load' => 'Load a CDB file for reading',
			'get' => 'Get a value for a key',
			'list' => 'List all keys in the file. Optional parameter specifies the maximum number '
				. 'of keys returned.',
			'find' => 'Find keys matching a regular expression',
			'exit' => 'Exit',
			'quit' => 'Exit',
			'help' => 'Help about a command',
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

	/**
	 * @inheritDoc
	 */
	public function execute() {
		if ( $this->isQuiet() ) {
			$this->fatalError( "This is an interactive script, can't use it with --quiet" );
		}

		$fileName = $this->getArg( 0, null );
		/** @var CdbReader|null $fileHandle */
		$fileHandle = $fileName ? $this->loadFile( $fileName ) : null;
		do {
			$bad = false;
			$quit = false;
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
					$this->showInternalHelp( array_shift( $args ) );
					break;
				case 'load':
					$fileHandle = $this->loadFile( array_shift( $args ) );
					break;
				case 'get':
					if ( !$fileHandle ) {
						print "Need to load a CDB file first\n";
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
				case 'list':
					$this->listKeys( $fileHandle, (int)array_shift( $args ) );
					break;
				case 'find':
					$this->findKeys( $fileHandle, array_shift( $args ) );
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
	}

	private function loadFile( ?string $fileName ) : ?CdbReader {
		if ( !$fileName ) {
			print "Need a filename there buddy\n";
			return null;
		}
		print "Loading CDB file $fileName...";
		$fileHandle = null;
		try {
			$fileHandle = CdbReader::open( $fileName );
		} catch ( CdbException $e ) {
		}

		if ( !$fileHandle ) {
			print "Not a CDB file or unable to read it\n";
		} else {
			print "ok\n";
		}
		return $fileHandle;
	}

	/**
	 * @param CdbReader $cdb
	 * @param int $maxKeys Maximum number of keys to output, or 0 for all
	 */
	private function listKeys( CdbReader $cdb, int $maxKeys ) : void {
		$count = 0;
		$key = $cdb->firstkey();
		while ( $key !== false && ( !$maxKeys || $count < $maxKeys ) ) {
			print "'$key'\n";
			$count++;
			$key = $cdb->nextkey();
		}
		print "$count keys found.\n";
	}

	/**
	 * @param CdbReader $cdb
	 * @param string|null $regexp
	 */
	private function findKeys( CdbReader $cdb, ?string $regexp ) : void {
		if ( $regexp === null ) {
			print "Regexp required\n";
			return;
		}
		if ( !preg_match( '#^/.+/[imsxUXJ]*$#', $regexp ) ) {
			$regexp = "/$regexp/";
		}
		// Test for invalid regexp
		if ( preg_match( $regexp, $regexp ) === false ) {
			print "Invalid regular expression\n";
			return;
		}

		$count = 0;
		$key = $cdb->firstkey();
		while ( $key !== false ) {
			if ( preg_match( $regexp, $key ) ) {
				print "'$key'\n";
				$count++;
			}
			$key = $cdb->nextkey();
		}
		print "$count keys found.\n";
	}
}

$maintClass = CdbInspector::class;

require RUN_MAINTENANCE_IF_MAIN;
