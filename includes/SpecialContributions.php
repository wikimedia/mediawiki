<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** @package MediaWiki */
class contribs_finder {
	var $username, $offset, $limit, $namespace;
	var $dbr;

	function contribs_finder($username) {
		$this->username = $username;
		$this->namespace = false;
		$this->dbr =& wfGetDB(DB_SLAVE);
	}

	function set_namespace($ns) {
		$this->namespace = $ns;
	}

	function set_limit($limit) {
		$this->limit = $limit;
	}

	function set_offset($offset) {
		$this->offset = $offset;
	}

	function get_edit_limit($dir) {
		list($index, $usercond) = $this->get_user_cond();
		$nscond = $this->get_namespace_cond();
		$use_index = $this->dbr->useIndexClause($index);
		extract($this->dbr->tableNames('revision', 'page'));
		$sql =	"SELECT rev_timestamp " .
			" FROM $page,$revision $use_index " .
			" WHERE rev_page=page_id AND $usercond $nscond" .
			" ORDER BY rev_timestamp $dir LIMIT 1";

		$res = $this->dbr->query($sql, "contribs_finder::get_edit_limit");
		while ($o = $this->dbr->fetchObject($res))
			$row = $o;
		return $row->rev_timestamp;
	}

	function get_edit_limits() {
		return array(
			$this->get_edit_limit("ASC"),
			$this->get_edit_limit("DESC")
		);
	}

	function get_user_cond() {
		$condition = '';

		if ($this->username == 'newbies') {
			$max = $this->dbr->selectField('user', 'max(user_id)', false, 'make_sql');
			$condition = '>' . (int)($max - $max / 100);
		}

		if ($condition == '') {
			$condition = ' rev_user_text=' . $this->dbr->addQuotes($this->username);
			$index = 'usertext_timestamp';
		} else {
			$condition = ' rev_user '.$condition ;
			$index = 'user_timestamp';
		}
		return array($index, $condition);
	}

	function get_namespace_cond() {
		if ($this->namespace !== false)
			return ' AND page_namespace = ' . (int)$this->namespace;
		return '';
	}

	function get_previous_offset_for_paging() {
		list($index, $usercond) = $this->get_user_cond();
		$nscond = $this->get_namespace_cond();

		$use_index = $this->dbr->useIndexClause($index);
		extract($this->dbr->tableNames('page', 'revision'));

		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND rev_timestamp > '" . $this->offset . "' AND " .
			$usercond . $nscond;
		$sql .=	" ORDER BY rev_timestamp ASC";
		$sql = $this->dbr->limitResult($sql, $this->limit, 0);
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
		$nscond = $this->get_namespace_cond();
		$sql =	"SELECT rev_timestamp FROM $page, $revision $use_index " .
			"WHERE page_id = rev_page AND " .
			$usercond . $nscond;
		$sql .=	" ORDER BY rev_timestamp ASC";
		$sql = $this->dbr->limitResult($sql, $this->limit, 0);
		$res = $this->dbr->query($sql);
		$rows = array();
		while ($obj = $this->dbr->fetchObject($res))
			$rows[] = $obj;
		$this->dbr->freeResult($res);
		return $rows[count($rows) - 1]->rev_timestamp;
	}

	/* private */ function make_sql() {
		$userCond = $condition = $index = $offsetQuery = '';

		extract($this->dbr->tableNames('page', 'revision'));
		list($index, $userCond) = $this->get_user_cond();

		if ($this->offset)
			$offsetQuery = "AND rev_timestamp <= '{$this->offset}'";

		$nscond = $this->get_namespace_cond();
		$use_index = $this->dbr->useIndexClause($index);
		$sql = "SELECT
			page_namespace,page_title,page_is_new,page_latest,
			rev_id,rev_page,rev_text_id,rev_timestamp,rev_comment,rev_minor_edit,rev_user,rev_user_text,
			rev_deleted
			FROM $page,$revision $use_index
			WHERE page_id=rev_page AND $userCond $nscond $offsetQuery
		 	ORDER BY rev_timestamp DESC";
		$sql = $this->dbr->limitResult($sql, $this->limit, 0);
		return $sql;
	}

