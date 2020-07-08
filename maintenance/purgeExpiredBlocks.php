<?php
/**
 * Remove expired blocks from the ipblocks and ipblocks_restrictions tables
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
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright GPLv2 http://www.gnu.org/copyleft/gpl.html
 * @ingroup Maintenance
 */

use MediaWiki\Block\DatabaseBlock;

require_once __DIR__ . '/Maintenance.php';

/*
 * Maintenance script to remove expired blocks
 *
 * @since 1.35
 * @author DannyS712
 */

class PurgeExpiredBlocks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove expired blocks.' );
	}

	public function execute() {
		$this->output( "Purging expired blocks...\n" );

		DatabaseBlock::purgeExpired();

		$this->output( "Done purging expired blocks.\n" );
	}
}

$maintClass = PurgeExpiredBlocks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
