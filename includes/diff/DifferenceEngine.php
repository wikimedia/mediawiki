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
	var $mOldid, $mNewid, $mTitle;
	var $mOldtitle, $mNewtitle, $mPagetitle;
	var $mOldtext, $mNewtext;
	var $mOldPage, $mNewPage;
	var $mRcidMarkPatrolled;
	var $mOldRev, $mNewRev;
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

	protected $unhide = false; # show rev_deleted content if allowed
	/**#@-*/

	/**
	 * Constructor
	 * @param $titleObj Title object that the diff is associated with
	 * @param $old Integer: old ID we want to show and diff with.
	 * @param $new String: either 'prev' or 'next'.
	 * @param $rcid Integer: ??? FIXME (default 0)
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
			$this->mTitle = $wgTitle;
		}
		wfDebug( "DifferenceEngine old '$old' new '$new' rcid '$rcid'\n" );

		if ( 'prev' === $new ) {
			# Show diff between revision $old and the previous one.
			# Get previous one from DB.
			$this->mNewid = intval( $old );
			$this->mOldid = $this->mTitle->getPreviousRevisionID( $this->mNewid );
		} elseif ( 'next' === $new ) {
			# Show diff between revision $old and the next one.
			# Get next one from DB.
			$this->mOldid = intval( $old );
			$this->mNewid = $this->mTitle->getNextRevisionID( $this->mOldid );
			if ( false === $this->mNewid ) {
				# if no result, NewId points to the newest old revision. The only newer
				# revision is cur, which is "0".
				$this->mNewid = 0;
			}
		} else {
			$this->mOldid = intval( $old );
			$this->mNewid = intval( $new );
			wfRunHooks( 'NewDifferenceEngine', array( &$titleObj, &$this->mOldid, &$this->mNewid, $old, $new ) );
		}
		$this->mRcidMarkPatrolled = intval( $rcid );  # force it to be an integer
		$this->mRefreshCache = $refreshCache;
		$this->unhide = $unhide;
	}

	function setReducedLineNumbers( $value = true ) {
		$this->mReducedLineNumbers = $value;
	}

	function getTitle() {
		return $this->mTitle;
	}

	function wasCacheHit() {
		return $this->mCacheHit;
	}

	function getOldid() {
		return $this->mOldid;
	}

	function getNewid() {
		return $this->mNewid;
	}

	function showDiffPage( $diffOnly = false ) {
		global $wgUser, $wgOut, $wgUseExternalEditor, $wgUseRCPatrol;
		wfProfileIn( __METHOD__ );


		# If external diffs are enabled both globally and for the user,
		# we'll use the application/x-external-editor interface to call
		# an external diff tool like kompare, kdiff3, etc.
		if ( $wgUseExternalEditor && $wgUser->getOption( 'externaldiff' ) ) {
			global $wgInputEncoding, $wgServer, $wgScript, $wgLang;
			$wgOut->disable();
			header ( "Content-type: application/x-external-editor; charset=" . $wgInputEncoding );
			$url1 = $this->mTitle->getFullURL( array(
				'action' => 'raw',
				'oldid' => $this->mOldid
			) );
			$url2 = $this->mTitle->getFullURL( array(
				'action' => 'raw',
				'oldid' => $this->mNewid
			) );
			$special = $wgLang->getNsText( NS_SPECIAL );
			$control = <<<CONTROL
			[Process]
			Type=Diff text
			Engine=MediaWiki
			Script={$wgServer}{$wgScript}
			Special namespace={$special}

			[File]
			Extension=wiki
			URL=$url1

			[File 2]
			Extension=wiki
			URL=$url2
CONTROL;
			echo( $control );
			return;
		}

		$wgOut->setArticleFlag( false );
		if ( !$this->loadRevisionData() ) {
			$t = $this->mTitle->getPrefixedText();
			$d = wfMsgExt( 'missingarticle-diff', array( 'escape' ), $this->mOldid, $this->mNewid );
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addWikiMsg( 'missing-article', "<nowiki>$t</nowiki>", $d );
			wfProfileOut( __METHOD__ );
			return;
		}

		wfRunHooks( 'DiffViewHeader', array( $this, $this->mOldRev, $this->mNewRev ) );

		if ( $this->mNewRev->isCurrent() ) {
			$wgOut->setArticleFlag( true );
		}

		# mOldid is false if the difference engine is called with a "vague" query for
		# a diff between a version V and its previous version V' AND the version V
		# is the first version of that article. In that case, V' does not exist.
		if ( $this->mOldid === false ) {
			$this->showFirstRevision();
			$this->renderNewRevision();  // should we respect $diffOnly here or not?
			wfProfileOut( __METHOD__ );
			return;
		}

		$wgOut->suppressQuickbar();

		$oldTitle = $this->mOldPage->getPrefixedText();
		$newTitle = $this->mNewPage->getPrefixedText();
		if ( $oldTitle == $newTitle ) {
			$wgOut->setPageTitle( $newTitle );
		} else {
			$wgOut->setPageTitle( $oldTitle . ', ' . $newTitle );
		}
		if ( $this->mNewPage->equals( $this->mOldPage ) ) {
			$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
		} else {
			$wgOut->setSubtitle( wfMsgExt( 'difference-multipage', array( 'parseinline' ) ) );
		}
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		if ( !$this->mOldPage->userCanRead() || !$this->mNewPage->userCanRead() ) {
			$wgOut->loginToUse();
			$wgOut->output();
			$wgOut->disable();
			wfProfileOut( __METHOD__ );
			return;
		}

		$sk = $wgUser->getSkin();

		// Check if page is editable
		$editable = $this->mNewRev->getTitle()->userCan( 'edit' );
		if ( $editable && $this->mNewRev->isCurrent() && $wgUser->isAllowed( 'rollback' ) ) {
			$rollback = '&#160;&#160;&#160;' . $sk->generateRollback( $this->mNewRev );
		} else {
			$rollback = '';
		}

		// Prepare a change patrol link, if applicable
		if ( $wgUseRCPatrol && $this->mTitle->userCan( 'patrol' ) ) {
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
				$token = $wgUser->editToken( $rcid );
				$patrol = ' <span class="patrollink">[' . $sk->link(
					$this->mTitle,
					wfMsgHtml( 'markaspatrolleddiff' ),
					array(),
					array(
						'action' => 'markpatrolled',
						'rcid' => $rcid,
						'token' => $token,
					),
					array(
						'known',
						'noclasses'
					)
				) . ']</span>';
			} else {
				$patrol = '';
			}
		} else {
			$patrol = '';
		}

		# Carry over 'diffonly' param via navigation links
		if ( $diffOnly != $wgUser->getBoolOption( 'diffonly' ) ) {
			$query['diffonly'] = $diffOnly;
		}

		# Make "previous revision link"
		$query['diff'] = 'prev';
		$query['oldid'] = $this->mOldid;
		# Cascade unhide param in links for easy deletion browsing
		if ( $this->unhide ) {
			$query['unhide'] = 1;
		}
		if ( !$this->mOldRev->getPrevious() ) {
			$prevlink = '&#160;';
		} else {
			$prevlink = $sk->link(
				$this->mTitle,
				wfMsgHtml( 'previousdiff' ),
				array(
					'id' => 'differences-prevlink'
				),
				$query,
				array(
					'known',
					'noclasses'
				)
			);
		}

		# Make "next revision link"
		$query['diff'] = 'next';
		$query['oldid'] = $this->mNewid;
		# Skip next link on the top revision
		if ( $this->mNewRev->isCurrent() ) {
			$nextlink = '&#160;';
		} else {
			$nextlink = $sk->link(
				$this->mTitle,
				wfMsgHtml( 'nextdiff' ),
				array(
					'id' => 'differences-nextlink'
				),
				$query,
				array(
					'known',
					'noclasses'
				)
			);
		}

		$oldminor = '';
		$newminor = '';

		if ( $this->mOldRev->isMinor() ) {
			$oldminor = ChangesList::flag( 'minor' );
		}
		if ( $this->mNewRev->isMinor() ) {
			$newminor = ChangesList::flag( 'minor' );
		}

		# Handle RevisionDelete links...
		$ldel = $this->revisionDeleteLink( $this->mOldRev );
		$rdel = $this->revisionDeleteLink( $this->mNewRev );

		$oldHeader = '<div id="mw-diff-otitle1"><strong>' . $this->mOldtitle . '</strong></div>' .
			'<div id="mw-diff-otitle2">' .
				$sk->revUserTools( $this->mOldRev, !$this->unhide ) . '</div>' .
			'<div id="mw-diff-otitle3">' . $oldminor .
				$sk->revComment( $this->mOldRev, !$diffOnly, !$this->unhide ) . $ldel . '</div>' .
			'<div id="mw-diff-otitle4">' . $prevlink . '</div>';
		$newHeader = '<div id="mw-diff-ntitle1"><strong>' . $this->mNewtitle . '</strong></div>' .
			'<div id="mw-diff-ntitle2">' . $sk->revUserTools( $this->mNewRev, !$this->unhide ) .
				" $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor .
				$sk->revComment( $this->mNewRev, !$diffOnly, !$this->unhide ) . $rdel . '</div>' .
			'<div id="mw-diff-ntitle4">' . $nextlink . $patrol . '</div>';

		# Check if this user can see the revisions
		$allowed = $this->mOldRev->userCan( Revision::DELETED_TEXT )
			&& $this->mNewRev->userCan( Revision::DELETED_TEXT );
		# Check if one of the revisions is deleted/suppressed
		$deleted = $suppressed = false;
		if ( $this->mOldRev->isDeleted( Revision::DELETED_TEXT ) ) {
			$deleted = true; // old revisions text is hidden
			if ( $this->mOldRev->isDeleted( Revision::DELETED_RESTRICTED ) )
				$suppressed = true; // also suppressed
		}
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
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
					array( $msg ) );
			} else {
				# Give explanation and add a link to view the diff...
				$link = $this->mTitle->getFullUrl( array(
					'diff' => $this->mNewid,
					'oldid' => $this->mOldid,
					'unhide' => 1
				) );
				$msg = $suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff';
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", array( $msg, $link ) );
			}
		# Otherwise, output a regular diff...
		} else {
			# Add deletion notice if the user is viewing deleted content
			$notice = '';
			if ( $deleted ) {
				$msg = $suppressed ? 'rev-suppressed-diff-view' : 'rev-deleted-diff-view';
				$notice = "<div class='mw-warning plainlinks'>\n" . wfMsgExt( $msg, 'parseinline' ) . "</div>\n";
			}
			$this->showDiff( $oldHeader, $newHeader, $notice );
			if ( !$diffOnly ) {
				$this->renderNewRevision();
			}
		}
		wfProfileOut( __METHOD__ );
	}

	protected function revisionDeleteLink( $rev ) {
		global $wgUser;
		$link = '';
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		// Show del/undel link if:
		// (a) the user can delete revisions, or
		// (b) the user can view deleted revision *and* this one is deleted
		if ( $canHide || ( $rev->getVisibility() && $wgUser->isAllowed( 'deletedhistory' ) ) ) {
			$sk = $wgUser->getSkin();
			if ( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$link = $sk->revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type' 	 => 'revision',
					'target' => $rev->mTitle->getPrefixedDbkey(),
					'ids' 	 => $rev->getId()
				);
				$link = $sk->revDeleteLink( $query,
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

		$wgOut->addHTML( "<hr /><h2>{$this->mPagetitle}</h2>\n" );
		if ( !wfRunHooks( 'ArticleContentOnDiff', array( $this, $wgOut ) ) ) {
			return;
		}
		
		# Add deleted rev tag if needed
		if ( !$this->mNewRev->userCan( Revision::DELETED_TEXT ) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-permission' );
		} else if ( $this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-view' );
		}

		$pCache = true;
		if ( !$this->mNewRev->isCurrent() ) {
			$oldEditSectionSetting = $wgOut->parserOptions()->setEditSection( false );
			$pCache = false;
		}

		$this->loadNewText();
		if ( is_object( $this->mNewRev ) ) {
			$wgOut->setRevisionId( $this->mNewRev->getId() );
		}

		if ( $this->mTitle->isCssJsSubpage() || $this->mTitle->isCssOrJsPage() ) {
			// Stolen from Article::view --AG 2007-10-11
			// Give hooks a chance to customise the output
			if ( wfRunHooks( 'ShowRawCssJs', array( $this->mNewtext, $this->mTitle, $wgOut ) ) ) {
				// Wrap the whole lot in a <pre> and don't parse
				$m = array();
				preg_match( '!\.(css|js)$!u', $this->mTitle->getText(), $m );
				$wgOut->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
				$wgOut->addHTML( htmlspecialchars( $this->mNewtext ) );
				$wgOut->addHTML( "\n</pre>\n" );
			}
		} elseif ( $pCache ) {
			$article = new Article( $this->mTitle, 0 );
			$pOutput = ParserCache::singleton()->get( $article, $wgOut->parserOptions() );
			if( $pOutput ) {
				$wgOut->addParserOutput( $pOutput );
			} else {
				$article->doViewParse();
			} 
		} else {
			$wgOut->addWikiTextTidy( $this->mNewtext );
		}	

		if ( is_object( $this->mNewRev ) && !$this->mNewRev->isCurrent() ) {
			$wgOut->parserOptions()->setEditSection( $oldEditSectionSetting );
		}

		# Add redundant patrol link on bottom...
		if ( $this->mRcidMarkPatrolled && $this->mTitle->quickUserCan( 'patrol' ) ) {
			$sk = $wgUser->getSkin();
			$token = $wgUser->editToken( $this->mRcidMarkPatrolled );
			$wgOut->addHTML(
				"<div class='patrollink'>[" . $sk->link(
					$this->mTitle,
					wfMsgHtml( 'markaspatrolleddiff' ),
					array(),
					array(
						'action' => 'markpatrolled',
						'rcid' => $this->mRcidMarkPatrolled,
						'token' => $token,
					)
				) . ']</div>'
			 );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Show the first revision of an article. Uses normal diff headers in
	 * contrast to normal "old revision" display style.
	 */
	function showFirstRevision() {
		global $wgOut, $wgUser;
		wfProfileIn( __METHOD__ );

		# Get article text from the DB
		#
		if ( ! $this->loadNewText() ) {
			$t = $this->mTitle->getPrefixedText();
			$d = wfMsgExt( 'missingarticle-diff', array( 'escape' ), $this->mOldid, $this->mNewid );
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addWikiMsg( 'missing-article', "<nowiki>$t</nowiki>", $d );
			wfProfileOut( __METHOD__ );
			return;
		}
		if ( $this->mNewRev->isCurrent() ) {
			$wgOut->setArticleFlag( true );
		}

		# Check if user is allowed to look at this page. If not, bail out.
		#
		if ( !$this->mTitle->userCanRead() ) {
			$wgOut->loginToUse();
			$wgOut->output();
			wfProfileOut( __METHOD__ );
			throw new MWException( "Permission Error: you do not have access to view this page" );
		}

		# Prepare the header box
		#
		$sk = $wgUser->getSkin();

		$next = $this->mTitle->getNextRevisionID( $this->mNewid );
		if ( !$next ) {
			$nextlink = '';
		} else {
			$nextlink = '<br />' . $sk->link(
				$this->mTitle,
				wfMsgHtml( 'nextdiff' ),
				array(
					'id' => 'differences-nextlink'
				),
				array(
					'diff' => 'next',
					'oldid' => $this->mNewid,
				),
				array(
					'known',
					'noclasses'
				)
			);
		}
		$header = "<div class=\"firstrevisionheader\" style=\"text-align: center\">" .
			$sk->revUserTools( $this->mNewRev ) . "<br />" . $sk->revComment( $this->mNewRev ) . $nextlink . "</div>\n";

		$wgOut->addHTML( $header );

		$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get the diff text, send it to $wgOut
	 * Returns false if the diff could not be generated, otherwise returns true
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
		$wgOut->addModules( 'mediawiki.legacy.diff' );
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
			return false;
		} elseif ( $this->mOldRev && !$this->mOldRev->userCan( Revision::DELETED_TEXT ) ) {
			return false;
		} elseif ( $this->mNewRev && !$this->mNewRev->userCan( Revision::DELETED_TEXT ) ) {
			return false;
		}
		// Short-circuit
		if ( $this->mOldRev && $this->mNewRev
			&& $this->mOldRev->getID() == $this->mNewRev->getID() )
		{
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
			wfSuppressWarnings();
			dl( 'php_wikidiff.so' );
			wfRestoreWarnings();
			wfProfileOut( __METHOD__ . '-php_wikidiff.so' );
		}
		else if ( $wgExternalDiffEngine == 'wikidiff2' && !function_exists( 'wikidiff2_do_diff' ) ) {
			wfProfileIn( __METHOD__ . '-php_wikidiff2.so' );
			wfSuppressWarnings();
			wfDl( 'wikidiff2' );
			wfRestoreWarnings();
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

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );

		$this->initDiffEngines();

		if ( $wgExternalDiffEngine == 'wikidiff' && function_exists( 'wikidiff_do_diff' ) ) {
			# For historical reasons, external diff engine expects
			# input text to be HTML-escaped already
			$otext = htmlspecialchars ( $wgContLang->segmentForDiff( $otext ) );
			$ntext = htmlspecialchars ( $wgContLang->segmentForDiff( $ntext ) );
			return $wgContLang->unsegementForDiff( wikidiff_do_diff( $otext, $ntext, 2 ) ) .
			$this->debug( 'wikidiff1' );
		}

		if ( $wgExternalDiffEngine == 'wikidiff2' && function_exists( 'wikidiff2_do_diff' ) ) {
			# Better external diff engine, the 2 may some day be dropped
			# This one does the escaping and segmenting itself
			wfProfileIn( 'wikidiff2_do_diff' );
			$text = wikidiff2_do_diff( $otext, $ntext, 2 );
			$text .= $this->debug( 'wikidiff2' );
			wfProfileOut( 'wikidiff2_do_diff' );
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
			return $difftext;
		}

		# Native PHP diff
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
		$diffs = new Diff( $ota, $nta );
		$formatter = new TableDiffFormatter();
		return $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) ) .
		$this->debug();
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

		$oldid = $this->mOldRev->getId();
		$newid = $this->mNewRev->getId();
		if ( $oldid > $newid ) {
			$tmp = $oldid; $oldid = $newid; $newid = $tmp;
		}

		$nEdits = $this->mTitle->countRevisionsBetween( $oldid, $newid );
		if ( $nEdits > 0 ) {
			$limit = 100;
			// We use ($limit + 1) so we can detect if there are > 100 authors
			// in a given revision range. In that case, diff-multi-manyusers is used.
			$numUsers = $this->mTitle->countAuthorsBetween( $oldid, $newid, $limit + 1 );
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
	 * Add the header to a diff body
	 */
	static function addHeader( $diff, $otitle, $ntitle, $multi = '', $notice = '' ) {
		$header = "<table class='diff'>";
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
	 * Load revision metadata for the specified articles. If newid is 0, then compare
	 * the old article in oldid to the current article; if oldid is 0, then
	 * compare the current article to the immediately previous one (ignoring the
	 * value of newid).
	 *
	 * If oldid is false, leave the corresponding revision object set
	 * to false. This is impossible via ordinary user input, and is provided for
	 * API convenience.
	 */
	function loadRevisionData() {
		global $wgLang, $wgUser;
		if ( $this->mRevisionsLoaded ) {
			return true;
		} else {
			// Whether it succeeds or fails, we don't want to try again
			$this->mRevisionsLoaded = true;
		}

		// Load the new revision object
		$this->mNewRev = $this->mNewid
			? Revision::newFromId( $this->mNewid )
			: Revision::newFromTitle( $this->mTitle );
		if ( !$this->mNewRev instanceof Revision )
			return false;

		// Update the new revision ID in case it was 0 (makes life easier doing UI stuff)
		$this->mNewid = $this->mNewRev->getId();

		// Check if page is editable
		$editable = $this->mNewRev->getTitle()->userCan( 'edit' );

		// Set assorted variables
		$timestamp = $wgLang->timeanddate( $this->mNewRev->getTimestamp(), true );
		$dateofrev = $wgLang->date( $this->mNewRev->getTimestamp(), true );
		$timeofrev = $wgLang->time( $this->mNewRev->getTimestamp(), true );
		$this->mNewPage = $this->mNewRev->getTitle();
		if ( $this->mNewRev->isCurrent() ) {
			$newLink = $this->mNewPage->escapeLocalUrl( array(
				'oldid' => $this->mNewid
			) );
			$this->mPagetitle = htmlspecialchars( wfMsg(
				'currentrev-asof',
				$timestamp,
				$dateofrev,
				$timeofrev
			) );
			$newEdit = $this->mNewPage->escapeLocalUrl( array(
				'action' => 'edit'
			) );

			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a>";
			$this->mNewtitle .= " (<a href='$newEdit'>" . wfMsgHtml( $editable ? 'editold' : 'viewsourceold' ) . "</a>)";
		} else {
			$newLink = $this->mNewPage->escapeLocalUrl( array(
				'oldid' => $this->mNewid
			) );
			$newEdit = $this->mNewPage->escapeLocalUrl( array(
				'action' => 'edit',
				'oldid' => $this->mNewid
			) );
			$this->mPagetitle = htmlspecialchars( wfMsg(
				'revisionasof',
				$timestamp,
				$dateofrev,
				$timeofrev
			) );

			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a>";
			$this->mNewtitle .= " (<a href='$newEdit'>" . wfMsgHtml( $editable ? 'editold' : 'viewsourceold' ) . "</a>)";
		}
		if ( !$this->mNewRev->userCan( Revision::DELETED_TEXT ) ) {
			$this->mNewtitle = "<span class='history-deleted'>{$this->mPagetitle}</span>";
		} else if ( $this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
			$this->mNewtitle = "<span class='history-deleted'>{$this->mNewtitle}</span>";
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

		if ( $this->mOldRev ) {
			$this->mOldPage = $this->mOldRev->getTitle();

			$t = $wgLang->timeanddate( $this->mOldRev->getTimestamp(), true );
			$dateofrev = $wgLang->date( $this->mOldRev->getTimestamp(), true );
			$timeofrev = $wgLang->time( $this->mOldRev->getTimestamp(), true );
			$oldLink = $this->mOldPage->escapeLocalUrl( array(
				'oldid' => $this->mOldid
			) );
			$oldEdit = $this->mOldPage->escapeLocalUrl( array(
				'action' => 'edit',
				'oldid' => $this->mOldid
			) );
			$this->mOldPagetitle = htmlspecialchars( wfMsg( 'revisionasof', $t, $dateofrev, $timeofrev ) );

			$this->mOldtitle = "<a href='$oldLink'>{$this->mOldPagetitle}</a>"
			. " (<a href='$oldEdit'>" . wfMsgHtml( $editable ? 'editold' : 'viewsourceold' ) . "</a>)";
			// Add an "undo" link
			$newUndo = $this->mNewPage->escapeLocalUrl( array(
				'action' => 'edit',
				'undoafter' => $this->mOldid,
				'undo' => $this->mNewid
			) );
			$htmlLink = htmlspecialchars( wfMsg( 'editundo' ) );
			$htmlTitle = $wgUser->getSkin()->titleAttrib( 'undo' );
			if ( $editable && !$this->mOldRev->isDeleted( Revision::DELETED_TEXT ) && !$this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->mNewtitle .= " (<a href='$newUndo' $htmlTitle>" . $htmlLink . "</a>)";
			}

			if ( !$this->mOldRev->userCan( Revision::DELETED_TEXT ) ) {
				$this->mOldtitle = '<span class="history-deleted">' . $this->mOldPagetitle . '</span>';
			} else if ( $this->mOldRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->mOldtitle = '<span class="history-deleted">' . $this->mOldtitle . '</span>';
			}
		}

		return true;
	}

	/**
	 * Load the text of the revisions, as well as revision data.
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
