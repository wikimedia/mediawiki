<?php
#
# Copyright (C) 2002, 2004 Brion Vibber <brion@pobox.com>
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
 * Contain log classes
 *
 * @package MediaWiki
 */

/**
 * Class to simplify the use of log pages.
 * The logs are now kept in a table which is easier to manage and trim
 * than ever-growing wiki pages.
 *
 * @package MediaWiki
 */
class LogPage {
	/* private */ var $type, $action, $comment, $params, $target;
	var $updateRecentChanges = true;

	/**
	  * Constructor
	  *
	  * @param string $type One of '', 'block', 'protect', 'rights', 'delete',
	  *               'upload', 'move'
	  */
	function LogPage( $type ) {
		$this->type = $type;
	}

	function saveContent() {
		if( wfReadOnly() ) return;

		global $wgUser;
		$fname = 'LogPage::saveContent';

		$dbw =& wfGetDB( DB_MASTER );
		$uid = $wgUser->getID();

		$this->timestamp = $now = wfTimestampNow();
		$dbw->insert( 'logging',
			array(
				'log_type' => $this->type,
				'log_action' => $this->action,
				'log_timestamp' => $dbw->timestamp( $now ),
				'log_user' => $uid,
				'log_namespace' => $this->target->getNamespace(),
				'log_title' => $this->target->getDBkey(),
				'log_comment' => $this->comment,
				'log_params' => $this->params
			), $fname
		);
		
		# And update recentchanges
		if ( $this->updateRecentChanges ) {
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Log/' . $this->type );
			$rcComment = $this->actionText;
			if( '' != $this->comment ) {
				if ($rcComment == '')
					$rcComment = $this->comment;
				else
					$rcComment .= ': ' . $this->comment;
			}
			
			RecentChange::notifyLog( $now, $titleObj, $wgUser, $rcComment );
		}
		return true;
	}

	/**
	 * @static
	 */
	function validTypes() {
		static $types = array( '', 'block', 'protect', 'rights', 'delete', 'upload', 'move' );
		wfRunHooks( 'LogPageValidTypes', array( &$types) );
		return $types;
	}
	
	/**
	 * @static
	 */
	function validActions( $type ) {
		static $actions = array(
			'' => NULL,
			'block' => array( 'block', 'unblock' ),
			'protect' => array( 'protect', 'unprotect' ),
			'rights' => array( 'rights' ),
			'delete' => array( 'delete', 'restore' ),
			'upload' => array( 'upload' ),
			'move' => array( 'move' )
		);
		return $actions[$type];
	}
	
	/**
	 * @static
	 */
	function isLogType( $type ) {
		return in_array( $type, LogPage::validTypes() );
	}
	
	/**
	 * @static
	 */
	function logName( $type ) {
		static $typeText = array(
			''        => 'log',
			'block'   => 'blocklogpage',
			'protect' => 'protectlogpage',
			'rights'  => 'bureaucratlog',
			'delete'  => 'dellogpage',
			'upload'  => 'uploadlogpage',
			'move'    => 'movelogpage'
		);
		wfRunHooks( 'LogPageLogName', array( &$typeText) );
		
		return str_replace( '_', ' ', wfMsg( $typeText[$type] ) );
	}
	
	/**
	 * @static
	 */
	function logHeader( $type ) {
		static $headerText = array(
			''        => 'alllogstext',
			'block'   => 'blocklogtext',
			'protect' => 'protectlogtext',
			'rights'  => 'rightslogtext',
			'delete'  => 'dellogpagetext',
			'upload'  => 'uploadlogpagetext',
			'move'    => 'movelogpagetext'
		);
		wfRunHooks( 'LogPageLogHeader', array( &$headerText ) );
		
		return wfMsg( $headerText[$type] );
	}
	
	/**
	 * @static
	 */
	function actionText( $type, $action, $title = NULL, $skin = NULL, $params = array(), $filterWikilinks=false ) {
		static $actions = array(
			'block/block'       => 'blocklogentry',
			'block/unblock'     => 'unblocklogentry',
			'protect/protect'   => 'protectedarticle',
			'protect/unprotect' => 'unprotectedarticle',
			'rights/rights'     => 'bureaucratlogentry',
			'rights/addgroup'   => 'addgrouplogentry',
			'rights/rngroup'    => 'renamegrouplogentry',
			'rights/chgroup'    => 'changegrouplogentry',
			'delete/delete'     => 'deletedarticle',
			'delete/restore'    => 'undeletedarticle',
			'upload/upload'     => 'uploadedimage',
			'upload/revert'     => 'uploadedimage',
			'move/move'         => '1movedto2',
			'move/move_redir'   => '1movedto2_redir'
		);
		$key = "$type/$action";
		if( isset( $actions[$key] ) ) {
			if( is_null( $title ) ) {
				$rv=wfMsgForContent( $actions[$key] );
			} else {
				if( $skin ) {
					if ( $type == 'move' ) {
						$titleLink = $skin->makeLinkObj( $title, $title->getPrefixedText(), 'redirect=no' );
						// Change $param[0] into a link to the title specified in $param[0]
						$movedTo = Title::newFromText( $params[0] );
						$params[0] = $skin->makeLinkObj( $movedTo, $params[0] );
					} else {
						$titleLink = $skin->makeLinkObj( $title );
					}
				} else {
					$titleLink = $title->getPrefixedText();
				}
				if( count( $params ) == 0 ) {
					$rv = wfMsgForContent( $actions[$key], $titleLink );
				} else {
					array_unshift( $params, $titleLink );
					$rv = wfMsgReal( $actions[$key], $params, true, true );
				}
			}
		} else {
			wfDebug( "LogPage::actionText - unknown action $key\n" );
			$rv = "$action";
		}
		if( $filterWikilinks ) {
			$rv = str_replace( "[[", "", $rv );
			$rv = str_replace( "]]", "", $rv );
		}
		return $rv;
	}

	/**
	 * Add a log entry
	 * @param string $action one of '', 'block', 'protect', 'rights', 'delete', 'upload', 'move', 'move_redir'
	 * @param object &$target A title object.
	 * @param string $comment Description associated
	 * @param array $params Parameters passed later to wfMsg.* functions
	 */
	function addEntry( $action, &$target, $comment, $params = array() ) {
		if ( !is_array( $params ) ) {
			$params = array( $params );
		}
		
		$this->action = $action;
		$this->target =& $target;
		$this->comment = $comment;
		$this->params = LogPage::makeParamBlob( $params );
		
		$this->actionText = LogPage::actionText( $this->type, $action, $target, NULL, $params );

		return $this->saveContent();
	}

	/** 
	 * Create a blob from a parameter array
	 * @static
	 */
	function makeParamBlob( $params ) {
		return implode( "\n", $params );
	}

	/**
	 * Extract a parameter array from a blob
	 * @static
	 */
	function extractParams( $blob ) {
		if ( $blob === '' ) {
			return array();
		} else {
			return explode( "\n", $blob );
		}
	}
}

?>
