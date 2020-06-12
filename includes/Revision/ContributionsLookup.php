<?php

namespace MediaWiki\Revision;

use ContribsPager;
use FauxRequest;
use MediaWiki\User\UserIdentity;
use RequestContext;

/**
 * @since 1.35
 */
class ContributionsLookup {

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * ContributionsLookup constructor.
	 *
	 * @param RevisionStore $revisionStore
	 */
	public function __construct( RevisionStore $revisionStore ) {
		$this->revisionStore = $revisionStore;
	}

	// FIXME Make this testable
	private function getPagerParams( $limit, $segment ) {
		return [
			'limit' => $limit,
			'offset' => $segment,
		];
	}

	/**
	 * @param UserIdentity $user
	 *
	 * @param int $limit
	 * @param string|null $segment // TODO: implement $segment
	 * @return ContributionsSegment
	 */
	public function getRevisionsByUser(
		UserIdentity $user,
		int $limit,
		string $segment = null
	): ContributionsSegment {
		// FIXME: set acting user
		$context = new RequestContext();
		$paramArr = $this->getPagerParams( $limit, $segment );
		$context->setRequest( new FauxRequest( $paramArr ) );

		// TODO: explore moving this to factory method for testing
		$pager = new ContribsPager( $context, [
			'target' => $user->getName(),
		] );
		$revisions = [];
		$count = 0;
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				// We retrieve and ignore one extra record to see if we are on the oldest segment.
				if ( ++$count > $limit ) {
					break;
				}

				// TODO: pre-load title batch?
				$revision = $this->revisionStore->newRevisionFromRow( $row, 0 );
				$revisions[] = $revision;
			}
		}

		$pagingQueries = $pager->getPagingQueries();

		$after = $pagingQueries['prev']['offset'] ?? null; // later in time
		$before = $pagingQueries['next']['offset'] ?? null; // earlier in time
		$flags = [
			'oldest' => !$before
		];
		return new ContributionsSegment( $revisions, $before, $after, $flags );
	}
}
