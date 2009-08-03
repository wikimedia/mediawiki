<?php
/**
 * This script purges all language messages from the cache
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
 * @ingroup Maintenance
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class RebuildMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Purge all language messages from the cache";
	}

	public function execute() {
		global $wgLocalDatabases, $wgDBname, $wgEnableSidebarCache, $messageMemc;
		if( $wgLocalDatabases ) {
			$databases = $wgLocalDatabases;
		} else {
			$databases = array( $wgDBname );
		}
	
		foreach( $databases as $db ) {
			$this->output( "Deleting message cache for {$db}... " );
			$messageMemc->delete( "{$db}:messages" );
			if( $wgEnableSidebarCache )
				$messageMemc->delete( "{$db}:sidebar" );
			$this->output( "Deleted\n" );
		}
	}
}

$maintClass = "RebuildMessages";
require_once( DO_MAINTENANCE );
