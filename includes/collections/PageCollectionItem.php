<?php
/**
 * PageCollectionItem.php
 */

/**
 * Representation of page in a collection.
 */
class PageCollectionItem {

	/**
	 * Constructor
	 * @param Title $title
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * Get the title of the page
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}
}
