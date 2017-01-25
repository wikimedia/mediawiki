<?php
/**
 * Modern interactive console within the MediaWiki engine.
 *
 * Merely wraps around http://psysh.org/ and drop a console in the global
 * scope.
 *
 * Copyright © 2017 Antoine Musso <hashar@free.fr>
 * Copyright © 2017 Wikimedia Foundation Inc.
 * https://www.mediawiki.org/
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Interactive shell with completion and global scope.
 *
 */
class MediaWikiConsole extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addOption( 'd',
			'For back compatibility with eval.php. ' .
			'0 send debug to stdout. ' .
			'With 1 additionally initialize database with debugging ',
			false, true
		);
	}

	public function execute() {
		global $__builtInGlobals;

		$shell = new \Psy\Shell();
		if ( $this->hasOption( 'd' ) ) {
			$this->setupLegacy();
		}

		$shell->setScopeVariables( $GLOBALS );
		$shell->run();
	}

	/**
	 * For back compatibility with eval.php
	 */
	protected function setupLegacy() {
		global $wgDebugLogFile;

		$d = intval( $this->getOption( 'd' ) );
		if ( $d > 0 ) {
			$wgDebugLogFile = 'php://stdout';
		}
		if ( $d > 1 ) {
			# Set DBO_DEBUG (equivalent of $wgDebugDumpSql)
			# XXX copy pasted from eval.php :(
			$lb = wfGetLB();
			$serverCount = $lb->getServerCount();
			for ( $i = 0; $i < $serverCount; $i++ ) {
				$server = $lb->getServerInfo( $i );
				$server['flags'] |= DBO_DEBUG;
				$lb->setServerInfo( $i, $server );
			}
		}
	}

}

$maintClass = 'MediaWikiConsole';
require_once RUN_MAINTENANCE_IF_MAIN;
