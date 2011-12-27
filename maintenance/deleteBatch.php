<?php
/**
 * Deletes a batch of pages
 * Usage: php deleteBatch.php [-u <user>] [-r <reason>] [-i <interval>] [listfile]
 * where
 *	[listfile] is a file where each line contains the title of a page to be
 *             deleted, standard input is used if listfile is not given.
 *	<user> is the username
 *	<reason> is the delete reason
 *	<interval> is the number of seconds to sleep for after each delete
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DeleteBatch extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Deletes a batch of pages";
		$this->addOption( 'u', "User to perform deletion", false, true );
		$this->addOption( 'r', "Reason to delete page", false, true );
		$this->addOption( 'i', "Interval to sleep between deletions" );
		$this->addArg( 'listfile', 'File with titles to delete, separated by newlines. ' .
			'If not given, stdin will be used.', false );
	}

	public function execute() {
		global $wgUser;

		# Change to current working directory
		$oldCwd = getcwd();
		chdir( $oldCwd );

		# Options processing
		$username = $this->getOption( 'u', 'Delete page script' );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );

		$user = User::newFromName( $username );
		if ( !$user ) {
			$this->error( "Invalid username", true );
		}
		$wgUser = $user;

		if ( $this->hasArg() ) {
			$file = fopen( $this->getArg(), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if ( !$file ) {
			$this->error( "Unable to read file, exiting", true );
		}

		$dbw = wfGetDB( DB_MASTER );

		# Handle each entry
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = trim( fgets( $file ) );
			if ( $line == '' ) {
				continue;
			}
			$title = Title::newFromText( $line );
			if ( is_null( $title ) ) {
				$this->output( "Invalid title '$line' on line $linenum\n" );
				continue;
			}
			if ( !$title->exists() ) {
				$this->output( "Skipping nonexistent page '$line'\n" );
				continue;
			}

			$this->output( $title->getPrefixedText() );
			$dbw->begin();
			if ( $title->getNamespace() == NS_FILE ) {
				$img = wfFindFile( $title );
				if ( $img && $img->isLocal() && !$img->delete( $reason ) ) {
					$this->output( " FAILED to delete associated file... " );
				}
			}
			$page = WikiPage::factory( $title );
			$error = '';
			$success = $page->doDeleteArticle( $reason, false, 0, false, $error, $user );
			$dbw->commit();
			if ( $success ) {
				$this->output( " Deleted!\n" );
			} else {
				$this->output( " FAILED to delete article\n" );
			}

			if ( $interval ) {
				sleep( $interval );
			}
			wfWaitForSlaves();
		}
	}
}

$maintClass = "DeleteBatch";
require_once( RUN_MAINTENANCE_IF_MAIN );
