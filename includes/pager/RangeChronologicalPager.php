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
 * @ingroup Pager
 */
use Wikimedia\Timestamp\TimestampException;

/**
 * Pager for filtering by a range of dates.
 * @ingroup Pager
 */
abstract class RangeChronologicalPager extends ReverseChronologicalPager {

	protected $rangeConds = [];

	/**
	 * Set and return a date range condition using timestamps provided by the user.
	 * We want the revisions between the two timestamps.
	 * Also supports only having a start or end timestamp.
	 * Assumes that the start timestamp comes before the end timestamp.
	 *
	 * @param string $startStamp Timestamp of the beginning of the date range (or empty)
	 * @param string $endStamp Timestamp of the end of the date range (or empty)
	 * @return array|null Database conditions to satisfy the specified date range
	 *     or null if dates are invalid
	 */
	public function getDateRangeCond( $startStamp, $endStamp ) {
		$this->rangeConds = [];

		try {
			if ( $startStamp !== '' ) {
				$startTimestamp = MWTimestamp::getInstance( $startStamp );
				$startTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
				$startOffset = $this->mDb->timestamp( $startTimestamp->getTimestamp() );
				$this->rangeConds[] = $this->mIndexField . '>=' . $this->mDb->addQuotes( $startOffset );
			}

			if ( $endStamp !== '' ) {
				$endTimestamp = MWTimestamp::getInstance( $endStamp );
				$endTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
				$endOffset = $this->mDb->timestamp( $endTimestamp->getTimestamp() );
				$this->rangeConds[] = $this->mIndexField . '<=' . $this->mDb->addQuotes( $endOffset );

				// populate existing variables for compatibility with parent
				$this->mYear = (int)$endTimestamp->format( 'Y' );
				$this->mMonth = (int)$endTimestamp->format( 'm' );
				$this->mDay = (int)$endTimestamp->format( 'd' );
				$this->mOffset = $endOffset;
			}
		} catch ( TimestampException $ex ) {
			return null;
		}

		return $this->rangeConds;
	}

	/**
	 * Takes ReverseChronologicalPager::getDateCond parameters and repurposes
	 * them to work with timestamp-based getDateRangeCond.
	 *
	 * @param int $year Year up to which we want revisions
	 * @param int $month Month up to which we want revisions
	 * @param int $day [optional] Day up to which we want revisions. Default is end of month.
	 * @return string|null Timestamp or null if year and month are false/invalid
	 */
	public function getDateCond( $year, $month, $day = -1 ) {
		// run through getDateRangeCond so rangeConds, mOffset, ... are set
		$legacyTimestamp = self::getOffsetDate( $year, $month, $day );
		// ReverseChronologicalPager uses strict inequality for the end date ('<'),
		// but this class uses '<=' and expects extending classes to handle modifying the end date.
		// Therefore, we need to subtract one second from the output of getOffsetDate to make it
		// work with the '<=' inequality used in this class.
		$legacyTimestamp->timestamp = $legacyTimestamp->timestamp->modify( '-1 second' );
		$this->getDateRangeCond( '', $legacyTimestamp->getTimestamp( TS_MW ) );
		return $this->mOffset;
	}

	/**
	 * Build variables to use by the database wrapper.
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return array
	 */
	protected function buildQueryInfo( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = parent::buildQueryInfo(
			$offset,
			$limit,
			$descending
		);

		if ( $this->rangeConds ) {
			$conds = array_merge( $conds, $this->rangeConds );
		}

		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}
}
