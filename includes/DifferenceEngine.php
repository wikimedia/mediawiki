<?php
# See diff.doc

class DifferenceEngine {
	/* private */ var $mOldid, $mNewid;
	/* private */ var $mOldtitle, $mNewtitle, $mPagetitle;
	/* private */ var $mOldtext, $mNewtext;
	/* private */ var $mOldUser, $mNewUser;
	/* private */ var $mOldComment, $mNewComment;
	/* private */ var $mOldPage, $mNewPage;
	/* private */ var $mRcidMarkPatrolled;

	function DifferenceEngine( $old, $new, $rcid = 0 )
	{
		global $wgTitle;
		if ( 'prev' == $new ) {
			$this->mNewid = intval($old);
			$dbr =& wfGetDB( DB_SLAVE );
			$this->mOldid = $dbr->selectField( 'old', 'old_id',  
				"old_title='" . $wgTitle->getDBkey() . "'" .
				' AND old_namespace=' . $wgTitle->getNamespace() .
				" AND old_id<{$this->mNewid} order by old_id desc" );

		} else {
			$this->mOldid = intval($old);
			$this->mNewid = intval($new);
		}
		$this->mRcidMarkPatrolled = intval($rcid);  # force it to be an integer
	}

	function showDiffPage()
	{
		global $wgUser, $wgTitle, $wgOut, $wgLang, $wgOnlySysopsCanPatrol, $wgUseRCPatrol;
		$fname = 'DifferenceEngine::showDiffPage';
		wfProfileIn( $fname );

		$t = $wgTitle->getPrefixedText() . " (Diff: {$this->mOldid}, " .
		  "{$this->mNewid})";
		$mtext = wfMsg( 'missingarticle', $t );

		$wgOut->setArticleFlag( false );
		if ( ! $this->loadText() ) {
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->addHTML( $mtext );
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
		$wgOut->setRobotpolicy( 'noindex,follow' );

		if ( !( $this->mOldPage->userCanRead() && $this->mNewPage->userCanRead() ) ) {
			$wgOut->loginToUse();
			$wgOut->output();
			wfProfileOut( $fname );
			exit;
		}

		$sk = $wgUser->getSkin();
		$talk = $wgLang->getNsText( NS_TALK );
		$contribs = wfMsg( 'contribslink' );

		$this->mOldComment = $sk->formatComment($this->mOldComment);
		$this->mNewComment = $sk->formatComment($this->mNewComment);

		$oldUserLink = $sk->makeLinkObj( Title::makeTitleSafe( NS_USER, $this->mOldUser ), $this->mOldUser );
		$newUserLink = $sk->makeLinkObj( Title::makeTitleSafe( NS_USER, $this->mNewUser ), $this->mNewUser );
		$oldUTLink = $sk->makeLinkObj( Title::makeTitleSafe( NS_USER_TALK, $this->mOldUser ), $talk );
		$newUTLink = $sk->makeLinkObj( Title::makeTitleSafe( NS_USER_TALK, $this->mNewUser ), $talk );
		$oldContribs = $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Contributions' ), $contribs,
			'target=' . urlencode($this->mOldUser) );
		$newContribs = $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Contributions' ), $contribs,
			'target=' . urlencode($this->mNewUser) );
		if ( !$this->mNewid && $wgUser->isSysop() ) {
			$rollback = '&nbsp;&nbsp;&nbsp;<strong>[' . $sk->makeKnownLinkObj( $wgTitle, wfMsg( 'rollbacklink' ),
				'action=rollback&from=' . urlencode($this->mNewUser) ) . ']</strong>';
		} else {
			$rollback = '';
		}
		if ( $wgUseRCPatrol && $this->mRcidMarkPatrolled != 0 && $wgUser->getID() != 0 &&
		     ( $wgUser->isSysop() || !$wgOnlySysopsCanPatrol ) )
		{
			$patrol = ' [' . $sk->makeKnownLinkObj( $wgTitle, wfMsg( 'markaspatrolleddiff' ),
				"action=markpatrolled&rcid={$this->mRcidMarkPatrolled}" ) . ']';
		} else {
			$patrol = '';
		}

