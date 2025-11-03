<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\ChangeTags\ChangeTagsStore;
use Psr\Log\LoggerInterface;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

class ChangeTagsCondition extends ChangesListConditionBase {
	private float|int $denseRcSizeThreshold = 10000;

	private ?int $limit = null;
	private bool $densityThresholdReached = true;

	public function __construct(
		private ChangeTagsStore $changeTagsStore,
		private TableStatsProvider $rcStats,
		private LoggerInterface $logger,
		private bool $miserMode,
	) {
	}

	/**
	 * Set the query limit to be used for density heuristics
	 *
	 * @param int $limit
	 */
	public function setLimit( int $limit ) {
		$this->limit = $limit;
	}

	/**
	 * @param bool $reached Whether the query density is high enough to apply
	 *   heuristics for a straight join
	 */
	public function setDensityThresholdReached( bool $reached ) {
		$this->densityThresholdReached = $reached;
	}

	/**
	 * Set the minimum size of the recentchanges table at which change tag
	 * queries will be conditionally modified based on estimated density.
	 *
	 * @param float|int $threshold
	 */
	public function setDenseRcSizeThreshold( $threshold ) {
		$this->denseRcSizeThreshold = $threshold;
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( !is_scalar( $value ) ) {
			throw new \InvalidArgumentException( "Change tag must be string-like" );
		}
		return (string)$value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( !$row->ts_tags ) {
			return false;
		}
		return in_array( $value, explode( ',', $row->ts_tags ), true );
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( [
			'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'recentchanges' )
		] );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$ids = array_values( $this->changeTagsStore->getTagIdsFromNames( $required ) );
			if ( !$ids ) {
				// All tags were invalid, return nothing
				$query->forceEmptySet();
				return;
			}

			$join = $query->joinForConds( 'change_tag' );

			// Workaround for T298225: MySQL's lack of awareness of LIMIT when
			// choosing the join order.
			if ( $this->isDenseTagFilter( $dbr, $ids ) ) {
				$join->straight();
			} else {
				$join->reorderable();
			}

			$query->where( $dbr->expr( 'changetagdisplay.ct_tag_id', '=', $ids ) );
			if ( count( $ids ) > 1 ) {
				$query->distinct();
			}
		} elseif ( $excluded ) {
			$ids = array_values( $this->changeTagsStore->getTagIdsFromNames( $excluded ) );
			if ( !$ids ) {
				// No valid tags were excluded
				return;
			}
			$query->joinForConds( 'change_tag' )->left()
				->on( $dbr->expr( 'changetagdisplay.ct_tag_id', '=', $ids ) );
			$query->where( $dbr->expr( 'changetagdisplay.ct_tag_id', '=', null ) );
		}
	}

	/**
	 * Determine whether a tag filter matches a high proportion of the rows in
	 * recentchanges. If so, it is more efficient to scan recentchanges,
	 * filtering out non-matching rows, rather than scanning change_tag and
	 * then filesorting on rc_timestamp. MySQL is especially bad at making this
	 * judgement (T298225).
	 *
	 * @param IReadableDatabase $dbr
	 * @param int[] $tagIds
	 * @return bool
	 */
	protected function isDenseTagFilter( IReadableDatabase $dbr, array $tagIds ) {
		if ( !$tagIds
			// Only on RecentChanges or similar
			|| !$this->densityThresholdReached
			// Need a limit
			|| !$this->limit
			// This is a MySQL-specific hack
			|| $dbr->getType() !== 'mysql'
			// Unnecessary for small wikis
			|| !$this->miserMode
		) {
			return false;
		}

		$rcSize = $this->rcStats->getIdDelta();
		if ( $rcSize < $this->denseRcSizeThreshold ) {
			// RC is too small to worry about
			return false;
		}
		$tagCount = $dbr->newSelectQueryBuilder()
			->table( 'change_tag' )
			->where( [
				$dbr->expr( 'ct_rc_id', '>=', $this->rcStats->getMinId() ),
				'ct_tag_id' => $tagIds
			] )
			->caller( __METHOD__ )
			->estimateRowCount();

		// If we scan recentchanges first, the number of rows examined will be
		// approximately the limit divided by the proportion of tagged rows,
		// i.e. $limit / ( $tagCount / $rcSize ). If that's less than $tagCount,
		// use a straight join. The inequality below is rearranged for
		// simplicity and to avoid division by zero.
		$isDense = $this->limit * $rcSize < $tagCount * $tagCount;

		$this->logger->debug( __METHOD__ .
			": rcSize = $rcSize, tagCount = $tagCount, limit = {$this->limit} => " .
			( $isDense ? 'dense' : 'sparse' ) );
		return $isDense;
	}
}
