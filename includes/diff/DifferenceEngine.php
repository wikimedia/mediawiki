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

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\User\UserOptionsLookup;

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
 * subclass it and add such functionality. When that is the case (specifically, when a
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
	private const DIFF_VERSION = '1.12';

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
	 * @var RevisionRecord|null|false
	 */
	private $mOldRevisionRecord;

	/**
	 * New revision (right pane).
	 * Note that this might be an unsaved revision (e.g. for edit preview).
	 * Null in case of load failure; diff methods will just return an error message in that case,
	 * and loadRevisionData() will return false. Also null until lazy-loaded. Ignored completely
	 * when isContentOverridden is set.
	 * @var RevisionRecord|null
	 */
	private $mNewRevisionRecord;

	/**
	 * Title of old revision or null if the old revision does not exist or does not belong to a page.
	 * Since 1.32 public access is deprecated and the property can be null.
	 * @var Title|null
	 */
	protected $mOldPage;

	/**
	 * Title of new revision or null if the new revision does not exist or does not belong to a page.
	 * Since 1.32 public access is deprecated and the property can be null.
	 * @var Title|null
	 */
	protected $mNewPage;

	/**
	 * Change tags of old revision or null if it does not exist / is not saved.
	 * @var string|false|null
	 */
	private $mOldTags;

	/**
	 * Change tags of new revision or null if it does not exist / is not saved.
	 * @var string|null
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
	 * overridden and remain consistent with each other, so no special handling is needed.)
	 * @var bool
	 */
	protected $isContentOverridden = false;

	/** @var bool Was the diff fetched from cache? */
	protected $mCacheHit = false;

	/**
	 * Set this to true to add debug info to the HTML output.
	 * Warning: this may cause RSS readers to spuriously mark articles as "new"
	 * (T22601)
	 * @var bool
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

	/** @var SlotDiffRenderer[]|null DifferenceEngine classes for the slots, keyed by role name. */
	protected $slotDiffRenderers = null;

	/**
	 * Temporary hack for B/C while slot diff related methods of DifferenceEngine are being
	 * deprecated. When true, we are inside a DifferenceEngineSlotDiffRenderer and
	 * $slotDiffRenderers should not be used.
	 * @var bool
	 */
	protected $isSlotDiffRenderer = false;

	/**
	 * A set of options that will be passed to the SlotDiffRenderer upon creation
	 * @var array
	 */
	private $slotDiffOptions = [];

	/**
	 * @var LinkRenderer
	 */
	protected $linkRenderer;

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/** @var HookRunner */
	private $hookRunner;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** #@- */

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

		wfDebug( "DifferenceEngine old '$old' new '$new' rcid '$rcid'" );

		$this->mOldid = $old;
		$this->mNewid = $new;
		$this->mRefreshCache = $refreshCache;
		$this->unhide = $unhide;

		$services = MediaWikiServices::getInstance();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->contentHandlerFactory = $services->getContentHandlerFactory();
		$this->revisionStore = $services->getRevisionStore();
		$this->hookRunner = new HookRunner( $services->getHookContainer() );
		$this->wikiPageFactory = $services->getWikiPageFactory();
		$this->userOptionsLookup = $services->getUserOptionsLookup();
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
			$this->slotDiffRenderers = array_map( function ( array $contents ) {
				/** @var Content $content */
				$content = $contents['new'] ?: $contents['old'];
				return $content->getContentHandler()->getSlotDiffRenderer(
					$this->getContext(),
					$this->slotDiffOptions
				);
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
				SlotRecord::MAIN => [ 'old' => $this->mOldContent, 'new' => $this->mNewContent ]
			];
		} elseif ( !$this->loadRevisionData() ) {
			return [];
		}

		$newSlots = $this->mNewRevisionRecord->getPrimarySlots()->getSlots();
		$oldSlots = $this->mOldRevisionRecord ?
			$this->mOldRevisionRecord->getPrimarySlots()->getSlots() :
			[];
		// The order here will determine the visual order of the diff. The current logic is
		// slots of the new revision first in natural order, then deleted ones. This is ad hoc
		// and should not be relied on - in the future we may want the ordering to depend
		// on the page type.
		$roles = array_keys( array_merge( $newSlots, $oldSlots ) );

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

	/** @inheritDoc */
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
		return $this->mOldRevisionRecord ?: null;
	}

	/**
	 * Get the right side of the diff.
	 * Should not be null but can still happen in the case of load failure.
	 * @return RevisionRecord|null
	 */
	public function getNewRevision() {
		return $this->mNewRevisionRecord;
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
		if ( $this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$dbr = wfGetDB( DB_REPLICA );
			$arQuery = $this->revisionStore->getArchiveQueryInfo();
			$row = $dbr->selectRow(
				$arQuery['tables'],
				array_merge( $arQuery['fields'], [ 'ar_namespace', 'ar_title' ] ),
				[ 'ar_rev_id' => $id ],
				__METHOD__,
				[],
				$arQuery['joins']
			);
			if ( $row ) {
				$revRecord = $this->revisionStore->newRevisionFromArchiveRow( $row );
				$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );

				return SpecialPage::getTitleFor( 'Undelete' )->getFullURL( [
					'target' => $title->getPrefixedText(),
					'timestamp' => $revRecord->getTimestamp()
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
		if ( $this->mOldid && ( !$this->mOldRevisionRecord || !$this->mOldContent ) ) {
			$missing[] = $this->deletedIdMarker( $this->mOldid );
		}
		if ( !$this->mNewRevisionRecord || !$this->mNewContent ) {
			$missing[] = $this->deletedIdMarker( $this->mNewid );
		}

		$out->setPageTitle( $this->msg( 'errorpagetitle' ) );
		$msg = $this->msg( 'difference-missing-revision' )
			->params( $this->getLanguage()->listToText( $missing ) )
			->numParams( count( $missing ) )
			->parseAsBlock();
		$out->addHTML( $msg );
	}

	/**
	 * Checks whether one of the given Revisions was deleted
	 *
	 * @return bool
	 */
	public function hasDeletedRevision() {
		$this->loadRevisionData();
		return (
				$this->mNewRevisionRecord &&
				$this->mNewRevisionRecord->isDeleted( RevisionRecord::DELETED_TEXT )
			) ||
			(
				$this->mOldRevisionRecord &&
				$this->mOldRevisionRecord->isDeleted( RevisionRecord::DELETED_TEXT )
			);
	}

	/**
	 * Get the permission errors associated with the revisions for the current diff.
	 *
	 * @param Authority $performer
	 * @return array[] Array of arrays of the arguments to wfMessage to explain permissions problems.
	 */
	public function getPermissionErrors( Authority $performer ) {
		$this->loadRevisionData();
		$permStatus = PermissionStatus::newEmpty();
		if ( $this->mNewPage ) {
			$performer->authorizeRead( 'read', $this->mNewPage, $permStatus );
		}
		if ( $this->mOldPage ) {
			$performer->authorizeRead( 'read', $this->mOldPage, $permStatus );
		}
		return $permStatus->toLegacyErrorArray();
	}

	/**
	 * Checks whether one of the given Revisions was suppressed
	 *
	 * @return bool
	 */
	public function hasSuppressedRevision() {
		return $this->hasDeletedRevision() && (
			( $this->mOldRevisionRecord &&
				$this->mOldRevisionRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ) ||
			( $this->mNewRevisionRecord &&
				$this->mNewRevisionRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ) )
		);
	}

	/**
	 * Checks whether the current user has permission for accessing the revisions of the diff.
	 * Note that this does not check whether the user has permission to view the page, it only
	 * checks revdelete permissions.
	 *
	 * It is the caller's responsibility to call
	 * $this->getUserPermissionErrors or similar checks.
	 *
	 * @param Authority $performer
	 * @return bool
	 */
	public function isUserAllowedToSeeRevisions( Authority $performer ) {
		$this->loadRevisionData();

		if ( $this->mOldRevisionRecord && !$this->mOldRevisionRecord->userCan(
			RevisionRecord::DELETED_TEXT,
			$performer
		) ) {
			return false;
		}

		// $this->mNewRev will only be falsy if a loading error occurred
		// (in which case the user is allowed to see).
		return !$this->mNewRevisionRecord || $this->mNewRevisionRecord->userCan(
			RevisionRecord::DELETED_TEXT,
			$performer
		);
	}

	/**
	 * Checks whether the diff should be hidden from the current user
	 * This is based on whether the user is allowed to see it and has specifically asked to see it.
	 *
	 * @param Authority $performer
	 * @return bool
	 */
	public function shouldBeHiddenFromUser( Authority $performer ) {
		return $this->hasDeletedRevision() && ( !$this->unhide ||
			!$this->isUserAllowedToSeeRevisions( $performer ) );
	}

	/**
	 * @param bool $diffOnly
	 */
	public function showDiffPage( $diffOnly = false ) {
		# Allow frames except in certain special cases
		$out = $this->getOutput();
		$out->setPreventClickjacking( false );
		$out->setRobotPolicy( 'noindex,nofollow' );

		// Allow extensions to add any extra output here
		$this->hookRunner->onDifferenceEngineShowDiffPage( $out );

		if ( !$this->loadRevisionData() ) {
			if ( $this->hookRunner->onDifferenceEngineShowDiffPageMaybeShowMissingRevision( $this ) ) {
				$this->showMissingRevision();
			}
			return;
		}

		$user = $this->getUser();
		$permErrors = $this->getPermissionErrors( $this->getAuthority() );
		if ( $permErrors ) {
			throw new PermissionsError( 'read', $permErrors );
		}

		$rollback = '';

		$query = $this->slotDiffOptions;
		# Carry over 'diffonly' param via navigation links
		if ( $diffOnly != MediaWikiServices::getInstance()
			->getUserOptionsLookup()->getBoolOption( $user, 'diffonly' )
		) {
			$query['diffonly'] = $diffOnly;
		}
		# Cascade unhide param in links for easy deletion browsing
		if ( $this->unhide ) {
			$query['unhide'] = 1;
		}

		# Check if one of the revisions is deleted/suppressed
		$deleted = $this->hasDeletedRevision();
		$suppressed = $this->hasSuppressedRevision();
		$allowed = $this->isUserAllowedToSeeRevisions( $this->getAuthority() );

		$revisionTools = [];

		# mOldRevisionRecord is false if the difference engine is called with a "vague" query for
		# a diff between a version V and its previous version V' AND the version V
		# is the first version of that article. In that case, V' does not exist.
		if ( $this->mOldRevisionRecord === false ) {
			if ( $this->mNewPage ) {
				$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
			}
			$samePage = true;
			$oldHeader = '';
			// Allow extensions to change the $oldHeader variable
			$this->hookRunner->onDifferenceEngineOldHeaderNoOldRev( $oldHeader );
		} else {
			$this->hookRunner->onDifferenceEngineViewHeader( $this );

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

			if ( $samePage && $this->mNewPage &&
				$this->getAuthority()->probablyCan( 'edit', $this->mNewPage )
			) {
				if ( $this->mNewRevisionRecord->isCurrent() &&
					$this->getAuthority()->probablyCan( 'rollback', $this->mNewPage )
				) {
					$rollbackLink = Linker::generateRollback(
						$this->mNewRevisionRecord,
						$this->getContext(),
						[ 'noBrackets' ]
					);
					if ( $rollbackLink ) {
						$out->setPreventClickjacking( true );
						$rollback = "\u{00A0}\u{00A0}\u{00A0}" . $rollbackLink;
					}
				}

				if ( $this->userCanEdit( $this->mOldRevisionRecord ) &&
					$this->userCanEdit( $this->mNewRevisionRecord )
				) {
					$undoLink = $this->linkRenderer->makeKnownLink(
						$this->mNewPage,
						$this->msg( 'editundo' )->text(),
						[ 'title' => Linker::titleAttrib( 'undo' ) ],
						[
							'action' => 'edit',
							'undoafter' => $this->mOldid,
							'undo' => $this->mNewid
						]
					);
					$revisionTools['mw-diff-undo'] = $undoLink;
				}
			}
			# Make "previous revision link"
			$hasPrevious = $samePage && $this->mOldPage &&
				$this->revisionStore->getPreviousRevision( $this->mOldRevisionRecord );
			if ( $hasPrevious ) {
				$prevlink = $this->linkRenderer->makeKnownLink(
					$this->mOldPage,
					$this->msg( 'previousdiff' )->text(),
					[ 'id' => 'differences-prevlink' ],
					[ 'diff' => 'prev', 'oldid' => $this->mOldid ] + $query
				);
			} else {
				$prevlink = "\u{00A0}";
			}

			if ( $this->mOldRevisionRecord->isMinor() ) {
				$oldminor = ChangesList::flag( 'minor' );
			} else {
				$oldminor = '';
			}

			$oldRevRecord = $this->mOldRevisionRecord;

			$ldel = $this->revisionDeleteLink( $oldRevRecord );
			$oldRevisionHeader = $this->getRevisionHeader( $oldRevRecord, 'complete' );
			$oldChangeTags = ChangeTags::formatSummaryRow( $this->mOldTags, 'diff', $this->getContext() );
			$oldRevComment = Linker::revComment( $oldRevRecord, !$diffOnly, !$this->unhide );

			if ( $oldRevComment === '' ) {
				$defaultComment = $this->msg( 'changeslist-nocomment' )->escaped();
				$oldRevComment = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
			}

			$oldHeader = '<div id="mw-diff-otitle1"><strong>' . $oldRevisionHeader . '</strong></div>' .
				'<div id="mw-diff-otitle2">' .
				Linker::revUserTools( $oldRevRecord, !$this->unhide ) . '</div>' .
				'<div id="mw-diff-otitle3">' . $oldminor . $oldRevComment . $ldel . '</div>' .
				'<div id="mw-diff-otitle5">' . $oldChangeTags[0] . '</div>' .
				'<div id="mw-diff-otitle4">' . $prevlink . '</div>';

			// Allow extensions to change the $oldHeader variable
			$this->hookRunner->onDifferenceEngineOldHeader(
				$this, $oldHeader, $prevlink, $oldminor, $diffOnly, $ldel, $this->unhide );
		}

		$out->addJsConfigVars( [
			'wgDiffOldId' => $this->mOldid,
			'wgDiffNewId' => $this->mNewid,
		] );

		# Make "next revision link"
		# Skip next link on the top revision
		if ( $samePage && $this->mNewPage && !$this->mNewRevisionRecord->isCurrent() ) {
			$nextlink = $this->linkRenderer->makeKnownLink(
				$this->mNewPage,
				$this->msg( 'nextdiff' )->text(),
				[ 'id' => 'differences-nextlink' ],
				[ 'diff' => 'next', 'oldid' => $this->mNewid ] + $query
			);
		} else {
			$nextlink = "\u{00A0}";
		}

		if ( $this->mNewRevisionRecord->isMinor() ) {
			$newminor = ChangesList::flag( 'minor' );
		} else {
			$newminor = '';
		}

		# Handle RevisionDelete links...
		$rdel = $this->revisionDeleteLink( $this->mNewRevisionRecord );

		# Allow extensions to define their own revision tools
		$this->hookRunner->onDiffTools(
			$this->mNewRevisionRecord,
			$revisionTools,
			$this->mOldRevisionRecord ?: null,
			$user
		);

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

		$newRevRecord = $this->mNewRevisionRecord;

		$newRevisionHeader = $this->getRevisionHeader( $newRevRecord, 'complete' ) .
			' ' . implode( ' ', $formattedRevisionTools );
		$newChangeTags = ChangeTags::formatSummaryRow( $this->mNewTags, 'diff', $this->getContext() );
		$newRevComment = Linker::revComment( $newRevRecord, !$diffOnly, !$this->unhide );

		if ( $newRevComment === '' ) {
			$defaultComment = $this->msg( 'changeslist-nocomment' )->escaped();
			$newRevComment = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
		}

		$newHeader = '<div id="mw-diff-ntitle1"><strong>' . $newRevisionHeader . '</strong></div>' .
			'<div id="mw-diff-ntitle2">' . Linker::revUserTools( $newRevRecord, !$this->unhide ) .
			" $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor . $newRevComment . $rdel . '</div>' .
			'<div id="mw-diff-ntitle5">' . $newChangeTags[0] . '</div>' .
			'<div id="mw-diff-ntitle4">' . $nextlink . $this->markPatrolledLink() . '</div>';

		// Allow extensions to change the $newHeader variable
		$this->hookRunner->onDifferenceEngineNewHeader( $this, $newHeader,
			$formattedRevisionTools, $nextlink, $rollback, $newminor, $diffOnly,
			$rdel, $this->unhide );

		# If the diff cannot be shown due to a deleted revision, then output
		# the diff header and links to unhide (if available)...
		if ( $this->shouldBeHiddenFromUser( $this->getAuthority() ) ) {
			$this->showDiffStyle();
			$multi = $this->getMultiNotice();
			$out->addHTML( $this->addHeader( '', $oldHeader, $newHeader, $multi ) );
			if ( !$allowed ) {
				# Give explanation for why revision is not visible
				$msg = [ $suppressed ? 'rev-suppressed-no-diff' : 'rev-deleted-no-diff' ];
			} else {
				# Give explanation and add a link to view the diff...
				$query = $this->getRequest()->appendQueryValue( 'unhide', '1' );
				$msg = [
					$suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff',
					$this->getTitle()->getFullURL( $query )
				];
			}
			$out->addHtml( Html::warningBox( $this->msg( ...$msg )->parse(), 'plainlinks' ) );
		# Otherwise, output a regular diff...
		} else {
			# Add deletion notice if the user is viewing deleted content
			$notice = '';
			if ( $deleted ) {
				$msg = $suppressed ? 'rev-suppressed-diff-view' : 'rev-deleted-diff-view';
				$notice = Html::warningBox( $this->msg( $msg )->parse(), 'plainlinks' );
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
	 *
	 * Side effect: When the patrol link is build, this method will call
	 * OutputPage::setPreventClickjacking(true) and load a JS module.
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
					$this->linkRenderer->makeKnownLink(
						$this->mNewPage,
						$this->msg( 'markaspatrolleddiff' )->text(),
						[],
						[
							'action' => 'markpatrolled',
							'rcid' => $linkInfo['rcid'],
						]
					) . ']</span>';
				// Allow extensions to change the markpatrolled link
				$this->hookRunner->onDifferenceEngineMarkPatrolledLink( $this,
					$this->mMarkPatrolledLink, $linkInfo['rcid'] );
			}
		}
		return $this->mMarkPatrolledLink;
	}

	/**
	 * Returns an array of meta data needed to build a "mark as patrolled" link and
	 * adds a JS module to the output.
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
			$config->get( MainConfigNames::UseRCPatrol ) &&
			$this->mNewPage &&
			$this->getAuthority()->probablyCan( 'patrol', $this->mNewPage ) &&
			// Only do this if the revision isn't more than 6 hours older
			// than the Max RC age (6h because the RC might not be cleaned out regularly)
			RecentChange::isInRCLifespan( $this->mNewRevisionRecord->getTimestamp(), 21600 )
		) {
			// Look for an unpatrolled change corresponding to this diff
			$change = RecentChange::newFromConds(
				[
					'rc_this_oldid' => $this->mNewid,
					'rc_patrolled' => RecentChange::PRC_UNPATROLLED
				],
				__METHOD__
			);

			if ( $change && !$change->getPerformerIdentity()->equals( $user ) ) {
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
			$this->hookRunner->onDifferenceEngineMarkPatrolledRCID( $rcid, $this, $change, $user );

			// Build the link
			if ( $rcid ) {
				$this->getOutput()->setPreventClickjacking( true );
				if ( $this->getAuthority()->isAllowed( 'writeapi' ) ) {
					$this->getOutput()->addModules( 'mediawiki.misc-authed-curate' );
				}

				return [ 'rcid' => $rcid ];
			}
		}

		// No mark as patrolled link applicable
		return false;
	}

	/**
	 * @param RevisionRecord $revRecord
	 *
	 * @return string
	 */
	private function revisionDeleteLink( RevisionRecord $revRecord ) {
		$link = Linker::getRevDeleteLink(
			$this->getAuthority(),
			$revRecord,
			$revRecord->getPageAsLinkTarget()
		);
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
			// The code below only works with a RevisionRecord object. We could construct a
			// fake RevisionRecord (here or in setContent), but since this does not seem
			// needed at the moment, we'll just fail for now.
			throw new LogicException(
				__METHOD__
				. ' is not supported after calling setContent(). Use setRevisions() instead.'
			);
		}

		$out = $this->getOutput();
		$revHeader = $this->getRevisionHeader( $this->mNewRevisionRecord );
		# Add "current version as of X" title
		$out->addHTML( "<hr class='diff-hr' id='mw-oldid' />
		<h2 class='diff-currentversion-title'>{$revHeader}</h2>\n" );
		# Page content may be handled by a hooked call instead...
		if ( $this->hookRunner->onArticleContentOnDiff( $this, $out ) ) {
			$this->loadNewText();
			if ( !$this->mNewPage ) {
				// New revision is unsaved; bail out.
				// TODO in theory rendering the new revision is a meaningful thing to do
				// even if it's unsaved, but a lot of untangling is required to do it safely.
				return;
			}

			$out->setRevisionId( $this->mNewid );
			$out->setRevisionIsCurrent( $this->mNewRevisionRecord->isCurrent() );
			$out->setRevisionTimestamp( $this->mNewRevisionRecord->getTimestamp() );
			$out->setArticleFlag( true );

			if ( !$this->hookRunner->onArticleRevisionViewCustom(
				$this->mNewRevisionRecord, $this->mNewPage, $this->mOldid, $out )
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
					$wikiPage = $this->wikiPageFactory->newFromTitle( $this->mNewPage );
				}

				$parserOutput = $this->getParserOutput( $wikiPage, $this->mNewRevisionRecord );

				# WikiPage::getParserOutput() should not return false, but just in case
				if ( $parserOutput ) {
					// Allow extensions to change parser output here
					if ( $this->hookRunner->onDifferenceEngineRenderRevisionAddParserOutput(
						$this, $out, $parserOutput, $wikiPage )
					) {
						$skinOptions = $this->getSkin()->getOptions();
						$out->setSections( $parserOutput->getSections() );
						$out->addParserOutput( $parserOutput, [
							// phab:T311529 - diffs should respect skin
							'injectTOC' => $skinOptions['toc'],
							'enableSectionEditLinks' => $this->mNewRevisionRecord->isCurrent()
								&& $this->getAuthority()->probablyCan(
									'edit',
									$this->mNewRevisionRecord->getPage()
								),
							'absoluteURLs' => $this->slotDiffOptions['expand-url'] ?? false
						] );
					}
				}
			}
		}

		// Allow extensions to optionally not show the final patrolled link
		if ( $this->hookRunner->onDifferenceEngineRenderRevisionShowFinalPatrolLink() ) {
			# Add redundant patrol link on bottom...
			$out->addHTML( $this->markPatrolledLink() );
		}
	}

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord $revRecord
	 *
	 * @return ParserOutput|false False if the revision was not found
	 */
	protected function getParserOutput( WikiPage $page, RevisionRecord $revRecord ) {
		if ( !$revRecord->getId() ) {
			// WikiPage::getParserOutput wants a revision ID. Passing 0 will incorrectly show
			// the current revision, so fail instead. If need be, WikiPage::getParserOutput
			// could be made to accept a RevisionRecord instead of the id.
			return false;
		}

		$parserOptions = $page->makeParserOptions( $this->getContext() );
		return $page->getParserOutput( $parserOptions, $revRecord->getId() );
	}

	/**
	 * Get the diff text, send it to the OutputPage object
	 * Returns false if the diff could not be generated, otherwise returns true
	 *
	 * @param string|false $otitle Header for old text or false
	 * @param string|false $ntitle Header for new text or false
	 * @param string $notice HTML between diff header and body
	 *
	 * @return bool
	 */
	public function showDiff( $otitle, $ntitle, $notice = '' ) {
		// Allow extensions to affect the output here
		$this->hookRunner->onDifferenceEngineShowDiff( $this );

		$diff = $this->getDiff( $otitle, $ntitle, $notice );
		if ( $diff === false ) {
			$this->showMissingRevision();
			return false;
		}

		$this->showDiffStyle();
		if ( $this->slotDiffOptions['expand-url'] ?? false ) {
			$diff = Linker::expandLocalLinks( $diff );
		}
		$this->getOutput()->addHTML( $diff );
		return true;
	}

	/**
	 * Add style sheets for diff display.
	 */
	public function showDiffStyle() {
		if ( !$this->isSlotDiffRenderer ) {
			$this->getOutput()->addModules( 'mediawiki.diff' );
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
	 * @param string|false $otitle Header for old text or false
	 * @param string|false $ntitle Header for new text or false
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
	 * @return string|false
	 */
	public function getDiffBody() {
		$this->mCacheHit = true;
		// Check if the diff should be hidden from this user
		if ( !$this->isContentOverridden ) {
			if ( !$this->loadRevisionData() ) {
				return false;
			} elseif ( $this->mOldRevisionRecord &&
				!$this->mOldRevisionRecord->userCan(
					RevisionRecord::DELETED_TEXT,
					$this->getAuthority()
				)
			) {
				return false;
			} elseif ( $this->mNewRevisionRecord &&
				!$this->mNewRevisionRecord->userCan(
					RevisionRecord::DELETED_TEXT,
					$this->getAuthority()
				) ) {
				return false;
			}
			// Short-circuit
			if ( $this->mOldRevisionRecord === false || (
				$this->mOldRevisionRecord &&
				$this->mNewRevisionRecord &&
				$this->mOldRevisionRecord->getId() &&
				$this->mOldRevisionRecord->getId() == $this->mNewRevisionRecord->getId()
			) ) {
				if ( $this->hookRunner->onDifferenceEngineShowEmptyOldContent( $this ) ) {
					return '';
				}
			}
		}

		// Cacheable?
		$key = false;
		$services = MediaWikiServices::getInstance();
		$cache = $services->getMainWANObjectCache();
		$stats = $services->getStatsdDataFactory();
		if ( $this->mOldid && $this->mNewid ) {
			// Check if subclass is still using the old way
			// for backwards-compatibility
			$detected = MWDebug::detectDeprecatedOverride(
				$this,
				__CLASS__,
				'getDiffBodyCacheKey',
				'1.31'
			);
			$key = null;
			if ( $detected ) {
				$key = $this->getDiffBodyCacheKey();
			}
			if ( $key === null ) {
				$key = $cache->makeKey( ...$this->getDiffBodyCacheKeyParams() );
			}

			// Try cache
			if ( !$this->mRefreshCache ) {
				$difftext = $cache->get( $key );
				if ( is_string( $difftext ) ) {
					$stats->updateCount( 'diff_cache.hit', 1 );
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

		// Save to cache for 7 days
		if ( !$this->hookRunner->onAbortDiffCache( $this ) ) {
			$stats->updateCount( 'diff_cache.uncacheable', 1 );
		} elseif ( $key !== false ) {
			$stats->updateCount( 'diff_cache.miss', 1 );
			$cache->set( $key, $difftext, 7 * 86400 );
		} else {
			$stats->updateCount( 'diff_cache.uncacheable', 1 );
		}
		// localise line numbers and title attribute text
		$difftext = $this->localiseDiff( $difftext );

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
		$columnCount = $this->mOldRevisionRecord ? 4 : 2;
		$userLang = $this->getLanguage()->getHtmlCode();
		return Html::rawElement( 'tr', [ 'class' => 'mw-diff-slot-header', 'lang' => $userLang ],
			Html::element( 'th', [ 'colspan' => $columnCount ], $headerText ) );
	}

	/**
	 * Returns the cache key for diff body text or content.
	 *
	 * @deprecated since 1.31, use getDiffBodyCacheKeyParams() instead.
	 *  Hard deprecated in 1.39.
	 * @since 1.23
	 *
	 * @throws MWException
	 * @return string|null
	 */
	protected function getDiffBodyCacheKey() {
		wfDeprecated( __METHOD__, '1.31' );
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
	 * @return string[]
	 * @phan-return non-empty-array<string>
	 * @throws MWException
	 */
	protected function getDiffBodyCacheKeyParams() {
		if ( !$this->mOldid || !$this->mNewid ) {
			throw new MWException( 'mOldid and mNewid must be set to get diff cache key.' );
		}

		$engine = $this->getEngine();
		$params = [
			'diff',
			$engine === 'php' ? false : $engine, // Back compat
			self::DIFF_VERSION,
			"old-{$this->mOldid}",
			"rev-{$this->mNewid}"
		];

		if ( $engine === 'wikidiff2' ) {
			$params[] = phpversion( 'wikidiff2' );
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
	 * @return string[]
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
		$detected = MWDebug::detectDeprecatedOverride(
			$this,
			__CLASS__,
			'getDiffBodyCacheKey',
			'1.31'
		);
		if ( $detected ) {
			$cacheString = $this->getDiffBodyCacheKey();
			if ( $cacheString ) {
				return [ $cacheString ];
			}
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
	 * @param array $options for the difference engine.
	 * Accepts keys 'diff-type' and 'expand-url'
	 */
	public function setSlotDiffOptions( $options ) {
		$this->slotDiffOptions = $options;
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
		$slotDiffRenderer = $this->contentHandlerFactory
			->getContentHandler( CONTENT_MODEL_TEXT )
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
	 * Process DiffEngine config and get a sensible, usable engine
	 *
	 * @return string 'wikidiff2', 'php', or path to an executable
	 * @internal For use by this class and TextSlotDiffRenderer only.
	 */
	public static function getEngine() {
		$diffEngine = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::DiffEngine );
		$externalDiffEngine = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ExternalDiffEngine );

		if ( $diffEngine === null ) {
			$engines = [ 'external', 'wikidiff2', 'php' ];
		} else {
			$engines = [ $diffEngine ];
		}

		$failureReason = null;
		foreach ( $engines as $engine ) {
			switch ( $engine ) {
				case 'external':
					if ( is_string( $externalDiffEngine ) ) {
						if ( is_executable( $externalDiffEngine ) ) {
							return $externalDiffEngine;
						}
						$failureReason = 'ExternalDiffEngine config points to a non-executable';
						if ( $diffEngine === null ) {
							wfDebug( "$failureReason, ignoring" );
						}
					} else {
						$failureReason = 'ExternalDiffEngine config is set to a non-string value';
						if ( $diffEngine === null && $externalDiffEngine ) {
							wfWarn( "$failureReason, ignoring" );
						}
					}
					break;

				case 'wikidiff2':
					if ( function_exists( 'wikidiff2_do_diff' ) ) {
						return 'wikidiff2';
					}
					$failureReason = 'wikidiff2 is not available';
					break;

				case 'php':
					// Always available.
					return 'php';

				default:
					throw new DomainException( 'Invalid value for $wgDiffEngine: ' . $engine );
			}
		}
		throw new UnexpectedValueException( "Cannot use diff engine '$engine': $failureReason" );
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
	 *  Hard deprecated since 1.39.
	 */
	protected function textDiff( $otext, $ntext ) {
		wfDeprecated( __METHOD__, '1.32' );
		$slotDiffRenderer = $this->contentHandlerFactory
			->getContentHandler( CONTENT_MODEL_TEXT )
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
	 * @param string $generator What diff engine was used
	 *
	 * @return string
	 */
	protected function debug( $generator = "internal" ) {
		if ( !$this->enableDebugComment ) {
			return '';
		}
		$data = [ $generator ];
		if ( $this->getConfig()->get( MainConfigNames::ShowHostnames ) ) {
			$data[] = wfHostname();
		}
		$data[] = wfTimestamp( TS_DB );

		return "<!-- diff generator: " .
			implode( " ", array_map( "htmlspecialchars", $data ) ) .
			" -->\n";
	}

	/**
	 * @return string
	 */
	private function getDebugString() {
		$engine = self::getEngine();
		if ( $engine === 'wikidiff2' ) {
			return $this->debug( 'wikidiff2' );
		} elseif ( $engine === 'php' ) {
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
	 * @return string
	 */
	public function localiseLineNumbers( $text ) {
		return preg_replace_callback(
			'/<!--LINE (\d+)-->/',
			function ( array $matches ) {
				if ( $matches[1] === '1' && $this->mReducedLineNumbers ) {
					return '';
				}
				return $this->msg( 'lineno' )->numParams( $matches[1] )->escaped();
			},
			$text
		);
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
			function ( array $matches ) {
				$key = $matches[1] === 'right' ?
					'diff-paragraph-moved-toold' :
					'diff-paragraph-moved-tonew';
				return $matches[0] . ' title="' . $this->msg( $key )->escaped() . '"';
			},
			$text
		);
	}

	/**
	 * If there are revisions between the ones being compared, return a note saying so.
	 *
	 * @return string
	 */
	public function getMultiNotice() {
		// The notice only make sense if we are diffing two saved revisions of the same page.
		if (
			!$this->mOldRevisionRecord || !$this->mNewRevisionRecord
			|| !$this->mOldPage || !$this->mNewPage
			|| !$this->mOldPage->equals( $this->mNewPage )
			|| $this->mOldRevisionRecord->getId() === null
			|| $this->mNewRevisionRecord->getId() === null
			// (T237709) Deleted revs might have different page IDs
			|| $this->mNewPage->getArticleID() !== $this->mOldRevisionRecord->getPageId()
			|| $this->mNewPage->getArticleID() !== $this->mNewRevisionRecord->getPageId()
		) {
			return '';
		}

		if ( $this->mOldRevisionRecord->getTimestamp() > $this->mNewRevisionRecord->getTimestamp() ) {
			$oldRevRecord = $this->mNewRevisionRecord; // flip
			$newRevRecord = $this->mOldRevisionRecord; // flip
		} else { // normal case
			$oldRevRecord = $this->mOldRevisionRecord;
			$newRevRecord = $this->mNewRevisionRecord;
		}

		// Don't show the notice if too many rows must be scanned
		// @todo show some special message for that case
		$nEdits = $this->revisionStore->countRevisionsBetween(
			$this->mNewPage->getArticleID(),
			$oldRevRecord,
			$newRevRecord,
			1000
		);
		if ( $nEdits > 0 && $nEdits <= 1000 ) {
			$limit = 100; // use diff-multi-manyusers if too many users
			try {
				$users = $this->revisionStore->getAuthorsBetween(
					$this->mNewPage->getArticleID(),
					$oldRevRecord,
					$newRevRecord,
					null,
					$limit
				);
				$numUsers = count( $users );

				$newRevUser = $newRevRecord->getUser( RevisionRecord::RAW );
				$newRevUserText = $newRevUser ? $newRevUser->getName() : '';
				if ( $numUsers == 1 && $users[0]->getName() == $newRevUserText ) {
					$numUsers = 0; // special case to say "by the same user" instead of "by one other user"
				}
			} catch ( InvalidArgumentException $e ) {
				$numUsers = 0;
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
	 * @param RevisionRecord $revRecord
	 * @return bool whether the user can see and edit the revision.
	 */
	private function userCanEdit( RevisionRecord $revRecord ) {
		if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get a header for a specified revision.
	 *
	 * @param RevisionRecord $rev
	 * @param string $complete 'complete' to get the header wrapped depending
	 *        the visibility of the revision and a link to edit the page.
	 *
	 * @return string HTML fragment
	 */
	public function getRevisionHeader( RevisionRecord $rev, $complete = '' ) {
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
		);

		if ( $complete !== 'complete' ) {
			return $header->escaped();
		}

		$title = $rev->getPageAsLinkTarget();

		$header = $this->linkRenderer->makeKnownLink( $title, $header->text(), [],
			[ 'oldid' => $rev->getId() ] );

		if ( $this->userCanEdit( $rev ) ) {
			$editQuery = [ 'action' => 'edit' ];
			if ( !$rev->isCurrent() ) {
				$editQuery['oldid'] = $rev->getId();
			}

			$key = $this->getAuthority()->probablyCan( 'edit', $rev->getPage() ) ? 'editold' : 'viewsourceold';
			$msg = $this->msg( $key )->text();
			$editLink = $this->msg( 'parentheses' )->rawParams(
				$this->linkRenderer->makeKnownLink( $title, $msg, [], $editQuery ) )->escaped();
			$header .= ' ' . Html::rawElement(
				'span',
				[ 'class' => 'mw-diff-edit' ],
				$editLink
			);
			if ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				$header = Html::rawElement(
					'span',
					[ 'class' => Linker::getRevisionDeletedClass( $rev ) ],
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
			'class' => [
				'diff',
				'diff-contentalign-' . $this->getDiffLang()->alignStart(),
				'diff-editfont-' . $this->userOptionsLookup->getOption(
					$this->getUser(),
					'editfont'
				)
			],
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
				// FIXME Hardcoding values from TableDiffFormatter.
				$deletedClass = 'diff-side-deleted';
				$addedClass = 'diff-side-added';
				$header .= "
				<tr class=\"diff-title\" lang=\"{$userLang}\">
				<td colspan=\"$colspan\" class=\"diff-otitle {$deletedClass}\">{$otitle}</td>
				<td colspan=\"$colspan\" class=\"diff-ntitle {$addedClass}\">{$ntitle}</td>
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
		?RevisionRecord $oldRevision, RevisionRecord $newRevision
	) {
		if ( $oldRevision ) {
			$this->mOldRevisionRecord = $oldRevision;
			$this->mOldid = $oldRevision->getId();
			$this->mOldPage = Title::newFromLinkTarget( $oldRevision->getPageAsLinkTarget() );
			// This method is meant for edit diffs and such so there is no reason to provide a
			// revision that's not readable to the user, but check it just in case.
			$this->mOldContent = $oldRevision->getContent( SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER, $this->getAuthority() );
		} else {
			$this->mOldPage = null;
			$this->mOldRevisionRecord = $this->mOldid = false;
		}
		$this->mNewRevisionRecord = $newRevision;
		$this->mNewid = $newRevision->getId();
		$this->mNewPage = Title::newFromLinkTarget( $newRevision->getPageAsLinkTarget() );
		$this->mNewContent = $newRevision->getContent( SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER, $this->getAuthority() );

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
	 * @phan-return (int|false)[]
	 */
	public function mapDiffPrevNext( $old, $new ) {
		if ( $new === 'prev' ) {
			// Show diff between revision $old and the previous one. Get previous one from DB.
			$newid = intval( $old );
			$oldid = false;
			$newRev = $this->revisionStore->getRevisionById( $newid );
			if ( $newRev ) {
				$oldRev = $this->revisionStore->getPreviousRevision( $newRev );
				if ( $oldRev ) {
					$oldid = $oldRev->getId();
				}
			}
		} elseif ( $new === 'next' ) {
			// Show diff between revision $old and the next one. Get next one from DB.
			$oldid = intval( $old );
			$newid = false;
			$oldRev = $this->revisionStore->getRevisionById( $oldid );
			if ( $oldRev ) {
				$newRev = $this->revisionStore->getNextRevision( $oldRev );
				if ( $newRev ) {
					$newid = $newRev->getId();
				}
			}
		} else {
			$oldid = intval( $old );
			$newid = intval( $new );
		}

		// @phan-suppress-next-line PhanTypeMismatchReturn getId does not return null here
		return [ $oldid, $newid ];
	}

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

		$this->hookRunner->onNewDifferenceEngine(
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
			$this->getTitle(), $this->mOldid, $this->mNewid, $old, $new );
	}

	/**
	 * Load revision metadata for the specified revisions. If newid is 0, then compare
	 * the old revision in oldid to the current revision of the current page (as defined
	 * by the request context); if oldid is 0, then compare the revision in newid to the
	 * immediately previous one.
	 *
	 * If oldid is false, leave the corresponding RevisionRecord object set to false. This can
	 * happen with 'diff=prev' pointing to a non-existent revision, and is also used directly
	 * by the API.
	 *
	 * @return bool Whether both revisions were loaded successfully. Setting mOldRevisionRecord
	 *   to false counts as successful loading.
	 */
	public function loadRevisionData() {
		if ( $this->mRevisionsLoaded ) {
			return $this->isContentOverridden ||
			( $this->mOldRevisionRecord !== null && $this->mNewRevisionRecord !== null );
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mRevisionsLoaded = true;

		$this->loadRevisionIds();

		// Load the new RevisionRecord object
		if ( $this->mNewid ) {
			$this->mNewRevisionRecord = $this->revisionStore->getRevisionById( $this->mNewid );
		} else {
			$this->mNewRevisionRecord = $this->revisionStore->getRevisionByTitle( $this->getTitle() );
		}

		if ( !$this->mNewRevisionRecord instanceof RevisionRecord ) {
			return false;
		}

		// Update the new revision ID in case it was 0 (makes life easier doing UI stuff)
		$this->mNewid = $this->mNewRevisionRecord->getId();
		$this->mNewPage = $this->mNewid ?
			Title::newFromLinkTarget( $this->mNewRevisionRecord->getPageAsLinkTarget() ) :
			null;

		// Load the old RevisionRecord object
		$this->mOldRevisionRecord = false;
		if ( $this->mOldid ) {
			$this->mOldRevisionRecord = $this->revisionStore->getRevisionById( $this->mOldid );
		} elseif ( $this->mOldid === 0 ) {
			$revRecord = $this->revisionStore->getPreviousRevision( $this->mNewRevisionRecord );
			// No previous revision; mark to show as first-version only.
			$this->mOldid = $revRecord ? $revRecord->getId() : false;
			$this->mOldRevisionRecord = $revRecord ?? false;
		} /* elseif ( $this->mOldid === false ) leave mOldRevisionRecord false; */

		if ( $this->mOldRevisionRecord === null ) {
			return false;
		}

		if ( $this->mOldRevisionRecord && $this->mOldRevisionRecord->getId() ) {
			$this->mOldPage = Title::newFromLinkTarget(
				$this->mOldRevisionRecord->getPageAsLinkTarget()
			);
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
			return $this->loadRevisionData() &&
				( $this->mOldRevisionRecord === false || $this->mOldContent )
				&& $this->mNewContent;
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mTextLoaded = 2;

		if ( !$this->loadRevisionData() ) {
			return false;
		}

		if ( $this->mOldRevisionRecord ) {
			$this->mOldContent = $this->mOldRevisionRecord->getContent(
				SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER,
				$this->getAuthority()
			);
			if ( $this->mOldContent === null ) {
				return false;
			}
		}

		$this->mNewContent = $this->mNewRevisionRecord->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getAuthority()
		);
		$this->hookRunner->onDifferenceEngineLoadTextAfterNewContentIsLoaded( $this );
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

		$this->mNewContent = $this->mNewRevisionRecord->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getAuthority()
		);

		$this->hookRunner->onDifferenceEngineAfterLoadNewText( $this );

		return true;
	}

}
