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

/**
 * IndexPager with a formatted navigation bar
 * @ingroup Pager
 */
abstract class ReverseChronologicalPager extends IndexPager {
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;
	public $mYear;
	public $mMonth;

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
	 * Returns the timestamp less than or greater than the one represented by the
	 * year and month in the direction specified. 1 means forward and 0 otherwise
	 *
	 * @param int $year
	 * @param int $month
	 * @param boolean $direction True means date forward and backwards otherwise
	 * @return int $timestamp
	 */
	function getDateTimeCond( $year, $month, $direction = true ) {
		$year = intval( $year );
		$month = intval( $month );

		// Basic validity checks
		$this->mYear = $year > 0 ? $year : false;
		$this->mMonth = ( $month > 0 && $month < 13 ) ? $month : false;

		// Given an optional year and month, we need to generate a timestamp
		// to use as "WHERE rev_timestamp <= result" or
		// "WHERE rev_timestamp > result" depending upon "dir" parameter
		// Examples: year = 2006 equals < 20070101 (+000000) with dir = 1
		// year=2005, month=1    equals < 20050201 with dir = 1
		// year=2005, month=12   equals < 20051130 with dir = 0
		if ( !$this->mYear && !$this->mMonth ) {
			return;
		}

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
			$month = $direction ? $this->mMonth + 1:$this->mMonth - 1;
			// For December, we want January 1 of the next year
			if ( $month > 12 && $direction ) {
				$month = 1;
				$year++;
			} elseif ( $month < 1 && !$direction ) {
				$month = 12;
				$year--;
			}
		} else {
			// No month implies we want up to the end of the year in question
			$month = $direction ? 1 : 12;
			$year = $direction ? $year + 1:$year - 1;
		}

		// Y2K38 bug
		if ( $year > 2032 ) {
			$year = 2032;
		}

		$ymd = $direction == 1?(int)sprintf( "%04d%02d01", $year, $month ):
			(int)sprintf( "%04d%02d30", $year, $month );

		if ( $ymd > 20320101 ) {
			$ymd = 20320101;
		}

		// Treat the given time in the wiki timezone and get a UTC timestamp for the database lookup
		$timestamp = MWTimestamp::getInstance( "${ymd}000000" );
		$timestamp->setTimezone( $this->getConfig()->get( 'Localtimezone' ) );
		return $this->mDb->timestamp( $timestamp->getTimestamp() );
	}

	function getDateCond( $year, $month ) {
		$this->mOffset = $this->getDateTimeCond( $year, $month, true );
	}
}
