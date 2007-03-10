<?php
/**
 * See diff.doc
 * @addtogroup DifferenceEngine
 */

/**
 * @todo document
 * @public
 * @addtogroup DifferenceEngine
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
	/**#@-*/

	/**
	 * Constructor
	 * @param $titleObj Title object that the diff is associated with
	 * @param $old Integer: old ID we want to show and diff with.
	 * @param $new String: either 'prev' or 'next'.
	 * @param $rcid Integer: ??? FIXME (default 0)
	 */
	function DifferenceEngine( $titleObj = null, $old = 0, $new = 0, $rcid = 0 ) {
		$this->mTitle = $titleObj;
		wfDebug("DifferenceEngine old '$old' new '$new' rcid '$rcid'\n");

		if ( 'prev' === $new ) {
			# Show diff between revision $old and the previous one.
			# Get previous one from DB.
			#
			$this->mNewid = intval($old);

			$this->mOldid = $this->mTitle->getPreviousRevisionID( $this->mNewid );

		} elseif ( 'next' === $new ) {
			# Show diff between revision $old and the previous one.
			# Get previous one from DB.
			#
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
		}
		$this->mRcidMarkPatrolled = intval($rcid);  # force it to be an integer
	}

	function showDiffPage( $diffOnly = false ) {
		global $wgUser, $wgOut, $wgUseExternalEditor, $wgUseRCPatrol;
		$fname = 'DifferenceEngine::showDiffPage';
		wfProfileIn( $fname );

	 	# If external diffs are enabled both globally and for the user,
		# we'll use the application/x-external-editor interface to call
		# an external diff tool like kompare, kdiff3, etc.
		if($wgUseExternalEditor && $wgUser->getOption('externaldiff')) {
			global $wgInputEncoding,$wgServer,$wgScript,$wgLang;
			$wgOut->disable();
			header ( "Content-type: application/x-external-editor; charset=".$wgInputEncoding );
			$url1=$this->mTitle->getFullURL("action=raw&oldid=".$this->mOldid);
			$url2=$this->mTitle->getFullURL("action=raw&oldid=".$this->mNewid);
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
		if ( ! $this->loadRevisionData() ) {
			$t = $this->mTitle->getPrefixedText() . " (Diff: {$this->mOldid}, {$this->mNewid})";
			$mtext = wfMsg( 'missingarticle', "<nowiki>$t</nowiki>" );
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addWikitext( $mtext );
			wfProfileOut( $fname );
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
			wfProfileOut( $fname );
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
		$wgOut->setSubtitle( wfMsg( 'difference' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if ( !( $this->mOldPage->userCanRead() && $this->mNewPage->userCanRead() ) ) {
			$wgOut->loginToUse();
			$wgOut->output();
			wfProfileOut( $fname );
			exit;
		}

		$sk = $wgUser->getSkin();

		if ( $this->mNewRev->isCurrent() && $wgUser->isAllowed('rollback') ) {
			$rollback = '&nbsp;&nbsp;&nbsp;' . $sk->generateRollback( $this->mNewRev );
		} else {
			$rollback = '';
		}
		if( $wgUseRCPatrol && $this->mRcidMarkPatrolled != 0 && $wgUser->isAllowed( 'patrol' ) ) {
			$patrol = ' [' . $sk->makeKnownLinkObj( $this->mTitle, wfMsg( 'markaspatrolleddiff' ), "action=markpatrolled&rcid={$this->mRcidMarkPatrolled}" ) . ']';
		} else {
			$patrol = '';
		}

		$prevlink = $sk->makeKnownLinkObj( $this->mTitle, wfMsgHtml( 'previousdiff' ),
			'diff=prev&oldid='.$this->mOldid, '', '', 'id="differences-prevlink"' );
		if ( $this->mNewRev->isCurrent() ) {
			$nextlink = '&nbsp;';
		} else {
			$nextlink = $sk->makeKnownLinkObj( $this->mTitle, wfMsgHtml( 'nextdiff' ),
				'diff=next&oldid='.$this->mNewid, '', '', 'id="differences-nextlink"' );
		}

		$oldminor = '';
		$newminor = '';

		if ($this->mOldRev->mMinorEdit == 1) {
			$oldminor = wfElement( 'span', array( 'class' => 'minor' ),
				wfMsg( 'minoreditletter') ) . ' ';
		}

		if ($this->mNewRev->mMinorEdit == 1) {
			$newminor = wfElement( 'span', array( 'class' => 'minor' ),
			wfMsg( 'minoreditletter') ) . ' ';
		}

		$oldHeader = "<strong>{$this->mOldtitle}</strong><br />" .
			$sk->revUserTools( $this->mOldRev ) . "<br />" .
			$oldminor . $sk->revComment( $this->mOldRev, !$diffOnly ) . "<br />" .
			$prevlink;
		$newHeader = "<strong>{$this->mNewtitle}</strong><br />" .
			$sk->revUserTools( $this->mNewRev ) . " $rollback<br />" .
			$newminor . $sk->revComment( $this->mNewRev, !$diffOnly ) . "<br />" .
			$nextlink . $patrol;

		$this->showDiff( $oldHeader, $newHeader );

		if ( !$diffOnly )
			$this->renderNewRevision();

		wfProfileOut( $fname );
	}

	/**
	 * Show the new revision of the page.
	 */
	function renderNewRevision() {
		global $wgOut;
		$fname = 'DifferenceEngine::renderNewRevision';
		wfProfileIn( $fname );

		$wgOut->addHTML( "<hr /><h2>{$this->mPagetitle}</h2>\n" );
		#add deleted rev tag if needed
		if ( !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
		  	$wgOut->addWikiText( wfMsg( 'rev-deleted-text-permission' ) );
		}

		if( !$this->mNewRev->isCurrent() ) {
			$oldEditSectionSetting = $wgOut->parserOptions()->setEditSection( false );
		}

		$this->loadNewText();
		if( is_object( $this->mNewRev ) ) {
			$wgOut->setRevisionId( $this->mNewRev->getId() );
		}

		$wgOut->addWikiTextTidy( $this->mNewtext );

		if( !$this->mNewRev->isCurrent() ) {
			$wgOut->parserOptions()->setEditSection( $oldEditSectionSetting );
		}

		wfProfileOut( $fname );
	}

	/**
	 * Show the first revision of an article. Uses normal diff headers in
	 * contrast to normal "old revision" display style.
	 */
	function showFirstRevision() {
		global $wgOut, $wgUser;

		$fname = 'DifferenceEngine::showFirstRevision';
		wfProfileIn( $fname );

		# Get article text from the DB
		#
		if ( ! $this->loadNewText() ) {
			$t = $this->mTitle->getPrefixedText() . " (Diff: {$this->mOldid}, " .
			  "{$this->mNewid})";
			$mtext = wfMsg( 'missingarticle', "<nowiki>$t</nowiki>" );
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addWikitext( $mtext );
			wfProfileOut( $fname );
			return;
		}
		if ( $this->mNewRev->isCurrent() ) {
			$wgOut->setArticleFlag( true );
		}

		# Check if user is allowed to look at this page. If not, bail out.
		#
		if ( !( $this->mTitle->userCanRead() ) ) {
			$wgOut->loginToUse();
			$wgOut->output();
			wfProfileOut( $fname );
			exit;
		}

		# Prepare the header box
		#
		$sk = $wgUser->getSkin();

		$nextlink = $sk->makeKnownLinkObj( $this->mTitle, wfMsgHtml( 'nextdiff' ), 'diff=next&oldid='.$this->mNewid, '', '', 'id="differences-nextlink"' );
		$header = "<div class=\"firstrevisionheader\" style=\"text-align: center\"><strong>{$this->mOldtitle}</strong><br />" .
			$sk->revUserTools( $this->mNewRev ) . "<br />" .
			$sk->revComment( $this->mNewRev ) . "<br />" .
			$nextlink . "</div>\n";

		$wgOut->addHTML( $header );

		$wgOut->setSubtitle( wfMsg( 'difference' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		wfProfileOut( $fname );
	}

	/**
	 * Get the diff text, send it to $wgOut
	 * Returns false if the diff could not be generated, otherwise returns true
	 */
	function showDiff( $otitle, $ntitle ) {
		global $wgOut;
		$diff = $this->getDiff( $otitle, $ntitle );
		if ( $diff === false ) {
			$wgOut->addWikitext( wfMsg( 'missingarticle', "<nowiki>(fixme, bug)</nowiki>" ) );
			return false;
		} else {
			$wgOut->addHTML( $diff );
			return true;
		}
	}

	/**
	 * Get diff table, including header
	 * Note that the interface has changed, it's no longer static.
	 * Returns false on error
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
	 * Results are cached
	 * Returns false on error
	 */
	function getDiffBody() {
		global $wgMemc;
		$fname = 'DifferenceEngine::getDiffBody';
		wfProfileIn( $fname );
		
		// Cacheable?
		$key = false;
		if ( $this->mOldid && $this->mNewid ) {
			// Try cache
			$key = wfMemcKey( 'diff', 'oldid', $this->mOldid, 'newid', $this->mNewid );
			$difftext = $wgMemc->get( $key );
			if ( $difftext ) {
				wfIncrStats( 'diff_cache_hit' );
				$difftext = $this->localiseLineNumbers( $difftext );
				$difftext .= "\n<!-- diff cache key $key -->\n";
				wfProfileOut( $fname );
				return $difftext;
			}
		}

		#loadtext is permission safe, this just clears out the diff
		if ( !$this->loadText() ) {
			wfProfileOut( $fname );
			return false;
		} else if ( $this->mOldRev && !$this->mOldRev->userCan(Revision::DELETED_TEXT) ) {
		  return '';
		} else if ( $this->mNewRev && !$this->mNewRev->userCan(Revision::DELETED_TEXT) ) {
		  return '';
		}

		$difftext = $this->generateDiffBody( $this->mOldtext, $this->mNewtext );
		
		// Save to cache for 7 days
		if ( $key !== false && $difftext !== false ) {
			wfIncrStats( 'diff_cache_miss' );
			$wgMemc->set( $key, $difftext, 7*86400 );
		} else {
			wfIncrStats( 'diff_uncacheable' );
		}
		// Replace line numbers with the text in the user's language
		if ( $difftext !== false ) {
			$difftext = $this->localiseLineNumbers( $difftext );
		}
		wfProfileOut( $fname );
		return $difftext;
	}

	/**
	 * Generate a diff, no caching
	 * $otext and $ntext must be already segmented
	 */
	function generateDiffBody( $otext, $ntext ) {
		global $wgExternalDiffEngine, $wgContLang;
		$fname = 'DifferenceEngine::generateDiffBody';

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );
		
		if ( $wgExternalDiffEngine == 'wikidiff' ) {
			# For historical reasons, external diff engine expects
			# input text to be HTML-escaped already
			$otext = htmlspecialchars ( $wgContLang->segmentForDiff( $otext ) );
			$ntext = htmlspecialchars ( $wgContLang->segmentForDiff( $ntext ) );
			if( !function_exists( 'wikidiff_do_diff' ) ) {
				dl('php_wikidiff.so');
			}
			return $wgContLang->unsegementForDiff( wikidiff_do_diff( $otext, $ntext, 2 ) );
		}
		
		if ( $wgExternalDiffEngine == 'wikidiff2' ) {
			# Better external diff engine, the 2 may some day be dropped
			# This one does the escaping and segmenting itself
			if ( !function_exists( 'wikidiff2_do_diff' ) ) {
				wfProfileIn( "$fname-dl" );
				@dl('php_wikidiff2.so');
				wfProfileOut( "$fname-dl" );
			}
			if ( function_exists( 'wikidiff2_do_diff' ) ) {
				wfProfileIn( 'wikidiff2_do_diff' );
				$text = wikidiff2_do_diff( $otext, $ntext, 2 );
				wfProfileOut( 'wikidiff2_do_diff' );
				return $text;
			}
		}
		if ( $wgExternalDiffEngine !== false ) {
			# Diff via the shell
			global $wgTmpDirectory;
			$tempName1 = tempnam( $wgTmpDirectory, 'diff_' );
			$tempName2 = tempnam( $wgTmpDirectory, 'diff_' );

			$tempFile1 = fopen( $tempName1, "w" );
			if ( !$tempFile1 ) {
				wfProfileOut( $fname );
				return false;
			}
			$tempFile2 = fopen( $tempName2, "w" );
			if ( !$tempFile2 ) {
				wfProfileOut( $fname );
				return false;
			}
			fwrite( $tempFile1, $otext );
			fwrite( $tempFile2, $ntext );
			fclose( $tempFile1 );
			fclose( $tempFile2 );
			$cmd = wfEscapeShellArg( $wgExternalDiffEngine, $tempName1, $tempName2 );
			wfProfileIn( "$fname-shellexec" );
			$difftext = wfShellExec( $cmd );
			wfProfileOut( "$fname-shellexec" );
			unlink( $tempName1 );
			unlink( $tempName2 );
			return $difftext;
		}
		
		# Native PHP diff
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
		$diffs = new Diff( $ota, $nta );
		$formatter = new TableDiffFormatter();
		return $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) );
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
		return wfMsgExt( 'lineno', array('parseinline'), $wgLang->formatNum( $matches[1] ) );
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
	function addHeader( $diff, $otitle, $ntitle, $multi = '' ) {
		global $wgOut;
	
		if ( $this->mOldRev && $this->mOldRev->isDeleted(Revision::DELETED_TEXT) ) {
		   $otitle = '<span class="history-deleted">'.$otitle.'</span>';
		}
		if ( $this->mNewRev && $this->mNewRev->isDeleted(Revision::DELETED_TEXT) ) {
		   $ntitle = '<span class="history-deleted">'.$ntitle.'</span>';
		}
		$header = "
			<table border='0' width='98%' cellpadding='0' cellspacing='4' class='diff'>
			<tr>
				<td colspan='2' width='50%' align='center' class='diff-otitle'>{$otitle}</td>
				<td colspan='2' width='50%' align='center' class='diff-ntitle'>{$ntitle}</td>
			</tr>
		";

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
		global $wgLang;
		if ( $this->mRevisionsLoaded ) {
			return true;
		} else {
			// Whether it succeeds or fails, we don't want to try again
			$this->mRevisionsLoaded = true;
		}

		// Load the new revision object
		if( $this->mNewid ) {
			$this->mNewRev = Revision::newFromId( $this->mNewid );
		} else {
			$this->mNewRev = Revision::newFromTitle( $this->mTitle );
		}

		if( is_null( $this->mNewRev ) ) {
			return false;
		}

		// Set assorted variables
		$timestamp = $wgLang->timeanddate( $this->mNewRev->getTimestamp(), true );
		$this->mNewPage = $this->mNewRev->getTitle();
		if( $this->mNewRev->isCurrent() ) {
			$newLink = $this->mNewPage->escapeLocalUrl();
			$this->mPagetitle = htmlspecialchars( wfMsg( 'currentrev' ) );
			$newEdit = $this->mNewPage->escapeLocalUrl( 'action=edit' );

			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a> ($timestamp)"
				. " (<a href='$newEdit'>" . htmlspecialchars( wfMsg( 'editold' ) ) . "</a>)";

		} else {
			$newLink = $this->mNewPage->escapeLocalUrl( 'oldid=' . $this->mNewid );
			$newEdit = $this->mNewPage->escapeLocalUrl( 'action=edit&oldid=' . $this->mNewid );
			$this->mPagetitle = htmlspecialchars( wfMsg( 'revisionasof', $timestamp ) );

			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a>"
				. " (<a href='$newEdit'>" . htmlspecialchars( wfMsg( 'editold' ) ) . "</a>)";
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
			$oldLink = $this->mOldPage->escapeLocalUrl( 'oldid=' . $this->mOldid );
			$oldEdit = $this->mOldPage->escapeLocalUrl( 'action=edit&oldid=' . $this->mOldid );
			$this->mOldtitle = "<a href='$oldLink'>" . htmlspecialchars( wfMsg( 'revisionasof', $t ) )
				. "</a> (<a href='$oldEdit'>" . htmlspecialchars( wfMsg( 'editold' ) ) . "</a>)";
			//now that we considered old rev, we can make undo link (bug 8133, multi-edit undo)
			$newUndo = $this->mNewPage->escapeLocalUrl( 'action=edit&undoafter=' . $this->mOldid . '&undoto=' . $this->mNewid);
			$this->mNewtitle .= " (<a href='$newUndo'>" . htmlspecialchars( wfMsg( 'editundo' ) ) . "</a>)";
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
			// FIXME: permission tests
			$this->mOldtext = $this->mOldRev->revText();
			if ( $this->mOldtext === false ) {
				return false;
			}
		}
		if ( $this->mNewRev ) {
			$this->mNewtext = $this->mNewRev->revText();
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
		$this->mNewtext = $this->mNewRev->getText();
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
 * @addtogroup DifferenceEngine
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
 * @addtogroup DifferenceEngine
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
 * @addtogroup DifferenceEngine
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
 * @addtogroup DifferenceEngine
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
 * @addtogroup DifferenceEngine
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
 *
 * @author Geoffrey T. Dairiki, Tim Starling
 * @private
 * @addtogroup DifferenceEngine
 */
class _DiffEngine
{
	const MAX_XREF_LENGTH =  10000;

	function diff ($from_lines, $to_lines) {
		$fname = '_DiffEngine::diff';
		wfProfileIn( $fname );

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

		// Merge edits when possible
		$this->_shift_boundaries($from_lines, $this->xchanged, $this->ychanged);
		$this->_shift_boundaries($to_lines, $this->ychanged, $this->xchanged);

		// Compute the edit operations.
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
		wfProfileOut( $fname );
		return $edits;
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
		$fname = '_DiffEngine::_diag';
		wfProfileIn( $fname );
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
			wfProfileIn( "$fname-chunk" );
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
			wfProfileOut( "$fname-chunk" );
		}

		$seps[] = $flip ? array($yoff, $xoff) : array($xoff, $yoff);
		$ymid = $ymids[$this->lcs];
		for ($n = 0; $n < $nchunks - 1; $n++) {
			$x1 = $xoff + (int)(($numer + ($xlim - $xoff) * $n) / $nchunks);
			$y1 = $ymid[$n] + 1;
			$seps[] = $flip ? array($y1, $x1) : array($x1, $y1);
		}
		$seps[] = $flip ? array($ylim, $xlim) : array($xlim, $ylim);

		wfProfileOut( $fname );
		return array($this->lcs, $seps);
	}

	function _lcs_pos ($ypos) {
		$fname = '_DiffEngine::_lcs_pos';
		wfProfileIn( $fname );

		$end = $this->lcs;
		if ($end == 0 || $ypos > $this->seq[$end]) {
			$this->seq[++$this->lcs] = $ypos;
			$this->in_seq[$ypos] = 1;
			wfProfileOut( $fname );
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
		wfProfileOut( $fname );
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
		$fname = '_DiffEngine::_compareseq';
		wfProfileIn( $fname );

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
		wfProfileOut( $fname );
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
		$fname = '_DiffEngine::_shift_boundaries';
		wfProfileIn( $fname );
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
		wfProfileOut( $fname );
	}
}

/**
 * Class representing a 'diff' between two sequences of strings.
 * @todo document
 * @private
 * @addtogroup DifferenceEngine
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
		$fname = 'Diff::_check';
		wfProfileIn( $fname );
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
		wfProfileOut( $fname );
	}
}

/**
 * FIXME: bad name.
 * @todo document
 * @private
 * @addtogroup DifferenceEngine
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
		$fname = 'MappedDiff::MappedDiff';
		wfProfileIn( $fname );

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
		wfProfileOut( $fname );
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
 * @addtogroup DifferenceEngine
 */
class DiffFormatter
{
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
		$fname = 'DiffFormatter::format';
		wfProfileIn( $fname );

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
		wfProfileOut( $fname );
		return $end;
	}

	function _block($xbeg, $xlen, $ybeg, $ylen, &$edits) {
		$fname = 'DiffFormatter::_block';
		wfProfileIn( $fname );
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
		wfProfileOut( $fname );
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
		echo $header;
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
 *	Additions by Axel Boldt follow, partly taken from diff.php, phpwiki-1.3.3
 *
 */

define('NBSP', '&#160;');			// iso-8859-x non-breaking space.

/**
 * @todo document
 * @private
 * @addtogroup DifferenceEngine
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
				$this->_line .= '<ins class="diffchange">' .
					htmlspecialchars ( $this->_group ) . '</ins>';
			elseif ($this->_tag == 'del')
				$this->_line .= '<del class="diffchange">' .
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
 * @addtogroup DifferenceEngine
 */
class WordLevelDiff extends MappedDiff
{
	const MAX_LINE_LENGTH = 10000;

	function WordLevelDiff ($orig_lines, $closing_lines) {
		$fname = 'WordLevelDiff::WordLevelDiff';
		wfProfileIn( $fname );

		list ($orig_words, $orig_stripped) = $this->_split($orig_lines);
		list ($closing_words, $closing_stripped) = $this->_split($closing_lines);

		$this->MappedDiff($orig_words, $closing_words,
						  $orig_stripped, $closing_stripped);
		wfProfileOut( $fname );
	}

	function _split($lines) {
		$fname = 'WordLevelDiff::_split';
		wfProfileIn( $fname );

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
		wfProfileOut( $fname );
		return array($words, $stripped);
	}

	function orig () {
		$fname = 'WordLevelDiff::orig';
		wfProfileIn( $fname );
		$orig = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
				$orig->addWords($edit->orig);
			elseif ($edit->orig)
				$orig->addWords($edit->orig, 'del');
		}
		$lines = $orig->getLines();
		wfProfileOut( $fname );
		return $lines;
	}

	function closing () {
		$fname = 'WordLevelDiff::closing';
		wfProfileIn( $fname );
		$closing = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
				$closing->addWords($edit->closing);
			elseif ($edit->closing)
				$closing->addWords($edit->closing, 'ins');
		}
		$lines = $closing->getLines();
		wfProfileOut( $fname );
		return $lines;
	}
}

/**
 *	Wikipedia Table style diff formatter.
 * @todo document
 * @private
 * @addtogroup DifferenceEngine
 */
class TableDiffFormatter extends DiffFormatter
{
	function TableDiffFormatter() {
		$this->leading_context_lines = 2;
		$this->trailing_context_lines = 2;
	}

	function _block_header( $xbeg, $xlen, $ybeg, $ylen ) {
		$r = '<tr><td colspan="2" align="left"><strong><!--LINE '.$xbeg."--></strong></td>\n" .
		  '<td colspan="2" align="left"><strong><!--LINE '.$ybeg."--></strong></td></tr>\n";
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
		return "<td>+</td><td class='diff-addedline'>{$line}</td>";
	}

	# HTML-escape parameter before calling this
	function deletedLine( $line ) {
		return "<td>-</td><td class='diff-deletedline'>{$line}</td>";
	}

	# HTML-escape parameter before calling this
	function contextLine( $line ) {
		return "<td> </td><td class='diff-context'>{$line}</td>";
	}

	function emptyLine() {
		return '<td colspan="2">&nbsp;</td>';
	}

	function _added( $lines ) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->emptyLine() .
				$this->addedLine( htmlspecialchars ( $line ) ) . "</tr>\n";
		}
	}

	function _deleted($lines) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->deletedLine( htmlspecialchars ( $line ) ) .
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
		$fname = 'TableDiffFormatter::_changed';
		wfProfileIn( $fname );

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
		wfProfileOut( $fname );
	}
}

?>
