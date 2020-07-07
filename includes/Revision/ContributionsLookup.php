<?php

namespace MediaWiki\Revision;

use ContribsPager;
use FauxRequest;
use MediaWiki\User\UserIdentity;
use RequestContext;
use User;

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

	/**
	 * Constructs fake query parameters to be passed to ContribsPager
	 *
	 * @param int $limit Maximum number of revisions to return.
	 * @param string $segment Indicates which segment of the contributions to return.
	 * The segment should consist of 2 parts separated by a pipe character.
	 * The first part is mapped to the 'dir' parameter.
	 * The second part is mapped to the 'offset' parameter.
	 * The value for the offset is opaque and is ultimately supplied by ContribsPager::getPagingQueries().
	 * @return array
	 */
	private function getPagerParams( int $limit, string $segment ) {
		$dir = 'next';
		$seg = explode( '|', $segment, 2 );
		if ( count( $seg ) > 1 ) {
			if ( $seg[0] === 'after' ) {
				$dir = 'prev';
				$segment = $seg[1];
			} elseif ( $seg[0] == 'before' ) {
				$dir = 'next';
				$segment = $seg[1];
			} else {
				$dir = null;
				$segment = null;
			}
		} else {
			$segment = null;
		}
		return [
			'limit' => $limit,
			'offset' => $segment,
			'dir' => $dir
		];
	}

	/**
	 * @param UserIdentity $target the user from whom to retrieve contributions
	 * @param int $limit the maximum number of revisions to return
	 * @param User $performer the user used for permission checks
	 * @param string $segment
	 * @param string|null $tag
	 *
	 * @return ContributionsSegment
	 * @throws \MWException
	 */
	public function getContributions(
		UserIdentity $target,
		int $limit,
		User $performer,
		string $segment = '',
		string $tag = null
	): ContributionsSegment {
		$context = new RequestContext();
		$context->setUser( $performer );

		$paramArr = $this->getPagerParams( $limit, $segment );
		$context->setRequest( new FauxRequest( $paramArr ) );

		// TODO: explore moving this to factory method for testing
		$pager = new ContribsPager( $context, [
			'target' => $target->getName(),
			'tagfilter' => $tag,
		] );
		$revisions = [];
		$tags = [];
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
				$tags[ $row->rev_id ] =
					$row->ts_tags ? explode( ',', $row->ts_tags ) : [];
			}
		}

		$deltas = $this->getContributionDeltas( $revisions );

		$flags = [
			'newest' => $pager->mIsFirst,
			'oldest' => $pager->mIsLast,
		];

		// TODO: Make me an option in IndexPager
		$pager->mIsFirst = false; // XXX: nasty...
		$pagingQueries = $pager->getPagingQueries();

		$prev = $pagingQueries['prev']['offset'] ?? null;
		$next = $pagingQueries['next']['offset'] ?? null;

		$after = $prev ? 'after|' . $prev : null; // later in time
		$before = $next ? 'before|' . $next : null; // earlier in time

		// TODO: Possibly return public $pager properties to segment for populating URLS ($mIsFirst, $mIsLast)
		// HACK: Force result set order to be descending. Sorting logic in ContribsPager::reallyDoQuery is confusing.
		if ( $paramArr['dir'] === 'prev' ) {
			$revisions = array_reverse( $revisions );
		}
		return new ContributionsSegment( $revisions, $tags, $before, $after,  $deltas, $flags );
	}

	/**
	 * Gets size deltas of a revision and its parent revision
	 * @param RevisionRecord[] $revisions
	 * @return int[] Associative array of revision ids and their deltas.
	 *  If revision is the first on a page, delta is revision size.
	 *  If parent revision is unknown, delta is null.
	 */
	private function getContributionDeltas( $revisions ) {
		// SpecialContributions uses the size of the revision if the parent revision is unknown. Cases include:
		// - revision has been deleted
		// - parent rev id has not been populated (this is the case for very old revisions)
		$parentIds = [];
		foreach ( $revisions as $revision ) {
			$revId = $revision->getId();
			$parentIds[$revId] = $revision->getParentId();
		}
		$parentSizes = $this->revisionStore->getRevisionSizes( $parentIds );
		$deltas = [];
		foreach ( $revisions as $revision ) {
			$parentId = $revision->getParentId();
			if ( $parentId === 0 ) { // first revision on a page
				$delta = $revision->getSize();
			} elseif ( !isset( $parentSizes[$parentId] ) ) { // parent revision is either deleted or untracked
				$delta = null;
			} else {
				$delta = $revision->getSize() - $parentSizes[$parentId];
			}
			$deltas[ $revision->getId() ] = $delta;
		}
		return $deltas;
	}

	/**
	 * Returns the number of edits by the given user.
	 *
	 * @param UserIdentity $user
	 * @param User $performer the user used for permission checks
	 * @param string|null $tag
	 *
	 * @return int
	 */
	public function getContributionCount( UserIdentity $user, User $performer, $tag = null ): int {
		$context = new RequestContext();
		$context->setUser( $performer );
		$context->setRequest( new FauxRequest( [] ) );

		// TODO: explore moving this to factory method for testing
		$pager = new ContribsPager( $context, [
			'target' => $user->getName(),
			'tagfilter' => $tag,
		] );

		$query = $pager->getQueryInfo();

		$count = $pager->mDb->selectField(
			$query['tables'],
			'COUNT(*)',
			$query['conds'],
			__METHOD__,
			[],
			$query['join_conds']
		);

		// FIXME: this count does not include contributions that extensions would be injecting
		//   via the ContribsPager__reallyDoQuery.

		return (int)$count;
	}
}
