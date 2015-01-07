<?php
/**
 * CollectionItem.php
 */

/**
 * Representation of item in a collection.
 */
class CollectionItem {

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
