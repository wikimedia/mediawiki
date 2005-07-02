<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

class contribs_finder {
	var $username, $offset, $limit;
	var $dbr;

	function contribs_finder($username) {
		$this->username = $username;
		$this->dbr =& wfGetDB(DB_SLAVE);
	}

	function set_limit($limit) {
		$this->limit = $limit;
	}

	function set_offset($offset) {
		$this->offset = $offset;
	}

	function get_edit_limits() {
		list($index, $usercond) = $this->get_user_cond();

		$use_index = $this->dbr->useIndexClause($index);
		extract($this->dbr->tableNames('revision'));
		$sql =	"SELECT MIN(rev_timestamp) as earliest, MAX(rev_timestamp) as latest " .
			"FROM $revision $use_index WHERE " . $usercond;

		$res = $this->dbr->query($sql, "contribs_finder::get_edit_limits");
		$rows = array();
		while ($o = $this->dbr->fetchObject($res))
			$rows[] = $o;
		$row = $rows[count($rows) - 1];
		return array($row->earliest, $row->latest);
	}

	function get_user_cond() {
		$condition = "";

		if ($this->username == 'newbies') {
			$max = $this->dbr->selectField('user', 'max(user_id)', false, "make_sql");
			$condition = '>' . ($max - $max / 100);
		}

		if ($condition == "") {
			$condition = " rev_user_text=" . $this->dbr->addQuotes($this->username);
			$index = 'usertext_timestamp';
		} else {
			$condition = " rev_user {$condition}";
			$index = 'user_timestamp';
		}

		return array($index, $condition);
	}

	function get_previous_offset_for_paging() {
		list($index, $usercond) = $this->get_user_cond();
		$use_index = $this->dbr->useIndexClause($index);
		extract($this->dbr->tableNames('page', 'revision'));

		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND rev_timestamp > '" . $this->offset . "' AND " .
			"rev_user_text = " . $this->dbr->addQuotes($this->username);
		$sql .=	" ORDER BY rev_timestamp ASC LIMIT " . $this->limit;
		$res = $this->dbr->query($sql);
		$rows = array();
		while ($obj = $this->dbr->fetchObject($res))
			$rows[] = $obj;
		$this->dbr->freeResult($res);
		return $rows[count($rows) - 1]->rev_timestamp;
	}

	function get_first_offset_for_paging() {
		list($index, $usercond) = $this->get_user_cond();
		$use_index = $this->dbr->useIndexClause($index);
		extract($this->dbr->tableNames('page', 'revision'));

		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND " .
			"rev_user_text = " . $this->dbr->addQuotes($this->username);
		$sql .=	" ORDER BY rev_timestamp ASC LIMIT " . ($this->limit + 1);
		$res = $this->dbr->query($sql);
		$rows = array();
		while ($obj = $this->dbr->fetchObject($res))
			$rows[] = $obj;
		$this->dbr->freeResult($res);
		return $rows[count($rows) - 1]->rev_timestamp;
	}

	/* private */ function make_sql() {
		$userCond = $condition = $index = $offsetQuery = $limitQuery = "";

		extract($this->dbr->tableNames('page', 'revision'));
		list($index, $userCond) = $this->get_user_cond();

		$limitQuery = "LIMIT {$this->limit}";
		if ($this->offset)
			$offsetQuery = "AND rev_timestamp < '{$this->offset}'";

		$use_index = $this->dbr->useIndexClause($index);
		$sql = "SELECT
			page_namespace,page_title,page_is_new,page_latest,
			rev_id,rev_timestamp,rev_comment,rev_minor_edit,rev_user_text,
			rev_deleted
			FROM $page,$revision $use_index
			WHERE page_id=rev_page AND $userCond $offsetQuery
		 	ORDER BY rev_timestamp DESC $limitQuery";
		return $sql;
	}

	function find() {
		$contribs = array();
		$res = $this->dbr->query($this->make_sql(), "contribs_finder::find");
		while ($c = $this->dbr->fetchObject($res))
			$contribs[] = $c;
		$this->dbr->freeResult($res);
		return $contribs;
	}
};

