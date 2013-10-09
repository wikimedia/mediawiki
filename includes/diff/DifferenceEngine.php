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

/**
 * Constant to indicate diff cache compatibility.
 * Bump this when changing the diff formatting in a way that
 * fixes important bugs or such to force cached diff views to
 * clear.
 */
define( 'MW_DIFF_VERSION', '1.11a' );

/**
 * @todo document
 * @ingroup DifferenceEngine
 */
class DifferenceEngine extends ContextSource {
	/**#@+
	 * @private
	 */
	var $mOldid, $mNewid;
	var $mOldTags, $mNewTags;
	/**
	 * @var Content
	 */
	var $mOldContent, $mNewContent;
	protected $mDiffLang;

	/**
	 * @var Title
	 */
	var $mOldPage, $mNewPage;

	/**
	 * @var Revision
	 */
	var $mOldRev, $mNewRev;
	private $mRevisionsIdsLoaded = false; // Have the revisions IDs been loaded
	var $mRevisionsLoaded = false; // Have the revisions been loaded
	var $mTextLoaded = 0; // How many text blobs have been loaded, 0, 1 or 2?
	var $mCacheHit = false; // Was the diff fetched from cache?

	/**
	 * Set this to true to add debug info to the HTML output.
	 * Warning: this may cause RSS readers to spuriously mark articles as "new"
	 * (bug 20601)
	 */
	var $enableDebugComment = false;

	// If true, line X is not displayed when X is 1, for example to increase
	// readability and conserve space with many small diffs.
	protected $mReducedLineNumbers = false;

	// Link to action=markpatrolled
	protected $mMarkPatrolledLink = null;

	protected $unhide = false; # show rev_deleted content if allowed
	/**#@-*/

