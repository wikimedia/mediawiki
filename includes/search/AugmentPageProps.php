<?php

/**
 * Augment serch result set with values of certain page props.
 */
class AugmentPageProps implements ResultAugmentor {

	public function __construct( $propnames ) {
		$this->propnames = $propnames;
	}

	public function augment( SearchResultSet $resultSet ) {
		$titles = $resultSet->extractTitles();
		return PageProps::getInstance()->getProperties( $titles, $this->propnames );
	}
}
