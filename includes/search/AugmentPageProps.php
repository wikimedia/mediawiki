<?php

/**
 * Augment search result set with values of certain page props.
 */
class AugmentPageProps implements ResultSetAugmentor {
	/**
	 * @var array List of properties.
	 */
	private $propnames;

	public function __construct( $propnames ) {
		$this->propnames = $propnames;
	}

	public function augmentAll( ISearchResultSet $resultSet ) {
		$titles = $resultSet->extractTitles();
		return PageProps::getInstance()->getProperties( $titles, $this->propnames );
	}
}
