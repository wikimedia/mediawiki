<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Pager;

use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Timestamp\TimestampException;

/**
 * Pager for filtering by a range of dates.
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class RangeChronologicalPager extends ReverseChronologicalPager {

	/**
	 * @var string[]
	 * @deprecated since 1.40, use $startOffset and $endOffset instead.
	 */
	protected $rangeConds;

	/** @var string */
	protected $startOffset;

	/**
	 * Set and return a date range condition using timestamps provided by the user.
	 * We want the revisions between the two timestamps.
	 * Also supports only having a start or end timestamp.
	 * Assumes that the start timestamp comes before the end timestamp.
	 *
	 * @stable to override
	 *
	 * @param string $startTime Timestamp of the beginning of the date range (or empty)
	 * @param string $endTime Timestamp of the end of the date range (or empty)
	 * @return array|null Database conditions to satisfy the specified date range
	 *     or null if dates are invalid
	 */
	public function getDateRangeCond( $startTime, $endTime ) {
		// Construct the conds array for compatibility with callers and derived classes
		$this->rangeConds = [];

		try {
			if ( $startTime !== '' ) {
				$startTimestamp = MWTimestamp::getInstance( $startTime );
				$this->startOffset = $this->mDb->timestamp( $startTimestamp->getTimestamp() );
				$this->rangeConds[] = $this->mDb->buildComparison( '>=',
					[ $this->getTimestampField() => $this->startOffset ] );
			}

			if ( $endTime !== '' ) {
				$endTimestamp = MWTimestamp::getInstance( $endTime );
				// Turned to use '<' for consistency with the parent class,
				// add one second for compatibility with existing use cases
				$endTimestamp->timestamp = $endTimestamp->timestamp->modify( '+1 second' );
				$this->endOffset = $this->mDb->timestamp( $endTimestamp->getTimestamp() );
				$this->rangeConds[] = $this->mDb->buildComparison( '<',
					[ $this->getTimestampField() => $this->endOffset ] );

				// populate existing variables for compatibility with parent
				$this->mYear = (int)$endTimestamp->format( 'Y' );
				$this->mMonth = (int)$endTimestamp->format( 'm' );
				$this->mDay = (int)$endTimestamp->format( 'd' );
			}
		} catch ( TimestampException ) {
			return null;
		}

		return $this->rangeConds;
	}

	/**
	 * Return the range of date offsets, in the format of [ endOffset, startOffset ].
	 * Extensions can use this to get the range if they are not in the context of subclasses.
	 *
	 * @since 1.40
	 * @return string[]
	 */
	public function getRangeOffsets() {
		return [ $this->endOffset, $this->startOffset ];
	}

	/**
	 * @inheritDoc
	 */
	protected function buildQueryInfo( $offset, $limit, $order ) {
		[ $tables, $fields, $conds, $fname, $options, $join_conds ] = parent::buildQueryInfo(
			$offset,
			$limit,
			$order
		);
		// End of the range has been added by ReverseChronologicalPager
		if ( $this->startOffset ) {
			$conds[] = $this->mDb->expr( $this->getTimestampField(), '>=', $this->startOffset );
		} elseif ( $this->rangeConds ) {
			// Keep compatibility with some derived classes, T325034
			$conds = array_merge( $conds, $this->rangeConds );
		}

		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}
}

/** @deprecated class alias since 1.41 */
class_alias( RangeChronologicalPager::class, 'RangeChronologicalPager' );
