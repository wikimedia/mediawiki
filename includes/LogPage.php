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
	/* private */ var $type, $action, $comment;
	var $updateRecentChanges = true;

	function LogPage( $type ) {
		# Type is one of 'block', 'protect', 'rights', 'delete', 'upload'
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
				'log_comment' => $this->comment
			), $fname
		);
		
		# And update recentchanges
		if ( $this->updateRecentChanges ) {
			$rcComment = $this->actionText;
			if( '' != $this->comment ) {
				$rcComment .= ': ' . $this->comment;
			}
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Log/' . $this->type );
			RecentChange::notifyLog( $now, $titleObj, $wgUser, $rcComment );
		}
		return true;
	}

	/**
	 * @static
	 */
	function validTypes() {
		static $types = array( '', 'block', 'protect', 'rights', 'delete', 'upload' );
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
			'upload' => array( 'upload' )
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
		);
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
			'upload'  => 'uploadlogpagetext'
		);
		return wfMsg( $headerText[$type] );
	}
	
	/**
	 * @static
	 */
	function actionText( $type, $action, $titleLink = NULL ) {
		static $actions = array(
			'block/block' => 'blocklogentry',
			'block/unblock' => 'unblocklogentry',
			'protect/protect' => 'protectedarticle',
			'protect/unprotect' => 'unprotectedarticle',
			'rights/rights' => 'bureaucratlogentry',
			'delete/delete' => 'deletedarticle',
			'delete/restore' => 'undeletedarticle',
			'upload/upload' => 'uploadedimage',
			'upload/revert' => 'uploadedimage',
		);
		$key = "$type/$action";
		if( isset( $actions[$key] ) ) {
			if( is_null( $titleLink ) ) {
				return wfMsg( $actions[$key] );
			} else {
				return wfMsg( $actions[$key], $titleLink );
			}
		} else {
			wfDebug( "LogPage::actionText - unknown action $key\n" );
			return "$action $titleLink";
		}
	}

	/**
	 * Add a log entry
	 * @param string $action one of 'block', 'protect', 'rights', 'delete', 'upload'
	 * @param &$target
	 * @param string $comment Description associated
	 */
	function addEntry( $action, &$target, $comment ) {
		global $wgLang, $wgUser;
		
		$this->action = $action;
		$this->target =& $target;
		$this->comment = $comment;
		$this->actionText = LogPage::actionText( $this->type, $action,
			$target->getPrefixedText() );
				
		return $this->saveContent();
	}
}

?>
