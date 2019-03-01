<?php
/**
 * User interface for the difference engine.
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
 * @ingroup DifferenceEngine
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\NameTableAccessException;

/**
 * DifferenceEngine is responsible for rendering the difference between two revisions as HTML.
 * This includes interpreting URL parameters, retrieving revision data, checking access permissions,
 * selecting and invoking the diff generator class for the individual slots, doing post-processing
 * on the generated diff, adding the rest of the HTML (such as headers) and writing the whole thing
 * to OutputPage.
 *
 * DifferenceEngine can be subclassed by extensions, by customizing
 * ContentHandler::createDifferenceEngine; the content handler will be selected based on the
 * content model of the main slot (of the new revision, when the two are different).
 * That might change after PageTypeHandler gets introduced.
 *
 * In the past, the class was also used for slot-level diff generation, and extensions might still
 * subclass it and add such functionality. When that is the case (sepcifically, when a
 * ContentHandler returns a standard SlotDiffRenderer but a nonstandard DifferenceEngine)
 * DifferenceEngineSlotDiffRenderer will be used to convert the old behavior into the new one.
 *
 * @ingroup DifferenceEngine
 *
 * @todo This class is huge and poorly defined. It should be split into a controller responsible
 * for interpreting query parameters, retrieving data and checking permissions; and a HTML renderer.
 */
class DifferenceEngine extends ContextSource {

	use DeprecationHelper;

	/**
	 * Constant to indicate diff cache compatibility.
	 * Bump this when changing the diff formatting in a way that
	 * fixes important bugs or such to force cached diff views to
	 * clear.
	 */
	const DIFF_VERSION = '1.12';

	/**
	 * Revision ID for the old revision. 0 for the revision previous to $mNewid, false
	 * if the diff does not have an old revision (e.g. 'oldid=<first revision of page>&diff=prev'),
	 * or the revision does not exist, null if the revision is unsaved.
	 * @var int|false|null
	 */
	protected $mOldid;

	/**
	 * Revision ID for the new revision. 0 for the last revision of the current page
	 * (as defined by the request context), false if the revision does not exist, null
	 * if it is unsaved, or an alias such as 'next'.
	 * @var int|string|false|null
	 */
	protected $mNewid;

	/**
	 * Old revision (left pane).
	 * Allowed to be an unsaved revision, unlikely that's ever needed though.
	 * False when the old revision does not exist; this can happen when using
	 * diff=prev on the first revision. Null when the revision should exist but
	 * doesn't (e.g. load failure); loadRevisionData() will return false in that
	 * case. Also null until lazy-loaded. Ignored completely when isContentOverridden
	 * is set.
	 * Since 1.32 public access is deprecated.
	 * @var Revision|null|false
	 */
	protected $mOldRev;

	/**
	 * New revision (right pane).
	 * Note that this might be an unsaved revision (e.g. for edit preview).
	 * Null in case of load failure; diff methods will just return an error message in that case,
	 * and loadRevisionData() will return false. Also null until lazy-loaded. Ignored completely
	 * when isContentOverridden is set.
	 * Since 1.32 public access is deprecated.
	 * @var Revision|null
	 */
	protected $mNewRev;

	/**
	 * Title of $mOldRev or null if the old revision does not exist or does not belong to a page.
	 * Since 1.32 public access is deprecated and the property can be null.
	 * @var Title|null
	 */
	protected $mOldPage;

	/**
	 * Title of $mNewRev or null if the new revision does not exist or does not belong to a page.
	 * Since 1.32 public access is deprecated and the property can be null.
	 * @var Title|null
	 */
	protected $mNewPage;

	/**
	 * Change tags of $mOldRev or null if it does not exist / is not saved.
	 * @var string[]|null
	 */
	private $mOldTags;

	/**
	 * Change tags of $mNewRev or null if it does not exist / is not saved.
	 * @var string[]|null
	 */
	private $mNewTags;

	/**
	 * @var Content|null
	 * @deprecated since 1.32, content slots are now handled by the corresponding SlotDiffRenderer.
	 *   This property is set to the content of the main slot, but not actually used for the main diff.
	 */
	private $mOldContent;

	/**
	 * @var Content|null
	 * @deprecated since 1.32, content slots are now handled by the corresponding SlotDiffRenderer.
	 *   This property is set to the content of the main slot, but not actually used for the main diff.
	 */
	private $mNewContent;

	/** @var Language */
	protected $mDiffLang;

	/** @var bool Have the revisions IDs been loaded */
	private $mRevisionsIdsLoaded = false;

	/** @var bool Have the revisions been loaded */
	protected $mRevisionsLoaded = false;

	/** @var int How many text blobs have been loaded, 0, 1 or 2? */
	protected $mTextLoaded = 0;

	/**
	 * Was the content overridden via setContent()?
	 * If the content was overridden, most internal state (e.g. mOldid or mOldRev) should be ignored
	 * and only mOldContent and mNewContent is reliable.
	 * (Note that setRevisions() does not set this flag as in that case all properties are
	 * overriden and remain consistent with each other, so no special handling is needed.)
	 * @var bool
	 */
	protected $isContentOverridden = false;

	/** @var bool Was the diff fetched from cache? */
	protected $mCacheHit = false;

	/**
	 * Set this to true to add debug info to the HTML output.
	 * Warning: this may cause RSS readers to spuriously mark articles as "new"
	 * (T22601)
	 */
	public $enableDebugComment = false;

	/** @var bool If true, line X is not displayed when X is 1, for example
	 *    to increase readability and conserve space with many small diffs.
	 */
	protected $mReducedLineNumbers = false;

	/** @var string Link to action=markpatrolled */
	protected $mMarkPatrolledLink = null;

	/** @var bool Show rev_deleted content if allowed */
	protected $unhide = false;

	/** @var bool Refresh the diff cache */
	protected $mRefreshCache = false;

	/** @var SlotDiffRenderer[] DifferenceEngine classes for the slots, keyed by role name. */
	protected $slotDiffRenderers = null;

	/**
	 * Temporary hack for B/C while slot diff related methods of DifferenceEngine are being
	 * deprecated. When true, we are inside a DifferenceEngineSlotDiffRenderer and
	 * $slotDiffRenderers should not be used.
	 * @var bool
	 */
	protected $isSlotDiffRenderer = false;

	/**#@-*/

	/**
	 * @param IContextSource|null $context Context to use, anything else will be ignored
	 * @param int $old Old ID we want to show and diff with.
	 * @param string|int $new Either revision ID or 'prev' or 'next'. Default: 0.
	 * @param int $rcid Deprecated, no longer used!
	 * @param bool $refreshCache If set, refreshes the diff cache
	 * @param bool $unhide If set, allow viewing deleted revs
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false
	) {
		$this->deprecatePublicProperty( 'mOldid', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mNewid', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mOldRev', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mNewRev', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mOldPage', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mNewPage', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mOldContent', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mNewContent', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mRevisionsLoaded', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mTextLoaded', '1.32', __CLASS__ );
		$this->deprecatePublicProperty( 'mCacheHit', '1.32', __CLASS__ );

		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
		}

		wfDebug( "DifferenceEngine old '$old' new '$new' rcid '$rcid'\n" );

		$this->mOldid = $old;
		$this->mNewid = $new;
		$this->mRefreshCache = $refreshCache;
		$this->unhide = $unhide;
	}

	/**
	 * @return SlotDiffRenderer[] Diff renderers for each slot, keyed by role name.
	 *   Includes slots only present in one of the revisions.
	 */
	protected function getSlotDiffRenderers() {
		if ( $this->isSlotDiffRenderer ) {
			throw new LogicException( __METHOD__ . ' called in slot diff renderer mode' );
		}

		if ( $this->slotDiffRenderers === null ) {
			if ( !$this->loadRevisionData() ) {
				return [];
			}

			$slotContents = $this->getSlotContents();
			$this->slotDiffRenderers = array_map( function ( $contents ) {
				/** @var $content Content */
				$content = $contents['new'] ?: $contents['old'];
				return $content->getContentHandler()->getSlotDiffRenderer( $this->getContext() );
			}, $slotContents );
		}
		return $this->slotDiffRenderers;
	}

