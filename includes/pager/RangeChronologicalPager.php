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

/**
 * Pager for filtering by a range of dates.
 * @ingroup Pager
 */
abstract class RangeChronologicalPager extends ChronologicalPager {

	protected $rangeCond = [];

	/**
	 * Set and return a date range condition using timestamps provided by the user.
	 * We want the revisions between the two timestamps.
	 * Also supports only having a start or end timestamp.
	 * Assumes that the start timestamp comes before the end timestamp.
	 * @param string $startStamp Timestamp of the beginning of the date range
	 * @param string $endStamp Timestamp of the end of the date range
	 */
	function getDateRangeCond( $startStamp, $endStamp ) {
		$rangeCond = [];

		if ( $startStamp !== false ) {
			$startDate = new DateTime( $startStamp );
			$startTimestamp = new MWTimestamp( $startDate->getTimestamp() );
			$startTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
			$startOffset = $this->mDb->timestamp( $startTimestamp->getTimestamp() );
			$rangeCond[] = $this->mIndexField . '>=' . $this->mDb->addQuotes( $startOffset );
		}

		if ( $endStamp !== false ) {
			$endDate = new DateTime( $endStamp );
			$endDate = $endDate->modify( '+1 day' );
			$endTimestamp = new MWTimestamp( $endDate->getTimestamp() );
			$endTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
			$endOffset = $this->mDb->timestamp( $endTimestamp->getTimestamp() );
			$rangeCond[] = $this->mIndexField . '<' . $this->mDb->addQuotes( $endOffset );
		}

		$this->rangeCond = $rangeCond;
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

		$conds = array_merge( $conds, $this->rangeCond );

		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}
}
