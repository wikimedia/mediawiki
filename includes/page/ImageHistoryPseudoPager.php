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

namespace MediaWiki\Page;

use MediaWiki\FileRepo\File\File;
use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\Pager\ReverseChronologicalPager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Timestamp\TimestampException;

class ImageHistoryPseudoPager extends ReverseChronologicalPager {
	/** @var bool */
	protected $preventClickjacking = false;

	/**
	 * @var File|null
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

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/**
	 * @param ImagePage $imagePage
	 * @param LinkBatchFactory|null $linkBatchFactory
	 */
	public function __construct( $imagePage, ?LinkBatchFactory $linkBatchFactory = null ) {
		parent::__construct( $imagePage->getContext() );
		$this->mImagePage = $imagePage;
		$this->mTitle = $imagePage->getTitle()->createFragmentTarget( 'filehistory' );
		$this->mImg = null;
		$this->mHist = [];
		$this->mRange = [ 0, 0 ]; // display range

		// Only display 10 revisions at once by default, otherwise the list is overwhelming
		array_unshift( $this->mLimitsShown, 10 );
		$this->mDefaultLimit = 10;
		[ $this->mLimit, /* $offset */ ] =
			$this->mRequest->getLimitOffsetForUser(
				$this->getUser(),
				$this->mDefaultLimit,
				''
			);
		$this->linkBatchFactory = $linkBatchFactory ?? MediaWikiServices::getInstance()->getLinkBatchFactory();
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [];
	}

	/**
	 * @return string
	 */
	public function getIndexField() {
		return '';
	}

