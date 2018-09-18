<?php
/**
 * Rollback all edits by a given user or IP provided they're the most
 * recent edit (just like real rollback)
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
 * Maintenance script to rollback all edits by a given user or IP provided
 * they're the most recent edit.
 *
 * @ingroup Maintenance
 */
class RollbackEdits extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Rollback all edits by a given user or IP provided they're the most recent edit" );
		$this->addOption(
			'titles',
			'A list of titles, none means all titles where the given user is the most recent',
			false,
			true
		);
		$this->addOption( 'user', 'A user or IP to rollback all edits for', true, true );
		$this->addOption( 'summary', 'Edit summary to use', false, true );
		$this->addOption( 'bot', 'Mark the edits as bot' );
	}

	public function execute() {
		$user = $this->getOption( 'user' );
		$username = User::isIP( $user ) ? $user : User::getCanonicalName( $user );
		if ( !$username ) {
			$this->fatalError( 'Invalid username' );
		}

		$bot = $this->hasOption( 'bot' );
		$summary = $this->getOption( 'summary', $this->mSelf . ' mass rollback' );
		$titles = [];
		$results = [];
		if ( $this->hasOption( 'titles' ) ) {
			foreach ( explode( '|', $this->getOption( 'titles' ) ) as $title ) {
				$t = Title::newFromText( $title );
				if ( !$t ) {
					$this->error( 'Invalid title, ' . $title );
				} else {
					$titles[] = $t;
				}
			}
		} else {
			$titles = $this->getRollbackTitles( $user );
		}

		if ( !$titles ) {
			$this->output( 'No suitable titles to be rolled back' );

			return;
		}

		$doer = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );

		foreach ( $titles as $t ) {
			$page = WikiPage::factory( $t );
			$this->output( 'Processing ' . $t->getPrefixedText() . '... ' );
			if ( !$page->commitRollback( $user, $summary, $bot, $results, $doer ) ) {
				$this->output( "done\n" );
			} else {
				$this->output( "failed\n" );
			}
		}
	}

	/**
	 * Get all pages that should be rolled back for a given user
	 * @param string $user A name to check against
	 * @return array
	 */
	private function getRollbackTitles( $user ) {
		$dbr = $this->getDB( DB_REPLICA );
		$titles = [];
		$actorQuery = ActorMigration::newMigration()
			->getWhere( $dbr, 'rev_user', User::newFromName( $user, false ) );
		$results = $dbr->select(
			[ 'page', 'revision' ] + $actorQuery['tables'],
			[ 'page_namespace', 'page_title' ],
			$actorQuery['conds'],
			__METHOD__,
			[],
			[ 'revision' => [ 'JOIN', 'page_latest = rev_id' ] ] + $actorQuery['joins']
		);
		foreach ( $results as $row ) {
			$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
		}

		return $titles;
	}
}

$maintClass = RollbackEdits::class;
require_once RUN_MAINTENANCE_IF_MAIN;
