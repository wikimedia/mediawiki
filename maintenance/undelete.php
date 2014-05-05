<?php
/**
 * Undelete a page by fetching it from the archive table
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
 * Maintenance script to undelete a page by fetching it from the archive table.
 *
 * @ingroup Maintenance
 */
class Undelete extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Undelete a page";
		$this->addOption( 'user', 'The user to perform the undeletion', false, true, 'u' );
		$this->addOption( 'reason', 'The reason to undelete', false, true, 'r' );
		$this->addArg( 'pagename', 'Page to undelete' );
	}

	public function execute() {
		global $wgUser;

		$user = $this->getOption( 'user', 'Command line script' );
		$reason = $this->getOption( 'reason', '' );
		$pageName = $this->getArg();

		// Construct title object to undelete and check if title exists
		$title = Title::newFromText( $pageName );
		if ( !$title ) {
			$this->error( "Invalid title", true );
		}

		// Construct user object to perform undeletion and check if user exists
		$wgUser = User::newFromName( $user );
		if ( !$wgUser ) {
			$this->error( "Invalid username", true );
		}

		// Construct archived object to restore and check if archived object exists
		$archive = new PageArchive( $title );
		if ( !$archive->isDeleted() ) {
			$this->error( "Error undeleting page:" .
					"cannot locate deleted revisions to restore.", true );
		}

		// Actual block to perform undeletion
		$this->output( "Undeleting " . $title->getPrefixedDBkey() . "...\n" );
		try {
			$status = $archive->undelete( array(), $reason, array(), false, $wgUser );
			if ( $status === false ) {
				throw new MWException( "Error undeleting page:" .
						"unknown error found; operation returned false." );
			}
		} catch ( ErrorPageError $err ) {
			throw new MWException( "Error undeleting page:" .
					"the database is currently undergoing maintenance." );
		} catch ( PermissionsError $permerr ) {
			throw new MWException( "Error undeleting page:" .
					"you do not have permission to undelete this page." );
		} catch ( ReadOnlyError $readerr ) {
			throw new MWException( "Error undeleting page:" .
					"the database is currently locked from modifications." );
		} finally {
			$this->output( "\n" );
		}
		$this->output( "Done!\n" );
	}
}

$maintClass = "Undelete";
require_once RUN_MAINTENANCE_IF_MAIN;