	/**
	 * Mark this DifferenceEngine as a slot renderer (as opposed to a page renderer).
	 * This is used in legacy mode when the DifferenceEngine is wrapped in a
	 * DifferenceEngineSlotDiffRenderer.
	 * @internal For use by DifferenceEngineSlotDiffRenderer only.
	 */
	public function markAsSlotDiffRenderer() {
		$this->isSlotDiffRenderer = true;
	}

	/**
	 * Get the old and new content objects for all slots.
	 * This method does not do any permission checks.
	 * @return array [ role => [ 'old' => SlotRecord|null, 'new' => SlotRecord|null ], ... ]
	 */
	protected function getSlotContents() {
		if ( $this->isContentOverridden ) {
			return [
				SlotRecord::MAIN => [
					'old' => $this->mOldContent,
					'new' => $this->mNewContent,
				]
			];
		} elseif ( !$this->loadRevisionData() ) {
			return [];
		}

		$newSlots = $this->mNewRev->getRevisionRecord()->getSlots()->getSlots();
		if ( $this->mOldRev ) {
			$oldSlots = $this->mOldRev->getRevisionRecord()->getSlots()->getSlots();
		} else {
			$oldSlots = [];
		}
		// The order here will determine the visual order of the diff. The current logic is
		// slots of the new revision first in natural order, then deleted ones. This is ad hoc
		// and should not be relied on - in the future we may want the ordering to depend
		// on the page type.
		$roles = array_merge( array_keys( $newSlots ), array_keys( $oldSlots ) );

		$slots = [];
		foreach ( $roles as $role ) {
			$slots[$role] = [
				'old' => isset( $oldSlots[$role] ) ? $oldSlots[$role]->getContent() : null,
				'new' => isset( $newSlots[$role] ) ? $newSlots[$role]->getContent() : null,
			];
		}
		// move main slot to front
		if ( isset( $slots[SlotRecord::MAIN] ) ) {
			$slots = [ SlotRecord::MAIN => $slots[SlotRecord::MAIN] ] + $slots;
		}
		return $slots;
	}

	public function getTitle() {
		// T202454 avoid errors when there is no title
		return parent::getTitle() ?: Title::makeTitle( NS_SPECIAL, 'BadTitle/DifferenceEngine' );
	}

	/**
	 * Set reduced line numbers mode.
	 * When set, line X is not displayed when X is 1, for example to increase readability and
	 * conserve space with many small diffs.
	 * @param bool $value
	 */
	public function setReducedLineNumbers( $value = true ) {
		$this->mReducedLineNumbers = $value;
	}

	/**
	 * Get the language of the difference engine, defaults to page content language
	 *
	 * @return Language
	 */
	public function getDiffLang() {
		if ( $this->mDiffLang === null ) {
			# Default language in which the diff text is written.
			$this->mDiffLang = $this->getTitle()->getPageLanguage();
		}

		return $this->mDiffLang;
	}

	/**
	 * @return bool
	 */
	public function wasCacheHit() {
		return $this->mCacheHit;
	}

	/**
	 * Get the ID of old revision (left pane) of the diff. 0 for the revision
	 * previous to getNewid(), false if the old revision does not exist, null
	 * if it's unsaved.
	 * To get a real revision ID instead of 0, call loadRevisionData() first.
	 * @return int|false|null
	 */
	public function getOldid() {
		$this->loadRevisionIds();

		return $this->mOldid;
	}

	/**
	 * Get the ID of new revision (right pane) of the diff. 0 for the current revision,
	 * false if the new revision does not exist, null if it's unsaved.
	 * To get a real revision ID instead of 0, call loadRevisionData() first.
	 * @return int|false|null
	 */
	public function getNewid() {
		$this->loadRevisionIds();

		return $this->mNewid;
	}

	/**
	 * Get the left side of the diff.
	 * Could be null when the first revision of the page is diffed to 'prev' (or in the case of
	 * load failure).
	 * @return RevisionRecord|null
	 */
	public function getOldRevision() {
		return $this->mOldRev ? $this->mOldRev->getRevisionRecord() : null;
	}

	/**
	 * Get the right side of the diff.
	 * Should not be null but can still happen in the case of load failure.
	 * @return RevisionRecord|null
	 */
	public function getNewRevision() {
		return $this->mNewRev ? $this->mNewRev->getRevisionRecord() : null;
	}

