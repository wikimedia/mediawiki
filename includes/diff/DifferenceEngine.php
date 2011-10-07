<?php
/**
 * User interface for the difference engine
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
class DifferenceEngine {
	/**#@+
	 * @private
	 */
	var $mOldid, $mNewid;
	var $mOldtext, $mNewtext;
	protected $mDiffLang;

	/**
	 * @var Title
	 */
	var $mOldPage, $mNewPage, $mTitle;
	var $mRcidMarkPatrolled;

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
	 * @param $titleObj Title object that the diff is associated with
	 * @param $old Integer old ID we want to show and diff with.
	 * @param $new String either 'prev' or 'next'.
	 * @param $rcid Integer ??? FIXME (default 0)
	 * @param $refreshCache boolean If set, refreshes the diff cache
	 * @param $unhide boolean If set, allow viewing deleted revs
	 */
	function __construct( $titleObj = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false )
	{
		if ( $titleObj ) {
			$this->mTitle = $titleObj;
		} else {
			global $wgTitle;
			$this->mTitle = $wgTitle; // @TODO: get rid of this
		}
		wfDebug( "DifferenceEngine old '$old' new '$new' rcid '$rcid'\n" );

		$this->mOldid = $old;
		$this->mNewid = $new;
		$this->mRcidMarkPatrolled = intval( $rcid );  # force it to be an integer
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
			$this->mDiffLang = $this->mTitle->getPageLanguage();
		}
		return $this->mDiffLang;
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->mTitle;
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
		global $wgUser;
		if ( $wgUser->isAllowed( 'deletedhistory' ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow('archive', '*',
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

	function showDiffPage( $diffOnly = false ) {
		global $wgUser, $wgOut, $wgRequest, $wgUseExternalEditor, $wgUseRCPatrol;
		wfProfileIn( __METHOD__ );

		# Allow frames except in certain special cases
		$wgOut->allowClickjacking();
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		if ( !$this->loadRevisionData() ) {
			// Sounds like a deleted revision... Let's see what we can do.
			$t = $this->mTitle->getPrefixedText();
			$d = wfMsgExt( 'missingarticle-diff', array( 'escape' ),
				$this->deletedIdMarker( $this->mOldid ),
				$this->deletedIdMarker( $this->mNewid ) );
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addWikiMsg( 'missing-article', "<nowiki>$t</nowiki>", "<span class='plainlinks'>$d</span>" );
			wfProfileOut( __METHOD__ );
			return;
		}

		# mOldPage might not be set, see below.
		if ( !$this->mNewPage->userCanRead() || ( $this->mOldPage && !$this->mOldPage->userCanRead() ) ) {
			$wgOut->loginToUse();
			$wgOut->output();
			$wgOut->disable();
			wfProfileOut( __METHOD__ );
			return;
		}

		# If external diffs are enabled both globally and for the user,
		# we'll use the application/x-external-editor interface to call
		# an external diff tool like kompare, kdiff3, etc.
		if ( $wgUseExternalEditor && $wgUser->getOption( 'externaldiff' ) ) {
			global $wgCanonicalServer, $wgScript, $wgLang;
			$wgOut->disable();
			header ( "Content-type: application/x-external-editor; charset=UTF-8" );
			# This should be mOldPage, but it may not be set, see below.
			$url1 = $this->mNewPage->getCanonicalURL( array(
				'action' => 'raw',
				'oldid' => $this->mOldid
			) );
			$url2 = $this->mNewPage->getCanonicalURL( array(
				'action' => 'raw',
				'oldid' => $this->mNewid
			) );
			$special = $wgLang->getNsText( NS_SPECIAL );
			$control = <<<CONTROL
			[Process]
			Type=Diff text
			Engine=MediaWiki
			Script={$wgCanonicalServer}{$wgScript}
			Special namespace={$special}

			[File]
			Extension=wiki
			URL=$url1

			[File 2]
			Extension=wiki
			URL=$url2
CONTROL;
			echo( $control );

			wfProfileOut( __METHOD__ );
			return;
		}

		$rollback = '';
		$undoLink = '';

		$query = array();
		# Carry over 'diffonly' param via navigation links
		if ( $diffOnly != $wgUser->getBoolOption( 'diffonly' ) ) {
			$query['diffonly'] = $diffOnly;
		}
		# Cascade unhide param in links for easy deletion browsing
		if ( $this->unhide ) {
			$query['unhide'] = 1;
		}

		# Check if one of the revisions is deleted/suppressed
		$deleted = $suppressed = false;
		$allowed = $this->mNewRev->userCan( Revision::DELETED_TEXT );

		# mOldRev is false if the difference engine is called with a "vague" query for
		# a diff between a version V and its previous version V' AND the version V
		# is the first version of that article. In that case, V' does not exist.
		if ( $this->mOldRev === false ) {
			$wgOut->setPageTitle( $this->mNewPage->getPrefixedText() );
			$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
			$samePage = true;
			$oldHeader = '';
		} else {
			wfRunHooks( 'DiffViewHeader', array( $this, $this->mOldRev, $this->mNewRev ) );

			$sk = $wgUser->getSkin();
			if ( method_exists( $sk, 'suppressQuickbar' ) ) {
				$sk->suppressQuickbar();
			}

			if ( $this->mNewPage->equals( $this->mOldPage ) ) {
				$wgOut->setPageTitle( $this->mNewPage->getPrefixedText() );
				$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
				$samePage = true;
			} else {
				$wgOut->setPageTitle( $this->mOldPage->getPrefixedText() . ', ' . $this->mNewPage->getPrefixedText() );
				$wgOut->setSubtitle( wfMsgExt( 'difference-multipage', array( 'parseinline' ) ) );
				$samePage = false;
			}

			if ( $samePage && $this->mNewPage->userCan( 'edit' ) ) {
				if ( $this->mNewRev->isCurrent() && $this->mNewPage->userCan( 'rollback' ) ) {
					$wgOut->preventClickjacking();
					$rollback = '&#160;&#160;&#160;' . Linker::generateRollback( $this->mNewRev );
				}
				if ( !$this->mOldRev->isDeleted( Revision::DELETED_TEXT ) && !$this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
					$undoLink = ' ' . wfMsgHtml( 'parentheses', Html::element( 'a', array(
						'href' => $this->mNewPage->getLocalUrl( array(
							'action' => 'edit',
							'undoafter' => $this->mOldid,
							'undo' => $this->mNewid ) ),
						'title' => Linker::titleAttrib( 'undo' )
					), wfMsg( 'editundo' ) ) );
				}
			}

			# Make "previous revision link"
			if ( $samePage && $this->mOldRev->getPrevious() ) {
				$prevlink = Linker::linkKnown(
					$this->mOldPage,
					wfMsgHtml( 'previousdiff' ),
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

			$oldHeader = '<div id="mw-diff-otitle1"><strong>' . $oldRevisionHeader . '</strong></div>' .
				'<div id="mw-diff-otitle2">' .
					Linker::revUserTools( $this->mOldRev, !$this->unhide ) . '</div>' .
				'<div id="mw-diff-otitle3">' . $oldminor .
					Linker::revComment( $this->mOldRev, !$diffOnly, !$this->unhide ) . $ldel . '</div>' .
				'<div id="mw-diff-otitle4">' . $prevlink . '</div>';

			if ( $this->mOldRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$deleted = true; // old revisions text is hidden
				if ( $this->mOldRev->isDeleted( Revision::DELETED_RESTRICTED ) ) {
					$suppressed = true; // also suppressed
				}
			}

			# Check if this user can see the revisions
			if ( !$this->mOldRev->userCan( Revision::DELETED_TEXT ) ) {
				$allowed = false;
			}
		}

		# Make "next revision link"
		# Skip next link on the top revision
		if ( $samePage && !$this->mNewRev->isCurrent() ) {
			$nextlink = Linker::linkKnown(
				$this->mNewPage,
				wfMsgHtml( 'nextdiff' ),
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
		$newRevisionHeader = $this->getRevisionHeader( $this->mNewRev, 'complete' ) . $undoLink;

		$newHeader = '<div id="mw-diff-ntitle1"><strong>' . $newRevisionHeader . '</strong></div>' .
			'<div id="mw-diff-ntitle2">' . Linker::revUserTools( $this->mNewRev, !$this->unhide ) .
				" $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor .
				Linker::revComment( $this->mNewRev, !$diffOnly, !$this->unhide ) . $rdel . '</div>' .
			'<div id="mw-diff-ntitle4">' . $nextlink . $this->markPatrolledLink() . '</div>';

		if ( $this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
			$deleted = true; // new revisions text is hidden
			if ( $this->mNewRev->isDeleted( Revision::DELETED_RESTRICTED ) )
				$suppressed = true; // also suppressed
		}

		# If the diff cannot be shown due to a deleted revision, then output
		# the diff header and links to unhide (if available)...
		if ( $deleted && ( !$this->unhide || !$allowed ) ) {
			$this->showDiffStyle();
			$multi = $this->getMultiNotice();
			$wgOut->addHTML( $this->addHeader( '', $oldHeader, $newHeader, $multi ) );
			if ( !$allowed ) {
				$msg = $suppressed ? 'rev-suppressed-no-diff' : 'rev-deleted-no-diff';
				# Give explanation for why revision is not visible
				$wgOut->wrapWikiMsg( "<div id='mw-$msg' class='mw-warning plainlinks'>\n$1\n</div>\n",
					array( $msg ) );
			} else {
				# Give explanation and add a link to view the diff...
				$link = $this->mTitle->getFullUrl( $wgRequest->appendQueryValue( 'unhide', '1', true ) );
				$msg = $suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff';
				$wgOut->wrapWikiMsg( "<div id='mw-$msg' class='mw-warning plainlinks'>\n$1\n</div>\n", array( $msg, $link ) );
			}
		# Otherwise, output a regular diff...
		} else {
			# Add deletion notice if the user is viewing deleted content
			$notice = '';
			if ( $deleted ) {
				$msg = $suppressed ? 'rev-suppressed-diff-view' : 'rev-deleted-diff-view';
				$notice = "<div id='mw-$msg' class='mw-warning plainlinks'>\n" . wfMsgExt( $msg, 'parseinline' ) . "</div>\n";
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
	 * Side effect: this method will call OutputPage::preventClickjacking()
	 * when a link is builded.
	 *
	 * @return String
	 */
	protected function markPatrolledLink() {
		global $wgUseRCPatrol, $wgUser, $wgOut;

		if ( $this->mMarkPatrolledLink === null ) {
			// Prepare a change patrol link, if applicable
			if ( $wgUseRCPatrol && $this->mNewPage->userCan( 'patrol' ) ) {
				// If we've been given an explicit change identifier, use it; saves time
				if ( $this->mRcidMarkPatrolled ) {
					$rcid = $this->mRcidMarkPatrolled;
					$rc = RecentChange::newFromId( $rcid );
					// Already patrolled?
					$rcid = is_object( $rc ) && !$rc->getAttribute( 'rc_patrolled' ) ? $rcid : 0;
				} else {
					// Look for an unpatrolled change corresponding to this diff
					$db = wfGetDB( DB_SLAVE );
					$change = RecentChange::newFromConds(
						array(
						// Redundant user,timestamp condition so we can use the existing index
							'rc_user_text'  => $this->mNewRev->getRawUserText(),
							'rc_timestamp'  => $db->timestamp( $this->mNewRev->getTimestamp() ),
							'rc_this_oldid' => $this->mNewid,
							'rc_last_oldid' => $this->mOldid,
							'rc_patrolled'  => 0
						),
						__METHOD__
					);
					if ( $change instanceof RecentChange ) {
						$rcid = $change->mAttribs['rc_id'];
						$this->mRcidMarkPatrolled = $rcid;
					} else {
						// None found
						$rcid = 0;
					}
				}
				// Build the link
				if ( $rcid ) {
					$wgOut->preventClickjacking();
					$token = $wgUser->editToken( $rcid );
					$this->mMarkPatrolledLink = ' <span class="patrollink">[' . Linker::linkKnown(
						$this->mNewPage,
						wfMsgHtml( 'markaspatrolleddiff' ),
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
		global $wgUser;
		$link = '';
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		// Show del/undel link if:
		// (a) the user can delete revisions, or
		// (b) the user can view deleted revision *and* this one is deleted
		if ( $canHide || ( $rev->getVisibility() && $wgUser->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$link = Linker::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type' 	 => 'revision',
					'target' => $rev->getTitle()->getPrefixedDBkey(),
					'ids' 	 => $rev->getId()
				);
				$link = Linker::revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), $canHide );
			}
			$link = '&#160;&#160;&#160;' . $link . ' ';
		}
		return $link;
	}

	/**
	 * Show the new revision of the page.
	 */
	function renderNewRevision() {
		global $wgOut, $wgUser;
		wfProfileIn( __METHOD__ );
		$revHeader = $this->getRevisionHeader( $this->mNewRev );
		# Add "current version as of X" title
		$wgOut->addHTML( "<hr class='diff-hr' />
		<h2 class='diff-currentversion-title'>{$revHeader}</h2>\n" );
		# Page content may be handled by a hooked call instead...
		if ( wfRunHooks( 'ArticleContentOnDiff', array( $this, $wgOut ) ) ) {
			# Use the current version parser cache if applicable
			$pCache = true;
			if ( !$this->mNewRev->isCurrent() ) {
				$oldEditSectionSetting = $wgOut->parserOptions()->setEditSection( false );
				$pCache = false;
			}

			$this->loadNewText();
			$wgOut->setRevisionId( $this->mNewRev->getId() );
			$wgOut->setArticleFlag( true );

			if ( $this->mNewPage->isCssJsSubpage() || $this->mNewPage->isCssOrJsPage() ) {
				// Stolen from Article::view --AG 2007-10-11
				// Give hooks a chance to customise the output
				// @TODO: standardize this crap into one function
				if ( wfRunHooks( 'ShowRawCssJs', array( $this->mNewtext, $this->mNewPage, $wgOut ) ) ) {
					// Wrap the whole lot in a <pre> and don't parse
					$m = array();
					preg_match( '!\.(css|js)$!u', $this->mNewPage->getText(), $m );
					$wgOut->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
					$wgOut->addHTML( htmlspecialchars( $this->mNewtext ) );
					$wgOut->addHTML( "\n</pre>\n" );
				}
			} elseif ( $pCache ) {
				$article = new Article( $this->mNewPage, 0 );
				$pOutput = ParserCache::singleton()->get( $article, $wgOut->parserOptions() );
				if( $pOutput ) {
					$wgOut->addParserOutput( $pOutput );
				} else {
					$article->doViewParse();
				}
			} else {
				$wgOut->addWikiTextTidy( $this->mNewtext );
			}

			if ( !$this->mNewRev->isCurrent() ) {
				$wgOut->parserOptions()->setEditSection( $oldEditSectionSetting );
			}
		}
		# Add redundant patrol link on bottom...
		$wgOut->addHTML( $this->markPatrolledLink() );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get the diff text, send it to $wgOut
	 * Returns false if the diff could not be generated, otherwise returns true
	 *
	 * @return bool
	 */
	function showDiff( $otitle, $ntitle, $notice = '' ) {
		global $wgOut;
		$diff = $this->getDiff( $otitle, $ntitle, $notice );
		if ( $diff === false ) {
			$wgOut->addWikiMsg( 'missing-article', "<nowiki>(fixme, bug)</nowiki>", '' );
			return false;
		} else {
			$this->showDiffStyle();
			$wgOut->addHTML( $diff );
			return true;
		}
	}

	/**
	 * Add style sheets and supporting JS for diff display.
	 */
	function showDiffStyle() {
		global $wgOut;
		$wgOut->addModuleStyles( 'mediawiki.action.history.diff' );
	}

	/**
	 * Get complete diff table, including header
	 *
	 * @param $otitle Title: old title
	 * @param $ntitle Title: new title
	 * @param $notice String: HTML between diff header and body
	 * @return mixed
	 */
	function getDiff( $otitle, $ntitle, $notice = '' ) {
		$body = $this->getDiffBody();
		if ( $body === false ) {
			return false;
		} else {
			$multi = $this->getMultiNotice();
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
		} elseif ( $this->mOldRev && !$this->mOldRev->userCan( Revision::DELETED_TEXT ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		} elseif ( $this->mNewRev && !$this->mNewRev->userCan( Revision::DELETED_TEXT ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		// Short-circuit
		// If mOldRev is false, it means that the 
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

		$difftext = $this->generateDiffBody( $this->mOldtext, $this->mNewtext );

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
	 * Make sure the proper modules are loaded before we try to
	 * make the diff
	 */
	private function initDiffEngines() {
		global $wgExternalDiffEngine;
		if ( $wgExternalDiffEngine == 'wikidiff' && !function_exists( 'wikidiff_do_diff' ) ) {
			wfProfileIn( __METHOD__ . '-php_wikidiff.so' );
			wfDl( 'php_wikidiff' );
			wfProfileOut( __METHOD__ . '-php_wikidiff.so' );
		}
		elseif ( $wgExternalDiffEngine == 'wikidiff2' && !function_exists( 'wikidiff2_do_diff' ) ) {
			wfProfileIn( __METHOD__ . '-php_wikidiff2.so' );
			wfDl( 'wikidiff2' );
			wfProfileOut( __METHOD__ . '-php_wikidiff2.so' );
		}
	}

	/**
	 * Generate a diff, no caching
	 *
	 * @param $otext String: old text, must be already segmented
	 * @param $ntext String: new text, must be already segmented
	 */
	function generateDiffBody( $otext, $ntext ) {
		global $wgExternalDiffEngine, $wgContLang;

		wfProfileIn( __METHOD__ );

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );

		$this->initDiffEngines();

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
			global $wgTmpDirectory;
			$tempName1 = tempnam( $wgTmpDirectory, 'diff_' );
			$tempName2 = tempnam( $wgTmpDirectory, 'diff_' );

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
	 */
	function localiseLineNumbers( $text ) {
		return preg_replace_callback( '/<!--LINE (\d+)-->/',
		array( &$this, 'localiseLineNumbersCb' ), $text );
	}

	function localiseLineNumbersCb( $matches ) {
		global $wgLang;
		if ( $matches[1] === '1' && $this->mReducedLineNumbers ) return '';
		return wfMsgExt( 'lineno', 'escape', $wgLang->formatNum( $matches[1] ) );
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
		global $wgLang;
		if ( $numUsers > $limit ) {
			$msg = 'diff-multi-manyusers';
			$numUsers = $limit;
		} else {
			$msg = 'diff-multi';
		}
		return wfMsgExt( $msg, 'parseinline',
			$wgLang->formatnum( $numEdits ), $wgLang->formatnum( $numUsers ) );
	}

	/**
	 * Get a header for a specified revision.
	 *
	 * @param $rev Revision
	 * @param $complete String: 'complete' to get the header wrapped depending
	 *        the visibility of the revision and a link to edit the page.
	 * @return String HTML fragment
	 */
	private function getRevisionHeader( Revision $rev, $complete = '' ) {
		global $wgLang;

		$revtimestamp = $rev->getTimestamp();
		$timestamp = $wgLang->timeanddate( $revtimestamp, true );
		$dateofrev = $wgLang->date( $revtimestamp, true );
		$timeofrev = $wgLang->time( $revtimestamp, true );

		$header = htmlspecialchars( wfMsg(
			$rev->isCurrent() ? 'currentrev-asof' : 'revisionasof',
			$timestamp,
			$dateofrev,
			$timeofrev
		) );

		if ( $complete !== 'complete' ) {
			return $header;
		}

		$title = $rev->getTitle();

		$header = Linker::linkKnown( $title, $header, array(),
			array( 'oldid' => $rev->getID() ) );

		if ( $rev->userCan( Revision::DELETED_TEXT ) ) {
			$editQuery = array( 'action' => 'edit' );
			if ( !$rev->isCurrent() ) {
				$editQuery['oldid'] = $rev->getID();
			}

			$msg = wfMsgHtml( $title->userCan( 'edit' ) ? 'editold' : 'viewsourceold' );
			$header .= ' (' . Linker::linkKnown( $title, $msg, array(), $editQuery ) . ')';
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
			<tr valign='top'>
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
			$header .= "
			<tr valign='top'>
			<td colspan='$colspan' class='diff-otitle'>{$otitle}</td>
			<td colspan='$colspan' class='diff-ntitle'>{$ntitle}</td>
			</tr>";
		}

		if ( $multi != '' ) {
			$header .= "<tr><td colspan='{$multiColspan}' align='center' class='diff-multi'>{$multi}</td></tr>";
		}
		if ( $notice != '' ) {
			$header .= "<tr><td colspan='{$multiColspan}' align='center'>{$notice}</td></tr>";
		}

		return $header . $diff . "</table>";
	}

	/**
	 * Use specified text instead of loading from the database
	 */
	function setText( $oldText, $newText ) {
		$this->mOldtext = $oldText;
		$this->mNewtext = $newText;
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
			$this->mOldid = $this->mTitle->getPreviousRevisionID( $this->mNewid );
		} elseif ( $new === 'next' ) {
			# Show diff between revision $old and the next one.
			# Get next one from DB.
			$this->mOldid = intval( $old );
			$this->mNewid = $this->mTitle->getNextRevisionID( $this->mOldid );
			if ( $this->mNewid === false ) {
				# if no result, NewId points to the newest old revision. The only newer
				# revision is cur, which is "0".
				$this->mNewid = 0;
			}
		} else {
			$this->mOldid = intval( $old );
			$this->mNewid = intval( $new );
			wfRunHooks( 'NewDifferenceEngine', array( &$this->mTitle, &$this->mOldid, &$this->mNewid, $old, $new ) );
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
		global $wgUser;

		if ( $this->mRevisionsLoaded ) {
			return true;
		}

		// Whether it succeeds or fails, we don't want to try again
		$this->mRevisionsLoaded = true;

		$this->loadRevisionIds();

		// Load the new revision object
		$this->mNewRev = $this->mNewid
			? Revision::newFromId( $this->mNewid )
			: Revision::newFromTitle( $this->mTitle );

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
		} else {
			// Whether it succeeds or fails, we don't want to try again
			$this->mTextLoaded = 2;
		}

		if ( !$this->loadRevisionData() ) {
			return false;
		}
		if ( $this->mOldRev ) {
			$this->mOldtext = $this->mOldRev->getText( Revision::FOR_THIS_USER );
			if ( $this->mOldtext === false ) {
				return false;
			}
		}
		if ( $this->mNewRev ) {
			$this->mNewtext = $this->mNewRev->getText( Revision::FOR_THIS_USER );
			if ( $this->mNewtext === false ) {
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
		} else {
			$this->mTextLoaded = 1;
		}
		if ( !$this->loadRevisionData() ) {
			return false;
		}
		$this->mNewtext = $this->mNewRev->getText( Revision::FOR_THIS_USER );
		return true;
	}
}
