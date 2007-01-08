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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
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
	$logReader = new LogReader( $wgRequest );
	if( $wgRequest->getVal( 'type' ) == '' && $par != '' ) {
		$logReader->limitType( $par );
	}
	$logViewer = new LogViewer( $logReader );
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
		$page = $this->db->tableName( 'page' );
		$user = $this->db->tableName( 'user' );
		$this->joinClauses = array( 
			"LEFT OUTER JOIN $page ON log_namespace=page_namespace AND log_title=page_title",
			"INNER JOIN $user ON user_id=log_user" );
		$this->whereClauses = array();

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
	 * @param string $name (In)valid user name
	 * @private
	 */
	function limitUser( $name ) {
		if ( $name == '' )
			return false;
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if ( is_null( $usertitle ) )
			return false;
		$this->user = $usertitle->getText();
		
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = $this->db->selectField('user','user_id',array('user_name'=>$this->user));
		if (!$userid)
			/* It should be nicer to abort query at all, 
			   but for now it won't pass anywhere behind the optimizer */
			$this->whereClauses[] = "NULL";
		else
			$this->whereClauses[] = "log_user=$userid";
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
		$sql = "SELECT /*! STRAIGHT_JOIN */ log_type, log_action, log_timestamp,
			log_user, user_name,
			log_namespace, log_title, page_id,
			log_comment, log_params FROM $logging ";
		if( !empty( $this->joinClauses ) ) {
			$sql .= implode( ' ', $this->joinClauses );
		}
		if( !empty( $this->whereClauses ) ) {
			$sql .= " WHERE " . implode( ' AND ', $this->whereClauses );
		}
		$sql .= " ORDER BY log_timestamp DESC ";
		$sql = $this->db->limitResult($sql, $this->limit, $this->offset );
		return $sql;
	}

	/**
	 * Execute the query and start returning results.
	 * @return ResultWrapper result object to return the relevant rows
	 */
	function getRows() {
		$res = $this->db->query( $this->getQuery(), 'LogReader::getRows' );
		return $this->db->resultObject( $res );
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
	var $numResults = 0;

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
		$result = $this->getLogRows();
		$this->showPrevNext( $wgOut );
		$this->doShowList( $wgOut, $result );
		$this->showPrevNext( $wgOut );
	}

	/**
	 * Load the data from the linked LogReader
	 * Preload the link cache
	 * Initialise numResults
	 *
	 * Must be called before calling showPrevNext
	 *
	 * @return object database result set
	 */
	function getLogRows() {
		$result = $this->reader->getRows();
		$this->numResults = 0;

		// Fetch results and form a batch link existence query
		$batch = new LinkBatch;
		while ( $s = $result->fetchObject() ) {
			// User link
			$batch->addObj( Title::makeTitleSafe( NS_USER, $s->user_name ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $s->user_name ) );

			// Page the action was performed on
			$batch->addObj( Title::makeTitleSafe( $s->log_namespace, $s->log_title ) );

			// Move destination link
			if ( $s->log_type == 'move' ) {
				$paramArray = LogPage::extractParams( $s->log_params );
				$title = Title::newFromText( $paramArray[0] );
				$batch->addObj( $title );
			}
			++$this->numResults;
		}
		$batch->execute();

		return $result;
	}


	/**
	 * Output just the list of entries given by the linked LogReader,
	 * with extraneous UI elements. Use for displaying log fragments in
	 * another page (eg at Special:Undelete)
	 * @param OutputPage $out where to send output
	 */
	function showList( &$out ) {
		$this->doShowList( $out, $this->getLogRows() );
	}

	function doShowList( &$out, $result ) {
		// Rewind result pointer and go through it again, making the HTML
		if ($this->numResults > 0) {
			$html = "\n<ul>\n";
			$result->seek( 0 );
			while( $s = $result->fetchObject() ) {
				$html .= $this->logLine( $s );
			}
			$html .= "\n</ul>\n";
			$out->addHTML( $html );
		} else {
			$out->addWikiText( wfMsg( 'logempty' ) );
		}
		$result->free();
	}

	/**
	 * @param Object $s a single row from the result set
	 * @return string Formatted HTML list item
	 * @private
	 */
	function logLine( $s ) {
		global $wgLang;
		$title = Title::makeTitle( $s->log_namespace, $s->log_title );
		$time = $wgLang->timeanddate( wfTimestamp(TS_MW, $s->log_timestamp), true );

		// Enter the existence or non-existence of this page into the link cache,
		// for faster makeLinkObj() in LogPage::actionText()
		$linkCache =& LinkCache::singleton();
		if( $s->page_id ) {
			$linkCache->addGoodLinkObj( $s->page_id, $title );
		} else {
			$linkCache->addBadLinkObj( $title );
		}

		$userLink = $this->skin->userLink( $s->log_user, $s->user_name ) . $this->skin->userToolLinks( $s->log_user, $s->user_name );
		$comment = $this->skin->commentBlock( $s->log_comment );
		$paramArray = LogPage::extractParams( $s->log_params );
		$revert = '';
		if ( $s->log_type == 'move' && isset( $paramArray[0] ) ) {
			$specialTitle = SpecialPage::getTitleFor( 'Movepage' );
			$destTitle = Title::newFromText( $paramArray[0] );
			if ( $destTitle ) {
				$revert = '(' . $this->skin->makeKnownLinkObj( $specialTitle, wfMsg( 'revertmove' ),
					'wpOldTitle=' . urlencode( $destTitle->getPrefixedDBkey() ) .
					'&wpNewTitle=' . urlencode( $title->getPrefixedDBkey() ) .
					'&wpReason=' . urlencode( wfMsgForContent( 'revertmove' ) ) .
					'&wpMovetalk=0' ) . ')';
			}
		}

		$action = LogPage::actionText( $s->log_type, $s->log_action, $title, $this->skin, $paramArray, true, true );
		$out = "<li>$time $userLink $action $comment $revert</li>\n";
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
		$title = SpecialPage::getTitleFor( 'Log' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		$out->addHTML( "<form action=\"$action\" method=\"get\">\n" .
			"<input type='hidden' name='title' value=\"$special\" />\n" .
			$this->getTypeMenu() .
			$this->getUserInput() .
			$this->getTitleInput() .
			"<input type='submit' value=\"" . wfMsg( 'allpagessubmit' ) . "\" />" .
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
		global $wgContLang,$wgRequest;
		$pieces = array();
		$pieces[] = 'type=' . urlencode( $this->reader->queryType() );
		$pieces[] = 'user=' . urlencode( $this->reader->queryUser() );
		$pieces[] = 'page=' . urlencode( $this->reader->queryTitle() );
		$bits = implode( '&', $pieces );
		list( $limit, $offset ) = $wgRequest->getLimitOffset();

		# TODO: use timestamps instead of offsets to make it more natural
		# to go huge distances in time
		$html = wfViewPrevNext( $offset, $limit,
			$wgContLang->specialpage( 'Log' ),
			$bits,
			$this->numResults < $limit);
		$out->addHTML( '<p>' . $html . '</p>' );
	}
}


?>
