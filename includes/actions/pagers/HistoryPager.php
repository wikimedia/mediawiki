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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;

/**
 * @ingroup Pager
 * @ingroup Actions
 */
class HistoryPager extends ReverseChronologicalPager {
	/**
	 * @var bool|stdClass
	 */
	public $lastRow = false;

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

	/**
	 * @param HistoryAction $historyPage
	 * @param string $year
	 * @param string $month
	 * @param string $tagFilter
	 * @param array $conds
	 * @param string $day
	 */
	public function __construct(
		HistoryAction $historyPage,
		$year = '',
		$month = '',
		$tagFilter = '',
		array $conds = [],
		$day = ''
	) {
		parent::__construct( $historyPage->getContext() );
		$this->historyPage = $historyPage;
		$this->tagFilter = $tagFilter;
		$this->getDateCond( $year, $month, $day );
		$this->conds = $conds;
		$this->showTagEditUI = ChangeTags::showTagEditingUI( $this->getUser() );
	}

	// For hook compatibility...
	function getArticle() {
		return $this->historyPage->getArticle();
	}

	function getSqlComment() {
		if ( $this->conds ) {
			return 'history page filtered'; // potentially slow, see CR r58153
		} else {
			return 'history page unfiltered';
		}
	}

	function getQueryInfo() {
		$revQuery = Revision::getQueryInfo( [ 'user' ] );
		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => $revQuery['fields'],
			'conds' => array_merge(
				[ 'rev_page' => $this->getWikiPage()->getId() ],
				$this->conds ),
			'options' => [ 'USE INDEX' => [ 'revision' => 'page_timestamp' ] ],
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

		// Avoid PHP 7.1 warning of passing $this by reference
		$historyPager = $this;
		Hooks::run( 'PageHistoryPager::getQueryInfo', [ &$historyPager, &$queryInfo ] );

		return $queryInfo;
	}

	function getIndexField() {
		return 'rev_timestamp';
	}

	/**
	 * @param stdClass $row
	 * @return string
	 */
	function formatRow( $row ) {
		if ( $this->lastRow ) {
			$firstInList = $this->counter == 1;
			$this->counter++;

			$notifTimestamp = $this->getConfig()->get( 'ShowUpdatedMarker' )
				? $this->getTitle()->getNotificationTimestamp( $this->getUser() )
				: false;

			$s = $this->historyLine( $this->lastRow, $row, $notifTimestamp, false, $firstInList );
		} else {
			$s = '';
		}
		$this->lastRow = $row;

		return $s;
	}