/**
 * Special page "user contributions".
 * Shows a list of the contributions of a user.
 *
 * @return	none
 * @param	string	$par	(optional) user name of the user for which to show the contributions
 */
function wfSpecialContributions( $par = null ) {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgRequest, $wgTitle;
	$fname = 'wfSpecialContributions';

	$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
	if (!strlen($target)) {
		$wgOut->errorpage('notargettitle', 'notargettext');
		return;
	}

	$nt = Title::newFromURL( $target );
	if (!$nt) {
		$wgOut->errorpage( 'notargettitle', 'notargettext' );
		return;
	}
	$nt =& Title::makeTitle(NS_USER, $nt->getDBkey());

	$limit = min($wgRequest->getInt('limit', 50), 500);
	$offset = $wgRequest->getVal('offset');
	/* Offset must be an integral. */
	if (!strlen($offset) || !preg_match("/^[0-9]+$/", $offset))
		$offset = 0;

	$title = Title::makeTitle(NS_SPECIAL, "Contributions");
	$urlbits = "target=" . wfUrlEncode($target);
	$myurl = $title->escapeLocalURL($urlbits);

	$finder = new contribs_finder($nt->getText());

	$finder->set_limit($limit);
	$finder->set_offset($offset);

	if ($wgRequest->getText('go') == "prev") {
		$prevts = $finder->get_previous_offset_for_paging();
		$prevurl = $title->getLocalURL($urlbits . "&offset=$prevts&limit=$limit");
		$wgOut->redirect($prevurl);
		return;
	}

	if ($wgRequest->getText('go') == "first") {
		$prevts = $finder->get_first_offset_for_paging();
		$prevurl = $title->getLocalURL($urlbits . "&offset=$prevts&limit=$limit");
		$wgOut->redirect($prevurl);
		return;
	}

	$sk = $wgUser->getSkin();

	$id = User::idFromName($nt->getText());

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
		$userCond = '=' . $id;
	}
	$talk = $nt->getTalkPage();
	if( $talk ) {
		$ul .= ' (' . $sk->makeLinkObj( $talk, $wgLang->getNsText( NS_TALK ) ) . ')';
	}

	if ($target == 'newbies') {
		$ul = wfMsg ('newbies');
	}

	$wgOut->setSubtitle( wfMsgHtml( 'contribsub', $ul ) );

	$contribsPage = Title::makeTitle( NS_SPECIAL, 'Contributions' );
	$contribs = $finder->find();

	if (count($contribs) == 0) {
		$wgOut->addWikiText( wfMsg( "nocontribs" ) );
		return;
	}

	list($early, $late) = $finder->get_edit_limits();
	$lastts = count($contribs) ? $contribs[count($contribs) - 1]->rev_timestamp : 0;
	$atstart = (!count($contribs) || $late == $contribs[0]->rev_timestamp);
	$atend = (!count($contribs) || $early == $lastts);

	$lasturl = $wgTitle->escapeLocalURL("action=history&limit={$limit}");

	$firsttext = wfMsgHtml("histfirst");
	$lasttext = wfMsgHtml("histlast");

	$prevtext = wfMsg("prevn", $limit);
	if ($atstart) {
		$lastlink = $lasttext;
		$prevlink = $prevtext;
	} else {
		$lastlink = "<a href=\"$myurl&amp;limit=$limit\">$lasttext</a>";
		$prevlink = "<a href=\"$myurl&amp;offset=$offset&amp;limit=$limit&amp;go=prev\">$prevtext</a>";
	}

	$nexttext = wfMsg("nextn", $limit);
	if ($atend) {
		$firstlink = $firsttext;
		$nextlink = $nexttext;
	} else {
		$firstlink = "<a href=\"$myurl&amp;limit=$limit&amp;go=first\">$firsttext</a>";
		$nextlink = "<a href=\"$myurl&amp;offset=$lastts&amp;limit=$limit\">$nexttext</a>";
	}
	$firstlast = "($lastlink | $firstlink)";

	$urls = array();
	foreach (array(20, 50, 100, 250, 500) as $num)
		$urls[] = "<a href=\"$myurl&offset=$offset&limit={$num}\">".$wgLang->formatNum($num)."</a>";
	$bits = implode($urls, ' | ');

	$prevnextbits = "$firstlast " . wfMsgHtml("viewprevnext", $prevlink, $nextlink, $bits);

	$wgOut->addHTML( "<br />{$prevnextbits}</p>\n");

	$wgOut->addHTML( "<ul>\n" );

	foreach ($contribs as $contrib)
		$wgOut->addHTML(ucListEdit($sk, $contrib));

	$wgOut->addHTML( "</ul>\n" );
	$wgOut->addHTML( "<br />{$prevnextbits}\n");
}


