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

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Remove expired blocks from the block and ipblocks_restrictions tables
 *
 * @since 1.35
 * @ingroup Maintenance
 * @author DannyS712
 */
class PurgeExpiredBlocks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove expired blocks.' );
	}

	public function execute() {
		$this->output( "Purging expired blocks...\n" );

		$this->getServiceContainer()->getDatabaseBlockStore()->purgeExpiredBlocks();

		$this->output( "Done purging expired blocks.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeExpiredBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
