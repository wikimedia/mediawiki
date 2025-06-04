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

namespace MediaWiki\Pager;

use MediaWiki\Actions\HistoryAction;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Html\ListToggle;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistManager;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @ingroup Pager
 * @ingroup Actions
 */
#[\AllowDynamicProperties]
class HistoryPager extends ReverseChronologicalPager {

	/** @inheritDoc */
	public $mGroupByDate = true;

	public HistoryAction $historyPage;
	public string $buttons;
	public array $conds;

	/** @var int */
	protected $oldIdChecked;

	/** @var bool */
	protected $preventClickjacking = false;
	/**
	 * @var array
	 */
	protected $parentLens;

	/** @var bool Whether to show the tag editing UI */
	protected $showTagEditUI;

	protected MapCacheLRU $tagsCache;

	/** @var string */
	private $tagFilter;

	/** @var bool */
	private $tagInvert;

	/** @var string|null|false */
	private $notificationTimestamp;

	private RevisionStore $revisionStore;
	private WatchlistManager $watchlistManager;
	private LinkBatchFactory $linkBatchFactory;
	private CommentFormatter $commentFormatter;
	private HookRunner $hookRunner;
	private ChangeTagsStore $changeTagsStore;

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
	 * @param int $day
	 * @param string $tagFilter
	 * @param bool $tagInvert
	 * @param array $conds
	 * @param LinkBatchFactory|null $linkBatchFactory
	 * @param WatchlistManager|null $watchlistManager
	 * @param CommentFormatter|null $commentFormatter
	 * @param HookContainer|null $hookContainer
	 * @param ChangeTagsStore|null $changeTagsStore
	 */
	public function __construct(
		HistoryAction $historyPage,
		$year = 0,
		$month = 0,
		$day = 0,
		$tagFilter = '',
		$tagInvert = false,
		array $conds = [],
		?LinkBatchFactory $linkBatchFactory = null,
		?WatchlistManager $watchlistManager = null,
		?CommentFormatter $commentFormatter = null,
		?HookContainer $hookContainer = null,
		?ChangeTagsStore $changeTagsStore = null
	) {
		parent::__construct( $historyPage->getContext() );
		$this->historyPage = $historyPage;
		$this->tagFilter = $tagFilter;
		$this->tagInvert = $tagInvert;
		$this->getDateCond( $year, $month, $day );
		$this->conds = $conds;
		$this->showTagEditUI = ChangeTags::showTagEditingUI( $this->getAuthority() );
		$this->tagsCache = new MapCacheLRU( 50 );
		$services = MediaWikiServices::getInstance();
		$this->revisionStore = $services->getRevisionStore();
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->watchlistManager = $watchlistManager
			?? $services->getWatchlistManager();
		$this->commentFormatter = $commentFormatter ?? $services->getCommentFormatter();
		$this->hookRunner = new HookRunner( $hookContainer ?? $services->getHookContainer() );
		$this->notificationTimestamp = $this->getConfig()->get( MainConfigNames::ShowUpdatedMarker )
			? $this->watchlistManager->getTitleNotificationTimestamp( $this->getUser(), $this->getTitle() )
			: false;
		$this->changeTagsStore = $changeTagsStore ?? $services->getChangeTagsStore();
	}

	/**
	 * For hook compatibility…
	 * @return Article
	 */
	public function getArticle() {
		return $this->historyPage->getArticle();
	}

	/** @inheritDoc */
	protected function getSqlComment() {
		if ( $this->conds ) {
			return 'history page filtered'; // potentially slow, see CR r58153
		} else {
			return 'history page unfiltered';
		}
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $this->mDb )
			->joinComment()
			->joinUser()->field( 'user_id' )
			->useIndex( [ 'revision' => 'rev_page_timestamp' ] )
			->where( [ 'rev_page' => $this->getWikiPage()->getId() ] )
			->andWhere( $this->conds );