/**
 * Generates each row in the contributions list.
 *
 * Contributions which are marked "top" are currently on top of the history.
 * For these contributions, a [rollback] link is shown for users with sysop
 * privileges. The rollback link restores the most recent version that was not
 * written by the target user.
 *
 * If the contributions page is called with the parameter &bot=1, all rollback
 * links also get that parameter. It causes the edit itself and the rollback
 * to be marked as "bot" edits. Bot edits are hidden by default from recent
 * changes, so this allows sysops to combat a busy vandal without bothering
 * other users.
 *
 * @todo This would probably look a lot nicer in a table.
 */
function ucListEdit( $sk, $row ) {
	$fname = 'ucListEdit';
	wfProfileIn( $fname );

	global $wgLang, $wgOut, $wgUser, $wgRequest;
	static $messages;
	if( !isset( $messages ) ) {
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg ) {
			$messages[$msg] = wfMsg( $msg );
		}
	}

	$page =& Title::makeTitle( $row->page_namespace, $row->page_title );
	$link = $sk->makeKnownLinkObj( $page, '' );
	$difftext = $topmarktext = '';
	if( $row->rev_id == $row->page_latest ) {
		$topmarktext .= '<strong>' . $messages['uctop'] . '</strong>';
		if( !$row->page_is_new ) {
			$difftext .= $sk->makeKnownLinkObj( $page, '(' . $messages['diff'] . ')', 'diff=0' );
		} else {
			$difftext .= $messages['newarticle'];
		}

		if( $wgUser->isAllowed('rollback') ) {
			$extraRollback = $wgRequest->getBool( 'bot' ) ? '&bot=1' : '';
			$extraRollback .= '&token=' . urlencode(
				$wgUser->editToken( array( $page->getPrefixedText(), $row->rev_user_text ) ) );
			$topmarktext .= ' ['. $sk->makeKnownLinkObj( $page,
			  	$messages['rollbacklink'],
			  	'action=rollback&from=' . urlencode( $row->rev_user_text ) . $extraRollback ) .']';
		}

	}
	if( $row->rev_deleted && !$wgUser->isAllowed( 'undelete' ) ) {
		$difftext = '(' . $messages['diff'] . ')';
	} else {
		$difftext = $sk->makeKnownLinkObj( $page, '(' . $messages['diff'].')', 'diff=prev&oldid='.$row->rev_id );
	}
	$histlink='('.$sk->makeKnownLinkObj( $page, $messages['hist'], 'action=history' ) . ')';

	$comment = $sk->commentBlock( $row->rev_comment, $page );
	$d = $wgLang->timeanddate( $row->rev_timestamp, true );

	if( $row->rev_minor_edit ) {
		$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
	} else {
		$mflag = '';
	}

	$ret = "{$d} {$histlink} {$difftext} {$mflag} {$link} {$comment} {$topmarktext}";
	if( $row->rev_deleted ) {
		$ret = '<span class="deleted">' . $ret . '</span> ' . htmlspecialchars( wfMsg( 'deletedrev' ) );
	}
	$ret = "<li>$ret</li>\n";
	wfProfileOut( $fname );
	return $ret;
}

?>
