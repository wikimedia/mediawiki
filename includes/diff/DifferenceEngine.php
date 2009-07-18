<?php
/**
 * @defgroup DifferenceEngine DifferenceEngine
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
	var $htmldiff;

	protected $unhide = false;
	/**#@-*/

	/**
	 * Constructor
	 * @param $titleObj Title object that the diff is associated with
	 * @param $old Integer: old ID we want to show and diff with.
	 * @param $new String: either 'prev' or 'next'.
	 * @param $rcid Integer: ??? FIXME (default 0)
	 * @param $refreshCache boolean If set, refreshes the diff cache
	 * @param $htmldiff boolean If set, output using HTMLDiff instead of raw wikicode diff
	 * @param $unhide boolean If set, allow viewing deleted revs
	 */
	function __construct( $titleObj = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $htmldiff = false, $unhide = false )
	{
		if ( $titleObj ) {
			$this->mTitle = $titleObj;
		} else {
			global $wgTitle;
			$this->mTitle = $wgTitle;
		}
		wfDebug("DifferenceEngine old '$old' new '$new' rcid '$rcid'\n");

		if ( 'prev' === $new ) {
			# Show diff between revision $old and the previous one.
			# Get previous one from DB.
			$this->mNewid = intval($old);
			$this->mOldid = $this->mTitle->getPreviousRevisionID( $this->mNewid );
		} elseif ( 'next' === $new ) {
			# Show diff between revision $old and the next one.
			# Get next one from DB.
			$this->mOldid = intval($old);
			$this->mNewid = $this->mTitle->getNextRevisionID( $this->mOldid );
			if ( false === $this->mNewid ) {
				# if no result, NewId points to the newest old revision. The only newer
				# revision is cur, which is "0".
				$this->mNewid = 0;
			}
		} else {
			$this->mOldid = intval($old);
			$this->mNewid = intval($new);
			wfRunHooks( 'NewDifferenceEngine', array(&$titleObj, &$this->mOldid, &$this->mNewid, $old, $new) ); 
		}
		$this->mRcidMarkPatrolled = intval($rcid);  # force it to be an integer
		$this->mRefreshCache = $refreshCache;
		$this->htmldiff = $htmldiff;
		$this->unhide = $unhide;
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
		global $wgUser, $wgOut, $wgUseExternalEditor, $wgUseRCPatrol, $wgEnableHtmlDiff;
		wfProfileIn( __METHOD__ );


		# If external diffs are enabled both globally and for the user,
		# we'll use the application/x-external-editor interface to call
		# an external diff tool like kompare, kdiff3, etc.
		if($wgUseExternalEditor && $wgUser->getOption('externaldiff')) {
			global $wgInputEncoding,$wgServer,$wgScript,$wgLang;
			$wgOut->disable();
			header ( "Content-type: application/x-external-editor; charset=".$wgInputEncoding );
			$url1=$this->mTitle->getFullURL( array(
				'action' => 'raw',
				'oldid' => $this->mOldid
			) );
			$url2=$this->mTitle->getFullURL( array(
				'action' => 'raw',
				'oldid' => $this->mNewid
			) );
			$special=$wgLang->getNsText(NS_SPECIAL);
			$control=<<<CONTROL
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
			echo($control);
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
		if( $oldTitle == $newTitle ) {
			$wgOut->setPageTitle( $newTitle );
		} else {
			$wgOut->setPageTitle( $oldTitle . ', ' . $newTitle );
		}
		$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
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
			$rollback = '&nbsp;&nbsp;&nbsp;' . $sk->generateRollback( $this->mNewRev );
		} else {
			$rollback = '';
		}

		// Prepare a change patrol link, if applicable
		if( $wgUseRCPatrol && $this->mTitle->userCan('patrol') ) {
			// If we've been given an explicit change identifier, use it; saves time
			if( $this->mRcidMarkPatrolled ) {
				$rcid = $this->mRcidMarkPatrolled;
				$rc = RecentChange::newFromId( $rcid );
				// Already patrolled?
				$rcid = is_object($rc) && !$rc->getAttribute('rc_patrolled') ? $rcid : 0;
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
				if( $change instanceof RecentChange ) {
					$rcid = $change->mAttribs['rc_id'];
					$this->mRcidMarkPatrolled = $rcid;
				} else {
					// None found
					$rcid = 0;
				}
			}
			// Build the link
			if( $rcid ) {
				$patrol = ' <span class="patrollink">[' . $sk->link(
					$this->mTitle, 
					wfMsgHtml( 'markaspatrolleddiff' ),
					array(),
					array(
						'action' => 'markpatrolled',
						'rcid' => $rcid
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
		if( $diffOnly != $wgUser->getBoolOption('diffonly') ) {
			$query['diffonly'] = $diffOnly;
		}

		$htmldiffarg = $this->htmlDiffArgument();

		if( $htmldiffarg ) {
			$query['htmldiff'] = $htmldiffarg['htmldiff'];
		}

		# Make "previous revision link"
		$query['diff'] = 'prev';
		$query['oldid'] = $this->mOldid;

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
		# Make "next revision link"
		$query['diff'] = 'next';
		$query['oldid'] = $this->mNewid;

		if( $this->mNewRev->isCurrent() ) {
			$nextlink = '&nbsp;';
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

		if( $this->mOldRev->isMinor() ) {
			$oldminor = Xml::span( wfMsg( 'minoreditletter' ), 'minor' ) . ' ';
		}
		if( $this->mNewRev->isMinor() ) {
			$newminor = Xml::span( wfMsg( 'minoreditletter' ), 'minor' ) . ' ';
		}

		$rdel = ''; $ldel = '';
		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			if( !$this->mOldRev->userCan( Revision::DELETED_RESTRICTED ) ) {
				// If revision was hidden from sysops
				$ldel = Xml::tags( 'span', array( 'class' => 'mw-revdelundel-link' ), '(' . wfMsgHtml( 'rev-delundel' ) . ')' );
			} else {
				$query = array( 
					'type' => 'revision',
					'target' => $this->mOldRev->mTitle->getPrefixedDbkey(),
					'ids' => $this->mOldRev->getId()
				);
				$ldel = $sk->revDeleteLink( $query, $this->mOldRev->isDeleted( Revision::DELETED_RESTRICTED ) );
			}
			$ldel = "&nbsp;&nbsp;&nbsp;$ldel ";
			if( !$this->mNewRev->userCan( Revision::DELETED_RESTRICTED ) ) {
				// If revision was hidden from sysops
				$rdel = Xml::tags( 'span', array( 'class'=>'mw-revdelundel-link' ), '('.wfMsgHtml( 'rev-delundel' ).')' );
			} else {
				$query = array( 
					'type' => 'revision',
					'target' =>  $this->mNewRev->mTitle->getPrefixedDbkey(),
					'ids' => $this->mNewRev->getId()
				);
				$rdel = $sk->revDeleteLink( $query, $this->mNewRev->isDeleted( Revision::DELETED_RESTRICTED ) );
			}
			$rdel = "&nbsp;&nbsp;&nbsp;$rdel ";
		}

		$oldHeader = '<div id="mw-diff-otitle1"><strong>'.$this->mOldtitle.'</strong></div>' .
			'<div id="mw-diff-otitle2">' . $sk->revUserTools( $this->mOldRev, !$this->unhide ) . "</div>" .
			'<div id="mw-diff-otitle3">' . $oldminor . $sk->revComment( $this->mOldRev, !$diffOnly, !$this->unhide ).$ldel."</div>" .
			'<div id="mw-diff-otitle4">' . $prevlink .'</div>';
		$newHeader = '<div id="mw-diff-ntitle1"><strong>'.$this->mNewtitle.'</strong></div>' .
			'<div id="mw-diff-ntitle2">' . $sk->revUserTools( $this->mNewRev, !$this->unhide ) . " $rollback</div>" .
			'<div id="mw-diff-ntitle3">' . $newminor . $sk->revComment( $this->mNewRev, !$diffOnly, !$this->unhide ).$rdel."</div>" .
			'<div id="mw-diff-ntitle4">' . $nextlink . $patrol . '</div>';

		# Check if this user can see the revisions
		$allowed = $this->mOldRev->userCan(Revision::DELETED_TEXT)
			&& $this->mNewRev->userCan(Revision::DELETED_TEXT);
		$deleted = $this->mOldRev->isDeleted(Revision::DELETED_TEXT)
			|| $this->mNewRev->isDeleted(Revision::DELETED_TEXT);
		# Output the diff if allowed...
		if( $deleted && (!$this->unhide || !$allowed) ) {
			$this->showDiffStyle();
			$multi = $this->getMultiNotice();
			$wgOut->addHTML( $this->addHeader( '', $oldHeader, $newHeader, $multi ) );
			if( !$allowed ) {
				# Give explanation for why revision is not visible
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n",
					array( 'rev-deleted-no-diff' ) );
			} else {
				# Give explanation and add a link to view the diff...
				$link = $this->mTitle->getFullUrl( array(
					'diff' => $this->mNewid,
					'oldid' => $this->mOldid,
					'unhide' => 1
				) );
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n",
					array( 'rev-deleted-unhide-diff', $link ) );
			}
		} else if( $wgEnableHtmlDiff && $this->htmldiff ) {
			$multi = $this->getMultiNotice();
			$wgOut->addHTML( '<div class="diff-switchtype">' . $sk->link(
				$this->mTitle,
				wfMsgHtml( 'wikicodecomparison' ),
				array(
					'id' => 'differences-switchtype'
				),
				array(
					'diff' => $this->mNewid,
					'oldid' => $this->mOldid,
					'htmldiff' => 0
				),
				array(
					'known',
					'noclasses'
				)
			) . '</div>');
			$wgOut->addHTML( $this->addHeader( '', $oldHeader, $newHeader, $multi ) );
			$this->renderHtmlDiff();
		} else {
			if( $wgEnableHtmlDiff ) {
				$wgOut->addHTML( '<div class="diff-switchtype">' . $sk->link(
					$this->mTitle,
					wfMsgHtml( 'visualcomparison' ),
					array(
						'id' => 'differences-switchtype'
					),
					array(
						'diff' => $this->mNewid,
						'oldid' => $this->mOldid,
						'htmldiff' => 1
					),
					array(
						'known',
						'noclasses'
					)
				) . '</div>');
			}
			$this->showDiff( $oldHeader, $newHeader );
			if( !$diffOnly ) {
				$this->renderNewRevision();
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Show the new revision of the page.
	 */
	function renderNewRevision() {
		global $wgOut, $wgUser;
		wfProfileIn( __METHOD__ );

		$wgOut->addHTML( "<hr /><h2>{$this->mPagetitle}</h2>\n" );
		# Add deleted rev tag if needed
		if( !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-permission' );
		} else if( $this->mNewRev->isDeleted(Revision::DELETED_TEXT) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-view' );
		}

		if( !$this->mNewRev->isCurrent() ) {
			$oldEditSectionSetting = $wgOut->parserOptions()->setEditSection( false );
		}

		$this->loadNewText();
		if( is_object( $this->mNewRev ) ) {
			$wgOut->setRevisionId( $this->mNewRev->getId() );
		}

		if( $this->mTitle->isCssJsSubpage() || $this->mTitle->isCssOrJsPage() ) {
			// Stolen from Article::view --AG 2007-10-11
			// Give hooks a chance to customise the output
			if( wfRunHooks( 'ShowRawCssJs', array( $this->mNewtext, $this->mTitle, $wgOut ) ) ) {
				// Wrap the whole lot in a <pre> and don't parse
				$m = array();
				preg_match( '!\.(css|js)$!u', $this->mTitle->getText(), $m );
				$wgOut->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
				$wgOut->addHTML( htmlspecialchars( $this->mNewtext ) );
				$wgOut->addHTML( "\n</pre>\n" );
			}
		} else {
			$wgOut->addWikiTextTidy( $this->mNewtext );
		}

		if( is_object( $this->mNewRev ) && !$this->mNewRev->isCurrent() ) {
			$wgOut->parserOptions()->setEditSection( $oldEditSectionSetting );
		}
		# Add redundant patrol link on bottom...
		if( $this->mRcidMarkPatrolled && $this->mTitle->quickUserCan('patrol') ) {
			$sk = $wgUser->getSkin();
			$wgOut->addHTML(
				"<div class='patrollink'>[" . $sk->link(
					$this->mTitle,
					wfMsgHtml( 'markaspatrolleddiff' ),
					array(),
					array(
						'action' => 'markpatrolled',
						'rcid' => $this->mRcidMarkPatrolled
					)
				) . ']</div>'
			 );
		}

		wfProfileOut( __METHOD__ );
	}


	function renderHtmlDiff() {
		global $wgOut, $wgParser, $wgDebugComments;
		wfProfileIn( __METHOD__ );

		$this->showDiffStyle();

		$wgOut->addHTML( '<h2>'.wfMsgHtml( 'visual-comparison' )."</h2>\n" );
		#add deleted rev tag if needed
		if( !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-permission' );
		} else if( $this->mNewRev->isDeleted(Revision::DELETED_TEXT) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-view' );
		}

		if( !$this->mNewRev->isCurrent() ) {
			$oldEditSectionSetting = $wgOut->parserOptions()->setEditSection( false );
		}

		$this->loadText();

		// Old revision
		if( is_object( $this->mOldRev ) ) {
			$wgOut->setRevisionId( $this->mOldRev->getId() );
		}

		$popts = $wgOut->parserOptions();
		$oldTidy = $popts->setTidy( true );
		$popts->setEditSection( false );

		$parserOutput = $wgParser->parse( $this->mOldtext, $this->getTitle(), $popts, true, true, $wgOut->getRevisionId() );
		$popts->setTidy( $oldTidy );

		//only for new?
		//$wgOut->addParserOutputNoText( $parserOutput );
		$oldHtml = $parserOutput->getText();
		wfRunHooks( 'OutputPageBeforeHTML', array( &$wgOut, &$oldHtml ) );

		// New revision
		if( is_object( $this->mNewRev ) ) {
			$wgOut->setRevisionId( $this->mNewRev->getId() );
		}

		$popts = $wgOut->parserOptions();
		$oldTidy = $popts->setTidy( true );

		$parserOutput = $wgParser->parse( $this->mNewtext, $this->getTitle(), $popts, true, true, $wgOut->getRevisionId() );
		$popts->setTidy( $oldTidy );

		$wgOut->addParserOutputNoText( $parserOutput );
		$newHtml = $parserOutput->getText();
		wfRunHooks( 'OutputPageBeforeHTML', array( &$wgOut, &$newHtml ) );

		unset($parserOutput, $popts);

		$differ = new HTMLDiffer(new DelegatingContentHandler($wgOut));
		$differ->htmlDiff($oldHtml, $newHtml);
		if ( $wgDebugComments ) {
			$wgOut->addHTML( "\n<!-- HtmlDiff Debug Output:\n" . HTMLDiffer::getDebugOutput() . " End Debug -->" );
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
			throw new MWException("Permission Error: you do not have access to view this page");
		}

		# Prepare the header box
		#
		$sk = $wgUser->getSkin();

		$next = $this->mTitle->getNextRevisionID( $this->mNewid );
		if( !$next ) {
			$nextlink = '';
		} else {
			$nextlink = '<br/>' . $sk->link(
				$this->mTitle,
				wfMsgHtml( 'nextdiff' ),
				array(
					'id' => 'differences-nextlink'
				),
				array(
					'diff' => 'next',
					'oldid' => $this->mNewid,
					$this->htmlDiffArgument()
				),
				array(
					'known',
					'noclasses'
				)
			);
		}
		$header = "<div class=\"firstrevisionheader\" style=\"text-align: center\">" .
			$sk->revUserTools( $this->mNewRev ) . "<br/>" . $sk->revComment( $this->mNewRev ) . $nextlink . "</div>\n";

		$wgOut->addHTML( $header );

		$wgOut->setSubtitle( wfMsgExt( 'difference', array( 'parseinline' ) ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		wfProfileOut( __METHOD__ );
	}

	function htmlDiffArgument(){
		global $wgEnableHtmlDiff;
		if($wgEnableHtmlDiff){
			if($this->htmldiff){
				return array( 'htmldiff' => 1 );
			}else{
				return array( 'htmldiff' => 0 );
			}
		}else{
			return array();
		}
	}

	/**
	 * Get the diff text, send it to $wgOut
	 * Returns false if the diff could not be generated, otherwise returns true
	 */
	function showDiff( $otitle, $ntitle ) {
		global $wgOut;
		$diff = $this->getDiff( $otitle, $ntitle );
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
		global $wgStylePath, $wgStyleVersion, $wgOut;
		$wgOut->addStyle( 'common/diff.css' );

		// JS is needed to detect old versions of Mozilla to work around an annoyance bug.
		$wgOut->addScript( "<script type=\"text/javascript\" src=\"$wgStylePath/common/diff.js?$wgStyleVersion\"></script>" );
	}

	/**
	 * Get complete diff table, including header
	 *
	 * @param Title $otitle Old title
	 * @param Title $ntitle New title
	 * @return mixed
	 */
	function getDiff( $otitle, $ntitle ) {
		$body = $this->getDiffBody();
		if ( $body === false ) {
			return false;
		} else {
			$multi = $this->getMultiNotice();
			return $this->addHeader( $body, $otitle, $ntitle, $multi );
		}
	}

	/**
	 * Get the diff table body, without header
	 *
	 * @return mixed
	 */
	function getDiffBody() {
		global $wgMemc;
		wfProfileIn( __METHOD__ );
		$this->mCacheHit = true;
		// Check if the diff should be hidden from this user
		if ( !$this->loadRevisionData() )
			return '';
		if ( $this->mOldRev && !$this->mOldRev->userCan(Revision::DELETED_TEXT) ) {
			return '';
		} else if ( $this->mNewRev && !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
			return '';
		} else if ( $this->mOldRev && $this->mNewRev && $this->mOldRev->getID() == $this->mNewRev->getID() ) {
			return '';
		}
		// Cacheable?
		$key = false;
		if ( $this->mOldid && $this->mNewid ) {
			$key = wfMemcKey( 'diff', 'version', MW_DIFF_VERSION, 'oldid', $this->mOldid, 'newid', $this->mNewid );
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
		} else if ( $key !== false && $difftext !== false ) {
			wfIncrStats( 'diff_cache_miss' );
			$wgMemc->set( $key, $difftext, 7*86400 );
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
			dl( 'php_wikidiff2.so' );
			wfRestoreWarnings();
			wfProfileOut( __METHOD__ . '-php_wikidiff2.so' );
		}
	}

	/**
	 * Generate a diff, no caching
	 * $otext and $ntext must be already segmented
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
	protected function debug( $generator="internal" ) {
		global $wgShowHostnames;
		$data = array( $generator );
		if( $wgShowHostnames ) {
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
		return wfMsgExt( 'lineno', 'escape', $wgLang->formatNum( $matches[1] ) );
	}


	/**
	 * If there are revisions between the ones being compared, return a note saying so.
	 */
	function getMultiNotice() {
		if ( !is_object($this->mOldRev) || !is_object($this->mNewRev) )
		return '';

		if( !$this->mOldPage->equals( $this->mNewPage ) ) {
			// Comparing two different pages? Count would be meaningless.
			return '';
		}

		$oldid = $this->mOldRev->getId();
		$newid = $this->mNewRev->getId();
		if ( $oldid > $newid ) {
			$tmp = $oldid; $oldid = $newid; $newid = $tmp;
		}

		$n = $this->mTitle->countRevisionsBetween( $oldid, $newid );
		if ( !$n )
		return '';

		return wfMsgExt( 'diff-multi', array( 'parseinline' ), $n );
	}


	/**
	 * Add the header to a diff body
	 */
	static function addHeader( $diff, $otitle, $ntitle, $multi = '' ) {
		$colspan = 1;
		$header = "<table class='diff'>";
		if( $diff ) { // Safari/Chrome show broken output if cols not used
			$header .= "
			<col class='diff-marker' />
			<col class='diff-content' />
			<col class='diff-marker' />
			<col class='diff-content' />";
			$colspan = 2;
		}
		$header .= "
		<tr valign='top'>
		<td colspan='$colspan' class='diff-otitle'>{$otitle}</td>
		<td colspan='$colspan' class='diff-ntitle'>{$ntitle}</td>
		</tr>";

		if ( $multi != '' )
		$header .= "<tr><td colspan='4' align='center' class='diff-multi'>{$multi}</td></tr>";

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
		if( !$this->mNewRev instanceof Revision )
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
		if( $this->mNewRev->isCurrent() ) {
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
		if( !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
			$this->mNewtitle = "<span class='history-deleted'>{$this->mPagetitle}</span>";
		} else if ( $this->mNewRev->isDeleted(Revision::DELETED_TEXT) ) {
			$this->mNewtitle = "<span class='history-deleted'>{$this->mNewtitle}</span>";
		}

		// Load the old revision object
		$this->mOldRev = false;
		if( $this->mOldid ) {
			$this->mOldRev = Revision::newFromId( $this->mOldid );
		} elseif ( $this->mOldid === 0 ) {
			$rev = $this->mNewRev->getPrevious();
			if( $rev ) {
				$this->mOldid = $rev->getId();
				$this->mOldRev = $rev;
			} else {
				// No previous revision; mark to show as first-version only.
				$this->mOldid = false;
				$this->mOldRev = false;
			}
		}/* elseif ( $this->mOldid === false ) leave mOldRev false; */

		if( is_null( $this->mOldRev ) ) {
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
			$htmlTitle = $wgUser->getSkin()->tooltip( 'undo' );
			if( $editable && !$this->mOldRev->isDeleted( Revision::DELETED_TEXT ) && !$this->mNewRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->mNewtitle .= " (<a href='$newUndo' $htmlTitle>" . $htmlLink . "</a>)";
			}

			if( !$this->mOldRev->userCan( Revision::DELETED_TEXT ) ) {
				$this->mOldtitle = '<span class="history-deleted">' . $this->mOldPagetitle . '</span>';
			} else if( $this->mOldRev->isDeleted( Revision::DELETED_TEXT ) ) {
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

// A PHP diff engine for phpwiki. (Taken from phpwiki-1.3.3)
//
// Copyright (C) 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
// You may copy this code freely under the conditions of the GPL.
//

define('USE_ASSERTS', function_exists('assert'));

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp {
	var $type;
	var $orig;
	var $closing;

	function reverse() {
		trigger_error('pure virtual', E_USER_ERROR);
	}

	function norig() {
		return $this->orig ? sizeof($this->orig) : 0;
	}

	function nclosing() {
		return $this->closing ? sizeof($this->closing) : 0;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Copy extends _DiffOp {
	var $type = 'copy';

	function _DiffOp_Copy ($orig, $closing = false) {
		if (!is_array($closing))
		$closing = $orig;
		$this->orig = $orig;
		$this->closing = $closing;
	}

	function reverse() {
		return new _DiffOp_Copy($this->closing, $this->orig);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Delete extends _DiffOp {
	var $type = 'delete';

	function _DiffOp_Delete ($lines) {
		$this->orig = $lines;
		$this->closing = false;
	}

	function reverse() {
		return new _DiffOp_Add($this->orig);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Add extends _DiffOp {
	var $type = 'add';

	function _DiffOp_Add ($lines) {
		$this->closing = $lines;
		$this->orig = false;
	}

	function reverse() {
		return new _DiffOp_Delete($this->closing);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Change extends _DiffOp {
	var $type = 'change';

	function _DiffOp_Change ($orig, $closing) {
		$this->orig = $orig;
		$this->closing = $closing;
	}

	function reverse() {
		return new _DiffOp_Change($this->closing, $this->orig);
	}
}

/**
 * Class used internally by Diff to actually compute the diffs.
 *
 * The algorithm used here is mostly lifted from the perl module
 * Algorithm::Diff (version 1.06) by Ned Konz, which is available at:
 *	 http://www.perl.com/CPAN/authors/id/N/NE/NEDKONZ/Algorithm-Diff-1.06.zip
 *
 * More ideas are taken from:
 *	 http://www.ics.uci.edu/~eppstein/161/960229.html
 *
 * Some ideas are (and a bit of code) are from from analyze.c, from GNU
 * diffutils-2.7, which can be found at:
 *	 ftp://gnudist.gnu.org/pub/gnu/diffutils/diffutils-2.7.tar.gz
 *
 * closingly, some ideas (subdivision by NCHUNKS > 2, and some optimizations)
 * are my own.
 *
 * Line length limits for robustness added by Tim Starling, 2005-08-31
 * Alternative implementation added by Guy Van den Broeck, 2008-07-30
 *
 * @author Geoffrey T. Dairiki, Tim Starling, Guy Van den Broeck
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffEngine {

	const MAX_XREF_LENGTH =  10000;

	function diff ($from_lines, $to_lines){
		wfProfileIn( __METHOD__ );

		// Diff and store locally
		$this->diff_local($from_lines, $to_lines);

		// Merge edits when possible
		$this->_shift_boundaries($from_lines, $this->xchanged, $this->ychanged);
		$this->_shift_boundaries($to_lines, $this->ychanged, $this->xchanged);

		// Compute the edit operations.
		$n_from = sizeof($from_lines);
		$n_to = sizeof($to_lines);

		$edits = array();
		$xi = $yi = 0;
		while ($xi < $n_from || $yi < $n_to) {
			USE_ASSERTS && assert($yi < $n_to || $this->xchanged[$xi]);
			USE_ASSERTS && assert($xi < $n_from || $this->ychanged[$yi]);

			// Skip matching "snake".
			$copy = array();
			while ( $xi < $n_from && $yi < $n_to
			&& !$this->xchanged[$xi] && !$this->ychanged[$yi]) {
				$copy[] = $from_lines[$xi++];
				++$yi;
			}
			if ($copy)
			$edits[] = new _DiffOp_Copy($copy);

			// Find deletes & adds.
			$delete = array();
			while ($xi < $n_from && $this->xchanged[$xi])
			$delete[] = $from_lines[$xi++];

			$add = array();
			while ($yi < $n_to && $this->ychanged[$yi])
			$add[] = $to_lines[$yi++];

			if ($delete && $add)
			$edits[] = new _DiffOp_Change($delete, $add);
			elseif ($delete)
			$edits[] = new _DiffOp_Delete($delete);
			elseif ($add)
			$edits[] = new _DiffOp_Add($add);
		}
		wfProfileOut( __METHOD__ );
		return $edits;
	}

	function diff_local ($from_lines, $to_lines) {
		global $wgExternalDiffEngine;
		wfProfileIn( __METHOD__);

		if($wgExternalDiffEngine == 'wikidiff3'){
			// wikidiff3
			$wikidiff3 = new WikiDiff3();
			$wikidiff3->diff($from_lines, $to_lines);
			$this->xchanged = $wikidiff3->removed;
			$this->ychanged = $wikidiff3->added;
			unset($wikidiff3);
		}else{
			// old diff
			$n_from = sizeof($from_lines);
			$n_to = sizeof($to_lines);
			$this->xchanged = $this->ychanged = array();
			$this->xv = $this->yv = array();
			$this->xind = $this->yind = array();
			unset($this->seq);
			unset($this->in_seq);
			unset($this->lcs);

			// Skip leading common lines.
			for ($skip = 0; $skip < $n_from && $skip < $n_to; $skip++) {
				if ($from_lines[$skip] !== $to_lines[$skip])
				break;
				$this->xchanged[$skip] = $this->ychanged[$skip] = false;
			}
			// Skip trailing common lines.
			$xi = $n_from; $yi = $n_to;
			for ($endskip = 0; --$xi > $skip && --$yi > $skip; $endskip++) {
				if ($from_lines[$xi] !== $to_lines[$yi])
				break;
				$this->xchanged[$xi] = $this->ychanged[$yi] = false;
			}

			// Ignore lines which do not exist in both files.
			for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
				$xhash[$this->_line_hash($from_lines[$xi])] = 1;
			}

			for ($yi = $skip; $yi < $n_to - $endskip; $yi++) {
				$line = $to_lines[$yi];
				if ( ($this->ychanged[$yi] = empty($xhash[$this->_line_hash($line)])) )
				continue;
				$yhash[$this->_line_hash($line)] = 1;
				$this->yv[] = $line;
				$this->yind[] = $yi;
			}
			for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
				$line = $from_lines[$xi];
				if ( ($this->xchanged[$xi] = empty($yhash[$this->_line_hash($line)])) )
				continue;
				$this->xv[] = $line;
				$this->xind[] = $xi;
			}

			// Find the LCS.
			$this->_compareseq(0, sizeof($this->xv), 0, sizeof($this->yv));
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Returns the whole line if it's small enough, or the MD5 hash otherwise
	 */
	function _line_hash( $line ) {
		if ( strlen( $line ) > self::MAX_XREF_LENGTH ) {
			return md5( $line );
		} else {
			return $line;
		}
	}

	/* Divide the Largest Common Subsequence (LCS) of the sequences
	 * [XOFF, XLIM) and [YOFF, YLIM) into NCHUNKS approximately equally
	 * sized segments.
	 *
	 * Returns (LCS, PTS).	LCS is the length of the LCS. PTS is an
	 * array of NCHUNKS+1 (X, Y) indexes giving the diving points between
	 * sub sequences.  The first sub-sequence is contained in [X0, X1),
	 * [Y0, Y1), the second in [X1, X2), [Y1, Y2) and so on.  Note
	 * that (X0, Y0) == (XOFF, YOFF) and
	 * (X[NCHUNKS], Y[NCHUNKS]) == (XLIM, YLIM).
	 *
	 * This function assumes that the first lines of the specified portions
	 * of the two files do not match, and likewise that the last lines do not
	 * match.  The caller must trim matching lines from the beginning and end
	 * of the portions it is going to specify.
	 */
	function _diag ($xoff, $xlim, $yoff, $ylim, $nchunks) {
		$flip = false;

		if ($xlim - $xoff > $ylim - $yoff) {
			// Things seems faster (I'm not sure I understand why)
			// when the shortest sequence in X.
			$flip = true;
			list ($xoff, $xlim, $yoff, $ylim)
			= array( $yoff, $ylim, $xoff, $xlim);
		}

		if ($flip)
		for ($i = $ylim - 1; $i >= $yoff; $i--)
		$ymatches[$this->xv[$i]][] = $i;
		else
		for ($i = $ylim - 1; $i >= $yoff; $i--)
		$ymatches[$this->yv[$i]][] = $i;

		$this->lcs = 0;
		$this->seq[0]= $yoff - 1;
		$this->in_seq = array();
		$ymids[0] = array();

		$numer = $xlim - $xoff + $nchunks - 1;
		$x = $xoff;
		for ($chunk = 0; $chunk < $nchunks; $chunk++) {
			if ($chunk > 0)
			for ($i = 0; $i <= $this->lcs; $i++)
			$ymids[$i][$chunk-1] = $this->seq[$i];

			$x1 = $xoff + (int)(($numer + ($xlim-$xoff)*$chunk) / $nchunks);
			for ( ; $x < $x1; $x++) {
				$line = $flip ? $this->yv[$x] : $this->xv[$x];
				if (empty($ymatches[$line]))
				continue;
				$matches = $ymatches[$line];
				reset($matches);
				while (list ($junk, $y) = each($matches))
				if (empty($this->in_seq[$y])) {
					$k = $this->_lcs_pos($y);
					USE_ASSERTS && assert($k > 0);
					$ymids[$k] = $ymids[$k-1];
					break;
				}
				while (list ( /* $junk */, $y) = each($matches)) {
					if ($y > $this->seq[$k-1]) {
						USE_ASSERTS && assert($y < $this->seq[$k]);
						// Optimization: this is a common case:
						//	next match is just replacing previous match.
						$this->in_seq[$this->seq[$k]] = false;
						$this->seq[$k] = $y;
						$this->in_seq[$y] = 1;
					} else if (empty($this->in_seq[$y])) {
						$k = $this->_lcs_pos($y);
						USE_ASSERTS && assert($k > 0);
						$ymids[$k] = $ymids[$k-1];
					}
				}
			}
		}

		$seps[] = $flip ? array($yoff, $xoff) : array($xoff, $yoff);
		$ymid = $ymids[$this->lcs];
		for ($n = 0; $n < $nchunks - 1; $n++) {
			$x1 = $xoff + (int)(($numer + ($xlim - $xoff) * $n) / $nchunks);
			$y1 = $ymid[$n] + 1;
			$seps[] = $flip ? array($y1, $x1) : array($x1, $y1);
		}
		$seps[] = $flip ? array($ylim, $xlim) : array($xlim, $ylim);

		return array($this->lcs, $seps);
	}

	function _lcs_pos ($ypos) {
		$end = $this->lcs;
		if ($end == 0 || $ypos > $this->seq[$end]) {
			$this->seq[++$this->lcs] = $ypos;
			$this->in_seq[$ypos] = 1;
			return $this->lcs;
		}

		$beg = 1;
		while ($beg < $end) {
			$mid = (int)(($beg + $end) / 2);
			if ( $ypos > $this->seq[$mid] )
			$beg = $mid + 1;
			else
			$end = $mid;
		}

		USE_ASSERTS && assert($ypos != $this->seq[$end]);

		$this->in_seq[$this->seq[$end]] = false;
		$this->seq[$end] = $ypos;
		$this->in_seq[$ypos] = 1;
		return $end;
	}

	/* Find LCS of two sequences.
	 *
	 * The results are recorded in the vectors $this->{x,y}changed[], by
	 * storing a 1 in the element for each line that is an insertion
	 * or deletion (ie. is not in the LCS).
	 *
	 * The subsequence of file 0 is [XOFF, XLIM) and likewise for file 1.
	 *
	 * Note that XLIM, YLIM are exclusive bounds.
	 * All line numbers are origin-0 and discarded lines are not counted.
	 */
	function _compareseq ($xoff, $xlim, $yoff, $ylim) {
		// Slide down the bottom initial diagonal.
		while ($xoff < $xlim && $yoff < $ylim
		&& $this->xv[$xoff] == $this->yv[$yoff]) {
			++$xoff;
			++$yoff;
		}

		// Slide up the top initial diagonal.
		while ($xlim > $xoff && $ylim > $yoff
		&& $this->xv[$xlim - 1] == $this->yv[$ylim - 1]) {
			--$xlim;
			--$ylim;
		}

		if ($xoff == $xlim || $yoff == $ylim)
		$lcs = 0;
		else {
			// This is ad hoc but seems to work well.
			//$nchunks = sqrt(min($xlim - $xoff, $ylim - $yoff) / 2.5);
			//$nchunks = max(2,min(8,(int)$nchunks));
			$nchunks = min(7, $xlim - $xoff, $ylim - $yoff) + 1;
			list ($lcs, $seps)
			= $this->_diag($xoff,$xlim,$yoff, $ylim,$nchunks);
		}

		if ($lcs == 0) {
			// X and Y sequences have no common subsequence:
			// mark all changed.
			while ($yoff < $ylim)
			$this->ychanged[$this->yind[$yoff++]] = 1;
			while ($xoff < $xlim)
			$this->xchanged[$this->xind[$xoff++]] = 1;
		} else {
			// Use the partitions to split this problem into subproblems.
			reset($seps);
			$pt1 = $seps[0];
			while ($pt2 = next($seps)) {
				$this->_compareseq ($pt1[0], $pt2[0], $pt1[1], $pt2[1]);
				$pt1 = $pt2;
			}
		}
	}

	/* Adjust inserts/deletes of identical lines to join changes
	 * as much as possible.
	 *
	 * We do something when a run of changed lines include a
	 * line at one end and has an excluded, identical line at the other.
	 * We are free to choose which identical line is included.
	 * `compareseq' usually chooses the one at the beginning,
	 * but usually it is cleaner to consider the following identical line
	 * to be the "change".
	 *
	 * This is extracted verbatim from analyze.c (GNU diffutils-2.7).
	 */
	function _shift_boundaries ($lines, &$changed, $other_changed) {
		wfProfileIn( __METHOD__ );
		$i = 0;
		$j = 0;

		USE_ASSERTS && assert('sizeof($lines) == sizeof($changed)');
		$len = sizeof($lines);
		$other_len = sizeof($other_changed);

		while (1) {
			/*
			 * Scan forwards to find beginning of another run of changes.
			 * Also keep track of the corresponding point in the other file.
			 *
			 * Throughout this code, $i and $j are adjusted together so that
			 * the first $i elements of $changed and the first $j elements
			 * of $other_changed both contain the same number of zeros
			 * (unchanged lines).
			 * Furthermore, $j is always kept so that $j == $other_len or
			 * $other_changed[$j] == false.
			 */
			while ($j < $other_len && $other_changed[$j])
			$j++;

			while ($i < $len && ! $changed[$i]) {
				USE_ASSERTS && assert('$j < $other_len && ! $other_changed[$j]');
				$i++; $j++;
				while ($j < $other_len && $other_changed[$j])
				$j++;
			}

			if ($i == $len)
			break;

			$start = $i;

			// Find the end of this run of changes.
			while (++$i < $len && $changed[$i])
			continue;

			do {
				/*
				 * Record the length of this run of changes, so that
				 * we can later determine whether the run has grown.
				 */
				$runlength = $i - $start;

				/*
				 * Move the changed region back, so long as the
				 * previous unchanged line matches the last changed one.
				 * This merges with previous changed regions.
				 */
				while ($start > 0 && $lines[$start - 1] == $lines[$i - 1]) {
					$changed[--$start] = 1;
					$changed[--$i] = false;
					while ($start > 0 && $changed[$start - 1])
					$start--;
					USE_ASSERTS && assert('$j > 0');
					while ($other_changed[--$j])
					continue;
					USE_ASSERTS && assert('$j >= 0 && !$other_changed[$j]');
				}

				/*
				 * Set CORRESPONDING to the end of the changed run, at the last
				 * point where it corresponds to a changed run in the other file.
				 * CORRESPONDING == LEN means no such point has been found.
				 */
				$corresponding = $j < $other_len ? $i : $len;

				/*
				 * Move the changed region forward, so long as the
				 * first changed line matches the following unchanged one.
				 * This merges with following changed regions.
				 * Do this second, so that if there are no merges,
				 * the changed region is moved forward as far as possible.
				 */
				while ($i < $len && $lines[$start] == $lines[$i]) {
					$changed[$start++] = false;
					$changed[$i++] = 1;
					while ($i < $len && $changed[$i])
					$i++;

					USE_ASSERTS && assert('$j < $other_len && ! $other_changed[$j]');
					$j++;
					if ($j < $other_len && $other_changed[$j]) {
						$corresponding = $i;
						while ($j < $other_len && $other_changed[$j])
						$j++;
					}
				}
			} while ($runlength != $i - $start);

			/*
			 * If possible, move the fully-merged run of changes
			 * back to a corresponding run in the other file.
			 */
			while ($corresponding < $i) {
				$changed[--$start] = 1;
				$changed[--$i] = 0;
				USE_ASSERTS && assert('$j > 0');
				while ($other_changed[--$j])
				continue;
				USE_ASSERTS && assert('$j >= 0 && !$other_changed[$j]');
			}
		}
		wfProfileOut( __METHOD__ );
	}
}

/**
 * Class representing a 'diff' between two sequences of strings.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class Diff
{
	var $edits;

	/**
	 * Constructor.
	 * Computes diff between sequences of strings.
	 *
	 * @param $from_lines array An array of strings.
	 *		  (Typically these are lines from a file.)
	 * @param $to_lines array An array of strings.
	 */
	function Diff($from_lines, $to_lines) {
		$eng = new _DiffEngine;
		$this->edits = $eng->diff($from_lines, $to_lines);
		//$this->_check($from_lines, $to_lines);
	}

	/**
	 * Compute reversed Diff.
	 *
	 * SYNOPSIS:
	 *
	 *	$diff = new Diff($lines1, $lines2);
	 *	$rev = $diff->reverse();
	 * @return object A Diff object representing the inverse of the
	 *				  original diff.
	 */
	function reverse () {
		$rev = $this;
		$rev->edits = array();
		foreach ($this->edits as $edit) {
			$rev->edits[] = $edit->reverse();
		}
		return $rev;
	}

	/**
	 * Check for empty diff.
	 *
	 * @return bool True iff two sequences were identical.
	 */
	function isEmpty () {
		foreach ($this->edits as $edit) {
			if ($edit->type != 'copy')
			return false;
		}
		return true;
	}

	/**
	 * Compute the length of the Longest Common Subsequence (LCS).
	 *
	 * This is mostly for diagnostic purposed.
	 *
	 * @return int The length of the LCS.
	 */
	function lcs () {
		$lcs = 0;
		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$lcs += sizeof($edit->orig);
		}
		return $lcs;
	}

	/**
	 * Get the original set of lines.
	 *
	 * This reconstructs the $from_lines parameter passed to the
	 * constructor.
	 *
	 * @return array The original sequence of strings.
	 */
	function orig() {
		$lines = array();

		foreach ($this->edits as $edit) {
			if ($edit->orig)
			array_splice($lines, sizeof($lines), 0, $edit->orig);
		}
		return $lines;
	}

	/**
	 * Get the closing set of lines.
	 *
	 * This reconstructs the $to_lines parameter passed to the
	 * constructor.
	 *
	 * @return array The sequence of strings.
	 */
	function closing() {
		$lines = array();

		foreach ($this->edits as $edit) {
			if ($edit->closing)
			array_splice($lines, sizeof($lines), 0, $edit->closing);
		}
		return $lines;
	}

	/**
	 * Check a Diff for validity.
	 *
	 * This is here only for debugging purposes.
	 */
	function _check ($from_lines, $to_lines) {
		wfProfileIn( __METHOD__ );
		if (serialize($from_lines) != serialize($this->orig()))
		trigger_error("Reconstructed original doesn't match", E_USER_ERROR);
		if (serialize($to_lines) != serialize($this->closing()))
		trigger_error("Reconstructed closing doesn't match", E_USER_ERROR);

		$rev = $this->reverse();
		if (serialize($to_lines) != serialize($rev->orig()))
		trigger_error("Reversed original doesn't match", E_USER_ERROR);
		if (serialize($from_lines) != serialize($rev->closing()))
		trigger_error("Reversed closing doesn't match", E_USER_ERROR);


		$prevtype = 'none';
		foreach ($this->edits as $edit) {
			if ( $prevtype == $edit->type )
			trigger_error("Edit sequence is non-optimal", E_USER_ERROR);
			$prevtype = $edit->type;
		}

		$lcs = $this->lcs();
		trigger_error('Diff okay: LCS = '.$lcs, E_USER_NOTICE);
		wfProfileOut( __METHOD__ );
	}
}

/**
 * @todo document, bad name.
 * @private
 * @ingroup DifferenceEngine
 */
class MappedDiff extends Diff
{
	/**
	 * Constructor.
	 *
	 * Computes diff between sequences of strings.
	 *
	 * This can be used to compute things like
	 * case-insensitve diffs, or diffs which ignore
	 * changes in white-space.
	 *
	 * @param $from_lines array An array of strings.
	 *	(Typically these are lines from a file.)
	 *
	 * @param $to_lines array An array of strings.
	 *
	 * @param $mapped_from_lines array This array should
	 *	have the same size number of elements as $from_lines.
	 *	The elements in $mapped_from_lines and
	 *	$mapped_to_lines are what is actually compared
	 *	when computing the diff.
	 *
	 * @param $mapped_to_lines array This array should
	 *	have the same number of elements as $to_lines.
	 */
	function MappedDiff($from_lines, $to_lines,
	$mapped_from_lines, $mapped_to_lines) {
		wfProfileIn( __METHOD__ );

		assert(sizeof($from_lines) == sizeof($mapped_from_lines));
		assert(sizeof($to_lines) == sizeof($mapped_to_lines));

		$this->Diff($mapped_from_lines, $mapped_to_lines);

		$xi = $yi = 0;
		for ($i = 0; $i < sizeof($this->edits); $i++) {
			$orig = &$this->edits[$i]->orig;
			if (is_array($orig)) {
				$orig = array_slice($from_lines, $xi, sizeof($orig));
				$xi += sizeof($orig);
			}

			$closing = &$this->edits[$i]->closing;
			if (is_array($closing)) {
				$closing = array_slice($to_lines, $yi, sizeof($closing));
				$yi += sizeof($closing);
			}
		}
		wfProfileOut( __METHOD__ );
	}
}

/**
 * A class to format Diffs
 *
 * This class formats the diff in classic diff format.
 * It is intended that this class be customized via inheritance,
 * to obtain fancier outputs.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffFormatter {
	/**
	 * Number of leading context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	var $leading_context_lines = 0;

	/**
	 * Number of trailing context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	var $trailing_context_lines = 0;

	/**
	 * Format a diff.
	 *
	 * @param $diff object A Diff object.
	 * @return string The formatted output.
	 */
	function format($diff) {
		wfProfileIn( __METHOD__ );

		$xi = $yi = 1;
		$block = false;
		$context = array();

		$nlead = $this->leading_context_lines;
		$ntrail = $this->trailing_context_lines;

		$this->_start_diff();

		foreach ($diff->edits as $edit) {
			if ($edit->type == 'copy') {
				if (is_array($block)) {
					if (sizeof($edit->orig) <= $nlead + $ntrail) {
						$block[] = $edit;
					}
					else{
						if ($ntrail) {
							$context = array_slice($edit->orig, 0, $ntrail);
							$block[] = new _DiffOp_Copy($context);
						}
						$this->_block($x0, $ntrail + $xi - $x0,
						$y0, $ntrail + $yi - $y0,
						$block);
						$block = false;
					}
				}
				$context = $edit->orig;
			}
			else {
				if (! is_array($block)) {
					$context = array_slice($context, sizeof($context) - $nlead);
					$x0 = $xi - sizeof($context);
					$y0 = $yi - sizeof($context);
					$block = array();
					if ($context)
					$block[] = new _DiffOp_Copy($context);
				}
				$block[] = $edit;
			}

			if ($edit->orig)
			$xi += sizeof($edit->orig);
			if ($edit->closing)
			$yi += sizeof($edit->closing);
		}

		if (is_array($block))
		$this->_block($x0, $xi - $x0,
		$y0, $yi - $y0,
		$block);

		$end = $this->_end_diff();
		wfProfileOut( __METHOD__ );
		return $end;
	}

	function _block($xbeg, $xlen, $ybeg, $ylen, &$edits) {
		wfProfileIn( __METHOD__ );
		$this->_start_block($this->_block_header($xbeg, $xlen, $ybeg, $ylen));
		foreach ($edits as $edit) {
			if ($edit->type == 'copy')
			$this->_context($edit->orig);
			elseif ($edit->type == 'add')
			$this->_added($edit->closing);
			elseif ($edit->type == 'delete')
			$this->_deleted($edit->orig);
			elseif ($edit->type == 'change')
			$this->_changed($edit->orig, $edit->closing);
			else
			trigger_error('Unknown edit type', E_USER_ERROR);
		}
		$this->_end_block();
		wfProfileOut( __METHOD__ );
	}

	function _start_diff() {
		ob_start();
	}

	function _end_diff() {
		$val = ob_get_contents();
		ob_end_clean();
		return $val;
	}

	function _block_header($xbeg, $xlen, $ybeg, $ylen) {
		if ($xlen > 1)
		$xbeg .= "," . ($xbeg + $xlen - 1);
		if ($ylen > 1)
		$ybeg .= "," . ($ybeg + $ylen - 1);

		return $xbeg . ($xlen ? ($ylen ? 'c' : 'd') : 'a') . $ybeg;
	}

	function _start_block($header) {
		echo $header . "\n";
	}

	function _end_block() {
	}

	function _lines($lines, $prefix = ' ') {
		foreach ($lines as $line)
		echo "$prefix $line\n";
	}

	function _context($lines) {
		$this->_lines($lines);
	}

	function _added($lines) {
		$this->_lines($lines, '>');
	}
	function _deleted($lines) {
		$this->_lines($lines, '<');
	}

	function _changed($orig, $closing) {
		$this->_deleted($orig);
		echo "---\n";
		$this->_added($closing);
	}
}

/**
 * A formatter that outputs unified diffs
 * @ingroup DifferenceEngine
 */

class UnifiedDiffFormatter extends DiffFormatter {
	var $leading_context_lines = 2;
	var $trailing_context_lines = 2;

	function _added($lines) {
		$this->_lines($lines, '+');
	}
	function _deleted($lines) {
		$this->_lines($lines, '-');
	}
	function _changed($orig, $closing) {
		$this->_deleted($orig);
		$this->_added($closing);
	}
	function _block_header($xbeg, $xlen, $ybeg, $ylen) {
		return "@@ -$xbeg,$xlen +$ybeg,$ylen @@";
	}
}

/**
 * A pseudo-formatter that just passes along the Diff::$edits array
 * @ingroup DifferenceEngine
 */
class ArrayDiffFormatter extends DiffFormatter {
	function format($diff) {
		$oldline = 1;
		$newline = 1;
		$retval = array();
		foreach($diff->edits as $edit)
		switch($edit->type) {
			case 'add':
				foreach($edit->closing as $l) {
					$retval[] = array(
							'action' => 'add',
							'new'=> $l,
							'newline' => $newline++
					);
				}
				break;
			case 'delete':
				foreach($edit->orig as $l) {
					$retval[] = array(
							'action' => 'delete',
							'old' => $l,
							'oldline' => $oldline++,
					);
				}
				break;
			case 'change':
				foreach($edit->orig as $i => $l) {
					$retval[] = array(
							'action' => 'change',
							'old' => $l,
							'new' => @$edit->closing[$i],
							'oldline' => $oldline++,
							'newline' => $newline++,
					);
				}
				break;
			case 'copy':
				$oldline += count($edit->orig);
				$newline += count($edit->orig);
		}
		return $retval;
	}
}

/**
 *	Additions by Axel Boldt follow, partly taken from diff.php, phpwiki-1.3.3
 *
 */

define('NBSP', '&#160;'); // iso-8859-x non-breaking space.

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _HWLDF_WordAccumulator {
	function _HWLDF_WordAccumulator () {
		$this->_lines = array();
		$this->_line = '';
		$this->_group = '';
		$this->_tag = '';
	}

	function _flushGroup ($new_tag) {
		if ($this->_group !== '') {
			if ($this->_tag == 'ins')
			$this->_line .= '<ins class="diffchange diffchange-inline">' .
			htmlspecialchars ( $this->_group ) . '</ins>';
			elseif ($this->_tag == 'del')
			$this->_line .= '<del class="diffchange diffchange-inline">' .
			htmlspecialchars ( $this->_group ) . '</del>';
			else
			$this->_line .= htmlspecialchars ( $this->_group );
		}
		$this->_group = '';
		$this->_tag = $new_tag;
	}

	function _flushLine ($new_tag) {
		$this->_flushGroup($new_tag);
		if ($this->_line != '')
		array_push ( $this->_lines, $this->_line );
		else
		# make empty lines visible by inserting an NBSP
		array_push ( $this->_lines, NBSP );
		$this->_line = '';
	}

	function addWords ($words, $tag = '') {
		if ($tag != $this->_tag)
		$this->_flushGroup($tag);

		foreach ($words as $word) {
			// new-line should only come as first char of word.
			if ($word == '')
			continue;
			if ($word[0] == "\n") {
				$this->_flushLine($tag);
				$word = substr($word, 1);
			}
			assert(!strstr($word, "\n"));
			$this->_group .= $word;
		}
	}

	function getLines() {
		$this->_flushLine('~done');
		return $this->_lines;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class WordLevelDiff extends MappedDiff {
	const MAX_LINE_LENGTH = 10000;

	function WordLevelDiff ($orig_lines, $closing_lines) {
		wfProfileIn( __METHOD__ );

		list ($orig_words, $orig_stripped) = $this->_split($orig_lines);
		list ($closing_words, $closing_stripped) = $this->_split($closing_lines);

		$this->MappedDiff($orig_words, $closing_words,
		$orig_stripped, $closing_stripped);
		wfProfileOut( __METHOD__ );
	}

	function _split($lines) {
		wfProfileIn( __METHOD__ );

		$words = array();
		$stripped = array();
		$first = true;
		foreach ( $lines as $line ) {
			# If the line is too long, just pretend the entire line is one big word
			# This prevents resource exhaustion problems
			if ( $first ) {
				$first = false;
			} else {
				$words[] = "\n";
				$stripped[] = "\n";
			}
			if ( strlen( $line ) > self::MAX_LINE_LENGTH ) {
				$words[] = $line;
				$stripped[] = $line;
			} else {
				$m = array();
				if (preg_match_all('/ ( [^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | . ) (?: (?!< \n) [^\S\n])? /xs',
				$line, $m))
				{
					$words = array_merge( $words, $m[0] );
					$stripped = array_merge( $stripped, $m[1] );
				}
			}
		}
		wfProfileOut( __METHOD__ );
		return array($words, $stripped);
	}

	function orig () {
		wfProfileIn( __METHOD__ );
		$orig = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$orig->addWords($edit->orig);
			elseif ($edit->orig)
			$orig->addWords($edit->orig, 'del');
		}
		$lines = $orig->getLines();
		wfProfileOut( __METHOD__ );
		return $lines;
	}

	function closing () {
		wfProfileIn( __METHOD__ );
		$closing = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$closing->addWords($edit->closing);
			elseif ($edit->closing)
			$closing->addWords($edit->closing, 'ins');
		}
		$lines = $closing->getLines();
		wfProfileOut( __METHOD__ );
		return $lines;
	}
}

/**
 * Wikipedia Table style diff formatter.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class TableDiffFormatter extends DiffFormatter {
	function TableDiffFormatter() {
		$this->leading_context_lines = 2;
		$this->trailing_context_lines = 2;
	}

	public static function escapeWhiteSpace( $msg ) {
		$msg = preg_replace( '/^ /m', '&nbsp; ', $msg );
		$msg = preg_replace( '/ $/m', ' &nbsp;', $msg );
		$msg = preg_replace( '/  /', '&nbsp; ', $msg );
		return $msg;
	}

	function _block_header( $xbeg, $xlen, $ybeg, $ylen ) {
		$r = '<tr><td colspan="2" class="diff-lineno"><!--LINE '.$xbeg."--></td>\n" .
		  '<td colspan="2" class="diff-lineno"><!--LINE '.$ybeg."--></td></tr>\n";
		return $r;
	}

	function _start_block( $header ) {
		echo $header;
	}

	function _end_block() {
	}

	function _lines( $lines, $prefix=' ', $color='white' ) {
	}

	# HTML-escape parameter before calling this
	function addedLine( $line ) {
		return $this->wrapLine( '+', 'diff-addedline', $line );
	}

	# HTML-escape parameter before calling this
	function deletedLine( $line ) {
		return $this->wrapLine( '-', 'diff-deletedline', $line );
	}

	# HTML-escape parameter before calling this
	function contextLine( $line ) {
		return $this->wrapLine( ' ', 'diff-context', $line );
	}

	private function wrapLine( $marker, $class, $line ) {
		if( $line !== '' ) {
			// The <div> wrapper is needed for 'overflow: auto' style to scroll properly
			$line = Xml::tags( 'div', null, $this->escapeWhiteSpace( $line ) );
		}
		return "<td class='diff-marker'>$marker</td><td class='$class'>$line</td>";
	}

	function emptyLine() {
		return '<td colspan="2">&nbsp;</td>';
	}

	function _added( $lines ) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->emptyLine() .
			$this->addedLine( '<ins class="diffchange">' .
			htmlspecialchars ( $line ) . '</ins>' ) . "</tr>\n";
		}
	}

	function _deleted($lines) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->deletedLine( '<del class="diffchange">' .
			htmlspecialchars ( $line ) . '</del>' ) .
			$this->emptyLine() . "</tr>\n";
		}
	}

	function _context( $lines ) {
		foreach ($lines as $line) {
			echo '<tr>' .
			$this->contextLine( htmlspecialchars ( $line ) ) .
			$this->contextLine( htmlspecialchars ( $line ) ) . "</tr>\n";
		}
	}

	function _changed( $orig, $closing ) {
		wfProfileIn( __METHOD__ );

		$diff = new WordLevelDiff( $orig, $closing );
		$del = $diff->orig();
		$add = $diff->closing();

		# Notice that WordLevelDiff returns HTML-escaped output.
		# Hence, we will be calling addedLine/deletedLine without HTML-escaping.

		while ( $line = array_shift( $del ) ) {
			$aline = array_shift( $add );
			echo '<tr>' . $this->deletedLine( $line ) .
			$this->addedLine( $aline ) . "</tr>\n";
		}
		foreach ($add as $line) {	# If any leftovers
			echo '<tr>' . $this->emptyLine() .
			$this->addedLine( $line ) . "</tr>\n";
		}
		wfProfileOut( __METHOD__ );
	}
}