	/**
	 * @param stdClass $row
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
				// Do a batch existence check for user pages and talkpages.
				$linkBatch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
				for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
					$file = $this->mHist[$i];
					$uploader = $file->getUploader( File::FOR_THIS_USER, $this->getAuthority() );
					if ( $uploader ) {
						$linkBatch->add( NS_USER, $uploader->getName() );
						$linkBatch->add( NS_USER_TALK, $uploader->getName() );
					}
				}
				$linkBatch->execute();
			}

			// Batch-format comments
			$comments = [];
			for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
				$file = $this->mHist[$i];
				$comments[$i] = $file->getDescription(
					File::FOR_THIS_USER,
					$this->getAuthority()
				) ?: '';
			}
			$formattedComments = MediaWikiServices::getInstance()
				->getCommentFormatter()
				->formatStrings( $comments, $this->getTitle() );

			$list = new ImageHistoryList( $this->mImagePage );
			# Generate prev/next links
			$navLink = $this->getNavigationBar();

			$s = Html::element( 'h2', [ 'id' => 'filehistory' ], $this->msg( 'filehist' )->text() ) . "\n"
				. Html::openElement( 'div', [ 'id' => 'mw-imagepage-section-filehistory' ] ) . "\n"
				. $this->msg( 'filehist-help' )->parseAsBlock()
				. $navLink . "\n";

			$sList = $list->beginImageHistoryList();
			$onlyCurrentFile = true;
			// Skip rows there just for paging links
			for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
				$file = $this->mHist[$i];
				$sList .= $list->imageHistoryLine( !$file->isOld(), $file, $formattedComments[$i] );
				$onlyCurrentFile = !$file->isOld();
			}
			$sList .= $list->endImageHistoryList();
			if ( $onlyCurrentFile || !$this->mImg->isLocal() ) {
				// It is not possible to revision-delete the current file or foreign files,
				// if there is only the current file or the file is not local, show no buttons
				$s .= $sList;
			} else {
				$s .= $this->wrapWithActionButtons( $sList );
			}
			$s .= $navLink . "\n" . Html::closeElement( 'div' ) . "\n";

			if ( $list->getPreventClickjacking() ) {
				$this->setPreventClickjacking( true );
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
		// Make sure the date (probably from user input) is valid; if not, drop it.
		if ( $this->mOffset !== null ) {
			try {
				$this->mDb->timestamp( $this->mOffset );
			} catch ( TimestampException ) {
				$this->mOffset = null;
			}
		}
		$queryLimit = $this->mLimit + 1; // limit plus extra row
		if ( $this->mIsBackwards ) {
			// Fetch the file history
			$this->mHist = $this->mImg->getHistory( $queryLimit, null, $this->mOffset, false );
			// The current rev may not meet the offset/limit
			$numRows = count( $this->mHist );
			if ( $numRows <= $this->mLimit && $this->mImg->getTimestamp() > $this->mOffset ) {
				array_unshift( $this->mHist, $this->mImg );
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
				[ $this->mHist[$numRows - 1]->getTimestamp() ] : [ $this->mHist[0]->getTimestamp() ];
			# Discard the extra result row if there is one
			if ( $numRows > $this->mLimit && $numRows > 1 ) {
				if ( $this->mIsBackwards ) {
					# Index value of item past the index
					$this->mPastTheEndIndex = [ $this->mHist[0]->getTimestamp() ];
					# Index value of bottom item in the list
					$lastIndex = [ $this->mHist[1]->getTimestamp() ];
					# Display range
					$this->mRange = [ 1, $numRows - 1 ];
				} else {
					# Index value of item past the index
					$this->mPastTheEndIndex = [ $this->mHist[$numRows - 1]->getTimestamp() ];
					# Index value of bottom item in the list
					$lastIndex = [ $this->mHist[$numRows - 2]->getTimestamp() ];
					# Display range
					$this->mRange = [ 0, $numRows - 2 ];
				}
			} else {
				# Setting indexes to an empty array means that they will be
				# omitted if they would otherwise appear in URLs. It just so
				# happens that this  is the right thing to do in the standard
				# UI, in all the relevant cases.
				$this->mPastTheEndIndex = [];
				# Index value of bottom item in the list
				$lastIndex = $this->mIsBackwards ?
					[ $this->mHist[0]->getTimestamp() ] : [ $this->mHist[$numRows - 1]->getTimestamp() ];
				# Display range
				$this->mRange = [ 0, $numRows - 1 ];
			}
		} else {
			$firstIndex = [];
			$lastIndex = [];
			$this->mPastTheEndIndex = [];
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
	 * Wrap the content with action buttons at begin and end if the user
	 * is allow to use the action buttons.
	 * @param string $formcontents
	 * @return string
	 */
	private function wrapWithActionButtons( $formcontents ) {
		if ( !$this->getAuthority()->isAllowed( 'deleterevision' ) ) {
			return $formcontents;
		}

		# Show button to hide log entries
		$s = Html::openElement(
			'form',
			[ 'action' => wfScript(), 'id' => 'mw-filehistory-deleterevision-submit' ]
		) . "\n";
		$s .= Html::hidden( 'target', $this->getTitle()->getPrefixedDBkey() ) . "\n";
		$s .= Html::hidden( 'type', 'oldimage' ) . "\n";
		$this->setPreventClickjacking( true );

		$buttons = Html::element(
			'button',
			[
				'type' => 'submit',
				'name' => 'title',
				'value' => SpecialPage::getTitleFor( 'Revisiondelete' )->getPrefixedDBkey(),
				'class' => "deleterevision-filehistory-submit mw-filehistory-deleterevision-button mw-ui-button"
			],
			$this->msg( 'showhideselectedfileversions' )->text()
		) . "\n";

		$s .= $buttons . $formcontents . $buttons;
		$s .= Html::closeElement( 'form' );

		return $s;
	}

	/**
	 * @param bool $enable
	 * @deprecated since 1.38, use ::setPreventClickjacking()
	 */
	protected function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @param bool $enable
	 * @since 1.38
	 */
	protected function setPreventClickjacking( bool $enable ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( ImageHistoryPseudoPager::class, 'ImageHistoryPseudoPager' );
