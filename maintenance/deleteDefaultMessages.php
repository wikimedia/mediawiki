<?php
/**
 * Deletes all pages in the MediaWiki namespace which were last edited by
 * "MediaWiki default".
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that deletes all pages in the MediaWiki namespace
 * which were last edited by "MediaWiki default".
 *
 * @ingroup Maintenance
 */
class DeleteDefaultMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Deletes all pages in the MediaWiki namespace" .
								" which were last edited by \"MediaWiki default\"";
	}

	public function execute() {
		global $wgUser;

		$this->output( "Checking existence of old default messages..." );
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'page', 'revision' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'page_namespace' => NS_MEDIAWIKI,
				'page_latest=rev_id',
				'rev_user_text' => 'MediaWiki default',
			)
		);

		if( $dbr->numRows( $res ) == 0 ) {
			# No more messages left
			$this->output( "done.\n" );
			return;
		}

		# Deletions will be made by $user temporarly added to the bot group
		# in order to hide it in RecentChanges.
		$user = User::newFromName( 'MediaWiki default' );
		if ( !$user ) {
			$this->error( "Invalid username", true );
		}
		$user->addGroup( 'bot' );
		$wgUser = $user;

		# Handle deletion
		$this->output( "\n...deleting old default messages (this may take a long time!)...", 'msg' );
		$dbw = wfGetDB( DB_MASTER );

		foreach ( $res as $row ) {
			wfWaitForSlaves();
			$dbw->ping();
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$page = WikiPage::factory( $title );
			$dbw->begin( __METHOD__ );
			$error = ''; // Passed by ref
			$page->doDeleteArticle( 'No longer required', false, 0, false, $error, $user );
			$dbw->commit( __METHOD__ );
		}

		$this->output( "done!\n", 'msg' );
	}
}

$maintClass = "DeleteDefaultMessages";
require_once( RUN_MAINTENANCE_IF_MAIN );