	/**
	 * Constructor
	 * @param $context IContextSource context to use, anything else will be ignored
	 * @param $old Integer old ID we want to show and diff with.
	 * @param $new String either 'prev' or 'next'.
	 * @param $rcid Integer Deprecated, no longer used!
	 * @param $refreshCache boolean If set, refreshes the diff cache
	 * @param $unhide boolean If set, allow viewing deleted revs
	 */
	function __construct( $context = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false )
	{
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
	 * @param $value bool
	 */
	function setReducedLineNumbers( $value = true ) {
		$this->mReducedLineNumbers = $value;
	}

	/**
	 * @return Language
	 */
	function getDiffLang() {
		if ( $this->mDiffLang === null ) {
			# Default language in which the diff text is written.
			$this->mDiffLang = $this->getTitle()->getPageLanguage();
		}
		return $this->mDiffLang;
	}

	/**
	 * @return bool
	 */
	function wasCacheHit() {
		return $this->mCacheHit;
	}

	/**
	 * @return int
	 */
	function getOldid() {
		$this->loadRevisionIds();
		return $this->mOldid;
	}

	/**
	 * @return Bool|int
	 */
	function getNewid() {
		$this->loadRevisionIds();
		return $this->mNewid;
	}

	/**
	 * Look up a special:Undelete link to the given deleted revision id,
	 * as a workaround for being unable to load deleted diffs in currently.
	 *
	 * @param int $id revision ID
	 * @return mixed URL or false
	 */
	function deletedLink( $id ) {
		if ( $this->getUser()->isAllowed( 'deletedhistory' ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'archive', '*',
				array( 'ar_rev_id' => $id ),
				__METHOD__ );
			if ( $row ) {
				$rev = Revision::newFromArchiveRow( $row );
				$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
				return SpecialPage::getTitleFor( 'Undelete' )->getFullURL( array(
					'target' => $title->getPrefixedText(),
					'timestamp' => $rev->getTimestamp()
				));
			}
		}
		return false;
	}

	/**
	 * Build a wikitext link toward a deleted revision, if viewable.
	 *
	 * @param int $id revision ID
	 * @return string wikitext fragment
	 */
	function deletedIdMarker( $id ) {
		$link = $this->deletedLink( $id );
		if ( $link ) {
			return "[$link $id]";
		} else {
			return $id;
		}
	}

	private function showMissingRevision() {
		$out = $this->getOutput();

		$missing = array();
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
		$out->addWikiMsg( 'difference-missing-revision',
			$this->getLanguage()->listToText( $missing ), count( $missing ) );
	}

	function showDiffPage( $diffOnly = false ) {
		wfProfileIn( __METHOD__ );

		# Allow frames except in certain special cases
		$out = $this->getOutput();
		$out->allowClickjacking();
		$out->setRobotPolicy( 'noindex,nofollow' );

		if ( !$this->loadRevisionData() ) {
			$this->showMissingRevision();
			wfProfileOut( __METHOD__ );
			return;
		}

		$user = $this->getUser();
		$permErrors = $this->mNewPage->getUserPermissionsErrors( 'read', $user );
		if ( $this->mOldPage ) { # mOldPage might not be set, see below.
			$permErrors = wfMergeErrorArrays( $permErrors,
				$this->mOldPage->getUserPermissionsErrors( 'read', $user ) );
		}
		if ( count( $permErrors ) ) {
			wfProfileOut( __METHOD__ );
			throw new PermissionsError( 'read', $permErrors );
		}

		$rollback = '';
		$undoLink = '';

		$query = array();
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

		$revisionTools = array();

		# mOldRev is false if the difference engine is called with a "vague" query for
		# a diff between a version V and its previous version V' AND the version V
		# is the first version of that article. In that case, V' does not exist.
		if ( $this->mOldRev === false ) {
			$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
			$samePage = true;
			$oldHeader = '';
		} else {
			wfRunHooks( 'DiffViewHeader', array( $this, $this->mOldRev, $this->mNewRev ) );

			if ( $this->mNewPage->equals( $this->mOldPage ) ) {
				$out->setPageTitle( $this->msg( 'difference-title', $this->mNewPage->getPrefixedText() ) );
				$samePage = true;
			} else {
				$out->setPageTitle( $this->msg( 'difference-title-multipage', $this->mOldPage->getPrefixedText(),
					$this->mNewPage->getPrefixedText() ) );
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
				if ( !$this->mOldRev->isDeleted( Revision::DELETED_TEXT ) && !$this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
					$undoLink = Html::element( 'a', array(
							'href' => $this->mNewPage->getLocalURL( array(
								'action' => 'edit',
								'undoafter' => $this->mOldid,
								'undo' => $this->mNewid ) ),
							'title' => Linker::titleAttrib( 'undo' )
						),
						$this->msg( 'editundo' )->text()
					);
					$revisionTools[] = $undoLink;
				}
			}

			# Make "previous revision link"
			if ( $samePage && $this->mOldRev->getPrevious() ) {
				$prevlink = Linker::linkKnown(
					$this->mOldPage,
					$this->msg( 'previousdiff' )->escaped(),
					array( 'id' => 'differences-prevlink' ),
					array( 'diff' => 'prev', 'oldid' => $this->mOldid ) + $query
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
			$oldChangeTags = ChangeTags::formatSummaryRow( $this->mOldTags, 'diff' );

			$oldHeader = '<div id="mw-diff-otitle1"><strong>' . $oldRevisionHeader . '</strong></div>' .
				'<div id="mw-diff-otitle2">' .
					Linker::revUserTools( $this->mOldRev, !$this->unhide ) . '</div>' .
				'<div id="mw-diff-otitle3">' . $oldminor .
					Linker::revComment( $this->mOldRev, !$diffOnly, !$this->unhide ) . $ldel . '</div>' .
				'<div id="mw-diff-otitle5">' . $oldChangeTags[0] . '</div>' .
				'<div id="mw-diff-otitle4">' . $prevlink . '</div>';

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

		# Make "next revision link"
		# Skip next link on the top revision
		if ( $samePage && !$this->mNewRev->isCurrent() ) {
			$nextlink = Linker::linkKnown(
				$this->mNewPage,
				$this->msg( 'nextdiff' )->escaped(),
				array( 'id' => 'differences-nextlink' ),
				array( 'diff' => 'next', 'oldid' => $this->mNewid ) + $query
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
		wfRunHooks( 'DiffRevisionTools', array( $this->mNewRev, &$revisionTools ) );
		$formattedRevisionTools = array();
		// Put each one in parentheses (poor man's button)
		foreach ( $revisionTools as $tool ) {
			$formattedRevisionTools[] = $this->msg( 'parentheses' )->rawParams( $tool )->escaped();
		}
		$newRevisionHeader = $this->getRevisionHeader( $this->mNewRev, 'complete' ) . ' ' . implode( ' ', $formattedRevisionTools );
		$newChangeTags = ChangeTags::formatSummaryRow( $this->mNewTags, 'diff' );

		$newHeader = '<div id="mw-diff-ntitle1"><strong>' . $newRevisionHeader . '</strong></div>' .
			'<div id="mw-diff-ntitle2">' . Linker::revUserTools( $this->mNewRev, !$this->unhide ) .
				" $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor .
				Linker::revComment( $this->mNewRev, !$diffOnly, !$this->unhide ) . $rdel . '</div>' .
			'<div id="mw-diff-ntitle5">' . $newChangeTags[0] . '</div>' .
			'<div id="mw-diff-ntitle4">' . $nextlink . $this->markPatrolledLink() . '</div>';

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
					array( $msg ) );
			} else {
				# Give explanation and add a link to view the diff...
				$link = $this->getTitle()->getFullURL( $this->getRequest()->appendQueryValue( 'unhide', '1', true ) );
				$msg = $suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff';
				$out->wrapWikiMsg( "<div id='mw-$msg' class='mw-warning plainlinks'>\n$1\n</div>\n", array( $msg, $link ) );
			}
		# Otherwise, output a regular diff...
		} else {
			# Add deletion notice if the user is viewing deleted content
			$notice = '';
			if ( $deleted ) {
				$msg = $suppressed ? 'rev-suppressed-diff-view' : 'rev-deleted-diff-view';
				$notice = "<div id='mw-$msg' class='mw-warning plainlinks'>\n" . $this->msg( $msg )->parse() . "</div>\n";
			}
			$this->showDiff( $oldHeader, $newHeader, $notice );
			if ( !$diffOnly ) {
				$this->renderNewRevision();
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get a link to mark the change as patrolled, or '' if there's either no
	 * revision to patrol or the user is not allowed to to it.
	 * Side effect: When the patrol link is build, this method will call
	 * OutputPage::preventClickjacking() and load mediawiki.page.patrol.ajax.
	 *
	 * @return String
	 */
	protected function markPatrolledLink() {
		global $wgUseRCPatrol, $wgEnableAPI, $wgEnableWriteAPI;
		$user = $this->getUser();

		if ( $this->mMarkPatrolledLink === null ) {
			// Prepare a change patrol link, if applicable
			if (
				// Is patrolling enabled and the user allowed to?
				$wgUseRCPatrol && $this->mNewPage->quickUserCan( 'patrol', $user ) &&
				// Only do this if the revision isn't more than 6 hours older
				// than the Max RC age (6h because the RC might not be cleaned out regularly)
				RecentChange::isInRCLifespan( $this->mNewRev->getTimestamp(), 21600 )
			) {
				// Look for an unpatrolled change corresponding to this diff

				$db = wfGetDB( DB_SLAVE );
				$change = RecentChange::newFromConds(
					array(
						'rc_timestamp' => $db->timestamp( $this->mNewRev->getTimestamp() ),
						'rc_this_oldid' => $this->mNewid,
						'rc_patrolled' => 0
					),
					__METHOD__,
					array( 'USE INDEX' => 'rc_timestamp' )
				);

				if ( $change && $change->getPerformer()->getName() !== $user->getName() ) {
					$rcid = $change->getAttribute( 'rc_id' );
				} else {
					// None found or the page has been created by the current user.
					// If the user could patrol this it already would be patrolled
					$rcid = 0;
				}
				// Build the link
				if ( $rcid ) {
					$this->getOutput()->preventClickjacking();
					if ( $wgEnableAPI && $wgEnableWriteAPI
						&& $user->isAllowed( 'writeapi' )
					) {
						$this->getOutput()->addModules( 'mediawiki.page.patrol.ajax' );
					}

					$token = $user->getEditToken( $rcid );
					$this->mMarkPatrolledLink = ' <span class="patrollink">[' . Linker::linkKnown(
						$this->mNewPage,
						$this->msg( 'markaspatrolleddiff' )->escaped(),
						array(),
						array(
							'action' => 'markpatrolled',
							'rcid' => $rcid,
							'token' => $token,
						)
					) . ']</span>';
				} else {
					$this->mMarkPatrolledLink = '';
				}
			} else {
				$this->mMarkPatrolledLink = '';
			}
		}

		return $this->mMarkPatrolledLink;
	}

	/**
	 * @param $rev Revision
	 * @return String
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
	function renderNewRevision() {
		wfProfileIn( __METHOD__ );
		$out = $this->getOutput();
		$revHeader = $this->getRevisionHeader( $this->mNewRev );
		# Add "current version as of X" title
		$out->addHTML( "<hr class='diff-hr' />
		<h2 class='diff-currentversion-title'>{$revHeader}</h2>\n" );
		# Page content may be handled by a hooked call instead...
		if ( wfRunHooks( 'ArticleContentOnDiff', array( $this, $out ) ) ) {
			$this->loadNewText();
			$out->setRevisionId( $this->mNewid );
			$out->setRevisionTimestamp( $this->mNewRev->getTimestamp() );
			$out->setArticleFlag( true );

			// NOTE: only needed for B/C: custom rendering of JS/CSS via hook
			if ( $this->mNewPage->isCssJsSubpage() || $this->mNewPage->isCssOrJsPage() ) {
				// Stolen from Article::view --AG 2007-10-11
				// Give hooks a chance to customise the output
				// @todo standardize this crap into one function
				if ( ContentHandler::runLegacyHooks( 'ShowRawCssJs', array( $this->mNewContent, $this->mNewPage, $out ) ) ) {
					// NOTE: deprecated hook, B/C only
					// use the content object's own rendering
					$cnt = $this->mNewRev->getContent();
					$po = $cnt ? $cnt->getParserOutput( $this->mNewRev->getTitle(), $this->mNewRev->getId() ) : null;
					$txt = $po ? $po->getText() : '';
					$out->addHTML( $txt );
				}
			} elseif ( !wfRunHooks( 'ArticleContentViewCustom', array( $this->mNewContent, $this->mNewPage, $out ) ) ) {
				// Handled by extension
			} elseif ( !ContentHandler::runLegacyHooks( 'ArticleViewCustom', array( $this->mNewContent, $this->mNewPage, $out ) ) ) {
				// NOTE: deprecated hook, B/C only
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

				# Also try to load it as a redirect
				$rt = $this->mNewContent ? $this->mNewContent->getRedirectTarget() : null;

				if ( $rt ) {
					$article = Article::newFromTitle( $this->mNewPage, $this->getContext() );
					$out->addHTML( $article->viewRedirect( $rt ) );

					# WikiPage::getParserOutput() should not return false, but just in case
					if ( $parserOutput ) {
						# Show categories etc.
						$out->addParserOutputNoText( $parserOutput );
					}
				} elseif ( $parserOutput ) {
					$out->addParserOutput( $parserOutput );
				}
			}
		}
		# Add redundant patrol link on bottom...
		$out->addHTML( $this->markPatrolledLink() );

		wfProfileOut( __METHOD__ );
	}

	protected function getParserOutput( WikiPage $page, Revision $rev ) {
		$parserOptions = $page->makeParserOptions( $this->getContext() );

		if ( !$rev->isCurrent() || !$rev->getTitle()->quickUserCan( "edit" ) ) {
			$parserOptions->setEditSection( false );
		}

		$parserOutput = $page->getParserOutput( $parserOptions, $rev->getId() );
		return $parserOutput;
	}

	/**
	 * Get the diff text, send it to the OutputPage object
	 * Returns false if the diff could not be generated, otherwise returns true
	 *
	 * @return bool
	 */
	function showDiff( $otitle, $ntitle, $notice = '' ) {
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
	 * Add style sheets and supporting JS for diff display.
	 */
	function showDiffStyle() {
		$this->getOutput()->addModuleStyles( 'mediawiki.action.history.diff' );
	}

	/**
	 * Get complete diff table, including header
	 *
	 * @param string|bool $otitle Header for old text or false
	 * @param string|bool $ntitle Header for new text or false
	 * @param string $notice HTML between diff header and body
	 * @return mixed
	 */
	function getDiff( $otitle, $ntitle, $notice = '' ) {
		$body = $this->getDiffBody();
		if ( $body === false ) {
			return false;
		} else {
			$multi = $this->getMultiNotice();
			// Display a message when the diff is empty
			if ( $body === '' ) {
				$notice .= '<div class="mw-diff-empty">' . $this->msg( 'diff-empty' )->parse() . "</div>\n";
			}
			return $this->addHeader( $body, $otitle, $ntitle, $multi, $notice );
		}
	}

	/**
	 * Get the diff table body, without header
	 *
	 * @return mixed (string/false)
	 */
	public function getDiffBody() {
		global $wgMemc;
		wfProfileIn( __METHOD__ );
		$this->mCacheHit = true;
		// Check if the diff should be hidden from this user
		if ( !$this->loadRevisionData() ) {
			wfProfileOut( __METHOD__ );
			return false;
		} elseif ( $this->mOldRev && !$this->mOldRev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		} elseif ( $this->mNewRev && !$this->mNewRev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		// Short-circuit
		if ( $this->mOldRev === false || ( $this->mOldRev && $this->mNewRev
			&& $this->mOldRev->getID() == $this->mNewRev->getID() ) )
		{
			wfProfileOut( __METHOD__ );
			return '';
		}
		// Cacheable?
		$key = false;
		if ( $this->mOldid && $this->mNewid ) {
			$key = wfMemcKey( 'diff', 'version', MW_DIFF_VERSION,
				'oldid', $this->mOldid, 'newid', $this->mNewid );
			// Try cache
			if ( !$this->mRefreshCache ) {
				$difftext = $wgMemc->get( $key );
				if ( $difftext ) {
					wfIncrStats( 'diff_cache_hit' );
					$difftext = $this->localiseLineNumbers( $difftext );
					$difftext .= "\n<!-- diff cache key $key -->\n";
					wfProfileOut( __METHOD__ );
					return $difftext;
				}
			} // don't try to load but save the result
		}
		$this->mCacheHit = false;

		// Loadtext is permission safe, this just clears out the diff
		if ( !$this->loadText() ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$difftext = $this->generateContentDiffBody( $this->mOldContent, $this->mNewContent );

		// Save to cache for 7 days
		if ( !wfRunHooks( 'AbortDiffCache', array( &$this ) ) ) {
			wfIncrStats( 'diff_uncacheable' );
		} elseif ( $key !== false && $difftext !== false ) {
			wfIncrStats( 'diff_cache_miss' );
			$wgMemc->set( $key, $difftext, 7 * 86400 );
		} else {
			wfIncrStats( 'diff_uncacheable' );
		}
		// Replace line numbers with the text in the user's language
		if ( $difftext !== false ) {
			$difftext = $this->localiseLineNumbers( $difftext );
		}
		wfProfileOut( __METHOD__ );
		return $difftext;
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
	 * @param $old Content: old content
	 * @param $new Content: new content
	 *
	 * @return bool|string
	 * @since 1.21
	 * @throws MWException if $old or $new are not instances of TextContent.
	 */
	function generateContentDiffBody( Content $old, Content $new ) {
		if ( !( $old instanceof TextContent ) ) {
			throw new MWException( "Diff not implemented for " . get_class( $old ) . "; "
					. "override generateContentDiffBody to fix this." );
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
	 * @param string $otext old text, must be already segmented
	 * @param string $ntext new text, must be already segmented
	 * @return bool|string
	 * @deprecated since 1.21, use generateContentDiffBody() instead!
	 */
	function generateDiffBody( $otext, $ntext ) {
		ContentHandler::deprecated( __METHOD__, "1.21" );

		return $this->generateTextDiffBody( $otext, $ntext );
	}

	/**
	 * Generate a diff, no caching
	 *
	 * @todo move this to TextDifferenceEngine, make DifferenceEngine abstract. At some point.
	 *
	 * @param string $otext old text, must be already segmented
	 * @param string $ntext new text, must be already segmented
	 * @return bool|string
	 */
	function generateTextDiffBody( $otext, $ntext ) {
		global $wgExternalDiffEngine, $wgContLang;

		wfProfileIn( __METHOD__ );

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );

		if ( $wgExternalDiffEngine == 'wikidiff' && function_exists( 'wikidiff_do_diff' ) ) {
			# For historical reasons, external diff engine expects
			# input text to be HTML-escaped already
			$otext = htmlspecialchars ( $wgContLang->segmentForDiff( $otext ) );
			$ntext = htmlspecialchars ( $wgContLang->segmentForDiff( $ntext ) );
			wfProfileOut( __METHOD__ );
			return $wgContLang->unsegmentForDiff( wikidiff_do_diff( $otext, $ntext, 2 ) ) .
			$this->debug( 'wikidiff1' );
		}

		if ( $wgExternalDiffEngine == 'wikidiff2' && function_exists( 'wikidiff2_do_diff' ) ) {
			# Better external diff engine, the 2 may some day be dropped
			# This one does the escaping and segmenting itself
			wfProfileIn( 'wikidiff2_do_diff' );
			$text = wikidiff2_do_diff( $otext, $ntext, 2 );
			$text .= $this->debug( 'wikidiff2' );
			wfProfileOut( 'wikidiff2_do_diff' );
			wfProfileOut( __METHOD__ );
			return $text;
		}
		if ( $wgExternalDiffEngine != 'wikidiff3' && $wgExternalDiffEngine !== false ) {
			# Diff via the shell
			$tmpDir = wfTempDir();
			$tempName1 = tempnam( $tmpDir, 'diff_' );
			$tempName2 = tempnam( $tmpDir, 'diff_' );

			$tempFile1 = fopen( $tempName1, "w" );
			if ( !$tempFile1 ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$tempFile2 = fopen( $tempName2, "w" );
			if ( !$tempFile2 ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			fwrite( $tempFile1, $otext );
			fwrite( $tempFile2, $ntext );
			fclose( $tempFile1 );
			fclose( $tempFile2 );
			$cmd = wfEscapeShellArg( $wgExternalDiffEngine, $tempName1, $tempName2 );
			wfProfileIn( __METHOD__ . "-shellexec" );
			$difftext = wfShellExec( $cmd );
			$difftext .= $this->debug( "external $wgExternalDiffEngine" );
			wfProfileOut( __METHOD__ . "-shellexec" );
			unlink( $tempName1 );
			unlink( $tempName2 );
			wfProfileOut( __METHOD__ );
			return $difftext;
		}

		# Native PHP diff
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
		$diffs = new Diff( $ota, $nta );
		$formatter = new TableDiffFormatter();
		$difftext = $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) ) .
		wfProfileOut( __METHOD__ );
		return $difftext;
	}

	/**
	 * Generate a debug comment indicating diff generating time,
	 * server node, and generator backend.
	 * @return string
	 */
	protected function debug( $generator = "internal" ) {
		global $wgShowHostnames;
		if ( !$this->enableDebugComment ) {
			return '';
		}
		$data = array( $generator );
		if ( $wgShowHostnames ) {
			$data[] = wfHostname();
		}
		$data[] = wfTimestamp( TS_DB );
		return "<!-- diff generator: " .
		implode( " ",
		array_map(
					"htmlspecialchars",
		$data ) ) .
			" -->\n";
	}

	/**
	 * Replace line numbers with the text in the user's language
	 * @return mixed
	 */
	function localiseLineNumbers( $text ) {
		return preg_replace_callback( '/<!--LINE (\d+)-->/',
		array( &$this, 'localiseLineNumbersCb' ), $text );
	}

	function localiseLineNumbersCb( $matches ) {
		if ( $matches[1] === '1' && $this->mReducedLineNumbers ) {
			return '';
		}
		return $this->msg( 'lineno' )->numParams( $matches[1] )->escaped();
	}

	/**
	 * If there are revisions between the ones being compared, return a note saying so.
	 * @return string
	 */
	function getMultiNotice() {
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

		$nEdits = $this->mNewPage->countRevisionsBetween( $oldRev, $newRev );
		if ( $nEdits > 0 ) {
			$limit = 100; // use diff-multi-manyusers if too many users
			$numUsers = $this->mNewPage->countAuthorsBetween( $oldRev, $newRev, $limit );
			return self::intermediateEditsMsg( $nEdits, $numUsers, $limit );
		}
		return ''; // nothing
	}

	/**
	 * Get a notice about how many intermediate edits and users there are
	 * @param $numEdits int
	 * @param $numUsers int
	 * @param $limit int
	 * @return string
	 */
	public static function intermediateEditsMsg( $numEdits, $numUsers, $limit ) {
		if ( $numUsers > $limit ) {
			$msg = 'diff-multi-manyusers';
			$numUsers = $limit;
		} else {
			$msg = 'diff-multi';
		}
		return wfMessage( $msg )->numParams( $numEdits, $numUsers )->parse();
	}

	/**
	 * Get a header for a specified revision.
	 *
	 * @param $rev Revision
	 * @param string $complete 'complete' to get the header wrapped depending
	 *        the visibility of the revision and a link to edit the page.
	 * @return String HTML fragment
	 */
	protected function getRevisionHeader( Revision $rev, $complete = '' ) {
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

		$header = Linker::linkKnown( $title, $header, array(),
			array( 'oldid' => $rev->getID() ) );

		if ( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			$editQuery = array( 'action' => 'edit' );
			if ( !$rev->isCurrent() ) {
				$editQuery['oldid'] = $rev->getID();
			}

			$msg = $this->msg( $title->quickUserCan( 'edit', $user ) ? 'editold' : 'viewsourceold' )->escaped();
			$header .= ' ' . $this->msg( 'parentheses' )->rawParams(
				Linker::linkKnown( $title, $msg, array(), $editQuery ) )->plain();
			if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$header = Html::rawElement( 'span', array( 'class' => 'history-deleted' ), $header );
			}
		} else {
			$header = Html::rawElement( 'span', array( 'class' => 'history-deleted' ), $header );
		}

		return $header;
	}

	/**
	 * Add the header to a diff body
	 *
	 * @return string
	 */
	function addHeader( $diff, $otitle, $ntitle, $multi = '', $notice = '' ) {
		// shared.css sets diff in interface language/dir, but the actual content
		// is often in a different language, mostly the page content language/dir
		$tableClass = 'diff diff-contentalign-' . htmlspecialchars( $this->getDiffLang()->alignStart() );
		$header = "<table class='$tableClass'>";

		if ( !$diff && !$otitle ) {
			$header .= "
			<tr style='vertical-align: top;'>
			<td class='diff-ntitle'>{$ntitle}</td>
			</tr>";
			$multiColspan = 1;
		} else {
			if ( $diff ) { // Safari/Chrome show broken output if cols not used
				$header .= "
				<col class='diff-marker' />
				<col class='diff-content' />
				<col class='diff-marker' />
				<col class='diff-content' />";
				$colspan = 2;
				$multiColspan = 4;
			} else {
				$colspan = 1;
				$multiColspan = 2;
			}
			if ( $otitle || $ntitle ) {
				$header .= "
				<tr style='vertical-align: top;'>
				<td colspan='$colspan' class='diff-otitle'>{$otitle}</td>
				<td colspan='$colspan' class='diff-ntitle'>{$ntitle}</td>
				</tr>";
			}
		}

		if ( $multi != '' ) {
			$header .= "<tr><td colspan='{$multiColspan}' style='text-align: center;' class='diff-multi'>{$multi}</td></tr>";
		}
		if ( $notice != '' ) {
			$header .= "<tr><td colspan='{$multiColspan}' style='text-align: center;'>{$notice}</td></tr>";
		}

		return $header . $diff . "</table>";
	}

	/**
	 * Use specified text instead of loading from the database
	 * @deprecated since 1.21, use setContent() instead.
	 */
	function setText( $oldText, $newText ) {
		ContentHandler::deprecated( __METHOD__, "1.21" );

		$oldContent = ContentHandler::makeContent( $oldText, $this->getTitle() );
		$newContent = ContentHandler::makeContent( $newText, $this->getTitle() );

		$this->setContent( $oldContent, $newContent );
	}

	/**
	 * Use specified text instead of loading from the database
	 * @since 1.21
	 */
	function setContent( Content $oldContent, Content $newContent ) {
		$this->mOldContent = $oldContent;
		$this->mNewContent = $newContent;

		$this->mTextLoaded = 2;
		$this->mRevisionsLoaded = true;
	}

	/**
	 * Set the language in which the diff text is written
	 * (Defaults to page content language).
	 * @since 1.19
	 */
	function setTextLanguage( $lang ) {
		$this->mDiffLang = wfGetLangObj( $lang );
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

		if ( $new === 'prev' ) {
			# Show diff between revision $old and the previous one.
			# Get previous one from DB.
			$this->mNewid = intval( $old );
			$this->mOldid = $this->getTitle()->getPreviousRevisionID( $this->mNewid );
		} elseif ( $new === 'next' ) {
			# Show diff between revision $old and the next one.
			# Get next one from DB.
			$this->mOldid = intval( $old );
			$this->mNewid = $this->getTitle()->getNextRevisionID( $this->mOldid );
			if ( $this->mNewid === false ) {
				# if no result, NewId points to the newest old revision. The only newer
				# revision is cur, which is "0".
				$this->mNewid = 0;
			}
		} else {
			$this->mOldid = intval( $old );
			$this->mNewid = intval( $new );
			wfRunHooks( 'NewDifferenceEngine', array( $this->getTitle(), &$this->mOldid, &$this->mNewid, $old, $new ) );
		}
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
	function loadRevisionData() {
		if ( $this->mRevisionsLoaded ) {
			return true;
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mRevisionsLoaded = true;

		$this->loadRevisionIds();

		// Load the new revision object
		$this->mNewRev = $this->mNewid
			? Revision::newFromId( $this->mNewid )
			: Revision::newFromTitle( $this->getTitle(), false, Revision::READ_NORMAL );

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
		$dbr = wfGetDB( DB_SLAVE );
		if ( $this->mOldid !== false ) {
			$this->mOldTags = $dbr->selectField(
				'tag_summary',
				'ts_tags',
				array( 'ts_rev_id' => $this->mOldid ),
				__METHOD__
			);
		} else {
			$this->mOldTags = false;
		}
		$this->mNewTags = $dbr->selectField(
			'tag_summary',
			'ts_tags',
			array( 'ts_rev_id' => $this->mNewid ),
			__METHOD__
		);

		return true;
	}

	/**
	 * Load the text of the revisions, as well as revision data.
	 *
	 * @return bool
	 */
	function loadText() {
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
	function loadNewText() {
		if ( $this->mTextLoaded >= 1 ) {
			return true;
		}

		$this->mTextLoaded = 1;

		if ( !$this->loadRevisionData() ) {
			return false;
		}

		$this->mNewContent = $this->mNewRev->getContent( Revision::FOR_THIS_USER, $this->getUser() );

		return true;
	}
}
