<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialLog( $par = '' ) {
	global $wgRequest;
	$logReader =& new LogReader( $wgRequest );
	if( '' == $wgRequest->getVal( 'type' ) && !empty( $par ) ) {
		$logReader->limitType( $par );
	}
	$logViewer =& new LogViewer( $logReader );
	$logViewer->show();
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class LogReader {
	var $db, $joinClauses, $whereClauses;
	var $type = '', $user = '', $title = null;
	
	/**
	 * @param WebRequest $request For internal use use a FauxRequest object to pass arbitrary parameters.
	 */
	function LogReader( $request ) {
		$this->db =& wfGetDB( DB_SLAVE );
		$this->setupQuery( $request );
	}
	
	/**
	 * Basic setup and applies the limiting factors from the WebRequest object.
	 * @param WebRequest $request
	 * @private
	 */
	function setupQuery( $request ) {
		$cur = $this->db->tableName( 'cur' );
		$user = $this->db->tableName( 'user' );
		$this->joinClauses = array( "LEFT OUTER JOIN $cur ON log_namespace=cur_namespace AND log_title=cur_title" );
		$this->whereClauses = array( 'user_id=log_user' );
		
		$this->limitType( $request->getVal( 'type' ) );
		$this->limitUser( $request->getText( 'user' ) );
		$this->limitTitle( $request->getText( 'page' ) );
		$this->limitTime( $request->getVal( 'from' ), '>=' );
		$this->limitTime( $request->getVal( 'until' ), '<=' );
		
		list( $this->limit, $this->offset ) = $request->getLimitOffset();
	}
	
	/**
	 * Set the log reader to return only entries of the given type.
	 * @param string $type A log type ('upload', 'delete', etc)
	 * @private
	 */
	function limitType( $type ) {
		if( empty( $type ) ) {
			return false;
		}
		$this->type = $type;
		$safetype = $this->db->strencode( $type );
		$this->whereClauses[] = "log_type='$safetype'";
	}
	
	/**
	 * Set the log reader to return only entries by the given user.
	 * @param string $name Valid user name
	 * @private
	 */
	function limitUser( $name ) {
		$title = Title::makeTitle( NS_USER, $name );
		if( empty( $name ) || is_null( $title ) ) {
			return false;
		}
		$this->user = str_replace( '_', ' ', $title->getDBkey() );
		$safename = $this->db->strencode( $this->user );
		$user = $this->db->tableName( 'user' );
		$this->whereClauses[] = "user_name='$safename'";
	}
	
	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 * @param string $page Title name as text
	 * @private
	 */
	function limitTitle( $page ) {
		$title = Title::newFromText( $page );
		if( empty( $page ) || is_null( $title )  ) {
			return false;
		}
		$this->title =& $title;
		$safetitle = $this->db->strencode( $title->getDBkey() );
		$ns = $title->getNamespace();
		$this->whereClauses[] = "log_namespace=$ns AND log_title='$safetitle'";
	}
	
	/**
	 * Set the log reader to return only entries in a given time range.
	 * @param string $time Timestamp of one endpoint
	 * @param string $direction either ">=" or "<=" operators
	 * @private
	 */
	function limitTime( $time, $direction ) {
		# Direction should be a comparison operator
		if( empty( $time ) ) {
			return false;
		}
		$safetime = $this->db->strencode( wfTimestamp( TS_MW, $time ) );
		$this->whereClauses[] = "log_timestamp $direction '$safetime'";
	}
	
	/**
	 * Build an SQL query from all the set parameters.
	 * @return string the SQL query
	 * @private
	 */
	function getQuery() {
		$logging = $this->db->tableName( "logging" );
		$user = $this->db->tableName( 'user' );
		$sql = "SELECT log_type, log_action, log_timestamp,
			log_user, user_name,
			log_namespace, log_title, cur_id,
			log_comment FROM $user, $logging ";
		if( !empty( $this->joinClauses ) ) {
			$sql .= implode( ',', $this->joinClauses );
		}
		if( !empty( $this->whereClauses ) ) {
			$sql .= " WHERE " . implode( ' AND ', $this->whereClauses );
		}
		$sql .= " ORDER BY log_timestamp DESC ";
		$sql .= $this->db->limitResult( $this->limit, $this->offset );
		return $sql;
	}
	
	/**
	 * Execute the query and start returning results.
	 * @return ResultWrapper result object to return the relevant rows
	 */
	function getRows() {
		return $this->db->resultObject( $this->db->query( $this->getQuery() ) );
	}
	
	/**
	 * @return string The query type that this LogReader has been limited to.
	 */
	function queryType() {
		return $this->type;
	}
	
	/**
	 * @return string The username type that this LogReader has been limited to, if any.
	 */
	function queryUser() {
		return $this->user;
	}
	
	/**
	 * @return string The text of the title that this LogReader has been limited to.
	 */
	function queryTitle() {
		if( is_null( $this->title ) ) {
			return '';
		} else {
			return $this->title->getPrefixedText();
		}
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class LogViewer {
	/**
	 * @var LogReader $reader
	 */
	var $reader;
	
	/**
	 * @param LogReader &$reader where to get our data from
	 */
	function LogViewer( &$reader ) {
		global $wgUser;
		$this->skin =& $wgUser->getSkin();
		$this->reader =& $reader;
	}
	
	/**
	 * Take over the whole output page in $wgOut with the log display.
	 */
	function show() {
		global $wgOut;
		$this->showHeader( $wgOut );
		$this->showOptions( $wgOut );
		$this->showPrevNext( $wgOut );
		$this->showList( $wgOut );
		$this->showPrevNext( $wgOut );
	}
	
	/**
	 * Output just the list of entries given by the linked LogReader,
	 * with extraneous UI elements. Use for displaying log fragments in
	 * another page (eg at Special:Undelete)
	 * @param OutputPage $out where to send output
	 */
	function showList( &$out ) {
		$html = "";
		$result = $this->reader->getRows();
		while( $s = $result->fetchObject() ) {
			$html .= $this->logLine( $s );
		}
		$result->free();
		$out->addHTML( $html );
	}
	
	/**
	 * @param Object $s a single row from the result set
	 * @return string Formatted HTML list item
	 * @private
	 */
	function logLine( $s ) {
		global $wgLang;
		$title = Title::makeTitle( $s->log_namespace, $s->log_title );
		$user = Title::makeTitleSafe( NS_USER, $s->user_name );
		$time = $wgLang->timeanddate( $s->log_timestamp );
		if( $s->cur_id ) {
			$titleLink = $this->skin->makeKnownLinkObj( $title );
		} else {
			$titleLink = $this->skin->makeBrokenLinkObj( $title );
		}
		$userLink = $this->skin->makeLinkObj( $user, htmlspecialchars( $s->user_name ) );
		if( '' === $s->log_comment ) {
			$comment = '';
		} else {
			$comment = '(<em>' . $this->skin->formatComment( $s->log_comment ) . '</em>)';
		}
		
		$action = LogPage::actionText( $s->log_type, $s->log_action, $titleLink );
		$out = "<li>$time $userLink $action $comment</li>\n";
		return $out;
	}
	
	/**
	 * @param OutputPage &$out where to send output
	 * @private
	 */
	function showHeader( &$out ) {
		$type = $this->reader->queryType();
		if( LogPage::isLogType( $type ) ) {
			$out->setPageTitle( LogPage::logName( $type ) );
			$out->addWikiText( LogPage::logHeader( $type ) );
		}
	}
	
	/**
	 * @param OutputPage &$out where to send output
	 * @private
	 */
	function showOptions( &$out ) {
		global $wgScript;
		$action = htmlspecialchars( $wgScript );
		$title = Title::makeTitle( NS_SPECIAL, 'Log' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		$out->addHTML( "<form action=\"$action\" method=\"get\">\n" .
			"<input type='hidden' name='title' value=\"$special\" />\n" .
			$this->getTypeMenu() .
			$this->getUserInput() .
			$this->getTitleInput() .
			"<input type='submit' />" .
			"</form>" );
	}
	
	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getTypeMenu() {
		$out = "<select name='type'>\n";
		foreach( LogPage::validTypes() as $type ) {
			$text = htmlspecialchars( LogPage::logName( $type ) );
			$selected = ($type == $this->reader->queryType()) ? ' selected="selected"' : '';
			$out .= "<option value=\"$type\"$selected>$text</option>\n";
		}
		$out .= "</select>\n";
		return $out;
	}
	
	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getUserInput() {
		$user = htmlspecialchars( $this->reader->queryUser() );
		return wfMsg('specialloguserlabel') . "<input type='text' name='user' size='12' value=\"$user\" />\n";
	}
	
	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getTitleInput() {
		$title = htmlspecialchars( $this->reader->queryTitle() );
		return wfMsg('speciallogtitlelabel') . "<input type='text' name='page' size='20' value=\"$title\" />\n";
	}
	
	/**
	 * @param OutputPage &$out where to send output
	 * @private
	 */
	function showPrevNext( &$out ) {
		global $wgContLang;
		$pieces = array();
		$pieces[] = 'type=' . htmlspecialchars( $this->reader->queryType() );
		$pieces[] = 'user=' . htmlspecialchars( $this->reader->queryUser() );
		$pieces[] = 'page=' . htmlspecialchars( $this->reader->queryTitle() );
		$bits = implode( '&', $pieces );
		$offset = 0; $limit = 50;
		
		# TODO: use timestamps instead of offsets to make it more natural
		# to go huge distances in time
		$html = wfViewPrevNext( $offset, $limit,
			$wgContLang->specialpage( 'Log' ),
			$bits,
			false);
		$out->addHTML( '<p>' . $html . '</p>' );
	}
}


?>
