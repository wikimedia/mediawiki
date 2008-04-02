<?php
# Copyright (C) 2008 Aaron Schulz
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
 * @addtogroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialLog( $par = '' ) {
	global $wgRequest, $wgOut, $wgUser;
	# Get parameters
	$type = $wgRequest->getVal( 'type', $par );
	$user = $wgRequest->getText( 'user' );
	$title = $wgRequest->getText( 'page' );
	$pattern = $wgRequest->getBool( 'pattern' );
	# Create a LogPager item to get the results and a LogEventList
	# item to format them...
	$loglist = new LogEventList( $wgUser->getSkin() );
	$pager = new LogPager( $loglist, $type, $user, $title, $pattern );
	# Set title and add header
	$loglist->showHeader( $wgOut, $pager->getType() );
	# Show form options
	$loglist->showOptions( $wgOut, $pager->getType(), $pager->getUser(), $pager->getPage(), $pager->getPattern() );

	# Insert list
	$logBody = $pager->getBody();
	if( $logBody ) {
		$wgOut->addHTML(
			$pager->getNavigationBar() . 
			$loglist->beginLogEventList() .
			$logBody .
			$loglist->endLogEventList() .
			$pager->getNavigationBar()
		);
	} else {
		$wgOut->addWikiMsg( 'logempty' );
	}
}

/**
 * @addtogroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	var $type = '', $user = '', $title = '', $pattern = '';
	/**
	* constructor
	* @param LogEventList $loglist,
	* @param string $type,
	* @param string $user,
	* @param string $page,
	* @param string $pattern
	* @param array $conds
	*/
	function __construct( $loglist, $type, $user, $title, $pattern, $conds = array() ) {
		parent::__construct();
		$this->mConds = $conds;
		
		$this->mLogList = $loglist;
		
		$this->limitType( $type );
		$this->limitUser( $user );
		$this->limitTitle( $title, $pattern );
	}
	
	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 * @param string $type A log type ('upload', 'delete', etc)
	 * @private
	 */
	private function limitType( $type ) {
		// Don't show private logs to unpriviledged users
		$hideLogs = LogEventList::getExcludeClause( $this->mDb );
		if( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if( empty($type) ) {
			return false;
		}
		$this->type = $type;
		$this->mConds['log_type'] = $type;
	}
	
	/**
	 * Set the log reader to return only entries by the given user.
	 * @param string $name (In)valid user name
	 * @private
	 */
	function limitUser( $name ) {
		if( $name == '' ) {
			return false;
		}
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if( is_null($usertitle) ) {
			return false;
		}
		$this->user = $usertitle->getText();
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = User::idFromName( $this->user );
		if( !$userid ) {
			/* It should be nicer to abort query at all, 
			   but for now it won't pass anywhere behind the optimizer */
			$this->mConds[] = "NULL";
		} else {
			$this->mConds['log_user'] = $userid;
		}
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 * @param string $page Title name as text
	 * @private
	 */
	function limitTitle( $page, $pattern ) {
		global $wgMiserMode;
		
		$title = Title::newFromText( $page );
		if( strlen($page) == 0 || !$title instanceof Title )
			return false;
		
		$this->title = $title->getPrefixedText();
		$this->pattern = $pattern;
		$ns = $title->getNamespace();
		if( $pattern && !$wgMiserMode ) {
			# use escapeLike to avoid expensive search patterns like 't%st%'
			$safetitle = $this->mDb->escapeLike( $title->getDBkey() );
			$this->mConds['log_namespace'] = $ns;
			$this->mConds[] = "log_title LIKE '$safetitle%'";
		} else {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds['log_title'] = $title->getDBkey();
		}
	}

	function getQueryInfo() {
		$this->mConds[] = 'user_id = log_user';
		# Hack this until live
		global $wgAllowLogDeletion;
		$log_id = $wgAllowLogDeletion ? 'log_id' : '0 AS log_id';
		return array(
			'tables' => array( 'logging', 'user' ),
			'fields' => array( 'log_type', 'log_action', 'log_user', 'log_namespace', 'log_title', 
				'log_params', 'log_comment', $log_id, 'log_deleted', 'log_timestamp', 'user_name' ),
			'conds' => $this->mConds,
			'options' => array()
		);
	}

	function getIndexField() {
		return 'log_timestamp';
	}

	function formatRow( $row ) {
		return $this->mLogList->logLine( $row );
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function getPage() {
		return $this->title;
	}
	
	public function getPattern() {
		return $this->pattern;
	}
}
