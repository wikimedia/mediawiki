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
 * Remove expired userrights from user_groups table and move them to former_user_groups.
 *
 * By default, this does not need to be run. The UserGroupManager service naturally
 * takes care of detecting expired rows when it is written to (e.g. from SpecialUserrights)
 * and queues UserGroupExpiryJob to purge expired rows.
 *
 * Large wiki farms may experience stale rows if their users manage local groups
 * via a central wiki. In that case, UserGroupExpiryJob may run rarely or never
 * from local wikis, in which case this script can help to periodically clean up
 * expired rows.
 *
 * @since 1.31
 * @ingroup Maintenance
 * @author Eddie Greiner-Petter <wikimedia.org at eddie-sh.de>
 */
class PurgeExpiredUserrights extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Move expired userrights from user_groups to former_user_groups table.' );
	}

	public function execute() {
		$this->output( "Purging expired user rights...\n" );
		$res = $this->getServiceContainer()->getUserGroupManager()->purgeExpired();
		if ( $res === false ) {
			$this->output( "Purging failed.\n" );
		} else {
			$this->output( "$res rows purged.\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeExpiredUserrights::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
