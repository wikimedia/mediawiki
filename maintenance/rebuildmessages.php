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
 * @ingroup Maintenance
 */

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that purges cache used by MessageCache.
 *
 * @ingroup Maintenance
 */
class RebuildMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge the MessageCache for all interface languages.' );
	}

	public function execute() {
		$this->output( "Purging message cache for all languages on this wiki... " );
		$messageCache = MediaWikiServices::getInstance()->getMessageCache();
		$messageCache->clear();
		$this->output( "Done\n" );
	}
}

$maintClass = RebuildMessages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
