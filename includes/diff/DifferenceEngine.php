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
use MediaWiki\Shell\Shell;

/**
 * @todo document
 * @ingroup DifferenceEngine
 */
class DifferenceEngine extends ContextSource {
	/**
	 * Constant to indicate diff cache compatibility.
	 * Bump this when changing the diff formatting in a way that
	 * fixes important bugs or such to force cached diff views to
	 * clear.
	 */
	const DIFF_VERSION = '1.12';

	/** @var int */
	public $mOldid;

	/** @var int */
	public $mNewid;

	private $mOldTags;
	private $mNewTags;

	/** @var Content */
	public $mOldContent;

	/** @var Content */
	public $mNewContent;

	/** @var Language */
	protected $mDiffLang;

	/** @var Title */
	public $mOldPage;

	/** @var Title */
	public $mNewPage;

	/** @var Revision */
	public $mOldRev;

	/** @var Revision */
	public $mNewRev;

	/** @var bool Have the revisions IDs been loaded */
	private $mRevisionsIdsLoaded = false;

	/** @var bool Have the revisions been loaded */
	public $mRevisionsLoaded = false;

	/** @var int How many text blobs have been loaded, 0, 1 or 2? */
	public $mTextLoaded = 0;

	/** @var bool Was the diff fetched from cache? */
	public $mCacheHit = false;

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

	/**#@-*/

	/**
	 * @param IContextSource $context Context to use, anything else will be ignored
	 * @param int $old Old ID we want to show and diff with.
	 * @param string|int $new Either revision ID or 'prev' or 'next'. Default: 0.
	 * @param int $rcid Deprecated, no longer used!
	 * @param bool $refreshCache If set, refreshes the diff cache
	 * @param bool $unhide If set, allow viewing deleted revs
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false
	) {
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
	 * @param bool $value
	 */
	public function setReducedLineNumbers( $value = true ) {
		$this->mReducedLineNumbers = $value;
	}

	/**
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
	 * @return int
	 */
	public function getOldid() {
		$this->loadRevisionIds();

		return $this->mOldid;
	}

