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
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class Protect extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Protect or unprotect an article from the command line.";
		$this->addOption( 'unprotect', 'Removes protection' );
		$this->addOption( 'semiprotect', 'Adds semi-protection' );
		$this->addOption( 'u', 'Username to protect with', false, true );
		$this->addOption( 'r', 'Reason for un/protection', false, true );
	}

	public function execute() {
		global $wgUser, $wgTitle, $wgArticle;

		$userName = $this->getOption( 'u', 'Maintenance script' );
		$reason = $this->getOption( 'r', '' );

		$protection = "sysop";
		if ( $this->hasOption('semiprotect') ) {
			$protection = "autoconfirmed";
		} elseif ( $this->hasOption('unprotect') ) {
			$protection = "";
		}

		$wgUser = User::newFromName( $userName );
		$restrictions = array( 'edit' => $protection, 'move' => $protection );

		$wgTitle = Title::newFromText( $args[0] );
		if ( !$wgTitle ) {
			$this->error( "Invalid title\n", true );
		}

		$wgArticle = new Article( $wgTitle );

		# un/protect the article
		$this->output( "Updating protection status... " );
		$success = $wgArticle->updateRestrictions($restrictions, $reason);
		if ( $success ) {
			$this->output( "done\n" );
		} else {
			$this->output( "failed\n" );
		}
	}
}

$maintClass = "Protect";
require_once( DO_MAINTENANCE );
