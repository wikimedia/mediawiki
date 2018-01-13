<?php
/**
 * Cleanup all spam from a given hostname.
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
 * Maintenance script to cleanup all spam from a given hostname.
 *
 * @ingroup Maintenance
 */
class CleanupSpam extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Cleanup all spam from a given hostname' );
		$this->addOption( 'all', 'Check all wikis in $wgLocalDatabases' );
		$this->addOption( 'delete', 'Delete pages containing only spam instead of blanking them' );
		$this->addArg(
			'hostname',
			'Hostname that was spamming, single * wildcard in the beginning allowed'
		);
	}

	public function execute() {
		global $IP, $wgLocalDatabases, $wgUser;

		$username = wfMessage( 'spambot_username' )->text();
		$wgUser = User::newSystemUser( $username );
		if ( !$wgUser ) {
			$this->fatalError( "Invalid username specified in 'spambot_username' message: $username" );
		}
		// Hack: Grant bot rights so we don't flood RecentChanges
		$wgUser->addGroup( 'bot' );

		$spec = $this->getArg();
		$like = LinkFilter::makeLikeArray( $spec );
		if ( !$like ) {
			$this->fatalError( "Not a valid hostname specification: $spec" );
		}

		if ( $this->hasOption( 'all' ) ) {
			// Clean up spam on all wikis
			$this->output( "Finding spam on " . count( $wgLocalDatabases ) . " wikis\n" );
			$found = false;
			foreach ( $wgLocalDatabases as $wikiID ) {
				$dbr = $this->getDB( DB_REPLICA, [], $wikiID );

				$count = $dbr->selectField( 'externallinks', 'COUNT(*)',
					[ 'el_index' . $dbr->buildLike( $like ) ], __METHOD__ );
				if ( $count ) {
					$found = true;
					$cmd = wfShellWikiCmd( "$IP/maintenance/cleanupSpam.php",
						[ '--wiki', $wikiID, $spec ] );
					passthru( "$cmd | sed 's/^/$wikiID:  /'" );
				}
			}
			if ( $found ) {
				$this->output( "All done\n" );
			} else {
				$this->output( "None found\n" );
			}
		} else {
			// Clean up spam on this wiki

			$dbr = $this->getDB( DB_REPLICA );
			$res = $dbr->select( 'externallinks', [ 'DISTINCT el_from' ],
				[ 'el_index' . $dbr->buildLike( $like ) ], __METHOD__ );
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
		$currentRevId = $rev->getId();

		while ( $rev && ( $rev->isDeleted( Revision::DELETED_TEXT )
			|| LinkFilter::matchEntry( $rev->getContent( Revision::RAW ), $domain ) )
		) {
			$rev = $rev->getPrevious();
		}

		if ( $rev && $rev->getId() == $currentRevId ) {
			// The regex didn't match the current article text
			// This happens e.g. when a link comes from a template rather than the page itself
			$this->output( "False match\n" );
		} else {
			$dbw = $this->getDB( DB_MASTER );
			$this->beginTransaction( $dbw, __METHOD__ );
			$page = WikiPage::factory( $title );
			if ( $rev ) {
				// Revert to this revision
				$content = $rev->getContent( Revision::RAW );

				$this->output( "reverting\n" );
				$page->doEditContent(
					$content,
					wfMessage( 'spam_reverting', $domain )->inContentLanguage()->text(),
					EDIT_UPDATE | EDIT_FORCE_BOT,
					$rev->getId()
				);
			} elseif ( $this->hasOption( 'delete' ) ) {
				// Didn't find a non-spammy revision, blank the page
				$this->output( "deleting\n" );
				$page->doDeleteArticle(
					wfMessage( 'spam_deleting', $domain )->inContentLanguage()->text()
				);
			} else {
				// Didn't find a non-spammy revision, blank the page
				$handler = ContentHandler::getForTitle( $title );
				$content = $handler->makeEmptyContent();

				$this->output( "blanking\n" );
				$page->doEditContent(
					$content,
					wfMessage( 'spam_blanking', $domain )->inContentLanguage()->text(),
					EDIT_UPDATE | EDIT_FORCE_BOT
				);
			}
			$this->commitTransaction( $dbw, __METHOD__ );
		}
	}
}

$maintClass = CleanupSpam::class;
require_once RUN_MAINTENANCE_IF_MAIN;
