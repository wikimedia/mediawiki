<?php
/**
 * Protect or unprotect a page.
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
 * Maintenance script that protects or unprotects a page.
 *
 * @ingroup Maintenance
 */
class Protect extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Protect or unprotect a page from the command line.' );
		$this->addOption( 'unprotect', 'Removes protection' );
		$this->addOption( 'semiprotect', 'Adds semi-protection' );
		$this->addOption( 'cascade', 'Add cascading protection' );
		$this->addOption( 'user', 'Username to protect with', false, true, 'u' );
		$this->addOption( 'reason', 'Reason for un/protection', false, true, 'r' );
		$this->addArg( 'title', 'Title to protect', true );
	}

	public function execute() {
		$userName = $this->getOption( 'u', false );
		$reason = $this->getOption( 'r', '' );

		$cascade = $this->hasOption( 'cascade' );

		$protection = "sysop";
		if ( $this->hasOption( 'semiprotect' ) ) {
			$protection = "autoconfirmed";
		} elseif ( $this->hasOption( 'unprotect' ) ) {
			$protection = "";
		}

		if ( $userName === false ) {
			$user = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $userName );
		}
		if ( !$user ) {
			$this->error( "Invalid username", true );
		}

		// @todo FIXME: This is reset 7 lines down.
		$restrictions = [ 'edit' => $protection, 'move' => $protection ];

		$t = Title::newFromText( $this->getArg() );
		if ( !$t ) {
			$this->error( "Invalid title", true );
		}

		$restrictions = [];
		foreach ( $t->getRestrictionTypes() as $type ) {
			$restrictions[$type] = $protection;
		}

		# un/protect the article
		$this->output( "Updating protection status... " );

		$page = WikiPage::factory( $t );
		$status = $page->doUpdateRestrictions( $restrictions, [], $cascade, $reason, $user );

		if ( $status->isOK() ) {
			$this->output( "done\n" );
		} else {
			$this->output( "failed\n" );
		}
	}
}

$maintClass = "Protect";
require_once RUN_MAINTENANCE_IF_MAIN;
