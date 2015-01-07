<?php
/**
 * MobilePage.php
 */

/**
 * Retrieves information specific to a mobile page
 * Currently this only provides helper functions for loading PageImage associated with a page
 * @todo FIXME: Rename when this class when its purpose becomes clearer
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