	protected function doBatchLookups() {
		if ( !Hooks::run( 'PageHistoryPager::doBatchLookups', [ $this, $this->mResult ] ) ) {
			return;
		}

		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		$revIds = [];
		foreach ( $this->mResult as $row ) {
			if ( $row->rev_parent_id ) {
				$revIds[] = $row->rev_parent_id;
			}
			if ( $row->user_name !== null ) {
				$batch->add( NS_USER, $row->user_name );
				$batch->add( NS_USER_TALK, $row->user_name );
			} else { # for anons or usernames of imported revisions
				$batch->add( NS_USER, $row->rev_user_text );
				$batch->add( NS_USER_TALK, $row->rev_user_text );
			}
		}
		$this->parentLens = Revision::getParentLengths( $this->mDb, $revIds );
		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * Creates begin of history list with a submit button
	 *
	 * @return string HTML output
	 */
	protected function getStartBody() {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$this->lastRow = false;
		$this->counter = 1;
		$this->oldIdChecked = 0;

		$this->getOutput()->wrapWikiMsg( "<div class='mw-history-legend'>\n$1\n</div>", 'histlegend' );
		$s = Html::openElement( 'form', [ 'action' => wfScript(),
			'id' => 'mw-history-compare' ] ) . "\n";
		$s .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) . "\n";
		$s .= Html::hidden( 'action', 'historysubmit' ) . "\n";
		$s .= Html::hidden( 'type', 'revision' ) . "\n";

		// Button container stored in $this->buttons for re-use in getEndBody()
		$this->buttons = '';
		if ( $this->getNumRows() > 0 ) {
			$this->buttons .= Html::openElement(
				'div', [ 'class' => 'mw-history-compareselectedversions' ] );
			$className = 'historysubmit mw-history-compareselectedversions-button';
			$attrs = [ 'class' => $className ]
				+ Linker::tooltipAndAccesskeyAttribs( 'compareselectedversions' );
			$this->buttons .= $this->submitButton( $this->msg( 'compareselectedversions' )->text(),
				$attrs
			) . "\n";

			$user = $this->getUser();
			$actionButtons = '';
			if ( $permissionManager->userHasRight( $user, 'deleterevision' ) ) {
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

			if ( $permissionManager->userHasRight( $user, 'deleterevision' ) || $this->showTagEditUI ) {
				$this->buttons .= ( new ListToggle( $this->getOutput() ) )->getHTML();
			}

			$this->buttons .= '</div>';

			$s .= $this->buttons;
		}
		$s .= '<ul id="pagehistory">' . "\n";

		return $s;
	}

	private function getRevisionButton( $name, $msg ) {
		$this->preventClickjacking();
		# Note T22966, <button> is non-standard in IE<8
		$element = Html::element(
			'button',
			[
				'type' => 'submit',
				'name' => $name,
				'value' => '1',
				'class' => "historysubmit mw-history-$name-button",
			],
			$this->msg( $msg )->text()
		) . "\n";
		return $element;
	}

	protected function getEndBody() {
		if ( $this->lastRow ) {
			$firstInList = $this->counter == 1;
			if ( $this->mIsBackwards ) {
				# Next row is unknown, but for UI reasons, probably exists if an offset has been specified
				if ( $this->mOffset == '' ) {
					$next = null;
				} else {
					$next = 'unknown';
				}
			} else {
				# The next row is the past-the-end row
				$next = $this->mPastTheEndRow;
			}
			$this->counter++;

			$notifTimestamp = $this->getConfig()->get( 'ShowUpdatedMarker' )
				? $this->getTitle()->getNotificationTimestamp( $this->getUser() )
				: false;

			$s = $this->historyLine( $this->lastRow, $next, $notifTimestamp, false, $firstInList );
		} else {
			$s = '';
		}
		$s .= "</ul>\n";
		# Add second buttons only if there is more than one rev
		if ( $this->getNumRows() > 2 ) {
			$s .= $this->buttons;
		}
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
	function submitButton( $message, $attributes = [] ) {
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
	 * @param bool $dummy Unused.
	 * @param bool $firstInList Whether this row corresponds to the first
	 *   displayed on this history page.
	 * @return string HTML output for the row
	 */
	function historyLine( $row, $next, $notificationtimestamp = false,
		$dummy = false, $firstInList = false ) {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$rev = new Revision( $row, 0, $this->getTitle() );

		if ( is_object( $next ) ) {
			$prevRev = new Revision( $next, 0, $this->getTitle() );
		} else {
			$prevRev = null;
		}

		$latest = $rev->getId() === $this->getWikiPage()->getLatest();
		$curlink = $this->curLink( $rev );
		$lastlink = $this->lastLink( $rev, $next );
		$curLastlinks = Html::rawElement( 'span', [], $curlink ) .
			Html::rawElement( 'span', [], $lastlink );
		$histLinks = Html::rawElement(
			'span',
			[ 'class' => 'mw-history-histlinks mw-changeslist-links' ],
			$curLastlinks
		);

		$diffButtons = $this->diffButtons( $rev, $firstInList );
		$s = $histLinks . $diffButtons;

		$link = $this->revLink( $rev );
		$classes = [];

		$del = '';
		$user = $this->getUser();
		$canRevDelete = $permissionManager->userHasRight( $user, 'deleterevision' );
		// Show checkboxes for each revision, to allow for revision deletion and
		// change tags
		if ( $canRevDelete || $this->showTagEditUI ) {
			$this->preventClickjacking();
			// If revision was hidden from sysops and we don't need the checkbox
			// for anything else, disable it
			if ( !$this->showTagEditUI
				&& !$rev->userCan( RevisionRecord::DELETED_RESTRICTED, $user )
			) {
				$del = Xml::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
			// Otherwise, enable the checkbox...
			} else {
				$del = Xml::check( 'showhiderevisions', false,
					[ 'name' => 'ids[' . $rev->getId() . ']' ] );
			}
		// User can only view deleted revisions...
		} elseif ( $rev->getVisibility() &&
				   $permissionManager->userHasRight( $user, 'deletedhistory' ) ) {
			// If revision was hidden from sysops, disable the link
			if ( !$rev->userCan( RevisionRecord::DELETED_RESTRICTED, $user ) ) {
				$del = Linker::revDeleteLinkDisabled( false );
			// Otherwise, show the link...
			} else {
				$query = [ 'type' => 'revision',
					'target' => $this->getTitle()->getPrefixedDBkey(), 'ids' => $rev->getId() ];
				$del .= Linker::revDeleteLink( $query,
					$rev->isDeleted( RevisionRecord::DELETED_RESTRICTED ), false );
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
			Linker::revUserTools( $rev, true, false ) . "</span>";
		$s .= $dirmark;

		if ( $rev->isMinor() ) {
			$s .= ' ' . ChangesList::flag( 'minor', $this->getContext() );
		}

		# Sometimes rev_len isn't populated
		if ( $rev->getSize() !== null ) {
			# Size is always public data
			$prevSize = $this->parentLens[$row->rev_parent_id] ?? 0;
			$sDiff = ChangesList::showCharacterDifference( $prevSize, $rev->getSize() );
			$fSize = Linker::formatRevisionSize( $rev->getSize(), false );
			$s .= ' <span class="mw-changeslist-separator"></span> ' . "$fSize $sDiff";
		}

		# Text following the character difference is added just before running hooks
		$s2 = Linker::revComment( $rev, false, true, false );

		if ( $notificationtimestamp && ( $row->rev_timestamp >= $notificationtimestamp ) ) {
			$s2 .= ' <span class="updatedmarker">' . $this->msg( 'updatedmarker' )->escaped() . '</span>';
			$classes[] = 'mw-history-line-updated';
		}

		$tools = [];

		# Rollback and undo links

		if ( $prevRev && $permissionManager->quickUserCan( 'edit', $user, $this->getTitle() ) ) {
			if ( $latest && $permissionManager->quickUserCan( 'rollback',
					$user, $this->getTitle() )
			) {
				// Get a rollback link without the brackets
				$rollbackLink = Linker::generateRollback(
					$rev,
					$this->getContext(),
					[ 'verify', 'noBrackets' ]
				);
				if ( $rollbackLink ) {
					$this->preventClickjacking();
					$tools[] = $rollbackLink;
				}
			}

			if ( !$rev->isDeleted( RevisionRecord::DELETED_TEXT )
				&& !$prevRev->isDeleted( RevisionRecord::DELETED_TEXT )
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
						'undoafter' => $prevRev->getId(),
						'undo' => $rev->getId()
					]
				);
				$tools[] = "<span class=\"mw-history-undo\">{$undolink}</span>";
			}
		}
		// Allow extension to add their own links here
		Hooks::run( 'HistoryRevisionTools', [ $rev, &$tools, $prevRev, $user ] );

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
		if ( $s2 !== '' ) {
			$s .= ' <span class="mw-changeslist-separator"></span> ' . $s2;
		}

		$attribs = [ 'data-mw-revid' => $rev->getId() ];

		Hooks::run( 'PageHistoryLineEnding', [ $this, &$row, &$s, &$classes, &$attribs ] );
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
	 * @param Revision $rev
	 * @return string
	 */
	function revLink( $rev ) {
		return ChangesList::revDateLink( $rev, $this->getUser(), $this->getLanguage(),
			$this->getTitle() );
	}

	/**
	 * Create a diff-to-current link for this revision for this page
	 *
	 * @param Revision $rev
	 * @return string
	 */
	function curLink( $rev ) {
		$cur = $this->historyPage->message['cur'];
		$latest = $this->getWikiPage()->getLatest();
		if ( $latest === $rev->getId()
			|| !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getUser() )
		) {
			return $cur;
		} else {
			return $this->getLinkRenderer()->makeKnownLink(
				$this->getTitle(),
				new HtmlArmor( $cur ),
				[],
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
	 * @param Revision $prevRev The revision being displayed
	 * @param stdClass|string|null $next The next revision in list (that is
	 *        the previous one in chronological order).
	 *        May either be a row, "unknown" or null.
	 * @return string
	 */
	function lastLink( $prevRev, $next ) {
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
				[],
				[
					'diff' => $prevRev->getId(),
					'oldid' => 'prev'
				]
			);
		}

		$nextRev = new Revision( $next, 0, $this->getTitle() );

		if ( !$prevRev->userCan( RevisionRecord::DELETED_TEXT, $this->getUser() )
			|| !$nextRev->userCan( RevisionRecord::DELETED_TEXT, $this->getUser() )
		) {
			return $last;
		}

		return $linkRenderer->makeKnownLink(
			$this->getTitle(),
			new HtmlArmor( $last ),
			[],
			[
				'diff' => $prevRev->getId(),
				'oldid' => $next->rev_id
			]
		);
	}

	/**
	 * Create radio buttons for page history
	 *
	 * @param Revision $rev
	 * @param bool $firstInList Is this version the first one?
	 *
	 * @return string HTML output for the radio buttons
	 */
	function diffButtons( $rev, $firstInList ) {
		if ( $this->getNumRows() > 1 ) {
			$id = $rev->getId();
			$radio = [ 'type' => 'radio', 'value' => $id ];
			/** @todo Move title texts to javascript */
			if ( $firstInList ) {
				$first = Xml::element( 'input',
					array_merge( $radio, [
						'style' => 'visibility:hidden',
						'name' => 'oldid',
						'id' => 'mw-oldid-null' ] )
				);
				$checkmark = [ 'checked' => 'checked' ];
			} else {
				# Check visibility of old revisions
				if ( !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getUser() ) ) {
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
	 * @inheritDoc
	 */
	function getDefaultQuery() {
		parent::getDefaultQuery();
		unset( $this->mDefaultQuery['date-range-to'] );
		return $this->mDefaultQuery;
	}

	/**
	 * This is called if a write operation is possible from the generated HTML
	 * @param bool $enable
	 */
	function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * Get the "prevent clickjacking" flag
	 * @return bool
	 */
	function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}
