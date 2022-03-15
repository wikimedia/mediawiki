<?php
/**
 * Page history pager
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
 * @ingroup Actions
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * @ingroup Pager
 * @ingroup Actions
 */
class HistoryPager extends ReverseChronologicalPager {

	public $mGroupByDate = true;

	/**
	 * @var bool|stdClass
	 */
	public $lastRow = false;
	/**
	 * @var bool|stdClass|string May be set to 'unknown' when processing the last row
	 */
	public $currRow = false;
	public $lastResultOffset = false;

	public $counter, $historyPage, $buttons, $conds;

	protected $oldIdChecked;

	protected $preventClickjacking = false;
	/**
	 * @var array
	 */
	protected $parentLens;

	/** @var bool Whether to show the tag editing UI */
	protected $showTagEditUI;

	/** @var string */
	private $tagFilter;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var WatchlistManager */
	private $watchlistManager;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var CommentFormatter */
	private $commentFormatter;

	/**
	 * @var RevisionRecord[] Revisions, with the key being their result offset
	 */
	private $revisions = [];

	/**
	 * @var string[] Formatted comments, with the key being their result offset as for $revisions
	 */
	private $formattedComments = [];

	/**
	 * @param HistoryAction $historyPage
	 * @param int $year
	 * @param int $month
	 * @param string $tagFilter
	 * @param array $conds
	 * @param int $day
	 * @param LinkBatchFactory|null $linkBatchFactory
	 * @param WatchlistManager|null $watchlistManager
	 * @param CommentFormatter|null $commentFormatter
	 */
	public function __construct(
		HistoryAction $historyPage,
		$year = 0,
		$month = 0,
		$tagFilter = '',
		array $conds = [],
		$day = 0,
		LinkBatchFactory $linkBatchFactory = null,
		WatchlistManager $watchlistManager = null,
		CommentFormatter $commentFormatter = null
	) {
		parent::__construct( $historyPage->getContext() );
		$this->historyPage = $historyPage;
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month, $day );
		$this->conds = $conds;
		$this->showTagEditUI = ChangeTags::showTagEditingUI( $this->getAuthority() );
		$services = MediaWikiServices::getInstance();
		$this->revisionStore = $services->getRevisionStore();
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->watchlistManager = $watchlistManager
			?? $services->getWatchlistManager();
		$this->commentFormatter = $commentFormatter ?? $services->getCommentFormatter();
	}

	// For hook compatibility...
	public function getArticle() {
		return $this->historyPage->getArticle();
	}

	protected function getSqlComment() {
		if ( $this->conds ) {
			return 'history page filtered'; // potentially slow, see CR r58153
		} else {
			return 'history page unfiltered';
		}
	}

	public function getQueryInfo() {
		$revQuery = $this->revisionStore->getQueryInfo( [ 'user' ] );
		// T270033 Index renaming
		$revIndex = $this->mDb->indexExists( 'revision', 'page_timestamp',  __METHOD__ )
			? 'page_timestamp'
			: 'rev_page_timestamp';

		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => $revQuery['fields'],
			'conds' => array_merge(
				[ 'rev_page' => $this->getWikiPage()->getId() ],
				$this->conds ),
			'options' => [ 'USE INDEX' => [ 'revision' => $revIndex ] ],
			'join_conds' => $revQuery['joins'],
		];
		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		$this->getHookRunner()->onPageHistoryPager__getQueryInfo( $this, $queryInfo );

		return $queryInfo;
	}

	public function getIndexField() {
		return [ [ 'rev_timestamp', 'rev_id' ] ];
	}

	/**
	 * Changes getRow behaviour to only render the previous row.
	 *
	 * @inheritDoc
	 */
	protected function getRow( $row ): string {
		$this->currRow = $row;
		if ( $this->lastRow ) {
			$this->counter++;
			$s = parent::getRow( $this->lastRow );
		} else {
			$s = '';
		}
		$this->lastRow = $row;
		$this->lastResultOffset = $this->getResultOffset();
		return $s;
	}

	/**
	 * @param stdClass $row Unused because we're actually processing the previous row
	 * @return string
	 */
	public function formatRow( $row ) {
		$firstInList = $this->counter == 1;

		$notifTimestamp = $this->getConfig()->get( 'ShowUpdatedMarker' )
			? $this->watchlistManager
				->getTitleNotificationTimestamp( $this->getUser(), $this->getTitle() )
			: false;

		return $this->historyLine( $this->lastRow, $this->currRow, $notifTimestamp,
			$firstInList, $this->lastResultOffset );
	}

	protected function doBatchLookups() {
		if ( !$this->getHookRunner()->onPageHistoryPager__doBatchLookups( $this, $this->mResult ) ) {
			return;
		}

		# Do a link batch query
		$batch = $this->linkBatchFactory->newLinkBatch();
		$revIds = [];
		$title = $this->getTitle();
		foreach ( $this->mResult as $row ) {
			if ( $row->rev_parent_id ) {
				$revIds[] = (int)$row->rev_parent_id;
			}
			if ( $row->user_name !== null ) {
				$batch->add( NS_USER, $row->user_name );
				$batch->add( NS_USER_TALK, $row->user_name );
			} else { # for anons or usernames of imported revisions
				$batch->add( NS_USER, $row->rev_user_text );
				$batch->add( NS_USER_TALK, $row->rev_user_text );
			}
			$this->revisions[] = $this->revisionStore->newRevisionFromRow(
				$row,
				RevisionStore::READ_NORMAL,
				$title
			);
		}
		$this->parentLens = $this->revisionStore->getRevisionSizes( $revIds );
		$batch->execute();

		# The keys of $this->formattedComments will be the same as the keys of $this->revisions
		$this->formattedComments = $this->commentFormatter->createRevisionBatch()
			->revisions( $this->revisions )
			->authority( $this->getAuthority() )
			->samePage( false )
			->hideIfDeleted( true )
			->useParentheses( false )
			->execute();

		$this->mResult->seek( 0 );
	}

	/**
	 * Returns message when query returns no revisions
	 * @return string escaped message
	 */
	protected function getEmptyBody() {
		return $this->msg( 'history-empty' )->escaped();
	}

	/**
	 * Creates begin of history list with a submit button
	 *
	 * @return string HTML output
	 */
	protected function getStartBody() {
		$this->lastRow = false;
		$this->counter = 0;
		$this->oldIdChecked = 0;
		$s = '';
		// Button container stored in $this->buttons for re-use in getEndBody()
		$this->buttons = '';
		if ( $this->getNumRows() > 0 ) {
			$this->getOutput()->wrapWikiMsg( "<div class='mw-history-legend'>\n$1\n</div>", 'histlegend' );
			$s = Html::openElement( 'form', [
				'action' => wfScript(),
				'id' => 'mw-history-compare'
			] ) . "\n";
			$s .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n";
			$s .= Html::hidden( 'action', 'historysubmit' ) . "\n";
			$s .= Html::hidden( 'type', 'revision' ) . "\n";

			$this->buttons .= Html::openElement(
				'div', [ 'class' => 'mw-history-compareselectedversions' ] );
			$className = 'historysubmit mw-history-compareselectedversions-button mw-ui-button';
			$attrs = [ 'class' => $className ]
				+ Linker::tooltipAndAccesskeyAttribs( 'compareselectedversions' );
			$this->buttons .= $this->submitButton( $this->msg( 'compareselectedversions' )->text(),
				$attrs
			) . "\n";

			$actionButtons = '';
			if ( $this->getAuthority()->isAllowed( 'deleterevision' ) ) {
				$actionButtons .= $this->getRevisionButton(
					'revisiondelete', 'showhideselectedversions' );
			}
			if ( $this->showTagEditUI ) {
				$actionButtons .= $this->getRevisionButton(
					'editchangetags', 'history-edit-tags' );
			}
			if ( $actionButtons ) {
				$this->buttons .= Xml::tags( 'div', [ 'class' =>
					'mw-history-revisionactions' ], $actionButtons );
			}

			if ( $this->getAuthority()->isAllowed( 'deleterevision' ) || $this->showTagEditUI ) {
				$this->buttons .= ( new ListToggle( $this->getOutput() ) )->getHTML();
			}

			$this->buttons .= '</div>';

			$s .= $this->buttons;
		}

		$s .= '<section id="pagehistory" class="mw-pager-body">';

		return $s;
	}

	private function getRevisionButton( $name, $msg ) {
		$this->setPreventClickjacking( true );
		$element = Html::element(
			'button',
			[
				'type' => 'submit',
				'name' => $name,
				'value' => '1',
				'class' => "historysubmit mw-history-$name-button mw-ui-button",
			],
			$this->msg( $msg )->text()
		) . "\n";
		return $element;
	}

	protected function getEndBody() {
		if ( $this->getNumRows() == 0 ) {
			return '';
		}

		# Special handling for the last row.
		if ( $this->lastRow ) {
			$this->counter++;

			if ( $this->mIsBackwards ) {
				# Next row is unknown, but for UI reasons, probably exists if an offset has been specified
				if ( $this->mOffset == '' ) {
					$row = null;
				} else {
					$row = 'unknown';
				}
			} else {
				# The next row is the past-the-end row
				$row = $this->mPastTheEndRow;
			}

			$s = $this->getStartGroup();
			$s .= $this->getRow( $row );
			$s .= $this->getEndGroup();
		} else {
			$s = '';
		}
		# Add second buttons only if there is more than one rev
		if ( $this->getNumRows() > 2 ) {
			$s .= $this->buttons;
		}
		$s .= '</section>'; // closes section#pagehistory
		$s .= '</form>';
		return $s;
	}

	/**
	 * Creates a submit button
	 *
	 * @param string $message Text of the submit button, will be escaped
	 * @param array $attributes
	 * @return string HTML output for the submit button
	 */
	private function submitButton( $message, $attributes = [] ) {
		# Disable submit button if history has 1 revision only
		if ( $this->getNumRows() > 1 ) {
			return Html::submitButton( $message, $attributes );
		} else {
			return '';
		}
	}

	/**
	 * Returns a row from the history printout.
	 *
	 * @todo document some more, and maybe clean up the code (some params redundant?)
	 *
	 * @param stdClass $row The database row corresponding to the previous line.
	 * @param mixed $next The database row corresponding to the next line
	 *   (chronologically previous)
	 * @param bool|string $notificationtimestamp
	 * @param bool $firstInList Whether this row corresponds to the first
	 *   displayed on this history page.
	 * @param int $resultOffset The offset into the current result set
	 * @return string HTML output for the row
	 */
	private function historyLine( $row, $next, $notificationtimestamp,
		$firstInList, $resultOffset
	) {
		$revRecord = $this->revisions[$resultOffset];

		if ( is_object( $next ) ) {
			$previousRevRecord = $this->revisionStore->newRevisionFromRow(
				$next,
				RevisionStore::READ_NORMAL,
				$this->getTitle()
			);
		} else {
			$previousRevRecord = null;
		}

		$latest = $revRecord->getId() === $this->getWikiPage()->getLatest();
		$curlink = $this->curLink( $revRecord );
		$lastlink = $this->lastLink( $revRecord, $next );
		$curLastlinks = Html::rawElement( 'span', [], $curlink ) .
			Html::rawElement( 'span', [], $lastlink );
		$histLinks = Html::rawElement(
			'span',
			[ 'class' => 'mw-history-histlinks mw-changeslist-links' ],
			$curLastlinks
		);

		$diffButtons = $this->diffButtons( $revRecord, $firstInList );
		$s = $histLinks . $diffButtons;

		$link = $this->revLink( $revRecord );
		$classes = [];

		$del = '';
		$user = $this->getUser();
		$canRevDelete = $this->getAuthority()->isAllowed( 'deleterevision' );
		// Show checkboxes for each revision, to allow for revision deletion and
		// change tags
		$visibility = $revRecord->getVisibility();
		if ( $canRevDelete || $this->showTagEditUI ) {
			$this->setPreventClickjacking( true );
			// If revision was hidden from sysops and we don't need the checkbox
			// for anything else, disable it
			if ( !$this->showTagEditUI
				&& !$revRecord->userCan( RevisionRecord::DELETED_RESTRICTED, $this->getAuthority() )
			) {
				$del = Xml::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
			// Otherwise, enable the checkbox...
			} else {
				$del = Xml::check( 'showhiderevisions', false,
					[ 'name' => 'ids[' . $revRecord->getId() . ']' ] );
			}
		// User can only view deleted revisions...
		} elseif ( $revRecord->getVisibility() && $this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			// If revision was hidden from sysops, disable the link
			if ( !$revRecord->userCan( RevisionRecord::DELETED_RESTRICTED, $this->getAuthority() ) ) {
				$del = Linker::revDeleteLinkDisabled( false );
			// Otherwise, show the link...
			} else {
				$query = [
					'type' => 'revision',
					'target' => $this->getTitle()->getPrefixedDBkey(),
					'ids' => $revRecord->getId()
				];
				$del .= Linker::revDeleteLink(
					$query,
					$revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ),
					false
				);
			}
		}
		if ( $del ) {
			$s .= " $del ";
		}

		$lang = $this->getLanguage();
		$dirmark = $lang->getDirMark();

		$s .= " $link";
		$s .= $dirmark;
		$s .= " <span class='history-user'>" .
			Linker::revUserTools( $revRecord, true, false ) . "</span>";
		$s .= $dirmark;

		if ( $revRecord->isMinor() ) {
			$s .= ' ' . ChangesList::flag( 'minor', $this->getContext() );
		}

		# Sometimes rev_len isn't populated
		if ( $revRecord->getSize() !== null ) {
			# Size is always public data
			$prevSize = $this->parentLens[$row->rev_parent_id] ?? 0;
			$sDiff = ChangesList::showCharacterDifference( $prevSize, $revRecord->getSize() );
			$fSize = Linker::formatRevisionSize( $revRecord->getSize() );
			$s .= ' <span class="mw-changeslist-separator"></span> ' . "$fSize $sDiff";
		}

		# Text following the character difference is added just before running hooks
		$s2 = $this->formattedComments[$resultOffset];

		if ( $s2 === '' ) {
			$defaultComment = $this->msg( 'changeslist-nocomment' )->escaped();
			$s2 = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
		}

		if ( $notificationtimestamp && ( $row->rev_timestamp >= $notificationtimestamp ) ) {
			$s2 .= ' <span class="updatedmarker">' . $this->msg( 'updatedmarker' )->escaped() . '</span>';
			$classes[] = 'mw-history-line-updated';
		}

		$tools = [];

		# Rollback and undo links

		if ( $previousRevRecord && $this->getAuthority()->probablyCan( 'edit', $this->getTitle() ) ) {
			if ( $latest && $this->getAuthority()->probablyCan( 'rollback', $this->getTitle() )
			) {
				// Get a rollback link without the brackets
				$rollbackLink = Linker::generateRollback(
					$revRecord,
					$this->getContext(),
					[ 'verify', 'noBrackets' ]
				);
				if ( $rollbackLink ) {
					$this->setPreventClickjacking( true );
					$tools[] = $rollbackLink;
				}
			}

			if ( !$revRecord->isDeleted( RevisionRecord::DELETED_TEXT )
				&& !$previousRevRecord->isDeleted( RevisionRecord::DELETED_TEXT )
			) {
				# Create undo tooltip for the first (=latest) line only
				$undoTooltip = $latest
					? [ 'title' => $this->msg( 'tooltip-undo' )->text() ]
					: [];
				$undolink = $this->getLinkRenderer()->makeKnownLink(
					$this->getTitle(),
					$this->msg( 'editundo' )->text(),
					$undoTooltip,
					[
						'action' => 'edit',
						'undoafter' => $previousRevRecord->getId(),
						'undo' => $revRecord->getId()
					]
				);
				$tools[] = "<span class=\"mw-history-undo\">{$undolink}</span>";
			}
		}
		// Allow extension to add their own links here
		$this->getHookRunner()->onHistoryTools(
			$revRecord,
			$tools,
			$previousRevRecord,
			$user
		);

		if ( $tools ) {
			$s2 .= ' ' . Html::openElement( 'span', [ 'class' => 'mw-changeslist-links' ] );
			foreach ( $tools as $tool ) {
				$s2 .= Html::rawElement( 'span', [], $tool );
			}
			$s2 .= Html::closeElement( 'span' );
		}

		# Tags
		list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'history',
			$this->getContext()
		);
		$classes = array_merge( $classes, $newClasses );
		if ( $tagSummary !== '' ) {
			$s2 .= " $tagSummary";
		}

		# Include separator between character difference and following text
		$s .= ' <span class="mw-changeslist-separator"></span> ' . $s2;

		$attribs = [ 'data-mw-revid' => $revRecord->getId() ];

		$this->getHookRunner()->onPageHistoryLineEnding( $this, $row, $s, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		return Xml::tags( 'li', $attribs, $s ) . "\n";
	}

	/**
	 * Create a link to view this revision of the page
	 *
	 * @param RevisionRecord $rev
	 * @return string
	 */
	private function revLink( RevisionRecord $rev ) {
		return ChangesList::revDateLink( $rev, $this->getUser(), $this->getLanguage(),
			$this->getTitle() );
	}

	/**
	 * Create a diff-to-current link for this revision for this page
	 *
	 * @param RevisionRecord $rev
	 * @return string
	 */
	private function curLink( RevisionRecord $rev ) {
		$cur = $this->historyPage->message['cur'];
		$latest = $this->getWikiPage()->getLatest();
		if ( $latest === $rev->getId()
			|| !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
		) {
			return $cur;
		} else {
			return $this->getLinkRenderer()->makeKnownLink(
				$this->getTitle(),
				new HtmlArmor( $cur ),
				[
					'title' => $this->historyPage->message['tooltip-cur']
				],
				[
					'diff' => $latest,
					'oldid' => $rev->getId()
				]
			);
		}
	}

	/**
	 * Create a diff-to-previous link for this revision for this page.
	 *
	 * @param RevisionRecord $prevRev The revision being displayed
	 * @param stdClass|string|null $next The next revision in list (that is
	 *        the previous one in chronological order).
	 *        May either be a row, "unknown" or null.
	 * @return string
	 */
	private function lastLink( RevisionRecord $prevRev, $next ) {
		$last = $this->historyPage->message['last'];

		if ( $next === null ) {
			# Probably no next row
			return $last;
		}

		$linkRenderer = $this->getLinkRenderer();
		if ( $next === 'unknown' ) {
			# Next row probably exists but is unknown, use an oldid=prev link
			return $linkRenderer->makeKnownLink(
				$this->getTitle(),
				new HtmlArmor( $last ),
				[
					'title' => $this->historyPage->message['tooltip-last']
				],
				[
					'diff' => $prevRev->getId(),
					'oldid' => 'prev'
				]
			);
		}

		$nextRev = $this->revisionStore->newRevisionFromRow(
			$next,
			RevisionStore::READ_NORMAL,
			$this->getTitle()
		);

		if ( !$prevRev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ||
			!$nextRev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
		) {
			return $last;
		}

		return $linkRenderer->makeKnownLink(
			$this->getTitle(),
			new HtmlArmor( $last ),
			[
				'title' => $this->historyPage->message['tooltip-last']
			],
			[
				'diff' => $prevRev->getId(),
				'oldid' => $next->rev_id
			]
		);
	}

	/**
	 * Create radio buttons for page history
	 *
	 * @param RevisionRecord $rev
	 * @param bool $firstInList Is this version the first one?
	 *
	 * @return string HTML output for the radio buttons
	 */
	private function diffButtons( RevisionRecord $rev, $firstInList ) {
		if ( $this->getNumRows() > 1 ) {
			$id = $rev->getId();
			$radio = [ 'type' => 'radio', 'value' => $id ];
			/** @todo Move title texts to javascript */
			if ( $firstInList ) {
				$first = Xml::element( 'input',
					array_merge( $radio, [
						// Disable the hidden radio because it can still
						// be selected with arrow keys on Firefox
						'disabled' => '',
						'name' => 'oldid',
						'id' => 'mw-oldid-null' ] )
				);
				$checkmark = [ 'checked' => 'checked' ];
			} else {
				# Check visibility of old revisions
				if ( !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
					$radio['disabled'] = 'disabled';
					$checkmark = []; // We will check the next possible one
				} elseif ( !$this->oldIdChecked ) {
					$checkmark = [ 'checked' => 'checked' ];
					$this->oldIdChecked = $id;
				} else {
					$checkmark = [];
				}
				$first = Xml::element( 'input',
					array_merge( $radio, $checkmark, [
						'name' => 'oldid',
						'id' => "mw-oldid-$id" ] ) );
				$checkmark = [];
			}
			$second = Xml::element( 'input',
				array_merge( $radio, $checkmark, [
					'name' => 'diff',
					'id' => "mw-diff-$id" ] ) );

			return $first . $second;
		} else {
			return '';
		}
	}

	/**
	 * Returns whether to show the "navigation bar"
	 *
	 * @return bool
	 */
	protected function isNavigationBarShown() {
		if ( $this->getNumRows() == 0 ) {
			return false;
		}
		return parent::isNavigationBarShown();
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultQuery() {
		parent::getDefaultQuery();
		unset( $this->mDefaultQuery['date-range-to'] );
		return $this->mDefaultQuery;
	}

	/**
	 * Set the "prevent clickjacking" flag; for example if a write operation
	 * if possible from the generated HTML.
	 * @param bool $flag
	 */
	private function setPreventClickjacking( bool $flag ) {
		$this->preventClickjacking = $flag;
	}

	/**
	 * Get the "prevent clickjacking" flag
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}