		$oldHeader = "<strong>{$this->mOldtitle}</strong><br />$oldUserLink " .
			"($oldUTLink | $oldContribs)<br />" . $this->mOldComment;
		$newHeader = "<strong>{$this->mNewtitle}</strong><br />$newUserLink " .
			"($newUTLink | $newContribs) $rollback<br />" . $this->mNewComment . $patrol;

		DifferenceEngine::showDiff( $this->mOldtext, $this->mNewtext,
		  $oldHeader, $newHeader );
		$wgOut->addHTML( "<hr /><h2>{$this->mPagetitle}</h2>\n" );
		$wgOut->addWikiText( $this->mNewtext );

		wfProfileOut( $fname );
	}

	function showDiff( $otext, $ntext, $otitle, $ntitle )
	{
		global $wgOut, $wgUseExternalDiffEngine;

		$otext = str_replace( "\r\n", "\n", $otext );
		$ntext = str_replace( "\r\n", "\n", $ntext );


			$wgOut->addHTML( "<table border='0' width='98%'
cellpadding='0' cellspacing='4px' class='diff'><tr>
<td colspan='2' width='50%' align='center' class='diff-otitle'>
{$otitle}</td>
<td colspan='2' width='50%' align='center' class='diff-ntitle'>
{$ntitle}</td>
</tr>\n" );
		if ( $wgUseExternalDiffEngine ) {
			dl('php_wikidiff.so');
			$wgOut->addHTML( wikidiff_do_diff( $otext, $ntext, 2) );
		} else {
			$ota = explode( "\n", $otext);
			$nta = explode( "\n", $ntext);
			$diffs = new Diff( $ota, $nta );
			$formatter = new TableDiffFormatter();
			$formatter->format( $diffs );
		}
		$wgOut->addHTML( "</table>\n" );
	}

	# Load the text of the articles to compare.  If newid is 0, then compare
	# the old article in oldid to the current article; if oldid is 0, then
	# compare the current article to the immediately previous one (ignoring
	# the value of newid).
	#
	function loadText()
	{
		global $wgTitle, $wgOut, $wgLang;
		$fname = 'DifferenceEngine::loadText';

		$dbr =& wfGetDB( DB_SLAVE );
		if ( 0 == $this->mNewid || 0 == $this->mOldid ) {
			$wgOut->setArticleFlag( true );
			$newLink = $wgTitle->getLocalUrl();
			$this->mPagetitle = wfMsg( 'currentrev' );
			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a>";
			$id = $wgTitle->getArticleID();

			$s = $dbr->getArray( 'cur', array( 'cur_text', 'cur_user_text', 'cur_comment' ),
				array( 'cur_id' => $id ), $fname );
			if ( $s === false ) {
				return false;
			}

			$this->mNewPage = &$wgTitle;
			$this->mNewtext = $s->cur_text;
			$this->mNewUser = $s->cur_user_text;
			$this->mNewComment = $s->cur_comment;
		} else {
			$s = $dbr->getArray( 'old', array( 'old_namespace','old_title','old_timestamp', 'old_text',
				'old_flags','old_user_text','old_comment' ), array( 'old_id' => $this->mNewid ), $fname );

			if ( $s === false ) {
				return false;
			}

			$this->mNewtext = Article::getRevisionText( $s );

			$t = $wgLang->timeanddate( $s->old_timestamp, true );
			$this->mNewPage = Title::MakeTitle( $s->old_namespace, $s->old_title );
			$newLink = $wgTitle->getLocalUrl ('oldid=' . $this->mNewid);
			$this->mPagetitle = wfMsg( 'revisionasof', $t );
			$this->mNewtitle = "<a href='$newLink'>{$this->mPagetitle}</a>";
			$this->mNewUser = $s->old_user_text;
			$this->mNewComment = $s->old_comment;
		}
		if ( 0 == $this->mOldid ) {
			$s = $dbr->getArray( 'old',
				array( 'old_namespace','old_title','old_timestamp','old_text', 'old_flags','old_user_text','old_comment' ),
				array( /* WHERE */
					'old_namespace' => $this->mNewPage->getNamespace(),
					'old_title' => $this->mNewPage->getDBkey()
				), $fname, array( 'ORDER BY' => 'inverse_timestamp', 'USE INDEX' => 'name_title_timestamp' )
			);
		} else {
			$s = $dbr->getArray( 'old',
				array( 'old_namespace','old_title','old_timestamp','old_text','old_flags','old_user_text','old_comment'),
				array( 'old_id' => $this->mOldid ),
				$fname
			);
		}
		if ( $s === false ) {
			return false;
		}
		$this->mOldPage = Title::MakeTitle( $s->old_namespace, $s->old_title );
		$this->mOldtext = Article::getRevisionText( $s );

		$t = $wgLang->timeanddate( $s->old_timestamp, true );
		$oldLink = $this->mOldPage->getLocalUrl ('oldid=' . $this->mOldid);
		$this->mOldtitle = "<a href='$oldLink'>" . wfMsg( 'revisionasof', $t ) . '</a>';
		$this->mOldUser = $s->old_user_text;
		$this->mOldComment = $s->old_comment;

		return true;
	}
}

