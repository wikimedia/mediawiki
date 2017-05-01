<?php
/**
 * Efficient paging for SQL queries.
 *
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
 * IndexPager with a formatted navigation bar
 * @ingroup Pager
 */
abstract class ReverseChronologicalPager extends IndexPager {
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;
	public $mYear;
	public $mMonth;
	public $mDay;

	function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = [
			'prev' => $this->msg( 'pager-newer-n' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'pager-older-n' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'histlast' )->escaped(),
			'last' => $this->msg( 'histfirst' )->escaped()
		];

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $this->getLanguage()->pipeList( $limitLinks );
		$firstLastLinks = $this->msg( 'parentheses' )->rawParams( "{$pagingLinks['first']}" .
			$this->msg( 'pipe-separator' )->escaped() .
			"{$pagingLinks['last']}" )->escaped();

		$this->mNavigationBar = $firstLastLinks . ' ' .
			$this->msg( 'viewprevnext' )->rawParams(
				$pagingLinks['prev'], $pagingLinks['next'], $limits )->escaped();

		return $this->mNavigationBar;
	}

	/**
	 * Set and return the mOffset timestamp such that we can get all revisions with
	 * a timestamp up to the specified parameters.
	 * @param int $year Year up to which we want revisions
	 * @param int $month Month up to which we want revisions
	 * @param int $day [optional] Day up to which we want revisions. Default is end of month.
	 * @return string|null Timestamp or null if year and month are false/invalid
	 */
	function getDateCond( $year, $month, $day = -1 ) {
		$year = intval( $year );
		$month = intval( $month );
		$day = intval( $day );

		// Basic validity checks for year and month
		$this->mYear = $year > 0 ? $year : false;
		$this->mMonth = ( $month > 0 && $month < 13 ) ? $month : false;

		// If year and month are false, don't update the mOffset
		if ( !$this->mYear && !$this->mMonth ) {
			return null;
		}

		// Given an optional year, month, and day, we need to generate a timestamp
		// to use as "WHERE rev_timestamp <= result"
		// Examples: year = 2006      equals < 20070101 (+000000)
		// year=2005, month=1         equals < 20050201
		// year=2005, month=12        equals < 20060101
		// year=2005, month=12, day=5 equals < 20051206
		if ( $this->mYear ) {
			$year = $this->mYear;
		} else {
			// If no year given, assume the current one
			$timestamp = MWTimestamp::getInstance();
			$year = $timestamp->format( 'Y' );
			// If this month hasn't happened yet this year, go back to last year's month
			if ( $this->mMonth > $timestamp->format( 'n' ) ) {
				$year--;
			}
		}

		if ( $this->mMonth ) {
			$month = $this->mMonth;

			// Day validity check after we have month and year checked
			$this->mDay = checkdate( $month, $day, $year ) ? $day : false;

			if ( $this->mDay ) {
				// If we have a day, we want up to the day immediately afterward
				$day = $this->mDay + 1;

				// Did we overflow the current month?
				if ( !checkdate( $month, $day, $year ) ) {
					$day = 1;
					$month++;
				}
			} else {
				// If no day, assume beginning of next month
				$day = 1;
				$month++;
			}

			// Did we overflow the current year?
			if ( $month > 12 ) {
				$month = 1;
				$year++;
			}

		} else {
			// No month implies we want up to the end of the year in question
			$month = 1;
			$day = 1;
			$year++;
		}

		// Y2K38 bug
		if ( $year > 2032 ) {
			$year = 2032;
		}

		$ymd = (int)sprintf( "%04d%02d%02d", $year, $month, $day );

		if ( $ymd > 20320101 ) {
			$ymd = 20320101;
		}

		// Treat the given time in the wiki timezone and get a UTC timestamp for the database lookup
		$timestamp = MWTimestamp::getInstance( "${ymd}000000" );
		$timestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );

		try {
			$this->mOffset = $this->mDb->timestamp( $timestamp->getTimestamp() );
		} catch ( TimestampException $e ) {
			// Invalid user provided timestamp (T149257)
			return null;
		}

		return $this->mOffset;
	}
}
