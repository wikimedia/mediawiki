<?php
/**
 * Cleanup all spam from a given hostname
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

require_once( dirname(__FILE__) . '/Maintenance.php' );

class CleanupSpam extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Cleanup all spam from a given hostname";
		$this->addOption( 'all', 'Check all wikis in $wgLocalDatabases' );
		$this->addArg( 'hostname', 'Hostname that was spamming' );
	}

	public function execute() {
		global $wgLocalDatabases;

		$username = wfMsg( 'spambot_username' );
		$wgUser = User::newFromName( $username );
		// Create the user if necessary
		if ( !$wgUser->getId() ) {
			$wgUser->addToDatabase();
		}
		$spec = $this->getArg();
		$like = LinkFilter::makeLikeArray( $spec );
		if ( !$like ) {
			$this->error( "Not a valid hostname specification: $spec", true );
		}
	
		if ( $this->hasOption('all') ) {
			// Clean up spam on all wikis
			$this->output( "Finding spam on " . count( $wgLocalDatabases ) . " wikis\n" );
			$found = false;
			foreach ( $wgLocalDatabases as $wikiID ) {
				$dbr = wfGetDB( DB_SLAVE, array(), $wikiID );

				$count = $dbr->selectField( 'externallinks', 'COUNT(*)', 
					array( 'el_index' . $dbr->buildLike( $like ) ), __METHOD__ );
				if ( $count ) {
					$found = true;
					passthru( "php cleanupSpam.php --wiki='$wikiID' $spec | sed 's/^/$wikiID:  /'" );
				}
			}
			if ( $found ) {
				$this->output( "All done\n" );
			} else {
				$this->output( "None found\n" );
			}
		} else {
			// Clean up spam on this wiki

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'externallinks', array( 'DISTINCT el_from' ), 
				array( 'el_index' . $dbr->buildLike( $like ) ), __METHOD__ );
			$count = $dbr->numRows( $res );
			$this->output( "Found $count articles containing $spec\n" );
			foreach ( $res as $row ) {
				$this->cleanupArticle( $row->el_from, $spec );
			}
			if ( $count ) {
				$this->output( "Done\n" );
			}
		}
	}

	private function cleanupArticle( $id, $domain ) {
		$title = Title::newFromID( $id );
		if ( !$title ) {
			$this->error( "Internal error: no page for ID $id" );
			return;
		}
	
		$this->output( $title->getPrefixedDBkey() . " ..." );
		$rev = Revision::newFromTitle( $title );
		$revId = $rev->getId();
		$currentRevId = $revId;
	
		while ( $rev && LinkFilter::matchEntry( $rev->getText() , $domain ) ) {
			# Revision::getPrevious can't be used in this way before MW 1.6 (Revision.php 1.26)
			#$rev = $rev->getPrevious();
			$revId = $title->getPreviousRevisionID( $revId );
			if ( $revId ) {
				$rev = Revision::newFromTitle( $title, $revId );
			} else {
				$rev = false;
			}
		}
		if ( $revId == $currentRevId ) {
			// The regex didn't match the current article text
			// This happens e.g. when a link comes from a template rather than the page itself
			$this->output( "False match\n" );
		} else {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->begin();
			if ( !$rev ) {
				// Didn't find a non-spammy revision, blank the page
				$this->output( "blanking\n" );
				$article = new Article( $title );
				$article->updateArticle( '', wfMsg( 'spam_blanking', $domain ),
					false, false );
	
			} else {
				// Revert to this revision
				$this->output( "reverting\n" );
				$article = new Article( $title );
				$article->updateArticle( $rev->getText(), wfMsg( 'spam_reverting', $domain ), false, false );
			}
			$dbw->commit();
			wfDoUpdates();
		}
	}
}

$maintClass = "CleanupSpam";
require_once( DO_MAINTENANCE );
