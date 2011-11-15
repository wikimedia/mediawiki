<?php
/**
 * Special handling for category pages
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
			if ( $cat->getPageCount()
				|| $cat->getSubcatCount()
				|| $cat->getFileCount()
			) {
				return true;
			}
		}
		return false;
	}
}
