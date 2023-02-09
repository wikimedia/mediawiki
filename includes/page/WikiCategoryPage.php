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

use MediaWiki\Category\Category;
use MediaWiki\MediaWikiServices;

/**
 * Special handling for representing category pages.
 */
class WikiCategoryPage extends WikiPage {

	/**
	 * Don't return a 404 for categories in use.
	 * In use defined as: either the actual page exists
	 * or the category currently has members.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		if ( parent::hasViewableContent() ) {
			return true;
		} else {
			$cat = Category::newFromTitle( $this->mTitle );
			// If any of these are not 0, then has members
			if ( $cat->getMemberCount()
				|| $cat->getSubcatCount()
				|| $cat->getFileCount()
			) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Checks if a category is hidden.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function isHidden() {
		$pageId = $this->getTitle()->getArticleID();
		$pageProps = MediaWikiServices::getInstance()
			->getPageProps()
			->getProperties( $this->getTitle(), 'hiddencat' );

		return isset( $pageProps[$pageId] );
	}

	/**
	 * Checks if a category is expected to be an unused category.
	 *
	 * @since 1.33
	 * @return bool
	 */
	public function isExpectedUnusedCategory() {
		$pageId = $this->getTitle()->getArticleID();
		$pageProps = MediaWikiServices::getInstance()
			->getPageProps()
			->getProperties( $this->getTitle(), 'expectunusedcategory' );

		return isset( $pageProps[$pageId] );
	}

	/**
	 * Update category counts on purge (T85696)
	 *
	 * @return bool
	 */
	public function doPurge() {
		if ( !parent::doPurge() ) {
			// Aborted by hook most likely
			return false;
		}

		$title = $this->mTitle;
		DeferredUpdates::addCallableUpdate(
			static function () use ( $title ) {
				$cat = Category::newFromTitle( $title );
				// If the category has less than 5000 pages, refresh the counts.
				// 5000 was chosen based on the discussion at T85696.
				$cat->refreshCountsIfSmall( 5000 );
			},
			// Explicitly PRESEND so that counts are correct before we try to
			// re-render the page on the next load so {{PAGESINCAT:...}} will
			// be using the correct new values, not the old ones.
			DeferredUpdates::PRESEND
		);

		return true;
	}
}
