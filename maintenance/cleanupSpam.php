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

use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\Title\Title;

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
		global $IP, $wgLocalDatabases;

		$username = wfMessage( 'spambot_username' )->text();
		$user = User::newSystemUser( $username );
		if ( !$user ) {
			$this->fatalError( "Invalid username specified in 'spambot_username' message: $username" );
		}
		// Hack: Grant bot rights so we don't flood RecentChanges
		MediaWikiServices::getInstance()->getUserGroupManager()->addUserToGroup( $user, 'bot' );
		StubGlobalUser::setUser( $user );

		$spec = $this->getArg( 0 );

		$protConds = [];
		foreach ( [ 'http://', 'https://' ] as $prot ) {
			$conds = LinkFilter::getQueryConditions( $spec, [ 'protocol' => $prot ] );
			if ( !$conds ) {
				$this->fatalError( "Not a valid hostname specification: $spec" );
			}
			$protConds[$prot] = $conds;
		}

		if ( $this->hasOption( 'all' ) ) {
			// Clean up spam on all wikis
			$this->output( "Finding spam on " . count( $wgLocalDatabases ) . " wikis\n" );
			$found = false;
			foreach ( $wgLocalDatabases as $wikiId ) {
				/** @var Database $dbr */
				$dbr = $this->getDB( DB_REPLICA, [], $wikiId );

				foreach ( $protConds as $conds ) {
					$count = $dbr->newSelectQueryBuilder()
						->select( 'COUNT(*)' )
						->from( 'externallinks' )
						->where( $conds )
						->caller( __METHOD__ )
						->fetchField();
					if ( $count ) {
						$found = true;
						$cmd = wfShellWikiCmd(
							"$IP/maintenance/cleanupSpam.php",
							[ '--wiki', $wikiId, $spec ]
						);
						passthru( "$cmd | sed 's/^/$wikiId:  /'" );
					}
				}
			}
			if ( $found ) {
				$this->output( "All done\n" );
			} else {
				$this->output( "None found\n" );
			}
		} else {
			// Clean up spam on this wiki

			$count = 0;
			/** @var Database $dbr */
			$dbr = $this->getDB( DB_REPLICA );
			foreach ( $protConds as $prot => $conds ) {
				$res = $dbr->newSelectQueryBuilder()
					->select( 'el_from' )
					->distinct()
					->from( 'externallinks' )
					->where( $conds )
					->caller( __METHOD__ )
					->fetchResultSet();
				$count = $res->numRows();
				$this->output( "Found $count articles containing $spec\n" );
				foreach ( $res as $row ) {
					$this->cleanupArticle(
						$row->el_from,
						$spec,
						$prot,
						$user
					);
				}
			}
			if ( $count ) {
				$this->output( "Done\n" );
			}
		}
	}

	/**
	 * @param int $id
	 * @param string $domain
	 * @param string $protocol
	 * @param Authority $performer
	 * @throws MWException
	 */
	private function cleanupArticle( $id, $domain, $protocol, Authority $performer ) {
		$title = Title::newFromID( $id );
		if ( !$title ) {
			$this->error( "Internal error: no page for ID $id" );

			return;
		}

		$this->output( $title->getPrefixedDBkey() . " ..." );

		$services = MediaWikiServices::getInstance();
		$revLookup = $services->getRevisionLookup();
		$rev = $revLookup->getRevisionByTitle( $title );
		$currentRevId = $rev->getId();

		while ( $rev && ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ||
			LinkFilter::matchEntry(
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable RAW never returns null
				$rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW ),
				$domain,
				$protocol
			) )
		) {
			$rev = $revLookup->getPreviousRevision( $rev );
		}

		if ( $rev && $rev->getId() == $currentRevId ) {
			// The regex didn't match the current article text
			// This happens e.g. when a link comes from a template rather than the page itself
			$this->output( "False match\n" );
		} else {
			$dbw = $this->getDB( DB_PRIMARY );
			$this->beginTransaction( $dbw, __METHOD__ );
			$page = $services->getWikiPageFactory()->newFromTitle( $title );
			if ( $rev ) {
				// Revert to this revision
				$content = $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW );

				$this->output( "reverting\n" );
				$page->doUserEditContent(
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable RAW never returns null
					$content,
					$performer,
					wfMessage( 'spam_reverting', $domain )->inContentLanguage()->text(),
					EDIT_UPDATE | EDIT_FORCE_BOT,
					$rev->getId()
				);
			} elseif ( $this->hasOption( 'delete' ) ) {
				// Didn't find a non-spammy revision, blank the page
				$this->output( "deleting\n" );
				$page->doDeleteArticleReal(
					wfMessage( 'spam_deleting', $domain )->inContentLanguage()->text(),
					$performer->getUser()
				);
			} else {
				// Didn't find a non-spammy revision, blank the page
				$handler = $services->getContentHandlerFactory()
					->getContentHandler( $title->getContentModel() );
				$content = $handler->makeEmptyContent();

				$this->output( "blanking\n" );
				$page->doUserEditContent(
					$content,
					$performer,
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
