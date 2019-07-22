<?php

/**
 * Perform augmentation of each row and return composite result,
 * indexed by ID.
 */
class PerRowAugmentor implements ResultSetAugmentor {

	/**
	 * @var ResultAugmentor
	 */
	private $rowAugmentor;

	/**
	 * @param ResultAugmentor $augmentor Per-result augmentor to use.
	 */
	public function __construct( ResultAugmentor $augmentor ) {
		$this->rowAugmentor = $augmentor;
	}

	/**
	 * Produce data to augment search result set.
	 * @param ISearchResultSet $resultSet
	 * @return array Data for all results
	 */
	public function augmentAll( ISearchResultSet $resultSet ) {
		$data = [];
		foreach ( $resultSet->extractResults() as $result ) {
			$id = $result->getTitle()->getArticleID();
			if ( !$id ) {
				continue;
			}
			$data[$id] = $this->rowAugmentor->augment( $result );
		}
		return $data;
	}
}
