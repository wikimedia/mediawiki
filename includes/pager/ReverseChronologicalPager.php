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

use DateTime;
use MediaWiki\Html\Html;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Timestamp\TimestampException;

/**
 * IndexPager with a formatted navigation bar.
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class ReverseChronologicalPager extends IndexPager {
	/** @var bool */
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;
	/** @var bool Whether to group items by date */
	public $mGroupByDate = false;
	/** @var int */
	public $mYear;
	/** @var int */
	public $mMonth;
	/** @var int */
	public $mDay;
	/** @var string */
	private $lastHeaderDate;
	/** @var string */
	protected $endOffset;

	protected function getHeaderRow( string $date ): string {
		$headingClass = $this->isFirstHeaderRow() ?
			// We use mw-index-pager- prefix here on the anticipation that this method will
			// eventually be upstreamed to apply to other pagers. For now we constrain the
			// change to ReverseChronologicalPager to reduce the risk of pages this touches
			// in case there are any bugs.
			'mw-index-pager-list-header-first mw-index-pager-list-header' :
			'mw-index-pager-list-header';

		$s = $this->isFirstHeaderRow() ? '' : $this->getEndGroup();
		$s .= Html::element( 'h4', [
				'class' => $headingClass,
			],
			$date
		);
		$s .= $this->getStartGroup();
		return $s;
	}

	/**
	 * Determines if a header row is needed based on the current state of the IndexPager.
	 *
	 * @since 1.38
	 * @param string $date Formatted date header
	 * @return bool
	 */
	protected function isHeaderRowNeeded( string $date ): bool {
		if ( !$this->mGroupByDate ) {
			return false;
		}
		return $date && $this->lastHeaderDate !== $date;
	}

	/**
	 * Determines whether the header row is the first that will be outputted to the page.
	 *
	 * @since 1.38
	 * @return bool
	 */
	final protected function isFirstHeaderRow(): bool {
		return $this->lastHeaderDate === null;
	}

	/**
	 * Returns the name of the timestamp field. Subclass can override this to provide the
	 * timestamp field if they are using a aliased field for getIndexField()
	 *
	 * @since 1.40
	 * @return string
	 */
	public function getTimestampField() {
		// This is a chronological pager, so the first column should be some kind of timestamp
		return is_array( $this->mIndexField ) ? $this->mIndexField[0] : $this->mIndexField;
	}

	/**
	 * Get date from the timestamp
	 *
	 * @since 1.38
	 * @param string $timestamp
	 * @return string Formatted date header
	 */
	final protected function getDateFromTimestamp( string $timestamp ) {
		return $this->getLanguage()->userDate( $timestamp, $this->getUser() );
	}

	/**
	 * @inheritDoc
	 */
	protected function getRow( $row ): string {
		$s = '';

		$timestampField = $this->getTimestampField();
		$timestamp = $row->$timestampField ?? null;
		$date = $timestamp ? $this->getDateFromTimestamp( $timestamp ) : null;
		if ( $date && $this->isHeaderRowNeeded( $date ) ) {
			$s .= $this->getHeaderRow( $date );
			$this->lastHeaderDate = $date;
		}

		$s .= $this->formatRow( $row );
		return $s;
	}

	/**
	 * Start a new group of page rows.
	 *
	 * @stable to override
	 * @since 1.38
	 * @return string
	 */
	protected function getStartGroup(): string {
		return "<ul class=\"mw-contributions-list\">\n";
	}

	/**
	 * End an existing group of page rows.
	 *
	 * @stable to override
	 * @since 1.38
	 * @return string
	 */
	protected function getEndGroup(): string {
		return '</ul>';
	}

	/**
	 * @inheritDoc
	 */
	protected function getFooter(): string {
		return $this->getEndGroup();
	}

	/**
	 * @stable to override
	 * @return string HTML
	 */
	public function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( $this->mNavigationBar !== null ) {
			return $this->mNavigationBar;
		}

		$navBuilder = $this->getNavigationBuilder()
			->setPrevMsg( 'pager-newer-n' )
			->setNextMsg( 'pager-older-n' )
			->setFirstMsg( 'histlast' )
			->setLastMsg( 'histfirst' );

		$this->mNavigationBar = $navBuilder->getHtml();

		return $this->mNavigationBar;
	}

	/**
	 * Set and return the offset timestamp such that we can get all revisions with
	 * a timestamp up to the specified parameters.
	 *
	 * @stable to override
	 *
	 * @param int $year Year up to which we want revisions
	 * @param int $month Month up to which we want revisions
	 * @param int $day [optional] Day up to which we want revisions. Default is end of month.
	 * @return string|null Timestamp or null if year and month are false/invalid
	 */
	public function getDateCond( $year, $month, $day = -1 ) {
		$year = (int)$year;
		$month = (int)$month;
		$day = (int)$day;

		// Basic validity checks for year and month
		// If year and month are invalid, don't update the offset
		if ( $year <= 0 && ( $month <= 0 || $month >= 13 ) ) {
			return null;
		}

		$timestamp = self::getOffsetDate( $year, $month, $day );

		try {
			// The timestamp used for DB queries is at midnight of the *next* day after the selected date.
			$selectedDate = new DateTime( $timestamp->getTimestamp( TS_ISO_8601 ) );
			$selectedDate = $selectedDate->modify( '-1 day' );

			$this->mYear = (int)$selectedDate->format( 'Y' );
			$this->mMonth = (int)$selectedDate->format( 'm' );
			$this->mDay = (int)$selectedDate->format( 'd' );
			// Don't mess with mOffset which IndexPager uses
			$this->endOffset = $this->mDb->timestamp( $timestamp->getTimestamp() );
		} catch ( TimestampException ) {
			// Invalid user provided timestamp (T149257)
			return null;
		}

		return $this->endOffset;
	}

	/**
	 * Core logic of determining the offset timestamp such that we can get all items with
	 * a timestamp up to the specified parameters. Given parameters for a day up to which to get
	 * items, this function finds the timestamp of the day just after the end of the range for use
	 * in a database strict inequality filter.
	 *
	 * This is separate from getDateCond so we can use this logic in other places, such as in
	 * RangeChronologicalPager, where this function is used to convert year/month/day filter options
	 * into a timestamp.
	 *
	 * @param int $year Year up to which we want revisions
	 * @param int $month Month up to which we want revisions
	 * @param int $day [optional] Day up to which we want revisions. Default is end of month.
	 * @return MWTimestamp Timestamp or null if year and month are false/invalid
	 */
	public static function getOffsetDate( $year, $month, $day = -1 ) {
		// Given an optional year, month, and day, we need to generate a timestamp
		// to use as "WHERE rev_timestamp <= result"
		// Examples: year = 2006      equals < 20070101 (+000000)
		// year=2005, month=1         equals < 20050201
		// year=2005, month=12        equals < 20060101
		// year=2005, month=12, day=5 equals < 20051206
		if ( $year <= 0 ) {
			// If no year given, assume the current one
			$timestamp = MWTimestamp::getInstance();
			$year = $timestamp->format( 'Y' );
			// If this month hasn't happened yet this year, go back to last year's month
			if ( $month > $timestamp->format( 'n' ) ) {
				$year--;
			}
		}

		if ( $month && $month > 0 && $month < 13 ) {
			// Day validity check after we have month and year checked
			$day = checkdate( $month, $day, $year ) ? $day : false;

			if ( $day && $day > 0 ) {
				// If we have a day, we want up to the day immediately afterward
				$day++;

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

		$ymd = sprintf( "%04d%02d%02d", $year, $month, $day );

		return MWTimestamp::getInstance( "{$ymd}000000" );
	}

	/**
	 * Return the end offset, extensions can use this if they are not in the context of subclass.
	 *
	 * @since 1.40
	 * @return string
	 */
	public function getEndOffset() {
		return $this->endOffset;
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
		if ( $this->endOffset ) {
			$conds[] = $this->mDb->expr( $this->getTimestampField(), '<', $this->endOffset );
		}

		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ReverseChronologicalPager::class, 'ReverseChronologicalPager' );
