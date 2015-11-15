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
 * @since 1.27
 */
class CategoryMembershipUpdate implements DeferrableUpdate {
	/** @var WikiPage */
	protected $page;
	/** @var Revision */
	protected $rev;
	/** @var ParserOutput */
	protected $output;
	/** @var Revision|null */
	protected $oldRev;

	/**
	 * @param WikiPage $page
	 * @param Revision $rev
	 * @param ParserOutput $output
	 * @param Revision|null $oldRev
	 */
	public function __construct(
		WikiPage $page, Revision $rev, ParserOutput $output, Revision $oldRev = null
	) {
		$this->page = $page;
		$this->rev = $rev;
		$this->output = $output;
		$this->oldRev = $oldRev;
	}

	/**
	 * Add recent changes entries for category membership changes
	 */
	public function doUpdate() {
		$title = $this->page->getTitle();

		$oldCategories = array();
		if ( $this->oldRev ) {
			// Parse the old rev and get the categories. Do not use link tables as that
			// assumes this updates are perfectly FIFO and that link tables are always
			// up to date, neither of which are true.
			$oldContent = $this->oldRev->getContent();
			if ( $oldContent ) {
				$oldOutput = $oldContent->getParserOutput( $title, $this->oldRev->getId() );
				$oldCategories = array_keys( $oldOutput->getCategories() );
			}
		}
		$newCategories = array_keys( $this->output->getCategories() );

		$categoryInserts = array_diff( $newCategories, $oldCategories );
		$categoryDeletes = array_diff( $oldCategories, $newCategories );
		if ( !$categoryInserts && !$categoryDeletes ) {
			return; // nothing to do
		}

		$catMembChange = new CategoryMembershipChange( $title, $this->rev );
		$catMembChange->checkTemplateLinks();

		foreach ( $categoryInserts as $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryAddedNotification( $categoryTitle );
		}
		if ( $categoryInserts ) {
			wfDebugLog( 'CategoryNotif', "Added to '{$title}': " .
			implode( ', ', $categoryInserts ) );
		}

		foreach ( $categoryDeletes as $categoryName ) {
			$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
			$catMembChange->triggerCategoryRemovedNotification( $categoryTitle );
		}
		if ( $categoryDeletes ) {
			wfDebugLog( 'CategoryNotif', "Removed from '{$title}': " .
			implode( ', ', $categoryInserts ) );
		}
	}
}
