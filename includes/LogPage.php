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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
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
	/* @access private */
	var $type, $action, $comment, $params, $target;
	/* @acess public */
	var $updateRecentChanges;

	/**
	  * Constructor
	  *
	  * @param string $type One of '', 'block', 'protect', 'rights', 'delete',
	  *               'upload', 'move'
	  * @param bool $rc Whether to update recent changes as well as the logging table
	  */
	function LogPage( $type, $rc = true ) {
		$this->type = $type;
		$this->updateRecentChanges = $rc;
	}

	function saveContent() {
		if( wfReadOnly() ) return false;

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
			$titleObj = SpecialPage::getTitleFor( 'Log', $this->type );
			$rcComment = $this->actionText;
			if( '' != $this->comment ) {
				if ($rcComment == '')
					$rcComment = $this->comment;
				else
					$rcComment .= ': ' . $this->comment;
			}

			RecentChange::notifyLog( $now, $titleObj, $wgUser, $rcComment, '',
				$this->type, $this->action, $this->target, $this->comment, $this->params );
		}
		return true;
	}

	/**
	 * @static
	 */
	function validTypes() {
		global $wgLogTypes;
		return $wgLogTypes;
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
	public static function logName( $type ) {
		global $wgLogNames;

		if( isset( $wgLogNames[$type] ) ) {
			return str_replace( '_', ' ', wfMsg( $wgLogNames[$type] ) );
		} else {
			// Bogus log types? Perhaps an extension was removed.
			return $type;
		}
	}

	/**
	 * @fixme: handle missing log types
	 * @static
	 */
	function logHeader( $type ) {
		global $wgLogHeaders;
		return wfMsg( $wgLogHeaders[$type] );
	}

	/**
	 * @static
	 */
	function actionText( $type, $action, $title = NULL, $skin = NULL, $params = array(), $filterWikilinks=false, $translate=false ) {
		global $wgLang, $wgContLang, $wgLogActions;

		$key = "$type/$action";
		if( isset( $wgLogActions[$key] ) ) {
			if( is_null( $title ) ) {
				$rv=wfMsg( $wgLogActions[$key] );
			} else {
				if( $skin ) {

					switch( $type ) {
						case 'move':
							$titleLink = $skin->makeLinkObj( $title, $title->getPrefixedText(), 'redirect=no' );
							$params[0] = $skin->makeLinkObj( Title::newFromText( $params[0] ), $params[0] );
							break;
						case 'block':
							if( substr( $title->getText(), 0, 1 ) == '#' ) {
								$titleLink = $title->getText();
							} else {
								$titleLink = $skin->makeLinkObj( $title, $title->getText() );
								$titleLink .= ' (' . $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Contributions', $title->getDBkey() ), wfMsg( 'contribslink' ) ) . ')';
							}
							break;
						case 'rights':
							$text = $wgContLang->ucfirst( $title->getText() );
							$titleLink = $skin->makeLinkObj( Title::makeTitle( NS_USER, $text ) );
							break;
						default:
							$titleLink = $skin->makeLinkObj( $title );
					}

				} else {
					$titleLink = $title->getPrefixedText();
				}
				if( $key == 'rights/rights' ) {
					if ($skin) {
						$rightsnone = wfMsg( 'rightsnone' );
					} else {
						$rightsnone = wfMsgForContent( 'rightsnone' );
					}
					if( !isset( $params[0] ) || trim( $params[0] ) == '' )
						$params[0] = $rightsnone;
					if( !isset( $params[1] ) || trim( $params[1] ) == '' )
						$params[1] = $rightsnone;
				}
				if( count( $params ) == 0 ) {
					if ( $skin ) {
						$rv = wfMsg( $wgLogActions[$key], $titleLink );
					} else {
						$rv = wfMsgForContent( $wgLogActions[$key], $titleLink );
					}
				} else {
					array_unshift( $params, $titleLink );
					if ( $translate && $key == 'block/block' ) {
						$params[1] = $wgLang->translateBlockExpiry( $params[1] );
						$params[2] = isset( $params[2] )
										? self::formatBlockFlags( $params[2] )
										: '';
					}
					$rv = wfMsgReal( $wgLogActions[$key], $params, true, !$skin );
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
	function addEntry( $action, $target, $comment, $params = array() ) {
		if ( !is_array( $params ) ) {
			$params = array( $params );
		}

		$this->action = $action;
		$this->target = $target;
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
	
	/**
	 * Convert a comma-delimited list of block log flags
	 * into a more readable (and translated) form
	 *
	 * @param $flags Flags to format
	 * @return string
	 */
	public static function formatBlockFlags( $flags ) {
		$flags = explode( ',', trim( $flags ) );
		if( count( $flags ) > 0 ) {
			for( $i = 0; $i < count( $flags ); $i++ )
				$flags[$i] = self::formatBlockFlag( $flags[$i] );
			return '(' . implode( ', ', $flags ) . ')';
		} else {
			return '';
		}
	}
	
	/**
	 * Translate a block log flag if possible
	 *
	 * @param $flag Flag to translate
	 * @return string
	 */
	public static function formatBlockFlag( $flag ) {
		static $messages = array();
		if( !isset( $messages[$flag] ) ) {
			$k = 'block-log-flags-' . $flag;
			$msg = wfMsg( $k );
			$messages[$flag] = htmlspecialchars( wfEmptyMsg( $k, $msg ) ? $flag : $msg );
		}
		return $messages[$flag];
	}
	
}

?>