	/**
	 * @return bool|int
	 */
	public function getNewid() {
		$this->loadRevisionIds();

		return $this->mNewid;
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
		$permErrors = $this->mNewPage->getUserPermissionsErrors( 'read', $user );
		if ( $this->mOldPage ) { # mOldPage might not be set, see below.
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
			$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
			$samePage = true;
			$oldHeader = '';
			// Allow extensions to change the $oldHeader variable
			Hooks::run( 'DifferenceEngineOldHeaderNoOldRev', [ &$oldHeader ] );
		} else {
			Hooks::run( 'DiffViewHeader', [ $this, $this->mOldRev, $this->mNewRev ] );

			if ( $this->mNewPage->equals( $this->mOldPage ) ) {
				$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
				$samePage = true;
			} else {
				$out->setPageTitle( $this->msg( 'difference-title-multipage',
					$this->mOldPage->getPrefixedText(), $this->mNewPage->getPrefixedText() ) );
				$out->addSubtitle( $this->msg( 'difference-multipage' ) );
				$samePage = false;
			}

			if ( $samePage && $this->mNewPage->quickUserCan( 'edit', $user ) ) {
				if ( $this->mNewRev->isCurrent() && $this->mNewPage->userCan( 'rollback', $user ) ) {
					$rollbackLink = Linker::generateRollback( $this->mNewRev, $this->getContext() );
					if ( $rollbackLink ) {
						$out->preventClickjacking();
						$rollback = '&#160;&#160;&#160;' . $rollbackLink;
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
			if ( $samePage && $this->mOldRev->getPrevious() ) {
				$prevlink = Linker::linkKnown(
					$this->mOldPage,
					$this->msg( 'previousdiff' )->escaped(),
					[ 'id' => 'differences-prevlink' ],
					[ 'diff' => 'prev', 'oldid' => $this->mOldid ] + $query
				);
			} else {
				$prevlink = '&#160;';
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
		if ( $samePage && !$this->mNewRev->isCurrent() ) {
			$nextlink = Linker::linkKnown(
				$this->mNewPage,
				$this->msg( 'nextdiff' )->escaped(),
				[ 'id' => 'differences-nextlink' ],
				[ 'diff' => 'next', 'oldid' => $this->mNewid ] + $query
			);
		} else {
			$nextlink = '&#160;';
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
			if ( !$linkInfo ) {
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
		global $wgUseRCPatrol, $wgEnableAPI, $wgEnableWriteAPI;

		$user = $this->getUser();

		// Prepare a change patrol link, if applicable
		if (
			// Is patrolling enabled and the user allowed to?
			$wgUseRCPatrol && $this->mNewPage->quickUserCan( 'patrol', $user ) &&
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
				if ( $wgEnableAPI && $wgEnableWriteAPI
					&& $user->isAllowed( 'writeapi' )
				) {
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
			$link = '&#160;&#160;&#160;' . $link . ' ';
		}

		return $link;
	}

	/**
	 * Show the new revision of the page.
	 */
	public function renderNewRevision() {
		$out = $this->getOutput();
		$revHeader = $this->getRevisionHeader( $this->mNewRev );
		# Add "current version as of X" title
		$out->addHTML( "<hr class='diff-hr' id='mw-oldid' />
		<h2 class='diff-currentversion-title'>{$revHeader}</h2>\n" );
		# Page content may be handled by a hooked call instead...
		if ( Hooks::run( 'ArticleContentOnDiff', [ $this, $out ] ) ) {
			$this->loadNewText();
			$out->setRevisionId( $this->mNewid );
			$out->setRevisionTimestamp( $this->mNewRev->getTimestamp() );
			$out->setArticleFlag( true );

			if ( !Hooks::run( 'ArticleContentViewCustom',
				[ $this->mNewContent, $this->mNewPage, $out ] )
			) {
				// Handled by extension
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
		$this->getOutput()->addModuleStyles( 'mediawiki.diff.styles' );
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
		if ( $this->mOldRev === false || ( $this->mOldRev && $this->mNewRev
			&& $this->mOldRev->getId() == $this->mNewRev->getId() )
		) {
			if ( Hooks::run( 'DifferenceEngineShowEmptyOldContent', [ $this ] ) ) {
				return '';
			}
		}
		// Cacheable?
		$key = false;
		$cache = ObjectCache::getMainWANInstance();
		if ( $this->mOldid && $this->mNewid ) {
			// Check if subclass is still using the old way
			// for backwards-compatibility
			$key = $this->getDiffBodyCacheKey();
			if ( $key === null ) {
				$key = call_user_func_array(
					[ $cache, 'makeKey' ],
					$this->getDiffBodyCacheKeyParams()
				);
			}

			// Try cache
			if ( !$this->mRefreshCache ) {
				$difftext = $cache->get( $key );
				if ( $difftext ) {
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

		$difftext = $this->generateContentDiffBody( $this->mOldContent, $this->mNewContent );

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

		return $params;
	}

	/**
	 * Generate a diff, no caching.
	 *
	 * This implementation uses generateTextDiffBody() to generate a diff based on the default
	 * serialization of the given Content objects. This will fail if $old or $new are not
	 * instances of TextContent.
	 *
	 * Subclasses may override this to provide a different rendering for the diff,
	 * perhaps taking advantage of the content's native form. This is required for all content
	 * models that are not text based.
	 *
	 * @since 1.21
	 *
	 * @param Content $old Old content
	 * @param Content $new New content
	 *
	 * @throws MWException If old or new content is not an instance of TextContent.
	 * @return bool|string
	 */
	public function generateContentDiffBody( Content $old, Content $new ) {
		if ( !( $old instanceof TextContent ) ) {
			throw new MWException( "Diff not implemented for " . get_class( $old ) . "; " .
				"override generateContentDiffBody to fix this." );
		}

		if ( !( $new instanceof TextContent ) ) {
			throw new MWException( "Diff not implemented for " . get_class( $new ) . "; "
				. "override generateContentDiffBody to fix this." );
		}

		$otext = $old->serialize();
		$ntext = $new->serialize();

		return $this->generateTextDiffBody( $otext, $ntext );
	}

	/**
	 * Generate a diff, no caching
	 *
	 * @todo move this to TextDifferenceEngine, make DifferenceEngine abstract. At some point.
	 *
	 * @param string $otext Old text, must be already segmented
	 * @param string $ntext New text, must be already segmented
	 *
	 * @return bool|string
	 */
	public function generateTextDiffBody( $otext, $ntext ) {
		$diff = function () use ( $otext, $ntext ) {
			$time = microtime( true );

			$result = $this->textDiff( $otext, $ntext );

			$time = intval( ( microtime( true ) - $time ) * 1000 );
			MediaWikiServices::getInstance()->getStatsdDataFactory()->timing( 'diff_time', $time );
			// Log requests slower than 99th percentile
			if ( $time > 100 && $this->mOldPage && $this->mNewPage ) {
				wfDebugLog( 'diff',
					"$time ms diff: {$this->mOldid} -> {$this->mNewid} {$this->mNewPage}" );
			}

			return $result;
		};

		/**
		 * @param Status $status
		 * @throws FatalError
		 */
		$error = function ( $status ) {
			throw new FatalError( $status->getWikiText() );
		};

		// Use PoolCounter if the diff looks like it can be expensive
		if ( strlen( $otext ) + strlen( $ntext ) > 20000 ) {
			$work = new PoolCounterWorkViaCallback( 'diff',
				md5( $otext ) . md5( $ntext ),
				[ 'doWork' => $diff, 'error' => $error ]
			);
			return $work->execute();
		}

		return $diff();
	}

	/**
	 * Process $wgExternalDiffEngine and get a sane, usable engine
	 *
	 * @return bool|string 'wikidiff2', path to an executable, or false
	 */
	private function getEngine() {
		global $wgExternalDiffEngine;
		// We use the global here instead of Config because we write to the value,
		// and Config is not mutable.
		if ( $wgExternalDiffEngine == 'wikidiff' || $wgExternalDiffEngine == 'wikidiff3' ) {
			wfDeprecated( "\$wgExternalDiffEngine = '{$wgExternalDiffEngine}'", '1.27' );
			$wgExternalDiffEngine = false;
		} elseif ( $wgExternalDiffEngine == 'wikidiff2' ) {
			// Same as above, but with no deprecation warnings
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
	 * @return bool|string
	 */
	protected function textDiff( $otext, $ntext ) {
		global $wgContLang;

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );

		$engine = $this->getEngine();

		// Better external diff engine, the 2 may some day be dropped
		// This one does the escaping and segmenting itself
		if ( $engine === 'wikidiff2' ) {
			$wikidiff2Version = phpversion( 'wikidiff2' );
			if (
				$wikidiff2Version !== false &&
				version_compare( $wikidiff2Version, '1.5.0', '>=' )
			) {
				$text = wikidiff2_do_diff(
					$otext,
					$ntext,
					2,
					$this->getConfig()->get( 'WikiDiff2MovedParagraphDetectionCutoff' )
				);
			} else {
				// Don't pass the 4th parameter for compatibility with older versions of wikidiff2
				$text = wikidiff2_do_diff(
					$otext,
					$ntext,
					2
				);

				// Log a warning in case the configuration value is set to not silently ignore it
				if ( $this->getConfig()->get( 'WikiDiff2MovedParagraphDetectionCutoff' ) > 0 ) {
					wfLogWarning( '$wgWikiDiff2MovedParagraphDetectionCutoff is set but has no
						effect since the used version of WikiDiff2 does not support it.' );
				}
			}

			$text .= $this->debug( 'wikidiff2' );

			return $text;
		} elseif ( $engine !== false ) {
			# Diff via the shell
			$tmpDir = wfTempDir();
			$tempName1 = tempnam( $tmpDir, 'diff_' );
			$tempName2 = tempnam( $tmpDir, 'diff_' );

			$tempFile1 = fopen( $tempName1, "w" );
			if ( !$tempFile1 ) {
				return false;
			}
			$tempFile2 = fopen( $tempName2, "w" );
			if ( !$tempFile2 ) {
				return false;
			}
			fwrite( $tempFile1, $otext );
			fwrite( $tempFile2, $ntext );
			fclose( $tempFile1 );
			fclose( $tempFile2 );
			$cmd = [ $engine, $tempName1, $tempName2 ];
			$result = Shell::command( $cmd )
				->execute();
			$exitCode = $result->getExitCode();
			if ( $exitCode !== 0 ) {
				throw new Exception( "External diff command returned code {$exitCode}. Stderr: "
					. wfEscapeWikiText( $result->getStderr() )
				);
			}
			$difftext = $result->getStdout();
			$difftext .= $this->debug( "external $engine" );
			unlink( $tempName1 );
			unlink( $tempName2 );

			return $difftext;
		}

		# Native PHP diff
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
		$diffs = new Diff( $ota, $nta );
		$formatter = new TableDiffFormatter();
		$difftext = $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) );
		$difftext .= $this->debug( 'native PHP' );

		return $difftext;
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
		global $wgShowHostnames;
		if ( !$this->enableDebugComment ) {
			return '';
		}
		$data = [ $generator ];
		if ( $wgShowHostnames ) {
			$data[] = wfHostname();
		}
		$data[] = wfTimestamp( TS_DB );

		return "<!-- diff generator: " .
			implode( " ", array_map( "htmlspecialchars", $data ) ) .
			" -->\n";
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
		if ( !is_object( $this->mOldRev ) || !is_object( $this->mNewRev ) ) {
			return '';
		} elseif ( !$this->mOldPage->equals( $this->mNewPage ) ) {
			// Comparing two different pages? Count would be meaningless.
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

		return ''; // nothing
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
	 */
	public function setContent( Content $oldContent, Content $newContent ) {
		$this->mOldContent = $oldContent;
		$this->mNewContent = $newContent;

		$this->mTextLoaded = 2;
		$this->mRevisionsLoaded = true;
	}

	/**
	 * Set the language in which the diff text is written
	 * (Defaults to page content language).
	 * @param Language|string $lang
	 * @since 1.19
	 */
	public function setTextLanguage( $lang ) {
		$this->mDiffLang = wfGetLangObj( $lang );
	}

	/**
	 * Maps a revision pair definition as accepted by DifferenceEngine constructor
	 * to a pair of actual integers representing revision ids.
	 *
	 * @param int $old Revision id, e.g. from URL parameter 'oldid'
	 * @param int|string $new Revision id or strings 'next' or 'prev', e.g. from URL parameter 'diff'
	 *
	 * @return int[] List of two revision ids, older first, later second.
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
	 * Load revision metadata for the specified articles. If newid is 0, then compare
	 * the old article in oldid to the current article; if oldid is 0, then
	 * compare the current article to the immediately previous one (ignoring the
	 * value of newid).
	 *
	 * If oldid is false, leave the corresponding revision object set
	 * to false. This is impossible via ordinary user input, and is provided for
	 * API convenience.
	 *
	 * @return bool
	 */
	public function loadRevisionData() {
		if ( $this->mRevisionsLoaded ) {
			return true;
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
		$this->mNewPage = $this->mNewRev->getTitle();

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

		if ( $this->mOldRev ) {
			$this->mOldPage = $this->mOldRev->getTitle();
		}

		// Load tags information for both revisions
		$dbr = wfGetDB( DB_REPLICA );
		if ( $this->mOldid !== false ) {
			$this->mOldTags = $dbr->selectField(
				'tag_summary',
				'ts_tags',
				[ 'ts_rev_id' => $this->mOldid ],
				__METHOD__
			);
		} else {
			$this->mOldTags = false;
		}
		$this->mNewTags = $dbr->selectField(
			'tag_summary',
			'ts_tags',
			[ 'ts_rev_id' => $this->mNewid ],
			__METHOD__
		);

		return true;
	}

	/**
	 * Load the text of the revisions, as well as revision data.
	 *
	 * @return bool
	 */
	public function loadText() {
		if ( $this->mTextLoaded == 2 ) {
			return true;
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

		if ( $this->mNewRev ) {
			$this->mNewContent = $this->mNewRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );
			Hooks::run( 'DifferenceEngineLoadTextAfterNewContentIsLoaded', [ $this ] );
			if ( $this->mNewContent === null ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Load the text of the new revision, not the old one
	 *
	 * @return bool
	 */
	public function loadNewText() {
		if ( $this->mTextLoaded >= 1 ) {
			return true;
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
