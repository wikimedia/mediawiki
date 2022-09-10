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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that deletes all pages in the MediaWiki namespace
 * which were last edited by "MediaWiki default".
 *
 * @ingroup Maintenance
 */
class DeleteDefaultMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Deletes all pages in the MediaWiki namespace' .
			' which were last edited by "MediaWiki default"' );
		$this->addOption( 'dry-run', 'Perform a dry run, delete nothing' );
	}

	public function execute() {
		$services = MediaWikiServices::getInstance();

		$this->output( "Checking existence of old default messages..." );
		$dbr = $this->getDB( DB_REPLICA );

		$userFactory = $services->getUserFactory();
		$actorQuery = ActorMigration::newMigration()
			->getWhere( $dbr, 'rev_user', $userFactory->newFromName( 'MediaWiki default' ) );

		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->tables( [ 'page', 'revision' ] + $actorQuery['tables'] )
			->where( [
				'page_namespace' => NS_MEDIAWIKI,
				$actorQuery['conds'],
			] )
			->joinConds( [ 'revision' => [ 'JOIN', 'page_latest=rev_id' ] ] + $actorQuery['joins'] )
			->caller( __METHOD__ )
			->fetchResultSet();

		if ( $res->numRows() == 0 ) {
			// No more messages left
			$this->output( "done.\n" );
			return;
		}

		$dryrun = $this->hasOption( 'dry-run' );
		if ( $dryrun ) {
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->output( "\n* [[$title]]" );
			}
			$this->output( "\n\nRun again without --dry-run to delete these pages.\n" );
			return;
		}

		// Deletions will be made by $user temporarily added to the bot group
		// in order to hide it in RecentChanges.
		$user = User::newSystemUser( 'MediaWiki default', [ 'steal' => true ] );
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}
		$userGroupManager = $services->getUserGroupManager();
		$userGroupManager->addUserToGroup( $user, 'bot' );
		StubGlobalUser::setUser( $user );

		// Handle deletion
		$this->output( "\n...deleting old default messages (this may take a long time!)...", 'msg' );
		$dbw = $this->getDB( DB_PRIMARY );

		$lbFactory = $services->getDBLoadBalancerFactory();
		$wikiPageFactory = $services->getWikiPageFactory();

		foreach ( $res as $row ) {
			$lbFactory->waitForReplication();
			$dbw->ping();
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$page = $wikiPageFactory->newFromTitle( $title );
			// FIXME: Deletion failures should be reported, not silently ignored.
			$page->doDeleteArticleReal( 'No longer required', $user );
		}

		$this->output( "done!\n", 'msg' );
	}
}

$maintClass = DeleteDefaultMessages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
