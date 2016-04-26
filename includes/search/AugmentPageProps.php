<?php

/**
 * Augment serch result set with values of certain page props.
 */
class AugmentPageProps implements ResultSetAugmentor {

	public function __construct( $propnames ) {
		$this->propnames = $propnames;
	}

	public function augmentAll( SearchResultSet $resultSet ) {
		$titles = $resultSet->extractTitles();
		return PageProps::getInstance()->getProperties( $titles, $this->propnames );
	}
}