	function find() {
		$contribs = array();
		$res = $this->dbr->query($this->make_sql(), 'contribs_finder::find');
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
	global $wgUser, $wgOut, $wgLang, $wgRequest;
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

	$options = array();

	list( $options['limit'], $options['offset']) = wfCheckLimits();
	$options['offset'] = $wgRequest->getVal('offset');
	/* Offset must be an integral. */
	if (!strlen($options['offset']) || !preg_match('/^[0-9]+$/', $options['offset']))
		$options['offset'] = '';

	$title = Title::makeTitle(NS_SPECIAL, 'Contributions');
	$options['target'] = $target;

	if (($ns = $wgRequest->getVal('namespace', null)) !== null && $ns !== '') {
		$options['namespace'] = $ns;
	} else {
		$options['namespace'] = '';
	}

	if ($wgUser->isAllowed('rollback') && $wgRequest->getBool( 'bot' )) {
		$options['bot'] = '1';
	}

	$nt =& Title::makeTitle(NS_USER, $nt->getDBkey());
	$finder = new contribs_finder(($target == 'newbies') ? 'newbies' : $nt->getText());
	$finder->set_limit($options['limit']);
	$finder->set_offset($options['offset']);
	$finder->set_namespace($options['namespace']);

	if ($wgRequest->getText('go') == 'prev') {
		$options['offset'] = $finder->get_previous_offset_for_paging();
		$prevurl = $title->getLocalURL(wfArrayToCGI( $options ));
		$wgOut->redirect($prevurl);
		return;
	}

	if ($wgRequest->getText('go') == 'first' && $target != 'newbies') {
		$options['offset'] = $finder->get_first_offset_for_paging();
		$prevurl = $title->getLocalURL(wfArrayToCGI( $options ));
		$wgOut->redirect($prevurl);
		return;
	}

	if ($target == 'newbies') {
		$wgOut->setSubtitle( wfMsgHtml( 'sp-contributions-newbies-sub') );
	} else {
		$wgOut->setSubtitle( wfMsgHtml( 'contribsub', contributionsSub($nt) ) );
	}

	$id = User::idFromName($nt->getText());
	wfRunHooks('SpecialContributionsBeforeMainOutput', $id );

	$wgOut->addHTML(contributionsForm($options));

	$contribs = $finder->find();

	if (count($contribs) == 0) {
		$wgOut->addWikiText( wfMsg( 'nocontribs' ) );
		return;
	}

	list($early, $late) = $finder->get_edit_limits();
	$lastts = count($contribs) ? $contribs[count($contribs) - 1]->rev_timestamp : 0;
	$atstart = (!count($contribs) || $late == $contribs[0]->rev_timestamp);
	$atend = (!count($contribs) || $early == $lastts);

	// These four are defaults
	$newestlink = wfMsgHtml('sp-contributions-newest');
	$oldestlink = wfMsgHtml('sp-contributions-oldest');
	$newerlink  = wfMsgHtml('sp-contributions-newer', $options['limit']);
	$olderlink  = wfMsgHtml('sp-contributions-older', $options['limit']);

	if (!$atstart) {
		$stuff = $title->escapeLocalURL(wfArrayToCGI(array('offset' => ''), $options));
		$newestlink = "<a href=\"$stuff\">$newestlink</a>";
		$stuff = $title->escapeLocalURL(wfArrayToCGI(array('go' => 'prev'), $options));
		$newerlink = "<a href=\"$stuff\">$newerlink</a>";
	}

	if (!$atend) {
		$stuff = $title->escapeLocalURL(wfArrayToCGI(array('go' => 'first'), $options));
		$oldestlink = "<a href=\"$stuff\">$oldestlink</a>";
		$stuff = $title->escapeLocalURL(wfArrayToCGI(array('offset' => $lastts), $options));
		$olderlink = "<a href=\"$stuff\">$olderlink</a>";
	}

	if ($target == 'newbies') {
		$firstlast ="($newestlink)";
	} else {
		$firstlast = "($newestlink | $oldestlink)";
	}

	$urls = array();
	foreach (array(20, 50, 100, 250, 500) as $num) {
		$stuff = $title->escapeLocalURL(wfArrayToCGI(array('limit' => $num), $options));
		$urls[] = "<a href=\"$stuff\">".$wgLang->formatNum($num)."</a>";
	}
	$bits = implode($urls, ' | ');

	$prevnextbits = $firstlast .' '. wfMsgHtml('viewprevnext', $newerlink, $olderlink, $bits);

	$wgOut->addHTML( "<p>{$prevnextbits}</p>\n");

	$wgOut->addHTML( "<ul>\n" );

	$sk = $wgUser->getSkin();
	foreach ($contribs as $contrib)
		$wgOut->addHTML(ucListEdit($sk, $contrib));

	$wgOut->addHTML( "</ul>\n" );
	$wgOut->addHTML( "<p>{$prevnextbits}</p>\n");
}

/**
 * Generates the subheading with links
 * @param object $nt title object for the target
 */
function contributionsSub( $nt ) {
	global $wgSysopUserBans, $wgLang, $wgUser;

	$sk = $wgUser->getSkin();
	$id = User::idFromName($nt->getText());

	if ( 0 == $id ) {
		$ul = $nt->getText();
	} else {
		$ul = $sk->makeLinkObj( $nt, htmlspecialchars( $nt->getText() ) );
	}
	$talk = $nt->getTalkPage();
	if( $talk ) {
		# Talk page link	
		$tools[] = $sk->makeLinkObj( $talk, $wgLang->getNsText( NS_TALK ) );
		if( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && User::isIP( $nt->getText() ) ) ) {
			# Block link
			if( $wgUser->isAllowed( 'block' ) )
				$tools[] = $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Blockip/' . $nt->getDBkey() ), wfMsgHtml( 'blocklink' ) );
			# Block log link
			$tools[] = $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Log' ), htmlspecialchars( LogPage::logName( 'block' ) ), 'type=block&page=' . $nt->getPrefixedUrl() );
		}
		# Other logs link
		$tools[] = $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Log' ), wfMsgHtml( 'log' ), 'user=' . $nt->getPartialUrl() );
		$ul .= ' (' . implode( ' | ', $tools ) . ')';
	}
	return $ul;
}

