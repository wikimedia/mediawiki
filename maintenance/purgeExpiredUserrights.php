<?php
/**
 * Remove expired userrights from user_groups table and move them to former_user_groups
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
 * @author Eddie Greiner-Petter <wikimedia.org at eddie-sh.de>
 * @ingroup Maintenance
 */

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/*
 * Maintenance script to move expired userrights to user_former_groups
 *
 * @since 1.31
 */

class PurgeExpiredUserrights extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Move expired userrights from user_groups to former_user_groups table.' );
	}

	public function execute() {
		$this->output( "Purging expired user rights...\n" );
		$res = MediaWikiServices::getInstance()->getUserGroupManager()->purgeExpired();
		if ( $res === false ) {
			$this->output( "Purging failed.\n" );
		} else {
			$this->output( "$res rows purged.\n" );
		}
	}
}

$maintClass = PurgeExpiredUserrights::class;
require_once RUN_MAINTENANCE_IF_MAIN;