		$queryInfo = $queryBuilder->getQueryInfo( 'join_conds' );
		$this->changeTagsStore->modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter,
			$this->tagInvert
		);

		$this->hookRunner->onPageHistoryPager__getQueryInfo( $this, $queryInfo );

		return $queryInfo;
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'rev_timestamp', 'rev_id' ] ];
	}

	protected function doBatchLookups() {
		if ( !$this->hookRunner->onPageHistoryPager__doBatchLookups( $this, $this->mResult ) ) {
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
				$batch->addUser( new UserIdentityValue( (int)$row->user_id, $row->user_name ) );
			} else { # for anons or usernames of imported revisions
				$batch->add( NS_USER, $row->rev_user_text );
				$batch->add( NS_USER_TALK, $row->rev_user_text );
			}
			$this->revisions[] = $this->revisionStore->newRevisionFromRow(
				$row,
				IDBAccessObject::READ_NORMAL,
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
		$this->oldIdChecked = 0;
		$s = '';
		// Button container stored in $this->buttons for re-use in getEndBody()
		$this->buttons = '';
		if ( $this->getNumRows() > 0 ) {
			$this->getOutput()->wrapWikiMsg( "<div class='mw-history-legend'>\n$1\n</div>", 'histlegend' );
			// Main form for comparing revisions
			$s = Html::openElement( 'form', [
				'action' => wfScript(),
				'id' => 'mw-history-compare'
			] ) . "\n";
			$s .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n";

			$this->buttons .= Html::openElement(
				'div', [ 'class' => 'mw-history-compareselectedversions' ] );
			$className = 'historysubmit mw-history-compareselectedversions-button cdx-button';
			$attrs = [ 'class' => $className ]
				+ Linker::tooltipAndAccesskeyAttribs( 'compareselectedversions' );
			$this->buttons .= $this->submitButton( $this->msg( 'compareselectedversions' )->text(),
				$attrs
			) . "\n";

			$actionButtons = '';
			if ( $this->getAuthority()->isAllowed( 'deleterevision' ) ) {
				$actionButtons .= $this->getRevisionButton(
					'Revisiondelete', 'showhideselectedversions', 'mw-history-revisiondelete-button' );
			}
			if ( $this->showTagEditUI ) {
				$actionButtons .= $this->getRevisionButton(
					'EditTags', 'history-edit-tags', 'mw-history-editchangetags-button' );
			}
			if ( $actionButtons ) {
				// Prepend a mini-form for changing visibility and editing tags.
				// Checkboxes and buttons are associated with it using the <input form="…"> attribute.
				//
				// This makes the submitted parameters cleaner (on supporting browsers - all except IE 11):
				// the 'mw-history-compare' form submission will omit the `ids[…]` parameters, and the
				// 'mw-history-revisionactions' form submission will omit the `diff` and `oldid` parameters.
				$s = Html::rawElement( 'form', [
					'action' => wfScript(),
					'id' => 'mw-history-revisionactions',
				] ) . "\n" . $s;
				$s .= Html::hidden( 'type', 'revision', [ 'form' => 'mw-history-revisionactions' ] ) . "\n";

				$this->buttons .= Html::rawElement( 'div', [ 'class' =>
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

	private function getRevisionButton( string $name, string $msg, string $class ): string {
		$this->preventClickjacking = true;
		$element = Html::element(
			'button',
			[
				'type' => 'submit',
				'name' => 'title',
				'value' => SpecialPage::getTitleFor( $name )->getPrefixedDBkey(),
				'class' => [ 'cdx-button', $class, 'historysubmit' ],
				'form' => 'mw-history-revisionactions',
			],
			$this->msg( $msg )->text()
		) . "\n";
		return $element;
	}

	/** @inheritDoc */
	protected function getEndBody() {
		if ( $this->getNumRows() == 0 ) {
			return '';
		}
		$s = '';
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
	 * @param stdClass $row The database row corresponding to the current line.
	 * @return string HTML output for the row
	 */
	public function formatRow( $row ) {
		$resultOffset = $this->getResultOffset();
		$numRows = min( $this->mResult->numRows(), $this->mLimit );

		$firstInList = $resultOffset === ( $this->mIsBackwards ? $numRows - 1 : 0 );
		// Next in the list, previous in chronological order.
		$nextResultOffset = $resultOffset + ( $this->mIsBackwards ? -1 : 1 );

		$revRecord = $this->revisions[$resultOffset];
		// This may only be null if the current line is the last one in the list.
		$previousRevRecord = $this->revisions[$nextResultOffset] ?? null;

		$latest = $revRecord->getId() === $this->getWikiPage()->getLatest();
		$curlink = $this->curLink( $revRecord );
		if ( $previousRevRecord ) {
			// Display a link to compare to the previous revision
			$lastlink = $this->lastLink( $revRecord, $previousRevRecord );
		} elseif ( $this->mIsBackwards && $this->mOffset !== '' ) {
			// When paging "backwards", we don't have the extra result for the next revision that would
			// appear in the list, and we don't know whether this is the oldest revision or not.
			// However, if an offset has been specified, then the user probably reached this page by
			// navigating from the "next" page, therefore the next revision probably exists.
			// Display a link using &oldid=prev (this skips some checks but that's fine).
			$lastlink = $this->lastLink( $revRecord, null );
		} else {
			// Do not display a link, because this is the oldest revision of the page
			$lastlink = Html::element( 'span', [
				'class' => 'mw-history-histlinks-previous',
			], $this->historyPage->message['last'] );
		}
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
		$canRevDelete = $this->getAuthority()->isAllowed( 'deleterevision' );
		// Show checkboxes for each revision, to allow for revision deletion and
		// change tags
		if ( $canRevDelete || $this->showTagEditUI ) {
			$this->preventClickjacking = true;
			// If revision was hidden from sysops and we don't need the checkbox
			// for anything else, disable it
			if ( !$this->showTagEditUI
				&& !$revRecord->userCan( RevisionRecord::DELETED_RESTRICTED, $this->getAuthority() )
			) {
				$del = Html::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
			// Otherwise, enable the checkbox…
			} else {
				$del = Html::check(
					'ids[' . $revRecord->getId() . ']', false,
					[ 'form' => 'mw-history-revisionactions' ]
				);
			}
		// User can only view deleted revisions…
		} elseif ( $revRecord->getVisibility() && $this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			// If revision was hidden from sysops, disable the link
			if ( !$revRecord->userCan( RevisionRecord::DELETED_RESTRICTED, $this->getAuthority() ) ) {
				$del = Linker::revDeleteLinkDisabled( false );
			// Otherwise, show the link…
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
		$s .= ' ' . Html::rawElement( 'bdi', [ 'dir' => $lang->getDir() ], $link );
		$s .= " <span class='history-user'>" .
			Linker::revUserTools( $revRecord, true, false ) . "</span>";

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

		# Include separator between character difference and following text
		$s .= ' <span class="mw-changeslist-separator"></span> ';

		# Text following the character difference is added just before running hooks
		$comment = $this->formattedComments[$resultOffset];

		if ( $comment === '' ) {
			$defaultComment = $this->historyPage->message['changeslist-nocomment'];
			$comment = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
		}
		$s .= $comment;

		if ( $this->notificationTimestamp && $row->rev_timestamp >= $this->notificationTimestamp ) {
			$s .= ' <span class="updatedmarker">' . $this->historyPage->message['updatedmarker'] . '</span>';
			$classes[] = 'mw-history-line-updated';
		}

		$pagerTools = new PagerTools(
			$revRecord,
			$previousRevRecord,
			$latest && $previousRevRecord,
			$this->hookRunner,
			$this->getTitle(),
			$this->getContext(),
			$this->getLinkRenderer()
		);
		if ( $pagerTools->shouldPreventClickjacking() ) {
			$this->preventClickjacking = true;
		}
		$s .= $pagerTools->toHTML();

		# Tags
		[ $tagSummary, $newClasses ] = $this->tagsCache->getWithSetCallback(
			$this->tagsCache->makeKey(
				$row->ts_tags ?? '',
				$this->getUser()->getName(),
				$lang->getCode()
			),
			fn () => ChangeTags::formatSummaryRow(
				$row->ts_tags,
				'history',
				$this->getContext()
			)
		);
		$classes = array_merge( $classes, $newClasses );
		if ( $tagSummary !== '' ) {
			$s .= " $tagSummary";
		}

		$attribs = [ 'data-mw-revid' => $revRecord->getId() ];

		$this->hookRunner->onPageHistoryLineEnding( $this, $row, $s, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);
		$attribs['class'] = $classes;

		return Html::rawElement( 'li', $attribs, $s ) . "\n";
	}

	/**
	 * Create a link to view this revision of the page
	 *
	 * @param RevisionRecord $rev
	 * @return string
	 */
	private function revLink( RevisionRecord $rev ) {
		return ChangesList::revDateLink( $rev, $this->getAuthority(), $this->getLanguage(),
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
			return Html::element( 'span', [
				'class' => 'mw-history-histlinks-current',
			], $cur );
		} else {
			return $this->getLinkRenderer()->makeKnownLink(
				$this->getTitle(),
				new HtmlArmor( $cur ),
				[
					'class' => 'mw-history-histlinks-current',
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
	 * @param RevisionRecord|null $nextRev The next revision in list (that is the previous one in
	 *        chronological order) or null if it is unknown, but a link should be created anyway.
	 * @return string
	 */
	private function lastLink( RevisionRecord $prevRev, ?RevisionRecord $nextRev ) {
		$last = $this->historyPage->message['last'];

		if ( !$prevRev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ||
			( $nextRev && !$nextRev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) )
		) {
			return Html::element( 'span', [
				'class' => 'mw-history-histlinks-previous',
			], $last );
		}

		return $this->getLinkRenderer()->makeKnownLink(
			$this->getTitle(),
			new HtmlArmor( $last ),
			[
				'class' => 'mw-history-histlinks-previous',
				'title' => $this->historyPage->message['tooltip-last']
			],
			[
				'diff' => 'prev', // T243569
				'oldid' => $prevRev->getId()
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
				$first = Html::element( 'input',
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
				$first = Html::element( 'input',
					array_merge( $radio, $checkmark, [
						'name' => 'oldid',
						'id' => "mw-oldid-$id" ] ) );
				$checkmark = [];
			}
			$second = Html::element( 'input',
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
	 * Get the "prevent clickjacking" flag
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}

/** @deprecated class alias since 1.41 */
class_alias( HistoryPager::class, 'HistoryPager' );