/**
 * Generates the namespace selector form with hidden attributes
 * @param array $options the options to be inluded
 */
function contributionsForm( $options ) {
	global $wgScript, $wgTitle;

	$options['title'] = $wgTitle->getPrefixedText();

	$f = "<form method='get' action=\"$wgScript\">\n";
	foreach ( $options as $name => $value ) {
		if( $name === 'namespace') continue;
		$f .= "\t" . wfElement('input', array(
			'name' => $name,
			'type' => 'hidden',
			'value' => $value)) . "\n";
	}

	$f .= '<p>' . wfMsgHtml('namespace') . ' ' .
	HTMLnamespaceselector( $options['namespace'], '' ) .
	wfElement('input', array(
			'type' => 'submit',
			'value' => wfMsg('allpagessubmit'))
	) .
	"</p></form>\n";

	return $f;
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

	global $wgLang, $wgUser, $wgRequest;
	static $messages;
	if( !isset( $messages ) ) {
		foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg ) {
			$messages[$msg] = wfMsg( $msg );
		}
	}

	$rev = new Revision( $row );
	
	$page = Title::makeTitle( $row->page_namespace, $row->page_title );
	$link = $sk->makeKnownLinkObj( $page );
	$difftext = $topmarktext = '';
	if( $row->rev_id == $row->page_latest ) {
		$topmarktext .= '<strong>' . $messages['uctop'] . '</strong>';
		if( !$row->page_is_new ) {
			$difftext .= '(' . $sk->makeKnownLinkObj( $page, $messages['diff'], 'diff=0' ) . ')';
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
	if( $rev->userCan( MW_REV_DELETED_TEXT ) ) {
		$difftext = '(' . $sk->makeKnownLinkObj( $page, $messages['diff'], 'diff=prev&oldid='.$row->rev_id ) . ')';
	} else {
		$difftext = '(' . $messages['diff'] . ')';
	}
	$histlink='('.$sk->makeKnownLinkObj( $page, $messages['hist'], 'action=history' ) . ')';

	$comment = $sk->revComment( $rev );
	$d = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->rev_timestamp), true );
	
	if( $rev->isDeleted( MW_REV_DELETED_TEXT ) ) {
		$d = '<span class="history-deleted">' . $d . '</span>';
	}

	if( $row->rev_minor_edit ) {
		$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
	} else {
		$mflag = '';
	}

	$ret = "{$d} {$histlink} {$difftext} {$mflag} {$link} {$comment} {$topmarktext}";
	if( $rev->isDeleted( MW_REV_DELETED_TEXT ) ) {
		$ret .= ' ' . wfMsgHtml( 'deletedrev' );
	}
	$ret = "<li>$ret</li>\n";
	wfProfileOut( $fname );
	return $ret;
}

?>