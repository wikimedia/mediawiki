<?php
/**
 * Look for 'orphan' revisions hooked to pages which don't exist and
 * 'childless' pages with no revisions.
 * Then, kill the poor widows and orphans.
 * Man this is depressing.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @author <brion@pobox.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Maintenance script that looks for 'orphan' revisions hooked to pages which
 * don't exist and 'childless' pages with no revisions.
 *
 * @ingroup Maintenance
 */
class Orphans extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Look for 'orphan' revisions hooked to pages which don't exist\n" .
			"and 'childless' pages with no revisions\n" .
			"Then, kill the poor widows and orphans\n" .
			"Man this is depressing"
		);
		$this->addOption( 'fix', 'Actually fix broken entries' );
	}

	public function execute() {
		$this->checkOrphans( $this->hasOption( 'fix' ) );
		$this->checkSeparation( $this->hasOption( 'fix' ) );
	}

	/**
	 * Lock the appropriate tables for the script
	 * @param IMaintainableDatabase $db
	 * @param string[] $extraTable The name of any extra tables to lock (eg: text)
	 */
	private function lockTables( $db, $extraTable = [] ) {
		$tbls = [ 'page', 'revision', 'redirect' ];
		if ( $extraTable ) {
			$tbls = array_merge( $tbls, $extraTable );
		}
		$db->lockTables( [], $tbls, __METHOD__ );
	}

	/**
	 * Check for orphan revisions
	 * @param bool $fix Whether to fix broken revisions when found
	 */
	private function checkOrphans( $fix ) {
		$dbw = $this->getDB( DB_MASTER );
		$commentStore = CommentStore::getStore();

		if ( $fix ) {
			$this->lockTables( $dbw );
		}

		$commentQuery = $commentStore->getJoin( 'rev_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'rev_user' );

		$this->output( "Checking for orphan revision table entries... "
			. "(this may take a while on a large wiki)\n" );
		$result = $dbw->select(
			[ 'revision', 'page' ] + $commentQuery['tables'] + $actorQuery['tables'],
			[ 'rev_id', 'rev_page', 'rev_timestamp' ] + $commentQuery['fields'] + $actorQuery['fields'],
			[ 'page_id' => null ],
			__METHOD__,
			[],
			[ 'page' => [ 'LEFT JOIN', [ 'rev_page=page_id' ] ] ] + $commentQuery['joins']
				+ $actorQuery['joins']
		);
		$orphans = $result->numRows();
		if ( $orphans > 0 ) {
			$this->output( "$orphans orphan revisions...\n" );
			$this->output( sprintf(
				"%10s %10s %14s %20s %s\n",
				'rev_id', 'rev_page', 'rev_timestamp', 'rev_user_text', 'rev_comment'
			) );

			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			foreach ( $result as $row ) {
				$comment = $commentStore->getComment( 'rev_comment', $row )->text;
				if ( $comment !== '' ) {
					$comment = '(' . $contLang->truncateForVisual( $comment, 40 ) . ')';
				}
				$rev_user_text = $contLang->truncateForVisual( $row->rev_user_text, 20 );
				# pad $rev_user_text to 20 characters.  Note that this may
				# yield poor results if $rev_user_text contains combining
				# or half-width characters.  Alas.
				if ( mb_strlen( $rev_user_text ) < 20 ) {
					$rev_user_text = str_repeat( ' ', 20 - mb_strlen( $rev_user_text ) );
				}
				$this->output( sprintf( "%10d %10d %14s %s %s\n",
					$row->rev_id,
					$row->rev_page,
					$row->rev_timestamp,
					$rev_user_text,
					$comment ) );
				if ( $fix ) {
					$dbw->delete( 'revision', [ 'rev_id' => $row->rev_id ], __METHOD__ );
				}
			}
			if ( !$fix ) {
				$this->output( "Run again with --fix to remove these entries automatically.\n" );
			}
		} else {
			$this->output( "No orphans! Yay!\n" );
		}

		if ( $fix ) {
			$dbw->unlockTables( __METHOD__ );
		}
	}

	/**
	 * Check for pages where page_latest is wrong
	 * @param bool $fix Whether to fix broken entries
	 */
	private function checkSeparation( $fix ) {
		$dbw = $this->getDB( DB_MASTER );
		$page = $dbw->tableName( 'page' );
		$revision = $dbw->tableName( 'revision' );

		if ( $fix ) {
			$this->lockTables( $dbw, [ 'user', 'text' ] );
		}

		$this->output( "\nChecking for pages whose page_latest links are incorrect... "
			. "(this may take a while on a large wiki)\n" );
		$result = $dbw->query( "
			SELECT *
			FROM $page LEFT JOIN $revision ON page_latest=rev_id
		", __METHOD__ );
		$found = 0;
		$revLookup = MediaWikiServices::getInstance()->getRevisionLookup();
		foreach ( $result as $row ) {
			$result2 = $dbw->query( "
				SELECT MAX(rev_timestamp) as max_timestamp
				FROM $revision
				WHERE rev_page=" . (int)( $row->page_id ),
				__METHOD__
			);
			$row2 = $dbw->fetchObject( $result2 );
			if ( $row2 ) {
				if ( $row->rev_timestamp != $row2->max_timestamp ) {
					if ( $found == 0 ) {
						$this->output( sprintf( "%10s %10s %14s %14s\n",
							'page_id', 'rev_id', 'timestamp', 'max timestamp' ) );
					}
					++$found;
					$this->output( sprintf( "%10d %10d %14s %14s\n",
						$row->page_id,
						$row->page_latest,
						$row->rev_timestamp,
						$row2->max_timestamp ) );
					if ( $fix ) {
						# ...
						$maxId = $dbw->selectField(
							'revision',
							'rev_id',
							[
								'rev_page' => $row->page_id,
								'rev_timestamp' => $row2->max_timestamp
							],
							__METHOD__
						);
						$this->output( "... updating to revision $maxId\n" );
						$maxRev = $revLookup->getRevisionById( $maxId );
						$title = Title::makeTitle( $row->page_namespace, $row->page_title );
						$article = WikiPage::factory( $title );
						$article->updateRevisionOn( $dbw, $maxRev );
					}
				}
			} else {
				$this->output( "wtf\n" );
			}
		}

		if ( $found ) {
			$this->output( "Found $found pages with incorrect latest revision.\n" );
		} else {
			$this->output( "No pages with incorrect latest revision. Yay!\n" );
		}
		if ( !$fix && $found > 0 ) {
			$this->output( "Run again with --fix to remove these entries automatically.\n" );
		}

		if ( $fix ) {
			$dbw->unlockTables( __METHOD__ );
		}
	}
}

$maintClass = Orphans::class;
require_once RUN_MAINTENANCE_IF_MAIN;
