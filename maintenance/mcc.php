<?php
/**
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
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

/**
 * Diagnostic tool for interacting with memcached.
 *
 * @ingroup Maintenance
 */
class Mcc extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'MemCached Command (mcc) is an interactive CLI that lets you interact ' .
			'with the MediaWiki memcached backends.'
		);
		$this->addOption( 'cache', 'Cache type to use (a key in $wgObjectCaches)', false, true );
		$this->addOption( 'debug', 'Set debug mode on the memcached connection' );
	}

	protected function showHelp() {
		parent::showHelp();
		$this->output( "Interactive commands:\n    " );
		$this->output( str_replace( "\n", "\n    ", $this->mccGetHelp( false ) ) );
		$this->output( "\n" );
	}

	public function execute() {
		$mcc = new MemcachedClient( [
			'persistent' => true,
			'debug' => $this->hasOption( 'debug' ),
		] );

		$config = $this->getConfig();
		$objectCaches = $config->get( MainConfigNames::ObjectCaches );
		$mainCacheType = $config->get( MainConfigNames::MainCacheType );
		if ( $this->hasOption( 'cache' ) ) {
			$cache = $this->getOption( 'cache' );
			if ( !isset( $objectCaches[$cache] ) ) {
				$this->fatalError( "MediaWiki isn't configured with a cache named '$cache'" );
			}
			$servers = $objectCaches[$cache]['servers'];
		} elseif ( $mainCacheType === CACHE_MEMCACHED ) {
			$mcc->set_servers( $config->get( MainConfigNames::MemCachedServers ) );
		} elseif ( isset( $objectCaches[$mainCacheType]['servers'] ) ) {
			$mcc->set_servers( $objectCaches[$mainCacheType]['servers'] );
		} else {
			$this->fatalError( "MediaWiki isn't configured for Memcached usage" );
		}

		do {
			$bad = false;
			$quit = false;

			$line = self::readconsole();
			if ( $line === false ) {
				break;
			}

			$args = explode( ' ', $line );
			$command = array_shift( $args );

			// process command
			switch ( $command ) {
				case 'help':
					// show a help message
					print $this->mccGetHelp( array_shift( $args ) );
					break;

				case 'get':
					$sub = '';
					if ( array_key_exists( 1, $args ) ) {
						$sub = $args[1];
					}
					print "Getting {$args[0]}[$sub]\n";
					$res = $mcc->get( $args[0] );
					if ( array_key_exists( 1, $args ) ) {
						$res = $res[$args[1]];
					}
					if ( $res === false ) {
						# print 'Error: ' . $mcc->error_string() . "\n";
						print "MemCached error\n";
					} elseif ( is_string( $res ) ) {
						print "$res\n";
					} else {
						var_dump( $res );
					}
					break;

				case 'getsock':
					$res = $mcc->get( $args[0] );
					$sock = $mcc->get_sock( $args[0] );
					var_dump( $sock );
					break;

				case 'server':
					if ( $mcc->_single_sock !== null ) {
						print $mcc->_single_sock . "\n";
						break;
					}
					$res = $mcc->get( $args[0] );
					$hv = $mcc->_hashfunc( $args[0] );
					for ( $i = 0; $i < 3; $i++ ) {
						print $mcc->_buckets[$hv % $mcc->_bucketcount] . "\n";
						$hv += $mcc->_hashfunc( $i . $args[0] );
					}
					break;

				case 'set':
					$key = array_shift( $args );
					if ( $args[0] == "#" && is_numeric( $args[1] ) ) {
						$value = str_repeat( '*', (int)$args[1] );
					} else {
						$value = implode( ' ', $args );
					}
					if ( !$mcc->set( $key, $value, 0 ) ) {
						# print 'Error: ' . $mcc->error_string() . "\n";
						print "MemCached error\n";
					}
					break;

				case 'delete':
					$key = implode( ' ', $args );
					if ( !$mcc->delete( $key ) ) {
						# print 'Error: ' . $mcc->error_string() . "\n";
						print "MemCached error\n";
					}
					break;

				case 'history':
					if ( function_exists( 'readline_list_history' ) ) {
						foreach ( readline_list_history() as $num => $line ) {
							print "$num: $line\n";
						}
					} else {
						print "readline_list_history() not available\n";
					}
					break;

				case 'dumpmcc':
					var_dump( $mcc );
					break;

				case 'quit':
				case 'exit':
					$quit = true;
					break;

				default:
					$bad = true;
			}

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

	/**
	 * @param string|false $command
	 */
	private function mccGetHelp( $command ): string {
		$output = '';
		$commandList = [
			'get' => 'grabs something',
			'getsock' => 'lists sockets',
			'set' => 'changes something',
			'delete' => 'deletes something',
			'history' => 'show command line history',
			'server' => 'show current memcached server',
			'dumpmcc' => 'shows the whole thing',
			'exit' => 'exit mcc',
			'quit' => 'exit mcc',
			'help' => 'help about a command',
		];
		if ( !$command ) {
			$command = 'fullhelp';
		}
		if ( $command === 'fullhelp' ) {
			$max_cmd_len = max( array_map( 'strlen', array_keys( $commandList ) ) );
			foreach ( $commandList as $cmd => $desc ) {
				$output .= sprintf( "%-{$max_cmd_len}s: %s\n", $cmd, $desc );
			}
		} elseif ( isset( $commandList[$command] ) ) {
			$output .= "$command: $commandList[$command]\n";
		} else {
			$output .= "$command: command does not exist or no help for it\n";
		}

		return $output;
	}
}

// @codeCoverageIgnoreStart
$maintClass = Mcc::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
