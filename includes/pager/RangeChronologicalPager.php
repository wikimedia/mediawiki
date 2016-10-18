<?php
/**
 * Created by IntelliJ IDEA.
 * User: gmon
 * Date: 12/11/16
 * Time: 10:04 PM
 */

abstract class RangeChronologicalPager extends ChronologicalPager {
	
	protected $start = false;
	protected $end = false;
	
	/**
	 * Set and return a date range condition using timestamps provided by the user.
	 * We want the revisions between the two timestamps.
	 * Also supports only having a start or end timestamp.
	 * Assumes that the start timestamp comes before the end timestamp.
	 *
	 * @return array Conditions to fulfill date range
	 */
	function getDateRangeCond() {
		$rangeCond = [];

		if ( $this->start !== false ) {
			$startDate = new DateTime( $this->start );
			$startTimestamp = new MWTimestamp( $startDate->getTimestamp() );
			$startTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
			$startOffset = $this->mDb->timestamp( $startTimestamp->getTimestamp() );
			$rangeCond[] = $this->mIndexField . '>=' . $this->mDb->addQuotes( $startOffset );
		}

		if ( $this->end !== false ) {
			$endDate = new DateTime( $this->end );
			$endDate = $endDate->modify( '+1 day' );
			$endTimestamp = new MWTimestamp( $endDate->getTimestamp() );
			$endTimestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
			$endOffset = $this->mDb->timestamp( $endTimestamp->getTimestamp() );
			$rangeCond[] = $this->mIndexField . '<' . $this->mDb->addQuotes( $endOffset );
		}

		return $rangeCond;
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

		$conds = array_merge( $conds, $this->getDateRangeCond() );

		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}
}