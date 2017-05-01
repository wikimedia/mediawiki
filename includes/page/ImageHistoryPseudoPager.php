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

class ImageHistoryPseudoPager extends ReverseChronologicalPager {
	protected $preventClickjacking = false;

	/**
	 * @var File
	 */
	protected $mImg;

	/**
	 * @var Title
	 */
	protected $mTitle;

	/**
	 * @since 1.14
	 * @var ImagePage
	 */
	public $mImagePage;

	/**
	 * @since 1.14
	 * @var File[]
	 */
	public $mHist;

	/**
	 * @since 1.14
	 * @var int[]
	 */
	public $mRange;

	/**
	 * @param ImagePage $imagePage
	 */
	public function __construct( $imagePage ) {
		parent::__construct( $imagePage->getContext() );
		$this->mImagePage = $imagePage;
		$this->mTitle = clone $imagePage->getTitle();
		$this->mTitle->setFragment( '#filehistory' );
		$this->mImg = null;
		$this->mHist = [];
		$this->mRange = [ 0, 0 ]; // display range

		// Only display 10 revisions at once by default, otherwise the list is overwhelming
		$this->mLimitsShown = array_merge( [ 10 ], $this->mLimitsShown );
		$this->mDefaultLimit = 10;
		list( $this->mLimit, /* $offset */ ) =
			$this->mRequest->getLimitOffset( $this->mDefaultLimit, '' );
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	public function getQueryInfo() {
		return false;
	}

	/**
	 * @return string
	 */
	public function getIndexField() {
		return '';
	}

	/**
	 * @param object $row
	 * @return string
	 */
	public function formatRow( $row ) {
		return '';
	}

	/**
	 * @return string
	 */
	public function getBody() {
		$s = '';
		$this->doQuery();
		if ( count( $this->mHist ) ) {
			if ( $this->mImg->isLocal() ) {
				// Do a batch existence check for user pages and talkpages
				$linkBatch = new LinkBatch();
				for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
					$file = $this->mHist[$i];
					$user = $file->getUser( 'text' );
					$linkBatch->add( NS_USER, $user );
					$linkBatch->add( NS_USER_TALK, $user );
				}
				$linkBatch->execute();
			}

			$list = new ImageHistoryList( $this->mImagePage );
			# Generate prev/next links
			$navLink = $this->getNavigationBar();
			$s = $list->beginImageHistoryList( $navLink );
			// Skip rows there just for paging links
			for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
				$file = $this->mHist[$i];
				$s .= $list->imageHistoryLine( !$file->isOld(), $file );
			}
			$s .= $list->endImageHistoryList( $navLink );

			if ( $list->getPreventClickjacking() ) {
				$this->preventClickjacking();
			}
		}
		return $s;
	}

	public function doQuery() {
		if ( $this->mQueryDone ) {
			return;
		}
		$this->mImg = $this->mImagePage->getPage()->getFile(); // ensure loading
		if ( !$this->mImg->exists() ) {
			return;
		}
		$queryLimit = $this->mLimit + 1; // limit plus extra row
		if ( $this->mIsBackwards ) {
			// Fetch the file history
			$this->mHist = $this->mImg->getHistory( $queryLimit, null, $this->mOffset, false );
			// The current rev may not meet the offset/limit
			$numRows = count( $this->mHist );
			if ( $numRows <= $this->mLimit && $this->mImg->getTimestamp() > $this->mOffset ) {
				$this->mHist = array_merge( [ $this->mImg ], $this->mHist );
			}
		} else {
			// The current rev may not meet the offset
			if ( !$this->mOffset || $this->mImg->getTimestamp() < $this->mOffset ) {
				$this->mHist[] = $this->mImg;
			}
			// Old image versions (fetch extra row for nav links)
			$oiLimit = count( $this->mHist ) ? $this->mLimit : $this->mLimit + 1;
			// Fetch the file history
			$this->mHist = array_merge( $this->mHist,
				$this->mImg->getHistory( $oiLimit, $this->mOffset, null, false ) );
		}
		$numRows = count( $this->mHist ); // Total number of query results
		if ( $numRows ) {
			# Index value of top item in the list
			$firstIndex = $this->mIsBackwards ?
				$this->mHist[$numRows - 1]->getTimestamp() : $this->mHist[0]->getTimestamp();
			# Discard the extra result row if there is one
			if ( $numRows > $this->mLimit && $numRows > 1 ) {
				if ( $this->mIsBackwards ) {
					# Index value of item past the index
					$this->mPastTheEndIndex = $this->mHist[0]->getTimestamp();
					# Index value of bottom item in the list
					$lastIndex = $this->mHist[1]->getTimestamp();
					# Display range
					$this->mRange = [ 1, $numRows - 1 ];
				} else {
					# Index value of item past the index
					$this->mPastTheEndIndex = $this->mHist[$numRows - 1]->getTimestamp();
					# Index value of bottom item in the list
					$lastIndex = $this->mHist[$numRows - 2]->getTimestamp();
					# Display range
					$this->mRange = [ 0, $numRows - 2 ];
				}
			} else {
				# Setting indexes to an empty string means that they will be
				# omitted if they would otherwise appear in URLs. It just so
				# happens that this  is the right thing to do in the standard
				# UI, in all the relevant cases.
				$this->mPastTheEndIndex = '';
				# Index value of bottom item in the list
				$lastIndex = $this->mIsBackwards ?
					$this->mHist[0]->getTimestamp() : $this->mHist[$numRows - 1]->getTimestamp();
				# Display range
				$this->mRange = [ 0, $numRows - 1 ];
			}
		} else {
			$firstIndex = '';
			$lastIndex = '';
			$this->mPastTheEndIndex = '';
		}
		if ( $this->mIsBackwards ) {
			$this->mIsFirst = ( $numRows < $queryLimit );
			$this->mIsLast = ( $this->mOffset == '' );
			$this->mLastShown = $firstIndex;
			$this->mFirstShown = $lastIndex;
		} else {
			$this->mIsFirst = ( $this->mOffset == '' );
			$this->mIsLast = ( $numRows < $queryLimit );
			$this->mLastShown = $lastIndex;
			$this->mFirstShown = $firstIndex;
		}
		$this->mQueryDone = true;
	}

	/**
	 * @param bool $enable
	 */
	protected function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}