	/**
	 * Look up a special:Undelete link to the given deleted revision id,
	 * as a workaround for being unable to load deleted diffs in currently.
	 *
	 * @param int $id Revision ID
	 *
	 * @return string|bool Link HTML or false
	 */
	public function deletedLink( $id ) {
		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$dbr = wfGetDB( DB_REPLICA );
			$arQuery = Revision::getArchiveQueryInfo();
			$row = $dbr->selectRow(
				$arQuery['tables'],
				array_merge( $arQuery['fields'], [ 'ar_namespace', 'ar_title' ] ),
				[ 'ar_rev_id' => $id ],
				__METHOD__,
				[],
				$arQuery['joins']
			);
			if ( $row ) {
				$rev = Revision::newFromArchiveRow( $row );
				$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );

				return SpecialPage::getTitleFor( 'Undelete' )->getFullURL( [
					'target' => $title->getPrefixedText(),
					'timestamp' => $rev->getTimestamp()
				] );
			}
		}

		return false;
	}

	/**
	 * Build a wikitext link toward a deleted revision, if viewable.
	 *
	 * @param int $id Revision ID
	 *
	 * @return string Wikitext fragment
	 */
	public function deletedIdMarker( $id ) {
		$link = $this->deletedLink( $id );
		if ( $link ) {
			return "[$link $id]";
		} else {
			return (string)$id;
		}
	}

	private function showMissingRevision() {
		$out = $this->getOutput();

		$missing = [];
		if ( $this->mOldRev === null ||
			( $this->mOldRev && $this->mOldContent === null )
		) {
			$missing[] = $this->deletedIdMarker( $this->mOldid );
		}
		if ( $this->mNewRev === null ||
			( $this->mNewRev && $this->mNewContent === null )
		) {
			$missing[] = $this->deletedIdMarker( $this->mNewid );
		}

		$out->setPageTitle( $this->msg( 'errorpagetitle' ) );
		$msg = $this->msg( 'difference-missing-revision' )
			->params( $this->getLanguage()->listToText( $missing ) )
			->numParams( count( $missing ) )
			->parseAsBlock();
		$out->addHTML( $msg );
	}

	public function showDiffPage( $diffOnly = false ) {
		# Allow frames except in certain special cases
		$out = $this->getOutput();
		$out->allowClickjacking();
		$out->setRobotPolicy( 'noindex,nofollow' );

		// Allow extensions to add any extra output here
		Hooks::run( 'DifferenceEngineShowDiffPage', [ $out ] );

		if ( !$this->loadRevisionData() ) {
			if ( Hooks::run( 'DifferenceEngineShowDiffPageMaybeShowMissingRevision', [ $this ] ) ) {
				$this->showMissingRevision();
			}
			return;
		}

		$user = $this->getUser();
		$permErrors = [];
		if ( $this->mNewPage ) {
			$permErrors = $this->mNewPage->getUserPermissionsErrors( 'read', $user );
		}
		if ( $this->mOldPage ) {
			$permErrors = wfMergeErrorArrays( $permErrors,
				$this->mOldPage->getUserPermissionsErrors( 'read', $user ) );
		}
		if ( count( $permErrors ) ) {
			throw new PermissionsError( 'read', $permErrors );
		}

		$rollback = '';

		$query = [];
		# Carry over 'diffonly' param via navigation links
		if ( $diffOnly != $user->getBoolOption( 'diffonly' ) ) {
			$query['diffonly'] = $diffOnly;
		}
		# Cascade unhide param in links for easy deletion browsing
		if ( $this->unhide ) {
			$query['unhide'] = 1;
		}

		# Check if one of the revisions is deleted/suppressed
		$deleted = $suppressed = false;
		$allowed = $this->mNewRev->userCan( Revision::DELETED_TEXT, $user );

		$revisionTools = [];

		# mOldRev is false if the difference engine is called with a "vague" query for
		# a diff between a version V and its previous version V' AND the version V
		# is the first version of that article. In that case, V' does not exist.
		if ( $this->mOldRev === false ) {
			if ( $this->mNewPage ) {
				$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
			}
			$samePage = true;
			$oldHeader = '';
			// Allow extensions to change the $oldHeader variable
			Hooks::run( 'DifferenceEngineOldHeaderNoOldRev', [ &$oldHeader ] );
		} else {
			Hooks::run( 'DiffViewHeader', [ $this, $this->mOldRev, $this->mNewRev ] );

			if ( !$this->mOldPage || !$this->mNewPage ) {
				// XXX say something to the user?
				$samePage = false;
			} elseif ( $this->mNewPage->equals( $this->mOldPage ) ) {
				$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
				$samePage = true;
			} else {
				$out->setPageTitle( $this->msg( 'difference-title-multipage',
					$this->mOldPage->getPrefixedText(), $this->mNewPage->getPrefixedText() ) );
				$out->addSubtitle( $this->msg( 'difference-multipage' ) );
				$samePage = false;
			}

			if ( $samePage && $this->mNewPage && $this->mNewPage->quickUserCan( 'edit', $user ) ) {
				if ( $this->mNewRev->isCurrent() && $this->mNewPage->userCan( 'rollback', $user ) ) {
					$rollbackLink = Linker::generateRollback( $this->mNewRev, $this->getContext() );
					if ( $rollbackLink ) {
						$out->preventClickjacking();
						$rollback = "\u{00A0}\u{00A0}\u{00A0}" . $rollbackLink;
					}
				}

				if ( !$this->mOldRev->isDeleted( Revision::DELETED_TEXT ) &&
					!$this->mNewRev->isDeleted( Revision::DELETED_TEXT )
				) {
					$undoLink = Html::element( 'a', [
							'href' => $this->mNewPage->getLocalURL( [
								'action' => 'edit',
								'undoafter' => $this->mOldid,
								'undo' => $this->mNewid
							] ),
							'title' => Linker::titleAttrib( 'undo' ),
						],
						$this->msg( 'editundo' )->text()
					);
					$revisionTools['mw-diff-undo'] = $undoLink;
				}
			}

			# Make "previous revision link"
			if ( $samePage && $this->mOldPage && $this->mOldRev->getPrevious() ) {
				$prevlink = Linker::linkKnown(
					$this->mOldPage,
					$this->msg( 'previousdiff' )->escaped(),
					[ 'id' => 'differences-prevlink' ],
					[ 'diff' => 'prev', 'oldid' => $this->mOldid ] + $query
				);
			} else {
				$prevlink = "\u{00A0}";
			}

			if ( $this->mOldRev->isMinor() ) {
				$oldminor = ChangesList::flag( 'minor' );
			} else {
				$oldminor = '';
			}

			$ldel = $this->revisionDeleteLink( $this->mOldRev );
			$oldRevisionHeader = $this->getRevisionHeader( $this->mOldRev, 'complete' );
			$oldChangeTags = ChangeTags::formatSummaryRow( $this->mOldTags, 'diff', $this->getContext() );

			$oldHeader = '<div id="mw-diff-otitle1"><strong>' . $oldRevisionHeader . '</strong></div>' .
				'<div id="mw-diff-otitle2">' .
				Linker::revUserTools( $this->mOldRev, !$this->unhide ) . '</div>' .
				'<div id="mw-diff-otitle3">' . $oldminor .
				Linker::revComment( $this->mOldRev, !$diffOnly, !$this->unhide ) . $ldel . '</div>' .
				'<div id="mw-diff-otitle5">' . $oldChangeTags[0] . '</div>' .
				'<div id="mw-diff-otitle4">' . $prevlink . '</div>';

			// Allow extensions to change the $oldHeader variable
			Hooks::run( 'DifferenceEngineOldHeader', [ $this, &$oldHeader, $prevlink, $oldminor,
				$diffOnly, $ldel, $this->unhide ] );

			if ( $this->mOldRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$deleted = true; // old revisions text is hidden
				if ( $this->mOldRev->isDeleted( Revision::DELETED_RESTRICTED ) ) {
					$suppressed = true; // also suppressed
				}
			}

			# Check if this user can see the revisions
			if ( !$this->mOldRev->userCan( Revision::DELETED_TEXT, $user ) ) {
				$allowed = false;
			}
		}

		$out->addJsConfigVars( [
			'wgDiffOldId' => $this->mOldid,
			'wgDiffNewId' => $this->mNewid,
		] );

		# Make "next revision link"
		# Skip next link on the top revision
		if ( $samePage && $this->mNewPage && !$this->mNewRev->isCurrent() ) {
			$nextlink = Linker::linkKnown(
				$this->mNewPage,
				$this->msg( 'nextdiff' )->escaped(),
				[ 'id' => 'differences-nextlink' ],
				[ 'diff' => 'next', 'oldid' => $this->mNewid ] + $query
			);
		} else {
			$nextlink = "\u{00A0}";
		}

		if ( $this->mNewRev->isMinor() ) {
			$newminor = ChangesList::flag( 'minor' );
		} else {
			$newminor = '';
		}

		# Handle RevisionDelete links...
		$rdel = $this->revisionDeleteLink( $this->mNewRev );

		# Allow extensions to define their own revision tools
		Hooks::run( 'DiffRevisionTools',
			[ $this->mNewRev, &$revisionTools, $this->mOldRev, $user ] );
		$formattedRevisionTools = [];
		// Put each one in parentheses (poor man's button)
		foreach ( $revisionTools as $key => $tool ) {
			$toolClass = is_string( $key ) ? $key : 'mw-diff-tool';
			$element = Html::rawElement(
				'span',
				[ 'class' => $toolClass ],
				$this->msg( 'parentheses' )->rawParams( $tool )->escaped()
			);
			$formattedRevisionTools[] = $element;
		}
		$newRevisionHeader = $this->getRevisionHeader( $this->mNewRev, 'complete' ) .
			' ' . implode( ' ', $formattedRevisionTools );
		$newChangeTags = ChangeTags::formatSummaryRow( $this->mNewTags, 'diff', $this->getContext() );

		$newHeader = '<div id="mw-diff-ntitle1"><strong>' . $newRevisionHeader . '</strong></div>' .
			'<div id="mw-diff-ntitle2">' . Linker::revUserTools( $this->mNewRev, !$this->unhide ) .
			" $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor .
			Linker::revComment( $this->mNewRev, !$diffOnly, !$this->unhide ) . $rdel . '</div>' .
			'<div id="mw-diff-ntitle5">' . $newChangeTags[0] . '</div>' .
			'<div id="mw-diff-ntitle4">' . $nextlink . $this->markPatrolledLink() . '</div>';

		// Allow extensions to change the $newHeader variable
		Hooks::run( 'DifferenceEngineNewHeader', [ $this, &$newHeader, $formattedRevisionTools,
			$nextlink, $rollback, $newminor, $diffOnly, $rdel, $this->unhide ] );

		if ( $this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
			$deleted = true; // new revisions text is hidden
			if ( $this->mNewRev->isDeleted( Revision::DELETED_RESTRICTED ) ) {
				$suppressed = true; // also suppressed
			}
		}

		# If the diff cannot be shown due to a deleted revision, then output
		# the diff header and links to unhide (if available)...
		if ( $deleted && ( !$this->unhide || !$allowed ) ) {
			$this->showDiffStyle();
			$multi = $this->getMultiNotice();
			$out->addHTML( $this->addHeader( '', $oldHeader, $newHeader, $multi ) );
			if ( !$allowed ) {
				$msg = $suppressed ? 'rev-suppressed-no-diff' : 'rev-deleted-no-diff';
				# Give explanation for why revision is not visible
				$out->wrapWikiMsg( "<div id='mw-$msg' class='mw-warning plainlinks'>\n$1\n</div>\n",
					[ $msg ] );
			} else {
				# Give explanation and add a link to view the diff...
				$query = $this->getRequest()->appendQueryValue( 'unhide', '1' );
				$link = $this->getTitle()->getFullURL( $query );
				$msg = $suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff';
				$out->wrapWikiMsg(
					"<div id='mw-$msg' class='mw-warning plainlinks'>\n$1\n</div>\n",
					[ $msg, $link ]
				);
			}
		# Otherwise, output a regular diff...
		} else {
			# Add deletion notice if the user is viewing deleted content
			$notice = '';
			if ( $deleted ) {
				$msg = $suppressed ? 'rev-suppressed-diff-view' : 'rev-deleted-diff-view';
				$notice = "<div id='mw-$msg' class='mw-warning plainlinks'>\n" .
					$this->msg( $msg )->parse() .
					"</div>\n";
			}
			$this->showDiff( $oldHeader, $newHeader, $notice );
			if ( !$diffOnly ) {
				$this->renderNewRevision();
			}
		}
	}

	/**
	 * Build a link to mark a change as patrolled.
	 *
	 * Returns empty string if there's either no revision to patrol or the user is not allowed to.
	 * Side effect: When the patrol link is build, this method will call
	 * OutputPage::preventClickjacking() and load mediawiki.page.patrol.ajax.
	 *
	 * @return string HTML or empty string
	 */
	public function markPatrolledLink() {
		if ( $this->mMarkPatrolledLink === null ) {
			$linkInfo = $this->getMarkPatrolledLinkInfo();
			// If false, there is no patrol link needed/allowed
			if ( !$linkInfo || !$this->mNewPage ) {
				$this->mMarkPatrolledLink = '';
			} else {
				$this->mMarkPatrolledLink = ' <span class="patrollink" data-mw="interface">[' .
					Linker::linkKnown(
						$this->mNewPage,
						$this->msg( 'markaspatrolleddiff' )->escaped(),
						[],
						[
							'action' => 'markpatrolled',
							'rcid' => $linkInfo['rcid'],
						]
					) . ']</span>';
				// Allow extensions to change the markpatrolled link
				Hooks::run( 'DifferenceEngineMarkPatrolledLink', [ $this,
					&$this->mMarkPatrolledLink, $linkInfo['rcid'] ] );
			}
		}
		return $this->mMarkPatrolledLink;
	}

	/**
	 * Returns an array of meta data needed to build a "mark as patrolled" link and
	 * adds the mediawiki.page.patrol.ajax to the output.
	 *
	 * @return array|false An array of meta data for a patrol link (rcid only)
	 *  or false if no link is needed
	 */
	protected function getMarkPatrolledLinkInfo() {
		$user = $this->getUser();
		$config = $this->getConfig();

		// Prepare a change patrol link, if applicable
		if (
			// Is patrolling enabled and the user allowed to?
			$config->get( 'UseRCPatrol' ) &&
			$this->mNewPage && $this->mNewPage->quickUserCan( 'patrol', $user ) &&
			// Only do this if the revision isn't more than 6 hours older
			// than the Max RC age (6h because the RC might not be cleaned out regularly)
			RecentChange::isInRCLifespan( $this->mNewRev->getTimestamp(), 21600 )
		) {
			// Look for an unpatrolled change corresponding to this diff
			$db = wfGetDB( DB_REPLICA );
			$change = RecentChange::newFromConds(
				[
					'rc_timestamp' => $db->timestamp( $this->mNewRev->getTimestamp() ),
					'rc_this_oldid' => $this->mNewid,
					'rc_patrolled' => RecentChange::PRC_UNPATROLLED
				],
				__METHOD__
			);

			if ( $change && !$change->getPerformer()->equals( $user ) ) {
				$rcid = $change->getAttribute( 'rc_id' );
			} else {
				// None found or the page has been created by the current user.
				// If the user could patrol this it already would be patrolled
				$rcid = 0;
			}

			// Allow extensions to possibly change the rcid here
			// For example the rcid might be set to zero due to the user
			// being the same as the performer of the change but an extension
			// might still want to show it under certain conditions
			Hooks::run( 'DifferenceEngineMarkPatrolledRCID', [ &$rcid, $this, $change, $user ] );

			// Build the link
			if ( $rcid ) {
				$this->getOutput()->preventClickjacking();
				if ( $user->isAllowed( 'writeapi' ) ) {
					$this->getOutput()->addModules( 'mediawiki.page.patrol.ajax' );
				}

				return [
					'rcid' => $rcid,
				];
			}
		}

		// No mark as patrolled link applicable
		return false;
	}

	/**
	 * @param Revision $rev
	 *
	 * @return string
	 */
	protected function revisionDeleteLink( $rev ) {
		$link = Linker::getRevDeleteLink( $this->getUser(), $rev, $rev->getTitle() );
		if ( $link !== '' ) {
			$link = "\u{00A0}\u{00A0}\u{00A0}" . $link . ' ';
		}

		return $link;
	}

	/**
	 * Show the new revision of the page.
	 *
	 * @note Not supported after calling setContent().
	 */
	public function renderNewRevision() {
		if ( $this->isContentOverridden ) {
			// The code below only works with a Revision object. We could construct a fake revision
			// (here or in setContent), but since this does not seem needed at the moment,
			// we'll just fail for now.
			throw new LogicException(
				__METHOD__
				. ' is not supported after calling setContent(). Use setRevisions() instead.'
			);
		}

		$out = $this->getOutput();
		$revHeader = $this->getRevisionHeader( $this->mNewRev );
		# Add "current version as of X" title
		$out->addHTML( "<hr class='diff-hr' id='mw-oldid' />
		<h2 class='diff-currentversion-title'>{$revHeader}</h2>\n" );
		# Page content may be handled by a hooked call instead...
		if ( Hooks::run( 'ArticleContentOnDiff', [ $this, $out ] ) ) {
			$this->loadNewText();
			if ( !$this->mNewPage ) {
				// New revision is unsaved; bail out.
				// TODO in theory rendering the new revision is a meaningful thing to do
				// even if it's unsaved, but a lot of untangling is required to do it safely.
				return;
			}

			$out->setRevisionId( $this->mNewid );
			$out->setRevisionTimestamp( $this->mNewRev->getTimestamp() );
			$out->setArticleFlag( true );

			if ( !Hooks::run( 'ArticleRevisionViewCustom',
				[ $this->mNewRev->getRevisionRecord(), $this->mNewPage, $out ] )
			) {
				// Handled by extension
				// NOTE: sync with hooks called in Article::view()
			} elseif ( !Hooks::run( 'ArticleContentViewCustom',
				[ $this->mNewContent, $this->mNewPage, $out ], '1.32' )
			) {
				// Handled by extension
				// NOTE: sync with hooks called in Article::view()
			} else {
				// Normal page
				if ( $this->getTitle()->equals( $this->mNewPage ) ) {
					// If the Title stored in the context is the same as the one
					// of the new revision, we can use its associated WikiPage
					// object.
					$wikiPage = $this->getWikiPage();
				} else {
					// Otherwise we need to create our own WikiPage object
					$wikiPage = WikiPage::factory( $this->mNewPage );
				}

				$parserOutput = $this->getParserOutput( $wikiPage, $this->mNewRev );

				# WikiPage::getParserOutput() should not return false, but just in case
				if ( $parserOutput ) {
					// Allow extensions to change parser output here
					if ( Hooks::run( 'DifferenceEngineRenderRevisionAddParserOutput',
						[ $this, $out, $parserOutput, $wikiPage ] )
					) {
						$out->addParserOutput( $parserOutput, [
							'enableSectionEditLinks' => $this->mNewRev->isCurrent()
								&& $this->mNewRev->getTitle()->quickUserCan( 'edit', $this->getUser() ),
						] );
					}
				}
			}
		}

		// Allow extensions to optionally not show the final patrolled link
		if ( Hooks::run( 'DifferenceEngineRenderRevisionShowFinalPatrolLink' ) ) {
			# Add redundant patrol link on bottom...
			$out->addHTML( $this->markPatrolledLink() );
		}
	}

	/**
	 * @param WikiPage $page
	 * @param Revision $rev
	 *
	 * @return ParserOutput|bool False if the revision was not found
	 */
	protected function getParserOutput( WikiPage $page, Revision $rev ) {
		if ( !$rev->getId() ) {
			// WikiPage::getParserOutput wants a revision ID. Passing 0 will incorrectly show
			// the current revision, so fail instead. If need be, WikiPage::getParserOutput
			// could be made to accept a Revision or RevisionRecord instead of the id.
			return false;
		}

		$parserOptions = $page->makeParserOptions( $this->getContext() );
		$parserOutput = $page->getParserOutput( $parserOptions, $rev->getId() );

		return $parserOutput;
	}

	/**
	 * Get the diff text, send it to the OutputPage object
	 * Returns false if the diff could not be generated, otherwise returns true
	 *
	 * @param string|bool $otitle Header for old text or false
	 * @param string|bool $ntitle Header for new text or false
	 * @param string $notice HTML between diff header and body
	 *
	 * @return bool
	 */
	public function showDiff( $otitle, $ntitle, $notice = '' ) {
		// Allow extensions to affect the output here
		Hooks::run( 'DifferenceEngineShowDiff', [ $this ] );

		$diff = $this->getDiff( $otitle, $ntitle, $notice );
		if ( $diff === false ) {
			$this->showMissingRevision();

			return false;
		} else {
			$this->showDiffStyle();
			$this->getOutput()->addHTML( $diff );

			return true;
		}
	}

	/**
	 * Add style sheets for diff display.
	 */
	public function showDiffStyle() {
		if ( !$this->isSlotDiffRenderer ) {
			$this->getOutput()->addModuleStyles( [
				'mediawiki.interface.helpers.styles',
				'mediawiki.diff.styles'
			] );
			foreach ( $this->getSlotDiffRenderers() as $slotDiffRenderer ) {
				$slotDiffRenderer->addModules( $this->getOutput() );
			}
		}
	}

	/**
	 * Get complete diff table, including header
	 *
	 * @param string|bool $otitle Header for old text or false
	 * @param string|bool $ntitle Header for new text or false
	 * @param string $notice HTML between diff header and body
	 *
	 * @return mixed
	 */
	public function getDiff( $otitle, $ntitle, $notice = '' ) {
		$body = $this->getDiffBody();
		if ( $body === false ) {
			return false;
		}

		$multi = $this->getMultiNotice();
		// Display a message when the diff is empty
		if ( $body === '' ) {
			$notice .= '<div class="mw-diff-empty">' .
				$this->msg( 'diff-empty' )->parse() .
				"</div>\n";
		}

		return $this->addHeader( $body, $otitle, $ntitle, $multi, $notice );
	}

	/**
	 * Get the diff table body, without header
	 *
	 * @return mixed (string/false)
	 */
	public function getDiffBody() {
		$this->mCacheHit = true;
		// Check if the diff should be hidden from this user
		if ( !$this->isContentOverridden ) {
			if ( !$this->loadRevisionData() ) {
				return false;
			} elseif ( $this->mOldRev &&
				!$this->mOldRev->userCan( Revision::DELETED_TEXT, $this->getUser() )
			) {
				return false;
			} elseif ( $this->mNewRev &&
				!$this->mNewRev->userCan( Revision::DELETED_TEXT, $this->getUser() )
			) {
				return false;
			}
			// Short-circuit
			if ( $this->mOldRev === false || ( $this->mOldRev && $this->mNewRev &&
				$this->mOldRev->getId() && $this->mOldRev->getId() == $this->mNewRev->getId() )
			) {
				if ( Hooks::run( 'DifferenceEngineShowEmptyOldContent', [ $this ] ) ) {
					return '';
				}
			}
		}

		// Cacheable?
		$key = false;
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		if ( $this->mOldid && $this->mNewid ) {
			// Check if subclass is still using the old way
			// for backwards-compatibility
			$key = $this->getDiffBodyCacheKey();
			if ( $key === null ) {
				$key = $cache->makeKey( ...$this->getDiffBodyCacheKeyParams() );
			}

			// Try cache
			if ( !$this->mRefreshCache ) {
				$difftext = $cache->get( $key );
				if ( is_string( $difftext ) ) {
					wfIncrStats( 'diff_cache.hit' );
					$difftext = $this->localiseDiff( $difftext );
					$difftext .= "\n<!-- diff cache key $key -->\n";

					return $difftext;
				}
			} // don't try to load but save the result
		}
		$this->mCacheHit = false;

		// Loadtext is permission safe, this just clears out the diff
		if ( !$this->loadText() ) {
			return false;
		}

		$difftext = '';
		// We've checked for revdelete at the beginning of this method; it's OK to ignore
		// read permissions here.
		$slotContents = $this->getSlotContents();
		foreach ( $this->getSlotDiffRenderers() as $role => $slotDiffRenderer ) {
			$slotDiff = $slotDiffRenderer->getDiff( $slotContents[$role]['old'],
				$slotContents[$role]['new'] );
			if ( $slotDiff && $role !== SlotRecord::MAIN ) {
				// FIXME: ask SlotRoleHandler::getSlotNameMessage
				$slotTitle = $role;
				$difftext .= $this->getSlotHeader( $slotTitle );
			}
			$difftext .= $slotDiff;
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$diffEngine = $this;

		// Save to cache for 7 days
		if ( !Hooks::run( 'AbortDiffCache', [ &$diffEngine ] ) ) {
			wfIncrStats( 'diff_cache.uncacheable' );
		} elseif ( $key !== false && $difftext !== false ) {
			wfIncrStats( 'diff_cache.miss' );
			$cache->set( $key, $difftext, 7 * 86400 );
		} else {
			wfIncrStats( 'diff_cache.uncacheable' );
		}
		// localise line numbers and title attribute text
		if ( $difftext !== false ) {
			$difftext = $this->localiseDiff( $difftext );
		}

		return $difftext;
	}

	/**
	 * Get the diff table body for one slot, without header
	 *
	 * @param string $role
	 * @return string|false
	 */
	public function getDiffBodyForRole( $role ) {
		$diffRenderers = $this->getSlotDiffRenderers();
		if ( !isset( $diffRenderers[$role] ) ) {
			return false;
		}

		$slotContents = $this->getSlotContents();
		$slotDiff = $diffRenderers[$role]->getDiff( $slotContents[$role]['old'],
			$slotContents[$role]['new'] );
		if ( !$slotDiff ) {
			return false;
		}

		if ( $role !== SlotRecord::MAIN ) {
			// TODO use human-readable role name at least
			$slotTitle = $role;
			$slotDiff = $this->getSlotHeader( $slotTitle ) . $slotDiff;
		}

		return $this->localiseDiff( $slotDiff );
	}

	/**
	 * Get a slot header for inclusion in a diff body (as a table row).
	 *
	 * @param string $headerText The text of the header
	 * @return string
	 *
	 */
	protected function getSlotHeader( $headerText ) {
		// The old revision is missing on oldid=<first>&diff=prev; only 2 columns in that case.
		$columnCount = $this->mOldRev ? 4 : 2;
		$userLang = $this->getLanguage()->getHtmlCode();
		return Html::rawElement( 'tr', [ 'class' => 'mw-diff-slot-header', 'lang' => $userLang ],
			Html::element( 'th', [ 'colspan' => $columnCount ], $headerText ) );
	}

	/**
	 * Returns the cache key for diff body text or content.
	 *
	 * @deprecated since 1.31, use getDiffBodyCacheKeyParams() instead
	 * @since 1.23
	 *
	 * @throws MWException
	 * @return string|null
	 */
	protected function getDiffBodyCacheKey() {
		return null;
	}

	/**
	 * Get the cache key parameters
	 *
	 * Subclasses can replace the first element in the array to something
	 * more specific to the type of diff (e.g. "inline-diff"), or append
	 * if the cache should vary on more things. Overriding entirely should
	 * be avoided.
	 *
	 * @since 1.31
	 *
	 * @return array
	 * @throws MWException
	 */
	protected function getDiffBodyCacheKeyParams() {
		if ( !$this->mOldid || !$this->mNewid ) {
			throw new MWException( 'mOldid and mNewid must be set to get diff cache key.' );
		}

		$engine = $this->getEngine();
		$params = [
			'diff',
			$engine,
			self::DIFF_VERSION,
			"old-{$this->mOldid}",
			"rev-{$this->mNewid}"
		];

		if ( $engine === 'wikidiff2' ) {
			$params[] = phpversion( 'wikidiff2' );
			$params[] = $this->getConfig()->get( 'WikiDiff2MovedParagraphDetectionCutoff' );
		}

		if ( !$this->isSlotDiffRenderer ) {
			foreach ( $this->getSlotDiffRenderers() as $slotDiffRenderer ) {
				$params = array_merge( $params, $slotDiffRenderer->getExtraCacheKeys() );
			}
		}

		return $params;
	}

	/**
	 * Implements DifferenceEngineSlotDiffRenderer::getExtraCacheKeys(). Only used when
	 * DifferenceEngine is wrapped in DifferenceEngineSlotDiffRenderer.
	 * @return array
	 * @internal for use by DifferenceEngineSlotDiffRenderer only
	 * @deprecated
	 */
	public function getExtraCacheKeys() {
		// This method is called when the DifferenceEngine is used for a slot diff. We only care
		// about special things, not the revision IDs, which are added to the cache key by the
		// page-level DifferenceEngine, and which might not have a valid value for this object.
		$this->mOldid = 123456789;
		$this->mNewid = 987654321;

		// This will repeat a bunch of unnecessary key fields for each slot. Not nice but harmless.
		$cacheString = $this->getDiffBodyCacheKey();
		if ( $cacheString ) {
			return [ $cacheString ];
		}

		$params = $this->getDiffBodyCacheKeyParams();

		// Try to get rid of the standard keys to keep the cache key human-readable:
		// call the getDiffBodyCacheKeyParams implementation of the base class, and if
		// the child class includes the same keys, drop them.
		// Uses an obscure PHP feature where static calls to non-static methods are allowed
		// as long as we are already in a non-static method of the same class, and the call context
		// ($this) will be inherited.
		// phpcs:ignore Squiz.Classes.SelfMemberReference.NotUsed
		$standardParams = DifferenceEngine::getDiffBodyCacheKeyParams();
		if ( array_slice( $params, 0, count( $standardParams ) ) === $standardParams ) {
			$params = array_slice( $params, count( $standardParams ) );
		}

		return $params;
	}

	/**
	 * Generate a diff, no caching.
	 *
	 * @since 1.21
	 *
	 * @param Content $old Old content
	 * @param Content $new New content
	 *
	 * @throws Exception If old or new content is not an instance of TextContent.
	 * @return bool|string
	 *
	 * @deprecated since 1.32, use a SlotDiffRenderer instead.
	 */
	public function generateContentDiffBody( Content $old, Content $new ) {
		$slotDiffRenderer = $new->getContentHandler()->getSlotDiffRenderer( $this->getContext() );
		if (
			$slotDiffRenderer instanceof DifferenceEngineSlotDiffRenderer
			&& $this->isSlotDiffRenderer
		) {
			// Oops, we are just about to enter an infinite loop (the slot-level DifferenceEngine
			// called a DifferenceEngineSlotDiffRenderer that wraps the same DifferenceEngine class).
			// This will happen when a content model has no custom slot diff renderer, it does have
			// a custom difference engine, but that does not override this method.
			throw new Exception( get_class( $this ) . ': could not maintain backwards compatibility. '
				. 'Please use a SlotDiffRenderer.' );
		}
		return $slotDiffRenderer->getDiff( $old, $new ) . $this->getDebugString();
	}

	/**
	 * Generate a diff, no caching
	 *
	 * @param string $otext Old text, must be already segmented
	 * @param string $ntext New text, must be already segmented
	 *
	 * @throws Exception If content handling for text content is configured in a way
	 *   that makes maintaining B/C hard.
	 * @return bool|string
	 *
	 * @deprecated since 1.32, use a TextSlotDiffRenderer instead.
	 */
	public function generateTextDiffBody( $otext, $ntext ) {
		$slotDiffRenderer = ContentHandler::getForModelID( CONTENT_MODEL_TEXT )
			->getSlotDiffRenderer( $this->getContext() );
		if ( !( $slotDiffRenderer instanceof TextSlotDiffRenderer ) ) {
			// Someone used the GetSlotDiffRenderer hook to replace the renderer.
			// This is too unlikely to happen to bother handling properly.
			throw new Exception( 'The slot diff renderer for text content should be a '
				. 'TextSlotDiffRenderer subclass' );
		}
		return $slotDiffRenderer->getTextDiff( $otext, $ntext ) . $this->getDebugString();
	}

	/**
	 * Process $wgExternalDiffEngine and get a sane, usable engine
	 *
	 * @return bool|string 'wikidiff2', path to an executable, or false
	 * @internal For use by this class and TextSlotDiffRenderer only.
	 */
	public static function getEngine() {
		global $wgExternalDiffEngine;
		// We use the global here instead of Config because we write to the value,
		// and Config is not mutable.
		if ( $wgExternalDiffEngine == 'wikidiff' || $wgExternalDiffEngine == 'wikidiff3' ) {
			wfDeprecated( "\$wgExternalDiffEngine = '{$wgExternalDiffEngine}'", '1.27' );
			$wgExternalDiffEngine = false;
		} elseif ( $wgExternalDiffEngine == 'wikidiff2' ) {
			wfDeprecated( "\$wgExternalDiffEngine = '{$wgExternalDiffEngine}'", '1.32' );
			$wgExternalDiffEngine = false;
		} elseif ( !is_string( $wgExternalDiffEngine ) && $wgExternalDiffEngine !== false ) {
			// And prevent people from shooting themselves in the foot...
			wfWarn( '$wgExternalDiffEngine is set to a non-string value, forcing it to false' );
			$wgExternalDiffEngine = false;
		}

		if ( is_string( $wgExternalDiffEngine ) && is_executable( $wgExternalDiffEngine ) ) {
			return $wgExternalDiffEngine;
		} elseif ( $wgExternalDiffEngine === false && function_exists( 'wikidiff2_do_diff' ) ) {
			return 'wikidiff2';
		} else {
			// Native PHP
			return false;
		}
	}

	/**
	 * Generates diff, to be wrapped internally in a logging/instrumentation
	 *
	 * @param string $otext Old text, must be already segmented
	 * @param string $ntext New text, must be already segmented
	 *
	 * @throws Exception If content handling for text content is configured in a way
	 *   that makes maintaining B/C hard.
	 * @return bool|string
	 *
	 * @deprecated since 1.32, use a TextSlotDiffRenderer instead.
	 */
	protected function textDiff( $otext, $ntext ) {
		$slotDiffRenderer = ContentHandler::getForModelID( CONTENT_MODEL_TEXT )
			->getSlotDiffRenderer( $this->getContext() );
		if ( !( $slotDiffRenderer instanceof TextSlotDiffRenderer ) ) {
			// Someone used the GetSlotDiffRenderer hook to replace the renderer.
			// This is too unlikely to happen to bother handling properly.
			throw new Exception( 'The slot diff renderer for text content should be a '
				. 'TextSlotDiffRenderer subclass' );
		}
		return $slotDiffRenderer->getTextDiff( $otext, $ntext ) . $this->getDebugString();
	}

	/**
	 * Generate a debug comment indicating diff generating time,
	 * server node, and generator backend.
	 *
	 * @param string $generator : What diff engine was used
	 *
	 * @return string
	 */
	protected function debug( $generator = "internal" ) {
		if ( !$this->enableDebugComment ) {
			return '';
		}
		$data = [ $generator ];
		if ( $this->getConfig()->get( 'ShowHostnames' ) ) {
			$data[] = wfHostname();
		}
		$data[] = wfTimestamp( TS_DB );

		return "<!-- diff generator: " .
			implode( " ", array_map( "htmlspecialchars", $data ) ) .
			" -->\n";
	}

	private function getDebugString() {
		$engine = self::getEngine();
		if ( $engine === 'wikidiff2' ) {
			return $this->debug( 'wikidiff2' );
		} elseif ( $engine === false ) {
			return $this->debug( 'native PHP' );
		} else {
			return $this->debug( "external $engine" );
		}
	}

	/**
	 * Localise diff output
	 *
	 * @param string $text
	 * @return string
	 */
	private function localiseDiff( $text ) {
		$text = $this->localiseLineNumbers( $text );
		if ( $this->getEngine() === 'wikidiff2' &&
			version_compare( phpversion( 'wikidiff2' ), '1.5.1', '>=' )
		) {
			$text = $this->addLocalisedTitleTooltips( $text );
		}
		return $text;
	}

	/**
	 * Replace line numbers with the text in the user's language
	 *
	 * @param string $text
	 *
	 * @return mixed
	 */
	public function localiseLineNumbers( $text ) {
		return preg_replace_callback(
			'/<!--LINE (\d+)-->/',
			[ $this, 'localiseLineNumbersCb' ],
			$text
		);
	}

	public function localiseLineNumbersCb( $matches ) {
		if ( $matches[1] === '1' && $this->mReducedLineNumbers ) {
			return '';
		}

		return $this->msg( 'lineno' )->numParams( $matches[1] )->escaped();
	}

	/**
	 * Add title attributes for tooltips on moved paragraph indicators
	 *
	 * @param string $text
	 * @return string
	 */
	private function addLocalisedTitleTooltips( $text ) {
		return preg_replace_callback(
			'/class="mw-diff-movedpara-(left|right)"/',
			[ $this, 'addLocalisedTitleTooltipsCb' ],
			$text
		);
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	private function addLocalisedTitleTooltipsCb( array $matches ) {
		$key = $matches[1] === 'right' ?
			'diff-paragraph-moved-toold' :
			'diff-paragraph-moved-tonew';
		return $matches[0] . ' title="' . $this->msg( $key )->escaped() . '"';
	}

	/**
	 * If there are revisions between the ones being compared, return a note saying so.
	 *
	 * @return string
	 */
	public function getMultiNotice() {
		// The notice only make sense if we are diffing two saved revisions of the same page.
		if (
			!$this->mOldRev || !$this->mNewRev
			|| !$this->mOldPage || !$this->mNewPage
			|| !$this->mOldPage->equals( $this->mNewPage )
		) {
			return '';
		}

		if ( $this->mOldRev->getTimestamp() > $this->mNewRev->getTimestamp() ) {
			$oldRev = $this->mNewRev; // flip
			$newRev = $this->mOldRev; // flip
		} else { // normal case
			$oldRev = $this->mOldRev;
			$newRev = $this->mNewRev;
		}

		// Sanity: don't show the notice if too many rows must be scanned
		// @todo show some special message for that case
		$nEdits = $this->mNewPage->countRevisionsBetween( $oldRev, $newRev, 1000 );
		if ( $nEdits > 0 && $nEdits <= 1000 ) {
			$limit = 100; // use diff-multi-manyusers if too many users
			$users = $this->mNewPage->getAuthorsBetween( $oldRev, $newRev, $limit );
			$numUsers = count( $users );

			if ( $numUsers == 1 && $users[0] == $newRev->getUserText( Revision::RAW ) ) {
				$numUsers = 0; // special case to say "by the same user" instead of "by one other user"
			}

			return self::intermediateEditsMsg( $nEdits, $numUsers, $limit );
		}

		return '';
	}

	/**
	 * Get a notice about how many intermediate edits and users there are
	 *
	 * @param int $numEdits
	 * @param int $numUsers
	 * @param int $limit
	 *
	 * @return string
	 */
	public static function intermediateEditsMsg( $numEdits, $numUsers, $limit ) {
		if ( $numUsers === 0 ) {
			$msg = 'diff-multi-sameuser';
		} elseif ( $numUsers > $limit ) {
			$msg = 'diff-multi-manyusers';
			$numUsers = $limit;
		} else {
			$msg = 'diff-multi-otherusers';
		}

		return wfMessage( $msg )->numParams( $numEdits, $numUsers )->parse();
	}

	/**
	 * Get a header for a specified revision.
	 *
	 * @param Revision $rev
	 * @param string $complete 'complete' to get the header wrapped depending
	 *        the visibility of the revision and a link to edit the page.
	 *
	 * @return string HTML fragment
	 */
	public function getRevisionHeader( Revision $rev, $complete = '' ) {
		$lang = $this->getLanguage();
		$user = $this->getUser();
		$revtimestamp = $rev->getTimestamp();
		$timestamp = $lang->userTimeAndDate( $revtimestamp, $user );
		$dateofrev = $lang->userDate( $revtimestamp, $user );
		$timeofrev = $lang->userTime( $revtimestamp, $user );

		$header = $this->msg(
			$rev->isCurrent() ? 'currentrev-asof' : 'revisionasof',
			$timestamp,
			$dateofrev,
			$timeofrev
		)->escaped();

		if ( $complete !== 'complete' ) {
			return $header;
		}

		$title = $rev->getTitle();

		$header = Linker::linkKnown( $title, $header, [],
			[ 'oldid' => $rev->getId() ] );

		if ( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$editQuery = [ 'action' => 'edit' ];
			if ( !$rev->isCurrent() ) {
				$editQuery['oldid'] = $rev->getId();
			}

			$key = $title->quickUserCan( 'edit', $user ) ? 'editold' : 'viewsourceold';
			$msg = $this->msg( $key )->escaped();
			$editLink = $this->msg( 'parentheses' )->rawParams(
				Linker::linkKnown( $title, $msg, [], $editQuery ) )->escaped();
			$header .= ' ' . Html::rawElement(
				'span',
				[ 'class' => 'mw-diff-edit' ],
				$editLink
			);
			if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$header = Html::rawElement(
					'span',
					[ 'class' => 'history-deleted' ],
					$header
				);
			}
		} else {
			$header = Html::rawElement( 'span', [ 'class' => 'history-deleted' ], $header );
		}

		return $header;
	}

	/**
	 * Add the header to a diff body
	 *
	 * @param string $diff Diff body
	 * @param string $otitle Old revision header
	 * @param string $ntitle New revision header
	 * @param string $multi Notice telling user that there are intermediate
	 *   revisions between the ones being compared
	 * @param string $notice Other notices, e.g. that user is viewing deleted content
	 *
	 * @return string
	 */
	public function addHeader( $diff, $otitle, $ntitle, $multi = '', $notice = '' ) {
		// shared.css sets diff in interface language/dir, but the actual content
		// is often in a different language, mostly the page content language/dir
		$header = Html::openElement( 'table', [
			'class' => [ 'diff', 'diff-contentalign-' . $this->getDiffLang()->alignStart() ],
			'data-mw' => 'interface',
		] );
		$userLang = htmlspecialchars( $this->getLanguage()->getHtmlCode() );

		if ( !$diff && !$otitle ) {
			$header .= "
			<tr class=\"diff-title\" lang=\"{$userLang}\">
			<td class=\"diff-ntitle\">{$ntitle}</td>
			</tr>";
			$multiColspan = 1;
		} else {
			if ( $diff ) { // Safari/Chrome show broken output if cols not used
				$header .= "
				<col class=\"diff-marker\" />
				<col class=\"diff-content\" />
				<col class=\"diff-marker\" />
				<col class=\"diff-content\" />";
				$colspan = 2;
				$multiColspan = 4;
			} else {
				$colspan = 1;
				$multiColspan = 2;
			}
			if ( $otitle || $ntitle ) {
				$header .= "
				<tr class=\"diff-title\" lang=\"{$userLang}\">
				<td colspan=\"$colspan\" class=\"diff-otitle\">{$otitle}</td>
				<td colspan=\"$colspan\" class=\"diff-ntitle\">{$ntitle}</td>
				</tr>";
			}
		}

		if ( $multi != '' ) {
			$header .= "<tr><td colspan=\"{$multiColspan}\" " .
				"class=\"diff-multi\" lang=\"{$userLang}\">{$multi}</td></tr>";
		}
		if ( $notice != '' ) {
			$header .= "<tr><td colspan=\"{$multiColspan}\" " .
				"class=\"diff-notice\" lang=\"{$userLang}\">{$notice}</td></tr>";
		}

		return $header . $diff . "</table>";
	}

	/**
	 * Use specified text instead of loading from the database
	 * @param Content $oldContent
	 * @param Content $newContent
	 * @since 1.21
	 * @deprecated since 1.32, use setRevisions or ContentHandler::getSlotDiffRenderer.
	 */
	public function setContent( Content $oldContent, Content $newContent ) {
		$this->mOldContent = $oldContent;
		$this->mNewContent = $newContent;

		$this->mTextLoaded = 2;
		$this->mRevisionsLoaded = true;
		$this->isContentOverridden = true;
		$this->slotDiffRenderers = null;
	}

	/**
	 * Use specified text instead of loading from the database.
	 * @param RevisionRecord|null $oldRevision
	 * @param RevisionRecord $newRevision
	 */
	public function setRevisions(
		RevisionRecord $oldRevision = null, RevisionRecord $newRevision
	) {
		if ( $oldRevision ) {
			$this->mOldRev = new Revision( $oldRevision );
			$this->mOldid = $oldRevision->getId();
			$this->mOldPage = Title::newFromLinkTarget( $oldRevision->getPageAsLinkTarget() );
			// This method is meant for edit diffs and such so there is no reason to provide a
			// revision that's not readable to the user, but check it just in case.
			$this->mOldContent = $oldRevision->getContent( SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER, $this->getUser() );
		} else {
			$this->mOldPage = null;
			$this->mOldRev = $this->mOldid = false;
		}
		$this->mNewRev = new Revision( $newRevision );
		$this->mNewid = $newRevision->getId();
		$this->mNewPage = Title::newFromLinkTarget( $newRevision->getPageAsLinkTarget() );
		$this->mNewContent = $newRevision->getContent( SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER, $this->getUser() );

		$this->mRevisionsIdsLoaded = $this->mRevisionsLoaded = true;
		$this->mTextLoaded = $oldRevision ? 2 : 1;
		$this->isContentOverridden = false;
		$this->slotDiffRenderers = null;
	}

	/**
	 * Set the language in which the diff text is written
	 *
	 * @param Language $lang
	 * @since 1.19
	 */
	public function setTextLanguage( Language $lang ) {
		$this->mDiffLang = $lang;
	}

	/**
	 * Maps a revision pair definition as accepted by DifferenceEngine constructor
	 * to a pair of actual integers representing revision ids.
	 *
	 * @param int $old Revision id, e.g. from URL parameter 'oldid'
	 * @param int|string $new Revision id or strings 'next' or 'prev', e.g. from URL parameter 'diff'
	 *
	 * @return array List of two revision ids, older first, later second.
	 *     Zero signifies invalid argument passed.
	 *     false signifies that there is no previous/next revision ($old is the oldest/newest one).
	 */
	public function mapDiffPrevNext( $old, $new ) {
		if ( $new === 'prev' ) {
			// Show diff between revision $old and the previous one. Get previous one from DB.
			$newid = intval( $old );
			$oldid = $this->getTitle()->getPreviousRevisionID( $newid );
		} elseif ( $new === 'next' ) {
			// Show diff between revision $old and the next one. Get next one from DB.
			$oldid = intval( $old );
			$newid = $this->getTitle()->getNextRevisionID( $oldid );
		} else {
			$oldid = intval( $old );
			$newid = intval( $new );
		}

		return [ $oldid, $newid ];
	}

	/**
	 * Load revision IDs
	 */
	private function loadRevisionIds() {
		if ( $this->mRevisionsIdsLoaded ) {
			return;
		}

		$this->mRevisionsIdsLoaded = true;

		$old = $this->mOldid;
		$new = $this->mNewid;

		list( $this->mOldid, $this->mNewid ) = self::mapDiffPrevNext( $old, $new );
		if ( $new === 'next' && $this->mNewid === false ) {
			# if no result, NewId points to the newest old revision. The only newer
			# revision is cur, which is "0".
			$this->mNewid = 0;
		}

		Hooks::run(
			'NewDifferenceEngine',
			[ $this->getTitle(), &$this->mOldid, &$this->mNewid, $old, $new ]
		);
	}

	/**
	 * Load revision metadata for the specified revisions. If newid is 0, then compare
	 * the old revision in oldid to the current revision of the current page (as defined
	 * by the request context); if oldid is 0, then compare the revision in newid to the
	 * immediately previous one.
	 *
	 * If oldid is false, leave the corresponding revision object set
	 * to false. This can happen with 'diff=prev' pointing to a non-existent revision,
	 * and is also used directly by the API.
	 *
	 * @return bool Whether both revisions were loaded successfully. Setting mOldRev
	 *   to false counts as successful loading.
	 */
	public function loadRevisionData() {
		if ( $this->mRevisionsLoaded ) {
			return $this->isContentOverridden || $this->mNewRev && !is_null( $this->mOldRev );
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mRevisionsLoaded = true;

		$this->loadRevisionIds();

		// Load the new revision object
		if ( $this->mNewid ) {
			$this->mNewRev = Revision::newFromId( $this->mNewid );
		} else {
			$this->mNewRev = Revision::newFromTitle(
				$this->getTitle(),
				false,
				Revision::READ_NORMAL
			);
		}

		if ( !$this->mNewRev instanceof Revision ) {
			return false;
		}

		// Update the new revision ID in case it was 0 (makes life easier doing UI stuff)
		$this->mNewid = $this->mNewRev->getId();
		if ( $this->mNewid ) {
			$this->mNewPage = $this->mNewRev->getTitle();
		} else {
			$this->mNewPage = null;
		}

		// Load the old revision object
		$this->mOldRev = false;
		if ( $this->mOldid ) {
			$this->mOldRev = Revision::newFromId( $this->mOldid );
		} elseif ( $this->mOldid === 0 ) {
			$rev = $this->mNewRev->getPrevious();
			if ( $rev ) {
				$this->mOldid = $rev->getId();
				$this->mOldRev = $rev;
			} else {
				// No previous revision; mark to show as first-version only.
				$this->mOldid = false;
				$this->mOldRev = false;
			}
		} /* elseif ( $this->mOldid === false ) leave mOldRev false; */

		if ( is_null( $this->mOldRev ) ) {
			return false;
		}

		if ( $this->mOldRev && $this->mOldRev->getId() ) {
			$this->mOldPage = $this->mOldRev->getTitle();
		} else {
			$this->mOldPage = null;
		}

		// Load tags information for both revisions
		$dbr = wfGetDB( DB_REPLICA );
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		if ( $this->mOldid !== false ) {
			$tagIds = $dbr->selectFieldValues(
				'change_tag',
				'ct_tag_id',
				[ 'ct_rev_id' => $this->mOldid ],
				__METHOD__
			);
			$tags = [];
			foreach ( $tagIds as $tagId ) {
				try {
					$tags[] = $changeTagDefStore->getName( (int)$tagId );
				} catch ( NameTableAccessException $exception ) {
					continue;
				}
			}
			$this->mOldTags = implode( ',', $tags );
		} else {
			$this->mOldTags = false;
		}

		$tagIds = $dbr->selectFieldValues(
			'change_tag',
			'ct_tag_id',
			[ 'ct_rev_id' => $this->mNewid ],
			__METHOD__
		);
		$tags = [];
		foreach ( $tagIds as $tagId ) {
			try {
				$tags[] = $changeTagDefStore->getName( (int)$tagId );
			} catch ( NameTableAccessException $exception ) {
				continue;
			}
		}
		$this->mNewTags = implode( ',', $tags );

		return true;
	}

	/**
	 * Load the text of the revisions, as well as revision data.
	 * When the old revision is missing (mOldRev is false), loading mOldContent is not attempted.
	 *
	 * @return bool Whether the content of both revisions could be loaded successfully.
	 *   (When mOldRev is false, that still counts as a success.)
	 *
	 */
	public function loadText() {
		if ( $this->mTextLoaded == 2 ) {
			return $this->loadRevisionData() && ( $this->mOldRev === false || $this->mOldContent )
				&& $this->mNewContent;
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mTextLoaded = 2;

		if ( !$this->loadRevisionData() ) {
			return false;
		}

		if ( $this->mOldRev ) {
			$this->mOldContent = $this->mOldRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );
			if ( $this->mOldContent === null ) {
				return false;
			}
		}

		$this->mNewContent = $this->mNewRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );
		Hooks::run( 'DifferenceEngineLoadTextAfterNewContentIsLoaded', [ $this ] );
		if ( $this->mNewContent === null ) {
			return false;
		}

		return true;
	}

	/**
	 * Load the text of the new revision, not the old one
	 *
	 * @return bool Whether the content of the new revision could be loaded successfully.
	 */
	public function loadNewText() {
		if ( $this->mTextLoaded >= 1 ) {
			return $this->loadRevisionData();
		}

		$this->mTextLoaded = 1;

		if ( !$this->loadRevisionData() ) {
			return false;
		}

		$this->mNewContent = $this->mNewRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );

		Hooks::run( 'DifferenceEngineAfterLoadNewText', [ $this ] );

		return true;
	}

}