// A PHP diff engine for phpwiki. (Taken from phpwiki-1.3.3)
//
// Copyright (C) 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
// You may copy this code freely under the conditions of the GPL.
//

define('USE_ASSERTS', function_exists('assert'));

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
 * @author Geoffrey T. Dairiki
 * @access private
 */
class _DiffEngine
{
	function diff ($from_lines, $to_lines) {
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
			if ($from_lines[$skip] != $to_lines[$skip])
				break;
			$this->xchanged[$skip] = $this->ychanged[$skip] = false;
		}
		// Skip trailing common lines.
		$xi = $n_from; $yi = $n_to;
		for ($endskip = 0; --$xi > $skip && --$yi > $skip; $endskip++) {
			if ($from_lines[$xi] != $to_lines[$yi])
				break;
			$this->xchanged[$xi] = $this->ychanged[$yi] = false;
		}

		// Ignore lines which do not exist in both files.
		for ($xi = $skip; $xi < $n_from - $endskip; $xi++)
			$xhash[$from_lines[$xi]] = 1;
		for ($yi = $skip; $yi < $n_to - $endskip; $yi++) {
			$line = $to_lines[$yi];
			if ( ($this->ychanged[$yi] = empty($xhash[$line])) )
				continue;
			$yhash[$line] = 1;
			$this->yv[] = $line;
			$this->yind[] = $yi;
		}
		for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
			$line = $from_lines[$xi];
			if ( ($this->xchanged[$xi] = empty($yhash[$line])) )
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
		return $edits;
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
		while (list ($junk, $y) = each($matches)) {
			if ($y > $this->seq[$k-1]) {
			USE_ASSERTS && assert($y < $this->seq[$k]);
			// Optimization: this is a common case:
			//	next match is just replacing previous match.
			$this->in_seq[$this->seq[$k]] = false;
			$this->seq[$k] = $y;
			$this->in_seq[$y] = 1;
					}
			else if (empty($this->in_seq[$y])) {
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
		}
	else {
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
	}
}

/**
 * Class representing a 'diff' between two sequences of strings.
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
	}
}

/**
 * FIXME: bad name.
 */
class MappedDiff
extends Diff
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
	}
}

