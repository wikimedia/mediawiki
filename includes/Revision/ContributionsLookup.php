<?php

namespace MediaWiki\Revision;

/**
 * @since 1.35
 */
class ContributionsLookup {

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * ContributionsLookup constructor
	 *
	 * @param RevisionStore $revisionStore
	 */
	public function __construct( RevisionStore $revisionStore ) {
		$this->revisionStore = $revisionStore;
	}

}
