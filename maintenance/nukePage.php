<?php
/**
 * Erase a page record from the database
 * Irreversible (can't use standard undelete) and does not update link tables
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
 * @author Rob Church <robchur@gmail.com>
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

/**
 * Maintenance script that erases a page record from the database.
 *
 * @ingroup Maintenance
 */
class NukePage extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Remove a page record from the database' );
		$this->addOption( 'delete', "Actually delete the page" );
		$this->addArg( 'title', 'Title to delete' );
	}

	public function execute() {
		$name = $this->getArg( 0 );
		$delete = $this->hasOption( 'delete' );

		$dbw = $this->getPrimaryDB();
		$this->beginTransaction( $dbw, __METHOD__ );

		# Get page ID
		$this->output( "Searching for \"$name\"..." );
		$title = Title::newFromText( $name );
		if ( $title && $title->exists() ) {
			$id = $title->getArticleID();
			$real = $title->getPrefixedText();
			$isGoodArticle = $title->isContentPage();
			$this->output( "found \"$real\" with ID $id.\n" );

			# Get corresponding revisions
			$this->output( "Searching for revisions..." );

			$revs = $dbw->newSelectQueryBuilder()
				->select( 'rev_id' )
				->from( 'revision' )
				->where( [ 'rev_page' => $id ] )
				->caller( __METHOD__ )->fetchFieldValues();
			$count = count( $revs );
			$this->output( "found $count.\n" );

			# Delete the page record and associated recent changes entries
			if ( $delete ) {
				$this->output( "Deleting page record..." );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'page' )
					->where( [ 'page_id' => $id ] )
					->caller( __METHOD__ )->execute();
				$this->output( "done.\n" );
				$this->output( "Cleaning up recent changes..." );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'recentchanges' )
					->where( [ 'rc_cur_id' => $id ] )
					->caller( __METHOD__ )->execute();
				$this->output( "done.\n" );
			}

			$this->commitTransaction( $dbw, __METHOD__ );

			# Delete revisions as appropriate
			if ( $delete && $count ) {
				$this->output( "Deleting revisions..." );
				$this->deleteRevisions( $revs );
				$this->output( "done.\n" );
				$this->purgeRedundantText( true );
			}

			# Update stats as appropriate
			if ( $delete ) {
				$this->output( "Updating site stats..." );
				// if it was good, decrement that too
				$ga = $isGoodArticle ? -1 : 0;
				$stats = SiteStatsUpdate::factory( [
					'edits' => -$count,
					'articles' => $ga,
					'pages' => -1
				] );
				$stats->doUpdate();
				$this->output( "done.\n" );
			}
		} else {
			$this->output( "not found in database.\n" );
			$this->commitTransaction( $dbw, __METHOD__ );
		}
	}

	public function deleteRevisions( array $ids ) {
		$dbw = $this->getPrimaryDB();
		$this->beginTransaction( $dbw, __METHOD__ );

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'revision' )
			->where( [ 'rev_id' => $ids ] )
			->caller( __METHOD__ )->execute();

		$this->commitTransaction( $dbw, __METHOD__ );
	}
}

// @codeCoverageIgnoreStart
$maintClass = NukePage::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
