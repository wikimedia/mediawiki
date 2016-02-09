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
 * @file
 */

/**
 * @since 1.27
 */
class SearchDBIndexUpdater {

	/**
	 * Perform a search index update with locking
	 * @param int $maxLockTime The maximum time to keep the search index locked.
	 * @param string $callback The function that will update the function.
	 * @param DatabaseBase $dbw
	 * @param array $results
	 */
	public function updateSearchIndex( $maxLockTime, $callback, $dbw, $results ) {
		$lockTime = time();

		# Lock searchindex
		if ( $maxLockTime ) {
			$this->output( "   --- Waiting for lock ---" );
			$this->lockSearchindex( $dbw );
			$lockTime = time();
			$this->output( "\n" );
		}

		# Loop through the results and do a search update
		foreach ( $results as $row ) {
			# Allow reads to be processed
			if ( $maxLockTime && time() > $lockTime + $maxLockTime ) {
				$this->output( "	--- Relocking ---" );
				$this->relockSearchindex( $dbw );
				$lockTime = time();
				$this->output( "\n" );
			}
			call_user_func( $callback, $dbw, $row );
		}

		# Unlock searchindex
		if ( $maxLockTime ) {
			$this->output( "	--- Unlocking --" );
			$this->unlockSearchindex( $dbw );
			$this->output( "\n" );
		}
	}

	/**
	 * Update the searchindex table for a given pageid
	 * @param DatabaseBase $dbw A database write handle
	 * @param int $pageId The page ID to update.
	 * @return null|string
	 */
	public function updateSearchIndexForPage( $dbw, $pageId ) {
		// Get current revision
		$rev = Revision::loadFromPageId( $dbw, $pageId );
		$title = null;
		if ( $rev ) {
			$titleObj = $rev->getTitle();
			$title = $titleObj->getPrefixedDBkey();
			$this->output( "$title..." );
			# Update searchindex
			$u = new SearchUpdate( $pageId, $titleObj->getText(), $rev->getContent() );
			$u->doUpdate();
			$this->output( "\n" );
		}

		return $title;
	}

	/**
	 * Lock the search index
	 * @param DatabaseBase &$db
	 */
	private function lockSearchindex( $db ) {
		$write = array( 'searchindex' );
		$read = array(
			'page',
			'revision',
			'text',
			'interwiki',
			'l10n_cache',
			'user',
			'page_restrictions'
		);
		$db->lockTables( $read, $write, __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock the tables
	 * @param DatabaseBase &$db
	 */
	private function unlockSearchindex( $db ) {
		$db->unlockTables( __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock and lock again
	 * Since the lock is low-priority, queued reads will be able to complete
	 * @param DatabaseBase &$db
	 */
	private function relockSearchindex( $db ) {
		$this->unlockSearchindex( $db );
		$this->lockSearchindex( $db );
	}

	// @TODO / @FIXME
	private function output( $text ) {
		echo "$text";
	}

}