/**
 * A class to format Diffs
 *
 * This class formats the diff in classic diff format.
 * It is intended that this class be customized via inheritance,
 * to obtain fancier outputs.
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

		return $this->_end_diff();
	}

	function _block($xbeg, $xlen, $ybeg, $ylen, &$edits) {
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

class _HWLDF_WordAccumulator {
	function _HWLDF_WordAccumulator () {
		$this->_lines = array();
		$this->_line = '';
		$this->_group = '';
		$this->_tag = '';
	}

	function _flushGroup ($new_tag) {
		if ($this->_group !== '') {
			if ($this->_tag == 'mark')
				$this->_line .= '<span class="diffchange">'.htmlspecialchars ( $this->_group ).'</span>';
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

class WordLevelDiff extends MappedDiff
{
	function WordLevelDiff ($orig_lines, $closing_lines) {
		list ($orig_words, $orig_stripped) = $this->_split($orig_lines);
		list ($closing_words, $closing_stripped) = $this->_split($closing_lines);


		$this->MappedDiff($orig_words, $closing_words,
						  $orig_stripped, $closing_stripped);
	}

	function _split($lines) {
		if (!preg_match_all('/ ( [^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | . ) (?: (?!< \n) [^\S\n])? /xs',
							implode("\n", $lines),
							$m)) {
			return array(array(''), array(''));
		}
		return array($m[0], $m[1]);
	}

	function orig () {
		$orig = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
				$orig->addWords($edit->orig);
			elseif ($edit->orig)
				$orig->addWords($edit->orig, 'mark');
		}
		return $orig->getLines();
	}

	function closing () {
		$closing = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
				$closing->addWords($edit->closing);
			elseif ($edit->closing)
				$closing->addWords($edit->closing, 'mark');
		}
		return $closing->getLines();
	}
}

/**
 *	Wikipedia Table style diff formatter.
 *
 */
class TableDiffFormatter extends DiffFormatter
{
	function TableDiffFormatter() {
		$this->leading_context_lines = 2;
		$this->trailing_context_lines = 2;
	}

	function _block_header( $xbeg, $xlen, $ybeg, $ylen ) {
		$l1 = wfMsg( 'lineno', $xbeg );
		$l2 = wfMsg( 'lineno', $ybeg );

		$r = '<tr><td colspan="2" align="left"><strong>'.$l1."</strong></td>\n" .
			'<td colspan="2" align="left"><strong>'.$l2."</strong></td></tr>\n";
		return $r;
	}

	function _start_block( $header ) {
		global $wgOut;
		$wgOut->addHTML( $header );
	}

	function _end_block() {
	}

	function _lines( $lines, $prefix=' ', $color='white' ) {
	}

	function addedLine( $line ) {
		return '<td>+</td><td class="diff-addedline">' .
			$line . '</td>';
	}

	function deletedLine( $line ) {
		return '<td>-</td><td class="diff-deletedline">' .
			$line . '</td>';
	}

	function emptyLine() {
		return '<td colspan="2">&nbsp;</td>';
	}

	function contextLine( $line ) {
		return '<td> </td><td class="diff-context">' .
			htmlspecialchars ( $line ) . '</td>';
	}

	function _added($lines) {
		global $wgOut;
		foreach ($lines as $line) {
			$wgOut->addHTML( '<tr>' . $this->emptyLine() .
			  $this->addedLine( htmlspecialchars ( $line ) ) . "</tr>\n" );
		}
	}

	function _deleted($lines) {
		global $wgOut;
		foreach ($lines as $line) {
			$wgOut->addHTML( '<tr>' . $this->deletedLine( htmlspecialchars ( $line ) ) .
			  $this->emptyLine() . "</tr>\n" );
		}
	}

	function _context( $lines ) {
		global $wgOut;
		foreach ($lines as $line) {
			$wgOut->addHTML( '<tr>' . $this->contextLine( $line ) .
			  $this->contextLine( $line ) . "</tr>\n" );
		}
	}

	function _changed( $orig, $closing ) {
		global $wgOut;
		$diff = new WordLevelDiff( $orig, $closing );
		$del = $diff->orig();
		$add = $diff->closing();

		while ( $line = array_shift( $del ) ) {
			$aline = array_shift( $add );
			$wgOut->addHTML( '<tr>' . $this->deletedLine( $line ) .
			  $this->addedLine( $aline ) . "</tr>\n" );
		}
		$this->_added( $add ); # If any leftovers
	}
}

?>
