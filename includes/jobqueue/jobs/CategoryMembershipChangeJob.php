<?php
/**
 * Updater for link tracking tables after a page edit.
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
 */

/**
 * Job to add recent change entries mentioning category membership changes
 *
 * Parameters include:
 *   - revId : the new revision ID (whether still current or not)
 *   - priorRevId : the prior revision ID to diff against (they may be the same ID)
 *
 * @since 1.27
 */
class CategoryMembershipChangeJob extends Job {
	function __construct( Title $title, array $params ) {
		parent::__construct( 'categoryMembershipChange', $title, $params );

		$this->removeDuplicates = true;
	}

	public function run() {
		global $wgUpdateRowsPerQuery;

		// Get the new revision
		$newRev = Revision::newFromId( $this->params['revId'], Revision::READ_LATEST );
		$newContent = $newRev ? $newRev->getContent() : null;
		if ( !$newContent ) {
			$this->setLastError( "Could not fetch content of new revision" );
			return false; // deleted?
		}

		// Get the prior revision (the same for null edits)
		if ( $this->params['priorRevId'] ) {
			$oldRev = Revision::newFromId( $this->params['priorRevId'], Revision::READ_LATEST );
			$oldContent = $oldRev ? $oldRev->getContent() : null;
			if ( !$oldContent ) {
				$this->setLastError( "Could not fetch content of prior revision" );
				return false; // deleted?
			}
		} else {
			$oldRev = null;
			$oldContent = null;
		}

		// Inject the same timestamp for both revision parses to reduce the chance of seeing
		// category changes merely due to time-based parser functions.
		$parseTimestamp = $newRev->getTimestamp();

		// Parse the new revision and get the categories. This could possibly use the parser
		// cache if it checked the revision ID, but that's more complicated than it's worth.
		$nOpts = $newContent->getContentHandler()->makeParserOptions( 'canonical' );
		$nOpts->setTimestamp( $parseTimestamp );
		$newOutput = $newContent->getParserOutput( $this->title, $newRev->getId(), $nOpts );
		$newCategories = array_keys( $newOutput->getCategories() );

		if ( $oldContent ) {
			// Parse the old rev and get the categories. Do not use link tables as that
			// assumes this updates are perfectly FIFO and that link tables are always
			// up to date, neither of which are true.
			$oOpts = $oldContent->getContentHandler()->makeParserOptions( 'canonical' );
			$oOpts->setTimestamp( $parseTimestamp );
			$oldOutput = $oldContent->getParserOutput( $this->title, $oldRev->getId(), $oOpts );
			$oldCategories = array_keys( $oldOutput->getCategories() );
		} else {
			$oldCategories = array();
		}

		$categoryInserts = array_diff( $newCategories, $oldCategories );
		$categoryDeletes = array_diff( $oldCategories, $newCategories );
		if ( !$categoryInserts && !$categoryDeletes ) {
			return true; // nothing to do
		}

		$dbw = wfGetDB( DB_MASTER );
		$catMembChange = new CategoryMembershipChange( $this->title, $newRev );
		$catMembChange->checkTemplateLinks();

		foreach ( $categoryInserts as $i => $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryAddedNotification( $categoryTitle );
			if ( $i % $wgUpdateRowsPerQuery == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}
		if ( $categoryInserts ) {
			wfDebugLog( 'CategoryNotif', "Added to '{$this->title}': " .
				implode( ', ', $categoryInserts ) );
		}

		foreach ( $categoryDeletes as $i => $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryRemovedNotification( $categoryTitle );
			if ( $i % $wgUpdateRowsPerQuery == 0 ) {
				$dbw->commit( __METHOD__, 'flush' );
				wfWaitForSlaves();
			}
		}
		if ( $categoryDeletes ) {
			wfDebugLog( 'CategoryNotif', "Removed from '{$this->title}': " .
				implode( ', ', $categoryInserts ) );
		}

		return true;
	}
}
