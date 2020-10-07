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

class Undelete extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Undelete a page' );
		$this->addOption( 'user', 'The user to perform the undeletion', false, true, 'u' );
		$this->addOption( 'reason', 'The reason to undelete', false, true, 'r' );
		$this->addArg( 'pagename', 'Page to undelete' );
	}

	public function execute() {
		global $wgUser;

		$username = $this->getOption( 'user', false );
		$reason = $this->getOption( 'reason', '' );
		$pageName = $this->getArg( 0 );

		$title = Title::newFromText( $pageName );
		if ( !$title ) {
			$this->fatalError( "Invalid title" );
		}
		if ( $username === false ) {
			$user = User::newSystemUser( 'Command line script', [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $username );
		}
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}
		$wgUser = $user;

		$archive = new PageArchive( $title, RequestContext::getMain()->getConfig() );
		$this->output( "Undeleting " . $title->getPrefixedDBkey() . '...' );
		$archive->undeleteAsUser( [], $user, $reason );
		$this->output( "done\n" );
	}
}

$maintClass = Undelete::class;
require_once RUN_MAINTENANCE_IF_MAIN;
