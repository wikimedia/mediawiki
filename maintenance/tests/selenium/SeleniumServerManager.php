<?php
/**
 * Selenium server manager
 *
 * @file
 * @ingroup Maintenance
 * Copyright (C) 2010 Dan Nessett <dnessett@yahoo.com>
 * http://citizendium.org/
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @addtogroup Maintenance
 */

class SeleniumServerManager {
	private $SeleniumStartServer = false;
	private $OS = '';
	private $SeleniumServerPid = 'NaN';
	private $SeleniumServerPort = 4444;
	private $SeleniumServerStartTimeout = 10; // 10 secs.
	private $SeleniumServerExecPath;

	public function __construct( $startServer,
				     $serverPort,
				     $serverExecPath ) {
		$this->OS = (string) PHP_OS;
		if ( isset( $startServer ) )
			$this->SeleniumStartServer = $startServer;
		if ( isset( $serverPort ) )
			$this->SeleniumServerPort = $serverPort;
		if ( isset( $serverExecPath ) )
			$this->SeleniumServerExecPath = $serverExecPath;
		return;
	}

	// Getters for certain private attributes. No setters, since they
	// should not change after the manager object is created.

	public function getSeleniumStartServer() {
		return $this->SeleniumStartServer;
	}

	public function getSeleniumServerPort() {
		return $this->SeleniumServerPort;
	}

	public function getSeleniumServerPid() {
		return $this->SeleniumServerPid;
	}

	// Changing value of SeleniumStartServer allows starting server after
	// creation of the class instance. Only allow setting SeleniumStartServer
	// to true, since after server is started, it is shut down by stop().

	public function setSeleniumStartServer( $startServer ) {
		if ( $startServer == true ) $this->SeleniumStartServer = true;
	}

	// return values are: 1) started - server started, 2) failed -
	// server not started, 3) running - instructed to start server, but
	// server already running

	public function start() {

		if ( !$this->SeleniumStartServer ) return 'failed';

		// commented out cases are untested

		switch ( $this->OS ) {
			case "Linux":
#			case' CYGWIN_NT-5.1':
#			case 'Darwin':
#			case 'FreeBSD':
#			case 'HP-UX':
#			case 'IRIX64':
#			case 'NetBSD':
#			case 'OpenBSD':
#			case 'SunOS':
#			case 'Unix':
				// *nix based OS
				return $this->startServerOnUnix();
				break;
			case "Windows":
			case "WIN32":
			case "WINNT":
				// Windows
				return $this->startServerOnWindows();
				break;
			default:
				// An untested OS
				return 'failed';
				break;
		}
	}

	public function stop() {

		// commented out cases are untested

		switch ( $this->OS ) {
			case "Linux":
#			case' CYGWIN_NT-5.1':
#			case 'Darwin':
#			case 'FreeBSD':
#			case 'HP-UX':
#			case 'IRIX64':
#			case 'NetBSD':
#			case 'OpenBSD':
#			case 'SunOS':
#			case 'Unix':
				// *nix based OS
				return $this->stopServerOnUnix();
				break;
			case "Windows":
			case "WIN32":
			case "WINNT":
				// Windows
				return $this->stopServerOnWindows();
				break;
			default:
				// An untested OS
				return 'failed';
				break;
		}
	}

	private function startServerOnUnix() {

		$output = array();
                $user = $_ENV['USER'];
		exec("ps -U " . $user . " w | grep -i selenium-server", $output);

		// Start server. If there is already a server running,
		// return running.

		if ( isset( $this->SeleniumServerExecPath ) ) {
			$found = 0;
			foreach ( $output as $string ) {
				$found += preg_match(
					'~^(.*)java(.+)-jar(.+)selenium-server~',
					$string );
			}
			if ( $found == 0 ) {

				// Didn't find the selenium server. Start it up.
				// First set up comamand line suffix.
				// NB: $! is pid of last job run in background
				// The echo guarentees it is put into $op when
				// the exec command is run.

				$commandSuffix = ' > /dev/null 2>&1'. ' & echo $!';
				$portText = ' -port ' . $this->SeleniumServerPort;
				$command = "java -jar " . 
					$this->SeleniumServerExecPath .
					$portText . $commandSuffix;
				exec($command ,$op); 
				$pid = (int)$op[0]; 
				if ( $pid != "" )
					$this->SeleniumServerPid = $pid;
				else {
					$this->SeleniumServerPid = 'NaN';
					// Server start failed.
					return 'failed';
				}
				// Wait for the server to startup and listen
				// on its port. Note: this solution kinda
				// stinks, since it uses a wait loop - dnessett

				for ( $cnt = 1;
				      $cnt <= $this->SeleniumServerStartTimeout;
				      $cnt++ ) {
					$fp = @fsockopen ( 'localhost',
						$this->SeleniumServerPort,
						&$errno, &$errstr, 0 );
					if ( !$fp ) {
						sleep( 1 );
						continue;
					  // Server start succeeded.
					} else {
						fclose ( $fp );
						return 'started';
					}
				}
				echo ( "Starting Selenium server timed out.\n" );
				return 'failed';
			}
			// server already running.
			else return 'running';

		}
                // No Server execution path defined.
		return 'failed';
	}

	private function startServerOnWindows() {
		// Unimplemented. 
		return 'failed';
	}

	private function stopServerOnUnix() {

		if ( !empty( $this->SeleniumServerPid ) &&
		     $this->SeleniumServerPid != 'NaN' ) {
			exec( "kill -9 " . $this->SeleniumServerPid );
			return 'stopped';
		}
		else return 'failed';
	}

	private function stopServerOnWindows() {
		// Unimplemented. 
		return 'failed';

	}
}
